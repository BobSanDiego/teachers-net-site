# Durable Views Engineering Handoff

## 1. Current Phase

DV-UX013 is complete. Top-level Library bulk descendant controllers are
browser-verified at
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`.

DV-UX012 is complete. The left Library represented-state and top-level branch
selection semantics are browser-verified; the current review URL is
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`.

DV-UX011 is complete and is the current Views V1 authoring baseline. The
canonical Library and Current View use dual compact recursive checkbox trees;
manager links and draft deletion access are present; legacy per-entry editing
controls are omitted for canonical entries. Browser evidence was captured at
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`.

Stabilization — Job Center MVP certified; next consumer pending authorization.

## 2. Current Ticket

DV-003 persistence foundation verified in DDEV PHP and pushed as commit
`71ce3fb` on `agent/durable-views-dv003-persistence`.
DV-004 lifecycle controls verified and pushed as commit `f3dfedb` on the same
branch.
DV-005 validation and deterministic resolution verified and pushed as commit
`1d5e477` on that branch.
DV-006 draft group/entry authoring and the current-view consumer seam were
verified and pushed as commit `02c6399` on that branch.
DV-007 protected administration surface was verified and pushed as commit
`74dd68c` on that branch.
DV-008 preview and complete clone behavior were verified and pushed as commit
`db591f9` on that branch.
DV-009 retire and restore controls were verified and pushed as commit
`ed79f3f` on that branch.
DV-010 consumer service boundary was verified and pushed as commit
`83eebfb` on that branch.
DV-011 Jobs binding to a published View was verified and pushed as commit
`e6e3a2f` on the `tnet-jobs` `main` branch.
DV-012 parallel migration and rollback adapter was verified and pushed as commit
`2f31a93` on the `tnet-jobs` `main` branch.
DV-013 Job Center consumer certification passed; the MVP certification artifact
is `docs/core-terms/durable-views-dv013-job-center-certification.md`.
DV-014 refreshed this handoff and recorded the MVP closeout in
`docs/core-terms/durable-views-mvp-closeout.md`. Community is the next candidate
consumer, pending explicit authorization and a separate seam assessment.
DV-015 completed that read-only seam assessment. Community implementation is
blocked until source ownership/access and a legacy compatibility boundary are
established; see `docs/core-terms/durable-views-dv015-community-consumer-seam-assessment.md`.
DV-016 confirmed the same external prerequisite from the available local
evidence; see `docs/core-terms/durable-views-dv016-community-source-boundary.md`.
DV-018 completed the Job Center sprint readiness audit. The platform/service
MVP remains certified but browser authoring and live Jobs cutover are not yet
complete; see `docs/core-terms/durable-views-dv018-sprint-readiness-gap-audit.md`.
DV-019 implemented the protected draft composition workspace in the Profilaxes
admin surface. It is draft-only and supports canonical framework/term
selection, include/exclude, label/order, descendant intent, entry listing, and
removal. PHP and DDEV runtime checks passed; authenticated browser evidence is
still pending.
DV-020 added draft group creation, group assignment during entry authoring,
resolved draft preview, and visible validation state/messages. PHP and DDEV
runtime checks passed; authenticated browser evidence remains pending.

## 3. Last Completed Milestone

DV-001 confirmed that the first consumer seam is the Job Categories admin and
form-field option path. Jobs currently maps Core Terms sources and synchronizes
child terms into Jobs-owned option tables; this is a compatibility path, not a
Durable Views implementation.

## 4. What Is Objectively Known

- Core Terms owns the canonical hierarchy and stable term UUIDs.
- Core Terms exposes public lookup and hierarchy APIs for consumers.
- Core Terms has a functioning administrative taxonomy workbench.
- Core Terms Meta-Groups support audience/user resolution and are not Views.
- Jobs already references Core Terms UUIDs for Jobs-owned classification.
- No Durable Views persistence, resolver, lifecycle, admin workflow, consumer
  contract, or Job Center View binding is currently documented as implemented.
- Existing Jobs per-job term assignments remain outside the Views boundary.

## 5. Current Deliverables

- `docs/core-terms/durable-views-mvp-assessment.md`
- `docs/core-terms/durable-views-project-cursor.md`
- `docs/core-terms/durable-views-engineering-handoff.md`
- `docs/core-terms/durable-views-roadmap.md`

## 6. Proposed MVP

The MVP must provide:

- stable View and View Version identity;
- UUID-only Core Terms references;
- inclusion, exclusion, ordering, grouping, and presentation metadata;
- deterministic resolution and validation;
- draft/preview/publish/clone/retire/restore lifecycle;
- immutable published versions;
- a platform consumer contract;
- one controlled Job Center binding with rollback.

Inheritance, composition, personalization, subscriptions, analytics, AI, and
multi-product migration are deferred but must remain architecturally possible.

## 7. Completed DV-001 Findings

- Core Terms public read boundary: `CFM::get_framework()` and
  `CFM::get_terms()`.
- Job Center configuration boundary:
  `TNet_Jobs_Job_Categories_Admin` -> form-field services -> configured option
  rows.
- Jobs-owned assignment boundary: `TNet_Jobs_Term_Service` and
  `tnet_jobs_terms`.
- Detailed evidence: `docs/core-terms/durable-views-dv001-consumer-seam-audit.md`.
- Schema contract: `docs/core-terms/durable-views-dv002-schema-contract.md`.
- Persistence strategy: `docs/core-terms/durable-views-dv003-persistence-strategy.md`.
- Persistence implementation: `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-schema.php`,
  `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`,
  `wordpress/wp-content/plugins/profilaxes/includes/class-cfm.php`, and
  `wordpress/wp-content/plugins/profilaxes/profilaxes.php`.
- DV-ARCH001 assessment: `docs/core-terms/durable-views-dv-arch001-packaging-authority-assessment.md`.

## 8. Confirmed Decisions for Next Phase

The MVP boundary, separation between Views and Meta-Groups, platform ownership
boundary, first Job Center consumer seam, and ticket sequence are now the
working basis for DV-002. Any change must be recorded in this handoff and the
Project Cursor before implementation proceeds.

## 9. Next Execution Ticket

DV-021 added protected Jobs administrator controls for binding one form field
to a currently published Durable View version and for removing that binding.
The Jobs service remains the authority for matching/published checks and
platform resolution. PHP/DDEV checks passed; authenticated browser evidence
remains pending.
DV-023 browser certification is complete. Authenticated admin evidence passed
draft authoring, group creation, valid validation, preview, publish, and Jobs
binding. The employer form resolved the bound canonical Grade Level option
through an active local QA membership, then resolved the legacy Grade Level
children after the binding was removed. The original binding was restored;
the temporary membership was deactivated and its employer archived through the
established Jobs services.
DV-022 wired the live `configured_options_for_field()` path to prefer the
published Durable Views adapter for valid bindings, while preserving the
legacy Jobs-owned path as fallback. PHP/DDEV checks passed and an unbound
runtime smoke test returned 22 legacy options; authenticated browser evidence
remains pending.

Next authorized UX candidate: DV-UX002 — build the split-pane draft authoring
surface around the discovery seam. Do not add drag/drop, bulk selection,
nested groups, or broad shared asset extraction in that ticket.
The current administrator baseline is documented in
`docs/core-terms/durable-views-user-manual.md`. It deliberately distinguishes
browser capabilities from service-level or planned UX capabilities.
DV-UXAUD001 is the current UX handoff: it recommends an adapted split-pane
workbench with read-only canonical term discovery and View-owned composition.
It is audit-only; no UI implementation or shared asset extraction is approved
by that audit.
DV-UX001 now implements that bounded shell in the Profilaxes Views admin. The
canonical pane reads `CFM::get_terms()` data only, supports framework choice,
client-side search, hierarchy expand/collapse, context, and Add to Draft; the
existing draft composition forms remain the persistence path. No Core Terms
mutation handler, shared editor state, Jobs behavior, or published View binding
was changed. The local QA draft is version 13; JobLister remains published at
View 10 / Version 12 with binding 10:12.
DV-UX002 now presents draft groups as visual containers and entries as
composition cards with display-label, inclusion, descendant, save, remove,
and explicit Up/Down controls. Empty drafts guide administrators through
Browse → Add → Organize → Preview → Publish. The repository adds only a
draft-scoped move operation; drag/drop, bulk selection, nested groups, and
published semantics remain unchanged.
DV-UX003 adds browser-local multi-selection in the read-only canonical browser
and an Add Selected to Draft action. The batch path delegates each selection to
the existing repository, preserves canonical UUID references, defaults to
include/blank presentation labels, reports duplicate skips, and clears the
selection after a successful add. It does not mutate Core Terms or published
Views and does not change Jobs behavior.
DV-UX004 clarified the browser information architecture without adding
platform capability. Draft editing presents a compact View/version/status
context with Back to Views; the panes are named Core Terms Library (Read-only)
and Current View (Editable draft); the hierarchy is explicitly View → Groups →
Entries; and the workflow reads Browse → Select → Add → Organize → Preview →
Publish. The lower manual entry form remains available only as an alternate
compatibility path. Repository, validation, preview, publication, and Jobs
behavior were not changed.
DV-UX005 added draft-only drag-and-drop ordering for entries within their
groups and groups within the View using new repository reorder operations.
Drag handles, insertion/drop indicators, hover feedback, the 35/65 library-to-
composition balance, and retained keyboard ordering controls are present. The
visible Up/Down controls were removed; repository ordering, validation,
preview, publication, and Jobs behavior remain unchanged.
Use the existing Profilaxes repository and remote; do not create a separate
Durable Views repository during the MVP.

## 10. Verification Standard

Every implementation ticket must verify:

- authority compliance;
- Core Terms ownership preserved;
- no duplicated taxonomy;
- no consumer-side View assembly;
- published-version immutability;
- deterministic resolver behavior;
- rollback safety;
- required unit/integration evidence;
- current repository changes isolated from unrelated dirty work.

## 11. Open Questions

- Should the initial View service be implemented inside the Core Terms plugin
  or as a separately loaded platform component within the same platform
  boundary?
- What existing Jobs configuration storage can hold a stable View/version
  reference without coupling Jobs to View internals?
- Which Core Terms taxonomy snapshot/version semantics are required for the
  first published View?
- What minimum administrator capability is required for the first View to be
  created without broad Core Terms admin redesign?

## DV-UXAUD002 Completion

The product-definition audit is complete. The preferred future authoring model
is a dual-tree source/destination workbench: a read-only, searchable,
hierarchical Core Terms Library and an editable Current View presentation tree.
The product specification defines branch-aware selection, explicit shuttle
operations, representation states, presentation-container vocabulary, named
Save/Preview/Validate/Publish/Save As/Clone/Revert/Archive concepts, and the
View manager.

No implementation is authorized by this audit. The recommended first
implementation is DV-UX006, followed by selection/shuttle, Current View tree,
and lifecycle manager tickets. See
`docs/core-terms/durable-views-dv-uxaud002-authoring-model-specification.md`.

## DV-UX006 Completion

The first dual-tree shell is implemented in
`wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.
The read-only canonical Library preserves hierarchy and representation state;
the Current View now has tree semantics, collapsed Presentation Containers,
container toggles, and visibly deferred advanced toolbar actions. No
repository, schema, resolver, lifecycle, or Jobs behavior changed.

Profilaxes branch `agent/durable-views-dv003-persistence`, commit `6cd6c48`,
was pushed. Authenticated canonical browser inspection passed for the shell,
collapsed state, represented-term count, and deferred controls. Screenshot
capture remained unavailable after bounded attempts; accessibility snapshot and
DOM assertions are recorded in the ticket report.

## DV-ARCH002 Completion

DV-ARCH002 — Future Expansion Preservation Audit is complete and audit-only.
The reviewed schema, repository, resolver, lifecycle, and authoring assumptions
preserve the MVP authority model. The principal future constraint is the
unique `(version_id, term_uuid, inclusion)` entry scope, which prevents
repeated same-inclusion placement. The flat resolved entries shape also does
not yet represent virtual nodes, placement identity, inheritance overlays, or
consumer-specific projections.

These are preservation findings, not implementation authorization. No schema,
repository, resolver, UI, Core Terms, or Jobs changes were made. The next
ticket must be separately authorized after review of
`docs/core-terms/durable-views-dv-arch002-future-expansion-preservation-audit.md`.

## DV-UX006A Completion

DV-UX006A corrected the visual owners diagnosed at the canonical URL: the
editing context no longer uses sticky positioning, desktop source rows have
stable label/status/action columns, and 1200px/1024px layouts intentionally
stack representation and Add controls without horizontal overflow. The
canonical browser passed at 1440px, 1200px, and 1024px; screenshots were saved
for all three widths and the console had no errors.

Profilaxes branch `agent/durable-views-dv003-persistence`, commit `210d96f`,
was pushed. No Views behavior or architecture changed.

## DV-UX008 Supersession / DV-UX009 Next Ticket

DV-UX008 is superseded and is not an implementation target. Do not reconcile
or partially implement its historical Current View tree placeholder. The
next implementation ticket is DV-UX009 — Views V1 Authoring Workflow
Implementation. Its ChatGPT-supplied specification is the implementation
authority and must be ingested before coding begins.

## DV-SPEC001 Completion

The canonical Views V1 Product Specification is
`docs/core-terms/durable-views-v1-product-specification.md`. It is now the
implementation authority for DV-UX009. DV-SPEC001 was documentation-only;
schema, repository, resolver, UI, and consumer behavior were not changed.

## DV-ARCH003 Blocker

DV-UX009 remains open and blocked. The renderer diagnostic found interleaved
entry/group forms, no aggregate removal contract, and no bounded draft
lifecycle action seam. See
`docs/core-terms/durable-views-dv-arch003-blocking-diagnostic.md`. Do not
advance to DV-UX010.

## DV-UX009 Resume 2 Blocker

The renderer continuation revealed a new persistence/lifecycle blocker outside
DV-ARCH003: no saved-draft snapshot exists for Revert to Saved Draft, and no
Delete Draft repository/controller contract exists. DV-UX009 remains open and
blocked pending an explicitly authorized lifecycle persistence decision.

## DV-SPEC002 Completion

The V1 specification was aligned to the finalized ChatGPT interaction model.
The finalized document remains the sole authority for DV-UX009. No application
behavior or platform boundary changed.

## DV-DEC001 — Autosaved Draft Lifecycle

DV-DEC001 is accepted as a V1 product decision. The active draft may be
durably autosaved for recovery, while published versions remain immutable and
consumer-visible behavior remains unchanged. The decision requires a separate
implementation ticket for atomic snapshot/revision persistence, optimistic
concurrency, Revert, Delete Draft, and truthful failure handling. See
`docs/core-terms/durable-views-dv-dec001-autosaved-draft-lifecycle-v1.md`.
DV-UX009 remains blocked; DV-UX010 is not authorized.

## DV-UX009A Implementation Status

The approved autosaved draft lifecycle, protected Delete Draft, and Current
View removal toolbar are implemented and pushed in Profilaxes commits
`d5aa0b1`, `f13e2f5`, and `2dfb60d` on
`agent/durable-views-dv003-persistence`. DDEV PHP lint and WordPress bootstrap
checks pass. Authenticated browser verification partially passes for the
workbench, Saved state, removal toolbar, Preview, Publish Draft/Delete Draft
controls, and zero console messages. Full mutation acceptance and Jobs
regression passed for the authenticated employer workflow load. DV-UX009A is
complete and DV-UX009 is fully satisfied and may close. DV-UX010 remains
unauthorized.

## DV-UX009C Completion

The Core Terms Library expansion defect is fixed in Profilaxes commit
`852a515`, pushed on `agent/durable-views-dv003-persistence`. The cause was an
unguarded listener lookup for a non-rendered optional selection control.
Authenticated `jobman` browser verification passed for all three top-level
branches with no selection changes and no console messages. Human DV-UX009
acceptance testing may resume. DV-UX010 remains unauthorized.

## DV-UX010A Completion

The Library tree and ancestor shuttle contract are complete and browser-verified
on `jobman`. Profilaxes commit `406a670` is pushed. The next work must preserve
the canonical UUID-only shuttle payload and must not convert ancestor context
into separate taxonomy selections.

## DV-UX010B Completion

The full defect correction is now browser-verified: Meta-Groups triangle
controls, canonical sort order, depth-only indentation, and persistence of the
complete ancestor path. Profilaxes commit `7ea15fb` is pushed.

## DV-UX010 Completion

The left Core Terms Library now uses an isolated recursive structure adapted
from the existing Meta-Groups/assignment tree. Compact rows, aligned
expander/checkbox/label controls, and nested child wrappers are browser
verified as `jobman` at the canonical Views editor. Profilaxes commit
`49b2921` is pushed. Current View structure and all platform/Jobs boundaries
were preserved.
