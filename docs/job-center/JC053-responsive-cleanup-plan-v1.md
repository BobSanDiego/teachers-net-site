# JC053 Responsive Cleanup Plan v1

Status: proposed plan only. The responsive shell is frozen; this ticket does
not implement the plan.

## Batch 1 — Proven dead CSS only

Objective: remove only selectors proven to match no DOM in every implemented
view and no generated/inactive test state.

Expected files: `tmp/jc053-wizard-workbench/mockup.css` and a focused audit
evidence package.

Scope: no geometry, display, overflow, breakpoint, typography, or state rules.
Verification: matched-selector inventory, all frozen widths, all implemented
views, diff check, and bounded screenshot comparison.

Risk: low to medium. Rollback: revert the single batch commit.
Estimated reduction: 0–3 KB.

## Batch 2 — Duplicate responsive authority consolidation

Objective: consolidate one ownership family at a time for topbar, footer,
stepper, or bottom navigation without changing the cascade result.

Expected files: `mockup.css` only, with no HTML or JS changes.

Scope: preserve final computed values and source-order-independent ownership;
one family per commit.

Verification: computed matched rules and screenshots at all frozen widths,
dropdown/drawer interactions, footer modes, stepper states, bottom navigation,
overflow, console/page errors, and accessibility checks.

Risk: medium to high. Rollback: revert the family commit immediately if any
rendered difference appears.
Estimated reduction: 4–12 KB across multiple batches.

## Batch 3 — Safe responsive JavaScript cleanup

Objective: remove or consolidate only obsolete responsive branches proven
unreachable through runtime transition traces.

Expected files: `mockup.js`, potentially with focused documentation updates.

Scope: no renderer redesign, no Step 3–5 extraction, no state vocabulary or
DOM-semantic change.

Verification: direct load and in-page transitions for authority, Step 1 views,
Step 2 incomplete/ready, Step 3 foundation, dropdown/drawer, keyboard focus,
all frozen widths, and contact-sheet comparison.

Risk: high. Rollback: one isolated commit per branch family; stop after the
first unexplained DOM or screenshot difference.
Estimated reduction: 0 now; 6–16 KB deferred until wizard completion.

## Regression contract

Every cleanup batch must compare against the frozen contact sheet at all
approved widths. Anti-aliasing and font-rendering noise are acceptable only
when there is no layout-edge movement, changed wrapping, altered control
height, changed visibility/order, or semantic DOM change. The gate requires no
horizontal overflow, no JavaScript/page errors, working navbar dropdowns and
mobile drawer, stable stepper and bottom navigation, correct tablet/stacked
footer modes, and keyboard/focus accessibility checks.

## Stop boundary

Stop and issue a diagnostic ticket if a matched owner is uncertain, a rendered
symptom is unchanged, a cleanup needs specificity escalation, a source-order
accident is discovered, or any cleanup changes frozen pixels/layout. Do not
begin Phase B until Steps 3–5 are complete and the renderer architecture is
explicitly ready for extraction.
