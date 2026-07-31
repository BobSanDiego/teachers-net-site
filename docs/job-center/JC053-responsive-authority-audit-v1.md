# JC053 Responsive Authority Audit v1

Status: audit complete; responsive shell frozen; cleanup not implemented.
Date: 2026-07-31

## Frozen authority

The current rendered JC053 Wizard Authority is frozen for the reviewed
breakpoint set: 1440, 1200, 1025, 1024, 900, 768, 767, 651, 650, 531, 530,
500, 400, 375, 360, and 320px. The served authority is
`http://127.0.0.1:8768/?jc053resp014=1#wizard-authority-v1`, rendered as
`wizard-authority-v1` / Step 2 Job Basics. The July 31 contact sheet is the
current visual regression evidence; it is evidence, not a new authority.

The freeze covers desktop shell, 1200px maximum behavior, rail, navbar and
dropdown transitions, compact Job Center control, hamburger/drawer behavior,
utility controls, stepper, bottom navigation, tablet footer, and stacked mobile
footer. No cleanup recommendation below is authorized to alter those renders.

## Method and runtime evidence

Static inventory used the current `mockup.css` (124,394 bytes, 610 lines) and
`mockup.js` (59,191 bytes, 1,267 lines). External Chrome DevTools MCP was used
with cache-bypass reloads at 1200, 1024, 768, 767, 650, 530, 400, and 320px;
the frozen contact-sheet capture covered all listed widths. No horizontal
overflow was observed. The browser console contained one pre-existing form
field id/name issue and no JavaScript/page errors.

## Quantified findings

- CSS: approximately 1,022 brace blocks, 2,730 declarations, 78 media-query
  conditions, 1,100 selector occurrences, 665 distinct selector strings, 190
  repeated selector strings, and 435 repeat occurrences.
- CSS contains six `!important` declarations. They require matched-runtime
  review before removal.
- JavaScript: 81 function/arrow-function occurrences across 1,267 lines.
- The CSS inventory is intentionally approximate at declaration level because
  the file contains compressed legacy blocks and nested media-query text.
- Rough safe Phase A removal is estimated at 0–3 KB until each candidate is
  rendered-proven. Potential consolidation is estimated at 4–12 KB, not a
  promised reduction. Larger savings belong to Phase B.

## Ownership conclusions

The final responsive authority is concentrated in late CSS blocks around the
authority-scoped topbar, compact footer, mobile footer, and breakpoint-specific
navbar rules. Earlier rules remain load-bearing for shared shell defaults,
inactive views, and the current renderer. Several late blocks intentionally
override earlier experiments; source-order dependence is therefore a risk, not
proof of dead code.

Runtime checks confirmed one live application card, one topbar, one footer,
one bottom-navigation root, one stepper, and one authority marker in the
authority view. At 320px the active footer is flex/stacked, the drawer trigger
is present but closed, compact navigation links are 21.39px high, and document
scroll width equals the viewport. At 768px the footer remains grid/compact and
navigation remains full-label buttons.

## Classification summary

### A — Safe to remove now

None without a per-selector rendered proof. Text-search-only deadness is not
sufficient for this workbench because inactive views and generated markup are
part of the test surface.

### B — Safe to consolidate now

Potentially the repeated late authority declarations for footer mode ownership,
topbar grid ownership, and the final compact navigation presentation, but only
in isolated batches with contact-sheet regression. No immediate consolidation
is recommended in this audit.

### C — Must remain until wizard completion

Step 1–3 state registration, shell cloning, stepper state synchronization,
bottom-navigation generation, form behavior, authority marker synchronization,
diagnostic hooks, and inactive/placeholder view content.

### D — Requires rendered regression proof

All breakpoint media blocks, navbar/dropdown/drawer rules, footer rules,
stepper geometry, bottom navigation, selectors that use IDs or runtime data
attributes, and any declaration involving `display`, `grid-template-columns`,
`overflow`, `position`, `width`, or `min-height`.

### E — Uncertain/manual review

Repeated generic selectors, legacy scaffolding mixed with generated markup,
font-family fallbacks, and any rule that matches only a view not exercised by
the current authority capture.

## Recommendation

Declare the shell visually frozen and defer cleanup implementation to the
ordered plan. Do not remove CSS or JavaScript in this audit ticket.
