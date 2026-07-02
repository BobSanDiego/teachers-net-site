# Teachers.Net Design System v1.0

**Status:** Design Target (North Star)
**Version:** 1.0
**Owner:** Teachers.Net
**Purpose:** Canonical visual design reference for all Teachers.Net frontend development.

---

# Philosophy

Teachers.Net is **not** another employment website.

It is the professional home of educators.

Every interface should communicate:

- professionalism
- trust
- clarity
- calm
- efficiency
- longevity

The design language should feel more like Bloomberg, Apple, Linear, or Stripe than Indeed, Monster, or CareerBuilder.

We optimize for information density without visual clutter.

---

# Primary Goals

1. Help teachers find information quickly.
2. Encourage additional page views.
3. Encourage account creation.
4. Encourage Job Alert subscriptions.
5. Promote relevant Teachers.Net resources.
6. Maximize sponsor inventory without overwhelming content.
7. Maintain one consistent design language across all site modules.

---

# Layout

## Jobs app canvas

1200px

All visible Jobs application UI lives inside one centered 1200px app canvas.
Outside that canvas, only the browser/page background should be visible. Do not
run Jobs-owned header, hero, footer, card, rail, sponsor, or section background
bands full-width beyond the app canvas.

Implementation rule:

```css
.tnet-jobs-app-canvas {
  max-width: 1200px;
  margin-inline: auto;
  width: 100%;
}

--tnet-jobs-shell-width: 100%;
--tnet-jobs-canvas-gutter: 32px;
--tnet-jobs-shell-inset: var(--tnet-jobs-canvas-gutter);
```

All Jobs public page-level containers must live inside `.tnet-jobs-app-canvas`.
Header/nav background, hero background, search, browse/search results, detail,
saved jobs, alerts, employer-facing public pages, and footer background/content
must be contained by that canvas. Below tablet widths, the canvas may fill the
viewport and the canvas gutter reduces to `16px`.

Canvas content uses an internal gutter. The outer app canvas remains 1200px,
but visible content inside shared page containers should not sit flush against
the canvas edge.

```css
--tnet-jobs-canvas-gutter: 32px;
```

At tablet/mobile widths, reduce the gutter to `16px` so controls remain usable.

## Canvas edge and intentional inset

The layout has two alignment tiers:

1. Canvas edge: the canonical 1200px app boundary.
2. Intentional inset: a documented inner content line inside that shell.

The following outer containers must align to the canvas edge:

- header/nav content
- browse grid
- search results wrappers
- detail wrappers
- saved jobs wrappers
- alerts wrappers
- employer-facing public wrappers
- footer outer content

These canvas-edge containers apply the shared internal canvas gutter so visible
content does not sit flush against the canvas edge.

The following may align to the intentional inset:

- browse hero visible text/content
- browse search panel/card

Internal components may be narrower inside the shell only when they use a
documented inset variable or a documented component-specific exception. Do not
introduce competing 1040px, 1120px, 1180px, or 1220px max-widths as page-level
containers.

Verification must measure both the outer canvas and visible content edges. For
canvas-edge containers, compare the wrapper left/right to the canonical canvas,
then confirm visible content honors the internal gutter. For inset components,
compare the visible component left/right to the documented inset line. At
desktop widths wider than 1200px, no Jobs-owned visible pixels or background
bands should appear outside the app canvas.

---

## Design Tokens

Jobs public CSS should use a compact shared token layer for values that are
expected to recur across pages and components.

Canonical layout tokens:

```css
--tnet-jobs-canvas-width: 1200px;
--tnet-jobs-canvas-gutter: 32px;
--tnet-jobs-shell-width: 100%;
```

At tablet/mobile widths, the canvas gutter reduces to `16px`.

Canonical color tokens:

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

Canonical shape/control tokens:

```css
--tnet-jobs-radius-button: 6px;
--tnet-jobs-radius-card: 14px;
--tnet-jobs-radius-input: 6px;
--tnet-jobs-control-height: 36px;
--tnet-jobs-button-height: 40px;
```

Canonical header/hero typography tokens:

```css
--tnet-jobs-header-height: 105px;
--tnet-jobs-header-action-width: 74px;
--tnet-jobs-header-action-height: 44px;
--tnet-jobs-logo-font-size: 36px;
--tnet-jobs-logo-subtitle-font-size: 16px;
--tnet-jobs-hero-title-font-size: 38px;
--tnet-jobs-hero-subtitle-font-size: 38px;
```

The Jobs landing header and hero use `Arial, Helvetica, sans-serif` for the
Teachers.Net wordmark, Job Center subtitle, hero headline, and hero supporting
copy. Responsive breakpoints may reduce spacing and stacking behavior while
preserving the desktop token targets.

Component-specific illustration colors, gradients, shadows, exact table column
math, and one-off emphasis treatments may remain local when tokenizing them
would make the system harder to read or accidentally redesign a component.

---

## Content Width

Desktop:

1200px

Centered.

---

## Responsive

Maintain proportional spacing.

Do not redesign layouts at intermediate widths.

Collapse only when required.

---

# Header

Simple.

White background.

Approximately 72px tall.

---

## Left

Teachers.Net logo

Black wordmark.

Blue "JOB CENTER" designation beneath.

No circular TN icon.

---

## Center Navigation

Jobs

Lessons

Chatboards

More ▼

Only Jobs highlighted while inside Job Center.

---

## Right

Saved

Notifications

Login / Avatar

Minimal.

---

# Hero

Dark slate blue textured chalkboard.

Education-themed chalk illustration.

Illustration remains decorative.

Headline always has visual priority.

---

## Hero Height

Approximately 340–360px.

---

## Hero Text

Large.

Friendly.

Professional.

Never oversized.

Headline width constrained.

Do not allow typography to grow beyond intended desktop size.

---

## Search Panel

White card.

Floats over lower portion of hero.

Subtle shadow.

Rounded corners.

Contains:

- Keyword
- Grade Level
- Location
- Search Button
- Advanced Search

---

# Search Controls

Consistent control heights.

Generous padding.

Primary CTA aligned right.

Advanced Search understated.

---

# Filter Toolbar

Appears immediately above search results.

Contains:

Sort

Filters

Location

Job Type

Salary

Additional filters may be added later.

---

# Search Results

Primary purpose:

Fast comparison.

Optimize for scanning.

---

## Listing Density

Compact.

Approximately 60–70px per row.

Whitespace separates rows.

Avoid heavy separators.

---

## Listing Layout

Job Title

School • City, State

Grade Level

Subject

Salary

Type

Posted

Save

No redundant buttons.

No unnecessary icons.

---

## Salary

Dedicated column.

Strong visual emphasis.

Always encourage employers to include salary.

Support:

Range

Single salary

Hourly

Contract

Salary should become a primary filtering dimension.

---

## Save

Simple heart.

No heavy CTA.

---

## Results Counter

Top left.

Above listings.

Example:

1–10 of 478

Never inside the right rail.

---

# Advertisement Placement

Advertising should breathe.

Never feel inserted.

---

## Leaderboard

Placed between listing groups.

Minimum 20px spacing above.

Minimum 20px spacing below.

728x90

---

## Right Rail

300px width.

Contains:

Job Alerts

Quick Links

Resources

300x250 Medium Rectangle

---

# Job Alerts

One of the highest-value site features.

Promote consistently.

Include opportunities to create alerts from:

Searches

Subjects

Locations

Employers

Categories

Future:

Individual schools

Districts

---

# Right Rail Hierarchy

1. Job Search

Create Alert

Save Search

Manage Alerts

---

2. Quick Links

Saved Jobs

Recent Searches

Browse by Subject

Browse by Location

Browse by Employer

---

3. Featured Resources

Resume Tips

Interview Tips

Salary Guide

Teaching Resources

---

4. Advertisement

300x250 Medium Rectangle

---

# Pagination

Bottom of listings.

Simple.

Unobtrusive.

Previous

Page Numbers

Next

---

# Footer

Consistent site footer.

Dark blue.

Five-column layout.

Teachers.Net identity.

Site navigation.

Community links.

Resources.

About.

Social icons.

Copyright.

Used across entire site.

---

# Color System

Primary Link

`#0017DD`

Use for interactive text links, including job titles, inline links, navigation
links, resource links, Advanced Search, Manage Job Alerts, pagination links,
and table links.

---

Primary Button

`#0033C7`

Use for primary action button backgrounds, including Find Jobs, Create Job
Alert, Login, primary form submits, primary recruiter actions, and wizard
primary buttons.

---

Primary Blue

Teachers.Net brand blue.

Used sparingly.

Reserved for:

Primary CTA

Navigation highlight

Important actions

---

Dark Slate

Hero backgrounds.

Footer.

Professional accents.

---

White

Primary content surface.

---

Light Gray

Cards.

Table backgrounds.

Subtle separators.

---

Borders

Extremely light.

Never dominate.

---

# Typography

Friendly.

Professional.

Readable.

Avoid overly geometric faces.

Avoid condensed styles.

Clear hierarchy.

Large headlines.

Medium section headings.

Compact metadata.

---

# Cards

Soft corners.

Minimal shadows.

Generous whitespace.

---

# Tables

Information first.

Decoration second.

Whitespace preferred over borders.

---

# Icons

Simple outline style.

Consistent stroke width.

Never oversized.

Avoid emoji.

---

# Component Library

The following components are considered canonical and should be reused throughout Teachers.Net.

- Site Header
- Hero
- Hero Search Panel
- Filter Toolbar
- Results Table
- Right Rail
- Job Alert Card
- Quick Links Card
- Resources Card
- Advertisement Blocks
- Leaderboard
- Pagination
- CTA Banner
- Footer

---

# Reuse Strategy

Future modules should reuse this system rather than invent new layouts.

Including:

Jobs

Lessons

Chatboards

Employer Profiles

School Profiles

District Profiles

Member Directory

Resources

Events

Classifieds

Search

---

# Engineering Rules

Visual implementation tickets should:

- reference this document
- change one component at a time
- avoid architectural changes
- preserve spacing rhythm
- preserve typography hierarchy

Implementation should always favor the smallest coherent diff.

---

# Design Governance

This document is the canonical visual reference.

Mockups are exploratory.

Once a component is implemented and approved, this document becomes the source of truth.

Future refinements should update this document before they become implementation targets.

---

# Current Status

Design System v1.0 is based on the approved Teachers.Net Job Center landing page.

Future pages should extend this system rather than replace it.

The objective is a cohesive Teachers.Net product family sharing one visual language.

---

# North Star Principles

When evaluating a proposed UI change, ask:

1. Does it help teachers accomplish their goal faster?

2. Does it reduce visual noise?

3. Does it increase information density without sacrificing readability?

4. Does it encourage another meaningful page view?

5. Does it create an opportunity for Job Alerts, Saved Searches, or account creation?

6. Does it preserve the Teachers.Net design language?

7. Is this a reusable component rather than a page-specific solution?

If the answer to any of these is "no," reconsider the implementation before coding.
