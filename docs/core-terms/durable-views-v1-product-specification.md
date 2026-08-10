# Views V1 Product Specification

## Standalone MVP certification — 2026-08-10

The standalone Durable Views MVP is accepted for separately authorized
consumer integration. DV-ACCEPT002 proved the supported create, compose,
persist/reload, publish, immutable inspection, edit-from, draft isolation,
second-publication, resolver, manager, and subscriber-pinning lifecycle.

The Job Center adapter exists, but this certification does not migrate the Job
Center wizard. Its existing Grade Level subscriber remains explicitly pinned
to stable View 10 / published version 12; corrected JobLister v2 and the
current draft are independent. Subscriber migration, version nicknames,
semantic diffs, dependency warnings, snapshots, archival disturbance
indicators, and explicit Core Terms term migration remain deferred product
directions and require separate authorization.

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
- **Subscriber:** A consumer binding pinned to a specific published View/version.
- **Release nickname:** Optional human-readable administrative metadata; never a
  substitute for durable machine identity.

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

Publishing finalizes the current draft as an immutable, subscriber-eligible
published version. It preserves existing subscriber bindings and historical
versions; it does not automatically migrate subscribers, mutate older versions,
or hide/delete history. Consumers remain pinned until an explicit future
migration operation changes their binding.

When an older published version is used for editing, it is copied as the
starting composition for the single active draft. The published version is
never edited in place. If a draft already exists, replacing its contents
requires explicit confirmation; the immediate V1/V1.1 choice may be Replace
Current Draft or Cancel. Parallel drafts and snapshot preservation remain
deferred.

## 4. View Manager

The manager groups versions beneath stable View identities. Its intended
operational default is to show the current draft, the latest published version,
and every published version with active subscribers (showing a version once if
it is both latest and subscribed). Other history remains available through
See all versions or equivalent expansion. This is presentation filtering only;
it must not archive, retire, delete, or alter runtime resolution.

The manager should eventually summarize View-level state such as draft/latest
published versions and subscriber counts, and expose subscriber identities and
pinned View/version identity when expanded. Exact visual design is separate
implementation work.

The manager lists Views with name, status, and current published/draft state.
It locates Views and opens the appropriate viewing context; it does not edit a
View directly. The manager may expose Open/View Published, Create Draft/Edit
Draft, Clone, and Archive actions according to state and authorization. Publish
is available only inside the Draft Editor.

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
selection, manual UUID entry, ranking/order fields, include-descendants
switches, Add-to-Draft row actions, or presentation-container authoring.

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
- **Required ancestor context:** ancestor checkboxes are muted/disabled; the
  finalized V1 interaction does not use indeterminate parent selection as its
  primary model.

Top-level terms have no checkbox. Clicking a top-level name (not its disclosure
icon) prompts: “Select this entire tree?” with Yes and Cancel. Non-top-level
name clicks expand or collapse only.

The Library toolbar defaults to **Shuttle All Terms**. When pending selections
exist, contextual controls appear: **Shuttle Selected**, **Clear Selection**,
and **Shuttle All Terms**. Shuttle All Terms requires confirmation.

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

- Normal entries display normally and can be checked as removal roots.
- A checked removal target is struck through.
- Descendants affected by removal inherit strike-through and muted checkbox
  state; removal cascades downward.
- The Current View toolbar defaults to **Remove All Terms**. When removal
  selections exist, contextual controls appear: **Remove Selected**, **Clear
  Selection**, and **Remove All Terms**.
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

## 14. Subscriber and Core Terms governance decisions

Views owns View/version lifecycle; consumers own their bindings. A published
version remains identified by durable View and version identity even if future
release nicknames are added or changed. Publishing alone never rewrites a
consumer binding.

Core Terms remains the live canonical taxonomy authority referenced by UUID.
Views does not freeze or copy taxonomy data into a second authority. Canonical
add, rename, restructure, archive, and autosave operations remain permitted.
The accepted tradeoff is that canonical changes may affect the resolved meaning
or presentation of published Views and subscribers; Core Terms publication or
version governance is not introduced to prevent that.

Routine Core Term retirement should eventually prefer archive over hard delete.
Future tooling may flag affected Views, summarize dependencies, and preserve
UUID/history without silently migrating View versions, subscriber bindings, or
downstream records. Any record migration from an archived/deleted UUID is a
separate future administrator-controlled operation.

## 15. Deferred product directions and next boundary

Deferred capabilities include multiple drafts, saved draft snapshots, release
lineage, semantic version diffs, automatic or bulk subscriber migration,
resolution snapshots, generalized dependency graphs, hard-delete automation,
Core Terms record migration, and destructive archive/retire operations used only
for manager housekeeping. These remain possible later and are not implemented
by this specification update.

The next implementation boundary is an operationally understandable View
Manager: group versions under stable Views, surface draft/latest/subscribed
versions by default, and make subscriber/dependency significance explainable.
The exact UI and any dependency or migration tooling require separate tickets.

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
