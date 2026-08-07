# DV-FIX003 — Enforce Top-Level Structural Ancestor Invariant

Status: COMPLETE

## Result

Durable Views now treats canonical non-leaf terms as presence-driven structural ancestors. A draft-load normalization pass and the repository deletion path remove ancestor-only rows within the same draft version and Core Terms framework. Published versions, other versions, schema, resolver behavior, Library rendering, and Jobs integration were not changed.

## Implementation

- `CFM_Views_Repository::normalize_structural_ancestors()` prunes included non-leaf ancestors that have no included descendant, deepest-first and within the current version/framework.
- `delete_entries()` invokes normalization after removal.
- Draft editor rendering invokes normalization so a stale top-only draft is repaired before presentation.

## Verification

Canonical review URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

- Initial version 17 standalone Grade Level row (entry 17) normalized to zero rows.
- Selecting Early Learners through the canonical batch path restored Grade Level, Early Childhood, and Early Learners together.
- With two descendants present, removing one retained Grade Level, Early Childhood, and the remaining descendant.
- Removing the final descendant left no Current View rows after reload; the final normalization pass removed the remaining ancestor-only rows.
- Direct database inspection confirmed version 17 ended empty.
- Browser console reported no errors or warnings during the final reload.
- Screenshot: `tmp/DV-FIX003-after.png`.

The browser verification used the authenticated canonical Views editor. The shuttle button remained hidden by an existing UI state, so the normal batch form submission was invoked directly after selecting the descendant; this exercised the same server-side request path.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `e02e7e6`
- Push: successful

## Handoff

DV-DIAG002 should remain paused. DV-DIAG003 established that the Codex session and the engineer evidence did not share proven runtime/session state, and the restored right-panel fixture is not currently present in version 17. Resume DV-DIAG002 only after runtime/session parity and the required fixture are explicitly restored and verified.
