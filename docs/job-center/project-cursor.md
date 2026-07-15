# Job Center Project Cursor

## Project State

Active Development

## Current Phase

Responsive Design governance is complete through RESP-LAYOUT002,
RESP-ADS002, and DOC017A's approval of JC-030 Narrow Tablet v1.0. Responsive
Implementation is active after JC030-IMP001 and JC030-IMP002; approved visual
authorities remain immutable Patch Mode references. ADR001 establishes that
JC-030 implementation replaces the legacy page composition while reusing
existing services and behavior.
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

The approved responsive visual authority set is complete. JC-030 Narrow Tablet
v1.0, JC-030 Mobile v1.0, JC-015 Mobile v1.0, JC-015 Tablet v1.0, JC-014 Mobile v1.0, JC-014 Tablet
v1.0, JC-011 Mobile v1.0, JC-011 Tablet v1.0, JC-010 Tablet v1.0, and JC-010
Mobile v1.0 are in Patch Mode. Desktop JC-010 v1.1, JC-011 v1.0, JC-014 v1.0,
and JC-015 v1.0 remain the product/content authorities; desktop authority for JC-030 remains
unchanged. JC-014 Mobile changes only the backdrop and modal layer over the
JC-010 Mobile page. RESP-DEC002 governs the JC-011 Mobile support-content
exception. JC-011 Mobile's
native-resolution limitation does not authorize reconstruction, upscaling, or
other derivative replacement as authority. JC-003 and JC-004 drawers are also
Patch Mode component authority only. JC-030 implementation convergence is the
active workstream; the remaining backlog must be taken only from JC030-IMP001
and verified against current code before each ticket.

## Current Reference Page/Flow

JC-010 first-touch discovery → JC-014 location selection or JC-015 browse
exploration → JC-011 search results. Search and Browse share the same results,
lifecycle, presentation, and application behavior.

## Current Primitive/Workstream

JC-030 approved-authority composition implementation under ADR001, reusing
existing services and behavior while replacing the legacy page composition,
with
JC-030 Narrow Tablet, JC-030 Mobile, JC-015 Mobile, JC-015 Tablet, JC-014 Mobile, JC-014 Tablet, JC-011 Mobile,
JC-011 Tablet, JC-010 Tablet, JC-010 Mobile, and JC-003/JC-004 drawer Patch
Mode visual authority.

## Next Executable Ticket

Unassigned — define the first bounded JC-030 approved-composition
implementation ticket under ADR001, with browser-based visual QA against the
approved authority set.

## Next Decision

Select the first bounded JC-030 approved-composition implementation item under
ADR001 after verifying the current code state; do not reopen responsive visual
authority.

## Required Google Drive Context

A new ChatGPT session reads only these by default:

1. Engineering Director Playbook v2
   - https://docs.google.com/document/d/1GMT6pOFlhxC3wo4pfx6sxbxjzanPZJduvetY2CD6mWQ
2. Job Center Engineering Handoff
   - https://docs.google.com/document/d/1foiIgRjBcQcKUbGRsHRuCaPDk0R7o2BCwuFmx96Z3AE

The Project Cursor, Canonical V1 Contract, Employer UX V1, Job Center Design
System v1, Visual Manifest, roadmap, and implementation docs are consulted only
when the ticket requires them. Job Center UX Atlas v1 is the concise product map
for screen purpose, relationships, and governance status.

## Open Risks

- JC-030 implementation has been audited (JC030-IMP001) and its canonical
  action group has been implemented (JC030-IMP002); remaining convergence work
  is governed by the verified IMP001 backlog.
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
- Production deployment, monitoring, rollback, and V1 release-candidate approval
  remain pending.

## Stop Boundary

Stop each approval or implementation ticket at its named boundary. JC-030
Mobile, JC-011 Mobile, JC-015 Mobile, JC-015 Tablet, JC-014 Mobile, JC-014 Tablet, JC-011
Tablet, JC-010 Tablet, JC-010 Mobile, and JC-003/JC-004 drawers are Patch Mode
and permit only separately approved tablet, mobile, or component deltas. Do not infer
underlying-page or other-screen visual approval, expand beyond the verified
JC030-IMP001 implementation backlog, import real jobs, mutate schema, add
provider integrations, or begin pilot/bulk loading without a separately
approved ticket.
