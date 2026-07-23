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
  interaction sequence. JC-010 logged-out desktop authority is v1.1 following
  DESIGN009 right-rail reconciliation; responsive authority remains separate.

## Reconciled Phase-Gate Roadmap

The following is the authoritative forward dependency model from DOC020R
onward. It reconciles completed layers and does not claim that this exact
sequence formally governed earlier work:

Functional Foundation → Seed Marketplace → Public UI Polish → UX Atlas →
Visual Authorities → Responsive Authorities → Responsive Layout Geometry →
Repository State Verification → Phase Reconciliation → Implementation Strategy
→ Implementation Readiness Decision → Bounded Implementation Convergence →
Public and Employer Release-Candidate Audits → Real-Job Pilot → Operational
Launch Readiness → Explicit V1 Acceptance → Production Launch

## Current Authorized Workstream

Employer Operations remains in **Bounded Implementation Convergence**. The
JC-051A Employer My Jobs Desktop Authority v1.0 is now approved as the current visual authority, but
its bounded implementation and explicit browser/human visual acceptance remain
outstanding. My Jobs is the primary employer workspace; the separate Dashboard
direction is superseded for V1, with its useful concepts absorbed into My Jobs
as notifications, attention states, workflow guidance, summary context, and
School / Job Site scope. JC-050 All My Jobs and JC-051 Single School / Job Site
remain the approved desktop authorities. Their mobile adaptation is a
Responsive Candidate / Implementation Target pending Browser Verification, not
an Approved authority.

The current gate is bounded implementation and then browser/human acceptance of
the authenticated All My Jobs implementation against the JC-051A authority image.
The completed baseline includes the Employer
Workspace shell, external Chrome DevTools QA workflow, lifecycle fixture
architecture and invariant enforcement, typography weight convergence, white
navbar/gray rail shell, workspace selector, outlined Post a Job CTA, and
approved legacy Teachers.Net logo. After desktop acceptance, the next bounded
work is Employer Operations mobile implementation. UX Atlas placeholders are
not automatically scheduled.

## Forward Employer Implementation Sequence

1. Desktop implementation plan and diff mapping against Employer My Jobs Desktop Authority v1.0.
2. Desktop shell and navigation convergence.
3. Desktop table, status, and action convergence.
4. Compact footer convergence.
5. Desktop accessibility and visual acceptance, including contrast, semantics,
   keyboard, zoom, long-content, and measured visual comparison.
6. Responsive derivation only after stable desktop implementation and explicit
   acceptance.

This sequence preserves existing employer workflows and does not authorize a
broad redesign or responsive implementation before desktop acceptance.

## Completed Employer Operations Convergence Milestones

- **Employer Workspace shell convergence — Implemented and browser-verified:**
  1200px canvas, white navbar, approved Teachers.Net logo, Job Center label,
  notification/account controls, 250px gray rail, workspace selector, and
  outlined Post a Job CTA.
- **External Chrome DevTools browser QA workflow — Established:** dedicated QA
  Chrome profile, manual authentication, 1440 × 1000 verification, DOM and
  screenshot capture, console/page-error inspection, overflow checks, and
  narrower-width collision checks.
- **Lifecycle fixture architecture — Implemented and verified:** seeded
  `jobman` matrix, filter totals/pagination checks, state-specific timeline
  output, actions, and hidden archived/approved-only invariants.
- **Employer lifecycle invariant enforcement — Implemented and verified:**
  approved-only jobs are not employer-visible; archived jobs remain hidden.
- **Typography convergence study — Completed:** system stack rendered as Segoe
  UI in the Windows QA browser, with regular/bold 400/700 discipline.
- **Employer Operations shell redesign and legacy branding restoration —
  Implemented:** shell/navigation direction and supplied approved PNG logo are
  in the nested Jobs plugin through commit `5a11308`.
- **Workspace selector architecture — Implemented:** current workspace,
  authorized School / Job Site links, and manage-workspace destination are
  exposed from the My Jobs header.
- **Employer My Jobs Desktop Authority v1.0 approval — Completed:** JC-051A
  registered `docs/job-center/design/approved/jc-051a-employer-my-jobs-desktop-v1.0.png`
  as the current canonical raster; the DESIGN-AUTHORITY007 candidate, v1.1, and v1.0
  remain historical evidence.

These milestones are implemented and verified at the repository/browser-QA
level; they do not constitute explicit human visual acceptance of JC-050.

Each future interaction-state artifact inherits an approved page state and
changes only the minimum interface needed to document one interaction.

## V1 Critical Path

1. Implement and complete browser/human acceptance of the JC-050 final desktop authority,
   then begin bounded mobile implementation only if accepted.
2. Complete the remaining V1 visual-authority render groups recorded in the V1
   Authority Program.
3. **Real Job Ingestion and V1 Lifecycle Readiness Audit**
4. Approve the canonical real-job contract and dependency map.
5. Small implementation tickets for provenance/source identity, validation,
   deduplication, reconciliation, application integrity, expiration, coordinate
   coverage, independent origin resolution, and employer-claim gaps.
6. Employer posting and management acceptance.
7. Final Job Finder acceptance against actual supported behavior.
8. Limited real-job pilot.
9. Pilot corrections.
10. Controlled bulk import.
11. V1 release-candidate review, launch checklist, monitoring, and rollback plan.

## V1 Blockers

- ingestion readiness audit and approved real-job contract
- provenance, source identity, validation, deduplication, and batch traceability
- truthful external application routing
- expiration, reconciliation, exception reporting, and recovery paths
- job-coordinate coverage and independently resolvable typed search origins
- employer lifecycle acceptance and verified claim/conversion capability
- pilot import and corrections before bulk loading
- Job Finder acceptance and release-candidate review

## Current Visual Convergence Program

The remaining work is design exploration or explicit human acceptance, not an
implicit implementation queue:

- Employer detail workspace and recruiter campaign-management orientation.
- Performance dashboard, applicant workflow, and job metrics.
- Left-rail refinement, promotion placement, and summary strip.
- Typography fine tuning against approved authorities.
- JC-050 human visual acceptance and any separately bounded material corrections.

No implementation ticket is declared complete here without repository and
browser evidence. Deferred architecture remains separate from design
exploration and implementation.

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
