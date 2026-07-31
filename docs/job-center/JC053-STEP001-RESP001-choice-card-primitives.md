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
by side while the panel is wider than 650px. At or below 650px, the grid
becomes one column and the OR separator becomes horizontal. This threshold
prevents the existing-school card and its controls from being compressed below
a usable width while preserving the approved side-by-side composition at the
1024px representative width and making 932px the first tested stacked view.

The pattern preserves card styling, equal visual weight, source and keyboard
order, and existing state behavior. Later wizard views should reuse these
classes rather than creating viewport-specific choice-card overrides.
