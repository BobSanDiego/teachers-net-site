"""Pure test-domain core for the WordPress-native Community publisher."""
from __future__ import annotations
from copy import deepcopy
from dataclasses import dataclass, field, replace
from datetime import datetime, timezone
from typing import Any
import hashlib

MAX_TITLE, MAX_BODY = 200, 10000
STATES = {"draft", "validated", "published", "pending", "hidden", "moderated", "spam", "retracted", "deleted", "restored", "failed"}
TRANSITIONS = {"draft":{"validated","failed"},"validated":{"published","pending"},"pending":{"published","hidden","spam","failed"},"published":{"hidden","moderated","spam","retracted","deleted"},"hidden":{"published","spam","deleted","restored"},"moderated":{"published","spam","deleted","restored"},"spam":{"restored","deleted"},"retracted":{"restored","deleted"},"deleted":{"restored"},"restored":{"published"}}

def _copy(x): return deepcopy(x)
def _now(): return "2026-07-31T00:00:00+00:00"
def _id(prefix, key): return f"{prefix}:{hashlib.sha256(key.encode()).hexdigest()[:16]}"

@dataclass(frozen=True)
class CommunityPostDraft:
    submission_id: str; community_id: str; author_id: str; post_type: str; title: str; body: str
    parent_post_id: str|None = None; thread_id: str|None = None; visibility: str = "public"
    publication_mode: str = "post_first"; created_at: str = field(default_factory=_now)
    display_policy: str = "authenticated"; compatibility_refs: dict[str,Any] = field(default_factory=dict); audit_context: dict[str,Any] = field(default_factory=dict)

@dataclass(frozen=True)
class CommunityPost:
    post_id: str; community_id: str; author_id: str; thread_id: str; parent_post_id: str|None; post_type: str
    title: str; body: str; visibility: str; moderation_state: str; publication_state: str; created_at: str; published_at: str|None
    idempotency_key: str; revision: int = 1; safe_target: str = "community-post"; compatibility_refs: dict[str,Any] = field(default_factory=dict); audit_metadata: dict[str,Any] = field(default_factory=dict)

@dataclass(frozen=True)
class ValidationResult:
    valid: bool; reason_codes: tuple[str,...] = (); parent_thread_id: str|None = None

@dataclass(frozen=True)
class ModerationResult:
    classification: str; state: str; reason_code: str; evidence: dict[str,Any] = field(default_factory=dict)

@dataclass(frozen=True)
class LifecycleTransition:
    previous_state: str; new_state: str; actor: str; reason: str; visibility_effect: str; notification_effect: str; reversible: bool; audit: dict[str,Any]; reason_code: str

@dataclass(frozen=True)
class CommunityPublicationEvent:
    event_id: str; event_type: str; post_id: str; community_id: str; thread_id: str; parent_post_id: str|None; author_id: str; display_policy: str; publication_state: str; visibility: str; moderation_state: str; created_at: str; published_at: str; safe_target: str; idempotency_key: str; revision: int; compatibility_refs: dict[str,Any] = field(default_factory=dict)

@dataclass(frozen=True)
class PublicationResult:
    accepted: bool; post: CommunityPost|None = None; event: CommunityPublicationEvent|None = None; validation: ValidationResult|None = None; moderation: ModerationResult|None = None; reason_code: str = ""

class PublisherDomain:
    def __init__(self): self._posts={}; self._submissions={}
    def validate(self, draft: CommunityPostDraft, communities: dict[str,Any], moderation_input: str="clear") -> ValidationResult:
        reasons=[]; parent=None
        if not draft.community_id or draft.community_id not in communities: reasons.append("COMMUNITY_UNRESOLVED")
        if not draft.author_id: reasons.append("AUTHENTICATED_AUTHOR_REQUIRED")
        if draft.post_type not in {"topic","reply"}: reasons.append("POST_TYPE_UNSUPPORTED")
        if draft.post_type == "topic" and draft.parent_post_id: reasons.append("ROOT_TOPIC_PARENT_FORBIDDEN")
        if draft.post_type == "topic" and not draft.title.strip(): reasons.append("TITLE_REQUIRED")
        if not draft.body.strip(): reasons.append("BODY_REQUIRED")
        if len(draft.title)>MAX_TITLE: reasons.append("TITLE_TOO_LONG")
        if len(draft.body)>MAX_BODY: reasons.append("BODY_TOO_LONG")
        if draft.visibility not in {"public","members","private"}: reasons.append("VISIBILITY_UNSUPPORTED")
        if draft.publication_mode not in {"post_first","pending"}: reasons.append("PUBLICATION_MODE_UNSUPPORTED")
        if draft.post_type == "reply":
            if not draft.parent_post_id: reasons.append("REPLY_PARENT_REQUIRED")
            elif draft.parent_post_id not in self._posts: reasons.append("PARENT_NOT_FOUND")
            else:
                parent=self._posts[draft.parent_post_id]
                if parent.community_id != draft.community_id: reasons.append("PARENT_COMMUNITY_MISMATCH")
                if parent.publication_state in {"hidden","spam","retracted","deleted"}: reasons.append("PARENT_RESTRICTED")
                if parent.audit_metadata.get("thread_locked"): reasons.append("THREAD_LOCKED")
                if draft.thread_id and draft.thread_id != parent.thread_id: reasons.append("THREAD_MISMATCH")
        if moderation_input not in {"clear","flagged","spam","hidden","moderator_hold"}: reasons.append("MODERATION_INPUT_UNSUPPORTED")
        return ValidationResult(not reasons, tuple(reasons), parent.thread_id if parent else None)
    def moderate(self, value: str) -> ModerationResult:
        mapping={"clear":("clear","published","MODERATION_CLEAR"),"flagged":("flagged","published","MODERATION_FLAGGED"),"spam":("spam","spam","MODERATION_SPAM"),"hidden":("hidden","hidden","MODERATION_HIDDEN"),"moderator_hold":("moderator_hold","pending","MODERATION_HOLD")}
        c,state,code=mapping.get(value,("unknown","failed","MODERATION_INPUT_UNSUPPORTED")); return ModerationResult(c,state,code,{"synthetic":True})
    def publish(self, draft: CommunityPostDraft, communities: dict[str,Any], moderation_input="clear", fail_event=False) -> PublicationResult:
        if draft.submission_id in self._submissions:
            old=self._submissions[draft.submission_id]
            if old[0] != draft: return PublicationResult(False, validation=ValidationResult(False,("IDEMPOTENCY_CONFLICT",)), reason_code="IDEMPOTENCY_CONFLICT")
            return old[1]
        validation=self.validate(draft,communities,moderation_input)
        if not validation.valid: result=PublicationResult(False,validation=validation,reason_code=validation.reason_codes[0]); self._submissions[draft.submission_id]=(draft,result); return result
        mod=self.moderate(moderation_input); state="pending" if draft.publication_mode=="pending" or mod.state=="pending" else mod.state
        if state=="published": state="published"
        post_id=_id("post",draft.submission_id); thread=validation.parent_thread_id or draft.thread_id or _id("thread",draft.submission_id); published=_now() if state=="published" else None
        post=CommunityPost(post_id,draft.community_id,draft.author_id,thread,draft.parent_post_id,draft.post_type,draft.title.strip(),draft.body.strip(),draft.visibility,mod.classification,state,draft.created_at,published,draft.submission_id,1,"community-post",_copy(draft.compatibility_refs),_copy(draft.audit_context))
        event=None
        if state=="published":
            if fail_event: result=PublicationResult(True,post=post,moderation=mod,reason_code="EVENT_CONSTRUCTION_FAILED"); self._posts[post_id]=post; self._submissions[draft.submission_id]=(draft,result); return result
            event=CommunityPublicationEvent(_id("event",post_id),"community.post.published",post.post_id,post.community_id,post.thread_id,post.parent_post_id,post.author_id,draft.display_policy,post.publication_state,post.visibility,post.moderation_state,post.created_at,post.published_at,post.safe_target,post.idempotency_key,post.revision,_copy(post.compatibility_refs))
        self._posts[post_id]=post; result=PublicationResult(True,post,event,validation,mod); self._submissions[draft.submission_id]=(draft,result); return result
    def transition(self, post_id, new_state, actor, reason) -> LifecycleTransition:
        post=self._posts[post_id]
        if new_state not in TRANSITIONS.get(post.publication_state,set()): return LifecycleTransition(post.publication_state,post.publication_state,actor,reason,"unchanged","none",False,{"accepted":False},"LIFECYCLE_TRANSITION_INVALID")
        reversible=new_state not in {"deleted"}; updated=replace(post,publication_state=new_state,published_at=_now() if new_state=="published" else post.published_at); self._posts[post_id]=updated
        return LifecycleTransition(post.publication_state,new_state,actor,reason,"visible" if new_state in {"published","restored"} else "restricted","post_commit_event" if new_state=="published" else "moderation_audit",reversible,{"accepted":True,"post_id":post_id},"LIFECYCLE_TRANSITION_ACCEPTED")
