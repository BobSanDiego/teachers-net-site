# DV-UX014 Completion Report

Status: COMPLETE
Date: 2026-08-07

## Outcome

The left Core Terms Library now has deterministic collapsed initial state,
working global collapse behavior, fixed canonical generation alignment, and
clear but non-shifting top-level bulk controls.

## Implemented

- Fresh editor loads begin with only Grade Level, Subject Area, and Location
  visible.
- Expand all opens the complete recursive tree.
- Collapse all closes every descendant branch.
- Fixed the CSS precedence issue that allowed hidden rows to remain displayed.
- Reserved disclosure, bulk-control, and label columns remain stable regardless
  of control presence or represented state.
- Increased `+ / −` visual weight without changing geometry.
- Removed mouse-click focus outline while retaining restrained `:focus-visible`
  keyboard focus treatment.
- Preserved DV-UX013 bulk-selection semantics and selection state through
  disclosure changes.

## Verification

Canonical review URL:
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`

Authenticated browser QA confirmed:

- fresh-load collapsed state: 3 visible top-level rows;
- Expand all: 100 visible tree rows;
- Collapse all: 3 visible rows again;
- selected descendant count preserved through Expand/Collapse;
- fixed L1/L2/L3 alignment;
- `+` and `−` state behavior preserved;
- mouse focus outline absent and no layout shift observed;
- no console warnings or errors.

PHP lint and `git diff --check` passed. View Manager, Current View,
persistence, schema, repository, resolver, UUID, and Jobs behavior were not
changed.

## Git

Profilaxes branch: `agent/durable-views-dv003-persistence`

Profilaxes commit: `4c56e20` — pushed successfully.

Root documentation and cycle artifacts are recorded in the completion cycle.

## Evidence

- `DV-UX014-fresh-collapsed.png`
- `DV-UX014-expanded-l3.png`
- `DV-UX014-minus.png`
- `DV-UX014-collapsed-after-control.png`

Human DV-UX014 acceptance may resume.
