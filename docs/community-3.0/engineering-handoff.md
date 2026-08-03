# Community 3.0 Engineering Handoff

## 1. Current Phase

Branch authority: Community implementation work uses
`/home/bobreap/projects/teachers-net-community3` on `COMMUNITY3-ui-working`.
The canonical local review runtime is DDEV project `teachers-net-community3`
at `https://teachers-net-community3.ddev.site`, with the complete plugin tree
mounted from that worktree.
The original `COMMUNITY003-semantic-community-communications-working-draft`
is preserved as a mixed recovery workspace and must not receive new Community
or Job Center ticket edits. Preflight and Community hopper payload validation
are required before edits and handoff. The mandatory runtime gate is
`docs/community-3.0/community-runtime-authority-preflight-v1.md`: any runtime,
mount, branch, or commit mismatch stops execution pending explicit user
acknowledgment. UX reports must identify the canonical URL, DDEV project/path,
mounted plugin path, branch, commit, and browser evidence status; code
inspection alone cannot establish browser acceptance.

RUNTIME-BLOCKER001 is resolved. The dedicated `teachers-net-community3` DDEV
runtime is running at `https://teachers-net-community3.ddev.site`, and its
mounted plugin tree matches the complete Community3 worktree. Do not use
`teachers-net.ddev.site` for Community acceptance. The resolution record is
`docs/community-3.0/runtime-blocker001-resolution-v1.md`; the next gate is
authenticated responsive browser evidence, not feature implementation.

Bounded implementation preparation — C3-CORE001 complete; no delivery implementation begun.

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

OPS-BRANCH001 is complete. The next Community ticket, C3-UI003, may begin only
from `/tmp/community3-ui-working` on `COMMUNITY3-ui-working`. The recovery
worktree contains no C3-UI003 implementation.

Review C3-CORE001 and decide whether to authorize source ownership and a
read-only compatibility boundary before any live hook integration or
persistence slice.
persistence or channel slice. Keep delivery disabled. Stop before mail sending,
preference migration, delivery enablement, production edits, relationship
activation, or unrelated Job Center implementation.
Any follow-up evidence gathering remains read-only. Stop before mail sending,
schema changes, preference migration, delivery enablement, production edits,
relationship activation, or unrelated Job Center implementation.

C3-UI003 is implemented in
`wordpress/wp-content/plugins/tnet-community/includes/class-tnet-community-topic-composer-controller.php`.
The local authenticated `/community/new/` form uses the PHP publisher
application, nonce and capability checks, bounded Community selection,
generated idempotency key, server-side validation, and a Post/Redirect/Get to
Thread View. Automated checks are complete; desktop, tablet, and mobile human
visual QA remains pending. No production or delivery behavior changed.

C3-OPS001 is an incomplete bounded runtime-alignment checkpoint. The persistent
Community worktree is `/home/bobreap/projects/teachers-net-community3` on
`COMMUNITY3-ui-working`; the dedicated DDEV project is
`teachers-net-community3`. The ignored WordPress runtime files required for the
DDEV docroot were copied locally while preserving the branch's Community
plugin. DDEV web/db health did not remain available and the hostname returned
502, so database alignment, plugin activation, schema installation, rewrite
flush, and C3-UI003 browser QA remain pending. The original `teachers-net`
runtime/database was not modified.

C3-OPS002-DIAG001 diagnosed the dedicated HTTP 502: the persistent worktree
lacked the ignored WordPress bootstrap/runtime required by the configured
`wordpress` docroot. After restoring the local runtime, both dedicated DDEV
containers are healthy and the hostname returns HTTP 302 to WordPress install.
The database is not installed; no import, URL update, plugin activation,
schema install, rewrite flush, or product QA has been performed. Continue with
local database/runtime initialization only; the original runtime and
production remain untouched.

C3-OPS003 suspended the dedicated Community runtime experiment and resumed
validation against the existing `teachers-net` clone. The branch plugin is
mounted read-only into the existing local DDEV web container. Landing, seeded
Thread View, anonymous login redirect, authenticated composer rendering, and
successful publication were verified. Final QA Thread View:
`/community/thread/post:8e78f134fdbc8a86/`. Composer fixes preserved the
canonical Community identifier and Thread View colon, and added a carried
submission identifier for replay deduplication. Next ticket: C3-UI004.

C3-UI004 is complete. The Thread View now has authenticated direct and nested
reply forms backed by the existing PHP publisher flow. Parent/thread and
eligibility checks remain in the domain/repository boundary; forms use nonces,
capability checks, carried submission identifiers, and anchored PRG redirects.
Direct and nested browser QA passed at the existing clone. Next ticket:
C3-UI005.

C3-ARCH001 is complete as the adopted Community Thread Architecture Contract
v1. The visible model is one topic, flat L1 comments, and flat L2 descendants
with unlimited logical targeting. Exact parent lineage remains stored; the
selected hybrid model adds future branch-root and explicit-target fields without
executing schema or migration work. Next implementation ticket: C3-ARCH002 —
Community Thread Branch and Reply-Target Data Support.

C3-ARCH002-AUDIT001 confirms one Community thread engine for standalone and
future attached discussions. Subject products retain subject ownership; the
selected near-term model is a validated subject-reference value object plus
future nullable attachment fields, not a new universal conversations table.
No schema, migration, feed, notification, UI, or production work was done.
Extend C3-ARCH002 only with the required subject-identity compatibility tests.

C3-ARCH002 is now complete locally. The schema is version 2 and upgrades
additively/idempotently with nullable `conversation_root_id`,
`reply_to_post_id`, `reply_to_author_id`, `owner_product`, `subject_type`,
`subject_id`, `source_namespace`, and `subject_revision` fields plus indexes
for the required query paths. `TNet_Community_Subject_Reference` validates
standalone Community topics and synthetic Lesson Bank/Teachers.Net attached
identities. The publisher domain derives nearest-L1 roots, preserves exact
parents, snapshots target authors, rejects missing/cyclic/cross-thread,
cross-community, restricted, malformed, and unsupported references, and the
repository persists the fields transactionally. Required implementation and
QA documents are present in `docs/community-3.0/`. No rendering, UI,
notifications, migration/backfill, or production work was performed. The next
ticket is C3-ARCH003: flat L1/L2 rendering and explicit targets.

C3-ARCH003 is complete as a bounded local rendering increment. Thread View
projects topic -> chronological L1 branches -> one flat L2 layer, with exact
parent lineage preserved. It derives legacy NULL branch identity safely,
prevents cycles from hanging, suppresses hidden L1 branches as units, and
suppresses hidden L2 replies individually. Every L2 reply exposes safe target
context and a stable `#reply-post:{opaque-id}` anchor. The composer is now one
topic-level form with an explicit target selector; one-open JavaScript and
dirty-state warnings remain deferred. DDEV lint, HTTP smoke, synthetic
projection, anchor, and removal checks passed. Browser viewport screenshots
remain pending because browser control could not initialize in this WSL
session; do not claim visual completion until canonical Chrome QA is rerun.

C3-PUB001-AUDIT001 is complete as a documentation-only audit. Six publishing
artifacts are present in `docs/community-3.0/`: capability, rich-media,
link-enrichment, feed-card, feed-balance, and composer-matrix contracts. Plain
text is V1; rich media and specialized modes are planned behind storage,
security, accessibility, moderation, lifecycle, feed, notification, and
subject-compatibility contracts; AI remains assistive and deferred. No code,
schema, migration, UI, feed, notification, or production work was performed.
Next ticket: C3-PUB002 — Community Rich Composer Foundation.

C3-PUB002 is complete as a bounded local foundation. `/community/new/` now
uses modular native sections for context, writing, and progressive attachment
disclosure. Post-mode placeholders are Discussion, Question, Candle, and Idea;
all continue through the ordinary text topic publisher. Empty attachments and
link references are represented in existing compatibility metadata, with no
uploads, enrichment, schema, storage, feed, notification, AI, or production
behavior. DDEV lint, route protection, markup, and ordinary-text publication
checks passed. Authenticated browser screenshots remain pending because browser
control was unavailable in the WSL session. Next ticket: C3-PUB003 — Community
Link Enrichment and Preview Pipeline.

C3-PUB003 is complete as a fixture-only local link preview foundation. The
attachment service and cached preview model implement deterministic fixture
metadata, keep/remove/raw author choices, a composer placeholder, graceful
raw-link fallback, and repository persistence through existing compatibility
JSON. Live Open Graph/Twitter/oEmbed fetching, SSRF networking, uploads, feeds,
notifications, schema, and production remain out of scope. DDEV lint, fixture
choice checks, composer markup, and cached-preview round-trip passed. Next
ticket: C3-PUB004 — Community Rich Media Attachments.

C3-PUB004 is complete as a local fixture-only rich-media foundation. Validated
image/video/audio/document records persist in existing compatibility JSON; the
composer supports one fixture at a time, required image alt text, and removal;
Thread View renders safe cards with readable fallbacks. No uploads, remote
sources, embeds, scripts, data URLs, production storage, feeds, notifications,
or real media services were added. The production upload readiness gap lists
durable storage, authority, MIME inspection, malware quarantine,
derivatives/transcoding, CDN/private access, moderation/copyright,
retention/deletion, and quotas/rate limits. Browser screenshots remain pending
because browser control was unavailable. Next ticket: C3-PUB005 — Safe Live
Link Metadata Fetching Readiness and Adapter audit.

C3-PUB005 is complete as a repository/threat-model audit. Decision: NOT READY
for live retrieval. Fixture preview interfaces and compatibility JSON remain
the authority. The nine documents define URL admission, private-address and
DNS-rebinding controls, bounded transport, extraction/sanitization, provider
policy, cache lifecycle, interface separation, and deterministic mock tests.
No live DNS/HTTP, schema, feed, notification, or production work was done.
Next ticket: C3-PUB006 — Mocked Safe Link Fetch Policy and Adapter.

C3-PUB006 is complete as a deterministic no-network implementation. The
Community application service now coordinates URL admission, mock destination
authorization, bounded transport fixtures, extraction/sanitization, provider
classification, cache lifecycle, and fallbacks. Focused threat tests and
explicit no-network assertions pass; the visible fixture preview remains
unchanged and no public diagnostic route was needed. Reassessment: READY WITH
REQUIRED PRECONDITIONS for a separately authorized allowlisted pilot, not ready
for general live retrieval. Next ticket: C3-PUB007 — isolated allowlisted
live-fetch pilot design/approval.

C3-FEED001 is complete as a bounded local activity-feed implementation. The
`/community/` route now projects text-first mixed cards from existing
repository data, including safe fixture media and mocked link-preview
presentation, with author/title/excerpt/activity/reply metadata and canonical
Thread View links. Publication/moderation boundaries remain intact. No
ranking, personalization, infinite scroll, ads, live fetching, notifications,
AI, uploads, or production behavior was added. DDEV lint, HTTP smoke, mixed
content, card-link, and diff checks passed. Browser screenshots remain pending
because browser control was unavailable. Next ticket: C3-FEED002 — Community
Feed Card Polish and Responsive Refinement.

C3-COMP001 is complete as a bounded local natural-composer foundation. The
four C3-COMP001 documents in this directory are the authority. Browser
screenshots and interaction acceptance remain pending; review URL:
`https://teachers-net.ddev.site/community/new/`. Do not begin live link
enrichment, additional media families, production uploads, or inline document
serialization. Preferred next ticket: C3-FEED002 — Community Feed Card Polish
and Responsive Refinement.

C3-COMP001-FIX001 is the completed acceptance-correction pass. Paste, chooser,
and drop share one staging path; dropped files enter the submitted input;
preview/remove/replace state is explicit; PNG and WebP render as safe local
upload URLs; alt is checked before upload and attempted uploads are cleaned on
later failure. Browser QA remains pending because browser control was
unavailable. Do not begin FEED002 or live/media expansion before human browser
acceptance.

C3-COMP002 is partially bounded around shared authoring/rendering foundations:
the new Community authoring helper provides escaped Markdown rendering and
Thread View reply context no longer exposes topology selectors. PHP lint
passes. Browser QA remains pending. Do not infer completion of full shared
topic/reply media parity until the remaining composer extraction and browser
review are explicitly completed.

C3-FEED002 adds the Community-owned visual-language stylesheet and local route
injection for Feed, Composer, and Thread View. It supplies shared shell,
spacing, card, metadata, focus, media-bound, and responsive rules while
leaving queries, schemas, routes, publishing, attachments, previews, and
replies unchanged. Required screenshots and direct browser review are still
pending. Do not treat this as visual acceptance or begin the next ticket until
the named widths and mixed-content states are reviewed.

UX003-DIAG001 is complete as a diagnostic-only refactor map. Do not merge the
controllers. Extract shared pure services and a view partial in narrow steps;
retain topic-only fields and reply parent/thread, nonce, idempotency, PRG, and
publication responsibilities in their respective controllers. Next ticket:
the first extraction of URL detection, Markdown help, and staged-media
contracts.

UX003-REF001 is complete as the first reversible extraction. The topic
controller delegates pure HTTPS URL detection to the shared contract class;
the staged-image contract and focused PHP test are present. No reply upload,
shared view, route, publication, or schema work was performed. Next:
UX003-REF002 — Shared Composer View Partial for Topic.
