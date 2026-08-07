# DV-DIAG002 Diagnostic Report

Status: BLOCKED — REQUIRED RIGHT-PANEL QA FIXTURE UNAVAILABLE
Date: 2026-08-07

## 🚩 ENGINEERING INPUT REQUIRED 🚩

Restore an authorized local QA draft fixture at version 17 containing at least
the previously used `Grade Level → Early Childhood → Early Learners` and
`Grade Level → Elementary → Grade 1` branches, or provide a disposable local
draft with equivalent right-panel entries.

Canonical URL:
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`

Expected completion state: the right Current View contains representative L1,
L2, and L3 rows with differing disclosure and checkbox states so cross-panel
geometry can be measured. Execution can resume immediately after the fixture is
restored.

## Available authenticated evidence

Authenticated identity: `jobman`.

The current version 17 right panel contains only the `Grade Level` ancestor.
The child branch was intentionally removed during DV-FIX002 browser acceptance.
Therefore DV-DIAG002’s required right-panel representative comparison cannot
be completed without changing or restoring QA data. No fixture was fabricated
and no application/data change was made in this diagnostic.

## Left-panel measurements

The live Library was expanded through the existing control. Representative
geometry:

| Term | Depth | Disclosure | Bulk | Checkbox | Label left | Padding | Grid |
| --- | ---: | --- | --- | --- | ---: | ---: | --- |
| Grade Level | 0 | yes | yes | no | 89px | 0px | 18px 18px 240px |
| Early Childhood | 1 | yes | no | yes | 107px | 18px | 18px 18px 222px |
| Early Learners | 2 | no | no | yes | 125px | 36px | 18px 18px 204px |
| Kindergarten | 2 | no | no | yes | 125px | 36px | 18px 18px 204px |
| Subject Area | 0 | yes | yes | no | 89px | 0px | 18px 18px 240px |
| English Language Arts | 1 | no | no | yes | 107px | 18px | 18px 18px 222px |
| Mathematics | 1 | yes | no | yes | 107px | 18px | 18px 18px 222px |

The current left renderer is a sibling `.cfm-views-term-row` grid inside
`.cfm-views-term-node`, using `data-depth` and depth-specific custom-property
rules. Control columns are reserved. Current left measurements show 18px
generation increments and no left-panel drift in this runtime.

## Proven cross-panel divergence

The available right `Grade Level` row is a
`.cfm-views-current-term-row` inside `.cfm-views-group-entries` and computes:

- display: `flex`;
- padding: `6px`;
- gap: `6px`;
- inline `margin-left: 0px`;
- label left: `68px`;
- checkbox left: `33px`, width `25px`.

It does not share the Library’s grid columns, depth padding, or DOM structure.
The Current View renderer uses inline `margin-left: depth * 24px` and places
the disclosure button conditionally before a label. This is the exact renderer
divergence that can produce control-column drift once L1/L2 rows are present.

## Focus artifact

The disclosure control is a `BUTTON.button-link.cfm-views-toggle`. After a
mouse click in the current runtime, the element is not focused and computed
outline is `none`; no persistent square was reproduced. The final CSS includes
`text-decoration:none!important`, but the cross-panel fixture blocker prevents
testing the full focus sequence across representative branches.

## Runtime/asset identity

- Runtime: `https://teachers-net.ddev.site`.
- Authenticated user: `jobman`.
- Current page includes the expected final selectors:
  `cfm-views-ux012-library-final-styles`, `cfm-views-dv-ux014-bulk-presentation`,
  and `cfm-views-dv-fix001-removal-script`.
- No console warnings or errors were reported.
- No alternate plugin copy or application asset was changed.

## Recommended correction

After the QA fixture is restored, issue one bounded shared cross-panel
correction ticket. The smallest proven direction is to give Current View the
same three structural columns and depth-derived label position as Library,
while retaining its own checkbox/removal semantics. Do not copy Library
behavior wholesale. Re-test focus with `:focus-visible` only.

No implementation was performed. Diagnostic is blocked pending the right-panel
fixture and fresh screenshots.
