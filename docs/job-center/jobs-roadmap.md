# Teachers.Net Jobs Roadmap

## Roadmap Context

This is the durable engineering sequence. For current state, read the Job Center
Project Cursor, Engineering Handoff, and V1 Execution Plan. Use Job Center UX
Atlas v1 for the concise map of governed screens and their relationships.

## Completed Foundation

- Core Terms integration and Jobs custom-table/repository/service foundation.
- Employer authorization, dashboard, posting, edit, moderation, and lifecycle
  workflow foundation.
- Public Job Finder, detail, saved jobs, application instructions, Job Alerts,
  public shell, and responsive presentation work.
- Synthetic seed dataset and idempotent local seed importer.
- JDIST002-JDIST006 Distance Search foundation: geocode metadata/indexing,
  local radius query, Advanced typed-origin controls, cross-state option, and
  request-scoped browser current location.
- **Search & Discovery Interaction Suite v1 — Complete:** Approved desktop
  references JC-010, JC-014, JC-015, and JC-011 define first-touch discovery,
  location selection, browse exploration, and search results as one governed
  interaction sequence.

## Next Primary UX Workstream

1. **JC-030 — Job Detail:** product truth and UX specification defined;
   AUDIT007 content/pipeline reconciliation complete; DESIGN006 desktop visual
   authority v1.0 approved; implementation audit next.
2. Employer UX
3. Saved Jobs / Alerts
4. Responsive adaptations

Each future interaction-state artifact inherits an approved page state and
changes only the minimum interface needed to document one interaction.

## V1 Critical Path

1. **Real Job Ingestion and V1 Lifecycle Readiness Audit**
2. Approve the canonical real-job contract and dependency map.
3. Small implementation tickets for provenance/source identity, validation,
   deduplication, reconciliation, application integrity, expiration, coordinate
   coverage, independent origin resolution, and employer-claim gaps.
4. Employer posting and management acceptance.
5. Final Job Finder acceptance against actual supported behavior.
6. Limited real-job pilot.
7. Pilot corrections.
8. Controlled bulk import.
9. V1 release-candidate review, launch checklist, monitoring, and rollback plan.

## V1 Blockers

- ingestion readiness audit and approved real-job contract
- provenance, source identity, validation, deduplication, and batch traceability
- truthful external application routing
- expiration, reconciliation, exception reporting, and recovery paths
- job-coordinate coverage and independently resolvable typed search origins
- employer lifecycle acceptance and verified claim/conversion capability
- pilot import and corrections before bulk loading
- Job Finder acceptance and release-candidate review

## V1 Launch Polish

- final Basic Search presentation
- compact Location + Distance interaction
- adaptive right rail and responsive QA
- truthful source/correction/removal treatment for imported jobs

## V1.1 Candidates

- provider-backed autocomplete and richer typeahead
- cached selected locations
- expanded employer conversion automation
- deeper personalization
- analytics wrapper and event coverage
- shared AdSense/house-ad helper and design-system capture
- SEO/indexing strategy and reusable taxonomy hubs

## Deferred V2

- maps, commute-time routing, and travel-time search
- richer teacher/recruiter context switching
- advanced personalization
- salary matching/filtering and unrelated workflow expansion
- ATS/internal applications, resumes, commerce, notification center, reviews,
  and candidate-search systems

Google Places Autocomplete is not a V1 blocker. It may improve origin entry but
must not delay the audited local/provider-backed origin-resolution path.
