# Job Center Project Cursor

## Project State

Active Development

## Startup Worktree Preflight

Before any Job Center implementation, verify the current working directory and
Git worktree is `/home/bobreap/projects/teachers-net-jobcenter`. If it is not,
stop and report the mismatch before implementation. Do not switch branches in
place to bypass this preflight.

## Current Ticketing and Reporting Authority

Codex Desktop tickets are delivered by ChatGPT as inline fenced code blocks in
the active conversation. Downloadable `.txt` files are supporting artifacts
only. ChatGPT owns product direction, review commentary, and sequencing; Codex
owns implementation, verification, Git, and the status-first completion report
and Report/Hopper payload. `CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE
TICKETS` are ChatGPT post markers, not required Codex report headings unless a
ticket explicitly requires them.

Current sequencing gate:

`JC053-STEPPER-RUNTIME-PARITY-DIAGNOSTIC` →
`JC053-STEP1-RUNTIME-ASSET-MIGRATION` →
`JC053-STEP1-ADD-SCHOOL-JOBSITE-INTEGRATION`.

JC053-MIG004B is now implemented: the canonical employer-create route serves
the authenticated JC053 Step 1 production seam using existing wizard,
resource, authorization, media, and job services. The legacy renderer remains
the controlled flag-off rollback. Image-format QA is the next bounded gate.

JC053 production UI authority is now the `tnet-jobs` runtime source recorded in
the authority manifest. The standalone workbench is archived historical
reference only; no runtime-to-workbench synchronization is required.

Deferred, non-blocking identity-boundary decision: Display Name is the compact
resource identity and Full Name remains the canonical institution name.
JC053-STEP1-RESOURCE-IDENTITY-OVERFLOW-DEFENSE and its selected-resource
responsive convergence are engineering-complete; human visual QA remains before
Manage Schools / Jobsites implementation and browser
certification before JC053 Step 2 integration. This ordering is required for
operational correctness and employer self-service; it authorizes no
implementation. The final Display Name authoring maximum remains
undecided pending persistence, validation, legacy-data, and consumer inspection.
The approximate 40-character direction is not implementation authority.

## Continuity and inspection rule

Do not repeatedly rediscover settled Job Center facts. Unless the active ticket
requires an audit or direct evidence contradicts accepted authority, rely on the
Authority Manifest, approved contracts, governance, roadmaps/execution plans,
and accepted implementation in that order. Inspect only the directly affected
files and dependencies. Stop and report contradictions instead of silently
reinterpreting project history.

For routine `next ticket` requests, retrieve only the latest companion-chat
turns needed to locate the latest complete `TICKET READY FOR CODEX`. Read older
turns only when the handoff is truncated, missing context, materially changed,
unresolved by repository authority, or contradictory.

### Repeated Human-QA Failure Escalation

When the same Job Center visual or behavioral defect remains after two or more
implementation passes and human QA still reproduces it, stop speculative
patches and issue a diagnostic ticket. Audit CSS cascade and authority
conflicts, JavaScript state/handler/render ordering and reparenting, and
DOM/rendered geometry, including hidden elements with nonzero rectangles,
duplicate controls, collapsed/offscreen/covered nodes, and stale owners.

The diagnostic must require screenshot capture and visual inspection at the
canonical runtime, relevant computed-style checks, actual bounding rectangles,
and comparison with the approved state. Semantic DOM attributes and automated
assertions alone never establish visual PASS. Any disagreement leaves the
ticket FAIL/BLOCKED until explained.

## Current Phase

Visual Convergence Sprint. Responsive Design governance is complete
for the approved authority set. Approved visual authorities remain immutable
Patch Mode references. ADR001 governs current and future JC-030 work.
DOC018 approves JC-030 Mobile Reading Experience authority. DOC016 approves JC-015 Mobile responsive authority. DOC015 approves JC-015
Tablet responsive authority. DOC014 approves JC-014
Mobile responsive authority. DOC013 approves JC-014 Tablet responsive
authority. DOC012 approves JC-011 Mobile responsive
authority. DOC011 approves JC-011 Tablet responsive authority. DOC008 approves
JC-010 Tablet responsive authority. DOC003 approves JC-010 Mobile responsive
authority, and DOC006 approves Logged Out and Logged In mobile navigation drawer
components. Future tablet, mobile, and drawer work is Patch Mode; implementation
convergence is active and must not alter approved visual authorities.

## Current Milestone

DESIGN-PATCH003 completed the repository/browser-verified Employer Operations
desktop convergence baseline through nested Jobs plugin commit `5a11308`:
external Chrome QA, lifecycle/invariant verification, typography discipline,
white navbar and approved logo, gray rail, workspace selector, and Post a Job
CTA. EMP-IMP019 advanced the nested implementation baseline through commit
`51afd38` with bounded brand, controller, rail-icon, and table-control
convergence; that implementation history is not visual acceptance.
JC-051A registered the approved Employer My Jobs Desktop Authority v1.0
with controlled raster `docs/job-center/design/approved/jc-051a-employer-my-jobs-desktop-v1.0.png` (1240 × 827;
SHA-256 `e142787148a881502f2e44e643ef34b375daf8d4e3208e2b43dbcbb602249702`) for
future Employer Operations All My Jobs desktop convergence.

RESP-DEC001 resolved the shared responsive decisions required to interpret the
approved desktop suite without defining breakpoints or implementation. DOC003
approves JC-010 Mobile v1.0 responsive authority within its visible boundary.
DOC005 corrects its Approved raster identity to
`docs/job-center/design/approved/job-center-responsive-jc010-mobile-02c-approved.png`,
the byte-identical controlled copy of the verified external 02c source.
DOC006 approves JC-003 and JC-004 as the shared mobile navigation drawer
components for JC-010, JC-011, JC-014, JC-015, and JC-030.
DOC008 approves JC-010 Tablet v1.0 through
`docs/job-center/design/approved/jc-010-job-finder-state-1-tablet-v1.0.png`,
the byte-identical controlled copy of the verified 03d repository candidate.
DOC011 approves JC-011 Tablet v1.0 through
`docs/job-center/design/approved/jc-011-job-finder-state-2-tablet-v1.0.png`,
the byte-identical controlled copy of the verified R003 repository candidate.
RESP-DEC002 resolves the JC-011 Mobile support-content exception. DOC012
approves JC-011 Mobile v1.0 through
`docs/job-center/design/approved/jc-011-job-finder-state-2-mobile-v1.0.png`,
the byte-identical controlled copy of the verified 360 × 975 external source
raster. The native resolution is an accepted provenance limitation and does not
authorize derivative replacement.
DOC013 approves JC-014 Tablet v1.0 through
`docs/job-center/design/approved/jc-014-location-selection-modal-tablet-v1.0.png`,
the byte-identical controlled copy of the verified R002 repository candidate.
DOC014 approves JC-014 Mobile v1.0 through
`docs/job-center/design/approved/jc-014-location-selection-modal-mobile-v1.0.png`,
the byte-identical controlled copy of the verified R003 localized overlay
repository candidate.
DOC015 approves JC-015 Tablet v1.0 through
`docs/job-center/design/approved/jc-015-browse-reveal-tablet-v1.0.png`,
the byte-identical controlled copy of the verified R003 cleaned repository
candidate.
DOC016 approves JC-015 Mobile v1.0 through
`docs/job-center/design/approved/jc-015-browse-reveal-mobile-v1.0.png`,
the byte-identical controlled copy of the verified 863 × 4042 R002 repository
candidate.
DOC018 approves JC-030 Mobile v1.0 through
`docs/job-center/design/approved/jc-030-job-detail-mobile-v1.0.png`, the
byte-identical controlled copy of the verified 853 × 1857 M008 repository
candidate. The remaining typography, chip-row, and advertisement-container
refinements are implementation guidance only; future JC-030 Mobile work is
Patch Mode.
RESP-ADS002 establishes Responsive Advertising Strategy v1 as the canonical
authority for responsive advertisement inventory, intrinsic dimensions,
placement hierarchy, and exceptions. RESP-LAYOUT002 establishes Responsive
Layout Geometry v1 as the canonical authority for responsive classes,
two-column eligibility, rail collapse, reading widths, and physical fit.
DOC017A approves JC-030 Narrow Tablet v1.0 through
`docs/job-center/design/approved/jc-030-job-detail-narrow-tablet-v1.0.png`,
the byte-identical controlled copy of the verified `917 × 1716` NT002 source
raster. Future JC-030 Narrow Tablet work is Patch Mode. JC030-IMP001 records the
browser implementation audit, and JC030-IMP002 records the canonical Apply /
Save / Share action-group implementation in the Jobs plugin.

## Current Focus

### Runtime parity and asset authority gate

`JC053-CANONICAL-UI-TRANSITION-001` retires the dual living workbench/runtime
model. The production `tnet-jobs` source is canonical for JC053 HTML, CSS,
JavaScript, and the PHP integration/rendering boundary. The standalone
workbench remains a frozen historical reference and is not a synchronization
source.

### DATA001-REV1 architecture adoption

The School / Jobsite contract is now approved as the staged hybrid in
`docs/job-center/DATA001-school-jobsite-architecture-decision-v1.md`.
Employer-private visibility, trusted-member management, affiliation/recovery,
the U.S./international minimum address rules, confidence-scored duplicate
handling, Jobs-owned primary media, Work Arrangement semantics, and the
full_name/display_name distinction are authoritative. The migration sequence
is `docs/job-center/DATA002-migration-roadmap-v1.md`; begin with DATA002. The
completed JC053-MIG004 ticket was intentionally backend-only and its temporary
draft/state seam is expected. The approved Step 1 UI migration is separately
identified as `JC053-MIG004B` and is not yet scheduled or authorized.

Every Job must have exactly one Primary Resource as its organizational anchor.
The resource need not be the physical work location. Work Arrangement remains
separate, and additional locations or job-specific overrides remain optional;
legacy compatibility may be nullable only during migration.

DATA002–DATA008 are the next executable backend/data-contract sequence and may
proceed in parallel with JC052–JC056 UX authority convergence. They do not
alter approved UX authority. JC057 remains the broader UX implementation
capability audit and does not block this data sequence.

The active objective is to complete the remaining UX authority work before the
implementation capability audit. The governing sequence is design first, audit
second, implement third. Visual convergence has materially clarified the
architecture, so the architecture audit remains deferred until the remaining
UX convergence work is complete.

The bounded convergence workstreams are:

- JC052 Employer Workspace Completion: Schools / Jobsites management, Add/Edit
  School / Jobsite, organization and Jobsite modeling, employer relationships,
  reusable management shell, imagery, progressive disclosure, default contact
  behavior, location model, and image defaults.
  Flow authority: `docs/job-center/employer-workspace-flow-authority-v1.md`.
- JC053 Job Posting Wizard Re-Convergence: finalized shell and controls,
  spacing, progressive disclosure, School / Jobsite selection, organization
  defaults, listing overrides, image behavior, and validation.
  Field contract: `docs/job-center/job-posting-wizard-field-contract-v1.md`.
  Current Step 3 direction is paste-first rich authoring, required Short Summary,
  grouped Optional Fields, compact Benefits selection with inline empty-state
  teaching, and incremental populated-content preview. Authority is recorded in
  `docs/job-center/jc053-wizard-design-system-v1.md` and the field contract.
- JC054 Teacher Discovery Final Pass: search, filters, sort, browse, listing
  cards, detail, employer/location presentation, imagery, and promoted listings.
- JC055 Teacher Account Modules: Saved Jobs, Job Alerts, alert management,
  sharing, printing, and PDF evaluation where justified.
- JC056 Identity & Onboarding: separation of Teachers.Net User, Job Center
  role, employer organization, School / Jobsite, and job listing, including
  job-seeker, recruiter, dual-role, and legacy-user onboarding.

JC057 is the later Implementation Capability Audit. JC058 and later tickets
are generated from that audit; they are not current implementation work.

## Current Authority Context

DOC022 reconciles the Employer product model: Employer Operations is a hybrid
authenticated workspace inside the Teachers.Net shell; personas are descriptive
planning models; memberships and granted capabilities determine operations;
Claim is contextual acquisition; and Add My School / Add Organization is the
user-facing new-organization path. The DOC021 authority families remain
unchanged; after EMP-DOC004, the remaining render program is four groups:
Employer Authoring, Employer Composite State Sheet, Saved Jobs, and Job Alerts.

The Employer Operations implementation has advanced through bounded desktop
shell, lifecycle, typography, and branding convergence. My Jobs is the primary
Employer Workspace; the separate Dashboard direction is superseded for V1,
with its useful concepts absorbed into My Jobs as notifications, attention
states, workflow guidance, summary context, and School / Job Site scope.
EMP-IMP002A through EMP-IMP003D established the operations workspace, rail,
inventory, and desktop geometry while preserving authorization, school
selection, services, actions, sorting, and pagination. EMP-PATCH006 through
EMP-PATCH012, EMP-IMP013, DESIGN-IMP001, DESIGN-IMP002, and DESIGN-PATCH003
completed the verified lifecycle fixture/filter pass, approved-state invariant,
typography weight pass, shell/navigation pass, external browser QA workflow,
and approved Teachers.Net logo replacement. Human visual acceptance against
JC-050 remains a separate gate; this implementation history is not itself
product acceptance.

JC-051A approves Employer My Jobs Desktop Authority v1.0, whose controlled copy is
`docs/job-center/design/approved/jc-051a-employer-my-jobs-desktop-v1.0.png`
(source `jc-050-unicard-002.png`; 1240 × 827; SHA-256
`e142787148a881502f2e44e643ef34b375daf8d4e3208e2b43dbcbb602249702`).
DESIGN-AUTHORITY007's replacement candidate, JC-050 v1.1, and JC-050 v1.0
remain superseded historical evidence. EMP-DOC004's JC-051
approval remains unchanged at
`docs/job-center/design/approved/jc-051-employer-operations-school-job-site-desktop-v1.0.png`.
These authorities govern the All My Jobs and selected School / Job Site desktop
presentations, including the floating application card, off-white outer canvas,
250px desktop left rail, 950px main workspace, official legacy Teachers.Net
logo, separator aligned to the rail boundary, compact 60px navbar plane, flat
bottom divider, compact My Jobs controller, filled Post a Job control,
integrated filter/inventory panel, compact Order By control, aligned Action
heading, widened overflow controls, timeline alert icon treatment, pagination,
and privacy footer. Exact Expired-to-Closed timing, archive semantics,
retention policy, Duplicate versus Repost wording, notification
implementation, and deeper analytics remain unresolved and must not be inferred
from the approved mockups.
Employer Operations / JC053 responsive presentation is an active
implementation-target workstream, not an approved authority. The latest
rollback retains full navigation through 1025px and enters compact Resources
at 1024px; final browser and human acceptance remain open. Responsive/mobile
presentation is also recorded as two provisional candidate
rasters (contracted and expanded selector) under
`docs/job-center/design/draft/`. They are Implementation Targets pending
Browser Verification, not Approved authorities. Team Members, bottom
navigation, list/grid switching, and other explicit exclusions remain
unapproved or deferred to V1.1.

The approved responsive visual authority set for the established public and
job-detail surfaces is complete. JC053 Employer Operations responsive
convergence remains provisional and active. JC-030 Narrow Tablet
v1.0, JC-030 Mobile v1.0, JC-015 Mobile v1.0, JC-015 Tablet v1.0, JC-014 Mobile v1.0, JC-014 Tablet
v1.0, JC-011 Mobile v1.0, JC-011 Tablet v1.0, JC-010 Tablet v1.0, and JC-010
Mobile v1.0 are in Patch Mode. Desktop JC-010 v1.1, JC-011 v1.0, JC-014 v1.0,
and JC-015 v1.0 remain the product/content authorities; desktop authority for JC-030 remains
unchanged. JC-014 Mobile changes only the backdrop and modal layer over the
JC-010 Mobile page. RESP-DEC002 governs the JC-011 Mobile support-content
exception. JC-011 Mobile's
native-resolution limitation does not authorize reconstruction, upscaling, or
other derivative replacement as authority. JC-003 and JC-004 drawers are also
Patch Mode component authority only. JC-030 is in bounded implementation
convergence under ADR001; JC030-IMP100 is verified implementation history, not
proof of browser convergence. The current gate is browser, accessibility, and
human visual verification, with no percentage-complete estimate recorded.

## Current Reference Page/Flow

JC-010 first-touch discovery → JC-014 location selection or JC-015 browse
exploration → JC-011 search results. Search and Browse share the same results,
lifecycle, presentation, and application behavior.

## Current Verified State

- Current convergence objective: complete the remaining UX authority work under
  JC052–JC056 before the JC057 implementation capability audit. Design first,
  audit second, implement third.
- Current authenticated browser QA: external `chrome-devtools-mcp` with the
  dedicated QA Chrome profile; the built-in browser bridge is not used.
- Current browser workflow: launch the dedicated profile with CDP on
  `127.0.0.1:9222`, authenticate as `jobman`, use 1440 × 1000, and verify
  route, screenshot, DOM, console/page errors, overflow, selector state, and
  the relevant filter/rows-per-page state.
- Canonical Engineering Director review URL for the active JC053 Step 3
  review: `http://127.0.0.1:8768/?#step-03-job-description`. All browser QA,
  screenshots, DOM, console, accessibility, and human acceptance must use
  this exact URL. Confirm runtime ownership before verification; an alternate
  port, server, worktree, launcher, or runtime does not satisfy the ticket.
- Current shell architecture: a 1200px app canvas with a white shared navbar,
  approved Teachers.Net PNG logo, Job Center label, notification/account
  controls, a 250px gray Employer Operations rail, and a flexible workspace
  containing the My Jobs selector, Post a Job CTA, preserved inventory table,
  filters, sort, pagination, and privacy notice.
- Current employer workflow: All My Jobs is the default authorized aggregate;
  School / Job Site scope is mutually exclusive; lifecycle filters, filtered
  totals, pagination, rows-per-page, actions, and state-specific timelines are
  fixture-verified. Archived jobs remain hidden and approved-only jobs are not
  employer-visible.
- Current implementation status: nested Jobs plugin `main` is clean and pushed
  through `ba2be81` (`JC051A-PATCH023 remove fixed page height`).
- Known tooling constraints: the external profile requires manual
  authentication; screenshot files may be unavailable when the MCP path is not
  inside its configured workspace roots, so inline screenshot capture is the
  durable fallback.

## Current Primitive/Workstream

Employer and public UX authority convergence across JC052–JC056 is the active
workstream. It reuses existing Jobs membership, authorization, services,
actions, sorting, and pagination without authorizing implementation. The
implementation capability audit is deferred to JC057; mobile Employer
Operations remains a provisional implementation target pending desktop
acceptance.

## Next Executable Ticket

The next bounded sequence is the approved JC052 Manage Schools /
Jobsites implementation and browser certification. JC053 Step 2 integration
must wait until that management gate clears. Do not begin the implementation
capability audit until the remaining UX authority is complete.
Do not reopen Employer Operations product architecture or reintroduce a
separate Dashboard operating destination during this gate.

## Next Decision

Complete the remaining UX convergence work, then run JC057 as the implementation
capability audit. Only after that audit should JC058+ implementation sequencing
be generated. Do not begin Employer Operations mobile implementation until its
desktop visual acceptance is explicit, and do not declare JC-030 converged until
its separate verification gate passes.

## Required Google Drive Context

Drive sync primary-code transitions: `0 / 10`. Last successful Drive sync:
unknown; baseline established under PROCESS-GOV002 on cycle `260802014510`.
Do not synchronize the operational Handoff after ordinary tickets. Sync only
for PREPARE HANDOFF, explicit Engineering Director request, major milestone or
phase transition, or the tenth primary-code transition.

A new ChatGPT session reads only these by default:

1. Engineering Director Playbook v2
   - https://docs.google.com/document/d/1GMT6pOFlhxC3wo4pfx6sxbxjzanPZJduvetY2CD6mWQ
2. Job Center Engineering Handoff
   - https://docs.google.com/document/d/1foiIgRjBcQcKUbGRsHRuCaPDk0R7o2BCwuFmx96Z3AE

The Project Cursor, Canonical V1 Contract, Employer UX V1, Job Center Design
System v1, Visual Manifest, roadmap, and implementation docs are consulted only
when the ticket requires them. Job Center UX Atlas v1 is the concise product map
for screen purpose, relationships, and governance status. Public Job Finder
remote inclusion and distance-sort behavior is governed by
`docs/job-center/job-finder-search-contract-v1.md`.

## Open Risks

## Canonical Chrome QA Recovery

An initially unavailable CDP endpoint is a recoverable QA-environment
condition, not a ticket blocker. Run:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File '\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\launch-chrome-cdp-9222.ps1'
```

The launcher path is
`tools/qa/launch-chrome-cdp-9222.ps1`; it uses the isolated Windows profile
`C:\Main\Active\Projects\Teachers.Net\tmp\chrome-qa-profile`, Chrome CDP at
`127.0.0.1:9222`, and the JC053 workbench URL. The MCP process must be
`chrome-devtools-mcp@1.6.0` with `--allow-unrestricted-paths` and
`--no-usage-statistics`. Verify `/json/version`, MCP inspection, and then
resume the ticket. Do not repeatedly search for ad hoc commands or use the
built-in/obsolete WSL browser bridge; stop only if the canonical launcher
itself fails after its bounded timeout.

Verified troubleshooting detail: Windows PowerShell must invoke the launcher
through the WSL UNC path; translating the Linux repository to `C:\home\...`
fails. Inspect Windows process command lines rather than killing Chrome
blindly. WSL loopback HTTP checks can time out while the external MCP route is
healthy, so confirm with MCP `list_pages`. Use a cache-bypassing reload before
responsive measurements and screenshots to avoid stale workbench CSS.

- Employer UX authority convergence remains incomplete across JC052–JC056;
  the implementation capability audit and later implementation remain deferred
  until those authorities are complete.
- The external Chrome QA workflow depends on the dedicated profile being
  launched and manually authenticated; the built-in bridge remains unavailable
  or non-canonical.
- Employer mobile remains a provisional implementation target and is not an
  approved responsive authority.

- JC030-IMP100 is retained implementation history; browser/accessibility/human
  visual acceptance remains outstanding.
- JC-010 Mobile v1.0, JC-010 Tablet v1.0, JC-011 Tablet v1.0, JC-011 Mobile
  v1.0, JC-014 Tablet v1.0, JC-014 Mobile v1.0, JC-015 Tablet v1.0, and
  JC-015 Mobile v1.0 are approved, but other screen-specific responsive
  authority remains pending and does not inherit those approvals.
- JC-003 and JC-004 approve only the mobile navigation drawer component; they
  do not establish underlying-page, responsive-layout, tablet, or implementation authority.
- Implementation has not yet converged to the written product/design authority.
- A real-job pilot is required before any bulk loading.
- Core Terms CTJ004-CTJ006 commits remain ahead of `origin/main` in the local
  Profilaxes repository and require separate remote-parity verification.
- No production deployment is established. Production monitoring, rollback,
  release-candidate audits, pilot, launch readiness, and explicit V1
  acceptance remain pending.

## Stop Boundary

Stop each approval or implementation ticket at its named boundary. Do not
reopen approved design, expand JC-030 into new product work, schedule UX Atlas
placeholders, or let the JC-030 audit backlog replace the broader
release-candidate roadmap. JC-030
Mobile, JC-011 Mobile, JC-015 Mobile, JC-015 Tablet, JC-014 Mobile, JC-014 Tablet, JC-011
Tablet, JC-010 Tablet, JC-010 Mobile, and JC-003/JC-004 drawers are Patch Mode
and permit only separately approved tablet, mobile, or component deltas. Do not infer
underlying-page or other-screen visual approval, import real jobs, mutate schema, add
provider integrations, or begin pilot/bulk loading without a separately
approved ticket.
