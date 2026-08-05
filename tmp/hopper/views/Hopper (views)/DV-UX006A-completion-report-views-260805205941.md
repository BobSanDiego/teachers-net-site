# DV-UX006A — Dual-Tree Visual Stabilization

Status: Complete  
Date: 2026-08-05

## Result

The existing DV-UX006 interface is now visually stable enough for human
workflow testing. No product behavior, persistence, resolver, lifecycle, or
Jobs integration changed.

## Diagnosed causes

1. `.cfm-views-editing-context` used `position: sticky; top: 32px`, causing
   detached positioning and undesirable full-page capture behavior.
2. `.cfm-views-term-row` had five declared grid columns but six visible child
   controls. The Add control wrapped into an unintended row and collapsed to a
   narrow button.
3. At 1200px and 1024px, the source pane was too narrow for short label,
   representation status, and Add controls to share one row. The label track
   collapsed and text became unreadable.
4. Representation text was unnecessarily verbose for a compact source row.
5. Presentation Container controls had insufficient visual affordance.

The workbench grid itself was correct: measured at approximately 35/65 at all
required widths and produced no horizontal overflow.

## Corrections

Changed file:

`wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`

Selectors/owners changed:

- `.cfm-views-editing-context`: normal document flow.
- `.cfm-views-term-row`: six-column desktop layout with a minimum readable
  label track.
- Responsive `@media (max-width: 1440px)` and `@media (max-width: 1200px)`:
  short context hides, representation and Add controls stack predictably.
- `.cfm-views-representation-state`: compact “Represented” label with title
  text for full meaning.
- `.cfm-views-container-toggle`: bordered, obvious expand/collapse affordance.

## Browser verification

Canonical URL:

https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=13

Authenticated Chrome QA passed after cache-bypassed reload at 1440px, 1200px,
and 1024px:

| Width | Pane grid | Horizontal overflow | Row behavior |
|---:|---|---|---|
| 1440 | 400px / 799px | No | Compact six-column row; Add control aligned |
| 1200 | 320px / 639px | No | Representation and Add stack below label |
| 1024 | 261px / 522px | No | Same predictable stacked layout |

Additional checks:

- Normal-flow editing context confirmed.
- Current View container remains collapsed by default and expands through its
  visible control.
- Add Selected form remains present.
- Three draggable entries remain present.
- Preview link remains present.
- Validation remains valid.
- Published-version immutability copy remains present.
- Browser console: no errors.

Screenshots:

- [1440px screenshot](/home/bobreap/projects/teachers-net-site/tmp/dv-ux006a-1440.png)
- [1200px screenshot](/home/bobreap/projects/teachers-net-site/tmp/dv-ux006a-1200.png)
- [1024px screenshot](/home/bobreap/projects/teachers-net-site/tmp/dv-ux006a-1024.png)

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Profilaxes commit: `210d96f`
- Profilaxes push: successful
- Root documentation commit: pending this completion cycle
- Milestone tag: none

## Scope confirmation

No advanced selection model, shuttle redesign, Save As, Clone, repeated
placement, virtual nodes, nested containers, schema change, resolver change,
repository redesign, or Jobs change was made.
