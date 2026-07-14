# Job Center Project Cursor

## Project State

Active Development

## Current Phase

DOC003 approves JC-010 Mobile responsive authority, and DOC006 approves Logged
Out and Logged In mobile navigation drawer components. Future mobile and drawer
work is Patch Mode; responsive implementation remains unauthorized.

## Current Milestone

RESP-DEC001 resolved the shared responsive decisions required to interpret the
approved desktop suite without defining breakpoints or implementation. DOC003
approves JC-010 Mobile v1.0 responsive authority within its visible boundary.
DOC005 corrects its Approved raster identity to
`docs/job-center/design/approved/job-center-responsive-jc010-mobile-02c-approved.png`,
the byte-identical controlled copy of the verified external 02c source.
DOC006 approves JC-003 and JC-004 as the shared mobile navigation drawer
components for JC-010, JC-011, JC-014, JC-015, and JC-030.

## Current Focus

JC-010 Mobile v1.0 is in Patch Mode. Desktop JC-010 v1.1 remains the
product/content authority; desktop authority for JC-011, JC-014, JC-015, and
JC-030 remains unchanged. JC-003 and JC-004 drawers are also Patch Mode
component authority only. No responsive implementation is authorized.

## Current Reference Page/Flow

JC-010 first-touch discovery → JC-014 location selection or JC-015 browse
exploration → JC-011 search results. Search and Browse share the same results,
lifecycle, presentation, and application behavior.

## Current Primitive/Workstream

JC-010 Mobile and JC-003/JC-004 drawer Patch Mode visual authority.

## Next Executable Ticket

Unassigned — address an Engineering Director-approved JC-010 Mobile or
JC-003/JC-004 drawer Patch Mode delta only if issued.

## Next Decision

Establish JC-010 tablet responsive visual authority without reopening desktop
or approved JC-010 Mobile authority, or beginning implementation.

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

- JC-030 implementation has not yet been audited or converged to its Approved
  v1.0 desktop visual authority.
- JC-010 Mobile v1.0 is approved, but tablet and other screen-specific
  responsive authority remain pending and do not inherit that approval.
- JC-003 and JC-004 approve only the mobile navigation drawer component; they
  do not establish underlying-page, responsive-layout, tablet, or implementation authority.
- Implementation has not yet converged to the written product/design authority.
- A real-job pilot is required before any bulk loading.
- Core Terms CTJ004-CTJ006 commits remain ahead of `origin/main` in the local
  Profilaxes repository and require separate remote-parity verification.
- Production deployment, monitoring, rollback, and V1 release-candidate approval
  remain pending.

## Stop Boundary

Stop each approval or implementation ticket at its named boundary. JC-010 Mobile
and JC-003/JC-004 drawers are Patch Mode and permit only separately approved
component or mobile deltas. Do not infer underlying-page, tablet, or other-screen
visual approval, begin implementation convergence, import real jobs, mutate
schema, add provider integrations, or begin pilot/bulk loading without a
separately approved ticket.
