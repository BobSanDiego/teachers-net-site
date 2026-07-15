# Job Center Design System v1

**Project:** Teachers.Net - Job Center

**Status:** Canonical visual and interaction specification

**Version:** 1.0

**Date:** 2026-07-11

**Scope:** Public Job Center and employer-facing Job Center interfaces

## 1. Purpose and authority

This document defines the canonical visual and interaction system for the
Teachers.Net Job Center. It governs future UX audits, design references,
mockups, implementation tickets, component work, and visual/accessibility QA.

It translates the Job Center Canonical V1 Contract and Employer UX V1 into
stable presentation rules without changing product behavior. It does not
declare unsupported functionality implemented, approve an uncertain visual
artifact, or authorize code, route, template, schema, style, or behavior work.

The system exists to make Job Center interfaces:

- professional and trustworthy;
- calm, legible, and information-dense without appearing crowded;
- consistent across discovery, detail, saved/alert, and employer workflows;
- explicit about public visibility, employer authority, application behavior,
  and lifecycle;
- responsive without becoming a different product at each breakpoint; and
- compatible with intentional advertising without allowing advertising to
  dominate primary tasks.

This document is the Job Center-specific visual authority once accepted. The
shared `docs/design-system-v1.md` remains the Teachers.Net-wide foundation.
Employer interaction and terminology must conform to
`docs/job-center/employer-ux-v1.md`.

`docs/job-center/job-center-responsive-advertising-strategy-v1.md` is the
canonical companion authority for responsive advertisement inventory,
dimensions, placement hierarchy, and exceptions.

`docs/job-center/job-center-responsive-layout-geometry-v1.md` is the canonical
companion authority for responsive layout classes, physical column geometry,
rail eligibility, collapse behavior, and reading-width constraints.

## 2. Source hierarchy and conflict rules

### 2.1 Source hierarchy

Use sources in this order:

1. the current explicitly approved ticket or decision;
2. the Job Center Canonical V1 Contract for product truth and behavior;
3. Employer UX V1 for the employer journey, terminology, and interaction
   responsibilities;
4. this Job Center Design System for visual and interaction rules;
5. a specifically named and explicitly approved Job Center visual reference
   for its declared screen or component boundary;
6. the shared Teachers.Net Design System v1 for cross-product foundations;
7. the current Project Cursor and implementation map for current scope and
   implementation state;
8. current implementation as evidence of existing behavior and constraints;
9. Draft, Superseded, or Unclassified visual artifacts as research evidence
   only.

UA001-UA003 explain the findings synthesized into Employer UX V1 and this
document. They remain audit evidence, not competing design specifications.

### 2.2 Conflict rules

- Product truth outranks appearance. A mockup cannot create an unsupported
  route, action, status, metric, application flow, or authority rule.
- Explicitly approved references outrank legacy implementation for visual
  presentation within the approved reference boundary.
- Legacy implementation never becomes visual authority merely because it is
  deployed or screenshot-ready.
- A component-specific approved reference outranks a general reference only for
  that component.
- This document outranks Draft, Superseded, and Unclassified artifacts.
- More recent filenames, larger version suffixes, “final,” “design-target,”
  file location, and generated-image provenance do not prove approval.
- Where this document and the shared design system conflict, this document
  governs Job Center-specific composition; shared brand foundations remain in
  force unless explicitly superseded here.
- Where two approved references conflict, stop and request a written decision.
  Do not average, merge, or choose by recency.
- Where no approved reference exists, preserve the requirement as unresolved.
  Current implementation may be documented as evidence but must not be promoted
  to the target by implication.

### 2.3 Approval evidence

A visual artifact is **Approved** only when a durable project decision names the
artifact or stable identifier, its component/screen boundary, and approval
state. Conversation intent to create a target is not enough if the resulting
artifact was not subsequently accepted.

The shared design system states that it is based on an approved Job Center
landing page, but no durable source currently maps that approval to a specific
image filename. The shared design-system rules are authoritative; the available
landing screenshots remain Unclassified until an exact artifact is named.

## 3. Design principles

1. **Teachers.Net, not a generic job board.** The product feels like the
   professional home of educators, not a marketplace template.
2. **Clarity before decoration.** Typography, alignment, spacing, and state
   language do most of the visual work.
3. **Information density without clutter.** Compact scanning is preferred, but
   content retains readable spacing and clear grouping.
4. **One product language.** Public discovery, job detail, saved jobs, alerts,
   and employer tools share the same canvas, tokens, controls, and status model.
5. **Progressive disclosure.** Basic tasks remain concise; advanced controls
   appear on request without opening a second results system.
6. **Truthful state.** Published is not automatically Live. Visual treatment
   never overstates public visibility, authority, employer endorsement, or an
   application event.
7. **Directory, not spreadsheet.** Public job results are professional directory
   entries with narrative hierarchy, not data-table rows.
8. **Actions are proportional.** One primary action leads each task. Secondary
   and tertiary actions do not compete with it.
9. **Advertising is structural.** Reserved ad placements participate in the
   grid and spacing system; they never appear improvised inside content cards.
10. **Responsive continuity.** Layout may stack or reflow, but hierarchy,
    meaning, order, and actions remain recognizable.
11. **Accessible by default.** Keyboard, focus, contrast, labels, status
    announcements, and target sizes are component requirements, not QA polish.
12. **Unresolved means unresolved.** A missing approved design is documented,
    not invented.

## 4. Page canvas, grid, gutters, and responsive behavior

### 4.1 Desktop canvas

The entire desktop Job Center shell uses one centered `1200px` maximum-width
canvas.

```text
Canvas maximum width: 1200px
Desktop internal gutter: 32px
Tablet/mobile internal gutter: 16px
```

All visible Jobs-owned pixels belong inside this canvas, including:

- navbar and its background;
- breadcrumb and page heading/hero;
- search/browse controls;
- result and detail wrappers;
- employer interfaces;
- right rail;
- advertisements;
- footer and its background.

Outside the canvas, only the browser/page background is visible. Jobs-owned
header, hero, footer, rail, sponsor, or section bands must not run full viewport
width beyond the canvas.

The navbar and footer remain constrained to the same page canvas. A wide
viewport must not produce a full-bleed navbar/footer around a narrower app.

### 4.2 Alignment tiers

There are two allowed page-level alignment tiers:

1. **Canvas edge:** the 1200px outer shell.
2. **Intentional inset:** the shared internal content line created by the canvas
   gutter.

Outer wrappers align to the canvas edge and apply the shared gutter internally.
Visible content aligns to the inset. A component may be narrower only when this
document or an approved component reference names the exception. Competing
page-level widths such as 1040px, 1120px, 1180px, or 1220px are prohibited.

### 4.3 Primary grid

Desktop result and detail pages use a primary content column and a right rail.
The shared design-system target for the right rail is `300px`; the remaining
width belongs to main content after the column gap. Exact column-gap and main
column measurements require an approved component reference or token decision.

The main column owns the primary task. The rail supports search context, alerts,
related discovery, employer conversion, resources, and advertising. It must not
force the main content below a usable scanning width.

### 4.4 Responsive behavior

- Above 1200px, the canvas stays centered and does not grow.
- Below the desktop canvas, the shell may fill the viewport.
- Gutter reduction is proportional and reaches `16px` at tablet/mobile.
- Controls wrap or stack before text becomes cramped or targets overlap.
- Main content precedes right-rail support content in reading and focus order.
- The right rail becomes a stacked support region at the approved breakpoint.
- Public listing entries preserve narrative-first order when stacked.
- The compact secondary block remains grouped; it does not dissolve into
  unrelated metadata lines or a horizontal table.
- Responsive layouts do not hide required status, result-count, application,
  or employer-lifecycle truth.
- Horizontal page overflow is prohibited.
- Exact breakpoints, mobile ad serving behavior, and the approved mobile order
  of individual rail cards remain unresolved until explicitly approved.

## 5. Typography hierarchy

### 5.1 Foundation

Typography is friendly, professional, and readable. It avoids condensed,
overly geometric, novelty, and display-heavy styles. Hierarchy is created by
size, weight, spacing, and color—not excessive capitalization or multiple font
families.

The shared landing header and hero use:

```css
font-family: Arial, Helvetica, sans-serif;
```

No approved project source currently names a separate canonical body/UI font.
The final body/UI font family is unresolved. Future references must not silently
introduce a competing family.

### 5.2 Canonical header/hero targets

The shared foundation defines:

```text
Teachers.Net wordmark: 36px
Job Center subtitle: 16px
Landing hero title: 36px
Landing hero subtitle: 18px
```

Responsive reductions may occur without changing hierarchy.

### 5.3 Hierarchy rules

- Page title is the strongest text on the page.
- Section headings orient; they do not compete with the page title.
- Job title is the strongest text inside a listing entry.
- Employer and location form one clear secondary identity line.
- Summary uses readable body text and may wrap naturally.
- Taxonomy chips and supporting metadata are compact but remain legible.
- Salary is bold enough to scan but secondary to the job title.
- Distance, result range, dates, help, and ad labels use muted text.
- Button labels use a consistent UI weight; primary does not mean extra-bold.
- All-caps is reserved for short structural labels such as `ADVERTISEMENT`, not
  paragraphs or routine controls.
- Link meaning cannot depend only on weight or color.

Exact body, small, label, listing-title, card-heading, line-height, and weight
tokens remain unresolved pending a named approved type scale.

## 6. Color and interface states

### 6.1 Canonical palette

Use the shared color tokens:

```css
--tnet-jobs-color-link: #0017DD;
--tnet-jobs-color-button-primary: #0033C7;
--tnet-jobs-color-nav-text: #0A144D;
--tnet-jobs-color-text: #142235;
--tnet-jobs-color-muted: #526273;
--tnet-jobs-color-border: #d8e0e8;
--tnet-jobs-color-surface: #ffffff;
--tnet-jobs-color-hero: #073a63;
--tnet-jobs-color-footer: #001f49;
```

The navbar is white. The standard footer is dark Teachers.Net blue using the
footer token. Primary blue is reserved for active navigation, primary actions,
important links, and deliberate emphasis.

### 6.2 Surface and border rules

- Primary content surfaces are white.
- Page background may distinguish the centered shell, but must not compete with
  content.
- Borders are light and structural, never the dominant visual element.
- Shadows are subtle and reserved for elevation that communicates layering.
- Cards do not require a shadow when border and spacing already establish the
  boundary.

### 6.3 Interaction states

Every interactive component defines:

- default;
- hover;
- keyboard focus-visible;
- active/pressed;
- selected/current;
- disabled;
- loading where an asynchronous operation exists;
- success;
- warning/needs attention; and
- error.

State cannot depend on color alone. Focus is not a hover substitute. Disabled
controls remain legible and are not used to conceal authorization rules.

Exact success, warning, error, and status colors are unresolved. Until approved,
interfaces use accessible semantic text and restrained existing system colors
without creating a new palette.

## 7. Navigation, breadcrumb, hero, and footer rules

### 7.1 Navbar

- White background.
- Constrained to the centered 1200px canvas.
- Teachers.Net identity is visually primary at the left.
- Job Center context is visible without inventing a second brand.
- Current Job Center destination is indicated visually and with
  `aria-current="page"`.
- Navigation labels remain concise.
- Account actions occupy the right side and do not compete with the primary
  page task.
- Mobile navigation preserves labels, order, keyboard access, and current state.

The exact navigation item set and desktop/mobile navigation mechanism require a
named approved reference. Available screenshots disagree and are not approved
individually.

### 7.2 Breadcrumb

- Appears inside the content inset below the navbar and above the page title.
- Communicates hierarchy, not a duplicate page title.
- Uses real links for ancestor destinations and plain/current semantics for the
  final item.
- Home may use a simple outline icon with an accessible name.
- Separators are decorative and ignored by assistive technology.
- Breadcrumb wraps without horizontal scrolling on narrow screens.

### 7.3 Hero and page heading

The approved shared landing system permits a dark slate/chalkboard hero with a
decorative education illustration, constrained headline, and supporting copy.
The art remains decorative and cannot reduce text contrast.

Not every Job Center screen requires a large hero. Search results, detail, saved
jobs, alerts, and employer tools may use compact page headings when an approved
screen reference establishes that pattern. A page must not inherit the landing
hero merely for consistency.

Exact non-landing hero/page-heading compositions remain unresolved.

### 7.4 Footer

- Standard Teachers.Net dark blue: `#001f49`.
- Entire background and content constrained to the 1200px canvas.
- Preserves Teachers.Net identity, Job Center links, community, resources,
  about/legal, social links, copyright, and any approved newsletter area.
- Uses a stable column hierarchy on desktop and a readable stacked hierarchy on
  mobile.
- Footer links meet contrast, focus, and target requirements.
- Footer does not expand or shrink unpredictably based on sparse page content.

Exact column count and newsletter composition require a named approved footer
reference; available artifact families are not individually approved.

## 8. Buttons, links, forms, selectors, chips, notices, and status indicators

### 8.1 Shared shape and control tokens

```css
--tnet-jobs-radius-button: 6px;
--tnet-jobs-radius-card: 14px;
--tnet-jobs-radius-input: 6px;
--tnet-jobs-control-height: 36px;
--tnet-jobs-button-height: 40px;
```

Component-specific exceptions require an approved reference and cannot create a
second page-level system.

### 8.2 Buttons

- One primary button per immediate decision region.
- Primary: solid primary button color with accessible text contrast.
- Secondary: outlined or quiet surface treatment.
- Tertiary: text action for low-emphasis reversible behavior.
- Destructive actions use explicit consequence labels; they do not borrow the
  primary-blue treatment merely because they submit a form.
- Button labels use verb + object when ambiguity exists: **Search Jobs**,
  **Apply Filters**, **Submit for Review**, **Close Job**.
- Loading preserves button width and communicates progress.
- Disabled state explains unmet requirements when practical.

### 8.3 Links

- Links use the canonical link blue.
- Job titles are links only when a valid destination exists.
- Underline or another non-color cue appears on hover/focus and wherever context
  makes link identity ambiguous.
- A link is not styled as a button unless it initiates a button-like navigation
  decision.

### 8.4 Forms and selectors

- Labels remain visible; placeholders do not replace labels.
- Required state is announced in text/semantics and enforced server-side.
- Help appears before error when it prevents confusion.
- Error text identifies the field and actual rule; not every invalid value is
  described as “required.”
- Related inputs share a fieldset/legend or equivalent programmatic group.
- Selectors use consistent height, label spacing, chevrons, and focus treatment.
- Long hierarchical selectors provide selected-state feedback and remain
  keyboard operable.
- Create and Edit use the same field meanings and validation language.

### 8.5 Chips

- Chips summarize active filters or compact taxonomy.
- Filter chips appear near the search/filter context and provide a clear remove
  action when removal is supported.
- Taxonomy chips are informational and stay grouped inside the listing narrative
  block.
- Chips do not become independent table columns.
- Chip labels remain short; full meaning must remain available to assistive
  technology when abbreviated.

### 8.6 Notices

- Success confirms the actual result, not merely a stored status.
- Warning/Needs Attention names the condition and next step.
- Error preserves work and identifies recovery.
- Informational notices do not use warning styling.
- Notices receive appropriate live-region behavior without moving keyboard
  focus unexpectedly.
- A notice is not the only place a durable lifecycle state appears.

### 8.7 Status indicators

Employer status presentation conforms to Employer UX V1:

- Draft
- Awaiting Review
- Published — Live
- Published — Needs Attention
- Closed
- Expired
- Archived

Pills may provide compact scanning, but adjacent text or accessible naming must
carry full meaning. Published and Live are not interchangeable.

Exact status colors are unresolved.

## 9. Search interaction model

### 9.1 One Job Finder

`/jobs/` is one Job Finder. Search and Browse are interface states using the
same results, filters, chips, sort, pagination, eligibility, and listing
components. They are not separate products or result routes.

### 9.2 Progressive disclosure

Search uses progressive disclosure:

1. Basic controls expose the most common inputs and one clear search action.
2. Refine/Advanced controls expand in place or through one clearly connected
   panel.
3. Advanced controls do not reset basic inputs or open a second results model.
4. Applied values appear as chips or equivalent removable summaries.
5. The open/closed state has an accessible name and expanded state.
6. Clear affects the declared scope; **Clear filters** does not silently erase
   unrelated keyword/location intent.
7. Apply/Search labels distinguish rebuilding the query from applying refinements.

### 9.3 Search and Browse states

- Search prioritizes keyword/title intent.
- Browse prioritizes structured classification and discovery.
- Both may use location, grade, subject, employment type, salary, work
  arrangement, posted-within, and other supported filters.
- Sort is one shared results control, not a separate page mode.
- State changes update result count, chips, and URL behavior consistently.

### 9.4 Location and distance

Current implementation supports Distance controls in Advanced Search/Browse.
The Project Cursor explicitly identifies a compact Basic Location + Distance
editor as pending final design approval. This document does not invent that
interaction.

Browser current location remains opt-in, request-scoped, private, and visually
distinct from a saved preferred location. Search design cannot imply that
temporary current location is stored.

### 9.5 Search panel composition

The exact desktop row count, field order, action order, Basic/Advanced labels,
and mobile expansion pattern require an approved Search Panel reference. The
available panel comparison artifacts are Unclassified and cannot freeze these
details.

### 9.6 Search & Discovery Interaction Suite v1

**Status: Complete.** The canonical desktop v1 search/discovery journey is
defined by these Approved references, used together in interaction order:

1. **JC-010 — Job Finder State 1:** first-touch discovery.
2. **JC-014 — Location Selection Modal:** location selection.
3. **JC-015 — Browse Reveal:** browse exploration.
4. **JC-011 — Job Finder State 2:** search results.

The governing interaction-state rule is:

> Interaction-state artifacts inherit an approved page state and modify only
> the minimum interface necessary to document a single user interaction.

The governing progressive-disclosure rule is:

> The Job Center progressively reveals capability as the user demonstrates
> intent.

Future interaction states follow the same philosophy. This suite freezes the
approved desktop journey only; it does not confer approval on responsive
adaptations, Job Detail, employer, Saved Jobs, Alerts, or other placeholder
references. Exact artifact filenames, versions, scopes, and lineage remain
controlled by the visual manifest.

## 10. Canonical job-listing composition

### 10.1 Directory-entry rule

Public job listings are professional directory entries, not tables or
spreadsheets. A listing does not distribute title, employer, location,
taxonomy, salary, distance, and Save into independent metadata columns.

Every desktop entry uses two broad visual zones:

```text
LEFT: narrative block                  RIGHT: compact secondary block
Job title                              Salary
Employer · City, State                 Distance when supported
Taxonomy chips                         Save heart
Concise summary
```

### 10.2 Narrative block

- Job title is the strongest element and links to the job detail when valid.
- Employer and city/state remain directly associated with the title.
- Grade, subject, and employment type chips stay together below identity.
- Summary is concise, readable, and may wrap naturally.
- Taxonomy metadata never spreads into stand-alone vertical columns.
- Provenance, internal verification, source state, and moderation facts are not
  public listing badges unless separately approved by product contract.
- No **Verified Employer** badge exists in V1 without an approved designation
  and route.

### 10.3 Compact secondary block

- Salary and distance are one compact group.
- Salary is bold but secondary to title.
- Distance appears only when supported and truthful.
- Save uses the canonical heart behavior in Section 16.
- Secondary facts align consistently across entries without turning the result
  into a data grid.

### 10.4 Entry surface

- Light border and restrained radius.
- Consistent internal padding and separation.
- Enough air for scanning; no empty band caused by removed metadata.
- Natural summary wrapping may increase an entry, but the minimum structure and
  spacing remain consistent.
- Mobile stacks narrative before secondary content and keeps salary/distance/
  Save grouped.

Exact padding, radius exception, summary line limit, and whether a public
posting-age line appears remain unresolved until an approved listing component
reference is named. Available current candidate imagery omits posting age.

## 11. Job-detail presentation principles

No specific Job Detail visual artifact is currently approved or indexed.
Therefore this section defines principles, not an invented screen.

- Title, employer, location/work arrangement, and current public availability
  lead the page.
- Application action is truthful about external URL, protected email, or written
  instructions.
- Teachers.Net never implies it receives an application unless it does.
- Salary, employment type, grade, subject, description, requirements, and
  relevant location facts use one readable content hierarchy.
- Protected application data appears only after the authorized reveal behavior.
- Closed, Expired, or Needs Attention treatment replaces the live application
  CTA with clear state guidance.
- Save uses the same heart state as listings.
- Source/correction/removal/claim affordances remain discreet and must not imply
  endorsement.
- Right rail and advertising remain subordinate to title, content, and
  application truth.
- Structured data, visible state, finder eligibility, saved jobs, and
  application actions must agree.

Exact detail grid, sticky behavior, section order, CTA placement, and mobile
composition are unresolved design needs.

## 12. Employer interface principles

Employer interfaces conform to `docs/job-center/employer-ux-v1.md`.

### 12.1 Shared employer shell

- Uses the same 1200px Job Center canvas, navbar, footer, tokens, and focus rules.
- Clearly shows authenticated user and selected employer.
- Preserves validated employer context across Dashboard, My Jobs, Post, Edit,
  Review, confirmation, and returns.
- Uses Dashboard, My Jobs, and Post a Job as the core V1 navigation tasks.
- Does not create a separate claimed-employer visual system.

### 12.2 Dashboard

- Dashboard summarizes; it does not reproduce the full job manager.
- Establishes employer context and one primary next action.
- Uses complete counts, not recent-list approximations.
- Prioritizes attention: drafts, awaiting review, expiring, or published but not
  Live.
- Summary items route to the relevant My Jobs context.
- Advanced analytics are outside V1.

### 12.3 My Jobs

- My Jobs manages and is the authoritative employer inventory/action surface.
- Every row/card shows truthful lifecycle and visibility, relevant dates, basic
  metrics, and valid actions.
- Renew, Duplicate, Close, and Archive remain distinct in label and consequence.
- Views, Saved, and Interested follow Employer UX V1 definitions.
- Archived visibility follows the unresolved product decision; no design may
  assume a history view before approval.

### 12.4 Post Job and Edit

- Create and Edit share interaction patterns, field language, grouping,
  validation, Review, and Preview.
- Review is factual readiness and consequence.
- Preview is protected visual public presentation.
- Draft persistence, direct-publish trust, archive policy, and other unresolved
  Employer UX V1 decisions remain unresolved here.
- System-owned fields do not become editable merely to fill a layout.

No Employer Dashboard, My Jobs, Post Job, Edit, claim, or authority-review
visual artifact is currently Approved. Exact employer screen composition is
unresolved.

## 13. Right-rail structure

### 13.1 Role

The right rail supports the main task. It never contains the primary result
count, replaces navigation, or competes with the application action.

### 13.2 Desktop structure

- Target width: `300px`.
- Uses the same card, border, radius, heading, link, and spacing system.
- Cards align to one rail width.
- The rail may contain, in task-appropriate hierarchy:
  - current search and alert/save-search actions;
  - related/popular discovery;
  - employer conversion where appropriate;
  - teacher resources; and
  - the 300 × 250 advertisement.
- The advertisement is a separate reserved placement, not squeezed into a
  content card.

The shared design system describes Job Search, Quick Links, Featured Resources,
and Advertisement as the baseline rail hierarchy. Available finder candidates
show different card sets. Exact card count, order, labels, and page-specific
variants remain unresolved until a reference is explicitly approved.

### 13.3 Responsive rail

The rail stacks after primary content at the approved breakpoint. Essential
actions may be promoted earlier only through an approved responsive reference.
Ad and card order, collapse behavior, and mobile visibility remain unresolved.

## 14. Advertising placements and reserved dimensions

Advertising is an intentional layout component with stable reserved space.

### 14.1 Medium rectangle

```text
Label: ADVERTISEMENT
Reserved creative: 300 × 250
Placement: right rail
```

- Aligns to the right-rail width.
- Appears as a separate placement below the approved supporting cards.
- Does not inherit a resource/alert card heading or padding.
- Reserved size prevents content shift when creative loads.

### 14.2 Leaderboard

```text
Label: ADVERTISEMENT
Reserved creative: 728 × 90
Placement: main content column
```

- Centered within the main-column width.
- On result pages, appears below pagination/result-range treatment and above the
  footer.
- Maintains at least 20px clear space above and below.
- Never extends outside the main column or 1200px shell.

### 14.3 Ad rules

- Advertising is visibly labeled.
- Ads do not masquerade as job listings, resources, or employer actions.
- Loading does not move primary controls or results.
- Ad technology must not create horizontal overflow.
- Empty inventory preserves or intentionally collapses space according to an
  approved ad policy.
- Mobile creative size, substitution, hiding, and reserved-space behavior are
  unresolved; desktop dimensions must not simply scale into illegibility.
- Sample creative in the repository is implementation/QA evidence, not approved
  brand content.

## 15. Pagination and result-count treatment

- Result count appears above listings near the sort context.
- Copy states the total clearly, for example **42 jobs found**.
- Pagination appears after the listing group and before the leaderboard.
- Result range accompanies pagination, for example **Showing 1–10 of 42 jobs**.
- Previous and Next have text labels; page numbers expose current state with
  `aria-current="page"`.
- Disabled Previous/Next remain understandable and non-interactive.
- Ellipsis is not interactive unless it opens an explicit page selection.
- Filter, sort, and mode state persist across pages.
- Browser history and URLs remain coherent with the supported search model.
- Pagination is visually unobtrusive but has full-size interaction targets.
- Mobile may reduce visible page-number count without removing Previous, Next,
  current page, or result range.

Exact alignment of result range relative to page controls remains component-
reference dependent.

## 16. Icons, including Save/heart behavior

### 16.1 General icon rules

- Simple outline style.
- Consistent stroke width, optical size, color, and baseline within a component.
- Icons do not replace labels when meaning is not universally clear.
- No emoji for interface metrics or actions.
- Decorative icons are hidden from assistive technology.
- Icon-only controls have accessible names and tooltips where helpful.

### 16.2 Save heart

- Save uses an outline heart, never a bookmark or ribbon.
- Every listing heart uses the same glyph, size, stroke weight, color, opacity,
  and offset.
- The heart is small, light-weight, and unobtrusive in its visual treatment.
- Desktop listing placement belongs in the compact secondary block, aligned
  with the salary row rather than floating at an inconsistent card top.
- Unsaved uses outline; saved may use a clearly distinct selected state, but the
  exact fill/color treatment remains unresolved pending approval.
- Hover, focus, pressed, saved, error, and login-required behavior do not shift
  the listing layout.
- The visible heart may be compact while its interactive hit target meets the
  control target requirement.
- Accessible name changes between **Save job** and **Remove saved job**.
- State is not communicated by fill/color alone.

## 17. Accessibility and focus requirements

### 17.1 Standard

Job Center V1 targets WCAG 2.2 AA for authored interfaces.

### 17.2 Keyboard and focus

- Every action is keyboard reachable in logical reading order.
- Focus-visible is always apparent with at least a 2px high-contrast indicator
  and adequate separation from the component edge.
- Opening progressive controls moves no focus unless the user's action requires
  it; newly revealed content follows the trigger in reading order.
- Closing a disclosure returns focus to its trigger when focus was inside.
- Modal/dialog behavior, if approved later, traps focus and restores it.
- Skip navigation reaches the primary Job Center content.

### 17.3 Semantics

- One page-level `h1`; headings descend logically.
- Navbar, breadcrumb, main content, complementary rail, pagination, and footer
  use appropriate landmarks.
- Listing collections use list/article semantics, not data-table semantics.
- Real data tables use headers and captions; public job listings are not tables.
- Form fields have programmatic labels, descriptions, required state, and
  error association.
- Status and notices are available as text, not color alone.
- Result changes and form outcomes are announced without excessive live-region
  noise.

### 17.4 Contrast and targets

- Text and controls meet WCAG AA contrast in every state.
- Muted text remains readable; placeholder text is not required information.
- Interactive targets meet WCAG 2.2 AA minimum target requirements and use a
  preferred approximately 44px target where layout permits.
- A visually small heart or icon retains a larger invisible/quiet hit area.
- Adjacent targets have enough separation to prevent accidental activation.

### 17.5 Motion and zoom

- Interaction does not depend on animation.
- Reduced-motion preference is honored.
- Content remains usable at 200% zoom and reflows without two-dimensional page
  scrolling at the supported narrow viewport.
- Sticky elements do not obscure focused controls or headings.

## 18. Approved-reference index

### 18.1 Status definitions

- **Approved:** explicitly named and accepted for a declared boundary.
- **Draft:** intentionally retained work in progress with no approval authority.
- **Superseded:** replaced by a later directed iteration or decision; historical
  evidence only.
- **Unclassified:** available artifact with no durable approval or draft record.

No available raster screenshot/mockup currently meets the durable evidence rule
for **Approved** status. This does not invalidate the approved shared design-
system rules; it means the exact approved landing artifact has not been mapped.

### 18.2 Future UX Atlas identifiers

These are reference-index destinations, not implementation tickets:

| Atlas ID | Intended boundary |
|---|---|
| JC-ATLAS-001 | Global app canvas, navbar, breadcrumb, and footer |
| JC-ATLAS-002 | Job Center landing page |
| JC-ATLAS-003 | Job Finder Search/Basic and Advanced states |
| JC-ATLAS-004 | Job Finder Browse/Basic and Advanced states |
| JC-ATLAS-005 | Search results listing, sort, chips, pagination, and ads |
| JC-ATLAS-006 | Job Detail |
| JC-ATLAS-007 | Saved Jobs and Job Alerts |
| JC-ATLAS-008 | Employer authority and employer switching |
| JC-ATLAS-009 | Employer Dashboard |
| JC-ATLAS-010 | Employer My Jobs |
| JC-ATLAS-011 | Post Job, Edit, Review, and Preview |
| JC-ATLAS-012 | Responsive system and mobile ordering |
| JC-ATLAS-013 | Advertising placements |

### 18.3 Visual artifact inventory

| Identifier or filename | Screen/component | Status | Authority notes | Future UX Atlas |
|---|---|---|---|---|
| `art/mockups/job-center/generated/design006-job-finder-state-2-v4.png` | Full Job Finder search/results shell | Superseded | Followed by requested removal of verification treatment and spacing changes. No authority. | JC-ATLAS-003, 005 |
| `art/mockups/job-center/generated/design006-job-finder-state-2-v5.png` | Job Finder listing revision | Superseded | Followed by right-rail, posting-age, and Save-heart revisions. No authority. | JC-ATLAS-005 |
| `art/mockups/job-center/generated/refined-search-model-01g-polished.png` | Job Finder polished listing/search composition | Superseded | Followed by reworked `01h` and `01j` source directions. No authority. | JC-ATLAS-003, 005 |
| `art/mockups/job-center/generated/refined-search-model-01h-panel-polish.png` | Compact three-row search/filter panel in full Finder | Superseded | Later reworked `01j` direction replaced this iteration. No authority. | JC-ATLAS-003, 005 |
| `art/mockups/job-center/generated/refined-search-model-01j-design-target.png` | Current strongest full Job Finder candidate | Unclassified | Produced from a requested target cleanup, but no durable post-render acceptance names this output Approved. Candidate only. | JC-ATLAS-001, 003, 005, 013 |
| `tmp/search-and-sort-panels-01d.png` and identical `tmp/design-refs/search-and-sort-panels-01d.png` | Search/Browse panel and result/sort comparison board | Unclassified | Byte-identical duplicate files; no approval record. Do not count as two references. | JC-ATLAS-003, 004, 005 |
| `tmp/search-panels-basic-and-advanced.png` and identical `tmp/design-refs/search-panels-basic-and-advanced.png` | Basic/Advanced Search panel comparison | Unclassified | Byte-identical duplicate files; useful progressive-disclosure evidence only. | JC-ATLAS-003 |
| `tmp/sort-panels-basic-and-advanced.png` and identical `tmp/design-refs/sort-panels-basic-and-advanced.png` | Basic/Advanced Browse or sort panel comparison | Unclassified | Byte-identical duplicate files; labels/functions require contract verification. | JC-ATLAS-004, 005 |
| `tmp/jfin001-desktop.png` | Early full Job Finder desktop | Unclassified | Temporary implementation screenshot; no approval evidence. | JC-ATLAS-001, 003, 005 |
| `tmp/jfin001-search-basic-desktop.png` | Early Basic Search desktop state | Unclassified | Temporary state capture; no approval evidence. | JC-ATLAS-003 |
| `tmp/jfin001-browse-basic-desktop.png` | Early Basic Browse desktop state | Unclassified | Temporary state capture; no approval evidence. | JC-ATLAS-004 |
| `tmp/jfin001-tablet.png`, `tmp/jfin001-narrow.png`, `tmp/jfin001-mobile.png` | Early Job Finder responsive states | Unclassified | Current/legacy evidence only; cannot freeze breakpoints or mobile order. | JC-ATLAS-012 |
| `tmp/jfin002-browse-basic-desktop.png` | Later Browse Basic desktop state | Unclassified | Filename sequence does not prove supersession or approval. | JC-ATLAS-004, 005 |
| `tmp/jfin002-browse-basic-tablet.png`, `tmp/jfin002-browse-basic-narrow.png`, `tmp/jfin002-browse-basic-mobile.png` | Later Browse Basic responsive states | Unclassified | Responsive evidence only; no approval record. | JC-ATLAS-012 |
| `tmp/jlanding001/jobs-desktop.png`, `jobs-desktop-final.png` | Landing iteration 001 desktop | Unclassified | “final” in filename does not prove approval. Exact approved landing artifact is unmapped. | JC-ATLAS-002 |
| `tmp/jlanding001/jobs-tablet.png`, `jobs-tablet-final.png` | Landing iteration 001 tablet | Unclassified | No durable approval mapping. | JC-ATLAS-002, 012 |
| `tmp/jlanding001/jobs-mobile.png`, `jobs-mobile-final.png`, `jobs-mobile-final-2.png` | Landing iteration 001 mobile variants | Unclassified | Multiple “final” variants demonstrate filename ambiguity. | JC-ATLAS-002, 012 |
| `tmp/jlanding002/jobs-desktop.png`, `jobs-tablet.png`, `jobs-mobile.png` | Landing iteration 002 responsive set | Unclassified | No documented screen-level approval. | JC-ATLAS-002, 012 |
| `tmp/jlanding003/jobs-desktop.png`, `jobs-tablet.png`, `jobs-mobile.png` | Landing iteration 003 responsive set | Unclassified | No documented screen-level approval. | JC-ATLAS-002, 012 |
| `tmp/jlanding004/jobs-desktop.png`, `jobs-tablet.png`, `jobs-mobile.png` | Landing iteration 004 responsive set | Unclassified | No documented screen-level approval. | JC-ATLAS-002, 012 |
| `tmp/jlanding005-toggle-style/jobs-desktop.png`, `jobs-tablet.png`, `jobs-mobile.png` | Landing toggle-style experiment | Unclassified | Directory name suggests an experiment but status is not documented; no authority. | JC-ATLAS-002, 012 |
| `tmp/jlanding007/jobs-search-desktop.png` | Landing/Job Finder Search desktop state | Unclassified | Current implementation evidence only. | JC-ATLAS-003, 005 |
| `tmp/jlanding007/jobs-browse-desktop.png`, `jobs-browse-tablet.png`, `jobs-browse-mobile.png` | Landing/Job Finder Browse responsive set | Unclassified | Current implementation evidence only. | JC-ATLAS-004, 005, 012 |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/hero-chalkboard-1200x450.webp` | Chalkboard hero artwork | Unclassified | Tracked implementation asset; no artifact-specific approval record. | JC-ATLAS-002 |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/hero-teacher-classroom.webp` | Classroom hero artwork | Unclassified | Tracked implementation asset; current use does not establish authority. | JC-ATLAS-002 |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/hero-teacher-classroom-01a.webp` | Classroom hero artwork variant | Unclassified | Variant relationship and approval are undocumented. | JC-ATLAS-002 |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/jobs-landing-header-slate-art-1200x453.webp` | Slate landing-header artwork | Unclassified | Tracked implementation asset; visually consistent with shared hero direction but not individually approved. | JC-ATLAS-002 |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/sample-MediumRectangle-01a.png` | Sample 300 × 250 ad creative | Unclassified | QA/sample creative only; placement dimensions are authoritative, creative is not. | JC-ATLAS-013 |

`jobs-landing-02a.png:Zone.Identifier` is an untracked filesystem metadata stream,
not a visual artifact, and is excluded from the reference index.

### 18.4 Approved-reference register

At v1.0, the register contains no filename-specific Approved visual artifact.
The following durable documents are authoritative design inputs rather than
raster references:

- `docs/design-system-v1.md` — approved Teachers.Net design foundation and
  unnamed approved landing-page-derived rules;
- `docs/job-center/canonical-v1-contract.md` — product/behavior truth;
- `docs/job-center/employer-ux-v1.md` — employer interaction and terminology;
- this document — Job Center visual/interaction system after acceptance.

## 19. Unresolved design needs

The following remain explicitly unresolved and must not be invented in future
audits, mockups, or implementation tickets:

1. Exact approved Job Center landing screenshot corresponding to the shared
   design-system approval statement.
2. Exact navbar item set, account actions, responsive navigation, and approved
   header height where shared prose and tokens differ.
3. Canonical body/UI font family and complete type scale.
4. Exact desktop main/rail gap and responsive breakpoints.
5. Approved non-landing page-heading/hero pattern.
6. Exact footer columns, newsletter treatment, social icons, and mobile order.
7. Search panel field order, row count, button hierarchy, disclosure labels, and
   mobile expansion behavior.
8. Compact Basic Location + Distance editor pending final design approval.
9. Search/Browse mode control treatment and URL/history feedback.
10. Exact listing padding, radius, summary length, posting-age treatment, and
    saved-heart selected state.
11. Job Detail desktop/mobile composition, application CTA placement, protected
    reveal treatment, and rail behavior.
12. Saved Jobs and Job Alerts visual references.
13. Employer claim, authority-review, and multi-employer-switching visual
    references.
14. Employer Dashboard visual composition.
15. Employer My Jobs visual composition, lifecycle grouping, metric labels, and
    archived-history treatment.
16. Post Job/Edit shared authoring, Review, Preview, validation, draft, and
    moderation visual patterns.
17. Exact semantic status palette for success, warning, error, Needs Attention,
    Draft, Awaiting Review, Live, Closed, Expired, and Archived.
18. Right-rail card count/order and page-specific variants.
19. Mobile advertising creative/placement behavior and empty-ad collapse policy.
20. Approved responsive references for landing, finder, detail, employer, saved
    jobs, alerts, pagination, and footer.
21. UX Atlas storage location, artifact naming convention, and approval-record
    format.

Each unresolved item requires a named approval boundary. Absence of a decision
does not authorize copying the current implementation or an Unclassified image.

## 20. Versioning and supersession rules

### 20.1 Version meaning

- **Patch (`1.0.x`)** — clarification that does not change component meaning,
  hierarchy, tokens, or approval status.
- **Minor (`1.x`)** — approved addition or bounded component refinement that
  remains compatible with the v1 product and visual language.
- **Major (`2.0`)** — incompatible change to canvas, navigation, typography,
  interaction model, listing composition, employer UX contract, or other
  foundational rule.

### 20.2 Change requirements

Every change to this document must record:

- decision owner and date;
- affected section/component;
- exact approved reference identifier when visual evidence applies;
- whether existing references are Approved, Draft, Superseded, or Unclassified;
- behavioral-contract dependency, if any;
- responsive and accessibility impact; and
- whether the UX Atlas index changes.

### 20.3 Reference supersession

- Approval is bounded to the named screen/component and state.
- A later artifact does not supersede an approved artifact by filename or date.
- Supersession requires an explicit decision naming the old and new reference
  and the boundary replaced.
- Superseded artifacts remain indexed for history and cannot be used as current
  implementation specifications.
- A derivative image is Unclassified until separately approved.
- Editing an Approved image does not transfer approval to the edit.
- If an Approved reference conflicts with the Canonical V1 Contract or Employer
  UX V1, the behavioral contract wins and the visual reference returns to review.

### 20.4 Implementation relationship

Approval of this document or a reference does not itself authorize code work.
Implementation requires a separate bounded ticket. After implementation and
QA, the accepted component may be recorded as the canonical realized reference.
Until then, current implementation remains evidence rather than visual authority.

## Document boundary

D001 creates documentation only. It does not approve any currently Unclassified
artifact, generate a mockup, create an implementation ticket, or modify Job
Center code, routes, templates, schema, styles, or behavior.
