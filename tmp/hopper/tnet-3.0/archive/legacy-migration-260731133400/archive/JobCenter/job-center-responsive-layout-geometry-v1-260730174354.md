# Job Center Responsive Layout Geometry v1

**Status:** Canonical responsive layout governance

**Date:** 2026-07-15

## 1. Purpose and authority

This document governs responsive layout classes, breakpoint ranges, shell and
inset geometry, column eligibility, main-column minimums, right-rail width,
column gaps, rail-collapse behavior, reading-width constraints, and the
physical fit of advertising reservations across the Teachers.Net Job Center.

Approved mockups remain screen-level visual authorities. Implementation must
preserve their approved visual intent while respecting the physical geometry
codified here. This document does not alter approved raster history or approve
implementation.

`docs/job-center/job-center-responsive-advertising-strategy-v1.md` is the
companion authority for responsive advertising inventory, placement hierarchy,
and approved exceptions.

## 2. Canonical responsive layout classes

| Class | Viewport range | Shell / insets | Main column | Rail | Gap | Layout |
|---|---:|---|---|---|---:|---|
| Desktop | `≥1200px` | `1200px` max shell; `32px` outer inset | `804px` at max shell; readable text capped within it | Fixed `300px`, visible | `32px` | Two columns |
| Wide portrait tablet | `960–1199px` | Fluid shell; `16px` outer inset | Minimum `600px`; `604px` at `960px` viewport | Fixed `300px`, visible | `24px` | Two columns |
| Narrow portrait tablet | `768–959px` | Fluid shell; `16px` outer inset | Full-width single column | Collapsed below main content | — | Single-column tablet |
| Mobile | `<768px` | Fluid shell; `16px` outer inset | Full-width single column | Not persistent; governed support content stacks | — | Mobile layout |

Landscape tablet follows Wide Portrait Tablet rules when its viewport is at
least `960px`; otherwise it follows Narrow Portrait Tablet rules.

## 3. Two-column eligibility rule

A true retained `300px` right rail requires all of:

- viewport width of at least `960px`;
- `16px` minimum outer inset on both sides;
- fixed `300px` rail;
- `24px` column gap; and
- main column of at least `600px`.

Physical minimum:

`16 + 600 + 24 + 300 + 16 = 956px`

`960px` is the practical breakpoint floor. A two-column layout is not permitted
when the main column would fall below `600px`.

## 4. Rail-collapse rule

Below `960px`, the rail collapses. Narrow portrait tablet uses a distinct
single-column tablet layout: it does not inherit the mobile layout wholesale,
and retains tablet typography, spacing, shell, and reading measures. Former
rail content moves below primary content in the governed sequence.

### Finder/Search

1. Primary content
2. Pagination
3. Main-flow advertisement
4. Account
5. Browse
6. Employer
7. `300×250` advertisement
8. Community

### JC-030 Job Detail

1. Primary job content
2. Employer
3. Related Jobs
4. Advertisement

Screen-specific approved exceptions continue to govern where documented.

## 5. Reading-width rules

- **Two-column long-form content:** minimum `600px`; preferred reading measure
  `640–720px`.
- **Two-column listing content:** minimum `600px`; preferred range
  `640–800px`.
- **Single-column narrow-tablet content:** fluid, with long-form text capped
  near `720px` where practical.
- **Mobile:** fluid within `16px` insets; preserve a minimum practical content
  width of `320px`. Do not reduce typography or control sizing to force
  narrower layouts.

## 6. Advertising-fit implications

These are physical layout constraints. The Responsive Advertising Strategy v1
contains the full inventory and placement authority.

| Layout class | Main flow | Rail / lower placement |
|---|---|---|
| Desktop | `728×90` | `300×250` rail |
| Wide portrait tablet | `468×60` | True `300×250` rail |
| Narrow portrait tablet | `468×60` | `300×250` only after collapsed support content at intrinsic width |
| Mobile | `320×50` | `320×100` only where explicitly approved |

- A `728×90` banner cannot occupy a narrowed main column beside a retained
  `300px` rail.
- Advertisement reservations may not be compressed or distorted.
- A fixed `320px` mobile creative requires at least a `352px` viewport with
  `16px` side insets.
- Horizontal overflow is prohibited.

## 7. Existing-authority reconciliation

- JC-010 and JC-011 Tablet remain valid Wide Portrait Tablet reference
  authorities at approximately `962–963px`.
- Their apparent `728×90` main-column reservations require implementation
  reconciliation to `468×60` when the `300px` rail remains visible.
- JC-014 and JC-015 Tablet inherit the same wide-tablet shell geometry.
- No approved authority establishes a two-column layout below `960px`.
- Narrow portrait tablet behavior is governed by this document rather than
  inferred from wide-tablet mockups.
- The current unapproved JC-030 tablet candidate at approximately `864px` is
  physically unsupported as a two-column layout and must be replaced with a
  narrow-tablet single-column adaptation.
- Existing approved tablet authorities remain valid; discrepancies are Patch
  Mode or implementation-reconciliation items only.

## 8. Authority hierarchy

1. Product definition and UX specification
2. Screen-level approved visual authority
3. Responsive Layout Geometry v1
4. Responsive Advertising Strategy v1
5. Shared Responsive Decisions v1
6. Implementation notes and Patch Mode corrections

Where a raster appears to depict impossible geometry, implementation must
preserve approved visual intent while following this canonical physical layout
geometry. Approved raster history must not be silently altered.
