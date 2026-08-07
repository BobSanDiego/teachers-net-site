# Views V1 Product Specification

**Ticket:** DV-SPEC001  
**Status:** Canonical product authority  
**Date:** 2026-08-06  
**Implementation target:** DV-UX009

## 1. Product purpose

Views is the platform-owned presentation composition system for Teachers.Net.
It lets an administrator choose and organize canonical Core Terms into a
durable View that consumers can resolve and render.

Views is not a taxonomy, a replacement for Core Terms, a consumer-owned
configuration system, or a general editorial collection builder. Core Terms
owns meaning and hierarchy. Views owns composition and publication. Consumers
resolve Views through the platform boundary and own rendering.

```text
Core Terms  →  Views  →  Consumer  →  Rendering
 taxonomy      composition  resolution  presentation
```

## 2. Terminology

- **View:** Stable named presentation definition.
- **Draft:** Mutable working version of a View.
- **Published View:** Immutable version available to consumers.
- **Core Term:** Canonical taxonomy item identified by framework and UUID.
- **Current View:** The draft composition shown in the editor.
- **Core Terms Library:** Read-only hierarchical source of canonical terms.
- **Shuttle:** Explicit transfer of pending Library selection into the draft.
- **Remove:** Explicit transfer of a Current View selection out of the draft.
- **Preview:** Platform resolution of a draft without publication.
- **Validation:** Report of whether the draft can be safely published.

V1 does not expose presentation containers, floating terms, editorial
collections, or meta-groups as product concepts.

## 3. Lifecycle

```text
Published View → Create Draft → Save Draft → Preview / Validate → Publish Draft
```

- A View has at most one active draft.
- Published versions are immutable.
- Saving changes the draft only.
- Preview resolves the draft without changing the published version.
- Publish creates the next immutable published version after validation.
- Delete Draft removes the active draft after confirmation.
- Revert restores the draft to its last saved state or removes the unsaved
  working state according to the implemented confirmation flow.
- Existing published Views remain available while a draft is incomplete.

## 4. View Manager

The manager lists Views with name, status, current published version, and
available actions. V1 actions are Edit, Publish when a valid draft exists,
Clone where the existing platform contract supports it, and Archive/Restore
where authorized. A published View cannot be edited in place.

If a user requests a new draft while one exists, show:

> An existing draft is available. Open Existing Draft, Discard Draft and
> Create New Draft, or Cancel.

Discard and archive actions require explicit confirmation. No action silently
mutates a published version.

## 5. Create View

1. Select **Create View**.
2. Enter View name and optional description.
3. Confirm **Create Draft**.
4. Open the blank Draft Editor.

The initial editor must prioritize composition. It must not expose framework
selection when only one framework is available, manual UUID entry, ranking
numbers, include-descendants switches, Add-to-Draft row actions, or
presentation-container authoring.

## 6. Draft Editor

The editor contains:

```text
┌──────────────────────────────────────────────────────────┐
│ View / Draft status     Save Draft  Preview  Publish     │
├──────────────────────────────┬───────────────────────────┤
│ Core Terms Library           │ Current View               │
│ read-only source tree        │ editable draft tree        │
│ [Shuttle Selected]           │ [Remove Selected]          │
└──────────────────────────────┴───────────────────────────┘
```

The contextual toolbar shows only actions valid for the current state. Save
Draft remains available for a mutable draft. Preview and Publish are distinct;
Publish is unavailable or blocked while validation fails. No V1 drag/drop,
container editor, or floating-term interaction is exposed.

## 7. Core Terms Library

The Library is read-only and displays a compact hierarchy. Top-level terms are
shown initially; descendants are revealed with disclosure controls. Terms are
never moved, copied, renamed, reordered, archived, or otherwise mutated by
Views.

### Visual states

- **Available:** normal label and enabled checkbox.
- **Pending shuttle:** selected term highlighted blue.
- **Required ancestor context:** ancestor path highlighted blue with a muted
  or unavailable checkbox; it supplies context and is not an independent
  user selection.
- **Already represented:** label muted and checkbox unavailable/muted. This
  means the term is already in the active draft View and cannot be shuttled
  again.
- **Partial branch:** ancestor checkbox is indeterminate when only part of its
  branch is selected.

Top-level behavior is intentionally conservative: a top-level name click may
request confirmation before selecting its branch; the checkbox is not used to
imply an unbounded taxonomy mutation.

The Library provides **Shuttle Selected** and, where the visible workflow
supports it, **Shuttle All**. Shuttle All requires confirmation because it
creates a broad draft selection.

## 8. Shuttle rules

- Selection is temporary until shuttle confirmation.
- Selecting a term selects the intended canonical reference.
- Required ancestor context is included by the composition operation where
  needed, but ancestor context is not presented as a separate taxonomy edit.
- Terms already represented in the draft are unavailable for duplicate shuttle.
- Duplicate inclusion is rejected by the existing repository/service contract.
- Canonical framework and UUID identity is preserved.
- A shuttle creates or updates draft composition only; it never changes Core
  Terms or a published version.
- After a successful shuttle, pending selection is cleared and representation
  state is refreshed.

## 9. Current View and removal

Current View is the editable representation of the draft. It uses the same
hierarchical visual language as the Library, but its entries are View-owned
placements referencing canonical UUIDs.

- Normal entries display normally and can be selected for removal.
- A selected removal target is checked and struck through.
- Descendants affected by removal inherit the strike-through state.
- **Remove Selected** removes confirmed targets from the draft.
- **Remove All** requires confirmation and removes all draft placements.
- Removal is draft-only and does not mutate Core Terms or published versions.
- After removal, the Library refreshes represented and available states.

V1 does not expose nested containers, floating terms, editorial collections,
hidden ancestors, repeated placement, or alternate presentation trees.

## 10. Dialog wording

- **Create Draft:** “Create a draft View from these settings?”
- **Existing Draft:** “An existing draft is available. Open Existing Draft,
  Discard Draft and Create New Draft, or Cancel.”
- **Delete Draft:** “Delete this draft? The published View will remain
  unchanged.”
- **Publish Draft:** “Publish this validated draft? Published versions are
  immutable.”
- **Remove All:** “Remove all terms from this draft View?”
- **Shuttle All:** “Add all selected Library terms to this draft View?”

## 11. Visual language

| Visual state | Meaning |
| --- | --- |
| Blue highlight | Pending shuttle selection or required ancestor context |
| Muted label | Already represented or unavailable in the current draft |
| Muted checkbox | Cannot be selected for duplicate shuttle or is context-only |
| Strike-through | Pending removal from Current View |
| Disabled action | Invalid for current lifecycle or selection state |
| Draft badge | Mutable working version; not consumer-visible publication |
| Published badge | Immutable consumer-facing version |

Color is supplemented by text, checkbox state, and accessible names; color is
never the sole state indicator.

## 12. Explicitly deferred from V1

The following are intentionally excluded: drag/drop, presentation containers,
floating terms, editorial collections, hidden ancestors, templates, Save As,
repeated placement, virtual nodes, inheritance, View families, import/export,
approval workflows, analytics attachment points, and consumer-specific
presentation.

These are future capabilities, not implied by V1 UI or current persistence.

## 13. Acceptance criteria

Views V1 is complete when an administrator can:

1. Create a View and reach a blank Draft Editor.
2. Browse the read-only Core Terms Library.
3. Select a branch and see blue selection/ancestor context.
4. Shuttle selected terms into Current View without taxonomy mutation.
5. See represented terms muted and unavailable for duplicate shuttle.
6. Select Current View terms for removal and see strike-through propagation.
7. Remove selected terms or confirm Remove All.
8. Save a draft, preview it through the platform resolver, and validate it.
9. Publish a valid draft as an immutable version.
10. Preserve the existing Jobs consumer binding and fallback behavior.
11. Use the workflow without legacy controls or consumer-side composition.

Any contradiction between this specification and earlier UX artifacts must be
reported for product direction; it must not be resolved by implementation
assumption.

## 14. Authority and unresolved contradictions

This specification consolidates DV-ARCH002, DV-UXAUD002, DV-UX006,
DV-UX006A, DV-UX007, and DV-UX007A plus the finalized ChatGPT product
decisions. The earlier roadmap DV-UX008 placeholder is superseded.

The current code still contains some legacy server-rendered controls that are
CSS-hidden; DV-UX009 must remove them from the active authoring workflow. The
current repository also uses flat group persistence, but V1 does not expose
groups or containers as authoring concepts. These are implementation gaps to
address against this specification, not changes to this product authority.

**Next ticket:** DV-UX009 — Views V1 Authoring Workflow Implementation.
