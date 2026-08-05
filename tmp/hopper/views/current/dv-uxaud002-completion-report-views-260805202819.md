# DV-UXAUD002 — Completion Report

Status: Complete — product-definition audit only  
Date: 2026-08-05

## Executive recommendation

The next-generation Views product should use a dual-tree source/destination
workbench: a read-only hierarchical Core Terms Library on the left and an
editable Current View presentation tree on the right. The product should
retain canonical UUID references, draft-only editing, validation, preview,
immutable publication, and platform-owned Jobs resolution.

The first implementation ticket should be **DV-UX006 — Canonical tree
discovery and dual-tree shell**. Follow with selection/shuttle behavior,
Current View tree interaction, and then lifecycle/View-manager actions.

## Defined product decisions

- Core Terms Library is read-only and never mutates taxonomy.
- Current View is the sole editable presentation surface.
- Selection has explicit checked, unchecked, and indeterminate branch states.
- Add/Remove shuttle operations are primary; drag-across is optional and never
  the only accessible path.
- Representation means represented in this View, not moved or copied in Core
  Terms.
- Current groups should be presented as future-facing Presentation Containers,
  without claiming nested or virtual-node support.
- Save, Preview, Validate, Publish, Save As, Clone, Revert, Archive, and
  Restore are distinct product concepts.
- Virtual nodes, repeated placement, inheritance, templates, import/export,
  approval, and consumer-specific presentation remain deferred.

## Verification

Reviewed the current workbench, Core Terms reuse audit, DV-ARCH002 findings,
Views admin/repository/service/schema files, Core Terms admin routing, and the
canonical local review routes documented in the specification.

No UI, schema, repository, resolver, or consumer changes were made.

Specification: `docs/core-terms/durable-views-dv-uxaud002-authoring-model-specification.md`.
