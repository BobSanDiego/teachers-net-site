# Job Center Engineering Handoff

Operational state only. Permanent product, design, architecture, and
implementation detail remain in the referenced repository documents.

## Startup Worktree Preflight

JC053 production UI implementation must use the repository/worktree containing
the production `tnet-jobs` source at `/home/bobreap/projects/teachers-net-site`.
Verify the current working directory and Git worktree before inspecting
implementation state. The former `/home/bobreap/projects/teachers-net-jobcenter`
workbench is archived reference only. If the production startup worktree does
not match, stop and report the mismatch; do not switch branches or proceed.

## 1. Current Phase

Active Development - Visual Convergence Sprint.

## Handoff and Ticketing Authority

The active Codex Desktop ticket is delivered inline in a fenced code block by
ChatGPT. Downloadable ticket files are optional supporting artifacts and do not
override the active inline ticket. ChatGPT owns review commentary and the
next-ticket sequence. Codex owns implementation, verification, Git, and the
status-first completion report plus Report/Hopper artifacts.

`CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE TICKETS` belong to ChatGPT's
review/handoff post and are not required in Codex completion reports unless the
active ticket explicitly requests them.

### Continuity and handoff retrieval

Previously accepted authority remains valid unless the active ticket requires
re-audit or direct repository evidence contradicts it. Use the Authority
Manifest, approved contracts, governance, roadmaps/execution plans, and
accepted implementation before companion chat. Inspect only the implementation
materially required by the ticket; stop and report contradictions.

For routine next-ticket execution, tail-read the latest companion-chat portion
only far enough to locate the latest complete `TICKET READY FOR CODEX`. Do not
ingest the full transcript unless the ticket is truncated, required context is
missing, project context changed, repository authority cannot resolve the issue,
or a contradiction is detected.

### Repeated Human-QA Failure Escalation

If human QA reproduces the same visual or behavioral defect after two or more
implementation passes reported complete by Codex, stop issuing speculative
fixes. The next ticket must be diagnostic and inspect conflicting CSS
authorities, cascade/specificity/order, media or container queries,
`!important`, native `[hidden]`, state selectors, stale rules, duplicate
JavaScript handlers or state owners, initialization/post-load overwrites,
cloning/reparenting, duplicate DOM controls, and final rendered geometry.

Visual acceptance requires a screenshot captured and inspected from the
canonical browser, computed-style evidence where relevant, actual element
geometry where relevant, and comparison with the approved design/state. DOM
attributes, state variables, and selector existence cannot override a contrary
rendered result; disagreement leaves the ticket FAIL/BLOCKED until explained.

### JC053 disposable QA fixture discipline

Every authenticated JC053 verification ticket that creates School / Jobsite
data must use a uniquely named, explicitly disposable fixture and record its
resource ID, employer relationship ID, media binding ID, and attachment ID when
media is involved. Before testing, verify the actual employer/resource state;
never assume a zero-resource state from the ticket description or chooser UI.

At ticket completion, remove the fixture and its test-only relationship/media
and attachment data when it is not explicitly required by the immediately
following ticket. Preserve legitimate or uncertain resources and any resource
referenced by a real job. The completion report must state which fixtures were
removed, which were intentionally preserved, the resulting chooser state, and
whether the next test begins from `ZERO-RESOURCE START` or
`CONTROLLED-NONZERO START`.

Fixture cleanup is a narrow reconciliation operation, not a broad employer-ID
reset. Classify each relationship from repository/runtime evidence before
mutation and verify that no dangling relationships, media bindings, or
test-only attachments remain afterward.

Authentication and physical-desktop gate: if browser work requires login,
MFA, permission approval, popup handling, file selection, drag/drop,
clipboard interaction, or another action that automation cannot complete,
Codex must stop and flag the engineer through the clearest available
Windows/Desktop notification plus explicit Codex commentary. Codex must not
assume the action occurred or claim authenticated verification until MCP
observes the resulting authenticated page/state. If it cannot be observed,
report browser verification as `UNAVAILABLE` or `PARTIAL` and name the
blocker; continue only with trustworthy same-runtime fallback diagnosis when
the ticket objective permits it.

Recommended ChatGPT handoff pointer:

```text
CODEX HANDOFF POINTER

Latest ticket: <ticket title>
Complete ticket body: YES | NO
Prior context required: YES | NO
Required local documents: <paths>
Stop boundary: <summary>
Current authority manifest: <path>
Current authority commit: <commit>
```

Responsive Design is complete only for the previously approved public and
job-detail authority set. JC053 Employer Operations responsive convergence is
an active implementation-target workstream. Remaining UX authority
convergence is the active workstream. Approved
visual authorities remain Patch Mode references and must not be altered during
implementation.

## 2. Current Ticket

`JC053-CANONICAL-UI-TRANSITION-001` establishes production `tnet-jobs` as the
canonical JC053 UI source. The standalone workbench is frozen historical
reference only. Future JC053 UI work occurs directly in production-owned
runtime sources; synchronization back to the workbench is not required.

The approved School / Jobsite contract remains the staged hybrid in
`docs/job-center/DATA001-school-jobsite-architecture-decision-v1.md`, and the
data sequence in `docs/job-center/DATA002-migration-roadmap-v1.md` remains
authoritative for backend/data work. Do not use that older data sequence to
bypass the current runtime asset gate.

DATA001-PATCH001 correction: every Job requires exactly one Primary Resource as
its organizational anchor, including Remote, Hybrid, District-wide, and
Multi-site jobs. Work Arrangement describes physical-work semantics; optional
additional locations and job-specific overrides remain separate. Legacy
compatibility may be nullable during backfill only and is not the target
contract.

DATA002–DATA008 are the next executable backend/data-contract sequence and may
proceed in parallel with JC052–JC056 UX authority convergence. They do not
alter approved UX authority. JC057 remains the broader UX implementation
capability audit and does not block this data sequence.

JC-051A is complete: `jc-051a-employer-my-jobs-desktop-v1.0.png` is the approved
canonical Employer My Jobs Desktop Authority v1.0, sourced from
`jc-050-unicard-002.png` and superseding the prior JC-050 visual authorities. No
implementation ticket is active; the approval does not constitute
implementation or acceptance.

Current stop boundary: complete the remaining UX authority work before the
JC057 implementation capability audit. Do not begin implementation sequencing
from the audit until the design-first convergence gate is complete. JC053-MIG004
was intentionally backend-only; its temporary draft/state seam is expected.
The approved Step 1 UI replacement is a separate, currently unscheduled
`JC053-MIG004B` ticket. Do not begin
Employer Operations mobile implementation until desktop acceptance is explicit.

JC053-MIG004B is now implemented in the authenticated production
employer-create seam. It reuses the existing wizard-session, School / Jobsite
identity, authorization, repository, media, and job services. The legacy
Guided Job Posting renderer remains the flag-off rollback; image-format QA is a
separate follow-up.

Active bounded workstreams are JC052 Employer Workspace Completion, JC053 Job
Posting Wizard Re-Convergence, JC054 Teacher Discovery Final Pass, JC055
Teacher Account Modules, and JC056 Identity & Onboarding. JC057 is the later
implementation capability audit; JC058+ is generated from its findings.
Canonical Engineering Director review URL for JC053 Step 3:
`http://127.0.0.1:8768/?#step-03-job-description`.
All UI verification must use this exact URL and report
`Verified against canonical URL: YES` or `NO`, plus PID, command line, cwd,
docroot, loaded asset paths, and relevant hashes. If the canonical runtime is
stale, broken, unreachable, or serves different code, stop and repair the
canonical runtime before verification; an alternate runtime is not acceptable.

The JC053 V1 field inventory and admission contract is recorded at
`docs/job-center/job-posting-wizard-field-contract-v1.md`.

## 3. Last Completed Milestone

EMP-IMP002A through EMP-IMP003D replaced and converged the legacy My Jobs
presentation into the Employer Operations workspace while preserving existing
services, authorization, school selection, actions, sorting, and pagination.
EMP-PATCH006 through EMP-PATCH012 and EMP-IMP013 completed the bounded
hierarchy, lifecycle filter/count, fixture, and approved-state invariant work.
DESIGN-IMP001 established the system-font/Segoe UI baseline and 400/700 weight
discipline. DESIGN-IMP002 and DESIGN-PATCH003 established the white navbar,
gray 250px rail, workspace selector, Post a Job CTA, external browser workflow,
and approved Teachers.Net PNG logo. EMP-IMP019 completed bounded brand,
controller, rail-icon, and table-control convergence. The current nested Jobs
plugin `main` is clean and pushed through `ba2be81` (`JC051A-PATCH023 remove
fixed page height`).

JC-051A superseded the prior JC-050 All My Jobs desktop raster with the
final approved Employer My Jobs Desktop Authority v1.0 while leaving JC-051 unchanged. The current approved
desktop pair is:

- Employer My Jobs Desktop Authority v1.0 (jc-051a-employer-my-jobs-desktop-v1.0.png, 1240 × 827,
  SHA-256 e142787148a881502f2e44e643ef34b375daf8d4e3208e2b43dbcbb602249702)
- JC-051 Single School / Job Site

The approved direction treats My Jobs as the employer operating workspace.
Dashboard concepts are absorbed into My Jobs as notifications, attention
states, workflow guidance, summary context, and School / Job Site scope. Do not
reintroduce a separate Employer Dashboard operating destination for V1.

## 4. Next Five Planned Tickets

1. JC053-STEP1-JOBSITE-IMAGE-BROWSER-CERTIFICATION — **Complete; human visual
   acceptance PASS.**
2. JC053-STEP1-RESOURCE-IDENTITY-OVERFLOW-DEFENSE — **Complete; hostile-value
   containment verified.**
3. JC052-MANAGE-SCHOOLS-JOBSITES-INTEGRATION.
4. JC052-MANAGE-SCHOOLS-JOBSITES-BROWSER-CERTIFICATION.
5. JC053-STEP2-INTEGRATION.
4. JC053-STEP1-BROWSER-CERTIFICATION.
5. JC054 Teacher Discovery Final Pass, followed by JC055, JC056, and the JC057 implementation capability audit.

JC053 shared UI authority is now durable at
`docs/job-center/jc053-wizard-design-system-v1.md`. Future wizard work must
reuse the Wizard Responsive Form Grid, Form Control with Trailing Icon,
Stepper, Bottom Navigation, Choice Card, Step 3 authoring, Benefits selector,
and incremental preview primitives defined there rather than restating or
forking shared patterns.

## 5. Current Blockers

- UX authority convergence remains incomplete across JC052–JC056; implementation
  audit and later implementation sequencing remain deferred.
- Employer mobile implementation is intentionally held until desktop acceptance.
- JC053 responsive acceptance remains open. Required evidence is a local
  Windows-visible PNG series captured through external
  `chrome-devtools-mcp@1.6.0` at CDP `http://127.0.0.1:9222` with
  `--allow-unrestricted-paths`, plus browser and human review. Intended rules
  retain the 250px brand/rail and full navbar through 1025px, then use compact
  210px brand/rail and a unified Resources control at 1024px and below.
- Exact Expired-to-Closed timing, archive semantics, retention policy,
  Duplicate versus Repost wording, notification behavior, and deeper analytics
  remain unresolved and must not be invented during implementation.
- No production deployment is established by the verified history.

Drive sync primary-code transitions: `0 / 10`
Last successful Drive sync: `Unknown — baseline established under PROCESS-GOV002 on cycle 260802014510.`
Ordinary ticket completion does not trigger a Drive Handoff update. The next
sync requires PREPARE HANDOFF, an explicit Engineering Director request, a
major milestone/phase transition, or the tenth primary-code transition.

## 6. Recently Adopted Governance Documents

- PROCESS-GOV002 — Google Drive Synchronization Cadence.

- JC053 Wizard Design System v1:
  `docs/job-center/jc053-wizard-design-system-v1.md` — canonical shared wizard
  shell, controls, navigation, choice-card, and responsive form-grid guidance.

## Canonical Chrome QA Recovery

If `http://127.0.0.1:9222/json/version` is unavailable, run the repository
launcher before reporting browser verification as blocked:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File '\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\launch-chrome-cdp-9222.ps1'
```

The launcher uses the installed Windows Chrome executable and the isolated
profile `C:\Main\Active\Projects\Teachers.Net\tmp\chrome-qa-profile`, never
the normal Chrome profile. It serves the JC053 workbench at
`http://127.0.0.1:8768/#wizard-authority-v1`, enables CDP on
`http://127.0.0.1:9222`, and polls `/json/version` with a bounded timeout.
External `chrome-devtools-mcp@1.6.0` must retain
`--browser-url=http://127.0.0.1:9222 --allow-unrestricted-paths
--no-usage-statistics`. After recovery, confirm MCP inspection and resume the
active ticket automatically. Stop only when this launcher itself fails after
its bounded attempt; do not substitute the built-in or obsolete WSL browser
bridges.

### Recovery troubleshooting notes

The verified recovery sequence is: test `/json/version`, inspect Windows
Chrome command lines without touching normal Chrome, invoke the launcher via
the `\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\...` UNC
path, then call external MCP `list_pages`. Do not interpret a WSL `curl`
timeout against Windows loopback as conclusive if MCP can inspect the browser.
Once MCP is connected, reload the workbench with cache bypass before taking
responsive evidence; otherwise an older CSS build can remain visible.

Before any authenticated mutation or browser acceptance, reconcile MCP with the
live Windows CDP target universe. Capture Windows `/json/list`, run MCP
`list_pages`, and confirm that the intended canonical target URL/session is
represented in both. Numeric MCP page labels do not need to equal CDP target
IDs; the URL/session correspondence must match. If MCP reports targets absent
from live `/json/list`, preflight fails: discard/reconnect only the stale MCP
attachment, reconnect it to `http://127.0.0.1:9222`, and repeat the bounded
reconciliation. Do not mutate application data until a harmless MCP snapshot
and evaluation succeed against the reconciled target.

### Mandatory MCP verification rule

All authenticated Job Center browser QA must use the Chrome DevTools MCP bridge
on the canonical runtime. Always begin with MCP page discovery and navigate or
reload the exact canonical review URL through MCP. Use MCP snapshots or script
evaluation for state, MCP console inspection for load errors, and MCP viewport
emulation/screenshots when the ticket requires visual or responsive evidence.

HTTP `200`, shell `curl`, PHP/JS lint, source inspection, or automated tests may
support the result but cannot replace authenticated browser evidence. If MCP
cannot access a usable authenticated page, invoke the canonical launcher above
and retry MCP once. If the bridge still fails, classify browser verification as
`UNAVAILABLE`, preserve the exact blocker, and continue only with the approved
fallback evidence ladder when it can safely establish the remaining engineering
objective. Never claim browser PASS from fallback evidence. Stop only when the
objective inherently requires rendered/authenticated observation, all relevant
fallbacks are unavailable, a distinct application defect is proven, or scope
would expand.

Job Center browser-QA status vocabulary is mandatory in reports:

- `Browser verification: PASS | PARTIAL | UNAVAILABLE`
- `Engineering diagnosis: PASS | FAIL | BLOCKED`
- `Human visual acceptance: PASS | PENDING | NOT REQUIRED`

Approved fallback evidence may include direct CDP against the same QA session,
browser network/console evidence, server logs or traces, authoritative DB or
service readback, repository tests, derivative/media persistence inspection,
and screenshots already produced by that canonical session. Do not substitute
another runtime, browser profile, route, worktree, or unauthenticated session.

- Canonical V1 Contract.
- Employer UX V1.
- Job Center Design System v1.
- Job Center UX Atlas v1.
- Visual Manifest and Approved Mockup Library.
- Job Center Shared Responsive Decisions v1.
- Responsive Advertising Strategy v1.
- Responsive Layout Geometry v1.
- V1 Authority Program.
- V1 Execution Plan.
- Job Finder Search Contract v1.
- Employer Workspace Flow Authority v1.
- Codex Fast Operations and verification-proportionality protocol.
- PROCESS-GOV001 — Canonical Review URL Discipline.

## 7. Recently Approved Product Decisions

- My Jobs is the Employer Operations workspace.
- The separate Dashboard direction is superseded for V1.
- All My Jobs and a specific School / Job Site are mutually exclusive views.
- Approved left navigation entries are All My Jobs, My Schools / Job Sites, Add
  School / Job Site, and Manage Schools / Job Sites.
- The School / Job Site column, Job Timeline terminology, fixed row height,
  pagination, rows-per-page, filter/sort separation, and one primary row action
  are required.
- Employer Operations desktop authority is approved as JC-050 and JC-051.
- Employer Operations mobile remains a provisional implementation target
  pending Browser Verification, not an Approved authority.
- The remaining V1 visual-authority render program is four groups: Employer
  Authoring, Employer Composite State Sheet, Saved Jobs, and Job Alerts.

## 8. Recently Approved Visual References

- JC-050 final - Employer Operations All My Jobs desktop authority, approved
  by DESIGN-AUTHORITY008; the DESIGN-AUTHORITY007 replacement candidate, v1.1,
  and v1.0 are superseded historical evidence.
- JC-051 v1.0 - Employer Operations Single School / Job Site desktop authority,
  approved by EMP-DOC004.
- JC-030 Mobile v1.0 - Job Detail Mobile Reading Experience authority, approved
  by DOC018.
- JC-030 Narrow Tablet v1.0 - Job Detail narrow portrait-tablet authority,
  approved by DOC017A.
- JC-015 Tablet/Mobile v1.0, JC-014 Tablet/Mobile v1.0, JC-011 Tablet/Mobile
  v1.0, JC-010 Tablet/Mobile v1.0, and JC-003/JC-004 drawer components remain
  approved Patch Mode references.

## 9. Active Design Authority

Job Center Design System v1 controls written visual and interaction rules. The
Visual Manifest controls artifact status. Only manifest entries marked Approved
may serve as visual implementation authority.

For the active Employer Operations workstream:

- JC-051A's approved Employer My Jobs Desktop Authority v1.0 governs All My Jobs desktop.
- JC-051 governs Single School / Job Site desktop.
- Employer Operations mobile contracted/expanded selector rasters are
  Responsive Candidate / Implementation Target / Pending Browser Verification.

Implementation must preserve the approved My Jobs workspace direction and must
not introduce UX changes, rename governed labels, or revive rejected Dashboard
concepts without a separate Engineering Director decision.

## 10. Immediate Convergence Priorities

1. Complete the JC052–JC056 UX authority convergence work.
2. Run the JC057 implementation capability audit after that design-first gate.
3. Generate JC058+ implementation sequencing from the audit; only then resume
   bounded implementation and acceptance work.

## 11. Verified Workflow and Authority Notes

- Browser QA uses only external `chrome-devtools-mcp` with the dedicated QA
  Chrome profile and manual `jobman` authentication; the built-in browser
  bridge is not used.
- Standard desktop verification is 1440 × 1000 and includes route 200,
  authenticated state, screenshot, DOM, console/page errors, overflow, clipped
  content, filter/sort, pagination, rows-per-page, and selector behavior.
- The lifecycle fixture matrix is the canonical QA matrix. It covers filters,
  filtered totals, pagination, state timelines, primary/overflow actions, and
  the hidden archived/approved-only invariants.
- Public Job Finder remote inclusion, Work Location terminology, and distance-sort
  exclusion are governed by `docs/job-center/job-finder-search-contract-v1.md`;
  this is a documentation contract and does not imply implementation completion.
- The current visual baseline uses the system stack rendered as Segoe UI in
  Windows Chrome, with regular and bold weight discipline (400/700).
- The current shell is a 1200px canvas: white navbar with approved legacy
  Teachers.Net logo, Job Center label, notification/account controls, 250px
  gray rail, workspace selector, outlined Post a Job CTA, and preserved
  inventory panel.
- Browser-verified implementation does not equal human visual acceptance;
  remaining design exploration includes Employer detail workspace, recruiter
  campaign orientation, performance dashboard, applicant workflow, rail
  refinement, promotion placement, summary strip, metrics, and typography
  fine-tuning.


## Do Not Reopen Without New Evidence

Do not reopen the approved 1200/250/950 desktop geometry, JC-051A authority
registration, Live/In Review terminology, Archived Jobs as separate navigation,
Post a Job wording, approved top-navigation sequence, removal of unsupported
school identity from the header, Manrope/12px metadata baseline, or the
authority-image versus deterministic-workbench relationship. Revisit only with
new implementation, browser, accessibility, responsive, or workflow evidence.

## Known Production Deltas

Production remains the behavioral baseline and has not been converted to this
authority. Known deltas include shell/card composition, rail width, navigation,
heading caret, CTA wording, organization identity removal, Archived Jobs,
terminology, expiration presentation, row actions, action alignment, table
rhythm, typography, metadata, compact footer, contrast, semantics, keyboard
behavior, and zoom acceptance. These are a bounded backlog, not one ticket.

## Deterministic Workbench Role

The retained deterministic My Jobs workbench under tmp/jc050-deterministic-mockup/
supports geometry, typography, long-content stress, lifecycle fixtures,
accessibility proofing, and component mapping. It is supporting evidence, not
the visual authority, and must not be deleted or promoted over JC-051A.

## Immediate Convergence Strategy

Next work is the JC052–JC056 design-first authority sequence, followed by the
JC057 capability audit and a generated JC058+ implementation plan. Responsive
derivation remains downstream of explicit desktop acceptance. Existing employer
workflows, services, authorization, lifecycle semantics, and data behavior
remain protected.
