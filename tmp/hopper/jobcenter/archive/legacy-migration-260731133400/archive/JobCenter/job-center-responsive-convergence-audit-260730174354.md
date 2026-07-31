# Job Center Responsive Convergence Audit

**Ticket:** RESP-AUDIT001

**Date:** 2026-07-13

**Scope:** Responsive implications of approved desktop authorities JC-010 v1.1,
JC-011 v1.0, JC-014 v1.0, JC-015 v1.0, and JC-030 v1.0

**Authority created:** None. This audit creates no responsive specification,
visual authority, breakpoint, or implementation direction.

## 1. Shared Responsive Risks

Only the following shared areas remain unsafe to infer from the approved
desktop rasters.

### Navigation and shell

The canvas and narrow gutter behavior are already governed, but the desktop
navbar cannot safely shrink without an approved narrow navigation mechanism.
Brand, product destinations, current state, and logged-out account actions must
remain available without horizontal overflow. This is one shared risk, not a
screen-specific risk.

### Search controls and result context

The integrated keyword, location, distance, and Search Jobs instrument cannot
remain in its desktop row at every width. Browse/Refine actions, applied chips,
result count, and sort must retain their relationships when controls wrap or
stack. The basic control behavior is shared; JC-011 adds one screen-specific
density risk addressed below.

### Listings

Desktop listings use a narrative block and a compact salary/distance/Save
block. Narrow layouts must preserve that two-zone meaning, narrative-first
reading order, and consistent Save placement. This can be governed once for all
Finder states; it does not require separate authority per screen.

### Right rail

The rail must become support content without overtaking the primary task. The
unresolved shared issue is the order and visibility of cards and advertising
after the two-column composition ends. JC-010 v1.1 supplies the most complete
logged-out rail and should anchor shared authority. JC-030 has a separate
conversion-order risk because its application action is not ordinary support
content.

### Advertising

The `300 x 250` rail reservation and `728 x 90` leaderboard cannot simply be
scaled until their contents become illegible. Narrow creative substitution,
reservation, or omission and the position of the rail ad after stacking remain
unresolved. One shared advertising decision can govern all five surfaces.

### Breadcrumbs, pagination, and footer

Breadcrumbs must wrap without becoming horizontal scrollers. Pagination must
remain operable without presenting the complete desktop control row at an
unusable density. Footer columns require a stable narrow reading order. These
are shared shell concerns and do not warrant screen-specific visual authority.

## 2. Screen-Specific Risks

### JC-010 v1.1 — High

JC-010 contains the complete shared responsive problem: full navbar, integrated
search instrument, listing/result controls, the newly approved logged-out rail,
both ad formats, pagination, and footer. Its risk is **High** because desktop
authority does not determine the narrow navigation, search reflow, or stacked
rail order. It is nevertheless the correct single base for governing those
issues across the suite.

### JC-011 v1.0 — High

JC-011 inherits JC-010 and adds expanded refinement controls, applied chips,
reset/apply behavior, and sort/result context. Its risk is **High** because a
literal stacked rendering could place excessive control depth before results
or obscure which actions affect the query. Only this expanded-control delta
needs separate responsive authority.

### JC-014 v1.0 — High

JC-014's modal must remain usable at narrow widths and short viewport heights.
Tabs, location content, distance values, actions, close behavior, and focus
cannot be safely inferred from the centered desktop modal. Risk is **High**;
the modal itself needs narrow authority, while its background inherits JC-010.

### JC-015 v1.0 — Medium

The five Browse cards cannot remain one desktop row, but their order and equal
importance are already clear. Risk is **Medium** because a shared ordered-card
flow can adapt this state without introducing a new interaction or priority
decision. JC-015 does not justify dedicated responsive visual authority unless
later review exposes ambiguity not present in the approved content.

### JC-030 v1.0 — High

JC-030 places application actions and supporting information in a desktop
right rail while related application content also appears in the main flow.
Risk is **High** because ordinary main-first rail stacking could delay or
duplicate the primary conversion action. The narrow application/action order
requires separate authority; ordinary shell, footer, ad, breadcrumb, and card
behavior can inherit shared rules.

## 3. Responsive Authority Required

The minimum authority set is one shared Finder baseline plus three
screen-specific mobile deltas. A separate responsive artifact for every
desktop screen is unnecessary.

| Approved desktop surface | Inherits shared responsive rules | Tablet authority | Mobile authority |
| --- | --- | --- | --- |
| JC-010 v1.1 | Establishes the shared baseline | Required as part of one shared JC-010 authority set | Required as part of the same shared JC-010 authority set |
| JC-011 v1.0 | Shell, listings, rail, ads, pagination, footer | No dedicated authority; inherit the shared tablet baseline | Required only for expanded refinement/chip/result-context delta |
| JC-014 v1.0 | JC-010 background and shared shell | No dedicated authority; desktop modal may inherit until the shared narrow state applies | Required for modal fit, flow, and action access |
| JC-015 v1.0 | Shell plus shared ordered-card flow | No dedicated authority | No dedicated authority |
| JC-030 v1.0 | Shell, breadcrumbs, ordinary cards, ads, footer | No dedicated authority; inherit shared tablet behavior | Required only for application/action and rail-content order |

### Minimum visual authority coverage

1. **One JC-010 responsive baseline set** covering representative tablet and
   mobile views. This governs the shared shell, navigation, integrated search,
   listing, rail, advertising, pagination, and footer behavior.
2. **One JC-011 mobile delta** governing only expanded refinement, chips, result
   context, and action priority.
3. **One JC-014 mobile delta** governing only the location modal.
4. **One JC-030 mobile delta** governing only application/action priority and
   the transition of its desktop rail.

JC-015 should be validated against the shared baseline rather than assigned a
separate visual-authority exercise. Separate tablet artifacts for JC-011,
JC-014, JC-015, and JC-030 would duplicate the shared shell without resolving a
distinct product-order question.

## 4. Recommended Governance Sequence

The smallest remaining responsive workstream is:

1. Record one shared responsive decision covering navigation, semantic reading
   order, search-control reflow, listing preservation, support-rail transition,
   advertising policy, pagination, footer, and accessibility invariants.
2. Establish one consolidated responsive visual-authority set: JC-010 at tablet
   and mobile, plus mobile-only deltas for JC-011, JC-014, and JC-030.
3. Validate JC-015 against the shared authority. Create additional authority
   only if that validation reveals a real ambiguity.
4. After approval, perform one responsive implementation audit across the
   shared system and the three approved deltas.

This sequence avoids five parallel responsive screen workstreams, avoids
duplicating shared shell decisions, and preserves the rule that interaction
states modify only the minimum interface necessary to document their delta.
No responsive implementation may proceed from this audit alone.

## Verification Statement

- Audit only.
- Documentation only.
- No desktop authority reopened.
- No responsive design, mockup, specification, breakpoint, CSS, or
  implementation created.
