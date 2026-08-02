# Teachers.Net Jobs Roadmap

## Roadmap Context

This is the durable engineering sequence. For current state, read the Job Center
Project Cursor, Engineering Handoff, and V1 Execution Plan. Use Job Center UX
Atlas v1 for the concise map of governed screens and their relationships.
The Job Finder remote and distance-sort contract is governed by
`docs/job-center/job-finder-search-contract-v1.md`.

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

JC053 responsive shell refinement is a bounded active implementation-target
workstream within the wizard workstream. It is not an approved production
authority. Acceptance requires local Windows-visible PNG evidence, external
Chrome DevTools verification, and human visual review. The intended contract
retains full 250px brand/rail and full navbar through 1025px, then uses compact
210px brand/rail with one unified Resources control at 1024px and below.
Shared structural axes are authoritative; page-specific offsets and partial
navigation collapse are not accepted.

The project is in a **Visual Convergence Sprint**. The immediate objective is
to complete remaining UX authority work before the implementation capability
audit. The governing philosophy is **design first, audit second, implement
third**. Visual convergence has materially clarified the architecture, so the
architecture audit is intentionally deferred until the remaining UX work is
complete.

The current JC053 Step 3 direction is paste-first, progressive, and
preview-truthful: Job Description, Requirements / Qualifications, and Short
Summary are required narrative inputs; Responsibilities, Preferred
Qualifications, About Our School, and Benefits are grouped as optional
enrichment; and Listing Preview renders only populated sections. Benefits uses
the compact inline selector and empty-state teaching pattern recorded in the
JC053 Wizard Design System and field contract. This is design authority for
future convergence, not implementation authorization by itself.

The explicit bounded workstreams are:

### JC052 — Employer Workspace Completion

Schools / Jobsites management; Add/Edit School / Jobsite; Organization versus
Jobsite modeling; employer account relationships; a reusable management shell;
organization imagery; progressive disclosure; default contact behavior; the
adaptive location model; and image defaults.

### JC053 — Job Posting Wizard Re-Convergence

Revisit the existing wizard using the finalized shell, reusable controls,
spacing, progressive disclosure, School / Jobsite selection, organization
defaults, listing overrides, image behavior, and validation improvements. The
wizard becomes authoritative after this convergence.
The field inventory, admission rule, deferrals, and open decisions are recorded
at `docs/job-center/job-posting-wizard-field-contract-v1.md`. Shared JC053 UI
authority is defined in
`docs/job-center/jc053-wizard-design-system-v1.md`; future tickets must reuse
its Wizard Responsive Form Grid, trailing-icon control, stepper, bottom
navigation, Choice Card, Step 3 authoring, Benefits selector, and incremental
preview primitives.

### JC054 — Teacher Discovery Final Pass

Complete one additional authority pass for search, filters, sort, browse,
listing cards, job detail, employer presentation, location presentation,
imagery, and promoted listings before public browsing implementation proceeds.

### JC055 — Teacher Account Modules

Define Saved Jobs, Job Alerts, alert management, sharing, printing, and PDF
evaluation where justified, including account integration, notification UX, and
future extensibility.

### JC056 — Identity & Onboarding

Document the separation between Teachers.Net User, Job Center role, employer
organization, School / Jobsite, and job listing. Cover minimal-friction and
capability-based onboarding for job seekers, recruiters, dual-role users, and
legacy Teachers.Net users, including recruiter affiliation and organization
permissions.

JC057 is the later Implementation Capability Audit. JC058 and later tickets
are generated from that audit and are not current implementation work.

## Authoritative Convergence Decisions

The following decisions govern the remaining design-first convergence sprint and
must be carried into the later capability audit and implementation sequence:

- **Location model:** support Physical US, Remote, International, and Multiple
  Locations. For Physical US entry, ZIP input performs automatic lookup to City
  and State; there is no separate lookup button.
- **Display Name:** use Display Name instead of Short Name for compact
  listings, cards, search, and header presentation. Provide an informational
  tooltip; do not introduce unsupported response-rate claims.
- **Jobsite imagery:** Jobsite image is optional. Teachers.Net supplies a
  default when one is omitted; imagery may be recommended but is not required.
- **Progressive disclosure:** keep the base form rapid and place advanced
  sections behind collapsed disclosure, including Jobsite Image and Additional
  Information where applicable.

These are product/UX authority decisions, not implementation instructions. The
JC057 audit must verify their fit against the current repositories and services
before JC058+ implementation work is generated.

## Forward Sequence

1. JC052 Employer Workspace Completion authority convergence.
2. JC053 Job Posting Wizard Re-Convergence.
3. JC054 Teacher Discovery Final Pass.
4. JC055 Teacher Account Modules.
5. JC056 Identity & Onboarding.
6. JC057 Implementation Capability Audit.
7. Generate JC058+ implementation sequence from the audit.
8. Execute bounded implementation, browser/accessibility verification, release-
   candidate audits, pilot, and explicit V1 acceptance.

The approved JC-051A desktop implementation and its browser/human acceptance
remain a bounded evidence stream within this broader convergence program; they
do not authorize mobile or a premature implementation audit.

This sequence preserves existing employer workflows and does not authorize a
broad redesign or responsive implementation before desktop acceptance.

## Completed Employer Operations Convergence Milestones

### Process governance

PROCESS-GOV001 establishes canonical review URL discipline for all future UI
work. The active Project Cursor and Engineering Handoff record the exact
Engineering Director review URL; completion reports must state whether that URL
was verified and must not promote alternate runtimes.

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

1. Complete the JC052–JC056 visual-authority convergence work, then run the
   JC057 implementation capability audit.
2. Generate and execute the bounded JC058+ implementation sequence, including
   browser/accessibility verification and explicit V1 acceptance.
3. Complete the remaining V1 visual-authority render groups recorded in the V1
   Authority Program.
4. **Real Job Ingestion and V1 Lifecycle Readiness Audit**
5. Approve the canonical real-job contract and dependency map.
6. Small implementation tickets for provenance/source identity, validation,
   deduplication, reconciliation, application integrity, expiration, coordinate
   coverage, independent origin resolution, and employer-claim gaps.
7. Employer posting and management acceptance.
8. Final Job Finder acceptance against actual supported behavior.
9. Limited real-job pilot.
10. Pilot corrections.
11. Controlled bulk import.
12. V1 release-candidate review, launch checklist, monitoring, and rollback plan.

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
