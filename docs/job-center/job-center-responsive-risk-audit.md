# Job Center Responsive Risk & Adaptation Audit

**Ticket:** RESP-AUDIT001

**Date:** 2026-07-13

**Scope:** Approved desktop authorities JC-010, JC-011, JC-014, JC-015, and JC-030

**Status:** Audit only; no responsive specification, visual authority, or implementation authority

## 1. Executive Findings

The approved desktop suite is coherent, but it does not determine responsive
behavior. All five screens depend on the same constrained shell, navigation,
content/rail relationship, listing language, advertisements, pagination, and
footer. Governing those shared elements once is the largest opportunity to
avoid screen-by-screen duplication.

Overall responsive risk is **High**. The principal risk is not basic width
reduction; the current implementation already supplies useful foundations such
as a `1200px` maximum canvas, `16px` narrow gutters, grid collapse, wrapping,
and horizontal-overflow controls. The unresolved issues are product-order and
interaction decisions that desktop rasters cannot answer:

- the responsive navigation mechanism and account-action treatment;
- the breakpoint and exact order used when a right rail becomes a support
  region;
- the responsive composition of integrated search and expanded refinement;
- the modal's small-viewport, focus, keyboard, and vertical-overflow behavior;
- the order and prominence of Job Detail application actions;
- mobile advertising substitution, reservation, or omission; and
- pagination, footer, and secondary-card density at narrow widths.

The existing CSS is evidence of implementation capacity, not responsive visual
authority. Earlier tablet/mobile captures listed in the Visual Manifest remain
Draft or Unclassified and must not be promoted by inference. Responsive work
should therefore begin with shared governance, then state-specific authority,
then an implementation audit.

## 2. Responsive Principles

The following principles are already governed and apply to every adaptation:

1. The approved desktop artifact remains fixed; responsive work adapts it and
   does not reinterpret its product purpose, content, or hierarchy.
2. The shell remains constrained to the Job Center canvas. Below that canvas it
   may fill the viewport, reaching a `16px` internal gutter at tablet/mobile.
3. Horizontal page overflow is prohibited. Controls wrap or stack before text
   or targets become cramped.
4. Primary content precedes support content in reading and focus order.
5. Listing entries remain narrative-first directory entries. Their compact
   salary, distance, and Save zone stays grouped when reflowed.
6. Required result, status, application, employer, and lifecycle truth remains
   available at every supported width.
7. Progressive disclosure remains state-driven: adaptations expose only the
   minimum interface needed for the active interaction.
8. Keyboard order, visible focus, labels, current state, and usable target size
   must survive reflow.
9. Advertisements remain intentionally labeled and reserved without causing
   overflow or displacing the primary task unpredictably.
10. A desktop raster cannot decide unseen breakpoints, collapse mechanisms,
    content order, or mobile visibility. Those require named responsive
    authority.

## 3. Shared Components

| Component | Desktop acceptable? | Tablet impact | Mobile impact | Shared adaptation possible? | Requires separate authority? |
| --- | --- | --- | --- | --- | --- |
| Canvas and gutters | Yes; governed at 1200px/32px | Low | Low | Yes | Low; token behavior is already governed |
| Navbar and account actions | Yes | High | High | Yes | High; collapse mechanism is unresolved |
| Breadcrumbs and back links | Yes | Medium | Medium | Yes | Medium; wrapping is governed, truncation and paired-action behavior are not |
| Hero/page heading | Yes per screen | Medium | Medium | Partly | Medium; Finder and Detail differ intentionally |
| Integrated search control | Yes in JC-010/014/015 | High | High | Yes | High; field/action stacking and disclosure placement are unresolved |
| Expanded refinement panel | Yes in JC-011 | High | High | Partly | High; compact expansion and action order need state-specific authority |
| Browse disclosure/cards | Yes in JC-015 | Medium | High | Partly | High; card reflow and disclosure relationship are unseen |
| Canonical listing | Yes | Medium | High | Yes | Medium; narrative-first reflow is governed but exact compact-zone placement is not |
| Result count, sort, chips | Yes in JC-011 | Medium | High | Yes | Medium; wrapping, scrolling, and control order need approval |
| Pagination | Yes | Medium | High | Yes | Medium; narrow count and next/previous treatment are unresolved |
| Right-rail cards | Yes per screen | High | High | Yes structurally | High; breakpoint, order, collapse, and visibility are unresolved |
| 300 x 250 advertisement | Yes | Medium | Medium | Yes | High; mobile placement policy is unresolved |
| 728 x 90 leaderboard | Yes | High | High | Yes | High; the desktop creative cannot simply scale to mobile |
| Footer | Yes | Medium | High | Yes | High; exact stacked order and mobile composition are unresolved |
| Modal shell and tabs | Yes in JC-014 | High | High | Partly | High; requires state-specific responsive authority |
| Job Detail application actions | Yes in JC-030 | High | High | Partly | High; responsive priority and duplication behavior are product-critical |

Shared specification can govern the canvas, gutters, navigation contract,
focus/order rules, listing reflow, rail transition, advertising policy,
pagination, and footer. Search-state panels, the location modal, Browse Reveal,
and Job Detail still require state-specific responsive authority because their
critical behavior is not visible in any approved narrow reference.

## 4. Screen-by-Screen Audit

### JC-010 — Job Finder State 1

**Responsive risk: High**

The four-part integrated search instrument is the primary pressure point. At
narrow widths its keyword, location, distance, Search Jobs, Browse, and Refine
relationships must remain intelligible without turning secondary actions into
competing calls to action. Listings can use the shared narrative-first reflow,
but the result toolbar, compact metadata, pagination, right rail, leaderboard,
and full footer create a long stacked page.

The current implementation demonstrates grid collapse and narrow listing
reflow, but it does not establish approved ordering. A responsive visual
authority need is **High**, with JC-010 serving as the representative base
state for the shared shell and Finder composition.

### JC-011 — Job Finder State 2

**Responsive risk: High**

JC-011 inherits every JC-010 risk and adds the densest control state: two rows
of refinements, apply/reset actions, applied chips, result count, and sort.
Simple one-column stacking would be technically possible but would create a
large barrier between search intent and results. The specification must decide
disclosure, action persistence, chip behavior, and which context stays visible;
the desktop image cannot answer those questions.

The current CSS already collapses refine grids and result side blocks below
desktop widths. That is implementation evidence only. The new responsive
authority need is **High** because this state governs the most demanding Search
& Discovery control density.

### JC-014 — Location Selection Modal

**Responsive risk: High**

The modal depends on a fixed desktop relationship among three tabs, location
content, a five-value distance slider, a teaser, and two actions. Narrow width
threatens tab labels and slider ticks; short viewport height threatens access
to the actions. Responsive governance must also account for focus containment,
close/cancel semantics, background inertness, keyboard operation, and internal
versus page scrolling without changing the approved location-selection scope.

The dimmed JC-010 background is inherited, but the responsive modal cannot be
derived from it. A dedicated responsive visual authority need is **High** for
both narrow width and short-height conditions.

### JC-015 — Browse Reveal

**Responsive risk: High**

The expanded row of five browse cards is the meaningful delta from JC-010.
Those cards cannot remain a single row at narrow widths, and their reflow must
preserve scan order, equal exploratory importance, disclosure semantics, and
the unchanged relationship to Refine Search. The expansion also lengthens the
page before listings and therefore amplifies the rail, pagination, ad, and
footer ordering questions inherited from JC-010.

The shared Finder authority reduces duplication, but the exact browse-card
adaptation remains unseen. The state-specific responsive authority need is
**High**.

### JC-030 — Job Detail

**Responsive risk: High**

JC-030 has the highest conversion-order risk. Desktop places the narrative and
a conversion-oriented rail in parallel, with application information also
present later in the main flow. When the rail stacks, DOM or visual order can
make the primary apply path appear too late, duplicate actions confusingly, or
support content interrupt the job narrative. Long titles, metadata, taxonomy,
employer information, related jobs, sharing, the 300 x 250 ad, the application
panel, and the leaderboard all add narrow-width pressure.

The current implementation collapses the Detail grid and removes sticky rail
positioning, but its main-first DOM order places the sidebar after the complete
main card. That behavior is not approved and cannot settle the conversion
priority. A dedicated responsive visual authority need is **High**.

## 5. Responsive Dependency Matrix

| Component | Shared | Screen-specific | Needs specification | Needs visual authority | Likely implementation effort |
| --- | --- | --- | --- | --- | --- |
| Canvas/gutters | Yes | No | Low | Low | Low |
| Navbar/account controls | Yes | No | High | High | Medium |
| Breadcrumb/back behavior | Yes | Detail has paired back action | Medium | Medium | Low |
| Search control reflow | Yes across Finder states | State visibility differs | High | High | Medium |
| Refine panel | Partly | JC-011 | High | High | Medium |
| Browse cards/disclosure | Partly | JC-015 | Medium | High | Low |
| Location modal | Modal foundations only | JC-014 | High | High | Medium |
| Listing reflow | Yes | No | Medium | Medium | Medium |
| Chips/result toolbar | Yes | JC-011 density | Medium | Medium | Low |
| Pagination/result range | Yes | No | Medium | Medium | Low |
| Right-rail transition | Yes structurally | Card sets and priorities vary | High | High | High |
| Job Detail action priority | No | JC-030 | High | High | High |
| 300 x 250 ad | Yes | Placement context varies | High | High | Medium |
| 728 x 90 ad | Yes | Main-column context varies | High | High | Medium |
| Footer | Yes | No | Medium | High | Medium |
| Responsive regression coverage | Yes | State fixtures differ | Medium | Low | Medium |

## 6. Advertising

Advertising is a **High** responsive dependency because the Design System
explicitly leaves mobile creative size, substitution, hiding, and reservation
unresolved.

- The `300 x 250` unit can physically fit many mobile viewports with governed
  gutters, but its location after rail collapse is not approved. It must remain
  labeled and subordinate to the primary task.
- The `728 x 90` leaderboard cannot be treated as a proportionally shrunken
  desktop raster; that would make its content illegible. Mobile inventory,
  substitution, reserved height, and empty-inventory behavior need an ad
  policy before visual authority.
- Finder and Detail may require different contextual placement decisions even
  if they share serving rules.
- Neither ad may cause horizontal overflow, layout shift, or an ambiguous
  association with listings, employer information, or application actions.

Existing flexible CSS around the leaderboard and centered medium rectangle is
a reusable foundation, not a decision about the creative or its mobile order.

## 7. Navigation

Navigation risk is **High**. The approved desktop navbar contains brand,
multiple product destinations, and two account actions. The current CSS wraps
these into multiple rows and hides selected destinations at narrower widths,
but the Design System requires a named responsive mechanism and preservation of
labels, order, keyboard access, and current state. Hiding destinations based on
available width is therefore not authority.

Responsive governance must settle the navigation mechanism, account-action
priority, current-page indication, focus order, and relationship to the
constrained shell. Breadcrumbs must wrap without horizontal scrolling. JC-030
also pairs breadcrumbs with Back to Search Results, requiring explicit narrow
behavior so both functions remain understandable.

## 8. Right Rail

Right-rail risk is **High** across all audited screens. The governing invariant
is clear: primary content precedes support content in reading and focus order,
and the rail becomes a stacked support region at an approved breakpoint. The
following remain unresolved and must not be inferred from current DOM order:

- the breakpoint at which parallel columns end;
- exact card order after stacking;
- whether any essential action is promoted before the primary content;
- which cards remain expanded, collapse, or are omitted;
- the position of the `300 x 250` advertisement; and
- what must remain above the fold.

For JC-010, JC-011, and JC-015, save-search/current-search and discovery cards
support the search/listing task; none may displace the result truth. For JC-030,
the application panel is conversion-critical while job details, employer,
related discovery, and advertising are supporting content. The Design System
allows earlier promotion only through approved responsive authority, so the
JC-030 mobile action order is a required specification decision rather than an
audit recommendation.

The current Finder DOM renders the main column before the complete rail. The
current Detail DOM likewise renders the full main card before the sidebar.
Those structures make safe semantic sense for support content but may conflict
with JC-030 conversion priority. This is the highest-risk dependency for later
implementation planning.

## 9. Implementation Risk

**Highest risks**

- Changing visual order without preserving semantic reading and focus order.
- Treating existing breakpoints or Draft captures as approved behavior.
- Solving JC-030 conversion placement with duplicated or inconsistently stateful
  application controls.
- Allowing the modal or filter panel to create viewport-height traps.
- Scaling desktop ad creatives into unusable mobile placements.
- Accumulating page-specific overrides in the already large shared public
  stylesheet.

**Reusable implementation foundations**

- Shared canvas variables already express `1200px`, `32px`, and `16px` gutter
  behavior.
- Finder/Detail grids already collapse, sticky rails already become static,
  and several control/result groups already stack.
- Listing markup separates narrative and compact metadata/action content.
- Flexible result toolbars, wrapping chips, centered ads, and constrained shell
  primitives reduce the amount of new structural work likely to be required.

**CSS-only opportunities**

Some spacing, wrapping, column collapse, footer grids, card sizing, and
typographic adjustments are likely CSS-only after authority exists. They are
low risk only when DOM order already matches the approved reading order.

**Structural or behavioral dependencies**

Navigation collapse, modal accessibility, JC-030 action priority, rail card
ordering, ad substitution, and any collapsible support content may require
markup or JavaScript changes. These cannot be classified as CSS-only before a
responsive specification is approved.

**Testing concerns**

Later verification must cover representative tablet, narrow, and mobile widths;
short-height viewports; zoom/reflow; keyboard and screen-reader order; modal
focus; long titles and labels; chips and filters; saved/authenticated states;
closed or unavailable jobs; ad loaded/empty states; pagination; and horizontal
overflow. Existing browser coverage checks shell width and overflow on limited
public routes but does not prove the five approved states or JC-030 conversion
order.

The principal duplication risk is independently adapting the same navbar,
listing, rail, ads, pagination, and footer in five tickets. Shared responsive
governance and shared acceptance fixtures should precede state-specific work.

## 10. Recommended Responsive Governance Sequence

This sequence defines governance boundaries only; it is not an implementation
plan.

1. **Responsive specification — shared system.** Govern viewport classes,
   canvas/gutters, navigation, semantic order, listing reflow, right-rail
   transition, advertisements, pagination, footer, accessibility, and
   responsive acceptance principles.
2. **Responsive specification — Search & Discovery states.** Govern JC-010 as
   the base, then only the state deltas for JC-011, JC-014, and JC-015. Resolve
   filter disclosure, modal behavior, Browse reflow, and state continuity
   without reopening desktop approvals.
3. **Responsive specification — JC-030.** Govern application-action priority,
   narrative/support order, rail transition, employer/related content, and ad
   placement without changing approved product truth or desktop authority.
4. **Responsive visual authority — shared shell and JC-010.** Establish the
   representative tablet/mobile shell and first-touch Finder baseline.
5. **Responsive visual authority — interaction deltas.** Establish only the
   minimum responsive views needed to govern JC-011, JC-014, and JC-015.
6. **Responsive visual authority — JC-030.** Establish the conversion-critical
   tablet/mobile ordering separately from Finder states.
7. **Responsive implementation audit.** Compare the approved responsive suite
   with current markup, CSS, JavaScript, accessibility behavior, ad handling,
   and tests; then identify the smallest coherent implementation boundaries.

No implementation should begin from this audit alone. It establishes risk and
dependency visibility but creates no responsive behavior or visual authority.
