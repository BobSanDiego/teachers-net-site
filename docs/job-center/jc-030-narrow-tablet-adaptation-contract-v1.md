# JC-030 Narrow Portrait Tablet Adaptation Contract v1

**Status:** Canonical adaptation contract

**Scope:** JC-030 Narrow Portrait Tablet visual adaptation only

## 1. Purpose and authority

This contract defines the permitted responsive adaptation boundary for JC-030
Job Detail at narrow portrait-tablet widths. It preserves JC-030 product truth
and UX requirements while applying Responsive Layout Geometry v1 and Responsive
Advertising Strategy v1.

This contract does not approve a raster, authorize implementation, or alter an
existing desktop or mobile authority.

## 2. Viewport and architecture

- **Viewport class:** Narrow portrait tablet, `768–959px`.
- **Shell:** Fluid shell with `16px` outer insets.
- **Architecture:** Single-column tablet layout.
- **Rail:** No retained right rail. Former rail content enters the main flow
  only in the governed JC-030 sequence.
- **Reading measure:** Content remains fluid; long-form text is capped near
  `720px` where practical.

This is a distinct tablet class. It retains tablet typography, spacing, shell,
and reading measures; it does not inherit the mobile layout wholesale.

## 3. Inherited authorities

- **JC-030 Product Definition:** Canonical public job facts, truthful
  application path, employer identity, availability, save state, protected
  instructions, and external-application expectations.
- **JC-030 UX Specification:** Decision hierarchy, one primary conversion
  path, authentication continuity, save-state truth, availability treatment,
  optional-information truthfulness, employer trust, and navigation
  continuity.
- **Responsive Layout Geometry v1:** Narrow portrait-tablet class, single
  column, `16px` insets, rail-collapse rule, reading-width constraints, and
  JC-030 collapsed-content order.
- **Responsive Advertising Strategy v1:** Intrinsic advertisement inventory,
  subordination to required actions and reading content, and no-distortion or
  no-overflow constraints.

## 4. Permitted advertisement inventory

- **Main flow:** One intrinsic `468×60` reservation in the final governed
  advertisement stage.
- **Lower placement:** One intrinsic `300×250` reservation only after the
  collapsed JC-030 support content.
- **Prohibited inventory:** `728×90` and scaled, compressed, or distorted
  reservations.

Advertisements remain below required actions and reading content. They may not
appear inside narrative content or a conversion-control group, and may not
create horizontal overflow.

## 5. Governed content order

1. Primary job content, including job identity, required job facts, the one
   primary application path, secondary save engagement, and evaluation content.
2. Employer information.
3. Related Jobs.
4. Advertisement reservations: main-flow `468×60`, followed by a lower
   `300×250` reservation if present.

The contract preserves the JC-030 rail-collapse sequence: primary job content,
Employer, Related Jobs, then advertisement. Advertisement reservations are
governed physical layout regions and do not reorder primary decision or
conversion information.

## 6. Preserved behaviors

- One truthful primary application path remains available only when the job is
  publicly actionable.
- Application destination expectations remain explicit; Teachers.Net does not
  imply receipt or relay of an external application.
- Save remains authenticated-only engagement and never implies application or
  candidate status.
- Protected email and written instructions retain authentication and explicit
  reveal requirements.
- Closed, expired, and otherwise non-actionable jobs omit a live application
  action and communicate their supported availability condition.
- Employer identity, job facts, optional-information truthfulness, and
  related-job continuity remain intact.
- Return, Saved Jobs, employer-destination, and authentication continuity
  remain governed by their existing JC-030 responsibilities.

## 7. Prohibited layouts

- A two-column layout or retained `300px` right rail below `960px`.
- A desktop-style rail compressed beside a narrow main column.
- A `728×90` reservation in the narrowed primary content region.
- Replacing the distinct narrow-tablet class with the mobile layout wholesale.
- Moving advertising ahead of required conversion or reading content.
- Adding a second application path, changing application ownership, or
  implying that save or instruction reveal is an application.
- Redesigning or altering JC-030 desktop or mobile authority.

## 8. Acceptance criteria

- The adaptation is bounded to a `768–959px` viewport and uses a single-column
  tablet architecture with `16px` outer insets.
- No retained rail or two-column geometry appears below `960px`.
- Long-form reading remains fluid and is capped near `720px` where practical.
- The only permitted reservation sizes are intrinsic `468×60` main flow and,
  where present after collapsed support content, intrinsic `300×250`.
- Advertising is subordinate to required actions and reading content, with no
  compression, distortion, or horizontal overflow.
- JC-030 product facts, availability truth, one primary application path,
  save-state meaning, external-application expectations, Employer, and Related
  Jobs remain preserved.
- The resulting work is presented for separate visual review and approval;
  this contract alone does not establish responsive visual authority.
