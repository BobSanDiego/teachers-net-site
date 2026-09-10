# Community 3.0 Project Cursor

## Project State

Maintenance

## Current Phase

Bounded implementation preparation — C3-IMP003 complete; no delivery implementation begun.

## Current Milestone

Completed C3-RR001 reconciliation package, C3-PLAN003 master plan, and prior correction of the fossilized `path_id == group_id` assumption. The
AI in Education board demonstrated that a chatboard `path_id` can differ from
its teacher `group_id` (`241` versus `227`). The correction resolves teacher
groups through `local_path -> group_id` and preserves `path_id` for chatboard,
post, and feed operations.

## Permanent Invariant

A chatboard `path_id` is not a teacher `group_id`. Teacher-group and membership
operations must resolve or carry the canonical `tnet_groups.group_id` and must
not query `tnet_memberships` with `tnet_local_data.path_id` unless an explicit
mapping has established equality for that record.

## Verification State

The completed correction was reported verified for join, leave, reload state,
header star, sidebar membership/count/avatars, group settings, email-frequency
reads and persistence, Chat Center counts, the divergent AI in Education board,
and a legacy control board. Temporary diagnostics were removed before closure.

## Reconciliation Status

C3-RR001 is complete as a documentation-only package. It contains the
capability catalog, current-state census, roadmap crosswalk, external research
register, integrated M0-M9 roadmap, go-point readiness, and next-ticket queue.
The package concludes NO-GO until the Engineering Director reviews it and
explicitly authorizes a bounded M1 contract ticket.

C3-PLAN003 establishes `community-3.0-master-plan-v1.md` as the product
authority above engineering roadmaps and tickets. C3-PLAN004 enhanced that
plan with product success measurement principles, cross-product autonomy, and
the canonical product-level architecture diagram. It preserves all catalogued
capabilities and keeps the current NO-GO implementation boundary.

C3-GO001 records GO authorization for bounded Community 3.0 implementation
preparation. The authorized next ticket is C3-TRUST001 — Subscriber Policy
Contract. It may inspect current implementation and production evidence
read-only, but may not send mail, change schema, migrate preferences, or enable
delivery.

C3-TRUST001 is complete as a documentation and read-only evidence package at
`docs/community-3.0/subscriber-policy-contract-v1.md`. C3-TRUST002 may be
considered after Engineering Director review; it is not authorized by this
ticket.

C3-TRUST002 is complete as a documentation and read-only evidence package at
`docs/community-3.0/suppression-and-abuse-control-contract-v1.md`. It defines
suppression, complaints, bounces, abuse controls, moderation interaction,
kill-switch governance, audit, recovery, and acceptance criteria. C3-NOT001
may be considered after Engineering Director review; it is not authorized by
this ticket.

C3-NOT001 is complete as a documentation and read-only evidence package at
`docs/community-3.0/domain-event-and-notification-contract-v1.md`. It defines
event identity, lifecycle, producers, consumers, visibility, candidates,
eligibility, channels, deduplication, coalescing, expiry, audit, and
cross-product isolation. C3-NOT002 may be considered after Engineering
Director review; it is not authorized by this ticket.

C3-NOT002 is complete as a documentation and read-only evidence package at
`docs/community-3.0/bell-and-read-state-contract-v1.md`. It defines bell
notification lifecycle, read/archive state, grouping, expiry, badge behavior,
cross-device synchronization, accessibility, and product isolation.
C3-NOT003 may be considered after Engineering Director review; it is not
authorized by this ticket.

C3-NOT003 is complete as a documentation and read-only evidence package at
`docs/community-3.0/reply-notification-contract-v1.md`. It defines direct and
nested reply recipients, visibility, bell/email/digest behavior, suppression,
moderation, grouping, audit, and cross-product isolation. C3-NOT004 may be
considered after Engineering Director review; it is not authorized by this
ticket.

C3-NOT004 is complete as a documentation and read-only evidence package at
`docs/community-3.0/reaction-notification-contract-v1.md`. It defines reaction
recipients, visibility, grouping, bell/email/digest behavior, removal,
suppression, moderation, audit, and cross-product isolation. C3-NOT005 may be
considered after Engineering Director review; it is not authorized by this
ticket.

C3-NOT006 is complete as a documentation and read-only evidence package at
`docs/community-3.0/group-activity-notification-contract-v1.md`. It defines
group activity events, recipient eligibility, membership and frequency,
visibility, bell/email/digest behavior, grouping, suppression, moderation,
audit, and cross-product isolation. No implementation or production change was
made. The next notification contract requires Engineering Director review and
explicit authorization.

C3-IMP001 is complete as a repository-inspection and implementation-planning
package at `docs/community-3.0/notification-implementation-readiness-v1.md`,
`docs/community-3.0/implementation-gap-analysis-v1.md`, and
`docs/community-3.0/notification-implementation-sequence-v1.md`. It identifies
the missing local Mention Notification Contract and recommends C3-IMP002 as a
test-only dry-run group-post candidate evaluator. No code, schema, queue, bell,
email, or production change was made.

C3-IMP003 is complete as a bounded test-only candidate/audit boundary at
`tools/community3/notification_candidate_boundary.py`, with tests and policy
documentation at `tools/community3/test_notification_candidate_boundary.py`
and `docs/community-3.0/candidate-audit-boundary-v1.md`. It consumes only the
evaluator result, returns deterministic non-persistent candidate and redacted
audit objects, and has no database, schema, queue, bell, email, digest, UI, or
production side effect.

## Authorization Status

GO — bounded implementation preparation is authorized. This authorization does
not authorize C3-TRUST001 execution, delivery, migration, or production change
within this ticket.

## Next Authorized Ticket

C3-IMP003 is complete. Any next implementation slice requires Engineering
Director review and explicit authorization; delivery and persistence remain
deferred.

## Next Decision

Review C3-IMP003 and explicitly authorize any persistence or channel follow-up
or stop. No delivery, schema, queue, preference migration, or production work
is authorized by this ticket.

## Strategic Roadmap Alignment

The current planning direction is documented in
`docs/community-3.0/roadmap.md` and the Semantic Platform execution companion.
It records the converged platform model of Core Terms, Portable Views,
Subscriber Policies, Relationship Graphs, Communications Platform, and the
planning-only Semantic Studio concept. Job Center is the first bounded semantic
subscriber proof; Chatboards and Groups are the second major subscriber.

This roadmap is planning guidance only. It does not reopen the completed group
identity correction or authorize implementation, schema changes, migration,
production UI work, or communication delivery.

## Google Drive Handoff

The active Google Drive operational handoff is
<https://docs.google.com/document/d/1oxqqgFHkPwrJQpQ563-hho0jPf_MWrTEPE_qCJa-BeY>.
