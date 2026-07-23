# Job Center Engineering Handoff

Operational state only. Permanent product, design, architecture, and
implementation detail remain in the referenced repository documents.

## 1. Current Phase

Active Development - Bounded Implementation Convergence.

Responsive Design is complete for the approved authority set. Employer
Operations desktop implementation is the active convergence workstream. Approved
visual authorities remain Patch Mode references and must not be altered during
implementation.

## 2. Current Ticket

JC-051A is complete: `employer-my-jobs-desktop-authority-v1.0.png` is the approved
canonical Employer My Jobs Desktop Authority v1.0, sourced from
`jc-050-unicard-001.png` and superseding the prior JC-050 visual authorities. No
implementation ticket is active; the approval does not constitute
implementation or acceptance.

Stop boundary: bounded desktop implementation against the JC-051A
Employer My Jobs Desktop Authority v1.0 followed by
authenticated browser and human visual acceptance. Do not begin Employer
Operations mobile implementation until desktop acceptance is explicit.

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
controller, rail-icon, and table-control convergence. Nested Jobs plugin `main`
is clean and pushed at `51afd38`.

JC-051A superseded the prior JC-050 All My Jobs desktop raster with the
final approved Employer My Jobs Desktop Authority v1.0 while leaving JC-051 unchanged. The current approved
desktop pair is:

- JC-050 final All My Jobs (`jc-050-final-01a.png`, 1228 × 937, SHA-256
  `4404525b15f5b3640b000f9f6c936c73cba51f1626ff6905e55a9155dc3cb033`)
- JC-051 Single School / Job Site

The approved direction treats My Jobs as the employer operating workspace.
Dashboard concepts are absorbed into My Jobs as notifications, attention
states, workflow guidance, summary context, and School / Job Site scope. Do not
reintroduce a separate Employer Dashboard operating destination for V1.

## 4. Next Five Planned Tickets

1. Bounded desktop implementation against the DESIGN-AUTHORITY008 JC-050 final authority.
2. Human visual acceptance - authenticated All My Jobs against the JC-050 final authority.
3. Bounded Employer Operations mobile implementation, only after desktop acceptance.
4. Employer Authoring visual authority.
5. Employer Composite State Sheet.

## 5. Current Blockers

- JC-050 final-authority desktop implementation has not started; browser and human visual
  acceptance remain required after implementation.
- Employer mobile implementation is intentionally held until desktop acceptance.
- Exact Expired-to-Closed timing, archive semantics, retention policy,
  Duplicate versus Repost wording, notification behavior, and deeper analytics
  remain unresolved and must not be invented during implementation.
- No production deployment is established by the verified history.

## 6. Recently Adopted Governance Documents

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
- Codex Fast Operations and verification-proportionality protocol.

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

## 10. Immediate Engineering Priorities

1. Implement the approved JC-051A Employer My Jobs Desktop Authority v1.0 through a separate
   bounded ticket.
2. Complete browser and human visual acceptance against the JC-050 final authority.
3. Only after acceptance, decide whether to begin the provisional mobile work.

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
