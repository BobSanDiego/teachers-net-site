# DV-SPEC002 — Finalize Views V1 Product Specification

Status: Complete — documentation alignment only  
Date: 2026-08-06

## Result

DV-SPEC001 was revised to reflect the finalized V1 interaction model from the
ChatGPT Views session. It remains the sole product authority for DV-UX009.

Updated rules include:

- Left checkboxes mean pending shuttle action; blue is the consequence.
- Required ancestors are blue context with muted/disabled checkboxes.
- Top-level terms have no checkbox; name-click prompts “Select this entire
  tree?” with Yes/Cancel.
- The default left toolbar is Shuttle All Terms; Shuttle Selected and Clear
  Selection appear contextually when pending selections exist.
- Right checkboxes mark removal roots; strike-through and muted descendants
  show cascaded pending removal.
- The default right toolbar is Remove All Terms; Remove Selected and Clear
  Selection appear contextually when removal selections exist.
- View Manager opens viewing contexts; Publish Draft exists only in the Draft
  Editor.
- Draft terminology is Save Draft, Revert to Saved Draft, Preview, and
  Publish Draft.
- Presentation containers, drag/drop, floating terms, hidden ancestors,
  templates, Save As, repeated placement, and virtual nodes remain deferred.

## Contradictions removed

The earlier indeterminate-parent model was removed as the primary interaction
rule. Earlier Presentation Container, Add Group, manual-entry, framework,
ranking, and include-descendants concepts are explicitly historical and not V1
authoring controls.

## Verification

Reviewed DV-SPEC001 section by section against the finalized ChatGPT ticket.
No application files, schema, repository, resolver, or consumer behavior were
changed. Remaining implementation work belongs to DV-UX009.

Specification: `docs/core-terms/durable-views-v1-product-specification.md`.
