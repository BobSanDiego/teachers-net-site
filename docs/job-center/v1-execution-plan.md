# Job Center V1 Execution Plan

## Purpose

Define the V1 release-candidate execution path from the current populated local
marketplace to a controlled real-job pilot and eventual approved bulk loading.
Repository documentation remains authoritative for implementation detail.

## Sprint Outcome

V1 must deliver a dependable Job Finder, employer posting and management path,
truthful application routing, reliable job freshness, and production-capable
real-job ingestion. Visual polish cannot proceed independently of lifecycle,
location, application, and ingestion requirements that affect public trust.

## Critical Path

1. Real Job Ingestion and V1 Lifecycle Readiness Audit, beginning with
   **JREAL001 - Real Job Current-State Inventory**.
2. Approve the canonical real-job contract and dependency map.
3. Implement small, separately approved gaps for provenance, source identity,
   validation, deduplication, reconciliation, application integrity, expiration,
   coordinates, independent search-origin resolution, and employer claims.
4. Complete employer posting and management acceptance.
5. Confirm the final Job Finder interaction contract against supported behavior.
6. Run a limited real-job pilot.
7. Correct pilot findings, approve controlled bulk import, then complete V1
   release-candidate acceptance.

## V1 Blockers

- readiness audit and canonical real-job contract
- ingestion validation, source identity, provenance, and deterministic deduplication
- truthful application routing and application-path validation
- expiration, source reconciliation, exception reporting, and repair paths
- job-coordinate coverage and independent typed ZIP/city-state origin resolution
- employer create/manage lifecycle acceptance and verified claim/conversion path
- limited pilot import, pilot corrections, controlled bulk import, and Job Finder
  acceptance
- release-candidate review, launch planning, monitoring, and rollback readiness

The frozen minimum visual-authority inventory is recorded in
`docs/job-center/v1-authority-program.md` (DOC021). Implementation Readiness
must use that inventory and its five remaining render groups as the visual
authority gate; it must not expand the UX Atlas placeholder set.

Employer execution uses the refreshed Employer Operations model: My Jobs is the
hybrid authenticated operating workspace inside the Teachers.Net shell; prior
Dashboard concepts are absorbed into it. One active left-navigation selection
is allowed at a time (All My Jobs, My Schools / Job Sites, Add School / Job
Site, or Manage Schools / Job Sites). Memberships and granted capabilities scope
operations, while Claim and Add My School / Add Organization remain contextual
acquisition paths rather than routine Operations navigation.

## V1 Launch Polish

- final Basic Search presentation
- compact Location + Distance editor from the integrated Basic Search experience,
  subject to final design approval; it is not currently implemented
- adaptive right rail and responsive QA
- truthful imported-job source, correction, removal, and employer-claim treatment

## Settled Ingestion Principles

- Imported and employer-posted jobs use one public job entity and lifecycle.
- Provenance, source identity, ownership/claim state, import batch, and
  verification state are internal distinctions; they do not create a separate
  public scraped-job product.
- A record cannot auto-publish without an employer association, intelligible
  title, usable application path, resolvable location or explicit remote status,
  source information, lifecycle/expiration handling, and no unresolved duplicate.
- Ingestion must provide deterministic idempotent reruns, dry-run reporting,
  create/update/unchanged/reject outcomes, batch traceability, and exception
  reporting. AI-assisted extraction or mapping is not the audit trail and must
  not bypass validation.

## Lifecycle And Application Requirements

- Store canonical source identity, source URL, external source job identifier
  where available, first/last-seen timestamps, last-verified timestamp, and
  reconciliation state.
- Define refresh cadence, explicit expiry handling, fallback listing age,
  temporary-source-failure grace, change detection, disappearance handling,
  closure versus archival, manual override, and application-link failure paths.
- Application actions must state and route accurately: employer site, external
  ATS, or stored instructions. Teachers.Net does not imply it receives or relays
  applications unless that behavior actually exists.

## Location And Distance Search Requirements

- Preserve JDIST002-JDIST006: local database radius queries, coordinate index,
  Advanced Search/Browse controls, cross-state option, and request-scoped browser
  location with no persisted teacher coordinates.
- The currently implemented distance UI is Advanced-only. Any compact Basic
  editor is presentation work and must preserve the shared Search/Browse and
  Distance Search architecture.
- Non-remote imported and employer-created jobs need stored coordinates.
  Geocoding runs after a job location changes, never during public search.
  Missing coordinates need retry, repair, exception reporting, and manual
  correction paths.
- Supported ZIP and City, State origins must resolve independently of whether an
  active job exists in that exact place. Google Places Autocomplete is not a V1
  blocker.
- Preferred Search Location is explicitly saved; Current Search Origin is
  temporary and must not silently overwrite it.

## Employer Claim Direction

An imported employer or job may begin unclaimed. A representative initiates a
claim/correction path, authenticates, and is verified through employer-domain
email or manual review before gaining employer association and eligible job
management. Do not auto-create recruiter identities or transfer control without
verification.

## Pilot Scope And Acceptance

Pilot approximately 3-5 employers and 50-100 jobs across multiple cities/ZIPs,
with remote, hybrid, and on-site roles; varied application destinations and
expiration behavior; changed/removed source jobs; a duplicate; and a simulated
employer claim.

Accept the pilot only when reruns are idempotent, changes update, disappeared
records close or expire correctly, application paths work, eligible jobs join
Distance Search, public presentation remains native and truthful, failures are
reported, and verified employers can manage eligible jobs.

## V1.1 Candidates

- provider-backed autocomplete
- richer typeahead
- cached selected locations
- expanded employer-conversion automation
- deeper personalization

## Deferred Work

- maps, commute-time routing, and travel-time search
- richer teacher/recruiter context switching
- advanced personalization and unrelated workflow expansion
- Google Places Autocomplete as a required V1 dependency

## Next Release-Candidate Audit After Current Convergence Gate

After Employer Operations desktop convergence is explicitly accepted, and the
authorized downstream work is complete, **JREAL001 - Real Job Current-State
Inventory** is the first focused inspection within the broader **Real Job
Ingestion and V1 Lifecycle Readiness Audit**. It must inspect the current Jobs
model, schema, repositories/services, seed-importer reuse, source identity,
deduplication, provenance, ownership, applications, expiration/reconciliation,
coordinates, typed-origin resolution, employer flows, claim capability, and
pilot requirements. It stops at findings, contract recommendation, dependency
map, risk assessment, and the smallest implementation sequence.

## Stop Boundary

This execution plan authorizes planning only. Do not import real jobs, make
schema/service/UI changes, add provider integrations, or start a pilot/bulk load
without a separate approved ticket.
