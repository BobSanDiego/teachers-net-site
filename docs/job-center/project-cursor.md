# Job Center Project Cursor

## Project State

Active Development

## Current Phase

Teachers.Net Jobs V1 release-candidate execution sprint.

## Current Milestone

Establish a production-capable real-job ingestion and lifecycle path before a
controlled pilot and bulk loading.

## Current Focus

The public Job Finder, seed marketplace, and Distance Search foundation are
complete enough to inform the next phase. The priority is now trustworthy
real-job ingestion, employer ownership, application integrity, freshness, and
pilot readiness.

## Current Reference Page/Flow

One public `/jobs/` Job Finder for employer-posted and Teachers.Net-curated
jobs. Search and Browse share the same results, lifecycle, presentation, and
application behavior.

## Current Primitive/Task

Real Job Ingestion and V1 Lifecycle Readiness Audit.

## Next Decision

Approve the audit's canonical real-job contract and dependency map before
opening any ingestion, lifecycle, employer-claim, origin-resolution, or pilot
implementation ticket.

## Required Google Drive Context

A new ChatGPT session must read all of these, in order, before directing Job
Center work:

1. Engineering Director Playbook
2. Job Center Project Cursor
3. Job Center Engineering Handoff
4. Job Center V1 Execution Plan

## Open Risks

- Real-job provenance, source identity, reconciliation, expiration, and employer
  claim capability have not yet been audited as one end-to-end V1 contract.
- Current typed ZIP/city-state origin resolution relies on local job data;
  supported origins must ultimately resolve independently of current inventory.
- Seeded jobs are coordinate-ready, but employer-created non-remote jobs do not
  yet have automated geocoding, retry, repair, or exception workflows.
- A real-job pilot is required before any bulk loading.
- Core Terms CTJ004-CTJ006 commits remain ahead of `origin/main` in the local
  Profilaxes repository and require separate remote-parity verification.
- Production deployment, monitoring, rollback, and V1 release-candidate approval
  remain pending.

## Stop Boundary

Stop after the readiness audit produces findings, a canonical job-contract
recommendation, dependency map, risk assessment, and smallest implementation
sequence. Do not import real jobs, mutate schema, add provider integrations,
change public UI, or begin pilot/bulk loading without a separately approved
ticket.
