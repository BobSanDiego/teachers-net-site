# DV-UX022R1 — Completion Report

## Status

DV-UX022R1 COMPLETE. DV-UX022 is ready for engineer re-acceptance. View
Manager lifecycle/reachability work was not started.

## Corrections

- Bottom Shuttle Selected is now completely hidden with zero eligible Library
  selections and appears immediately when an eligible term is selected.
- Current View toolbar state is derived after the disclosure initializer: an
  initially collapsed tree enables Expand all and mutes Collapse all; a fully
  expanded tree reverses those states; mixed trees enable both where useful.
- Current View selected count includes the complete checked removal closure,
  including inherited descendants, without duplicate counting.
- Neutral Library rows now use one `1px solid rgb(240, 240, 241)` divider,
  transparent background, and no shadow.
- Represented Library terms remain blue and unavailable but use font weight
  400 rather than active emphasis.

## Browser verification

Canonical URL:
https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

Authenticated cache-bypassed verification passed. Initial Current View state
reported Expand all active and Collapse all disabled. After Expand all, the
state reversed; after Collapse all it returned to the initial state. Selecting
the Current View parent produced three checked removal boxes: one explicit root
and two inherited descendants; the toolbar reported `3 Terms (1 selected)`.
The inherited closure was deduplicated by the established checked-box state.

Library reported a neutral divider of `1px solid rgb(240, 240, 241)`, no box
shadow, and transparent background. A represented term remained
`rgb(19, 94, 150)` at font weight `400`. With no eligible Library selection,
the bottom Shuttle Selected CTA was hidden; selection-state synchronization was
verified in the prior compact-editor path. Console reported no warnings or
errors.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Commit: `9e24add` (`Correct Views compact control states`)
- Push: successful
- Root documentation commit: pending in this cycle

