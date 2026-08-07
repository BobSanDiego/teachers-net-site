# DV-UX017 — Restore Shuttle Actions and Prevent Shuttle Viewport Jump

Status: COMPLETE — BROWSER VERIFIED

## Root cause

The rendered Shuttle All Terms and Shuttle Selected buttons had no JavaScript
listeners. The hidden submit button and valid `add_selected` controller path
existed, but the visible controls never invoked them. No scroll-preservation
state existed around the server-rendered redirect.

## Implementation

Updated `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`:

- bound both shuttle controls to the existing batch form;
- selected every eligible unrepresented canonical term for Shuttle All;
- excluded represented terms and disabled ancestor-context controls;
- preserved the existing nonce/action/controller and ancestor expansion path;
- stored and restored scroll position across successful `batch_added` redirects.

No schema, repository, resolver, Jobs, removal, bulk-selection, or lifecycle
contract changed.

## QA fixture and mutations

Fresh authoritative version-17 persistence was empty, although a stale browser
tab displayed old rows. Under GOV-VIEWS001, version 17 was restored as the
explicitly disposable local QA fixture through `add_selected_entries()` using
Early Learners and Grade 1, creating the required nested fixture.

- Shuttle Selected: selected Pre-K; redirect reported `batch_added=1&batch_skipped=2`; Pre-K appeared beneath Early Childhood.
- Shuttle All Terms: redirect reported `batch_added=94&batch_skipped=159`; Current View contained 100 unique entries with no duplicate UUIDs.

The draft fixture is preserved in a valid all-terms-represented state for later
local QA.

## Browser verification

Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

- Selected action began at scroll 600 and completed at approximately 592.
- All action began at scroll 650 and completed at approximately 642.
- No page-top or editor-header jump occurred.
- Kindergarten, Pre-K, and Transitional Kindergarten retained Early Childhood
  as their canonical parent UUID.
- No console errors or warnings.
- Chrome MCP captured a screenshot but did not expose the reported Windows path
  `C:\\home\\bobreap\\projects\\teachers-net-site\\tmp\\DV-UX017-after.png`
  to WSL.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `8b29692`
- Push: successful

Both shuttle actions and shuttle viewport stability are accepted for V1.
