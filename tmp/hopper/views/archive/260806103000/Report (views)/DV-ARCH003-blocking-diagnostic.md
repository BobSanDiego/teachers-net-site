# DV-ARCH003 — Views Renderer Blocking Diagnostic

Status: Complete — diagnostic only  
Date: 2026-08-06  
DV-UX009: Open and blocked

## Executive conclusion

DV-UX009 is blocked by the current server-rendered editor architecture, not
by the Views schema, repository, resolver, or authority model. The renderer
interleaves multiple independent POST forms inside the same tree markup and
uses one large string-concatenated method for source discovery, composition,
legacy compatibility, ordering, and entry editing. This prevents safe
implementation of aggregate selection states and contextual toolbar actions.

## Evidence inspected

- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
  (`handle_actions`, `render_draft_editor`, `render_canonical_browser`, and
  `render_workbench_assets`).
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
  (`add_selected_entries`, `save_entry`, `delete_entry`, ordering, lifecycle,
  validation, and preview methods).
- Current Profilaxes commit: `6a600ff`.
- DV-SPEC001 finalized by DV-SPEC002.

## Blocking findings

1. The controller supports one `delete_entry` POST at a time. There is no
   aggregate removal action or branch-removal contract.
2. Each entry renders its own save, ordering, and delete forms while group
   reorder forms render inside group headers. A contextual aggregate toolbar
   cannot safely own tree selection controls without invalid nested forms or
   brittle client-side form synthesis.
3. `render_draft_editor()` mixes lifecycle, validation, preview, discovery,
   groups, entries, ordering, and legacy compatibility output.
4. The controller has no bounded Save Draft, Revert to Saved Draft, Delete
   Draft, or existing-draft decision seam.

## What is not blocked

Canonical UUID persistence, duplicate prevention, validation, preview,
immutable publication, resolver behavior, Jobs integration, and Core Terms
authority remain valid and require no redesign for this diagnostic.

## Recommended resolution

Treat the remedy as a renderer/controller seam refactor: introduce a V1 render
model, use one aggregate action boundary per tree operation, add a bounded
draft-only branch-removal contract, and add explicit lifecycle actions without
mutating published versions. This recommendation does not authorize
implementation within DV-ARCH003.

## Stop boundary

No schema, repository, resolver, UI, or controller changes were made. DV-UX009
remains open and blocked. DV-UX010 must not begin.
