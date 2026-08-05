# DV-UXAUD001 — Core Terms Workbench Reuse Audit

Status: Complete — audit only  
Date: 2026-08-05  
Implementation changes: None

## Executive finding

The Core Terms editor is the right interaction reference for the next Durable
Views Authoring UX, but it is not a drop-in editor component. Core Terms owns
and mutates the canonical hierarchy; Durable Views must remain a draft-only
composition workspace that reads canonical terms and persists only View
entries, groups, and presentation settings.

The safest target is an adapted split-pane workbench: a read-only, searchable
hierarchy browser on the left and a draft composition canvas on the right.
Core Terms interaction patterns such as hierarchy rows, expand/collapse,
drag affordances, status messaging, and responsive admin framing should guide
the design. Core Terms persistence handlers, tree state, mutation actions, and
taxonomy editor JavaScript must not be reused inside Views.

The first implementation should extract or define a small shared admin visual
layer only where the existing code proves a stable boundary. Do not extract a
shared taxonomy editor or begin nested presentation groups in the first UX
ticket.

## Exact review URLs and browser evidence

Authenticated local DDEV review URLs:

- Core Terms editor: `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-frameworks&action=editor`
- Durable Views index: `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views`
- Durable Views draft editor: `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=<draft-version-id>`
- Jobs mapping/binding: `https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-job-categories&tab=map`
- Employer form consumer: `https://teachers-net.ddev.site/jobs/employer/new/`

Browser observations captured with the authenticated Chrome DevTools session:

| Surface | Populated state | Narrow state | Evidence |
| --- | --- | --- | --- |
| Core Terms | `Core Terms Editor`; hierarchy rows for Grade Level, Subject Area, and Location; expand/collapse and drag-to-reorder controls visible | Not separately navigated after resize; implementation uses responsive admin shell | Snapshot of URL above, page title and rows observed |
| Durable Views | `Create View`; existing `JobLister` published, current version 12, Retire action | At 600px, WordPress menu collapsed to `Menu`; content remained reachable and controls were present in the accessibility tree | Snapshot of URL above |
| DV-023 consumer | Authenticated employer form previously reached Qualifications; bound state showed `Grade Level`, unbound state showed legacy children | Not part of this read-only audit; certified in DV-023 | `docs/core-terms/durable-views-dv023...` and DV-023 final hopper report |

The browser tool did not produce a saved screenshot file in this session, so no
image is claimed. The accessibility snapshots, source inspection, and prior
DV-023 browser evidence are the authoritative evidence for this audit.

## 1. Core Terms workbench inventory

### Routes and implementation

- Admin menu and editor routing: `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-admin.php`
  (`CFM_Admin::register_menu()`, `render_frameworks_page()`, and editor URL
  helpers).
- Browser route: `cfm-frameworks&action=editor`, with a framework ID in the
  surrounding edit route.
- AJAX handlers: `cfm_reorder_terms` and `cfm_move_branch` are registered by
  `CFM_Admin::init()`.
- Editor save/archive handlers: `core_terms_editor_save`,
  `core_terms_editor_archive`, and `core_terms_editor_undo_archive`.
- Persistence and compilation: `CFM_Framework_Repository` and `CFM_Compiler`.
- Admin asset evidence: `jquery-ui-sortable` is enqueued for the Core Terms
  top-level page. No independent shared Durable Views asset bundle was found.

### Observed capabilities

- framework context and back navigation;
- hierarchical tree rows with disclosure controls;
- sibling drag/reorder affordance;
- hierarchy-aware term identity display: label, slug, short label, community;
- row selection leading to edit, sibling insertion, child insertion, archive,
  and reorder operations;
- inline or workbench editing backed by a single submitted change set;
- status/live regions for saved and error outcomes;
- archive and undo-archive flows with conflict/expiry handling;
- Core Terms-owned validation such as duplicate sibling slug and invalid parent;
- tree persistence followed by active-version compilation.

### Not established as reusable Core Terms behavior

The source inspection did not establish a general-purpose search component,
bulk selection model, shared tree API, reusable drag/drop module, unsaved-change
dialog, or independently packaged CSS/JS component library. These may exist as
page-local behavior or browser affordances, but they should not be assumed to
be reusable without a focused implementation inspection ticket.

## 2. Durable Views inventory

### Routes and implementation

- Admin route: `cfm-views`, registered by `CFM_Views_Admin::register_menu()` in
  `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.
- Action handling: `CFM_Views_Admin::handle_actions()` with WordPress nonces
  and `manage_options` authorization.
- Rendering: `CFM_Views_Admin::render_page()` and
  `render_draft_editor()`.
- Preview: `CFM_Views_Admin::render_preview()` and
  `CFM_Views_Repository::preview_version()`.
- Persistence/resolution: `CFM_Views_Repository` and `CFM_Views_Service`.
- Jobs binding UI: `wordpress/wp-content/plugins/tnet-jobs/admin/class-tnet-jobs-job-categories-admin.php`.
- Jobs binding boundary: `TNet_Jobs_Durable_Views_Service`; consumer resolution
  uses the platform boundary and preserves legacy fallback.

### Current browser capabilities

The current editor visibly supports View creation, draft opening, flat group
creation, canonical term selection from framework optgroups, include/exclude,
display label, display order, descendant expansion, entry removal, preview,
validation feedback, and publish. The index exposes published status and
Retire/Restore lifecycle controls. Jobs exposes binding to a currently
published View/version and Remove Durable View binding.

### Current constraints

- The term selector loads the available terms into a server-rendered select;
  it is not a searchable or virtualized hierarchy browser.
- Groups are flat and ordered; nested presentation groups are not implemented.
- Entries are listed in a table; there is no drag-and-drop reorder interaction.
- Clone exists at the service level but is not a complete browser action.
- Published versions are immutable; there is no browser editor for a published
  version.
- The current page has no unsaved-change protection or dedicated audit history.
- The Jobs binding is fixed to a selected published View/version.

## 3. Side-by-side capability matrix

| Capability | Core Terms evidence | Views current state | Classification | Safe recommendation |
| --- | --- | --- | --- | --- |
| Admin shell/layout | WordPress wrap, editor heading, back link | WordPress wrap, headings, tables/forms | Visually reusable | Shared admin visual tokens only |
| Framework selection | Framework context in route and editor | Framework optgroups in term select | Adapt with ownership boundary | Read-only framework/term discovery service |
| Tree browser | Hierarchical rows, disclosure, reorder affordance | Flat select only | Adapt | New read-only View term browser |
| Search/filter | Not verified as a standalone component | Absent | Missing/needs design | Add View-local search over canonical discovery data |
| Multi-selection | Not verified | Absent | Not needed initially | Add only when entry-add workflow proves value |
| Drag/drop | Sibling reorder evidence; jQuery UI sortable | Absent | Adapt | Use only for View entry/group order, never taxonomy mutation |
| Hierarchy visualization | Core hierarchy is authoritative | Indented labels only | Adapt | Read-only tree with parent/descendant context |
| Grouping | Core taxonomy hierarchy, not presentation groups | Flat View groups | Incompatible semantics | Separate View group model |
| Nested groups | Core term nesting, not View groups | Schema-reserved/incomplete | Incompatible/deferred | Do not reuse taxonomy nesting UI |
| Inline editing | Core metadata editing | Forms for new View entries/groups | Conceptually reusable only | Keep presentation edits View-owned |
| Metadata | label/slug/short label/community | display label/order/descendant intent | Adapt | Separate forms and persistence |
| Validation | Core tree/slug/parent validation | View validation before publish | Service reuse only | Preserve independent validators |
| Preview | Not found as same View preview | Resolved draft preview exists | Views-specific | Improve within Views workflow |
| Publish/save | Core change set saves active tree and compiles | POST actions create/update draft and publish | Conceptually reusable | Add dirty-state protection around View drafts |
| Status communication | live status/error handling | WordPress notices and validation block | Adapt | Reuse notice language/pattern, not handlers |
| Error handling | transient errors and conflict/expiry routes | validation notices and redirects | Adapt | Add actionable View-local diagnostics |
| Empty states | Core editor state observed only partially | no Views / no terms / no entries messages exist | Adapt | Standardize empty/error states |
| Responsive behavior | WordPress admin shell; narrow behavior not fully audited | 600px shell collapsed successfully | Visually reusable | Require bounded responsive QA in implementation |
| Accessibility | disclosure/button roles and live regions observed | native forms, headings, notices | Adapt | Preserve semantic controls and live feedback |
| CSS architecture | No independent shared component layer identified | no Views-specific asset layer identified | Visually reusable only | Defer extraction until repeated patterns are named |
| JS architecture | page-local AJAX/editor behavior; sortable dependency | server-rendered POST workflow | Conceptually reusable only | New namespaced View module if needed |
| Server handlers | mutate canonical tree | mutate View drafts/groups/entries | Ownership incompatible | Do not share mutation handlers |
| Data loading | Core tree/repository/compile | `CFM::get_terms()` plus View repository | Service/API reusable | Read-only discovery adapter only |
| Clone | not relevant to taxonomy editor finding | service-level View clone | Views-specific | Add browser clone later if required |
| Unsaved protection | not established | absent | Missing | Add to View workbench, not inherited blindly |

## 4. Reusable component, style, and module inventory

### Directly reusable or safe to align

- WordPress admin `wrap`/heading/table conventions.
- Native WordPress buttons, notices, form-table controls, labels, and live
  regions.
- Disclosure semantics and visible drag-handle affordance as interaction
  references.
- Spacing, hierarchy, focus, and status language where a shared token layer is
  later established.

### Reusable with adaptation

- A read-only tree-row presentation pattern, with all mutation affordances
  removed or replaced by “Add to draft”.
- A sortable list pattern, with View-owned entry/group IDs and draft-only
  persistence.
- A responsive two-column layout, with a stacked mobile mode.
- Status/notice patterns, with View validation codes and View draft state.

### Do not share directly

- Core Terms tree JSON or editor change-set state.
- `core_terms_editor_save` and related archive/reorder handlers.
- Core taxonomy mutation helpers, compiler calls, or Core Terms repository
  writes.
- Global selectors, unnamespaced JavaScript, or assumptions that a row UUID is
  a taxonomy mutation target.

## 5. Safe reuse boundaries and risks

| Boundary | Decision | Primary risk |
| --- | --- | --- |
| PHP helper for admin chrome | Possible later | helper becomes a hidden cross-plugin contract |
| Template partial | Possible for notices/buttons only | taxonomy-specific markup leaks into Views |
| Shared CSS | Defer until tokens/components are enumerated | selector leakage and Core Terms regressions |
| Shared JavaScript | Do not share editor state; adapt behavior independently | globals, route assumptions, conflicting persistence |
| Shared asset bundle | Defer | loading sortable/editor code on unrelated admin pages |
| Service/API reuse | Yes for read-only canonical discovery and View service boundaries | accidental write capability or duplicated hierarchy cache |
| Copied visual pattern | Yes when independently namespaced | divergence is acceptable to preserve ownership |
| Persistence | Separate View repository and draft/version model | published mutation or taxonomy duplication |

The target must use stable UUID references but must never copy Core Terms tree
state into a second editable taxonomy. Descendant expansion must remain a View
entry intent resolved through the platform service.

## 6. Recommended target authoring model

### Preferred: split-pane source/destination workbench

Use a desktop split pane:

1. **Source pane:** framework selector, search field, read-only expandable Core
   Terms hierarchy, term status/context, and an **Add to draft** action.
2. **Composition pane:** ordered View groups and entries, inclusion state,
   descendant toggle, display label, order, remove, and draft status.
3. **Persistent action bar:** Preview, Validate, Publish, and a clear unsaved
   state.

On narrow screens, stack Source above Composition and preserve a sticky or
clearly repeated action region.

This model fits the existing Core Terms visual language while preserving the
fundamental difference: source terms are browsed read-only, while the View
composition is edited. It supports incremental delivery and avoids the
ambiguity of a wizard when administrators need to compare source hierarchy
with selected presentation entries.

The enhanced table is a useful interim fallback for the first ticket if the
tree browser cannot be delivered safely. A wizard is not preferred because it
obscures the source/destination relationship and makes reordering and review
less direct.

## 7. Nested groups

Core Terms supports hierarchical taxonomy terms and branch movement. That does
not establish support for nested presentation groups. Durable Views currently
supports flat groups; `parent_group_id` is schema-reserved/incomplete and the
resolved model is not a nested group tree.

Nested groups are not required for the first Job Center Grade Level or Subject
Area use case. Defer them. If later authorized, define View-owned hierarchy,
ordering, validation, preview, and consumer semantics independently before
reusing any visual tree control.

## 8. Minimum implementation sequence

### A. Shared foundation — first ticket

**DV-UX001 — Define the read-only canonical term discovery seam and namespaced
workbench shell.** Document the payload, authorization, loading/error states,
and ownership boundary. Reuse WordPress admin framing and accessibility
patterns; do not extract the Core Terms editor wholesale.

### B. Core authoring workflow

**DV-UX002 — Build the split-pane draft authoring surface.** Add read-only term
browse/search, add-to-draft, group assignment, and draft entry controls.

### C. Reordering and hierarchy

**DV-UX003 — Add View-owned ordering and hierarchy-aware source browsing.**
Implement drag/reorder only against draft View entries/groups, with deterministic
keyboard fallback and no Core Terms mutation.

### D. Validation/preview/publish

**DV-UX004 — Refine draft state, diagnostics, preview, and publication actions.**
Add actionable validation, empty/error states, dirty-state protection, and
publish confirmation while preserving immutable versions.

### E. Responsive/accessibility acceptance

**DV-UX005 — Certify the workbench across authenticated desktop and narrow
states.** Require browser evidence, keyboard operation, focus order, semantic
status communication, and no regression to the Core Terms editor.

### F. Later productivity

Defer clone button, bulk operations, drag polish, nested groups, advanced search,
and broader shared design-system extraction until the core workbench proves its
boundaries.

## 9. Recommended first implementation ticket

Proceed next with **DV-UX001**, limited to the contract and shell for a
read-only canonical term discovery seam plus independently namespaced Views
workbench framing. It must not change Core Terms taxonomy behavior, Jobs
behavior, View persistence semantics, or publish/lifecycle rules.

## 10. Risks and stop conditions

Stop before implementation if:

- the authoritative Core Terms route changes or a second editor appears;
- discovery requires mutation-capable Core Terms handlers;
- the payload cannot preserve canonical UUIDs and framework identity;
- shared CSS/JS cannot be namespaced without Core Terms regression risk;
- the proposed interaction implies nested groups or another unresolved product
  policy;
- authenticated browser evidence cannot be obtained for the affected states.

## 11. Recommendation on shared admin assets

Defer broad shared admin asset extraction. Start with independently namespaced
Views markup and small, explicit visual tokens only if needed by DV-UX001. Once
two or more stable components are proven in both workbenches, extract a narrow
shared admin design layer under an explicit contract. Do not extract the Core
Terms editor's mutation JavaScript, tree persistence, or taxonomy state.

## 12. Verification record

Repositories and implementation files inspected:

- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-admin.php`
- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-service.php`
- `wordpress/wp-content/plugins/tnet-jobs/admin/class-tnet-jobs-job-categories-admin.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/class-tnet-jobs-durable-views-service.php`
- `docs/core-terms/durable-views-user-manual.md`
- `docs/core-terms/durable-views-dv018-sprint-readiness-gap-audit.md`
- `docs/core-terms/durable-views-dv013-job-center-certification.md`
- DV-023 final hopper certification report

Runtime: authenticated local DDEV WordPress at `teachers-net.ddev.site`. The
browser snapshots matched the current routes and visible implementation. No
runtime data was changed. No UI, shared component, styling, or platform code
was implemented by this ticket.
