# Community 3.0 Reaction Notification Contract v1

Status: documentation and read-only evidence only. This contract defines reaction-notification policy; it does not implement notifications, create bell records, send mail, modify schema, implement queues, or change production.

## 1. Purpose and scope

This contract defines when a visible reaction event may produce a notification candidate, which recipients may receive it, and how reaction grouping, bell state, digest eligibility, suppression, moderation, audit, and engagement remain distinct. It complements the Domain Event and Notification Contract, Bell and Read-State Contract, Reply Notification Contract, Subscriber Policy Contract, and Suppression and Abuse-Control Contract.

The reaction and underlying post remain Community-owned facts. A reaction event is not a notification; a notification is not eligibility; eligibility is not delivery; reading is not engagement. `path_id` remains chatboard/path/feed identity and `group_id` remains teacher-group identity.

## 2. Reaction event model

A reaction event states that an authorized actor applied, changed, or removed a supported reaction on a visible target. It carries immutable event identity, producer, actor, reaction type/state, target post/reply, thread context, product/group/path context, visibility state, occurrence time, and audit provenance. It contains no pre-authorized send instruction.

Candidate generation may propose a notification for the target author or other explicitly eligible recipient. It must not infer promotional consent from reacting, receiving a reaction, group membership, semantic relevance, or recommendation. Visibility and suppression are evaluated before eligibility.

## 3. Eligible recipients

Potential recipients, evaluated separately, are:

- the author of the reacted-to visible post or reply;
- a participant explicitly subscribed to relevant activity under an approved policy;
- a moderator or administrator when the reaction triggers a permitted oversight notice;
- a product operator where an administrative rule requires it.

The reaction actor is not notified of their own reaction by default. Observers, group members, prior readers, inferred-interest subjects, and unrelated participants are not eligible merely because the reaction is relevant to them. A recipient must be authenticated or authorized by a separate account/security policy.

## 4. Visibility and authorization

Visibility precedes candidate generation. The evaluator considers target publication, author/recipient blocks, anonymous-post policy, group privacy, moderation state, target retraction, and explicit `path_id -> group_id` mapping where both contexts are present.

A reaction on private, hidden, removed, anonymous, or retracted content cannot expose restricted content to an unauthorized recipient. A safe summary or no notification may be used when the reaction state is relevant but target details are not visible. Promotion, Core Terms, Portable Views, relationships, or recommendations cannot expand visibility or create consent.

## 5. Reaction grouping and aggregation

Multiple compatible reactions on one target may be presented as a grouped notification, such as “several members reacted,” with actor/type detail only where policy permits. A group requires a stable target/recipient/category/channel key, included reaction event IDs, reaction types, first/latest times, visibility basis, and a rule for subsequent changes.

Grouping must not combine different products, visibility scopes, private/public targets, unrelated posts, or incompatible categories. A removed reaction updates or retracts the represented group according to policy; it does not erase the original event or audit. Aggregation is presentation behavior and does not prove engagement.

## 6. Bell behavior

An eligible reaction candidate may create a Community bell item only in a later authorized implementation. It begins unread when acknowledgement is required and is recipient-specific. Opening the permitted target context may mark the represented notification read; it does not prove the target was read or that the member engaged.

A single reaction may appear as one item. Multiple reactions may be grouped or coalesced while preserving all represented event IDs. Archive removes presentation, not the reaction, target, audit, or moderation evidence. Bell-only is a valid outcome when email or digest is ineligible.

## 7. Optional email behavior

Email is optional and separate from bell eligibility. It requires the reaction category/channel policy, consent or approved operational basis, no pause/unsubscribe/suppression, valid visibility, and dedupe/throttle resolution. A reaction may be bell-eligible but email-ineligible.

Reaction email must not silently contain promotional material. Paused optional email, promotional unsubscribe, hard bounce, complaint, abuse restriction, muted scope, or a kill switch can block email while preserving an eligible bell state. Delivery is a later transport action and does not follow from reaction candidate generation.

## 8. Digest behavior

Eligible reactions may be included in daily or weekly digest only when the member has the relevant category/channel permission and no higher suppression applies. Digest grouping must preserve target, product, group, visibility, reaction-event, and recipient scope. A member choosing never cannot be silently moved to a digest.

When reactions are aggregated, the digest records the represented event IDs or a durable aggregate reference and the reason for inclusion. Omitted candidates remain auditable with reasons such as frequency, suppression, dedupe, expiry, target withdrawal, or removal.

## 9. Deduplication and coalescing

Deduplication uses stable reaction event, recipient, target, reaction type/state, and channel/category scope. A reaction change or removal is a distinct event and must not be lost merely because it concerns the same target. Coalescing may combine compatible reactions but preserves event IDs, state changes, visibility, and audit.

Read, archive, email delivery, digest inclusion, and engagement remain separate states. Reading a grouped item must not mark hidden or unrepresented reactions read.

## 10. Suppression interaction

Suppression and abuse policy are evaluated before final eligibility. Hard bounce, complaint, legal/privacy, abuse/safety, administrative, user-requested, global unsubscribe, global pause, category/channel suppression, and kill-switch scope may block optional email or the bell as applicable. Suppression is not a preference and cannot be cleared by restoring a reaction setting.

If the target or reaction becomes hidden, retracted, moderated, or blocked after candidate generation, the candidate is re-evaluated and may become safe-summary, blocked, expired, retracted, or bell-only. Previously generated candidates are not assumed safe.

## 11. Moderation interaction

Moderation, target visibility, notification eligibility, and delivery remain distinct. A reaction on content hidden as spam may retain evidence but produce no ordinary recipient notification. A moderator action may create a separate administrative notice. An editorial promotion does not notify every person who can see the target.

Reaction abuse controls may limit the actor, reaction type, target, group, or notification path. Automated controls are bounded and reviewable; durable restrictions require authority, evidence, reason, scope, and appeal/correction. Removal or restoration changes future eligibility without erasing historical audit.

## 12. Audit requirements

Audit records reaction event ID and state, target/parent/thread identity, actor, recipient basis, product/group/path context, mapping evidence, visibility result, candidate decision, category/channel, consent/pause/unsubscribe state, suppression inputs, grouping/dedupe/coalescing, read/archive/retraction state, expiry, delivery reference if later enabled, and correction/appeal/incident references.

Audit is recipient- and privacy-scoped, append-oriented, and sufficient to explain why a reaction did or did not produce bell, email, digest, grouped, removed, or no notification. Retention periods remain a separate governance decision.

## 13. Legacy evidence census

| Evidence area | Classification | Contract conclusion |
|---|---|---|
| Community reaction sender/notification implementation | Absent/unknown in inspected local evidence | No active reaction-notification authority may be inferred. |
| Community reaction persistence/runtime | Partial/unknown | Requires separately authorized evidence before implementation. |
| `tnet_memberships.email_posts` and group frequency controls | Partial/legacy | Preference evidence only; not reaction-event or delivery authority. |
| BuddyPress notification framework | Legacy/unknown authority | Must not be silently combined with Community policy. |
| Local Community event/bell tables | Absent locally | Local environment is not a cloned Community runtime. |
| WordPress mail functions | Platform capability | Does not establish reaction consent, eligibility, or delivery. |
| Job Center notifications | Separate product/partial | Not Community reaction authority and not absorbed. |

No reaction notifications, bell records, email, queues, schema, production, provider, or membership state were changed.

## 14. Cross-product isolation

Reaction notifications are Community-scoped. Job Center, Lesson Bank, CE, Marketplace, and future products retain their own reaction facts, recipient rules, categories, and notification policies. Shared WordPress identity, Core Terms, Portable Views, relationships, or inferred interests do not create cross-product reaction consent or candidates.

## 15. Decision examples

- A single visible reaction creates a candidate for the target author; bell may be eligible while email is not.
- Multiple reactions on one post appear as one grouped item with represented event IDs preserved.
- Removing a reaction updates or retracts its group presentation but preserves the original event and audit.
- A self-reaction creates no self-notification by default.
- A reaction on moderated or hidden content produces no ordinary content notification unless a separate administrative notice is eligible.
- A paused-email member may receive a bell-only reaction notification.
- A hard bounce blocks reaction email for the affected channel but preserves an eligible bell outcome.
- A member reading a grouped reaction item is not treated as having engaged with the post or every underlying reaction.
- A group-specific abuse control does not silently suppress unrelated product communications.
- A retracted target causes candidates to be re-evaluated rather than delivered from stale state.

## 16. Open decisions

| Item | Classification |
|---|---|
| Supported reaction types and change/removal semantics | Engineering Director decision |
| Group aggregation wording, actor visibility, and badge count | Engineering Director decision / accessibility research |
| Numeric reaction expiry, digest windows, and retention | Engineering Director decision |
| Reaction category classification for operational email | Engineering Director decision / external research required |
| Legacy reaction source and BuddyPress reconciliation | Production evidence required |
| Reaction abuse thresholds and appeal authority | Engineering Director decision / implementation detail deferred |
| Cross-product reaction subscriptions | Engineering Director decision |

No broad external research was performed. C3-NOT005 may be considered after Engineering Director review; it is not authorized here.

## 17. Acceptance criteria

Later implementation must demonstrate:

1. Single, multiple, removed, self, moderated, hidden, paused-email, bell-only, and grouped-reaction scenarios behave as specified.
2. Reaction event, candidate, eligibility, bell, email, digest, delivery, read, and engagement states remain distinct.
3. Visibility, authorization, `path_id`/`group_id`, suppression, moderation, and current-state re-evaluation are enforced.
4. Grouping and coalescing preserve reaction event IDs, state changes, target scope, recipient basis, and audit.
5. Bell-only outcomes do not imply email delivery or engagement.
6. Cross-product isolation and no Job Center absorption are proven.
7. Audit explains candidate, block, group, removal, expiry, read/archive, and restoration decisions.
8. Verification and rollback/stop conditions are recorded without production writes or unsolicited mail.

This document authorizes no implementation. The next decision is whether the Engineering Director authorizes a bounded C3-NOT005 follow-up.
