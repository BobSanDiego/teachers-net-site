# DV-UX007A — Views V1 Interaction State Audit

Status: Complete — audit only  
Date: 2026-08-06  
Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=13  
Verified against canonical URL: YES

## Executive summary

The current DV-UX007 authoring flow is coherent enough for V1 use. The
Library-to-View composition model is discoverable, branch selection has the
required three-state behavior, and draft-only boundaries remain intact.
Preview and validation remain available without changing publication state.

The principal remaining interaction-state risk is implementation hygiene:
deprecated manual-entry and presentation controls remain present in server
output and are hidden through CSS. They are not visible in the normal browser
flow, but they remain discoverable to automation, assistive technology under
some state changes, and future CSS changes. This is a follow-up implementation
item, not a change made by this audit.

## State matrix

| Surface | Verified state | Result | Finding |
| --- | --- | --- | --- |
| Create View | Name/description form and Create draft action | Pass | Primary entry point is clear |
| Empty/current draft framing | Compose View, Library, Current View, workflow copy | Pass | Destination is explicit |
| Library hierarchy | Top-level terms visible; descendants collapsed | Pass | Initial density is controlled |
| Parent selection | Parent click selects descendants | Pass | Six direct children selected in browser test |
| Parent reduction | Second parent interaction leaves parent selected and clears descendants | Pass | Parent-only state observed |
| Clear | Third interaction clears parent | Pass | Unchecked state observed |
| Partial branch | One child selected | Pass | Parent became indeterminate |
| Shuttle | Add selected to View button present | Pass | Existing repository action remains the persistence seam |
| Representation | Existing terms remain in Library and show represented state | Pass | Library remains read-only |
| Current View | Existing draft entries and container disclosure | Pass | Draft composition remains editable |
| Preview | Preview draft link present | Pass | Resolution remains platform-owned |
| Validation | Existing draft reported Valid | Pass | Three entries reported in inspected draft |
| Publish boundary | Publish remains an explicit manager action | Pass | No publish was triggered during audit |
| Deprecated controls | Manual entry, Add to Draft, group form, and include-descendants markup | Follow-up | Hidden by CSS rather than removed from server output |
| Console | Browser console errors/warnings | Pass | None observed during inspected navigation |

## Interaction findings

### I-001 — Deprecated markup remains in the DOM

The active CSS hides `.cfm-views-legacy-entry-path`, per-row `Add to Draft`
buttons, the group creation form, and the entry-level `include descendants`
control. The PHP renderer still emits these controls. This creates a fragile
state boundary and leaves the old form contract in the page source.

Recommendation: remove obsolete controls from the primary renderer in a
separate bounded implementation ticket. Preserve repository handlers only if
they are still required for compatibility or migration.

Classification: UX implementation follow-up; no architecture blocker.

### I-002 — Framework selector is still rendered for a single framework

The inspected installation exposes one `Teachers.Net` framework, but the
authoring controls still render a framework select with one option. This is
functional but adds a needless decision in the V1 path.

Recommendation: render the selector only when multiple active frameworks are
available; retain the service-side framework boundary.

Classification: low-risk interaction polish.

### I-003 — Selection state is temporary until shuttle submission

The browser correctly distinguishes checked, mixed, and unchecked states, but
selection is not persisted until the shuttle form is submitted. This is the
intended draft workflow and should remain clearly labeled if future autosave
or navigation warnings are introduced.

Classification: document current behavior; no action required for V1.

## Authority and architecture audit

- Core Terms remains the taxonomy authority.
- Views submits canonical framework/UUID references through the existing
  repository method `CFM_Views_Repository::add_selected_entries`.
- No taxonomy is copied or mutated by the browser interaction.
- Draft-only editing, preview, validation, publication, and Jobs resolution
  boundaries remain unchanged.
- No schema, repository, resolver, or consumer changes were made.

## Verification evidence

Inspected files/classes:

- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
  (`handle_actions`, `render_page`, `render_draft_editor`,
  `render_canonical_browser`, and workbench scripts/styles).
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
  (`add_selected_entries`, entry persistence, reorder, publish, validate, and
  preview methods).
- Current Profilaxes commit: `23c703a` — `DV-UX007 rebuild Views authoring flow`.
- Governing specification:
  `docs/core-terms/durable-views-dv-uxaud002-authoring-model-specification.md`.

Browser evidence at the canonical URL:

- 100 canonical terms loaded; 3 top-level rows visible initially.
- Parent branch test: six direct children selected, then reduced to parent
  only, then cleared.
- Partial selection test: one child selected and parent indeterminate.
- Existing draft validation: Valid, three entries.
- Console: no error or warning messages.

## Stop boundary

This ticket made no implementation changes. No schema, UX, persistence,
resolver, Core Terms, or Jobs changes are authorized by this audit.
