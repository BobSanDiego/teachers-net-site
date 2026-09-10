"""Fixture-backed Community group-post adapter; no live hooks or I/O."""
from __future__ import annotations
from typing import Any

class GroupPostEventAdapter:
    @staticmethod
    def adapt(source: dict[str, Any], recipient: dict[str, Any], policy: dict[str, Any]) -> dict[str, Any]:
        required={"post_id","author_id","path_id","local_path","group_id","mapping_evidence","publication_state","moderation_state","visibility_state","group_privacy","created_at","event_family","content_ref"}
        if not isinstance(source,dict) or not required <= source.keys(): raise ValueError("malformed group-post source")
        if source["event_family"] != "group_post": raise ValueError("unsupported source event family")
        if not source["mapping_evidence"] or int(source["path_id"]) == int(source["group_id"]): raise ValueError("explicit distinct path/group mapping is required")
        if not isinstance(recipient,dict) or not {"recipient_id","authenticated","current_member","group_access","self_event"} <= recipient.keys(): raise ValueError("malformed recipient context")
        if not isinstance(policy,dict) or not {"frequency","category_enabled","bell_enabled","email_paused","group_mute","suppressed","kill_switch"} <= policy.keys(): raise ValueError("malformed policy context")
        reasons=[]; decision="eligible"
        if recipient["self_event"]: decision="ineligible"; reasons.append("self_event")
        elif source["publication_state"] != "published" or source["moderation_state"] != "clear" or source["visibility_state"] != "visible": decision="blocked"; reasons.append("source_not_visible")
        elif source["group_privacy"] == "private" and not recipient["group_access"]: decision="blocked"; reasons.append("group_access_denied")
        elif not recipient["authenticated"] or not recipient["current_member"] or not recipient["group_access"]: decision="ineligible"; reasons.append("recipient_not_current_member")
        elif policy["frequency"] == "never": decision="ineligible"; reasons.append("frequency_never")
        elif not policy["category_enabled"] or policy["suppressed"]: decision="ineligible"; reasons.append("policy_ineligible")
        return {"event_id":f"group-post:{source['post_id']}:recipient:{recipient['recipient_id']}","recipient_id":str(recipient["recipient_id"]),"path_id":int(source["path_id"]),"group_id":int(source["group_id"]),"event_family":"group_post","decision":decision,"reason_codes":tuple(reasons),"visibility":source["visibility_state"],"email_paused":bool(policy["email_paused"]),"bell_enabled":bool(policy["bell_enabled"] and not policy["group_mute"] and not policy["kill_switch"]),"created_at":source["created_at"],"content_ref":source["content_ref"]}
