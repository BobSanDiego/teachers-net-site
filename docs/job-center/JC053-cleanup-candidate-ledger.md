# JC053 Cleanup Candidate Ledger

Audit-only ledger. No candidate below is approved for deletion by this ticket.

| Candidate | Phase | Classification | Evidence required | Risk |
|---|---|---|---|---|
| Repeated final authority topbar grid declarations | A | B/D | matched rules at all 16 widths; dropdown/drawer interactions | high |
| Compact and stacked footer ownership blocks | A | B/D | contact-sheet pixel/bounded comparison at 768/767 and all mobile widths | high |
| Repeated stepper breakpoint declarations | A | B/D | stepper state and geometry comparison at 651/650/531/530/400/320 | high |
| Repeated bottom-navigation declarations | A | B/D | disabled/ready states plus keyboard/focus proof | medium |
| Generic selectors with no current baseline match | A | E | DOM match in every implemented view, including inactive transitions | medium |
| Old responsive experiments in early stylesheet blocks | A | E | source provenance plus matched-rule and screenshot proof | high |
| Duplicate view registration or shell-render paths | B | C/E | transition trace across Step 1, Step 2, authority, and Step 3 foundation | high |
| Generated markup strings and panel cloning | B | C | wizard completion and renderer extraction design | high |
| Diagnostics/build marker code | B | E | confirm workbench-only consumers and acceptance workflow | low |

## Quantified boundary

Static CSS metrics are approximately 1,022 blocks, 2,730 declarations, 78
media conditions, 190 repeated selector strings, and 435 repeated occurrences.
No exact removable byte count is claimed. Safe-now removal is 0 bytes until a
candidate is proven at runtime. Estimated Phase A consolidation is 4–12 KB;
this is a planning range, not an implementation target.

## Ledger rule

No candidate may be staged from this ledger until its active matched rule,
source order, runtime state, and full frozen contact-sheet regression are
recorded in a later bounded cleanup ticket.
