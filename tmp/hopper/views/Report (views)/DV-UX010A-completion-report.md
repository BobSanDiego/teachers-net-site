# DV-UX010A — Correct Library Tree Structure and Ancestor Shuttle Contract

## Result

DV-UX010A is complete and browser-verified. The Core Terms Library now uses the
approved V1 contract: nested terms render beneath their canonical parents;
disclosure controls operate independently; non-top-level names disclose their
own branch; top-level names offer whole-tree selection; and selecting a
descendant marks its ancestor path as muted context without adding ancestors to
the shuttle payload.

## Implementation

Changed `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.
The Library now renders term names as explicit controls, removes cascading
parent-selection behavior, adds ancestor-context synchronization, and handles
the top-level “Select this entire tree?” action. Current View, persistence,
resolver, schema, and Jobs integration were not changed.

## Verification

- Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17
- Authenticated browser: `jobman`.
- Grade Level expanded and collapsed independently; descendant rows remained
  nested and indented.
- Early Learners selected: selection count was `1 selected`; Grade Level and
  Early Childhood were marked ancestor context; Early Childhood was disabled;
  only the selected canonical UUID remained checked.
- Browser console: no messages or errors.
- Screenshot: `DV-UX010A-library-ancestor-views-260806123000.png`.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `406a670`
- Profilaxes push: successful
- Root branch: `COMMUNITY003-semantic-community-communications-working-draft`
- Root commit: pending documentation/hopper commit
- Root push: pending

Human DV-UX009 acceptance may resume; this ticket does not begin DV-UX010B or
any V2 work.
