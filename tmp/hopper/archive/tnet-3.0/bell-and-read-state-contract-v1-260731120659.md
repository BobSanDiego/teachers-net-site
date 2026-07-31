# Community 3.0 Bell and Read-State Contract v1

Status: documentation and read-only evidence only. This contract defines the in-product notification policy; it does not create records, modify schema, implement UI/APIs, send mail, or change production.

## 1. Purpose and scope

The bell is an in-product presentation of eligible Community notification candidates. This contract defines bell notification lifecycle, unread/read/archive state, grouping, expiry, acknowledgement, synchronization, accessibility, and audit. It complements the Domain Event and Notification Contract, Subscriber Policy Contract, and Suppression and Abuse-Control Contract.

The bell is not email, a digest, delivery, or engagement. A domain event is not a bell record; read is not delivered; archived is not deleted; notification is not engagement. `path_id` remains chatboard/path/feed identity and `group_id` remains teacher-group identity.

## 2. Bell notification model

A bell record is a presentation-specific record derived from an eligible notification candidate. It contains a stable notification ID, source event ID, family, recipient, actor/subject/target references, product scope, visibility basis, display summary, current state, timestamps, grouping metadata, and audit references. It must not contain a pre-authorized email instruction.

Bell records are recipient-scoped and privacy-filtered. Their existence does not grant visibility to the underlying content beyond the recipient's current authorization. If visibility is later withdrawn, the bell must resolve to a safe explanation or disappear according to approved policy while preserving audit history.

| Family | Unread/read behavior | Archive behavior | Badge impact | Expiry/audit |
|---|---|---|---|---|
| Reply | Unread on a visible reply candidate; opening the relevant context may mark read. | Archive removes active bell presentation, not event/history. | Increments unread count once per deduped/grouped item. | Expires when stale under approved policy; record event, visibility, state changes. |
| Reaction | Unread when a permitted reaction notification exists; read on acknowledgement/open. | May archive individually or as a group. | Counts only active unread notification groups. | Coalescing and actor changes audited; no engagement inference. |
| Mention | Unread for a visible, permitted mention; read on acknowledgement/open. | Archive is reversible presentation state. | Counts active unread mention item/group. | Abuse/suppression changes and expiry audited. |
| Group activity | Unread only when group activity is eligible for the member; membership alone is insufficient for all channels. | Grouped activity may archive as a group. | One badge unit per active group notification group, not every event. | Group mapping and visibility preserved; expiry is policy-defined. |
| Moderator action | Unread when an authorized action notice is relevant; read after acknowledgement. | Archive does not remove moderation evidence or appeal state. | Counts active action notices. | Actor, reason, scope, appeal, and state audit required. |
| Administrative notice | Unread while action is pending acknowledgement or relevant. | Archive may be blocked for required/pending notices or allowed only after acknowledgement. | Counts according to notice urgency/state. | Required scope, expiry/review, and acknowledgement audited. |
| Account/security notice | Unread until acknowledged where appropriate; read is not proof of delivery or resolution. | Required security notices may remain active until resolved; archive must not erase evidence. | Counts active security notices. | Security source, state, and outcome audited under restricted access. |

## 3. Notification lifecycle

1. A domain event is recorded by its product authority.
2. Visibility and suppression policies are evaluated.
3. A notification candidate is generated for an eligible bell recipient.
4. A bell record is created only by a later authorized implementation.
5. The record begins active/unread unless the family policy says acknowledgement is not needed.
6. The member views, acknowledges, reads, archives, or dismisses it according to policy.
7. Grouping, deduplication, coalescing, expiry, and visibility changes may alter presentation state.
8. Audit preserves the state transition and source event.
9. Deletion, if ever authorized, is a separate retention operation and is not implied by archive.

## 4. Read-state model

The conceptual states are:

- **unread** — active and not acknowledged by this recipient;
- **read** — recipient acknowledged or opened the bell context;
- **archived** — removed from active presentation by recipient or policy, with history preserved;
- **dismissed** — recipient removed the presentation where dismissal is allowed;
- **expired** — no longer actionable or current under policy;
- **retracted** — source visibility or authority was withdrawn;
- **blocked** — suppressed by policy, abuse, privacy, or operational control.

Read state is recipient-specific. Reading one grouped item may mark only the represented events that were actually exposed, not unrelated hidden events. A read action does not mark an email delivered, does not signal engagement with the underlying content, and does not grant consent.

## 5. Archive and dismissal rules

Archive is a reversible presentation state unless a later retention policy says otherwise. It removes an item from the active bell view but preserves event, notification, audit, and appeal evidence. Dismissal is permitted only for families that do not require acknowledgement or active resolution. Required security, moderation, or administrative notices may remain until acknowledged or resolved.

Archive and dismissal must not delete the source post, group membership, moderation evidence, preference, suppression, or delivery history. If a retraction or privacy change occurs, the bell may be hidden or replaced by a safe state; the underlying audit remains access-controlled.

## 6. Notification grouping and coalescing

Grouping presents related candidates as one bell item, such as several reactions on one post, replies in one thread, or activity in one group. A group requires a stable group key, included event IDs, recipient, product/category/channel scope, visibility basis, latest and earliest timestamps, and a policy for future events.

Grouping must not combine different products, visibility scopes, consent categories, security classes, or unrelated targets. A member can inspect the represented events where permitted. Unread count is based on active notification groups or explicit family policy, not raw event volume. Coalescing is presentation behavior and does not erase events or create engagement.

## 7. Expiry and retention

Expiry removes a notification from active/actionable presentation when it is stale, resolved, retracted, or outside an approved window. It does not delete the source event or audit. Security, moderation, and required administrative notices require family-specific resolution or review before expiry.

This contract does not invent numeric expiry or retention periods. Later policy must distinguish active presentation retention, read/archive history, audit evidence, moderation/appeal evidence, and account/security evidence. Expiry must record the rule, effective time, actor or system reason, and resulting state.

## 8. Bell versus email versus digest

The bell is an in-product channel. Email is a transport subject to consent, suppression, provider outcomes, and delivery policy. A digest is a coalesced communication with its own category and frequency. One event may yield a bell-only result, a digest candidate, both, or neither. A bell record never implies email consent, email delivery, or engagement.

## 9. Badge/count behavior

The badge represents active unread bell state for the authenticated member and current product scope. Counts must be deterministic, recipient-scoped, privacy-filtered, and stable under refresh. Grouped items count according to grouping policy, not every underlying event. Archived, expired, blocked, retracted, and read items do not count as active unread unless a family-specific pending state is explicitly defined.

Count changes must be attributable to state transitions. A count is not a membership count, post count, delivery count, or engagement metric. Cross-product badges require explicit product policy and must not silently merge Community with Job Center or other products.

## 10. Cross-device synchronization

Read, archive, dismissal, and acknowledgement state is recipient-scoped and should converge across authenticated devices. Later implementations must use conflict-safe state transitions, preserve event ordering and audit, and avoid marking unseen items read because another device rendered a summary. Offline or delayed updates must reconcile against current visibility and suppression before presentation.

## 11. Accessibility expectations

The bell must have an accessible name and state, keyboard-operable controls, visible focus, meaningful order, non-color status cues, and announcements that do not overwhelm the member. Unread state, count changes, grouping, archive, dismissal, and acknowledgement must be understandable to assistive technology. A badge must not be the sole communication of urgency. Required security or moderation notices need a clear path to the relevant action without exposing restricted content.

## 12. Legacy evidence census

| Evidence area | Classification | Contract conclusion |
|---|---|---|
| Community bell/read-state implementation | Absent/unknown in inspected local evidence | No active Community bell authority may be inferred. |
| BuddyPress notification framework, usermeta, options, notifications table | Legacy/unknown authority | Separate legacy framework; not silently adopted. |
| `tnet_memberships.email_posts` and group frequency controls | Partial/legacy | Preferences, not bell state or read state. |
| Job Center notification scaffolding | Separate product/partial | Not Community bell authority and not absorbed. |
| Domain event/notification queues | Absent locally/unknown in production | Requires separately authorized implementation/evidence work. |
| WordPress mail functions | Platform capability | Email is distinct from bell and does not establish read state. |

No notification records, tables, options, users, memberships, queues, schema, production state, or provider state were changed.

## 13. Cross-product isolation

Community bell state is scoped to Community product events and recipients. Job Center, Lesson Bank, CE, Marketplace, and future products retain their own notification surfaces and product facts. Shared identity, Core Terms, Portable Views, relationships, or recommendations do not create bell records or cross-product consent. A product may consume an explicitly governed event only with a defined audience, visibility, retention, correction, suppression, and audit boundary.

## 14. Decision examples

- A reply creates an unread Community bell item; it may remain bell-only even when email is not eligible.
- Several reactions on one post appear as one grouped item; reading it does not imply every underlying event was engaged with.
- A member archives group activity; source events and membership remain unchanged.
- A moderator action remains active until acknowledged or resolved; archive cannot erase appeal evidence.
- A security notice remains visible until its required action is resolved, even if optional email is paused.
- A visibility withdrawal retracts or safely replaces a bell item; audit remains restricted and preserved.
- A hard bounce blocks email but does not automatically block an otherwise eligible bell item.
- A global Community kill switch blocks bell generation or presentation only within its explicit scope; re-enable requires review.
- A badge count reflects active unread groups, not raw posts, members, delivered email, or engagement.
- A second device sees the read state after synchronization, but a summary view must not mark hidden events as read.

## 15. Open decisions

| Item | Classification |
|---|---|
| Authoritative bell store, API, and read-state versioning | Engineering Director decision / implementation detail deferred |
| Numeric expiry and retention periods by notification family | Engineering Director decision |
| Group-count versus event-count badge policy | Engineering Director decision |
| Cross-device conflict and offline synchronization behavior | Implementation detail deferred |
| Legacy BuddyPress migration or coexistence boundary | Production evidence required / Engineering Director decision |
| Accessibility announcement and urgency standards | External research required / Engineering Director decision |
| Required notice archive/dismissal rules | Engineering Director decision |
| Cross-product badge aggregation | Engineering Director decision |

No broad external research was performed. C3-NOT003 may be considered after Engineering Director review; it is not authorized here.

## 16. Acceptance criteria

Later implementation must demonstrate:

1. Bell records are separate from domain events, email, digest, delivery, and engagement.
2. All minimum notification families have defined lifecycle, read, archive, badge, expiry, and audit behavior.
3. Read, archive, dismissal, expiry, retraction, and blocked states are distinct and explainable.
4. Grouping and coalescing preserve included event IDs, visibility, product, category, and audit scope.
5. Counts are deterministic, recipient-scoped, privacy-filtered, and not engagement metrics.
6. Cross-device state converges without falsely marking unseen or hidden events read.
7. Bell-only outcomes are safe and supported when email/digest is ineligible.
8. Accessibility expectations are verified for keyboard, focus, state, grouping, count, and urgency.
9. Suppression, moderation, visibility, and event eligibility changes re-evaluate presentation safely.
10. Cross-product event, consent, and badge isolation is proven, including no Job Center absorption.
11. Expiry, retention, rollback, retraction, and audit behavior are tested without production writes or mail sends.

This document authorizes no implementation. The next decision is whether the Engineering Director authorizes a bounded C3-NOT003 follow-up.
