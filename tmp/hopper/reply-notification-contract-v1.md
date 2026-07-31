# Community 3.0 Reply Notification Contract v1

Status: documentation and read-only evidence only. This contract defines reply-notification policy; it does not implement notifications, create bell records, send mail, modify schema, implement queues, or change production.

## 1. Purpose and scope

This contract defines when a visible reply event may produce a notification candidate, which recipients may receive it, and how threading, visibility, suppression, moderation, bell state, digest eligibility, optional email, audit, and engagement remain distinct. It complements the Domain Event and Notification Contract, Bell and Read-State Contract, Subscriber Policy Contract, and Suppression and Abuse-Control Contract.

The underlying post and reply remain Community-owned facts. A reply event is not a notification; a notification is not eligibility; eligibility is not delivery; reading is not engagement. `path_id` remains chatboard/path/feed identity and `group_id` remains teacher-group identity.

## 2. Reply event model

A reply event states that an authorized actor created a reply associated with a parent post or reply. It carries immutable event identity, producer, actor, reply subject, parent target, thread context, product/group/path context, visibility state, occurrence time, and audit provenance. It contains no pre-authorized send instruction.

Candidate generation may propose recipients based on thread participation and explicit reply policy. It must not infer promotional consent from replying, group membership, semantic relevance, or relationship. Visibility and suppression are evaluated before any candidate is considered eligible.

## 3. Eligible recipients

Potential recipients, evaluated separately, are:

- the author of the directly replied-to post or reply;
- a participant who has explicitly subscribed to relevant thread activity under approved policy;
- a moderator or administrator when the reply triggers a permitted oversight notice;
- a member explicitly mentioned by the reply, under mention policy rather than reply policy;
- a group or product operator where an administrative rule requires it.

The reply author is not notified of their own reply by default. Observers, prior readers, group members, and inferred-interest subjects are not eligible merely because they could find the thread relevant. A recipient must be authenticated or otherwise authorized by a separate approved account/security policy.

## 4. Visibility and authorization

Visibility precedes candidate generation. The evaluator considers post/reply publication, author and recipient blocks, anonymous-post policy, group privacy, moderation state, thread access, retraction, and explicit `path_id -> group_id` mapping where both contexts are present.

A reply to a private, hidden, anonymous, removed, or retracted context cannot expose restricted content to an unauthorized recipient. A notification may use a safe summary or no content when the action is relevant but the underlying text is not currently visible. Promotion, recommendation, Core Terms, Portable Views, or relationship evidence cannot expand reply visibility or create consent.

## 5. Thread and nested-reply behavior

Direct replies target the author of the directly replied-to item when that author remains eligible. Nested replies retain the full ancestor/thread identity but do not notify every ancestor author by default. A later policy may allow thread participants to opt in to activity, but that is a separate category and must be scoped.

Self-replies produce no self-notification by default. A reply to a deleted or retracted parent produces no ordinary content notification; a necessary moderation or account notice is evaluated separately. A reply after moderation is evaluated against the current visibility and action state, not the state at event creation alone.

## 6. Bell behavior

An eligible reply candidate may create a Community bell item in a later implementation. It begins unread when acknowledgement is required and is recipient-specific. Opening the authorized thread context may mark the represented item read; it does not prove the reply was read in full or engaged with.

Multiple replies in one thread may be grouped or coalesced. The group preserves included event IDs, thread identity, visibility basis, latest/earliest times, and recipient scope. Archive removes presentation, not the reply, event, moderation record, or audit. A bell-only outcome is valid when email or digest is ineligible.

## 7. Optional email behavior

Email is optional and separate from bell eligibility. It requires the reply category/channel policy, consent or approved transactional basis, absence of pause/unsubscribe/suppression, valid visibility, and dedupe/throttle resolution. A reply may be bell-eligible but email-ineligible.

Reply email must not silently include promotional content. A muted group, paused optional email, promotional unsubscribe, hard bounce, complaint, abuse restriction, or kill switch can block email while preserving an otherwise eligible bell state. Delivery is a later attempted transport and does not follow merely from candidate generation.

## 8. Digest behavior

Eligible reply candidates may be included in a daily or weekly digest only when the member has the relevant category/channel permission and no higher suppression applies. Digest grouping must preserve thread, product, group, visibility, and included-event scope. Immediate reply candidates must not be silently converted to digest if the member selected never or if the event requires a different notice class.

Digest inclusion is not delivery or engagement. A candidate omitted from a digest remains auditable as omitted with a reason such as frequency, dedupe, suppression, expiry, or visibility change.

## 9. Deduplication and coalescing

Deduplication uses stable event, recipient, thread, and channel/category scope to prevent duplicate candidates and attempts. It must not collapse distinct replies merely because they have similar text. Coalescing combines compatible replies for one thread and recipient, preserves all represented event IDs, and never combines private/public or cross-product scopes.

Read, archive, email delivery, digest inclusion, and engagement are separate states. A member reading one group item must not mark unseen or hidden replies read.

## 10. Suppression interaction

Suppression and abuse policy are evaluated before final eligibility. Hard bounce, complaint, legal/privacy, abuse/safety, administrative, user-requested, global unsubscribe, global pause, category/channel suppression, and kill-switch scope may block optional email or the bell as applicable. Suppression is not a preference and cannot be cleared by restoring a reply setting.

The resolution records the applicable suppression, scope, authority, reason, effective time, and audit reference. When a reply becomes hidden, retracted, or blocked after candidate creation, the candidate is re-evaluated and may become safe-summary, blocked, expired, or retracted. No queued item is assumed safe merely because it was previously generated.

## 11. Moderation interaction

Moderation, visibility, notification eligibility, and delivery remain distinct. A reply hidden as spam may retain evidence but produce no ordinary recipient notification. A moderator action may produce a separate administrative notice to the affected member or authorized operator. A promoted/editorial reply may appear in a feed without notifying every participant.

Appeal, correction, removal, and restoration change future eligibility according to current policy; they do not erase historical event or audit evidence. A disabled or restricted account may lose reply-notification eligibility within scope while necessary account/security notices remain separately evaluated.

## 12. Audit requirements

Audit records reply event ID, parent/thread/ancestor identity, actor, recipient basis, product/group/path context, mapping evidence, visibility result, candidate decision, category/channel, consent/pause/unsubscribe state, suppression inputs, dedupe/coalescing, read/archive/retraction state, expiry, delivery reference if later enabled, and correction/appeal/incident references.

Audit is recipient- and privacy-scoped, append-oriented, and sufficient to explain why a direct or nested reply did or did not produce bell, email, digest, or no notification. Retention periods remain a separate governance decision.

## 13. Legacy evidence census

| Evidence area | Classification | Contract conclusion |
|---|---|---|
| Community reply sender/notification implementation | Absent/unknown in inspected local evidence | No active reply-notification authority may be inferred. |
| `tnet_memberships.email_posts` and group frequency controls | Partial/legacy | Preference evidence only; not reply-event or delivery authority. |
| BuddyPress notification framework | Legacy/unknown authority | Must not be silently combined with Community policy. |
| Local Community event/bell tables | Absent locally | Local environment is not a cloned Community runtime. |
| WordPress mail functions | Platform capability | Does not establish reply consent, eligibility, or delivery. |
| Job Center notifications | Separate product/partial | Not Community reply authority and not absorbed. |

No reply notifications, bell records, email, queues, schema, production, provider, or membership state were changed.

## 14. Cross-product isolation

Reply notifications are Community-scoped. Job Center, Lesson Bank, CE, Marketplace, and future products retain their own facts, reply semantics, recipients, categories, and notification policies. Shared WordPress identity, Core Terms, Portable Views, relationships, or inferred interests do not create cross-product reply consent or notification candidates.

## 15. Decision examples

- A direct visible reply creates a candidate for the parent author; bell may be eligible while email is not.
- A nested reply targets the directly replied-to participant by default, not every ancestor author.
- A self-reply creates no self-notification by default.
- A reply after moderation is evaluated against current visibility; a hidden reply produces no ordinary content notification.
- A deleted or retracted reply is removed from active notification presentation while audit remains preserved.
- A muted group blocks group-scoped reply email but does not silently suppress unrelated product communications.
- A member with paused optional email may receive a bell-only reply notification.
- A member with bell-only eligibility is not treated as having received email or engaged with the reply.
- Multiple replies in one thread may coalesce into one item with all event IDs preserved.
- A hard bounce blocks reply email for the affected address/channel but leaves an eligible bell state intact.

## 16. Open decisions

| Item | Classification |
|---|---|
| Direct versus thread-participant default scope | Engineering Director decision |
| Numeric reply expiry, digest windows, and retention | Engineering Director decision |
| Reply category classification for transactional versus operational email | Engineering Director decision / external research required |
| Legacy Community/BuddyPress reply source reconciliation | Production evidence required |
| Nested-thread grouping and cross-device read behavior | Implementation detail deferred |
| Anonymous reply summaries and safe-content templates | Engineering Director decision / implementation detail deferred |
| Product-specific reply subscriptions outside Community | Engineering Director decision |

No broad external research was performed. C3-NOT004 may be considered after Engineering Director review; it is not authorized here.

## 17. Acceptance criteria

Later implementation must demonstrate:

1. Direct, nested, self, moderated, deleted/retracted, muted-group, paused-email, bell-only, and multi-reply scenarios behave as specified.
2. Reply event, candidate, eligibility, bell, email, digest, delivery, read, and engagement states remain distinct.
3. Visibility, authorization, `path_id`/`group_id`, suppression, moderation, and current-state re-evaluation are enforced.
4. Dedupe and coalescing preserve event IDs, thread scope, recipient basis, and audit.
5. Bell-only outcomes remain safe and do not imply email delivery or engagement.
6. Cross-product isolation and no Job Center absorption are proven.
7. Audit can explain every candidate, block, grouping, expiry, read/archive, and restoration decision.
8. Verification and rollback/stop conditions are recorded without production writes or unsolicited mail.

This document authorizes no implementation. The next decision is whether the Engineering Director authorizes a bounded C3-NOT004 follow-up.
