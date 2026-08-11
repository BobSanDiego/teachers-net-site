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

### JC057 — Sourced-job authority foundation

The controlling authority is
`docs/job-center/sourced-job-v1-authority-contract.md`. The bounded future
sequence is discovery schema, employer sourcing policy, Admin review queue,
separate sourced-match notifications, and controlled-ingestion integration.
This architecture work does not move ahead of the currently active
employer-authority and wizard work, and the contract authorizes no application
implementation by itself.

### Current ticketing and reporting gate

Codex Desktop engineering tickets are issued inline in a fenced code block by
ChatGPT. Downloadable `.txt` tickets are optional supporting artifacts and do
not supersede the active inline ticket. ChatGPT owns review posts and
sequencing; Codex owns implementation, verification, Git, and the status-first
completion report with Report/Hopper evidence. The conversational markers
`CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE TICKETS` belong to ChatGPT's
review/handoff post unless a ticket explicitly requires them.

The current Job Center sequence is explicitly:

1. `JC053-STEPPER-RUNTIME-PARITY-DIAGNOSTIC` — compare and reconcile the
   production integration route with the workbench runtime.
2. `JC053-STEP1-RUNTIME-ASSET-MIGRATION` — make the canonical production asset
   source/version/runtime seam explicit and verify it.
3. `JC053-MIG004B` — promote JC053 Step 1 into the authenticated production
   employer-create seam. **Completed.**
4. `JC053-STEP1-JOBSITE-IMAGE-BROWSER-CERTIFICATION` — **Completed; human
   visual acceptance PASS.**
5. `JC053-STEP1-RESOURCE-IDENTITY-OVERFLOW-DEFENSE` — **Engineering correction
   complete; selected-resource responsive convergence accepted.**
6. `JC052-MANAGE-SCHOOLS-JOBSITES-INTEGRATION` — **Completed.**
7. `JC052-MANAGE-SCHOOLS-JOBSITES-BROWSER-CERTIFICATION` — **Completed;
   cycle 260810101500, commit 769c39c pushed.**
8. `JC053-STEP2-INTEGRATION` — **Current executable objective.**

`JC052-MANAGE-SCHOOLS-JOBSITES-INTEGRATION` is implemented as the bounded
authenticated `/jobs/employer/schools/` production route. The implementation
exposes shared catalog view, create, and ordinary edit through existing
authorization and resource services; media editing and destructive/archive
management remain outside its scope.

`JC052-MANAGE-SCHOOLS-JOBSITES-BROWSER-CERTIFICATION` is complete: Admin and
Recruiter authenticated behavior, shared catalog visibility, create/edit
roundtrip, and cleanup were accepted. JC053 Step 2 integration may now resume
under its own ticket.

`JC053-STEP2-INTEGRATION` attempted cycle `260810131301` and correctly blocked
before implementation on a Benefits ownership contradiction. The contradiction
is now resolved: Benefits belongs exclusively to Step 3 Optional Fields for V1.
Step 2 Job Basics owns Position, Employment, Starting Date, and Compensation.

`JC053-GOVERNANCE-AUTHORITY-DRIFT-AUDIT` cycle `260810133514` then confirmed
that the field contract's Starting Date / Start Timing deferral was unsupported
suspect drift, while accepted Step 2 evidence kept Starting Date in V1 with
default `Immediately`, `Specific Date` conditional Start Date reveal, and
`Flexible` as the alternate. Work Location default `Use School / Jobsite
Location` and Salary Visibility default `Show Salary` were accepted but
under-documented.

`JC053-GOVERNANCE-AUTHORITY-CONSOLIDATION` restored the Step 2 portions of that
authority but incorrectly strengthened Requirements / Qualifications to
required. `JC053-FULL-WIZARD-AUTHORITY-DRIFT-CONVERGENCE` supersedes that drift
and establishes `docs/job-center/jc053-wizard-product-contract-v1.md` as the
single canonical owner for JC053 V1 field ownership, requiredness, defaults,
conditional behavior, progressive disclosure, and step-transition gates.
Starting Date remains Step 2 V1, Work Location and Salary Visibility defaults
are explicit with no blank ordinary placeholder, Benefits remains Step 3 only,
Requirements / Qualifications is optional/recommended for matching, and Short
Summary is handled by the deterministic summary review/gate before Step 4 when
needed. `JC053-STEP2-INTEGRATION` is the immediate next executable objective.

Manage Schools / Jobsites is now a prerequisite before Step 2 for operational
correctness and employer self-service. This documentation change authorizes no
implementation and preserves the existing Employer Operations authority.

This sequence supersedes older roadmap entries that place Add School/Jobsite
integration immediately after the first hydration or parity ticket.

### DATA001-REV1 — Adopted data architecture gate

The approved School / Jobsite architecture is the staged hybrid: employer
private by default, relationship-based reuse, trusted-member management,
confidence-scored Create / Reuse / Relate / Resolve duplicate handling, one
Jobs-owned primary image, resource-plus-Work-Arrangement job locations, and
required `full_name` with optional `display_name`. The data migration roadmap
starts with DATA002 and is recorded in
`docs/job-center/DATA002-migration-roadmap-v1.md`. JC053 Step 1 production
integration is deferred until the data contract sequence is certified.
Every Job must have exactly one Primary Resource as its organizational anchor;
Work Arrangement never removes that requirement, and legacy nullable
compatibility is temporary migration behavior only.

DATA002–DATA008 are the next executable backend/data-contract sequence and may
proceed in parallel with JC052–JC056 UX authority convergence. They do not
alter approved UX authority. JC057 remains the broader UX implementation
capability audit and does not block this data sequence.

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
preview-truthful: Job Description is the only immediate required narrative
field; Short Summary is recommended in authoring and guaranteed before Step 4
through the deterministic summary review/gate when needed; Requirements /
Qualifications is optional/recommended for matching; Responsibilities,
Preferred Qualifications, About Our School, and Benefits are optional
enrichment; and Listing Preview renders only populated sections. Benefits uses
the compact inline selector and empty-state teaching pattern recorded in the
JC053 Wizard Product Contract, Wizard Design System, and field contract.

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

JC056 authority is now controlled by
`docs/job-center/employer-authority-contract-v2.md`. The contract revision
supersedes the prior multi-employer capability strategy and must be followed by
membership inventory/schema planning and a canonical Admin/Recruiter capability
boundary before employer-management implementation continues.

The JC056 authority chain is complete for the current Step 2 prerequisite:
membership reconciliation, one-active-employer schema hardening,
founding-employer provisional authoring, and Admin-approved affiliation are
implemented and committed. No sourcing policy, notifications, or billing is
included.

The existing EmployerClaim infrastructure is also the canonical
AffiliationRequest owner. The bounded approval implementation adds Employer
Admin authorization, explicit Admin/Recruiter role assignment, self-approval
protection, and transactional membership/decision finalization. No parallel
request subsystem or schema change was introduced.

`JC054-MY-JOBS-ROLE-SCOPED-QUERY` and
`JC054-MY-JOBS-BROWSER-CERTIFICATION` are complete after the JC056 authority
sequence. Selected-employer listing, counts, filters, pagination, sorting,
metrics, and direct management actions remain behind the canonical
Admin-all/Recruiter-own `job_manage` scope. Browser certification
follows the implementation before Manage Schools / Jobsites integration.

## Authoritative Convergence Decisions

The following decisions govern the remaining design-first convergence sprint and
must be carried into the later capability audit and implementation sequence:

- **Location model:** support Physical US, Remote, International, and Multiple
  Locations. For Physical US entry, ZIP input performs automatic lookup to City
  and State; there is no separate lookup button.
- **Display Name:** use Display Name instead of Short Name for compact
  listings, cards, search, and header presentation. Provide an informational
  tooltip; do not introduce unsupported response-rate claims.
- **Resource identity boundary:** Display Name is the intended compact
  School / Jobsite identity; Full Name remains the canonical institution name
  and may be substantially longer. Current Step 1 work is not blocked by
  long-name handling. Before final Step 1 certification, schedule
  JC053-STEP1-RESOURCE-IDENTITY-OVERFLOW-DEFENSE for bounded multiline
  display-name/full-name presentation, pathological-value wrapping,
  graceful truncation, complete accessible exposure, and hostile-fixture
  verification. This is deferred, non-blocking work, not a frozen authoring
  maximum.
- **Display Name authoring contract:** before School / Jobsite employer
  authoring is launch-ready, inspect persistence, validation, legacy-data, and
  consumer contracts and then establish the final Display Name maximum. The
  approximate 40-character direction is not implementation authority.
- **Jobsite imagery:** Jobsite image is optional. Teachers.Net supplies a
  default when one is omitted; imagery may be recommended but is not required.
- **Progressive disclosure:** keep the base form rapid and place advanced
  sections behind collapsed disclosure, including Jobsite Image and Additional
  Information where applicable.

These are product/UX authority decisions, not implementation instructions. The
JC057 audit must verify their fit against the current repositories and services
before JC058+ implementation work is generated.

## Forward Sequence

1. JC053-STEP1-JOBSITE-IMAGE-BROWSER-CERTIFICATION — **Complete; human visual
   acceptance PASS.**
2. JC053-STEP1-RESOURCE-IDENTITY-OVERFLOW-DEFENSE — **Engineering correction
   complete; selected-resource responsive convergence accepted.**
3. JC052-MANAGE-SCHOOLS-JOBSITES-INTEGRATION — **Complete.**
4. JC052-MANAGE-SCHOOLS-JOBSITES-BROWSER-CERTIFICATION — **Complete.**
5. JC053-STEP2-INTEGRATION — **Current executable objective.**
6. JC054 Teacher Discovery Final Pass.
7. JC055 Teacher Account Modules.
8. JC056 Identity & Onboarding.
9. JC057 Implementation Capability Audit.
10. Generate JC058+ implementation sequence from the audit.
11. Execute bounded implementation, browser/accessibility verification, release-
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

1. Complete the JC056 founding-employer provisional-authoring and
   Admin-approved affiliation capability sequence after the database invariant
   hardening.
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

## Post-V1 Recruiter Growth, Distribution, and Monetization Strategy

This section records the approved post-V1 product strategy without changing the
current V1.0 execution sequence. It is roadmap authority, not authorization to
implement analytics, ranking, promotion, pricing, billing, or commercial
permissions.

### V1.0 — Marketplace liquidity, traffic, SEO, and advertising

V1.0 is intended to get substantial job inventory live, rebuild recruiter
participation, rebuild teacher/jobseeker traffic, create indexable job content,
generate job-page/listing traffic, and begin or expand advertising revenue from
teacher-job traffic. TNET-sourced jobs may provide inventory depth while direct
employer supply develops, but sourced and employer-posted jobs are not
strategically equivalent.

Employer-posted jobs should receive systematic organic distribution preference
over TNET-sourced jobs, subject to teacher relevance and search-quality
guardrails. Future applicable surfaces may include search ranking, Latest Jobs,
related-job modules, homepage/editorial placements, and other Teachers.Net
distribution surfaces. This is a distribution principle, not a current hard
ordering rule.

Sourced inventory primarily contributes marketplace depth, SEO coverage, teacher
utility, traffic, and advertising inventory. Employer-posted inventory adds
recruiter relationships, retention, measurable recruiter ROI, future promotion
demand, and possible future paid-listing demand.

V1.0 should preserve durable performance provenance where practical so history
begins accumulating before a polished recruiter dashboard ships. At roadmap
level, provenance should eventually distinguish employer-posted versus
TNET-sourced jobs, distribution surface, placement type, later promotional
attribution, and downstream engagement/conversion events. Candidate event
families include impression/reach, detail view, employer/profile/outbound visit,
save, share, and application or application-intent where reliably measurable.
This section does not define an analytics schema.

### V1.1 — Recruiter analytics and premium promotional inventory

V1.1 may provide recruiter-facing analytics oriented toward recruiting
economics, including Teacher Reach, Job Detail Views, Employer Visits, Jobs
Saved, Shares, and defensible Applications or application-intent measures, with
derived conversion/economic metrics. Do not label a metric a lead unless its
conversion event is authoritative.

The contemplated Standard Job Listing reference value is approximately $99
while currently included free. This is a reference value, not a charging
decision. Any hypothetical reference-value cost per view, visit, or measurable
high-intent action must be clearly distinguished from actual current listing
cost or recruiter spend: REFERENCE VALUE != ACTUAL SPEND.

Optional premium promotional inventory may later span search/results, job pages,
the homepage, and other appropriate high-value surfaces. Ordinary
employer-posted jobs may first be rotated experimentally through promotional
inventory to establish real baselines. Normal organic employer distribution
and explicit promotional distribution must remain separately attributable so
incremental performance can be measured. Teachers.Net need not normalize away
its organic employer-posting preference; comparisons must use actual observed
data and must not manufacture uplift claims.

### V1.2 — Evidence-driven consideration of paid standard listings

Paid standard listings are not assumed to be necessary. Consider broader
standard-listing monetization only if recruiter adoption, job supply, teacher
traffic, performance history, marketplace liquidity, and demonstrated recruiter
economics support it. A future direction could move some or all job types toward
a paid reference-value range, but free standard listings remain a valid option
indefinitely if advertising revenue, premium promotion, traffic, or recruiter
supply economics make free listings the stronger model. Use performance data to
discover pricing rather than imposing pricing assumptions.

### Commercial authority constraint and marketplace flywheel

JOB MANAGEMENT AUTHORITY != COMMERCIAL/PURCHASE AUTHORITY. Employer Admin and
Recruiter authority governs job authoring and management. Future promotion or
paid products may require explicit individual confirmation, Employer Admin
approval, a separate commercial capability, or another future authorization
layer. Suspending or revoking employer authority must not erase or invalidate
historical paid transactions or purchased placements; commercial lifecycle
remains separate from current membership authority. Billing, packages, refunds,
and purchase permissions are deferred.

The strategic flywheel is: TNET-sourced jobs build inventory; inventory attracts
teachers; traffic creates SEO and advertising value; employer-posted jobs
receive strategic organic preference; recruiters receive measurable value and
return; direct inventory increases; dependence on sourced inventory decreases;
premium promotion becomes valuable; and eventual listing monetization can be
tested from real data. These are strategic hypotheses, not guarantees.

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
- recruiter analytics and the minimum provenance/event coverage dependency
  described in the Post-V1 strategy above; final analytics schema remains a
  later contract
- premium promotional inventory with separate organic/promotional attribution
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
