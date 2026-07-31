# Wizard Responsive Form Grid

The workbench uses a shared responsive form-grid contract for wizard forms.
Current consumers include School / Jobsite, Job Basics, Work Location, and
optional/additional field groups. The grid is governed by the containing panel
width rather than by a viewport-only patch.

Each field has `min-width: 0`; controls use `width: 100%` and
`box-sizing: border-box`; labels retain consistent vertical spacing and may
wrap naturally. The shared container query stacks the form fields when the
panel is at or below 650px, before controls become unusably narrow. Compact
fields may remain paired only above that threshold.

Future wizard sections should use the existing form-grid/field classes and
shared control contract instead of adding state-specific responsive grid rules.
Trailing-icon fields continue to use `.form-control-with-icon`.
