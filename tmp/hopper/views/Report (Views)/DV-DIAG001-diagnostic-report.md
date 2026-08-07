# DV-DIAG001 Diagnostic Report

Status: DIAGNOSTIC COMPLETE — NO IMPLEMENTATION
Date: 2026-08-07

## Canonical evidence

Authenticated identity: `jobman`

Canonical URL:
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`

No application code, database row, or request was changed. The orphan sequence
was reproduced at the DOM/FormData boundary only; the destructive Remove
Selected submit was intentionally not sent.

## Finding 1 — Library alignment

On the current live implementation after DV-UX014, the representative expanded
rows measured as follows:

| Term | Depth | Disclosure | Bulk | Checkbox | Label left |
| --- | ---: | --- | --- | --- | ---: |
| Grade Level | 0 | yes | yes | no | 89px |
| Early Childhood | 1 | yes | no | yes | 107px |
| Early Learners | 2 | no | no | yes | 125px |
| Kindergarten | 2 | no | no | yes | 125px |
| Elementary | 1 | yes | no | no | 107px |
| Grade 1 | 2 | no | no | no | 125px |

Each row is a sibling `.cfm-views-term-row` inside a `.cfm-views-term-node`,
with `data-depth` and `data-parent` attributes. The winning final CSS uses a
three-column grid (`18px 18px minmax(0, 1fr)`) and depth padding of 18px per
generation. The disclosure, bulk, and checkbox columns are reserved, including
when controls are absent. This produces fixed 18px generation increments in
the current browser evidence.

The source contains multiple historical style blocks. Earlier flex/grid rules
and the original hidden-row selector are superseded by the later
`cfm-views-dv-ux012-library-final-styles` block. Before that final override,
the cascade could allow control presence and hidden-row rules to affect layout;
DV-UX014 added the important hidden-row rule. No alignment defect was
reproduced in the current authenticated runtime. If QA still sees a shift, the
next inspection should compare the served asset/cache version against commit
`4c56e20` before changing layout code.

## Finding 2 — Current View removal state

Current View rows are rendered in `render_draft_editor()` as
`.cfm-views-current-term-row` elements with `data-uuid`, `data-parent`, and
`data-depth`. Each row has an independent checkbox named `entry_ids[]`.

The event path is:

1. `cfm-views-removal-script` listens for `change` on
   `[data-cfm-views-entry-select]`.
2. It only counts checked boxes, enables Remove Selected, and updates status.
3. It does not inspect `data-parent`, discover descendants, or mark child
   checkboxes inherited/muted.
4. The form submits only checked `entry_ids[]` values.
5. The admin controller passes those IDs directly to
   `CFM_Views_Repository::delete_entries()`.
6. `delete_entries()` loops over supplied numeric IDs and deletes only those
   rows from `cfm_view_entries`; it performs no descendant closure.

Authenticated FormData evidence for parent `Early Childhood` entry ID `18`
and child `Grade 1` entry ID `19`:

- parent checked: `true`;
- descendant checked: `false`;
- payload: `entry_ids[]=18`;
- Remove Selected: enabled.

This proves the invariant is missing at the UI selection model, request
payload, and repository enforcement layers. The controller is a transparent
pass-through and therefore also permits the invalid state.

## Exact root cause

The Current View removal model treats every entry checkbox as independent and
uses entry IDs rather than canonical UUID ancestry to construct removal. No
client-side descendant closure exists, and no repository-side closure protects
the persistence boundary. Removing a parent can therefore leave included
descendants behind.

## Recommended correction sequence

### DV-FIX001 — Current View descendant removal selection

Client-side only for interaction: when a parent is checked, discover included
descendants by `data-parent`, check them as inherited/muted, and include all
descendant entry IDs in the removal form. Unchecking the parent should clear
the inherited descendant state while preserving explicitly selected unrelated
entries according to the finalized V1 interaction contract.

### DV-FIX002 — Repository descendant-removal invariant

Repository boundary: before deletion, resolve the selected entries' canonical
UUID descendants within the same version/framework and delete the closure.
Reject or safely normalize any request that would leave an included descendant
without its selected ancestor. This is required even if DV-FIX001 is complete.

The two corrections should be separate tickets but delivered consecutively;
DV-FIX002 is the durable safety boundary.

## Console and evidence

The authenticated browser reported no warning or error console messages.

Evidence screenshots:

- `DV-DIAG001-alignment-current.png` — expanded Library measurements/state.
- `DV-DIAG001-parent-only-removal-pending.png` — parent selected while child
  remains unchecked.

Diagnostic stop boundary honored. No fix was implemented.
