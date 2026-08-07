# DV-UX016 — Unify Recursive Tree Rendering and Enforce Canonical Parentage

Status: COMPLETE — BROWSER VERIFIED

## Implementation

Updated `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.

- Added recursive Current View rendering from canonical Core Terms
  `parent_uuid` relationships.
- Current View now renders included terms by actual parentage, not flattened
  depth order. Version 17 verified `Kindergarten` beneath `Early Childhood`.
- Current and Library rows now share the structural `.cfm-views-term-row`
  contract and the same disclosure, selection/removal, and label slots.
- Recursion has no hard-coded depth ceiling; indentation is derived from the
  canonical term depth with a fixed 18px generation increment.
- Library and Current View state semantics remain separate.

## Browser verification

Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

The preserved version-17 fixture rendered in canonical order:

```text
Grade Level
  Early Childhood
    Early Learners
    Kindergarten
  Elementary
    Grade 1
    Grade 3
    Grade 4
```

Representative DOM evidence:

- Library row: `.cfm-views-term-row` with disclosure/spacer, selection/bulk
  slot, and `.cfm-views-term-name` label.
- Current row: `.cfm-views-term-row.cfm-views-current-term-row` with the same
  three structural slots and the same `.cfm-views-term-name` label class.
- Both use `grid-template-columns: 18px 18px minmax(0,1fr)` and `gap: 7px`.
- Both use 18px canonical depth increments.

Measured Current View positions:

| Depth | Disclosure/spacer | Selection/removal slot | Label |
| ---: | ---: | ---: | ---: |
| 0 | 27px | 52px | 77px |
| 1 | 45px | 70px | 95px |
| 2 | 63px | 88px | 113px |

Measured Library positions use the same relative progression:

| Depth | Disclosure/spacer | Selection/bulk slot | Label |
| ---: | ---: | ---: | ---: |
| 0 | 39px | 64px | 89px |
| 1 | 57px | 57px spacer | 107px |
| 2 | 75px spacer | 75px spacer | 125px |

Verified:

- Kindergarten is under Early Childhood and never under Elementary.
- sibling and descendant order follows canonical parent relationships;
- current Early Childhood collapse hid exactly Early Learners and Kindergarten,
  then re-expansion restored both;
- Library and Current View disclosure behavior remained independent;
- mouse disclosure activation left `document.activeElement` as `BODY`;
- `:focus` and `:focus-visible` were false after mouse activation;
- no persistent outline, border, or box-shadow artifact;
- no console errors or warnings.

Chrome MCP screenshot capture was attempted but timed out. No screenshot file
is claimed as locally available; DOM and computed-style evidence is recorded.

## Boundaries

No schema, Core Terms, repository contract, resolver, Jobs integration, View
Manager, lifecycle, ordering, drag/drop, or V2 capability changed. Version-17
data was not mutated.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `5f1b9ad`
- Push: successful
- Root documentation commit: pending cycle publication

The DV-UX015 human-acceptance defects concerning canonical parentage and
shared visible tree presentation are closed for the verified runtime.
