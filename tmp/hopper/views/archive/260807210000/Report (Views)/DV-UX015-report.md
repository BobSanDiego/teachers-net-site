# DV-UX015 — Unify Library and Current View Tree Layout Mechanisms

Status: COMPLETE — BROWSER VERIFIED

## Implementation

Updated `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.
Current View now uses the same structural three-column model as the Library:

1. disclosure control or spacer;
2. checkbox/removal control or spacer;
3. term label.

Both panels use 18px columns, 7px gaps, and an 18px depth increment. Current
View no longer uses flex layout or inline depth margins. Its removal-root,
inherited-descendant, aggregate toolbar, and DV-FIX003 ancestor semantics were
preserved.

## Browser verification

Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

The authorized version-17 fixture remained intact:

- Grade Level → Early Childhood → Early Learners
- Grade Level → Elementary → Grade 1

Current View measured positions after the change:

| Depth | Disclosure | Checkbox | Label | Depth increment |
| ---: | ---: | ---: | ---: | ---: |
| 0 | 27px | 51px | 77px | — |
| 1 | 45px | 69px | 95px | 18px |
| 2 | 63px | 87px | 113px | 18px |

Library measured positions use the same column progression:

| Depth | Disclosure | Selection slot | Label | Depth increment |
| ---: | ---: | ---: | ---: | ---: |
| 0 | 39px | 64px | 89px | — |
| 1 | 57px | 57px spacer | 107px | 18px |
| 2 | 75px spacer | 75px spacer | 125px | 18px |

Absolute panel origins differ because the panels have different containers;
the structural progression and column geometry are identical.

Verified:

- representative L1/L2/L3 rows in both panels;
- disclosure, checkbox/spacer, and label alignment by depth;
- leaf rows retain both structural slots;
- Current View collapse then re-expand restored all five rows;
- Library Grade Level expansion remained independent;
- mouse disclosure click left `document.activeElement` as `BODY`;
- `:focus` and `:focus-visible` were false after mouse activation;
- outline remained non-rendering and border/box-shadow remained none;
- console contained no errors or warnings;
- DDEV runtime and mounted Profilaxes source remained authoritative.

Chrome MCP screenshot capture was attempted for the parity evidence, but
`Page.captureScreenshot` timed out. The diagnostic measurements and browser
state evidence are preserved; no screenshot file is claimed as locally
available.

## Scope boundaries

No schema, repository, resolver, Core Terms, Jobs, View Manager, lifecycle,
Library semantics, or Current View removal semantics were changed.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `2f8f8aa`
- Push: successful
- Root documentation commit: pending cycle publication

Cross-panel alignment is resolved for the verified desktop runtime. The next
work must remain outside this ticket’s stop boundary.
