# Job Center Engineering Handoff

Operational state only. Permanent product, design, architecture, and
implementation detail remain in the referenced repository documents.

## 1. Current Phase

Active Development — Current phase is Bounded Implementation Convergence.
Responsive Design is complete for the approved authority set. Approved visual
authorities remain Patch Mode references.
DOC018 approves JC-030 Mobile Reading Experience authority.
DOC016 approves JC-015 Mobile responsive authority. DOC015
approves JC-015 Tablet responsive authority. DOC014
approves JC-014 Mobile responsive authority. DOC013 approves JC-014 Tablet
responsive authority. DOC012 approves JC-011 Mobile
responsive authority. DOC011 approves JC-011 Tablet responsive authority. DOC008
approves JC-010 Tablet responsive authority. DOC003 approves JC-010 Mobile
responsive authority, and DOC006 approves Logged Out and Logged In mobile
navigation drawer components. Future tablet, mobile, and drawer work is Patch
Mode; active implementation work must preserve those authorities.

## 2. Current Ticket

JC-030 approved-authority composition implementation under ADR001. ADR001 is
retrospective codification derived from the Responsive Authority Program; it
does not claim to have governed earlier work. The legacy
Job Detail page is not the implementation target; the page composition will be
replaced while existing route, services, repositories, business logic,
authentication, engagement, formatting, responsive, and advertisement
primitives are reused. DOC017A approved the exact narrow-tablet raster
`docs/job-center/design/approved/jc-030-job-detail-narrow-tablet-v1.0.png`
as JC-030 Narrow Tablet v1.0 from the `917 × 1716` source
`jc030-narrow-tablet-nt002-candidate.png`; future JC-030 Narrow Tablet work is
Patch Mode. JC030-IMP001 completed the browser implementation audit, and
JC030-IMP002 completed the canonical Apply / Save / Share action group in the
Jobs plugin. ADR001 supersedes the assumption that the legacy page is a
convergence baseline. Remaining implementation work must be issued as bounded
approved-composition tickets and verified against current code before each
ticket. JC030-IMP100 implemented the approved composition using reusable
existing infrastructure. The current gate is browser, accessibility, and human
visual verification; JC-030 is not yet converged. DOC018 approved the exact mobile raster
`docs/job-center/design/approved/jc-030-job-detail-mobile-v1.0.png` as
JC-030 Mobile v1.0 from the 853 × 1857 source
`jc030-mobile-reading-experience-m008-candidate.png`; future JC-030 Mobile
work is Patch Mode. Its recorded remaining typography, chip-row, and
advertisement-container refinements are implementation guidance only. DOC016 approved the exact mobile raster
`docs/job-center/design/approved/jc-015-browse-reveal-mobile-v1.0.png`
as JC-015 Mobile v1.0. DOC015 approved the exact portrait-tablet raster
`docs/job-center/design/approved/jc-015-browse-reveal-tablet-v1.0.png`
as JC-015 Tablet v1.0. DOC014 approved the exact mobile overlay raster
`docs/job-center/design/approved/jc-014-location-selection-modal-mobile-v1.0.png`
as JC-014 Mobile v1.0. DOC013 approved the exact portrait-tablet raster
`docs/job-center/design/approved/jc-014-location-selection-modal-tablet-v1.0.png`
as JC-014 Tablet v1.0. DOC012 approved the exact mobile raster
`docs/job-center/design/approved/jc-011-job-finder-state-2-mobile-v1.0.png`
as JC-011 Mobile v1.0, with RESP-DEC002 governing its support-content
exception. Its 360 × 975 native resolution is an accepted provenance limitation
and does not authorize derivative replacement. DOC011 approved the exact
portrait-tablet raster
`docs/job-center/design/approved/jc-011-job-finder-state-2-tablet-v1.0.png`
as JC-011 Tablet v1.0. DOC008 approved the exact portrait-tablet raster
`docs/job-center/design/approved/jc-010-job-finder-state-1-tablet-v1.0.png`
as JC-010 Tablet v1.0. DOC003 approved the exact mobile raster, with its
identity corrected by DOC005 to
`docs/job-center/design/approved/job-center-responsive-jc010-mobile-02c-approved.png`.
They are the canonical JC-015 Mobile, JC-015 Tablet, JC-014 Mobile, JC-014
Tablet, JC-011 Mobile, JC-011 Tablet, JC-010 Tablet, and JC-010 Mobile
Responsive Authorities within their visible boundaries. DOC006 separately approves the canonical
Logged Out and Logged In mobile navigation drawer components. Active
implementation must preserve these authorities.

## 3. Last Completed Milestone

DOC022 reconciled the Employer product model without changing the authority
families or render program. Employer Operations is a hybrid authenticated
workspace inside the standard Teachers.Net shell. Personas are descriptive;
memberships and granted capabilities determine tools. Claim is contextual
acquisition, while Add My School / Add Organization is the user-facing
new-organization path. Employer identity, profile, membership, posting account,
and source/provenance remain distinct. Progressive Completion governs employer
creation and location guidance.

DOC020R reconciled the forward phase-gate model and continuity governance.
RESP-LAYOUT002 established Responsive Layout Geometry v1 as the canonical
responsive layout authority, and RESP-ADS002 established Responsive Advertising
Strategy v1 as the canonical responsive advertisement authority. DOC017A
completed the primary responsive authority set by approving JC-030 Narrow
Tablet v1.0 and its byte-identical controlled-library copy. DOC018 approved JC-030 Mobile v1.0 and its byte-identical controlled-library
copy. DOC003 approved JC-010 Mobile v1.0; DOC005 corrects the Approved raster to the
verified external 02c source and its byte-identical controlled-library copy.
Desktop JC-010 v1.1 remains the product/content authority; responsive
implementation must preserve the separate presentation authorities. DOC006 then approved the bounded
Logged Out and Logged In mobile navigation drawer components. DOC008 then
approved the bounded JC-010 Tablet v1.0 authority and its byte-identical
controlled-library copy. DOC011 then approved the bounded JC-011 Tablet v1.0
authority and its byte-identical controlled-library copy. RESP-DEC002 resolved
the JC-011 Mobile support-content exception, and DOC012 then approved the
bounded JC-011 Mobile v1.0 authority and its byte-identical controlled-library
copy. DOC013 then approved the bounded JC-014 Tablet v1.0 authority and its
byte-identical controlled-library copy. DOC014 then approved the bounded JC-014
Mobile v1.0 localized overlay authority and its byte-identical
controlled-library copy. DOC015 then approved the bounded JC-015 Tablet v1.0
authority and its byte-identical controlled-library copy. DOC016 then approved
the bounded JC-015 Mobile v1.0 authority and its byte-identical
controlled-library copy.

## 4. Next Five Planned Tickets

1. JC030-IMP100 gate — browser, accessibility, and human visual verification.
2. Public and Employer V1 Release-Candidate Audits after JC-030 acceptance.
3. Real-Job Pilot readiness.
4. Operational Launch Readiness.
5. Explicit V1 Acceptance.

## 5. Current Blockers

- JC-030 browser, accessibility, and human visual acceptance remains incomplete
  after JC030-IMP100.
- Responsive implementation is active and must preserve the complete approved
  authority set, including JC-030 Narrow Tablet v1.0.
- No production deployment is established by the verified history.
- Release-candidate status still requires implementation convergence, real-job
  pilot evidence, operational launch planning, and explicit acceptance.

## 6. Recently Adopted Governance Documents

- Canonical V1 Contract.
- Employer UX V1.
- Job Center Design System v1.
- Approved Mockup Library and Manifest (D003).
- Engineering Handoff v2 procedure and template (DOC001).
- Search & Discovery Interaction Suite v1 milestone (DOC002).
- Job Center UX Atlas v1 (D004).
- JC-030 Job Detail Product Definition (DOC005).
- JC-030 Job Detail UX Specification (DOC006).
- Job Center Shared Responsive Decisions v1 (RESP-DEC001).
- Responsive Advertising Strategy v1 (RESP-ADS002).
- Responsive Layout Geometry v1 (RESP-LAYOUT002).

## 8. Current Gate and Authorization

Implementation Readiness Decision is a forward gate established by DOC020R; it
was not inferred for earlier work. For the bounded JC-030 workstream, DOC020R
prospectively authorizes continued implementation convergence under ADR001,
subject to browser, accessibility, and human visual verification. Approval does
not imply implementation, convergence, release-candidate acceptance, or
production deployment.

## 9. Repository Ownership

- Root `teachers-net-site`: governance, roadmap, audits, architectural
  decisions, approved authorities, and continuity documents.
- Nested `tnet-jobs`: Job Center implementation.

After JC-030 acceptance, return to the public and employer V1
release-candidate roadmap. Do not schedule UX Atlas placeholders automatically
or let the JC-030 audit backlog replace the broader roadmap.

## 7. Recently Approved Product Decisions

- Employer-posted and imported jobs use one canonical public job model.
- Public search is local-database-only; browser location remains request-scoped
  and private.
- Review and Preview, Renew and Duplicate, and Close and Archive remain distinct.
- Dashboard summarizes; My Jobs manages.
- Interaction-state artifacts inherit an approved page state and modify only
  the minimum interface necessary to document a single user interaction.
- The Job Center progressively reveals capability as the user demonstrates
  intent.

## 8. Recently Approved Visual References

- JC-010 v1.1 — Job Finder State 1 (Logged-Out First Touch), with the
  reconciled logged-out right rail approved by DESIGN009.
- JC-014 v1.0 — Location Selection Modal.
- JC-014 Tablet v1.0 — Location Selection Modal portrait-tablet responsive
  authority, approved by DOC013 to exact controlled raster `R002`.
- JC-014 Mobile v1.0 — Location Selection Modal mobile responsive authority,
  approved by DOC014 to exact controlled overlay raster `R003`.
- JC-015 v1.0 — Browse Reveal.
- JC-015 Tablet v1.0 — Browse Reveal portrait-tablet responsive authority,
  approved by DOC015 to exact controlled cleaned raster `R003`.
- JC-015 Mobile v1.0 — Browse Reveal mobile responsive authority, approved by
  DOC016 to exact controlled raster `R002`.
- JC-011 v1.0 — Job Finder State 2 (Search Results).
- JC-030 v1.0 — Job Detail desktop product/content authority and Mobile Reading
  Experience authority, approved by DOC018 to exact controlled raster `M008`.
- JC-030 Narrow Tablet v1.0 — Job Detail narrow portrait-tablet authority,
  approved by DOC017A to exact controlled raster `NT002`.
- JC-010 Mobile v1.0 — Job Finder State 1 mobile responsive authority, approved
  by DOC003 and corrected by DOC005 to exact controlled raster `02c`.
- JC-010 Tablet v1.0 — Job Finder State 1 portrait-tablet responsive authority,
  approved by DOC008 to exact controlled raster `03d`.
- JC-011 Tablet v1.0 — Job Finder State 2 portrait-tablet responsive authority,
  approved by DOC011 to exact controlled raster `R003`.
- JC-011 Mobile v1.0 — Job Finder State 2 mobile responsive authority, approved
  by DOC012 to exact controlled 360 × 975 raster `mobile-expanded-mobileads-01a`.
- JC-003 v1.0 — Logged Out mobile navigation drawer component, approved by DOC006.
- JC-004 v1.0 — Logged In mobile navigation drawer component, approved by DOC006.

## 9. Active Design Authority

Job Center Design System v1 controls written visual/interaction rules. The
visual manifest controls artifact status. Only manifest entries marked Approved
may serve as visual implementation authority. The four Approved references in
Search & Discovery Interaction Suite v1 jointly govern the canonical desktop
search/discovery journey within their individual approval scopes; JC-010 desktop
is currently v1.1, JC-010 Tablet v1.0, JC-011 Tablet v1.0, JC-014 Tablet
v1.0, and JC-015 Tablet v1.0 govern only their exact approved portrait-tablet
presentations in Patch Mode, and JC-010 Mobile v1.0, JC-011 Mobile v1.0,
JC-014 Mobile v1.0, and JC-015 Mobile v1.0 govern only their exact approved
mobile presentations in Patch Mode. JC-014 Mobile changes only the backdrop and modal layer over the
JC-010 Mobile page.
RESP-DEC002 governs the JC-011 Mobile
support-content exception. JC-011 Mobile's native-resolution limitation does
not authorize reconstruction, upscaling, or other derivative replacement as
authority. JC-003 and JC-004 govern only their shared mobile navigation drawer
components in Patch Mode across JC-010, JC-011, JC-014, JC-015, and JC-030
unless a screen has an Approved exception. JC-030 desktop v1.0 governs Job
Detail product/content truth together with its Product Definition, UX
Specification, AUDIT007 reconciliation, and Design System; its unavailable
editable source does not authorize reinterpretation. JC-030 Mobile v1.0 governs
the bounded Mobile Reading Experience in Patch Mode; its remaining typography,
chip-row, and advertisement-container refinements are implementation guidance
only. Responsive Layout Geometry v1 governs breakpoint classes, retained-rail
eligibility, collapse behavior, and reading widths; Responsive Advertising
Strategy v1 governs responsive advertising inventory and reservations.
JC-030 Narrow Tablet v1.0 governs the bounded `768–959px` single-column
presentation in Patch Mode.

## 10. Immediate Engineering Priorities

1. Continue JC-030 implementation convergence from the verified JC030-IMP001
   backlog; do not reopen desktop or responsive visual authority.
2. Preserve JC-030 Mobile v1.0, JC-015 Mobile v1.0, JC-015 Tablet v1.0, JC-014 Mobile v1.0, JC-014 Tablet v1.0, JC-011 Mobile v1.0, JC-011 Tablet v1.0, JC-010 Tablet v1.0, JC-010 Mobile v1.0, and JC-003/JC-004 drawers in Patch Mode.
3. Preserve all approved responsive authorities in Patch Mode and do not infer
   unapproved screen or breakpoint behavior.
