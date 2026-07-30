# Community 3.0 Project Cursor

## Project State

Maintenance

## Current Phase

Post-reconciliation planning package complete; implementation remains unauthorized.

## Current Milestone

Completed C3-RR001 reconciliation package and prior correction of the fossilized `path_id == group_id` assumption. The
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

## Next Decision

No new group architecture or redesign is authorized by this correction or by
C3-RR001. The exact next decision is whether to approve or revise the
reconciliation package and authorize bounded M1 work.

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
