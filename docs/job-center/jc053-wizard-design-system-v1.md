# JC053 Wizard Design System v1

**Status:** Canonical JC053 Employer Wizard UI authority

**Scope:** Shared wizard shell, navigation, controls, responsive primitives, and
choice-card patterns for the isolated JC053 workbench.

This document governs future JC053 implementation and responsive verification.
Future tickets must reuse these primitives rather than restating or inventing
step-specific patterns. It supplements the broader Job Center Design System at
`docs/job-center/job-center-design-system-v1.md`.

## Authority rules

- One shared wizard shell owns navbar, rail, workspace, footer, responsive shell,
  1200px layout, and breakpoint transitions.
- New steps inherit the shared shell and primitives before introducing unique
  presentation.
- Step-specific CSS is allowed only for genuinely unique content.
- Responsive behavior is component-driven where practical.

## Canonical primitives

### Stepper

The stepper is a progress indicator, not navigation. Bottom Previous/Next
controls perform navigation. Five indicators remain visible at every width.
From 768px through 401px, the active label remains beneath the current ball
with sufficient outer padding to prevent clipping. At 400px and below,
individual labels are hidden and the centered status block shows `Step X of 5`
and the current step name while all five balls remain visible.

### Bottom navigation

Previous is on the left and Next is on the right. The shared component is used
by every step; compact link presentation is permitted at narrow widths.

### Form Control with Trailing Icon

Use `.form-control-with-icon` for search fields, searchable selects, and future
icon-bearing controls. The wrapper is relative and full width; its control uses
`width: 100%`, `min-width: 0`, and `box-sizing: border-box`, with reserved
trailing padding and an SVG icon positioned at the trailing edge. No text may
render beneath the icon.

### Wizard Responsive Form Grid

Use the shared responsive form-grid primitive for School / Jobsite, Job Basics,
Work Location, and future wizard form sections. It is driven by available
component width, not viewport-only rules. Fields remain multi-column only while
their minimum usable width is satisfied, then stack at the shared container
threshold. Controls remain full width, minimum-width zero, and border-box; labels
may wrap naturally without distorting rows.

### Choice Card

Choice cards preserve equal visual weight, source and keyboard order, and
semantics. Paired cards stack based on component width rather than a
step-specific viewport patch.

## Responsive verification

Use the OPS-RESP004 workflow. Implementation tickets perform targeted breakpoint
checks; verification tickets use representative views when shared inheritance
changes. Contact sheets are milestone evidence, not a substitute for targeted
rendered checks.

## New primitive checklist

Before adding CSS or JavaScript, ask whether an existing primitive already
solves the problem, whether it can be extended safely, whether another step will
need it, and whether the pattern should be promoted here. Future tickets should
say, for example, “Reuse the Wizard Responsive Form Grid defined in JC053
Wizard Design System v1.”
