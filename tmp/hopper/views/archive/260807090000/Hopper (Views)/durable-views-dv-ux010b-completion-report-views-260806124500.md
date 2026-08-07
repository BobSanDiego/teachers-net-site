# DV-UX010B — Enforce Canonical Tree Rendering and Ancestor Shuttle

## Result

DV-UX010B is complete and browser-verified. The Library now visibly enforces
the canonical tree shuttle contract shown in the approved mockups: a pending
descendant and its required ancestor path receive the blue context treatment;
only the descendant remains a pending canonical selection; ancestor checkboxes
are disabled and do not enter the shuttle payload.

## Implementation

Changed `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.
The existing recursive tree and UUID parent relationships are preserved. The
selection synchronizer now applies and clears explicit pending-selection and
ancestor-context classes while preserving represented-term disabling and
canonical UUID-only form values. No schema, repository, resolver, Current View,
or Jobs code was changed.

## Verification

- Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17
- Authenticated browser: `jobman`.
- Grade Level expanded independently.
- Early Learners selected: pending selection state applied to the selected row;
  Grade Level and Early Childhood received ancestor context; Early Childhood
  checkbox was disabled.
- Selected count remained `1 selected`.
- Browser console: no messages or errors.
- Screenshot: `DV-UX010B-library-selection-views-260806124500.png`.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `942d8c4`
- Profilaxes push: successful
- Root branch: `COMMUNITY003-semantic-community-communications-working-draft`
- Root documentation/hopper commit: pending
- Root push: pending

DV-UX010B COMPLETE. Human DV-UX009 acceptance may continue. No V2 work was
begun.
