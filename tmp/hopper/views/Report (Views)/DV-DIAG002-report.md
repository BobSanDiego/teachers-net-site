# DV-DIAG002 — Cross-Panel Tree Alignment and Control-Column Drift Diagnostic

Status: COMPLETE — DIAGNOSTIC ONLY

Date: 2026-08-07

## Runtime and fixture

Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

The authenticated browser rendered the restored version-17 fixture:

- Grade Level (depth 0)
  - Early Childhood (depth 1)
    - Early Learners (depth 2)
  - Elementary (depth 1)
    - Grade 1 (depth 2)

Direct database inspection confirmed five version-17 rows, IDs 50–54, in the
`teachers-net` framework. No data was modified.

## Current View measurements

The right panel uses `.cfm-views-current-term-row` with `display:flex`,
`gap:6px`, `padding:2px 6px`, and inline `margin-left: depth * 24px`.

| Term | Depth | Row left | Disclosure/spacer left | Checkbox left | Label left | Inline margin | Width |
| --- | ---: | ---: | ---: | ---: | ---: | ---: | ---: |
| Grade Level | 0 | 27px | 33px | 57px | 57px | 0px | 429px |
| Early Childhood | 1 | 51px | 57px | 81px | 81px | 24px | 405px |
| Early Learners | 2 | 75px | 81px | 81px | 81px | 48px | 381px |
| Elementary | 1 | 51px | 57px | 81px | 81px | 24px | 405px |
| Grade 1 | 2 | 75px | 81px | 81px | 81px | 48px | 381px |

At depth 2, the disclosure spacer and checkbox occupy the same left position
(81px). The depth margin shifts the entire row, but the checkbox does not gain
a reserved depth-independent column.

## Library comparison

The left panel uses `.cfm-views-source .cfm-views-term-row` with a reserved
three-column grid and depth padding:

- `display:grid`
- `grid-template-columns:18px 18px minmax(0,1fr)`
- `gap:7px`
- `padding-left: depth * 18px`

Measured Library positions:

| Term | Depth | Disclosure left | Selection/bulk column left | Label left | Grid |
| --- | ---: | ---: | ---: | ---: | --- |
| Grade Level | 0 | 39px | 64px | 89px | 18px 18px 340px |
| Early Childhood | 1 | 57px | 57px spacer | 107px | 18px 18px 322px |
| Early Learners | 2 | 75px spacer | 75px spacer | 125px | 18px 18px 304px |
| Elementary | 1 | 57px | 57px spacer | 107px | 18px 18px 322px |
| Grade 1 | 2 | 75px spacer | 75px spacer | 125px | 18px 18px 304px |

The Library preserves both structural columns at every depth. Current View
does not: it conditionally emits a disclosure button/spacer, then places the
checkbox in the same flex flow as the label.

## Same-depth invariants

- Library disclosure controls align by depth: 39px, 57px, 75px.
- Library labels align by depth: 89px, 107px, 125px.
- Current View disclosure controls align by depth: 33px, 57px, 81px.
- Current View labels do not preserve a separate depth column: depth-1 labels
  start at 81px and depth-2 labels also start at 81px.
- Current View checkboxes violate the expected depth progression: depth-1 and
  depth-2 checkboxes both start at 81px.

## Root causes

1. Current View is a separate renderer in `admin/class-cfm-views-admin.php`.
   It emits `.cfm-views-current-term-row` as a flex row and applies inline
   `margin-left`, rather than using the Library’s reserved structural grid.
2. Disclosure controls are conditional buttons/spacers, but the checkbox is
   not a reserved second structural column. At depth 2 the spacer and checkbox
   therefore share the same x-position.
3. The Library’s depth is represented by `padding-left` and grid columns;
   Current View’s depth is represented by row-level inline margin. These are
   structurally different positioning models.

## Focus artifact

The disclosure control is `.cfm-views-current-toggle` and the Library control
is `.cfm-views-toggle`, both rendered as `button.button-link`. After a browser
click on the Current View disclosure control:

- `document.activeElement` was `BODY`;
- `:focus` was false;
- `:focus-visible` was false;
- computed outline was `rgb(56, 88, 233) none 3px`;
- border was `0px none`;
- box shadow was `none`.

The artifact was not reproduced as a persistent focus ring. The winning
selector is the inline style block `#cfm-views-dv-ux011-styles`, which sets
`.cfm-views-current-toggle,.cfm-views-current-toggle-spacer` to width 18px,
padding 0, border 0, and `text-decoration:none!important`. The Library’s
corresponding disclosure rule is in `#cfm-views-library-compact-styles`.

## Runtime/source identity

- DDEV project: `teachers-net`
- WordPress docroot: `/var/www/html/wordpress`
- Active plugin mount: `/var/www/html/wordpress/wp-content/plugins/profilaxes`
- Host source: `/home/bobreap/projects/teachers-net-site/wordpress/wp-content/plugins/profilaxes`
- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `e02e7e67846c367ecb8c59c710c449c0d285e391`
- Database: MariaDB service `db`, database `db`
- Authenticated user: `jobman`
- Document request: HTTP 200
- Console: no errors or warnings

The served runtime and mounted source match. No stale plugin copy, alternate
DDEV project, or alternate asset was found.

## Correction recommendation

The smallest coherent correction is for Current View to adopt the Library’s
reserved structural-column model: disclosure column, checkbox/removal column,
then label, with depth-derived padding or equivalent depth-aware grid behavior.
Retain Current View’s distinct removal semantics and ancestor strike-through
behavior. Do not copy Library selection behavior wholesale.

The next correction objective is therefore a bounded Current View structural
renderer alignment ticket. This diagnostic did not implement it.

## Evidence limitation

The browser screenshot capture succeeded and reported:
`C:\\home\\bobreap\\projects\\teachers-net-site\\tmp\\DV-DIAG002-current.png`.
The browser tool did not expose that Windows-reported path to the WSL
filesystem, so no local PNG could be copied into the hopper. DOM measurements,
computed styles, database rows, runtime identity, network status, and console
results are preserved here.
