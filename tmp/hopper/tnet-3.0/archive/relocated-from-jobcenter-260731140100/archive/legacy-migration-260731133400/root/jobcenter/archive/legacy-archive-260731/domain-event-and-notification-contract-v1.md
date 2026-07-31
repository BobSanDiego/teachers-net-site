# Community 3.0 Domain Event and Notification Contract v1

Status: documentation and read-only evidence only. This contract defines policy and lifecycle boundaries; it does not implement events, queues, bell records, mail, or delivery.

## 1. Purpose and scope

This contract defines how a Community 3.0 domain event becomes a notification candidate, how that candidate is evaluated for visibility and eligibility, and how any eligible channel is eventually treated as delivery. It keeps event facts, notification intent, consent, suppression, moderation, eligibility, delivery, and engagement separate.

The contract is subordinate to the Community 3.0 Master Plan and complements the Subscriber Policy Contract and Suppression and Abuse-Control Contract. Community owns its participation and moderation facts; Core Terms owns canonical semantic identity; Portable Views own reusable presentation; product subscribers own their facts and workflows. No event contract here authorizes schema, queue, provider, cron, production, or Job Center changes.

## 2. Domain event model

A domain event is an immutable statement that an authorized product action or state transition occurred. It has an event identity, family, producer, subject, actor, target, time, source, and evidence. It is not a notification and does not imply that any person should be contacted.

The invariant chain is: **Event != notification; notification != eligibility; eligibility != delivery; delivery != engagement.** Membership is not consent, and `path_id` is not `group_id`.

Minimum event families:

| Family | Producer | Subject/actor/target | Visibility and candidates | Eligible channels and audit |
|---|---|---|---|---|
| New post | Community posting authority | Subject: post; actor: author; target: chatboard/group/path context | Visibility policy determines readers; candidates may include feed/bell, replies, moderation, or group activity | Bell/feed in principle; email/digest only by policy. Record post, visibility, mapping, and event decision. |
| Reply | Community posting authority | Subject: reply; actor: responder; target: parent post/author or participants | Candidate for parent author and permitted participants; visibility evaluated independently | Bell/feed, optional email/digest. Record parent, recipient basis, and dedupe key. |
| Reaction | Community engagement authority | Subject: reaction; actor: reactor; target: post/author | Candidate only where reaction is visible and notification policy permits | Bell/feed or digest in principle; email optional. Record reaction and coalescing evidence. |
| Mention | Community posting authority | Subject: mention; actor: author; target: mentioned member/post | Candidate for named target after abuse, visibility, and block checks | Bell/feed, optional email. Record target, source text reference, and abuse checks. |
| Group join | Community membership authority | Subject: membership transition; actor: member/admin; target: group | Candidate for member confirmation or authorized group/admin state; membership does not create consent | Bell/feed and required account notice in principle; email only by policy. Record canonical `group_id`, not path substitution. |
| Group leave | Community membership authority | Subject: membership transition; actor: member/admin; target: group | Candidate for confirmation or administrative state; end membership-dependent eligibility | Bell/feed or required account notice in principle; no automatic promotional mail. Record prior/current state. |
| Moderator action | Moderation authority | Subject: moderation action; actor: moderator/system; target: content/member/group | Candidate for affected member or authorized operator only; preserve evidence and appeal scope | Bell/account notice, email only when necessary and eligible. Record reason, actor, scope, and audit. |
| Promotion/editorial action | Editorial/community authority | Subject: promotion state; actor: editor; target: post/resource | May create an editorial/feed candidate; promotion does not create notification eligibility or consent | Feed/bell or an explicitly governed announcement; audit editorial decision separately. |
| Administrative announcement | Authorized product/operator | Subject: announcement; actor: operator; target: product/group/audience | Candidate audience is explicit and visibility-scoped; no inferred audience expansion | Bell/feed, digest, or email by category policy. Record audience, authority, and consent basis. |
| Account/security event | WordPress/account authority | Subject: account/security state; actor: member/system; target: account | Candidate for affected account only; necessary scope must be justified | Account surface/email in principle; required notices are separate from promotion. Record security evidence and outcome. |

## 3. Event lifecycle

1. An authorized producer records the domain fact.
2. The event receives immutable identity and provenance.
3. Visibility and privacy determine who may know the event exists.
4. Candidate generation derives possible recipients and channels without sending.
5. Subscriber policy resolves consent, category, channel, frequency, group scope, pause, and unsubscribe.
6. Suppression and abuse controls resolve complaints, bounces, restrictions, kill switches, and operational blocks.
7. Event eligibility and dedupe/coalescing are evaluated.
8. A final decision records allow, block, defer, bell-only, digest, or no eligible channel.
9. A separate delivery process, if later authorized, attempts a permitted channel and records outcome.
10. Engagement is measured separately and never retroactively creates consent.

## 4. Event producers

Producers are product authorities, not transports. Community posting, membership, moderation, editorial, and account boundaries may emit events. A producer must be authorized for the fact, preserve actor/subject/target identity, use canonical group mapping, and not emit a notification as a substitute for the event. Core Terms and Portable Views may provide semantic context but do not become owners of Community participation events.

## 5. Event consumers

Potential consumers include Community feed/bell presentation, moderation and audit, subscriber-policy evaluation, suppression/abuse controls, digest planning, analytics, and product-specific views. A consumer may read an event only within its product and privacy authority. Communications is a consumer and policy resolver, not the owner of the underlying event. Job Center and other products must not consume Community events as permission to send unrelated communications.

## 6. Event identity and idempotency

Each event requires an immutable unique ID, family, producer, source record, occurrence time, and stable subject/target references. A notification candidate requires its own identity and a deterministic relation to the event. Reprocessing an event must not create duplicate candidates or duplicate delivery attempts. Idempotency keys must be scoped to event family, recipient, channel, and relevant target; implementation chooses storage only in a later ticket.

`path_id` may identify chatboard/post/feed context. `group_id` identifies the teacher group. An event carrying both must preserve both and the explicit mapping provenance.

## 7. Event payload contract

The conceptual payload includes:

- immutable event ID and family/version;
- producer/product and source record ID;
- occurrence, recorded, and effective times;
- actor, subject, and target identities with privacy-safe references;
- `path_id`, `group_id`, and mapping evidence where relevant;
- visibility state and audience basis;
- event reason/context and moderation/editorial state;
- correlation/parent event ID and idempotency key;
- candidate and decision references added by later lifecycle stages;
- audit, retention, and incident references.

Payloads must minimize personal data, avoid embedding secrets, preserve enough evidence to explain a decision, and distinguish absent from unknown values. An event payload must not contain a pre-authorized “send” instruction.

## 8. Visibility evaluation

Visibility precedes candidate generation. The evaluator considers content state, author/member block state, anonymous-post policy, group privacy, moderator action, path/group mapping, and audience authority. A hidden, removed, or private item cannot produce a candidate for a recipient who cannot see it. A necessary moderation or account notice may describe an action without exposing restricted content.

Promotion/editorial status does not override privacy. A relationship, inferred interest, recommendation, or Portable View does not expand event visibility or create communication consent.

## 9. Notification candidate generation

A candidate is a proposed recipient/channel/category response to an event. It records event ID, recipient basis, product, category, channel, visibility decision, and why it was generated. Candidate creation is not delivery and not eligibility.

Candidate generation may propose bell, account feed, immediate email, or digest membership in principle. It must not infer candidates from unrelated products, convert group membership into promotional consent, or bypass suppression. A candidate may be discarded, deferred, coalesced, or reduced to bell-only.

## 10. Eligibility evaluation

Eligibility resolves the candidate against the Subscriber Policy Contract and Suppression and Abuse-Control Contract: required/legal/security scope, visibility, consent, category, channel, pause, unsubscribe, group override, frequency, suppression, abuse state, event validity, dedupe, throttle, and active kill switches. The result is explainable and scoped.

Eligibility allow is not delivery. Delivery is a later attempted transport action. Delivery success is not engagement.

## 11. Bell versus email versus digest

Bell state is an in-product presentation channel. Account feed state is a durable product surface. Email is a transport subject to consent, suppression, provider outcomes, and delivery controls. Digest is a coalesced presentation with its own category, frequency, candidate window, and audit.

A reply may be eligible for bell but not email. A group activity event may be eligible for a weekly digest but not immediate email. A security event may be eligible for required email while optional email is paused. These are valid distinct outcomes, not inconsistent delivery.

## 12. Event deduplication

Deduplication prevents duplicate candidates or repeated delivery attempts for the same event/recipient/channel scope. It must preserve the original event and decision audit, identify the dedupe key and reason, and never use dedupe to conceal a suppression or consent conflict. A later distinct event must not be collapsed solely because its content looks similar.

## 13. Event coalescing

Coalescing combines compatible candidates into a digest or bounded in-product summary. It records included event IDs, excluded events, category/channel scope, window, recipient basis, and final eligibility. Coalescing cannot mix products, visibility scopes, or unrelated consent classes without explicit policy. A candidate blocked by suppression is not silently included in another message.

## 14. Event expiry

Events and candidates require an effective time and an expiry or review condition where timeliness matters. Expiry prevents stale notifications from becoming newly relevant; it does not erase the event or audit history. Security and legal notices use their own required-scope policy. Numeric expiry windows remain an Engineering Director/implementation decision unless existing authority establishes them.

## 15. Event audit

Audit records producer, event identity, payload version, actor, subject, target, visibility result, candidate generation, recipient basis, policy inputs, suppression inputs, dedupe/coalescing, final decision, delivery reference if later enabled, engagement reference if later measured, incident, appeal, and correction. Audit is append-oriented and privacy-scoped. Retention periods remain an open governance decision.

## 16. Legacy evidence census

| Evidence area | Classification | Contract conclusion |
|---|---|---|
| Community `tnet_*` event/notification tables in local DDEV | Absent locally | Local environment is not evidence of a complete Community event runtime. |
| `tnet_memberships.email_posts` | Partial/legacy | Preference evidence, not an event or delivery authority. |
| Legacy chatboard notification sender/queue/digest/throttle | Absent/unknown in inspected evidence | Do not infer active notification delivery. |
| BuddyPress notification framework, usermeta, options, notifications table | Legacy/unknown authority | Separate framework; no silent combination with Community events. |
| Job Center notification scaffolding | Separate product/partial | Must not be absorbed into Community; product authority remains Job Center. |
| WordPress `wp_mail()` paths | Platform capability/partial | Presence of mail functions does not establish Community eligibility or consent. |
| Production event queues and provider delivery | Unknown | Requires separately authorized read-only production audit. |

No event, bell record, queue, option, schema, cron, provider, membership, or production state was changed.

## 17. Cross-product event isolation

Events are product-scoped by producer and authority. Community events may inform Community surfaces and explicitly governed shared analytics, semantic context, or relationships. They do not authorize Job Center, Lesson Bank, CE, Marketplace, or future-product communications. A shared identity, Core Term, Portable View, relationship, or recommendation does not create cross-product consent.

Products may subscribe to shared event infrastructure only through an explicit contract specifying source, audience, visibility, retention, correction, consent, suppression, and failure behavior. Product facts and workflows remain local.

## 18. Open decisions

| Decision/evidence gap | Classification |
|---|---|
| Authoritative event store and versioning boundary | Engineering Director decision / implementation detail deferred |
| Event payload privacy and retention periods | Engineering Director decision |
| Bell/read-state model and expiry windows | Engineering Director decision |
| Queue, digest window, coalescing, and replay behavior | Implementation detail deferred |
| Provider feedback and delivery outcome integration | Production evidence required / implementation detail deferred |
| Legacy Community/BuddyPress event source reconciliation | Production evidence required |
| Cross-product event subscription and consent boundary | Engineering Director decision |
| Event-family moderation, editorial, and security classification edge cases | Engineering Director decision |

No broad external research was performed. C3-NOT002 remains the next possible notification contract review only after Engineering Director review; it is not authorized here.

## 19. Acceptance criteria

Later implementation must demonstrate:

1. Events, candidates, eligibility, delivery, and engagement are separate observable states.
2. Each minimum event family has producer, subject, actor, target, visibility, candidate, channel, and audit behavior.
3. Event identity and candidate idempotency prevent duplicates without erasing history.
4. Payloads preserve privacy, provenance, `path_id`, `group_id`, and explicit mapping evidence.
5. Visibility is evaluated before candidate generation.
6. Subscriber consent and suppression policies are resolved before eligibility.
7. Bell, account feed, email, and digest produce explainable independent outcomes, including bell-only.
8. Dedupe, coalescing, expiry, kill switches, and queue re-evaluation are scoped and auditable.
9. Moderation, promotion, notification eligibility, and delivery remain separate decisions.
10. Cross-product event and consent isolation is proven, including no Job Center implementation absorption.
11. Legacy and production evidence is classified before migration or delivery decisions.
12. Tests cover replay, duplicate events, visibility changes, suppression changes, appeals, recovery, and rollback without sending unsolicited mail.

This document authorizes no implementation. The next decision is whether the Engineering Director authorizes a bounded follow-up such as C3-NOT002.
