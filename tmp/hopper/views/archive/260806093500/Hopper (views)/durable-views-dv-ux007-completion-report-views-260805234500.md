# DV-UX007 — Views Authoring Flow Rebuild

## Outcome

The existing Views authoring surface now presents the approved dual-tree
composition workflow: create a View, enter a draft workspace, select from the
read-only Core Terms Library, shuttle selected terms into the Current View,
arrange the draft, and continue through save, preview, validation, and publish.

## Implementation

- Reframed the draft workspace as `Compose View` with the approved workflow
  language.
- Changed the batch action to `Add selected to View →`.
- Removed active per-row `Add to Draft` controls, the manual-entry path, the
  obsolete presentation-container form, and the `include descendants` control
  from the authoring presentation.
- Added parent/descendant selection scope behavior with indeterminate parent
  state while retaining canonical UUID submission to the existing repository.
- Preserved schema, repository, resolver, draft lifecycle, preview,
  validation, publication, and Jobs integration.

## Verification

- PHP lint: passed.
- Git diff check: passed.
- Authenticated browser URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views
- Draft editor URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=13
- Browser showed the Create View screen and the Compose View dual-tree editor.
- Selection scope DOM test passed: branch selection, parent-only reduction,
  and clearing were exercised without submitting or changing persisted data.
- Preview link remained available; existing draft validation remained valid.
- Jobs employer form remained open in the authenticated browser session at
  https://teachers-net.ddev.site/jobs/employer/new/.
- No console errors were observed during the inspected navigation.

## Git

Profilaxes branch `agent/durable-views-dv003-persistence`, commit `23c703a`,
pushed to GitHub. No schema or repository files changed.

## Remaining gaps

This ticket does not add Save As, cloning, version history, virtual nodes,
repeated placement, templates, inheritance, import/export, or consumer-specific
projection. Those remain separately authorized future work.
