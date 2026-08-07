# Durable Views Project Cursor

## Project State

Stabilization

## Workstream

Teachers.Net Durable Views System — shared presentation platform for Core Terms
consumers.

## Mission

Build the smallest coherent, platform-owned Durable Views capability that
unblocks the Job Center while preserving the long-term path for Community,
Lesson Bank, Marketplace, Directories, Search, Notifications, Messaging,
Analytics, AI, and future Teachers.Net consumers.

## Non-Negotiable Boundaries

- Core Terms answers what exists; Views answer what an audience sees.
- Core Terms remains the sole taxonomy authority.
- Views reference canonical Core Term UUIDs and never copy taxonomy.
- Views are platform-owned; products consume them.
- Jobs owns job data, authorization, lifecycle, and consumer behavior.
- Views are not permissions, search, recommendations, identity, or analytics.
- Existing Meta-Groups remain a distinct Core Terms capability and are not
  silently converted into Views.
- Do not rename `profilaxes`, `CFM`, `cfm_`, tables, routes, slugs, or
  namespaces.

## Current Phase

Phase 5 — MVP certification complete; next-consumer authorization pending.

## Current Status

- DV-FIX002 completed on 2026-08-07. `CFM_Views_Repository::delete_entries()`
  now computes canonical included-descendant closure within the requested draft
  version and framework before mutation. Parent-only and browser end-to-end
  removal passed; the Current View orphan-removal defect is closed at the
  repository boundary.

- DV-FIX001 completed on 2026-08-07. Current View parent removal selection now
  computes recursive included descendants, marks them inherited/muted and
  disabled, mirrors their IDs into the removal payload, and restores state when
  the root is cleared. Repository persistence closure remains the explicit next
  objective: DV-FIX002.

- DV-UX014 completed on 2026-08-07. Library fresh-load state is collapsed,
  Expand all and Collapse all operate across the complete tree, generation
  alignment is fixed, and top-level bulk controls have stronger action weight
  without mouse-focus artifacts. Browser QA passed with selection preservation.

- DV-UX013 completed on 2026-08-07. Top-level Library checkboxes were replaced
  with stable `+ / −` bulk descendant controllers. Controllers exclude
  represented descendants, never submit top-level UUIDs, synchronize on
  descendant changes, and reserve alignment space. Authenticated browser QA
  passed for plus, mixed plus, minus, and the no-controller render state.

- DV-UX012 completed on 2026-08-07. The Library now uses fixed-generation
  alignment, represented terms have unavailable checkbox space with blue name
  treatment and subtle tint, and every top-level branch has a controller
  checkbox whose descendant selections exclude the top-level UUID. Authenticated
  browser checks passed at the canonical editor URL.

- DV-UX011 completed on 2026-08-07. The editor now presents the canonical
  Library and Current View as compact recursive checkbox trees; manager names
  are lifecycle links; draft deletion is available from the manager; legacy
  per-entry presentation editors and active drag affordances are omitted for
  canonical Current View entries. Browser verification passed at the canonical
  editor URL with version 17, including disclosure behavior, indentation,
  removal controls, and no console errors observed.

- Authority package and supporting transcripts ingested.
- Existing Core Terms governance and continuity documents inspected.
- Existing Core Terms capability and integration contracts inspected.
- Existing Jobs/Core Terms integration seam audited and documented.
- No durable View object, View Version model, resolver, administration, or
  Job Center View binding has been implemented.
- MVP assessment and ticket sequence approved by instruction to begin.
- DV-001 completed.
- DV-002 schema contract completed.
- DV-003 persistence foundation verified, committed, and pushed as `71ce3fb`.
- DV-004 lifecycle controls verified, committed, and pushed as `f3dfedb`.
- DV-005 validation and deterministic resolution verified, committed, and pushed as `1d5e477`.
- DV-006 draft group/entry authoring and current-view consumer seam verified, committed, and pushed as `02c6399`.
- DV-007 protected administration surface verified, committed, and pushed as `74dd68c`.
- DV-008 preview and complete clone behavior verified, committed, and pushed as `db591f9`.
- DV-009 retire and restore controls verified, committed, and pushed as `ed79f3f`.
- DV-010 consumer service boundary verified, committed, and pushed as `83eebfb`.
- DV-011 Jobs binding to a published View verified, committed, and pushed as `e6e3a2f`.
- DV-012 parallel migration and rollback adapter verified, committed, and pushed as `2f31a93`.
- DV-013 Job Center consumer certified; MVP closure evidence recorded.
- DV-014 handoff refreshed; MVP closeout recorded and Community named as next
  candidate pending explicit authorization.
- DV-015 Community consumer seam assessment completed read-only; source ownership
  and compatibility boundary are required before implementation.
- DV-016 Community source ownership boundary confirmed; external authorized
  source access is required before further work.
- DV-018 Job Center sprint readiness and authoring gap audit completed; browser
  authoring and live Jobs cutover remain incomplete.
- DV-019 protected draft composition workspace implemented in Profilaxes;
  framework/term selection, include/exclude, label/order, descendant intent,
  draft entry listing, and draft-only removal are available. PHP and DDEV
  runtime checks passed; authenticated browser certification remains pending.
- DV-020 groups, draft preview, and visible validation feedback implemented in
  Profilaxes. Authenticated browser certification remains pending.
- DV-021 protected Jobs administrator binding controls implemented in the Jobs
  plugin. The control offers only current published View versions and delegates
  binding validation/resolution to the existing Jobs service boundary.
- DV-022 live Jobs option resolution now prefers a valid published Durable View
  binding and falls back to the existing Jobs-owned compatibility path when no
  binding or resolution is available. Runtime option smoke test returned 22
  options without a binding; browser certification remains pending.
- DV-023 browser certification completed: authenticated admin authoring,
  validation, preview, publish, Jobs binding, employer-form resolution through
  an active local QA membership, and unbound legacy fallback all pass. The
  temporary QA membership was deactivated and its employer archived through
  the established Jobs services; the Durable View binding was restored.
- DV-DOC001 updated the Durable Views Administrator Manual to describe the
  current browser implementation, explicit limitations, and the certified
  Job Center bind/unbind/fallback/restore workflow.
- DV-UXAUD001 completed a read-only Core Terms Workbench reuse audit. It
  recommends an adapted split-pane, read-only canonical term browser plus
  View-owned composition canvas; broad shared asset extraction and nested
  presentation groups remain deferred.
- DV-UX001 implemented and browser-verified the independently namespaced
  split-pane Views workbench shell with read-only canonical discovery, search,
  hierarchy expand/collapse, canonical context, and Add to Draft handoff into
  the existing draft form. JobLister View 10 / Version 12 and Jobs binding 10:12
  remained unchanged.
- DV-UX002 implemented a composition-first draft canvas with visual group
  containers, entry cards, empty-state guidance, inline presentation controls,
  and explicit Up/Down draft ordering. Discovery, persistence, publication,
  and Jobs behavior remained within their existing boundaries.
- DV-UX003 implemented client-side canonical term selection with visible-result
  selection controls and repository-mediated Add Selected to Draft. Batch
  insertion preserves UUID references, defaults to include/blank label, skips
  duplicates, clears selection after success, and leaves published Views and
  Jobs binding behavior unchanged.
- DV-UX004 clarified the authoring information architecture: draft editing now
  has a compact View/version context, explicit Core Terms Library and Current
  View labels, a visible View → Groups → Entries hierarchy, the full
  Browse → Select → Add → Organize → Preview → Publish workflow, and a
  subordinate alternate manual-entry path. Persistence and runtime behavior
  were unchanged.
- DV-UX005 added draft-only drag-and-drop ordering for entries within groups
  and groups within the View, persisted through the existing repository order
  model. Drag handles, drop indicators, hover feedback, a 35/65 workbench
  balance, and retained keyboard ordering controls were browser-verified.
- DV-ARCH001 completed: Profilaxes is confirmed as the physical MVP host and
  code authority; dedicated repository creation is deferred.

## Current Authority Documents

External source package, supplied in the Views project folder:

- `FIRST-PROMPT-Teachers.Net-Durable-Views-System-Engineering-Commission-and-Ingestion-Charter.md`
- Level 1 Platform Authority Contract and addendum
- Level 2 Platform Engineering Contract and addendum
- Level 3 Implementation Roadmap and addendum
- Level 4 Platform Execution Guide and addendum
- `Teachers.Net-Views-Supplemental-Documentation.md`
- Supporting ChatGPT transcripts

Local implementation contract:

- `docs/core-terms/durable-views-mvp-assessment.md`

## Next Decision

The MVP is certified and in Stabilization. Community remains the next candidate
consumer; implementation remains unauthorized until a separate seam assessment
and approval. DV-013 certified the
Job Center consumer and closed the MVP. DV-012 added parallel
migration and rollback verification. DV-011 bound Job Center
fields to one published View. DV-009 added retire and restore
operations. DV-007 added the minimum protected
administration surface. DV-006 added
draft-only group/entry authoring and the platform current-view consumer seam. The
resolver uses the existing
Profilaxes repository and remote with explicit Durable Views branch/staging
discipline.

## Next Five Planned Tickets

1. Authorize and assess the next consumer only after separate direction.
2. Future authoring refinements require explicit ticket scope.

## Current Risks

- Confusing Meta-Groups with Views.
- Placing View ownership inside Jobs.
- Reconstructing Views in the consumer.
- Storing labels/slugs or copied taxonomy instead of stable UUIDs.
- Mutating published versions in place.
- Expanding into inheritance, personalization, or broad migration before the
  first consumer is proven.
- Extensive unrelated dirty work exists in the shared repository; changes must
  remain narrowly scoped and selectively staged.
- The existing Jobs form-field mapping and option cache are compatibility
  structures, not the Durable Views model.
- DV-002 schema contract is complete; no tables or code were created by that
  ticket.
- DV-003 code is verified in DDEV PHP and the six Views tables plus schema flag
  are present locally; commit `71ce3fb` is pushed.
- DV-ARCH001 proved the DDEV runtime loads the same Profilaxes worktree and
  recommended deferring a dedicated Durable Views repository until extraction
  has an approved package boundary.
- DV-004 lifecycle verification passed in DDEV; temporary test records were
  removed after verification.
- DV-013 full Job Center certification passed in DDEV; certification artifact is
  `docs/core-terms/durable-views-dv013-job-center-certification.md`.

## DV-ARCH002 Completion

DV-ARCH002 is complete as an audit-only ticket. The MVP authority boundary is
preserved, with safe extension seams for bounded Save As/clone and metadata
workflows. Repeated same-inclusion placement, virtual nodes, inheritance,
placement-specific metadata, and consumer-specific projections require a
separate authorized design/schema ticket before implementation. No product
implementation changes were made.

## Stop Conditions

Stop and request direction if:

- the Job Center integration seam requires changing the authority boundary;
- a required capability is not expressible without duplicating taxonomy;
- implementation would require renaming compatibility surfaces;
- the MVP cannot preserve rollback to the current Job Center path;
- authority documents conflict in a way the current hierarchy does not resolve.

## Durable References

- Project Cursor: this document
- Engineering Handoff: `docs/core-terms/durable-views-engineering-handoff.md`
- Roadmap: `docs/core-terms/durable-views-roadmap.md`
- MVP Assessment: `docs/core-terms/durable-views-mvp-assessment.md`
- Administrator Manual: `docs/core-terms/durable-views-user-manual.md`
- UX Reuse Audit: `docs/core-terms/durable-views-uxaud001-reuse-audit.md`
- UX-001 Workbench: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
- UX-002 Composition Canvas: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
- UX-003 Selection & Batch Composition: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php` and `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
- UX-004 Authoring Information Architecture: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
- UX-005 Composition Interaction Polish: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php` and `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
- Core Terms Cursor: `docs/core-terms/project-cursor.md`
- Core Terms Handoff: `docs/core-terms/engineering-handoff.md`

## DV-UXAUD002 Completion

DV-UXAUD002 defines the next-generation Views authoring product model:
read-only hierarchical Core Terms Library, editable Current View presentation
tree, explicit shuttle/selection behavior, representation states, named
lifecycle actions, and a View manager. The specification retains the current
authority and immutable-publication boundaries and defers virtual nodes,
repeated placement, inheritance, templates, and consumer-specific projection
until a dedicated architecture ticket.

Specification: `docs/core-terms/durable-views-dv-uxaud002-authoring-model-specification.md`.

## DV-UX006 Completion

DV-UX006 implemented the first dual-tree workbench shell in Profilaxes. The
Core Terms Library remains read-only and now starts collapsed; the Current View
surface exposes a tree semantics with collapsed Presentation Containers,
representation highlighting, and a bounded toolbar showing deferred advanced
actions. Existing draft persistence, validation, preview, publication, drag
ordering, and Jobs integration were preserved.

Profilaxes commit: `6cd6c48`.

## DV-UX006A Completion

DV-UX006A stabilized the rendered dual-tree workbench without changing
behavior or architecture. The sticky editing context was returned to normal
flow, the 35/65 pane ratio was preserved, and source rows now use compact
desktop columns with intentional stacked controls below 1200px. Screenshots
were captured at 1440px, 1200px, and 1024px.

Profilaxes commit: `210d96f`.

## DV-UX007 Completion

DV-UX007 rebuilt the authoring flow around the approved Library → Current View
dual-tree composition model. The active UI now uses a Compose View workspace,
an explicit shuttle action, parent/descendant selection scope, and a simplified
draft tree while retaining the existing persistence, resolver, lifecycle,
preview, validation, publication, and Jobs boundaries. No schema or repository
redesign was introduced.

Profilaxes branch `agent/durable-views-dv003-persistence`, commit `23c703a`,
was pushed. Completion report: `docs/core-terms/durable-views-dv-ux007-completion-report.md`.

## DV-UX008 Supersession and DV-UX009 Transition

DV-UX008 is superseded. Its historical placeholder must not be used as an
implementation authority. The finalized V1 workflow is governed by the
post-DV-UX007 product direction and the forthcoming DV-UX009 specification.
The next implementation ticket is DV-UX009; do not begin it until the
ChatGPT-supplied specification is available and read.

## DV-SPEC001 Completion

DV-SPEC001 created the canonical Views V1 Product Specification at
`docs/core-terms/durable-views-v1-product-specification.md`. It consolidates
the finalized lifecycle, manager, editor, Library, shuttle, Current View,
removal, visual-state, dialog, deferred-capability, and acceptance rules.
DV-UX009 is now authorized to implement against this specification. No code
implementation was performed by DV-SPEC001.

## DV-ARCH003 Blocker

DV-UX009 is formally open and blocked by the renderer/controller seam. The
diagnostic is recorded at
`docs/core-terms/durable-views-dv-arch003-blocking-diagnostic.md`. The current
form topology cannot safely support aggregate removal or the finalized draft
lifecycle. DV-UX010 must not begin.

## DV-UX009 Continuation Pass 2 Blocker

The authorized renderer refactor surfaced a new blocker: the current
persistence model has no saved-draft snapshot or Delete Draft contract.
Revert to Saved Draft cannot be implemented deterministically, and Delete
Draft has no safe repository seam. See
`docs/core-terms/durable-views-dv-ux009-resume2-blocker-report.md`. DV-UX009
remains open; DV-UX010 is prohibited.

## DV-SPEC002 Completion

DV-SPEC002 finalized `docs/core-terms/durable-views-v1-product-specification.md`
with the approved checkbox, blue-context, shuttle, strike-through removal,
contextual-toolbar, View Manager, draft-lifecycle, and deferred-feature rules.
It is the sole product authority for DV-UX009. No code implementation was
performed.

## DV-DEC001 Completion

DV-DEC001 approved autosaved draft persistence for V1 as a recovery mechanism
for the one active mutable draft. Autosave must be atomic, durable, clearly
distinguished from publication, and must never mutate published versions or
consumer ownership. Revert and Delete Draft semantics and the required
repository/service seam are documented in
`docs/core-terms/durable-views-dv-dec001-autosaved-draft-lifecycle-v1.md`.
This is a product decision only; no schema, repository, UI, database, or
browser changes were made. DV-UX009 remains blocked pending implementation.

## DV-UX009A Implementation Pass

The approved autosaved lifecycle and remaining bounded removal controls were
implemented in Profilaxes. Commits `d5aa0b1`, `f13e2f5`, and `2dfb60d` are pushed on
`agent/durable-views-dv003-persistence`. DDEV PHP lint and WordPress bootstrap
checks pass. Authenticated browser verification is partial: workbench, Saved
state, removal toolbar, Preview, Publish Draft/Delete Draft controls, and zero
console messages were verified; full mutation acceptance and Jobs regression
were completed in Browser Verification Continuation Pass 2. Temporary draft
version 14 was deleted; QA draft version 13 published; Preview and Jobs
employer workflow passed. DV-UX009A and DV-UX009 are complete and may close.
DV-UX010 remains unauthorized.

## DV-UX009C Completion

DV-UX009C corrected the Core Terms Library expander defect. An unguarded
listener lookup for a non-rendered optional selection control previously
stopped workbench event initialization. The optional listener is now guarded.
Authenticated browser verification passed for Grade Level, Location, and
Subject Area with no selection changes or console messages. Human DV-UX009
acceptance may resume. Profilaxes commit: `852a515`.

## DV-UX010 Completion

DV-UX010 rebuilt only the left Core Terms Library using the existing
DV-UX010A then corrected the Library shuttle contract: descendant selection is
canonical-term-only, ancestors are context and muted/disabled, top-level names
offer whole-tree selection, and nested branch disclosure remains independent.
DV-UX010B added the explicit pending-selection visual state so the selected
descendant and ancestor path are visibly blue while the shuttle remains
canonical UUID-only.
DV-UX010B full defect correction is complete: triangle expanders match
Meta-Groups, sibling order uses Core Terms sort_order, depth rendering is
structural, and shuttle persistence inserts missing canonical ancestors.
Meta-Groups/assignment tree pattern: parent buckets, recursive nodes, compact
rows, aligned controls, and generation indentation. Views-specific selection
and shuttle semantics remain isolated. Authenticated `jobman` verification
passed for the canonical editor with no console messages. Profilaxes commit:
`49b2921`. The Current View tree was not changed.
## 2026-08-07 — DV-FIX003

DV-FIX003 is complete. Structural non-leaf ancestors are now presence-driven and are normalized on draft editor load and after entry deletion. Version 17 was browser-verified empty after final descendant removal; the implementation is pushed as Profilaxes commit `e02e7e6`. DV-DIAG002 remains paused pending runtime/session parity and restoration of its right-panel fixture.

GOV-VIEWS001 updated durable governance: Codex may establish and clean up
clearly disposable local DDEV QA Views, including during diagnostics, without
engineer approval for the fixture data itself. Reports must record fixture
identity, version, mutations, and cleanup/preservation. Production,
published/editorial Views, other active fixtures, and Core Terms remain
protected.

DV-UX018 stabilized Current View contextual controls and redirect viewport
behavior. Empty-state controls are hidden, selection controls synchronize,
Remove Selected has no confirmation, Remove All remains protected, and scroll
is restored for Collapse All and removals. Profilaxes commit `3aea480` is
pushed; the disposable version-17 fixture was cleaned to empty after QA.

DV-UX015 completed the bounded cross-panel geometry convergence. Current View
now uses the Library’s reserved disclosure/selection/label grid with 18px
depth increments while retaining distinct removal semantics. Profilaxes commit
`2f8f8aa` is pushed. Browser verification passed on the preserved version-17
fixture; screenshot transport timed out.

DV-UX016 completed canonical recursive Current View rendering and the shared
`.cfm-views-term-row` presentation contract. Kindergarten now renders beneath
Early Childhood, and both panels share structural slots and depth increments.
Profilaxes commit `5f1b9ad` is pushed. Browser verification passed; screenshot
transport timed out.

DV-UX017 restored Shuttle Selected and Shuttle All Terms by binding the visible
controls to the existing canonical batch form and added scroll preservation
across successful redirects. The local version-17 disposable fixture was
restored and preserved with all terms represented. Profilaxes commit `8b29692`
is pushed; both shuttle actions and viewport stability passed browser QA.
