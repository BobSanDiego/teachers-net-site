# Job Center Engineering Handoff

Operational state only. Permanent product, design, architecture, and
implementation detail remain in the referenced repository documents.

## 1. Current Phase

Active Development — DOC003 approves JC-010 Mobile responsive authority, and
DOC006 approves Logged Out and Logged In mobile navigation drawer components.
Future mobile and drawer work is Patch Mode; responsive implementation remains
unauthorized.

## 2. Current Ticket

None. DOC003 approved the exact raster, with its identity corrected by DOC005 to
`docs/job-center/design/approved/job-center-responsive-jc010-mobile-02c-approved.png`.
It is the canonical JC-010 Mobile Responsive Authority within its visible
boundary. DOC006 separately approves the canonical Logged Out and Logged In
mobile navigation drawer components. Do not begin responsive implementation.

## 3. Last Completed Milestone

DOC003 approved JC-010 Mobile v1.0; DOC005 corrects the Approved raster to the
verified external 02c source and its byte-identical controlled-library copy.
Desktop JC-010 v1.1 remains the product/content authority; mobile authority and
responsive implementation remain separate. DOC006 then approved the bounded
Logged Out and Logged In mobile navigation drawer components.

## 4. Next Five Planned Tickets

1. Unassigned — Address an Engineering Director-approved JC-010 Mobile or mobile drawer Patch Mode delta only if issued.
2. Unassigned — Establish JC-010 tablet responsive visual authority.
3. Unassigned — Govern the minimum JC-011, JC-014, and JC-030 mobile deltas.
4. Unassigned — Audit responsive implementation convergence after all required authority is approved.
5. Unassigned — Resume the next approved implementation or UX workstream.

## 5. Current Blockers

- JC-030 implementation has not yet been audited or converged to its Approved
  v1.0 desktop visual authority.
- Tablet and other screen-specific responsive authority remain pending and do
  not inherit desktop or JC-010 Mobile approval.
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
- JC-015 v1.0 — Browse Reveal.
- JC-011 v1.0 — Job Finder State 2 (Search Results).
- JC-030 v1.0 — Job Detail (Desktop).
- JC-010 Mobile v1.0 — Job Finder State 1 mobile responsive authority, approved
  by DOC003 and corrected by DOC005 to exact controlled raster `02c`.
- JC-003 v1.0 — Logged Out mobile navigation drawer component, approved by DOC006.
- JC-004 v1.0 — Logged In mobile navigation drawer component, approved by DOC006.

## 9. Active Design Authority

Job Center Design System v1 controls written visual/interaction rules. The
visual manifest controls artifact status. Only manifest entries marked Approved
may serve as visual implementation authority. The four Approved references in
Search & Discovery Interaction Suite v1 jointly govern the canonical desktop
search/discovery journey within their individual approval scopes; JC-010 desktop
is currently v1.1, and JC-010 Mobile v1.0 governs only the exact approved
mobile presentation in Patch Mode. JC-003 and JC-004 govern only their shared
mobile navigation drawer components in Patch Mode across JC-010, JC-011,
JC-014, JC-015, and JC-030 unless a screen has an Approved exception. JC-030 v1.0
governs desktop Job Detail together with its Product Definition, UX
Specification, AUDIT007 reconciliation, and Design System; its unavailable
editable source does not authorize reinterpretation.

## 10. Immediate Engineering Priorities

1. Preserve JC-010 Mobile v1.0 and JC-003/JC-004 drawers in Patch Mode; do not
   reopen desktop authority or begin responsive implementation.
2. Establish JC-010 tablet responsive visual authority from RESP-DEC001 without
   reopening desktop governance.
3. Keep responsive implementation behind explicit approval and audit.
