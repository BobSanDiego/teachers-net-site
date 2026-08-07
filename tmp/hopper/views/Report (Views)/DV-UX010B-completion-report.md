# DV-UX010B — Enforce Canonical Tree Rendering and Ancestor Shuttle

## Result

DV-UX010B is complete and browser-verified against all four attached defects.

## Root causes and corrections

1. Views used plus/minus expanders instead of the established Meta-Groups
   triangle control. Views now uses `▸` collapsed and `▾` expanded controls with
   a reserved 18px expander column and independent disclosure behavior.
2. The Library relied on incidental flex spacing. Recursive child wrappers and
   fixed expander/spacer columns now enforce depth-only indentation.
3. The Library inherited compiled-term query order without explicitly applying
   the Core Terms `sort_order` field. Recursive rendering now preserves each
   sibling's canonical `sort_order`, then stable Core Terms row id.
4. `add_selected_entries()` persisted only submitted descendants. It now
   resolves and inserts every missing canonical ancestor, in path order, while
   reusing already represented ancestors.

The implementation reuses `CFM::get_terms()`, the compiled Core Terms
`parent_uuid`, `depth`, `path`, and `sort_order` fields, and
`CFM_Framework_Repository::get_ancestor_uuids()`. No schema change was needed.

## Verification

- Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17
- Authenticated browser: `jobman`.
- Triangle expanders rendered as `▸`/`▾`; Grade Level and Elementary expanded
  independently.
- Grade 1 selection highlighted its required ancestor path.
- Shuttle Selected persisted exactly: Grade Level → Elementary → Grade 1.
- Database verification for version 17 confirmed all three canonical UUIDs,
  parent UUIDs, depths 0/1/2, and display order 0/1/2.
- Right-side Current View displayed `Current View`, never `Ungrouped entries`,
  with rendered margins 0px/24px/48px.
- Console: no messages or errors.
- Screenshots: `DV-UX010B-canonical-shuttle-result.png` and the collected
  selection-state screenshot.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `7ea15fb`
- Profilaxes push: successful
- Root branch: `COMMUNITY003-semantic-community-communications-working-draft`
- Root documentation/hopper commit: pending
- Root push: pending

DV-UX010B COMPLETE. Engineer QA may resume. The final Current View visual
rebuild and V2 work were not begun.
