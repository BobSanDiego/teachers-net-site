# Decision Log

- Core Terms stabilized before Jobs began.
- Jobs is a separate plugin from Core Terms.
- Core Terms remains folder/repo `profilaxes` for now.
- Do not rename CFM namespaces, DB tables, slugs, URLs, prefixes, or file paths yet.
- Jobs uses custom tables, not WordPress posts, as primary storage.
- Employer is first-class.
- Employer is not a Core Term.
- Classification belongs in Core Terms.
- Job-specific lifecycle belongs in Jobs.
- Jobs may store selected Core Terms IDs in Jobs-owned bridge/configuration tables.
- Jobs must not write to Core Terms.
- Core Terms taxonomy counts may change through legitimate Core Terms maintenance; Jobs must synchronize by stable identifiers/hierarchy rather than assuming a fixed count.
- Promotion and billing must not contaminate the jobs table.
- External apply is acceptable for launch.
- Application Instructions reveal behavior is a Jobs-owned engagement signal, not an internal application workflow.
- Saved Jobs use the Jobs engagement system.
- Job Alerts are required for V1 and should be implemented as a small user-owned alert system, not as a general notification center.
- Communications must use the Jobs communication service.
- The public Jobs browse/search/detail experience is Jobs-owned and follows Design System v1.
- The canonical runtime hero asset is `hero-chalkboard-1200x450.webp`.
- CSV import is admin-controlled and must not auto-publish by default.
- ATS, resumes, candidate search, interviews, offers, hires, notification center,
  maps, commute-time routing, reviews, and commerce are reserved future objects
  unless explicitly reopened. Distance Search has been explicitly reopened for
  V1; automatic geocoding remains controlled readiness work.
- Users may hold multiple Jobs identities; avoid ranked single-role ladders.
- Permissions should be capability-based and employer-scoped.
- Employer-posted and Teachers.Net-curated/imported jobs share one public job
  entity, lifecycle, search engine, presentation, application behavior, and
  expiration behavior.
- Provenance is required internal metadata and does not create a separate public
  scraped-job class.
- Public source and application behavior must be truthful; external applications
  must route to the stored destination without implying Teachers.Net receives
  them.
- Supported typed origins must resolve independently of current job inventory.
- Google Places Autocomplete is not a V1 release blocker.
- A controlled real-job pilot precedes bulk loading.
- Employer claims require authority verification; recruiter identities are not
  auto-created from imported records.

## Architectural Decisions

### ADR001 — JC-030 Implementation Strategy

**Status:** Accepted
**Date:** 2026-07-15

ADR001 codifies the implementation strategy derived from the Responsive
Authority Program. It was adopted after the responsive-authority work and must
not be represented as though it governed earlier implementation. It governs
current and future JC-030 work: implement the approved authority as a new page
composition while reusing the existing route, services, repositories,
business logic, authentication, engagement behavior, formatting helpers,
responsive primitives, and advertisement primitives. The legacy Job Detail
page is not the implementation target; replace page composition, not
underlying behavior, and avoid broad architectural replacement when bounded
composition work is sufficient.

## Repository Ownership

- Root `teachers-net-site` repository: governance, roadmap, architectural
  decisions, audits, approved visual authorities, and continuity documents.
- Nested `tnet-jobs` repository: Job Center implementation.

## Source-of-Truth Precedence

1. Current explicit Engineering Director instruction
2. Verified Git state in the correct repository
3. Reconciled Project Cursor
4. Reconciled Engineering Handoff
5. Approved product, UX, design, and responsive authorities
6. Visual Manifest and Approved Library
7. UX Atlas
8. Historical roadmap and planning documents
9. Conversation summaries
10. Model memory
