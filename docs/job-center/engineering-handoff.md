# Job Center Engineering Handoff

Operational state only. Permanent product, design, architecture, and
implementation detail remain in the referenced repository documents.

## 1. Current Phase

Active Development — DOC016 approves JC-015 Mobile responsive authority. DOC015
approves JC-015 Tablet responsive authority. DOC014
approves JC-014 Mobile responsive authority. DOC013 approves JC-014 Tablet
responsive authority. DOC012 approves JC-011 Mobile
responsive authority. DOC011 approves JC-011 Tablet responsive authority. DOC008
approves JC-010 Tablet responsive authority. DOC003 approves JC-010 Mobile
responsive authority, and DOC006 approves Logged Out and Logged In mobile
navigation drawer components. Future tablet, mobile, and drawer work is Patch
Mode; responsive implementation remains unauthorized.

## 2. Current Ticket

None. DOC016 approved the exact mobile raster
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
Logged Out and Logged In mobile navigation drawer components. Do not begin
responsive implementation.

## 3. Last Completed Milestone

DOC003 approved JC-010 Mobile v1.0; DOC005 corrects the Approved raster to the
verified external 02c source and its byte-identical controlled-library copy.
Desktop JC-010 v1.1 remains the product/content authority; mobile authority and
responsive implementation remain separate. DOC006 then approved the bounded
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

1. Unassigned — Address an Engineering Director-approved JC-015 Mobile, JC-015 Tablet, JC-014 Mobile, JC-014 Tablet, JC-011 Mobile, JC-011 Tablet, JC-010 Tablet, JC-010 Mobile, or mobile drawer Patch Mode delta only if issued.
2. Unassigned — Govern the minimum JC-030 mobile deltas.
3. Unassigned — Authorize responsive implementation and browser-based visual QA against the approved JC-010 authorities.
4. Unassigned — Audit responsive implementation convergence after all required authority is approved.
5. Unassigned — Resume the next approved implementation or UX workstream.

## 5. Current Blockers

- JC-030 implementation has not yet been audited or converged to its Approved
  v1.0 desktop visual authority.
- Other screen-specific responsive authority remains pending and does not
  inherit desktop, JC-010 Mobile, JC-010 Tablet, JC-011 Tablet, JC-011 Mobile,
  JC-014 Tablet, JC-014 Mobile, JC-015 Tablet, or JC-015 Mobile approval.
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
- JC-030 v1.0 — Job Detail (Desktop).
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
unless a screen has an Approved exception. JC-030 v1.0
governs desktop Job Detail together with its Product Definition, UX
Specification, AUDIT007 reconciliation, and Design System; its unavailable
editable source does not authorize reinterpretation.

## 10. Immediate Engineering Priorities

1. Preserve JC-015 Mobile v1.0, JC-015 Tablet v1.0, JC-014 Mobile v1.0, JC-014 Tablet v1.0, JC-011 Mobile v1.0, JC-011 Tablet v1.0, JC-010 Tablet v1.0, JC-010 Mobile v1.0, and JC-003/JC-004 drawers
   in Patch Mode; do not reopen desktop authority or begin responsive implementation.
2. Keep responsive implementation behind explicit approval and audit.
3. Govern remaining screen-specific responsive deltas without inferring their
   authority from JC-010.
