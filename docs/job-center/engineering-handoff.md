# Job Center Engineering Handoff

Read this after the Engineering Director Playbook, Job Center Project Cursor,
and Job Center V1 Execution Plan. It is compact operational continuity, not a
roadmap or implementation manual.

## Current Working State

- Project state: Active Development.
- Current phase: Teachers.Net Jobs V1 release-candidate execution sprint.
- Current milestone: move from the synthetic seed marketplace to a controlled,
  truthful, production-capable real-job pilot path.
- Current focus: plan and audit the complete real-job lifecycle before more
  presentation polish or any bulk import.
- Immediate task: **Real Job Ingestion and V1 Lifecycle Readiness Audit**.
- Next executable ticket: **JREAL001 - Real Job Current-State Inventory**.
- Root documentation remote state: `a228186` (`ROADMAP002 establish Job Center
  V1 execution continuity`).
- Jobs plugin remote state: `9321508` (`ROADMAP002 align Jobs V1 execution
  roadmap`). `9e9f0bf` (JDIST006) remains the latest completed application-code
  ticket.

## Recently Completed

- `a228186` / `9321508` ROADMAP002 execution continuity
  - Created the V1 Execution Plan, made pilot-before-bulk-load sequencing
    explicit, expanded the continuity protocol, and synchronized the Job Center
    Cursor, Handoff, and Execution Plan to Google Drive.

- JDIST002-JDIST006 established the Distance Search architecture and local
  foundation: coordinate metadata/indexing, radius query, typed ZIP/city-state
  origin controls, cross-state option, and opt-in browser current location.
  Coordinates are request-scoped for browser origin searches and are not stored
  in profiles, analytics, or URLs.
- Job Finder work established one `/jobs/` route with Search/Browse interface
  states, shared results/pagination/chips, public shell, detail layout, and
  responsive implementation passes.
- The seed importer is idempotent. Local fixture data contains 250 seed jobs,
  with 205 active published jobs; Grade and Subject mapping remains 250/250.

## Newly Approved Execution Direction

- Employer-posted and Teachers.Net-imported jobs remain one public job entity:
  same lifecycle, search, result/detail presentation, application behavior, and
  expiration behavior. Provenance is internal metadata, not a second-class
  public result type.
- Imported jobs must present truthfully: application actions route to the actual
  destination; source/correction/removal/claim paths may be discreet but must
  not imply employer endorsement or internal application receipt.
- A controlled real-job pilot must precede bulk loading. The audit defines the
  contract and dependency order; it does not implement them.
- The full V1 critical path lives in `docs/job-center/v1-execution-plan.md`.

## Immediately On Tap

1. **JREAL001 - Real Job Current-State Inventory** within the Real Job
   Ingestion and V1 Lifecycle Readiness Audit.
   - Inspect current fields/schema, services/repositories, seed-importer reuse,
     provenance/source identity, deduplication, ownership, application methods,
     expiration/reconciliation, coordinates, typed-origin resolution, employer
     flows, claim capability, and pilot requirements.
   - Stop at findings, canonical contract recommendation, dependency map, risks,
     and smallest implementation sequence.

2. Approve the canonical real-job contract and dependency map.

3. Open small, separate implementation tickets in the approved sequence, then
   conduct a limited real-job pilot before bulk import.

## Active Decisions

- `/jobs/` is the shared public Job Finder. Search and Browse are interface
  states, not separate products or routes.
- Distance controls stay in Advanced Search/Browse. Provider calls never run
  during public search. Google Places Autocomplete is useful but is not a V1
  release blocker.
- V1 launch polish may expose a compact Location + Distance editor from the
  integrated Basic Search experience. Its exact interaction awaits final design
  approval; the existing Advanced Search/Browse distance architecture remains
  the current implementation.
- Preferred Search Location is an explicit saved preference. Current Search
  Origin is temporary and must not overwrite it automatically.
- Employer claims require authority verification. Recruiter identities are never
  auto-created from imported data.

## Open Risks

- No audited, production-capable real-job contract yet exists for provenance,
  validation, source reconciliation, expiration, exception reporting, and repair.
- Current origin resolution must become independent of the live job inventory.
- Employer-coordinate automation and operational repair paths are absent.
- Core Terms remote parity is pending in its separate repository.
- V1 release-candidate, deployment, monitoring, rollback, and launch operations
  remain pending.

## Required Google Drive Context

1. Engineering Director Playbook
2. Job Center Project Cursor
3. Job Center Engineering Handoff
4. Job Center V1 Execution Plan

## Stop-After Boundary

Stop after the readiness audit and wait for approval. Do not implement ingestion,
provider integration, schema changes, claim automation, UI changes, pilot import,
or bulk loading in the audit ticket.

## Source Documents

- `docs/job-center/v1-execution-plan.md`
- `docs/job-center/jobs-roadmap.md`
- `docs/job-center/product-definition-v1.md`
- `docs/decision-log.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/development-constitution.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/bulk-import-spec.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/employer-portal-architecture-v0.1.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/job-location-strategy.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/distance-search-architecture.md`
