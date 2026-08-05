# DV-UXAUD002 — Next-Generation Views Authoring Model

Status: Complete — product-definition audit; no implementation  
Date: 2026-08-05  
Authority: Current Views architecture, DV-ARCH002, DV-UXAUD001

## 1. Executive recommendation

Views should evolve from a form-oriented draft editor into a dual-tree source/
destination workbench. The left side is a read-only Core Terms Library; the
right side is the editable Current View presentation tree. The product should
make the distinction explicit: Core Terms supplies canonical taxonomy, while
Views composes presentation.

Retain the current platform boundary, draft/publish lifecycle, validation,
preview, canonical UUID references, and Jobs service integration. Replace the
flat term selector and flat composition cards incrementally, beginning with a
read-only hierarchical discovery model and a View-owned composition model.

Do not expose virtual nodes, repeated placement, inheritance, templates,
approval, or consumer-specific presentation until their architecture and
product semantics are separately authorized.

## 2. Current versus proposed model

| Area | Current implementation | Product direction |
|---|---|---|
| Source discovery | Server-rendered term select | Collapsed, searchable hierarchical Core Terms tree |
| Destination | Groups and entry cards | Hierarchical Current View tree with presentation containers |
| Add workflow | Select then Add/Add Selected | Shuttle, explicit Add/Remove, and accessible drag-across |
| Selection | Visible-term checkbox batch selection | Branch-aware selection with explicit representation states |
| Ordering | View-owned drag/drop ordering | Retain drag/drop, add keyboard and menu alternatives |
| Grouping | Flat View groups | Presentation containers; nesting deferred until semantics are defined |
| Lifecycle | Draft, preview, validate, publish | Add Save, Save As, Clone, Revert, Archive as distinct concepts |
| Consumer | Jobs binding and platform resolution | Preserve unchanged; consumers never compose Views |

## 3. Users and goals

Primary user: an administrator composing a reusable presentation View from
canonical Core Terms.

Goals:

1. Find canonical terms quickly without editing taxonomy.
2. Understand what is already represented in the current View.
3. Organize presentation intentionally and review it before publishing.
4. Save safe drafts and distinguish Save, Publish, Save As, Clone, Revert, and
   Archive.
5. Recover from errors without mutating a published version.

Non-goals: taxonomy editing, permission management, search configuration,
consumer-specific composition, or hidden reconstruction of Core Terms data.

## 4. Information architecture

```text
Views
├── View manager
│   ├── search / filter / sort
│   ├── status and current published version
│   └── actions: Edit draft, Preview, Save As, Clone, Archive/Restore
└── View editor
    ├── context: View, draft version, status, unsaved state
    ├── Core Terms Library (read-only)
    │   ├── framework selector
    │   ├── search
    │   └── canonical hierarchy
    ├── Current View (editable draft)
    │   ├── presentation containers
    │   └── canonical term placements
    └── action bar: Save, Preview, Validate, Publish, Revert
```

The object vocabulary is: View → Version → Presentation Container → Placement
→ canonical Core Term reference. “Group” may remain the current implementation
term, but user-facing copy should prepare for “Presentation Container” without
claiming virtual-node support.

## 5. Dual-tree interaction

Both trees are collapsed by default, with clear disclosure controls and
accessible names. The Library shows canonical label, hierarchy context, and
representation state. The Current View shows presentation order, container,
display label, inclusion, descendant intent, and draft-only controls.

The Library is read-only. It may expose Add, Select, and context actions, but
never edit, archive, reorder, or persist Core Terms. The Current View is the
only editable tree and persists through the Views repository.

On narrow screens, stack Library above Current View. Keep the action bar sticky
or repeat it after the composition tree. Do not require side-by-side width for
Save, Preview, Validate, Publish, or Revert.

## 6. Selection model

Selection is a representation operation, not taxonomy mutation.

- Selecting a leaf selects that canonical reference for a proposed placement.
- Selecting a parent selects the visible branch as a proposed batch, with a
  clear count and confirmation before insertion.
- A second interaction may reduce the selection to the parent only; a third
  clears it. This cycle must be discoverable through checkbox state, help text,
  and keyboard behavior rather than relying on gesture memory.
- Partial branch selection uses an indeterminate state.
- Selection is temporary until Add/Move is confirmed.
- Duplicate policy must be explicit: current same-inclusion duplicates are
  rejected; future repeated placement requires a placement model first.

For accessibility, every parent checkbox exposes checked, unchecked, or
mixed state; keyboard users receive the same operations through focusable
controls and named commands.

## 7. Shuttle and drag behavior

The primary operation is an explicit shuttle:

```text
Core Terms Library  -- Add selected -->  Current View
Current View        -- Remove        -->  Core Terms Library state
```

Drag-across may be a convenience, but must not be the only method. It must
announce the destination, preserve canonical UUID identity, and create only a
draft placement. Keyboard equivalents use Add, Move to container, and Remove
commands. Dragging within Current View reorders View-owned placements or
containers only.

The first implementation should retain Add Selected as a reliable fallback;
the shuttle is a product model, not permission to weaken repository or
published-version safeguards.

## 8. Representation states

The Library should distinguish:

- Not represented — no matching draft placement.
- Represented — one current draft placement.
- Partially represented — the selected branch has mixed representation.
- Multiply represented — only available after a future placement model is
  authorized; not exposed by the MVP.

Representation indicators must say “represented in this View,” never imply
that the Core Term has been moved, copied, or changed.

## 9. Presentation containers

Use “Presentation Container” as the future-facing product term and explain
that current containers are flat groups. Containers own presentation order,
label, visibility, and contained placements. They do not become taxonomy
nodes.

Nested containers, virtual parents such as STEM, and repeated placements are
deferred. A future ticket must define node identity, placement identity,
ordering, validation, preview, resolver output, and consumer semantics before
the UI exposes them.

## 10. Lifecycle and actions

| Action | Product meaning |
|---|---|
| Save | Persist the current draft; does not publish it. |
| Preview | Resolve the draft through the platform service without changing publication. |
| Validate | Report missing terms, invalid containers, duplicates, and incomplete draft state. |
| Publish | Create an immutable published version after validation. |
| Save As | Create a new View identity from the selected draft/published source. |
| Clone | Create an independent View copy with provenance recorded. |
| Revert Draft | Discard or restore draft changes according to an explicit confirmation flow. |
| Archive | Remove a View from normal active management without deleting history. |
| Restore | Return an archived View to active management where authorized. |

Save As and Clone must not be presented as aliases until their provenance and
lineage behavior are implemented. Published versions remain immutable.

## 11. View manager

The View manager should support search, status filtering, framework/usage
context where available, sort by name/updated/recent, pagination when needed,
and clear row actions. Each row should show View name, draft status, published
version, updated time, and safe actions. Destructive/archive actions require
confirmation and must not silently alter a published version.

The manager should not expose future family, template, import/export, or
approval controls until their semantics and backend contracts exist.

## 12. Retain, revise, remove

Retain: canonical UUID authority, read-only discovery boundary, draft-only
editing, validation, preview, publish, immutable versions, Jobs binding, and
platform resolution.

Revise: flat select to tree discovery; flat cards to a tree-oriented
composition surface; ambiguous group language to container language; implicit
selection to explicit branch state; lifecycle actions to named product
operations.

Remove from the future primary path: taxonomy-like mutation affordances inside
Views, unexplained manual UUID/term entry, and any consumer-side reconstruction
of composition. A manual compatibility path may remain temporarily but should
be subordinate and clearly labeled.

## 13. Future compatibility and risks

The product model aligns with DV-ARCH002 if it treats a placement as distinct
from a canonical term reference in future design. It must not assume that a
Group is a virtual node, that `based_on_version_id` is inheritance, or that
flat resolver entries can express a presentation tree.

Primary risks are gesture-heavy selection, confusing taxonomy with
presentation, exposing unfinished capabilities, and designing UI states that
the current uniqueness rule cannot persist.

## 14. Recommended implementation sequence

1. **DV-UX006 — Canonical tree discovery and dual-tree shell:** retain current
   repository boundaries; add read-only hierarchical discovery, representation
   states, and accessible source/destination framing.
2. **DV-UX007 — Selection and shuttle behavior:** implement branch selection,
   Add Selected, keyboard equivalents, duplicate feedback, and draft-only
   insertion.
3. **DV-UX008 — Current View tree and container interaction:** evolve current
   flat groups/cards into a clearly ordered View-owned tree without virtual
   nodes or nested persistence.
4. **DV-UX009 — Lifecycle and View manager actions:** define and implement Save,
   Revert, Save As, Clone, and manager workflows only after repository contracts
   are separately approved.
5. **DV-ARCH003 — Placement/node extension audit:** required before repeated
   placement, virtual parents, nested containers, inheritance, or consumer
   projections.

## 15. Features to defer

Defer virtual presentation nodes, nested containers, repeated placements,
inheritance, View families, reusable templates, import/export, approval
workflow, analytics event contracts, and consumer-specific presentation.
These remain valid future directions but are not implied by this specification.

## 16. Verification record

Reviewed:

- `docs/core-terms/durable-views-uxaud001-reuse-audit.md`
- `docs/core-terms/durable-views-dv-arch002-future-expansion-preservation-audit.md`
- `docs/core-terms/durable-views-project-cursor.md`
- `docs/core-terms/durable-views-engineering-handoff.md`
- `docs/core-terms/durable-views-roadmap.md`
- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-admin.php`
- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-service.php`
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-schema.php`

Reference routes:

- `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-frameworks&action=editor`
- `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views`
- `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=<draft-version-id>`
- `https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-job-categories&tab=map`
- `https://teachers-net.ddev.site/jobs/employer/new/`

No UI, schema, repository, resolver, or consumer changes were made.
