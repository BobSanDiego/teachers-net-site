# Job Center Responsive Advertising Strategy v1

**Status:** Canonical responsive advertising governance

**Date:** 2026-07-15

## 1. Purpose and authority

This document governs responsive advertisement inventory, intrinsic dimensions,
placement hierarchy, and exceptions across the Teachers.Net Job Center.

Advertisements are reserved layout regions. Each inventory unit must fit its
actual available width; desktop inventory does not automatically scale into
tablet or mobile layouts. Screen-specific exceptions, additional sizes, and
additional placements require explicit design-governance approval.

Approved mockups remain the screen-level visual authority. Where an older
approved mockup conflicts with this dimensional strategy, implementation follows
this strategy while preserving the mockup's approved placement intent and
surrounding composition. This document does not alter approved raster records.

## 2. Desktop inventory

- **Main-flow primary:** `728 × 90`
- **Lower right-rail secondary:** `300 × 250`

Desktop placement hierarchy is:

1. primary user task;
2. supporting content; then
3. advertisement.

Advertising must not displace Apply, search controls, results, or core
job-evaluation content.

## 3. Portrait-tablet inventory

- **Main-column primary:** `468 × 60`
- **Lower retained-rail secondary:** `300 × 250`

A `728 × 90` reservation is not valid in a narrowed tablet main column while a
300px rail remains visible. It may be used only in a separately approved
full-width placement with at least 728px of genuine available width.

A retained rail claiming a `300 × 250` MPU must provide a true 300px-wide
reservation.

## 4. Mobile inventory

- **Standard in-flow reservation:** `320 × 50`
- **Optional lower post-pagination reservation:** `320 × 100`, only where
  explicitly approved

Mobile does not inherit `728 × 90` or `300 × 250` inventory by scaling.
JC-030 Mobile retains only its approved final `320 × 50` location; no
`320 × 100` placement is implied for JC-030.

## 5. Canonical placement matrix

| Device class | Main-flow reservation | Rail / lower reservation |
|---|---|---|
| Desktop | `728 × 90` after primary content | `300 × 250` after rail support |
| Portrait tablet | `468 × 60` after primary main-column content | `300 × 250` in lower retained rail |
| Mobile | `320 × 50` at explicitly approved in-flow break | `320 × 100` only where expressly approved, after pagination |

## 6. Permanent layout constraints

- Never compress or distort an advertisement reservation.
- Do not permit horizontal overflow.
- Reserve stable, visibly labeled space before inventory loads.
- Advertisements remain below required actions and reading content.
- Advertisements may not appear inside job cards, narrative paragraphs, or
  conversion-control groups.
- Mobile uses only approved mobile inventory and locations.
- Additional sizes or placements require explicit design-governance approval.
