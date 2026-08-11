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
At 768px and above, all five indicators retain their labels beneath the balls.
At 767px and below, every per-ball label is hidden and one centered status
block shows `Step X of 5` and the current step name while all five balls remain
visible. There is no intermediate compact-label range.

### Bottom navigation

Previous is on the left and Next is on the right. The shared component is used
by every step; compact link presentation is permitted at narrow widths.

### Responsive overflow menu

Anchored overflow menus at 531px and above do not display an explicit close
control. The explicit close control belongs only to the fixed mobile drawer
mode at 530px and below; trigger toggle, item selection, outside click, Escape,
and existing focus-departure behavior remain the dismissal mechanisms for the
anchored menu.

### Form Control with Trailing Icon

Use `.form-control-with-icon` for search fields, searchable selects, and future
icon-bearing controls. The wrapper is relative and full width; its control uses
`width: 100%`, `min-width: 0`, and `box-sizing: border-box`, with reserved
trailing padding and an SVG icon positioned at the trailing edge. No text may
render beneath the icon.

### School / Jobsite Selector Label Rule

Compact school/jobsite selectors display `display_name` when present and fall
back to `full_name` only when no display name is available. `full_name` remains
the canonical record identity. Full names are appropriate in detail, summary,
review, administrative, tooltip, title, and accessible-context surfaces where
complete identity is needed.

Do not introduce responsive breakpoints to swap labels, and do not solve long
selected values with font reduction, icon compression, or arbitrary clipping.
The existing display-name field exists to provide a concise human-readable
identity for constrained choice controls. Later implementation work must reuse
that field, preserve the full-name fallback, and verify Initial, School
Selected, Return, and later school/jobsite chooser consumers.

### Wizard Responsive Form Grid

Use the shared responsive form-grid primitive for School / Jobsite, Job Basics,
Work Location, and future wizard form sections. It is driven by available
component width, not viewport-only rules. Fields remain multi-column only while
their minimum usable width is satisfied, then stack at the shared container
threshold. Controls remain full width, minimum-width zero, and border-box; labels
may wrap naturally without distorting rows.

For Choice Cards, an embedded search/select control must not fall below the
documented 280px control width. This includes the trailing-icon reservation and
leaves approximately 242px for readable control text. The canonical Choice Card
group therefore stacks when its containing panel is 720px wide or narrower;
1147px remains a verified comfortable side-by-side case while 1024px is the
first verified stacked case in the current shell.

Selects using the trailing-icon primitive own exactly one SVG chevron. Native
select indicators and legacy background-image chevrons are suppressed while
native keyboard and selection behavior remain intact.

### Choice Card

Choice cards preserve equal visual weight, source and keyboard order, and
semantics. Paired cards stack based on component width rather than a
step-specific viewport patch.

## Responsive verification

Use the OPS-RESP004 workflow. Implementation tickets perform targeted breakpoint
checks; verification tickets use representative views when shared inheritance
changes. Contact sheets are milestone evidence, not a substitute for targeted
rendered checks.

### Page-head action and compact-stepper rules

At 768px and above, page-head actions use the desktop heading composition. From
767px through 501px, the compact shell keeps Cancel and Save Draft, when
present, at the top right with normal button dimensions. At 500px and below,
actions move beneath the heading into the existing mobile action row, with two
actions side by side when both are present and a coherent single-action layout
otherwise. The compact stepper-to-panel gap is intentionally tighter below
768px; its shared bottom margin is 42px.

### Responsive shell freeze candidate

Following JC053-WIZARD-RESP011 verification, the recommendation is:

**JC053 Responsive Wizard Shell v1 — Frozen**

This freeze applies to the shared shell, responsive breakpoints, page-head
composition, compact stepper, shared navigation, footer, and responsive
spacing primitives. Future changes to these areas require an explicit
exception ticket.

### Step 3 authoring workspace exception

Step 3 is an authoring workspace rather than a generic multi-field form. At
wide desktop it may collapse the employer rail to a narrow accessible strip
with a session-persistent expand/collapse control and use a two-pane authoring
and Listing Preview composition. At compact widths the persistent preview is
hidden and the authoring pane uses the frozen single-pane shell. Only Job
Description exposes a visible formatting toolbar. Requirements / Qualifications
and optional sections remain rich-paste capable without persistent toolbars.
Short Summary follows Job Description and uses deterministic, non-AI summary
assistance only when the user continues without a summary. Step 5 remains the
canonical full preview surface.

### Step 3 authoring principles

Step 3 is paste-first and disclosure-driven. Employers primarily bring an
existing formatted job description into the wizard; pasted formatting is
preserved automatically and visible editor controls remain minimal. It should
not resemble a word processor.

The immediate required narrative field is Job Description. Short Summary is
recommended in the authoring UI; if the user continues without a sufficient
summary, the deterministic summary review/gate runs before Step 4. Optional
Fields include Requirements / Qualifications (recommended for matching),
Responsibilities, Preferred Qualifications, About Our School, and Benefits.
Listing Preview is incremental: it renders only populated content and suppresses
empty headings, sections, and containers.

Benefits is the reusable compact-selector pattern: category headings, inline
clickable benefit names, selected-state highlighting, an always-visible
selected summary, and a progressive Additional Benefits field. Its empty state
teaches the interaction with `Benefits offered: Click any benefit to add or
remove it.` The guidance disappears after selection. Checkbox grids, large tag
pickers, dense matrices, permanent explanatory copy, and bright instructional
text are not the approved direction.

When a new control should behave like an existing control, reuse or clone the
existing component structure and change only content. After repeated
implementation failure, stop speculative changes and diagnose rendered DOM,
computed CSS, and active rule ownership before another attempt.

## New primitive checklist

Before adding CSS or JavaScript, ask whether an existing primitive already
solves the problem, whether it can be extended safely, whether another step will
need it, and whether the pattern should be promoted here. Future tickets should
say, for example, “Reuse the Wizard Responsive Form Grid defined in JC053
Wizard Design System v1.”
