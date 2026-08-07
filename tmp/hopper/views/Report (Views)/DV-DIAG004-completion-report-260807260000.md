# DV-DIAG004 — Completion Report

## Status

DV-DIAG004 COMPLETE. No implementation was performed. DV-UX020 remains
unauthorized and unopened.

## Runtime

Authenticated browser: `jobman` at
https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

The active runtime served the expected Profilaxes Views workbench. Version 17
contained the disposable nested Grade Level fixture. Console inspection found
no warnings or errors.

## Measured Library geometry

The expanded Grade Level branch was inspected at viewport coordinates. All rows
used 28px height, `1px 0px` row padding, 7px gap, and an 18px/18px/minmax grid.
The label column was correctly depth-indented at 77px (L0), 95px (L1), and
113px (L2). Disclosure positions were 27px, 45px, and 63px.

| Row | Depth | Disclosure/spacer | Checkbox slot | Checkbox element | Label |
| --- | ---: | ---: | ---: | ---: | ---: |
| Grade Level | 0 | 27px | 52px bulk control | n/a | 77px |
| Early Childhood | 1 | 45px | 70px spacer | n/a | 95px |
| Early Learners | 2 | 63px | 88px spacer | n/a | 113px |
| Pre-K | 2 | 63px | expected 88px | 64px, 16px wide | 113px |
| Elementary | 1 | 45px | expected 70px | 71px, 16px wide | 95px |
| Grade 1 | 2 | 63px | expected 88px | 64px, 16px wide | 113px |
| Middle School | 1 | 45px | expected 70px | 71px, 16px wide | 95px |
| Grade 6 | 2 | 63px | expected 88px | 64px, 16px wide | 113px |

## Root cause A — checkbox alignment

The Library row grid and label indentation are correct. The checkbox slot is
not consistently occupied. Historical `.cfm-views-toggle-spacer` styling
leaves leaf disclosure spacers absolutely positioned, so the spacer does not
consume grid column 1. The leaf checkbox then auto-places into grid column 1
instead of the reserved selection column 2. This produces element positions
of 64px at L2 and 71px at L1 rather than the canonical slot centers of 88px
and 70px respectively. The slot is structurally correct for rows with a real
disclosure and for represented terms; the native checkbox element is the
misplaced grid item when the disclosure spacer is absolute.

Relevant live style provenance:

- `cfm-views-workbench-styles`: historical 5-column row defaults.
- `cfm-views-library-compact-styles`: Library flex presentation.
- `cfm-views-dv-ux012-library-final-styles`: restores Library grid and depth
  padding with `!important`.
- `cfm-views-dv-ux019-parity-styles`: shared row geometry.
- `cfm-views-dv-ux019r1-focus-styles`: currently makes selection spacers static,
  but does not make the disclosure spacer a grid participant and does not
  assign the checkbox explicitly to column 2.

## Root cause B — checkbox focus artifact

Mouse clicking the Pre-K checkbox produced:

- `document.activeElement`: the checkbox;
- `:focus`: true;
- `:focus-visible`: false;
- outline: transparent 2px;
- box-shadow: `0 0 0 2px #fff, 0 0 0 4px var(--wp-admin-theme-color)`;
- border: `1px solid rgb(30, 30, 30)`;
- appearance: none.

The winning rule is WordPress admin core, from `forms`/`colors.min.css`:
`input[type="checkbox"]:focus, input[type="radio"]:focus`. It is not a
Views-specific selector. The same rule explains the heavy blue mouse-focus
ring. A presentation-only correction can suppress this decoration for mouse
focus and restore a restrained `:focus-visible` treatment without changing
checkbox layout or behavior.

## Accepted disclosure regression check

No disclosure code was changed. Existing browser state showed expanded
indicators matching visible descendants. Prior direct-branch verification
remains intact and this diagnostic did not alter the disclosure contract.

## Smallest correction objective

Create a narrow correction ticket that:

1. makes every Library disclosure spacer a static grid item in column 1;
2. assigns every Library checkbox or selection spacer explicitly to column 2;
3. applies scoped checkbox `:focus` suppression with keyboard-only
   `:focus-visible` indication;
4. leaves disclosure JavaScript, top-level +/− placement, and all consumer
   semantics unchanged.

Do not modify this diagnostic’s worktree implementation.

## Evidence limitation

Chrome MCP captured a diagnostic screenshot but exposed only the Windows path
`C:\\home\\bobreap\\projects\\teachers-net-site\\tmp\\DV-DIAG004-checkbox-diagnostic.png`;
it was not available as a WSL-local artifact and is therefore not claimed in
the hopper.

