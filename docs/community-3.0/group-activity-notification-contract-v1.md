# Community 3.0 Group Activity Notification Contract v1

Status: documentation and read-only evidence only. This contract defines policy
for group-activity notification candidates. It does not implement notifications,
create bell records, send mail, modify schema, implement queues, or change
production.

## 1. Purpose and scope

This contract defines how an authorized Community group-activity event may
become a notification candidate, how recipients are evaluated, and how
membership, frequency, visibility, suppression, moderation, bell, digest,
optional email, grouping, and audit remain distinct. It complements the Domain
Event and Notification Contract, Subscriber Policy Contract, Suppression and
Abuse-Control Contract, Bell and Read-State Contract, Reply Notification
Contract, and Reaction Notification Contract.

Group activity is a Community-owned fact. A group event is not a notification;
a notification is not eligibility; eligibility is not delivery; delivery is not
reading or engagement. `path_id` remains chatboard/path/feed identity and
`group_id` remains teacher-group identity. An explicit mapping is required when
an event carries both.

## 2. Group activity event model

A group-activity event records an authorized action in a teacher group or a
mapped Community context. Its immutable identity includes producer, actor,
activity type, target, group and path context, visibility basis, moderation
state, occurrence time, and audit provenance. Activity types include a new
group post, reply, announcement, membership change, moderation action, and
other explicitly approved group events.

The event carries facts and context, not a send instruction. Candidate
generation is a later policy evaluation. A membership change may affect future
eligibility but does not itself establish consent to receive all group
activity. A group announcement is still subject to visibility, category,
frequency, suppression, and moderation rules.

## 3. Eligible recipients

Potential recipients are evaluated separately and may include:

- an authenticated member with current access to the relevant group;
- a member with an explicit group-activity subscription or approved category
  policy;
- a group moderator or operator for an authorized administrative notice;
- an affected member for a permitted membership, safety, or account notice;
- a participant explicitly targeted by a separately approved mention or reply
  policy.

Group membership alone is not consent. A former member is not eligible for new
ordinary group activity merely because they previously participated. A member
who joins is not retroactively eligible for historical activity unless a later
policy explicitly defines a safe digest window. The actor is not notified of
their own activity by default.

## 4. Membership and frequency interaction

Membership establishes access context, not communication permission. The
evaluator resolves current membership, group status, role, leave time, blocks,
and any explicit subscription independently from channel and frequency.

Supported frequency outcomes are immediate, daily digest, weekly digest, and
never. Immediate means a candidate may be evaluated for prompt presentation;
it does not bypass suppression or delivery controls. Daily and weekly mean
eligible candidates may be held for the approved digest window. Never blocks
ordinary group-activity notices for that recipient and category, while
separately authorized account, security, or safety notices remain distinct.

Joining enables evaluation only for activity within the member's authorized
scope. Leaving prevents future ordinary group activity candidates and causes
pending items to be re-evaluated; it does not erase event or audit history.
Membership changes must not be used to infer promotional consent.

## 5. Visibility and authorization

Visibility and authorization precede candidate generation. The evaluator must
consider group privacy, member access, post/reply visibility, moderation state,
author and recipient blocks, anonymous or restricted content, retraction,
account restrictions, and the explicit `path_id` to `group_id` mapping.

Private-group activity must not expose group name, actor, content, or timing to
an unauthorized recipient. If access changes after candidate creation, the
candidate is re-evaluated and may become blocked, expired, retracted, or a
safe-summary administrative notice. Portable Views, Core Terms, relationships,
interests, onboarding, recommendation, and cross-product relevance do not
expand visibility or create group-notification consent.

## 6. Bell behavior

An eligible group-activity candidate may produce a recipient-specific Community
bell item in a later implementation. It begins unread only when policy requires
acknowledgement. Opening the authorized group context may mark the represented
bell item read; it does not prove that the activity was read in full or that
the member engaged with it.

Bell-only eligibility is valid. A bell item must retain category, group/path
context, visibility basis, event identity, and read/archive/retraction state.
Leaving a group, losing authorization, moderation, or retraction may remove an
item from active presentation while preserving audit evidence.

## 7. Optional email behavior

Email is a separate optional channel. It requires the relevant category and
channel policy, valid recipient consent or approved transactional basis,
current visibility, absence of pause/unsubscribe/suppression, a usable address,
and dedupe/throttle resolution. Bell eligibility does not imply email
eligibility, and email eligibility does not imply delivery.

A member with paused optional email may remain bell-eligible. A group muted in
the email channel may still permit a bell or an unrelated authorized
communication. Group activity email must not silently contain promotional
material or use inferred interest as a substitute for consent.

## 8. Digest behavior

Daily and weekly digest candidates are held only for recipients whose category,
channel, and frequency policy permits that window. Digest construction preserves
group, path, visibility, actor/target basis, and every included event ID. An
immediate setting is not silently downgraded to digest, and never is not
converted to a digest for convenience.

Digest inclusion is not delivery, reading, or engagement. Omitted, expired,
blocked, or superseded candidates remain explainable with a reason such as
frequency, dedupe, suppression, access change, moderation, or expiry.

## 9. Grouping and coalescing

Deduplication uses stable event, recipient, group, category, channel, and
policy scope. Similar text is not sufficient to collapse distinct events.
Compatible activity may be coalesced for one recipient and group, preserving
all event IDs, earliest/latest times, activity types, visibility basis, and
recipient scope.

Private and public activity, unrelated groups, different products, and
different authorization bases must not be coalesced. A bell grouping decision
does not determine email or digest grouping. Read, archive, email delivery,
digest inclusion, and engagement remain independent states.

## 10. Suppression interaction

Suppression and abuse controls are evaluated before final eligibility. Global
unsubscribe, category/channel suppression, user pause, hard bounce, complaint,
legal/privacy restriction, abuse or safety restriction, administrative block,
and a communication kill switch may block email, bell, or both according to
their declared scope.

The decision records authority, scope, reason, effective time, and audit
reference. Suppression is not a preference and cannot be cleared by merely
restoring a group-frequency setting. A candidate is re-evaluated when a
suppression, access, visibility, or moderation state changes.

## 11. Moderation interaction

Moderation, visibility, candidate eligibility, and delivery remain distinct. A
post or reply hidden as spam, unsafe, or pending review produces no ordinary
content notice to an unauthorized recipient. A permitted moderator or affected
member notice is evaluated as a separate administrative or safety category.

Restoration, removal, correction, and appeal change future evaluation under
current policy but do not erase the original activity event or audit evidence.
Promotion or editorial surfacing does not notify every group member. A muted,
restricted, or disabled account may lose ordinary activity eligibility while
necessary account or safety notices remain separately evaluated.

## 12. Audit requirements

Audit must explain each candidate and non-candidate by recording event ID,
actor, activity type, target, group/path context, mapping evidence, membership
and role basis, visibility result, recipient basis, category/channel,
frequency, consent/pause/unsubscribe state, suppression inputs,
dedupe/coalescing, moderation state, access changes, read/archive/retraction
state, expiry, and any later delivery reference.

Audit is recipient- and privacy-scoped and append-oriented. It must distinguish
event creation, candidate generation, eligibility, bell presentation, digest
inclusion, email attempt, delivery result, read state, archive, and engagement.
Retention and deletion periods remain separate governance decisions.

## 13. Legacy evidence census

| Evidence area | Classification | Contract conclusion |
|---|---|---|
| Legacy group email-frequency controls | Partial/legacy | Preference evidence only; not event or delivery authority. |
| `tnet_memberships` membership data | Partial/legacy | Membership/access evidence; not consent to every channel. |
| BuddyPress or WordPress notification facilities | Legacy/unknown authority | Must not be silently combined with Community policy. |
| Local Community event/bell runtime | Not established by this ticket | No implementation authority may be inferred. |
| WordPress mail functions | Platform capability | Do not establish eligibility, consent, or delivery. |
| Job Center or other product notifications | Separate product evidence | Not Community group-activity authority and not absorbed. |

No notification, bell record, email, queue, schema, preference, membership,
production, provider, or delivery state was changed.

## 14. Cross-product isolation

This contract is Community-scoped. Job Center, Lesson Bank, CE, Marketplace,
and future products retain their own facts, group semantics, recipients,
categories, and notification policies. Shared WordPress identity, Core Terms,
Portable Views, relationships, interests, or onboarding signals do not create
cross-product group-activity consent or notification candidates.

`path_id` and `group_id` remain distinct. A chatboard may map to a teacher group,
but the mapping is explicit and auditable; equality must never be assumed.

## 15. Decision examples

- A new visible group post may create immediate, daily, or weekly candidates
  only for currently eligible members under their selected frequency.
- A new group reply follows the reply contract and does not notify every group
  member by default.
- A group announcement may use a distinct approved category; importance does
  not bypass suppression or private-group authorization.
- Joining enables future evaluation; leaving blocks future ordinary activity and
  re-evaluates pending items without deleting audit history.
- A muted group blocks the muted channel/category while leaving other permitted
  outcomes intact.
- Immediate, daily, weekly, and never remain distinct; never is not a delayed
  digest.
- Paused optional email can produce a bell-only outcome.
- A private-group event is not exposed to a non-member or former member.
- Moderated or retracted content produces no ordinary notice when current
  visibility does not permit it.
- Multiple compatible posts may coalesce while preserving each event ID and
  group scope.

## 16. Open decisions

| Item | Classification |
|---|---|
| Default recipient scope for ordinary group posts | Engineering Director decision |
| Whether announcements have a separate transactional category | Engineering Director decision / external research may be required |
| Exact mute, digest, expiry, and coalescing windows | Engineering Director decision |
| Legacy group-frequency and BuddyPress source reconciliation | Verified production evidence required |
| Private-group safe-summary rules | Engineering Director decision / implementation detail deferred |
| Membership-change notice policy | Engineering Director decision |
| Cross-product group or relationship notifications | Explicit product authority required; no inference |

No broad external research was performed. This contract authorizes no
implementation or delivery work.

## 17. Acceptance criteria

Later implementation must demonstrate:

1. New post, reply, announcement, join, leave, muted, immediate, daily,
   weekly, never, paused-email, bell-only, private, moderated, and coalesced
   scenarios behave as specified.
2. Event, candidate, eligibility, bell, email, digest, delivery, read, and
   engagement states remain distinct.
3. Membership is not treated as consent, and visibility, authorization,
   suppression, moderation, and current-state re-evaluation are enforced.
4. `path_id` and `group_id` remain distinct with explicit mapping evidence.
5. Dedupe and coalescing preserve event IDs, recipient basis, channel scope,
   and auditability.
6. Cross-product isolation prevents Job Center or other product behavior from
   becoming Community group-notification authority.
7. Audit explains every candidate, block, grouping, expiry, read/archive,
   moderation, and restoration decision.
8. Verification and rollback/stop conditions are recorded without production
   writes, schema changes, queues, or unsolicited mail.

This document authorizes no implementation. The next decision is whether the
Engineering Director authorizes the next bounded notification contract ticket.
