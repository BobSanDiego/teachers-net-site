# JC030-IMP001 — Browser Implementation Audit

**Status:** Read-only implementation audit

**Date:** 2026-07-15

## Evidence boundary

The public detail route is registered at `/jobs/{job_slug}/` and the approved
reference job route returns HTTP `200`. Findings compare the current renderer
and responsive stylesheet against the approved JC-030 Desktop, Mobile, and
Narrow Tablet authorities; the JC-030 Product Definition and UX Specification;
the Job Center Design System; Responsive Layout Geometry v1; and Responsive
Advertising Strategy v1.

Direct browser visual automation could not attach to the local WSL workspace.
Accordingly, this audit does not claim pixel-level browser comparison; visual
findings below are limited to rendered structure and governed CSS behavior.

## Prioritized implementation gaps

| Priority | Classification | Gap | Approved authority reference |
|---|---|---|---|
| P0 | Functional | The renderer outputs Apply in the hero, in How to Apply, and again in the sidebar. The approved JC-030 mobile and narrow-tablet authorities establish one primary Apply action immediately after job identity; the UX Specification requires one understandable primary conversion path. | JC-030 Mobile and Narrow Tablet authorities; UX Specification §5 |
| P0 | Functional | Share is absent from the rendered Job Detail action set. The approved JC-030 Mobile and Narrow Tablet authorities require the Apply / Save / Share hierarchy. | JC-030 Mobile and Narrow Tablet authorities |
| P0 | UX | Related Jobs is not rendered. The approved mobile and narrow-tablet authorities require Related Jobs after Employer and before advertisement. | JC-030 Mobile and Narrow Tablet authorities; Responsive Layout Geometry v1 §4 |
| P1 | Responsive | The detail layout retains a `300px` sidebar until `920px`, then collapses. Responsive Layout Geometry v1 prohibits a retained rail below `960px`; widths `921–959px` are therefore unsupported. | Responsive Layout Geometry v1 §§2–4; JC-030 Narrow Tablet authority |
| P1 | Responsive | Below the current collapse point, the sidebar still renders Job Details, Job Actions, and a medium-rectangle advertisement. The approved narrow-tablet authority is a single-column JC-030 presentation with no persistent rail and a `468×60` advertisement reservation. | JC-030 Narrow Tablet authority; Responsive Layout Geometry v1 §4; Responsive Advertising Strategy v1 §§3, 5 |
| P1 | Visual | The current external-application section includes a second primary action instead of the approved narrow-tablet explanatory How to Apply panel after the already-present action row. | JC-030 Narrow Tablet authority; JC-030 Product Definition — Supported Actions |
| P1 | UX | Employer content is reduced to a generic sentence and does not provide the approved employer-card treatment or the governed Related Jobs transition. | JC-030 Desktop, Mobile, and Narrow Tablet authorities; UX Specification §§4, 11 |
| P2 | Accessibility | Duplicate Apply controls create multiple equivalent primary destinations in one page flow, weakening the approved single-primary-action hierarchy for keyboard and assistive-technology users. | UX Specification §§5, 14; Job Center Design System §3.8 |
| P2 | Visual | The current desktop and collapsed layouts do not map their advertisement reservations to the approved device inventories: desktop needs `728×90` main flow plus `300×250` rail; narrow tablet needs `468×60` and no persistent rail; mobile retains only its approved `320×50` location. | Responsive Advertising Strategy v1 §§2–5; approved JC-030 Desktop, Mobile, and Narrow Tablet authorities |

## Smallest coherent implementation sequence

1. Establish one shared JC-030 content model that renders job identity, one
   Apply / Save / Share action group, narrative, Qualifications, How to Apply,
   Employer, Related Jobs, and advertisements without duplicating conversion
   controls.
2. Apply the approved layout classes: desktop retained rail at `≥1200px`, wide
   tablet retained rail only at `960–1199px`, narrow-tablet single column at
   `768–959px`, and mobile below `768px`.
3. Bind advertisement reservations to the governed intrinsic inventory for each
   class and preserve their approved placement sequence.
4. Perform browser visual QA against the three JC-030 approved rasters and
   validate keyboard focus, accessible action names, and the single-primary
   conversion hierarchy.

## Verification conclusion

Every finding is bounded to an approved JC-030 authority or the named governing
documents. This roadmap proposes no new product behavior, authority, or design
concept.
