# Teachers.Net Durable Views Roadmap

Status: Active roadmap — Job Center MVP certified; stabilization and next-consumer authorization
Date: 2026-08-04

## Roadmap Objective

Deliver a platform-owned Durable Views service that immediately supports the
Job Center and can later become the canonical presentation layer for all
Teachers.Net products without architectural replacement.

## Stage 0 — Authority and Contract Preparation

Status: Complete

Deliverables:

- ingest and reconcile the authority package;
- inspect Core Terms and Jobs boundaries;
- define MVP included, deferred, and excluded capabilities;
- create the traceability matrix;
- identify principal risks and stop conditions;
- obtain approval before implementation.
- confirm the first Job Center consumer seam.

Exit gate:

- MVP assessment approved;
- first Job Center consumer seam identified and documented;
- no unresolved authority conflict blocks schema design.

Completed artifact: `docs/core-terms/durable-views-dv001-consumer-seam-audit.md`.

## Stage 1 — Foundation and Persistence

Target tickets: 3–5

Build:

- View identity;
- View Version identity and lineage;
- View entry and group records;
- presentation metadata;
- audit records;
- status and publication pointers;
- repository/service boundary;
- migration-safe persistence.

Required invariants:

- canonical Core Term UUID references only;
- published versions immutable;
- View identity stable across versions;
- no Jobs-specific fields in platform records;
- future inheritance/composition not blocked by the schema.

Exit gate:

- persistence tests pass;
- lifecycle state transitions are deterministic;
- published snapshots and rollback targets are recoverable.

## Stage 2 — Resolution and Validation

Target tickets: 2–3

Build:

- deterministic resolution of one View Version;
- Core Terms UUID lookup;
- inclusion/exclusion precedence;
- ordering and grouping rules;
- duplicate handling;
- stale/missing/retired-term handling;
- validation state and warnings;
- resolved presentation-model contract;
- safe failure behavior.

Exit gate:

- the same stored View Version always resolves to the same model for the same
  Core Terms state;
- invalid references never silently become new taxonomy;
- consumers no longer need composition logic.

## Stage 3 — Administration

Target tickets: 2–3

Build the smallest protected admin workflow that permits an administrator to:

- create a View;
- create/edit a draft version;
- browse/select canonical terms;
- order and group entries;
- add presentation metadata;
- preview a draft;
- validate;
- publish;
- clone;
- retire and restore.

Do not redesign the entire Core Terms workbench or create product-specific
admin concepts.

Exit gate:

- an administrator can create the first real Job Center View;
- preview and published resolution match the stored composition;
- capability checks, nonces, and audit behavior are verified.

## Stage 4 — Job Center Pilot

Target tickets: 2–3

Build:

- a Jobs-owned reference to a published View/version;
- one controlled consumer integration;
- parallel operation with current Job Center behavior;
- compatibility and rollback path;
- end-to-end verification using real canonical terms.

The Job Center may own selection policy and UI behavior, but it may not own
View composition or duplicate the Core Terms tree.

Exit gate:

- Job Center consumes a published View through the platform contract;
- current behavior remains recoverable;
- no consumer-side taxonomy assembly exists;
- acceptance evidence is complete.

## Stage 5 — Certification and Operational Handoff

Target tickets: 1–2

Deliver:

- consumer certification record;
- authority compliance review;
- regression results;
- migration and rollback record;
- operational failure playbook;
- updated Project Cursor and Engineering Handoff;
- next adoption plan.

Exit gate:

- MVP is explicitly accepted;
- Job Center is the first certified consumer;
- unresolved risks have owners and retirement or follow-up milestones.

## Stage 6 — Platform Adoption

After MVP acceptance, in priority order:

1. Community
2. Lesson Bank
3. Marketplace
4. Directories
5. Search
6. Notifications and Messaging
7. Analytics and AI consumers

Each adoption follows:

    Current consumer -> View equivalent -> Parallel operation -> Validation
    -> Cutover -> Legacy retirement

No consumer should create a product-owned View system.

## Stage 7 — Advanced Views

Deferred capabilities, to be admitted only through separate approved tickets:

- inherited Views;
- composed Views;
- personalized Views;
- subscriber policy bindings;
- stable public View URLs;
- subscriptions and version migration;
- analytics dimensions;
- AI-assisted View proposals;
- distributed caching and advanced invalidation.

These capabilities extend the MVP contracts; they do not replace them.

## Ticket Register

| ID | Ticket objective | Stage | Status |
| --- | --- | --- | --- |
| DV-000 | Approve MVP assessment and authority matrix | 0 | Complete |
| DV-001 | Confirm repository/runtime and Job Center consumer seam | 0 | Complete |
| DV-002 | Define canonical View schemas | 1 | Complete |
| DV-003 | Implement View persistence | 1 | Complete; verified and pushed |
| DV-ARCH001 | Assess Profilaxes packaging and Git authority | 1 | Complete; extraction deferred |
| DV-004 | Implement lifecycle, publication, and rollback | 1 | Complete; verified and pushed |
| DV-005 | Implement deterministic View resolution and validation | 2 | Complete; verified and pushed as `1d5e477` |
| DV-006 | Implement persistence-backed entry authoring and first consumer integration | 2 | Complete; verified and pushed as `02c6399` |
| DV-007 | Implement minimum administration surface | 3 | Complete; verified and pushed as `74dd68c` |
| DV-008 | Implement preview and clone behavior | 3 | Complete; verified and pushed as `db591f9` |
| DV-009 | Implement retire and restore | 3 | Complete; verified and pushed as `ed79f3f` |
| DV-010 | Implement platform consumer service boundary | 3 | Complete; verified and pushed as `83eebfb` |
| DV-011 | Bind Job Center to one published View | 4 | Complete; verified and pushed as `e6e3a2f` |
| DV-012 | Run parallel migration and rollback verification | 4 | Complete; verified and pushed as `2f31a93` |
| DV-013 | Certify Job Center consumer and close MVP | 5 | Complete; certification passed |
| DV-014 | Refresh handoff and authorize next consumer | 5 | Complete; Community named as candidate, implementation not authorized |
| DV-015 | Assess Community consumer seam | 5 | Complete; source ownership/compatibility prerequisite required |
| DV-016 | Confirm Community source ownership boundary | 5 | Complete; external authorized access required |
| DV-018 | Audit Job Center sprint readiness and authoring gaps | 5 | Complete; browser authoring/live cutover incomplete |
| DV-019 | Build protected draft composition workspace | 5 | Complete; committed and pushed; browser certification pending |
| DV-020 | Add groups, preview, and validation feedback | 5 | Complete; committed and pushed; browser certification pending |
| DV-021 | Add administrator Jobs binding controls | 5 | Complete; committed and pushed; browser certification pending |
| DV-022 | Wire live Jobs adapter cutover with fallback | 4 | Complete; committed and pushed; browser certification pending |
| DV-023 | Certify browser end-to-end Job Center workflow | 5 | Complete; bound Durable View and unbound legacy fallback verified in authenticated employer form; QA membership cleaned up |
| DV-UXAUD001 | Audit Core Terms workbench reuse for Views Authoring UX | 5 | Complete; read-only split-pane target and safe reuse boundaries documented |
| DV-UX001 | Build read-only canonical discovery seam and Views workbench shell | 5 | Complete; browser-verified with existing draft composition and JobLister regression check |
| DV-UX002 | Build rich draft composition canvas | 5 | Complete; visual groups, entry cards, empty guidance, inline controls, and explicit Up/Down ordering browser-verified |
| DV-UX003 | Add selection and batch composition | 5 | Complete; client-side visible-term selection, Add Selected repository workflow, duplicate feedback, and browser verification |
| DV-UX004 | Clarify authoring information architecture | 3 | Complete; compact editing context, explicit source/destination panes, hierarchy/workflow orientation, and subordinate manual path browser-verified |
| DV-UX005 | Polish composition interactions | 5 | Complete; draft-only drag/drop ordering, keyboard fallback, drop indicators, interaction feedback, and 35/65 workbench balance browser-verified |
| DV-UXAUD002 | Define next-generation Views authoring model | 5 | Complete; product-definition audit; no implementation changes; dual-tree and lifecycle specification documented |
| DV-UX006 | Implement dual-tree workbench Phase 1 | 5 | Complete; shell, tree semantics, collapsed state, representation highlighting, and deferred controls browser-verified; screenshot capture pending |
| DV-UX006A | Stabilize dual-tree visual layout | 5 | Complete; diagnosed and corrected flow, row-grid, responsive wrapping, and control-placement defects; authenticated screenshots captured at 1440/1200/1024 |
| DV-ARCH002 | Audit future expansion preservation | 5 | Complete; audit-only; no implementation changes; repeated placement and future node/projection constraints documented |
| DV-UX007 | Rebuild Views authoring flow around dual-tree composition | 8 | Complete; Compose View workflow, Library-to-View shuttle, selection scope, and simplified authoring surface implemented; browser-verified |
| DV-UX007A | Audit Views V1 interaction states | 8 | Complete; audit-only; findings recorded |
| DV-UX008 | Current View tree and container interaction | 8 | Superseded; historical placeholder replaced by finalized V1 workflow |
| DV-UX009 | Implement finalized Views V1 authoring workflow | 8 | Next; specification supplied by ChatGPT required before implementation |
| DV-SPEC001 | Capture canonical Views V1 Product Specification | 8 | Complete; documentation authority for DV-UX009 |
| DV-SPEC002 | Finalize Views V1 Product Specification | 8 | Complete; finalized interaction rules are now the sole DV-UX009 authority |
| DV-UX009 | Implement finalized Views V1 authoring workflow | 8 | In progress; legacy surface removed and Library foundation implemented; contextual removal/lifecycle states remain |
| DV-ARCH003 | Diagnose DV-UX009 renderer blocking architecture | 8 | Complete; renderer/controller seam blocks safe aggregate removal and draft lifecycle completion; DV-UX009 remains open |
| DV-UX009-CONT2 | Resume authorized renderer/controller refactor | 8 | Blocked; new persistence/lifecycle snapshot and draft-deletion contract is required |
| DV-DEC001 | Approve autosaved draft lifecycle for V1 | 8 | Complete; product decision recorded; implementation requires a separate ticket |
| DV-UX009A | Implement autosaved draft lifecycle | 8 | Complete; browser Pass 2 verified autosave, delete, publish, preview, removal controls, and Jobs regression |

DV-UX009 is now fully satisfied and may be closed. DV-UX010 remains
unauthorized.

## DV-DEC001 Decision

Autosaved draft persistence is approved for V1 as a recovery mechanism for the
single active draft. It never publishes, mutates published versions, or moves
composition into Jobs. The decision and required service contract are recorded
in `docs/core-terms/durable-views-dv-dec001-autosaved-draft-lifecycle-v1.md`.
DV-UX009 remains blocked pending separately authorized implementation.

DV-002 artifact: `docs/core-terms/durable-views-dv002-schema-contract.md`.
DV-003 artifact: `docs/core-terms/durable-views-dv003-persistence-strategy.md`.
DV-ARCH002 artifact: `docs/core-terms/durable-views-dv-arch002-future-expansion-preservation-audit.md`.
DV-UXAUD002 artifact: `docs/core-terms/durable-views-dv-uxaud002-authoring-model-specification.md`.
DV-UX006 artifact: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php` at Profilaxes commit `6cd6c48`.
DV-UX006A artifact: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php` at Profilaxes commit `210d96f`.
DV-UX007 artifact: `docs/core-terms/durable-views-dv-ux007-completion-report.md` and Profilaxes commit `23c703a`.
DV-UX007A artifact: `docs/core-terms/durable-views-dv-ux007a-interaction-state-audit.md`.
DV-SPEC001 artifact: `docs/core-terms/durable-views-v1-product-specification.md`.
DV-SPEC002 artifact: `docs/core-terms/durable-views-dv-spec002-completion-report.md`.

## DV-UX008 Supersession

DV-UX008 is superseded and must not be implemented from its historical
roadmap placeholder. The finalized V1 workflow that follows DV-ARCH002,
DV-UXAUD002, DV-UX006, DV-UX006A, DV-UX007, and DV-UX007A is authoritative.
The next implementation ticket is DV-UX009, subject to its ChatGPT-supplied
specification.

## Governance Rule

No ticket may advance a later stage while the current stage exit gate is
unproven. No diagnostic, infrastructure improvement, or product request
authorizes expanding the MVP boundary without an explicit decision recorded in
the Project Cursor and Engineering Handoff.

| DV-UX009C | Restore Core Terms Library tree expansion | 8 | Complete; root cause corrected and all three branches browser-verified |
| DV-UX010 | Adopt Meta-Groups tree structure in Core Terms Library | 8 | Complete; compact recursive Library tree browser-verified; Current View tree unchanged |
| DV-UX010A | Correct Library Tree Structure and Ancestor Shuttle Contract | 8 | Complete; nested disclosure, whole-tree selection prompt, and muted ancestor context browser-verified |
| DV-UX010B | Enforce Canonical Tree Rendering and Ancestor Shuttle | 8 | Complete; Meta-Groups controls, canonical ordering, depth rendering, and persisted ancestor paths browser-verified |
