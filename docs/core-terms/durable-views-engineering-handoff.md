# Durable Views Engineering Handoff

## 1. Current Phase

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

Maintain the Durable Views MVP release candidate and evidence set before any
separate consumer authorization.
The current administrator baseline is documented in
`docs/core-terms/durable-views-user-manual.md`. It deliberately distinguishes
browser capabilities from service-level or planned UX capabilities.
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
