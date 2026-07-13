# Job Center Project Cursor

## Project State

Active Development

## Current Phase

Shared responsive decisions are complete; JC-010 responsive visual authority
is the next governed workstream.

## Current Milestone

RESP-DEC001 resolves the shared responsive decisions required to interpret the
approved desktop suite without defining breakpoints or implementation. JC-010
tablet and mobile visual authority is next; screen-specific responsive deltas
remain separate until that shared baseline is governed.

## Current Focus

RESP-DEC001 governs shared responsive navigation, search, listing, support-flow,
advertising, Job Detail conversion-order, and modal behavior. Desktop authority
for JC-010 v1.1, JC-011, JC-014, JC-015, and JC-030 remains unchanged. The next
step is JC-010 tablet and mobile visual authority; no responsive implementation
is authorized.

## Current Reference Page/Flow

JC-010 first-touch discovery → JC-014 location selection or JC-015 browse
exploration → JC-011 search results. Search and Browse share the same results,
lifecycle, presentation, and application behavior.

## Current Primitive/Workstream

JC-010 shared responsive visual authority.

## Next Executable Ticket

Unassigned — create and govern the JC-010 tablet and mobile responsive visual
authority from the approved v1.1 desktop state and RESP-DEC001.

## Next Decision

Approve the minimum JC-010 tablet/mobile baseline without reopening desktop
authority or beginning implementation.

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
- Responsive decisions are complete, but responsive visual authority remains
  pending and does not inherit desktop approval.
- Implementation has not yet converged to the written product/design authority.
- A real-job pilot is required before any bulk loading.
- Core Terms CTJ004-CTJ006 commits remain ahead of `origin/main` in the local
  Profilaxes repository and require separate remote-parity verification.
- Production deployment, monitoring, rollback, and V1 release-candidate approval
  remain pending.

## Stop Boundary

Stop each approval or implementation ticket at its named boundary. Do not infer
visual approval, begin implementation convergence, import real jobs, mutate
schema, add provider integrations, or begin pilot/bulk loading without a
separately approved ticket.
