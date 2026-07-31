# JC053 Step 1 Responsive Primitives

## Form Control with Icon

`.form-control-with-icon` is the shared trailing-icon control wrapper. It is a
relative, full-width, minimum-width-zero container. Its direct input/select
receives border-box sizing, full width, minimum width zero, and reserved
trailing padding. `.form-control-with-icon__icon` is absolutely centered at
the trailing edge, ignores pointer input, and is therefore never covered by
placeholder or control text.

## Wizard Choice Card

`.choice-grid` is the shared choice-card layout. Its parent `.panel` provides
the inline-size container, so the pattern responds to component width rather
than only to viewport width. Two equal cards with a 72px OR column remain side
by side while the panel is wider than 720px. At or below 720px, the grid
becomes one column and the OR separator becomes horizontal. This threshold
keeps embedded controls at or above the canonical 280px minimum usable width;
1147px remains a verified comfortable side-by-side case and 1024px is the first
verified stacked case in the current shell.

The pattern preserves card styling, equal visual weight, source and keyboard
order, and existing state behavior. Later wizard views should reuse these
classes rather than creating viewport-specific choice-card overrides.
