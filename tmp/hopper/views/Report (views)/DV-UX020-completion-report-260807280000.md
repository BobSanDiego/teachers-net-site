# DV-UX020 — Completion Report

## Status

DV-UX020 COMPLETE and ready for engineer acceptance. View Manager work was not
started.

## Implementation

Top-level Library rows now use four stable structural slots:

`bulk + / − | disclosure | selection/control | term label`

The bulk slot is reserved even when hidden, preventing layout shift. The
controller is visible only when the top-level branch and every expandable
descendant branch are open. It shows `+` unless all available/unrepresented
descendants are checked, and `−` only when all are checked. Represented terms
are excluded. The existing tooltip remains `Check/uncheck all descendants`.

No Current View, shuttle, lifecycle, schema, repository, resolver, Core Terms,
or Jobs behavior was changed.

## Browser verification

Canonical URL:
https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

Authenticated cache-bypassed QA passed:

- Bulk control is physically left of disclosure: bulk 27px, disclosure 52px,
  selection slot 77px, label 102px.
- Collapsed and partially expanded top-level branches hide the controller.
- Expand All fully opens all branches and reveals `+`.
- 20 available descendants were selected by `+`; the control changed to `−`.
- `−` cleared all 20 selections and returned the control to `+`.
- Top-level UUID was absent from selected payload values.
- Reserved bulk slot prevents geometry shifts when visibility changes.
- Existing disclosure state, checkbox alignment, and Current View geometry
  remained intact.
- Console: no warnings or errors.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Commit: `92d0439` (`Reposition Views top-level bulk controls`)
- Push: successful
- Root documentation commit: pending in this cycle

