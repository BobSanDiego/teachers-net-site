# Community 3.0 Engineering Handoff

## 2026-09-05 Workflow Continuity Update

The current Community Workflow V2 terminal cursor is
`COMMUNITY-LEGACY-CHATBOARD-MIGRATION001-CONT2`, cycle `260905171732`.
The cycle is finalized and validated with state `partial` /
`HUMAN_QA_PENDING`: the AI in Education dedicated-board route and local source
bridge are proven, while native browser acceptance remains pending because the
registered browser bridge was unavailable. The matching Report, manifest,
cycle JSON, ledger, runtime evidence, and operational-current-state are in
`tmp/hopper/community/` under the registered control-plane repository.

This is the current legacy Chatboard migration continuity boundary. It does not
reopen settled Shared Shell, Home, notification, profile, or Community 3.0
contract decisions, and it does not supersede the historical recovery and
roadmap material retained below. Sandy/production remains untouched.

Screenshot evidence governance: Engineering Director screenshots are mandatory
evidence when a ticket requires them. Codex must access and inspect the actual
attachment through the supported attachment/conversation route, reconcile
Windows/WSL and hopper locations, and record the accessible identifier/path.
After one bounded repair attempt, unavailable evidence is a stop condition;
Codex must not substitute its own screenshots, DOM output, source inspection,
or automated assertions.

## Product-First Execution Governance

`ENGINEERING-GOV001 v2 — Product-First Execution Governance` is adopted for
future Community 3.0 tickets. Each ticket names one active UX006–UX015
milestone, advances only that milestone, reuses accepted diagnostics, performs
one structured verification sweep, and stops when the objective is proven.
Runtime, browser, DDEV, parser, and tooling work is support-only. Reports begin
with roadmap status and state implementation, verification, remaining
capability, and risk. No later milestone is authorized by infrastructure work.

## 1. Current Phase

Bounded implementation preparation — C3-CORE001 complete; no delivery implementation begun.

## 1A. Active Browser-First Recovery Gate

`COMMUNITY-RESTART001 — Browser-First Recovery Sprint` supersedes historical
browser-facing UX acceptance for this bounded local recovery task only. Verify
the exact canonical URL
`https://teachers-net-community3.ddev.site` before implementation and record
the runtime badge, branch, commit, plugin hash, controller, and authenticated
before screenshots.

The sole implementation objective is Modern Topic Composer v1: remove the
visible Image Alt field, Representative Link selector, and Preview selector
without changing shared media architecture, uploads, validation, publication,
routing, repository, or schema behavior. Acceptance requires after screenshots
showing all three controls absent while the runtime badge remains valid. Stop
immediately after the validated before/after evidence and hopper payload; no
additional UX work is authorized.

## 1C. COMMUNITY-RESTART003 Handoff Result

`COMMUNITY-RESTART003 — Modern Media Picker v1 + Hopper Governance Recovery`
is complete for its single local browser-visible correction. At the
authenticated canonical route
`https://teachers-net-community3.ddev.site/community/new/`, the composer
shows one `Add Photo` camera action and no visible native file input. Existing
chooser, paste, drag/drop, preview, remove, validation, upload, publication,
routing, repository, schema, and shared-media behavior remain in place.
Acceptance was captured once at 1440px; runtime status was `ok`.

The implementation is Community commit
`80878116c05eac550b214079046b180c853415f4` on `COMMUNITY3-ui-working`, pushed
to the remote branch. The completion report is
`docs/community-3.0/community-restart003-recovery-report.md`.

The authoritative handoff hopper is
`/home/bobreap/projects/teachers-net-site/tmp/hopper/tnet-3.0/current`, not a
Community-repository hopper. Cycle `260804004439` archived all prior current
contents and passed validation. Screenshots are referenced by canonical path
and are not copied into the hopper. Stop here: no further correction is
authorized by this ticket.

## 1B. Recovery Result

The single correction is complete and browser-verified at the canonical URL.
The authenticated after-state omits all three target controls, and the runtime
badge reports `status=ok` for the Community DDEV project, worktree, branch,
commit, plugin hash, route, and controller. See the Community worktree reports
`community-restart001-resolution-v1.md` and
`community-restart001-browser-gap-matrix-v1.md`. Stop pending explicit
acceptance; do not begin another UX slice.

## 2. Current Ticket

C3-URL001 is complete as a documentation-only routing decision and
compatibility design. It selects `/community/{community-slug}/{thread-slug}/`
for public canonical threads, keeps opaque IDs internal, freezes published
thread slugs, and requires verified mappings before legacy redirects. Its six
documents are in `docs/community-3.0/` under the URL architecture, thread
permalink, slug, legacy redirect, WordPress routing, and migration test-plan
names. No production routing, rewrite, sitemap, schema, database, or redirect
was changed. C3-URL002 — Local Canonical Community Routing Prototype is next;
retain the current DDEV-only `/community/thread/{post_id}/` route only as an
explicit temporary local alias while testing the new contract.

C3-RR001, C3-PLAN003, C3-PLAN004, C3-TRUST001, C3-TRUST002, C3-NOT001, C3-NOT002, C3-NOT003, and C3-NOT004 are complete as
documentation/read-only evidence work. The accepted subscriber-policy contract
is `docs/community-3.0/subscriber-policy-contract-v1.md`; the accepted
suppression and abuse-control contract is
`docs/community-3.0/suppression-and-abuse-control-contract-v1.md`; the accepted
domain-event and notification contract is
`docs/community-3.0/domain-event-and-notification-contract-v1.md`; the accepted
bell and read-state contract is
`docs/community-3.0/bell-and-read-state-contract-v1.md`; the accepted reply
notification contract is
`docs/community-3.0/reply-notification-contract-v1.md`.
`docs/community-3.0/reply-notification-contract-v1.md`; the accepted reaction
notification contract is
`docs/community-3.0/reaction-notification-contract-v1.md`.
The accepted group-activity notification contract is
`docs/community-3.0/group-activity-notification-contract-v1.md`.
The C3-IMP001 readiness, gap, and sequence documents are
`docs/community-3.0/notification-implementation-readiness-v1.md`,
`docs/community-3.0/implementation-gap-analysis-v1.md`, and
`docs/community-3.0/notification-implementation-sequence-v1.md`. They identify
the missing Mention Notification Contract and propose C3-IMP002 as a test-only
dry-run evaluator, pending explicit authorization.
The C3-IMP003 candidate/audit boundary is implemented in
`tools/community3/notification_candidate_boundary.py`, covered by
`tools/community3/test_notification_candidate_boundary.py`, and documented in
`docs/community-3.0/candidate-audit-boundary-v1.md`. It is test-only,
non-persistent, redacted, and non-delivering.
The C3-IMP004 process-local candidate store and continuity flush correction are
implemented in `tools/community3/notification_candidate_store.py` and
`tools/hopper/validate_community_continuity.py`, with documentation in
`docs/community-3.0/candidate-store-interface-v1.md` and
`docs/community-3.0/continuity-flush-diagnostic-v1.md`.
C3-IMP002 has no verified implementation commit and is not complete. C3-NOT005
has no verified contract or implementation commit and is not complete.
C3-IMP005 is complete as a test-only in-memory bell repository at
`tools/community3/notification_bell_repository.py`, covered by
`tools/community3/test_notification_bell_repository.py`, and documented in
`docs/community-3.0/bell-repository-interface-v1.md`. It consumes only eligible
validated candidates and preserves bell state separately from delivery and
engagement.
The C3-IMP006 end-to-end dry-run pipeline is implemented in
`tools/community3/notification_dry_run_pipeline.py`, covered by
`tools/community3/test_notification_dry_run_pipeline.py`, and documented in
`docs/community-3.0/dry-run-notification-pipeline-v1.md`. It remains test-only,
non-persistent, and non-delivering.
The C3-IMP007 application service is implemented in
`tools/community3/notification_application_service.py`, covered by
`tools/community3/test_notification_application_service.py`, and documented
in `docs/community-3.0/notification-application-service-v1.md`. It is the sole
public entry point for the current synthetic `group_post` dry run.
C3-IMP007 is complete as the current source milestone; no delivery behavior is
authorized.
The C3-IMP008 fixture-backed group-post adapter is implemented in
`tools/community3/group_post_event_adapter.py`, covered by
`tools/community3/test_group_post_event_adapter.py`, and documented in
`docs/community-3.0/group-post-event-adapter-v1.md`. It remains disconnected
from live hooks and production data.
C3-IMP008 is complete as the current source milestone; no additional event
family or delivery behavior is authorized.
The C3-IMP009 shadow seam is implemented in
`tools/community3/group_post_shadow_hook.py`, covered by
`tools/community3/test_group_post_shadow_hook.py`, and documented in
`docs/community-3.0/group-post-shadow-hook-v1.md`. Repository inspection found
no owned real Community publication hook, so this remains a test-owned model
and is not connected to production.
C3-IMP009 is complete as the current source milestone; no live publication hook
or delivery behavior is authorized.
The C3-IMP010 publication integration audit found no owned authoritative
Community publisher seam. Its integration map and hook-authority decision are
`docs/community-3.0/community-publication-integration-map-v1.md` and
`docs/community-3.0/publication-hook-authority-v1.md`. The C3-IMP009 seam
remains test-owned and non-authoritative.
C3-IMP010 is complete as the current source milestone; no live publisher hook
or delivery behavior is authorized.
C3-CORE001 is complete. It identified the Sandy legacy Perl publisher at
`/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi`. It exists in
production but is not owned or available in this repository and does not expose
canonical group mapping. Live notification attachment remains blocked. The
source census, lifecycle, authority decision, and minimum contract are the
four C3-CORE001 documents.
C3-CORE002 is complete as the follow-on read-only architecture and migration
audit. The recommendation is to preserve behavior as reference and provide a
temporary read-only compatibility boundary, while rebuilding new writes
natively in WordPress and retiring the legacy CGI execution path after URL and
archive migration is proven. Six C3-CORE002 documents capture the decision.
C3-CORE003 is complete as a local-only characterization baseline. The pure
model and synthetic fixtures characterize accepted topics/replies, validation,
abuse classification, legacy URL/timestamp shape, chat_posts fields,
local_path versus group identity, duplicate and partial-write classes, and
immutable archive metadata. No production or external side effect is possible
through the harness; unknown behavior remains explicit.
C3-CORE004 is complete as the canonical identity contract. The target uses an
opaque `community_id`, retains legacy path/local-path/group references without
renumbering, and confines translation to a resolver/repository boundary. Core
Terms classifies the entity and Portable Views present it; neither replaces
Community identity. The next implementation is a test-only in-memory resolver
and mapping-registry contract.
C3-CORE005 is complete as the corresponding test-only in-memory resolver. It
resolves legacy path/local/group references only through explicit synthetic
mappings, preserves unresolved states, rejects silent overwrite, and deep-copies
returned references/context. No persistence or product integration exists.
C3-CORE006 is complete as the publisher domain, lifecycle, storage, logical
model, event, compatibility, and test contracts. Dedicated custom tables are
preferred behind a narrow repository; WordPress identity and canonical
community/post IDs remain authoritative. New posts use post-first moderation,
and notification events occur only after commit. No schema or runtime publisher
was added. The next slice is C3-CORE007, test-only domain core.
C3-CORE007 is complete as that pure domain core. Topics, direct/nested replies,
validation, post-first/pending moderation, lifecycle transitions, idempotency,
and canonical event construction are executable against synthetic data only.
Persistence, WordPress runtime, notifications, forms, and migration remain
deferred. The next boundary is persistence implementation authorization.
C3-CORE008 is complete as the local-only persistence prototype owned by
`wordpress/wp-content/plugins/tnet-community/`. It defines opt-in schema,
repository transactions, audit/event outbox storage, idempotent retrieval, and
rollback injection tests. No production tables, routes, UI, dispatch, or
migration were changed. Next is local developer-facing verification/QA.
C3-CORE009 is complete as the local authenticated developer workbench at
`Tools -> Community Publisher Workbench`. It publishes synthetic topics and
displays persisted post/audit/event information through `tnet-community`.
Runtime language divergence is documented as a bounded PHP adapter over the
Python-tested contract. Automated checks and DDEV smoke/rollback checks pass;
browser visual QA was attempted but the browser bridge could not initialize.
C3-CORE010 is complete. PHP is the authoritative runtime domain under
`tnet-community`; the workbench no longer constructs canonical publisher
objects manually. Topics and replies share the PHP domain, and the shared JSON
fixture/parity checks preserve semantic agreement with Python regression
evidence. No Python runtime bridge, production deployment, or migration exists.
C3-CORE011 is complete as the local sandbox lifecycle extension. Thread,
reply, lifecycle, audit, and event controls are available through the existing
authenticated workbench and remain DDEV-only. No visual redesign or public
Community interface was begun.
C3-UI001 is complete as the first local Community-facing read-only Thread View.
The route renders seeded topics/replies and retracted tombstones through the
repository-backed read service. It is DDEV-gated, noindex, navigation-free,
and does not include a reply composer. Visual browser QA remains pending due to
the unavailable browser bridge.
C3-UI002 is complete as the local DDEV-only Community Landing Page v1 at
`/community/`. It uses the Community repository/read-service boundary to list
recent topics, safe author display, reply counts, last activity, empty state,
and links to the existing Thread View. Start Discussion is disabled as
Coming next. Browser snapshot and PNG evidence passed for the seeded state;
the linked Thread View was also verified. No production behavior changed.
Next bounded ticket: C3-URL002 — Local Canonical Community Routing Prototype.
OPS-CONT001 is complete as a continuity and hopper process audit. The durable
governance and validation documents are
`docs/process/community-continuity-root-cause-v1.md`,
`docs/process/community-hopper-governance-v1.md`, and
`docs/process/community-continuity-validation-v1.md`.
C3-GO001 records GO authorization for bounded implementation preparation. The
master plan is the product authority above engineering roadmaps and tickets.

## 3. Last Completed Milestone

Teacher-group operations were corrected to resolve the canonical group through
`tnet_local_data.local_path -> tnet_groups.local_path ->
tnet_groups.group_id`. The hidden legacy assumption that `path_id` and
`group_id` are interchangeable is no longer used for membership operations.

The correction covered global group state, chatboard modal/settings reads,
group-join mail-frequency lookup, Chat Center member counts, header star,
sidebar membership/count/avatar presentation, and temporary diagnostic cleanup.

## 4. Verification Record

The completed work was reported verified for join, leave, reload persistence,
header and sidebar membership state, member counts, avatars, group settings,
email-frequency persistence, Chat Center counts, the divergent AI in Education
board, and a legacy board control.

## 5. Architectural Caution

`path_id` remains the chatboard/post/feed identity. It must not be used as a
teacher-group or membership identity without an explicit mapping to the
canonical `tnet_groups.group_id`. Downstream templates should prefer the
canonical preloaded group state and retain only bounded fallbacks for partial
legacy execution paths.

## 6. Process Lessons

- Debug from returned server state rather than inferring from a stalled UI.
- Resolve the shared identity assumption instead of treating each symptom as a
  separate defect.
- Compare a divergent record with a legacy control record.
- Remove temporary HTML, logging, comments, and dump/exit diagnostics before
  milestone closure.

## 7. Strategic Documentation Alignment

The current Community 3.0 roadmap is
`docs/community-3.0/roadmap.md`. It captures the shift from application-centric
planning toward a semantic platform whose subscribers consume Core Terms,
Portable Views, Subscriber Policies, Relationship Graphs, and the
Communications Platform without surrendering product authority.

The next planning sequence is authority alignment, Core Terms/meta-term audit,
subscriber contracts, Portable View governance, a bounded Job Center View
pilot, chatboard/group mapping, interest and onboarding evidence, communications
consent and event architecture, relationship approval, explainable
recommendation proof, and only then product-by-product subscriber expansion.

Semantic Studio remains a planning concept for a future governance surface; it
is not an approved implementation. Open decisions and stop conditions remain
in the roadmap and the execution-plan companion.

The synchronized Google Drive handoff is:
<https://docs.google.com/document/d/1oxqqgFHkPwrJQpQ563-hho0jPf_MWrTEPE_qCJa-BeY>

## 8. Recently Approved Visual References

None. The current milestone is the identity correction and documentation
alignment; no new visual authority is established by this handoff.

## 9. Active Design Authority

The canonical Semantic, Community, and Communications Platform working draft,
the Semantic Platform Project Approach and Execution Plan, the Community 3.0
roadmap, and the teacher-group identity correction record. Preserve the
distinction between verified implementation, approved direction, proposed
design, exploratory concept, and deferred work.

## 10. Immediate Engineering Priorities

Review C3-CORE001 and decide whether to authorize source ownership and a
read-only compatibility boundary before any live hook integration or
persistence slice.
persistence or channel slice. Keep delivery disabled. Stop before mail sending,
preference migration, delivery enablement, production edits, relationship
activation, or unrelated Job Center implementation.
Any follow-up evidence gathering remains read-only. Stop before mail sending,
schema changes, preference migration, delivery enablement, production edits,
relationship activation, or unrelated Job Center implementation.
