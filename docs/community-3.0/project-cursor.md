# Community 3.0 Project Cursor

## Project State

Maintenance

## Branch and Worktree Authority

Community work must run from `/tmp/community3-ui-working` on
`COMMUNITY3-ui-working`, based on verified Community milestone `621708b`.
The prior `COMMUNITY003-semantic-community-communications-working-draft` is a
mixed recovery source containing Job Center commits and is not a valid active
Community implementation branch. Run
`tools/community3/project_ticket_preflight.py` before editing and
`tools/community3/validate_hopper_payload.py` before finalizing a payload.

## Current Phase

Bounded implementation preparation — C3-CORE001 complete; no delivery implementation begun.

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

C3-IMP004 is complete as a bounded process-local candidate store and continuity
flush correction. The store is implemented in
`tools/community3/notification_candidate_store.py` with tests, and the
continuity guard is `tools/hopper/validate_community_continuity.py`. No
persistence or delivery was added.

C3-IMP005 is complete as a test-only in-memory bell repository at
`tools/community3/notification_bell_repository.py`, with tests and interface
documentation. It consumes only eligible validated candidates, preserves bell
state separately from delivery and engagement, and adds no persistence or UI.

OPS-CONT001 is complete as a process audit and validation correction. It
documents the verified continuity drift cause and adds the semantic guard at
`tools/hopper/validate_community_continuity.py`. C3-IMP002 and C3-NOT005 remain
unverified and are not represented as complete.

C3-IMP006 is complete as a test-only end-to-end dry-run pipeline at
`tools/community3/notification_dry_run_pipeline.py`, with tests and policy
documentation. It connects evaluator, candidate boundary, in-memory candidate
store, and in-memory bell repository without persistence or delivery.

C3-IMP007 is complete as the single Community-owned in-memory application
service at `tools/community3/notification_application_service.py`, with tests
and documentation. It supports only synthetic `group_post` events and delegates
to the existing dry-run pipeline without exposing repository mutation APIs.

C3-IMP008 is complete as a fixture-backed Community group-post event adapter at
`tools/community3/group_post_event_adapter.py`, with integrated tests and
documentation. It requires explicit path/group mapping evidence and feeds only
the existing application service.

C3-IMP009 is complete as a disabled, test-owned shadow seam at
`tools/community3/group_post_shadow_hook.py`. Repository inspection found no
owned real Community publication hook, so no production hook was invented or
connected. The seam remains default-off and non-delivering.

C3-IMP010 is complete as a repository audit documenting that no owned
Community post-publication implementation or authoritative hook is currently
verifiable. It creates no second hook. The existing C3-IMP009 seam remains the
bounded, non-authoritative proof only.

C3-CORE001 is complete. Read-only Sandy inspection identified the legacy Perl
publisher at `/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi`, but
the source is not owned by this repository and lacks canonical group mapping.
Authority decision: publisher exists but source is not owned/available. A
minimum publisher contract was documented; live notification attachment remains
blocked.

C3-CORE002 is complete as a read-only architecture, compatibility, and
migration audit. The legacy engine is retained as a behavioral reference and
temporary read-only compatibility boundary, not as a WordPress-native
foundation or new-write bridge. Its execution architecture is a retirement
target after URL and archive migration is proven. Six audit and recommendation
documents record the subsystem matrix, migration options, preservation
boundaries, and characterization-test plan.

C3-CORE003 is complete as the first executable, synthetic characterization
harness. It adds a pure observation model, redacted fixtures, focused tests,
and an observation contract. It performs no CGI execution, production access,
filesystem publication, database write, notification, or WordPress publisher
implementation. Unknown identity, moderation, edit/delete, mailring,
concurrency, and recovery behavior remains explicitly unverified.

C3-CORE004 is complete as the canonical Community identity and legacy
compatibility contract. New Community code will use an opaque `community_id`;
legacy path/local-path/group identifiers remain immutable compatibility keys
behind one resolver boundary. Numeric alignment is rejected, and Core Terms
and Portable Views remain relationships rather than identity authorities.

C3-CORE005 is complete as a test-only, process-local in-memory resolver. It
implements explicit resolved, missing, ambiguous, duplicate, inactive, and
orphaned states with no-guess results and immutable returned copies. It has no
persistence, production mapping, publisher, membership, notification, Core
Terms, or Portable View integration.

C3-CORE006 is complete as the WordPress-native Community publisher contract
and logical data-model design. It selects dedicated custom tables behind a
narrow repository, defines canonical post/thread identity and post-first
moderation, requires post-commit events, and preserves legacy data through
immutable compatibility records. No schema or publisher runtime was written.

C3-CORE007 is complete as the pure test-only WordPress-native publisher domain
core. It creates synthetic canonical topics/replies, validates ownership and
threading, executes lifecycle and moderation transitions, proves process-local
idempotency, and produces canonical post-commit-shaped events. No WordPress
runtime, persistence, notification, or production integration was added.

C3-CORE008 is complete as a local-only WordPress custom-table persistence
prototype in `tnet-community`. It persists synthetic domain results, audit
rows, and post-commit-shaped outbox rows transactionally, proves idempotency
and rollback seams, and has no UI, notification, migration, or production
integration.

C3-CORE009 is complete as a local DDEV developer workbench under
`tnet-community`. An authenticated administrator can publish a synthetic topic
through the local PHP adapter and inspect the persisted post/audit/event result.
The workbench is DDEV-gated, hidden from public navigation, nonce/capability
protected, and has no replies, public route, REST, notification, or production
connection. Browser visual QA was attempted but blocked by the session browser
bridge; no visual success is claimed.

C3-CORE010 is complete as the authoritative PHP Community publisher domain and
shared-fixture parity boundary. PHP now owns runtime topic/reply validation,
threading, moderation, lifecycle, idempotency, and event construction. The
workbench delegates through the PHP application service; Python remains
characterization/regression support and is not executed by WordPress.

C3-CORE011 is complete as the local Developer Sandbox extension. The existing
workbench now supports thread browsing, direct/nested synthetic replies,
hide/retract/restore/soft-delete lifecycle actions, and audit/event viewers.
No public Community UI, notification dispatch, legacy CGI, migration, or
production behavior was added.

C3-UI001 is complete as the local-only read-only Thread View v1. It renders a
seeded canonical topic, direct/nested replies, lifecycle tombstones, safe
display fields, and noindex metadata through the Community read service. Reply
composition is deferred; no sitewide navigation, public production route, or
notification UI was added. Browser visual QA was attempted but unavailable
because the browser bridge failed to initialize.

C3-UI002 is complete as the local-only Community Landing Page v1 at
`/community/`. It lists recent published topics through the repository/read
service, shows safe author display, reply counts, last activity, an empty
state, a disabled Start Discussion control, and links to the existing local
Thread View. The route is DDEV-gated and noindex. No production route,
notification, CGI, migration, schema, or persistence behavior was added. The
next bounded route ticket remains C3-URL002 — Local Canonical Community Routing
Prototype.

## Authorization Status

GO — bounded implementation preparation is authorized. This authorization does
not authorize C3-TRUST001 execution, delivery, migration, or production change
within this ticket.

## Next Authorized Ticket

C3-URL001 is complete as a bounded, documentation-only permalink and legacy
redirect contract. The selected production pattern is
`/community/{community-slug}/{thread-slug}/`; internal IDs remain hidden from
normal URLs, thread slugs are immutable after publication, and legacy URLs
redirect only after verified mapping and public-visibility checks. The six
contract documents are the URL architecture, thread permalink, slug, legacy
redirect, WordPress routing, and migration test-plan documents in this
directory. No production route, rewrite, database, sitemap, schema, or
redirect changed. The next bounded ticket is C3-URL002 — Local Canonical
Community Routing Prototype. UI002 remains gated until the permalink contract
is accepted.

C3-CORE001 is complete. Any next implementation slice requires Engineering
Director review and explicit authorization; persistence and delivery remain
deferred.

## Next Decision

OPS-BRANCH001 is complete as branch/worktree recovery and process isolation.
C3-UI003 is unblocked only from `/tmp/community3-ui-working` on
`COMMUNITY3-ui-working`; do not implement it from the mixed recovery branch.

Review C3-CORE001 and explicitly authorize source ownership/compatibility work
before any live hook integration or persistence work.
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

C3-UI003 is implemented locally at `/community/new/`. It is DDEV-gated,
authenticated, nonce-protected, uses the authoritative PHP publisher
application and repository, validates a bounded local Community selection, and
redirects successful publication to Thread View. Automated verification is
complete; human responsive browser QA remains required. No production, CGI,
notification, migration, or Core Terms behavior changed.
persistence or delivery was added.

C3-IMP005 is complete as a test-only in-memory bell repository at
`tools/community3/notification_bell_repository.py`, with tests and interface
documentation. It consumes only eligible validated candidates, preserves bell
state separately from delivery and engagement, and adds no persistence or UI.

C3-OPS001 checkpoint: the Community branch is now persistent at
`/home/bobreap/projects/teachers-net-community3` on `COMMUNITY3-ui-working`,
with dedicated DDEV project `teachers-net-community3` and hostname
`https://teachers-net-community3.ddev.site`. Runtime configuration exists, but
web/db health and browser reachability remain blocked at the current checkpoint;
no database, activation, schema, rewrite, or production change was made.

C3-OPS002-DIAG001 diagnosed the dedicated HTTP 502 as the absent ignored
WordPress bootstrap/runtime at the persistent worktree's configured docroot.
Restoring the local runtime resolved the infrastructure 502; the dedicated
hostname now returns the WordPress installation redirect. The dedicated
Database remains uninstalled and no import, URL update, activation, schema, or
rewrite operation has been performed. The original runtime and production are
untouched.

C3-OPS003 resumed Community development against the existing local Teachers.Net
clone. The dedicated runtime experiment is suspended. The Community plugin is
mounted read-only from the isolated branch into the existing local DDEV web
container. Landing, Thread View, anonymous composer redirect, authenticated
composer rendering, and successful publication were verified. The final QA
Thread View was `/community/thread/post:8e78f134fdbc8a86/`. Two narrow composer
defects found during QA were corrected: Community identifier sanitization and
encoded-colon redirect construction. The next ticket is C3-UI004.
