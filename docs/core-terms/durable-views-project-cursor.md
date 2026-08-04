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

1. Obtain authorized read-only source access/ownership decision for Community.
2. Reassess a Community-specific Durable View only after that prerequisite.
3. Implement the minimum protected View administration workflow.
4. Implement preview, clone, retire, and restore behavior.
5. Bind the first Job Center consumer through the platform contract.

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
- Core Terms Cursor: `docs/core-terms/project-cursor.md`
- Core Terms Handoff: `docs/core-terms/engineering-handoff.md`
