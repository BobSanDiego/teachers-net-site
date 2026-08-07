# DV-UX018 — Stabilize Contextual Controls and Viewport Behavior

Status: COMPLETE — BROWSER VERIFIED

## Implementation

Updated `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.

- Added Current View contextual synchronization for empty, selected, expanded,
  and collapsed states.
- Expand/Collapse All now reflect actual current-branch state and are hidden
  when no meaningful action exists.
- Clear Selection and Remove Selected are hidden/disabled without pending
  removal selection and become active only after explicit/inherited selection.
- Clear Selection immediately returns both controls to unavailable state.
- Removed the confirmation prompt from Remove Selected.
- Preserved Remove All confirmation and destructive semantics.
- Preserved scroll position across Collapse All, Remove Selected, and Remove All
  redirects using session storage.

No schema, repository, resolver, Jobs, lifecycle, bulk-selection, or tree
parentage contract changed.

## Exact prior defects

- Context controls were rendered statically and did not synchronize with empty
  or selection state.
- Remove Selected inherited the toolbar’s confirmation listener, producing an
  unnecessary prompt.
- Redirecting removal actions had no scroll restoration.
- Global current-tree controls were not reliably scoped to Current View.

## Browser verification

Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

The disposable local version-17 fixture was restored with the existing
repository service as needed, then exercised and cleaned through approved
draft-only operations.

- Empty View: five contextual controls hidden; no console errors.
- Current View Collapse All: four descendants hidden; Expand All available,
  Collapse All hidden; scroll remained 500px.
- Current View Expand All: all five rows restored; Expand All hidden,
  Collapse All available.
- Removal selection: selecting the Grade Level root activated Clear Selection
  and Remove Selected and inherited all descendants.
- Clear Selection: immediately hid/disabled Clear Selection and Remove
  Selected.
- Remove Selected: executed without a dialog; scroll moved only from 450px to
  approximately 442px after redirect; resulting View was empty.
- Remove All: existing confirmation remained; scroll moved only from 500px to
  approximately 492px; resulting View was empty.
- Empty post-removal state: Expand All, Collapse All, Clear Selection, Remove
  All, and Remove Selected were all hidden.
- Console: no errors or warnings.
- Screenshot capture succeeded for the empty state and reported
  `C:\\home\\bobreap\\projects\\teachers-net-site\\tmp\\DV-UX018-empty.png`,
  but Chrome MCP did not expose the Windows-reported path to WSL.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `3aea480`
- Push: successful
- Root documentation commit: pending cycle publication

DV-UX018 is accepted for V1. DV-UX019 was not started.
