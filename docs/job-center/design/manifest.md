# Job Center Visual Reference Manifest

## Manifest authority

This manifest implements the reference-control rules in Job Center Design System v1. It inventories known visual artifacts and governed visual gaps as of D003. It does not approve a design by implication.

JC-011 is the first Approved filename-specific reference. JC-010 is now also Approved through VA002-FINAL. Each Engineering Director decision names an exact raster and bounded desktop Job Finder state. No other manifest entry inherits either approval; landing images and all other uncertain artifacts retain their existing status until separately approved.

Status totals for manifest entries: **Approved 2; Draft 9; Placeholder 15; Superseded 4.** Artifact paths are inventoried separately below; multiple artifacts can support one entry.

## Entry rules

- IDs are permanent.
- Version `0.x` denotes a candidate or placeholder; version `1.0` is reserved for a first Approved reference.
- Authority describes why the status is valid, not who created the image.
- Implementation-ticket labels inferred from filenames are evidence labels only and do not establish ticket authority.
- UX Atlas identifiers are reserved future entries from Job Center Design System v1.

## Shell, landing, and finder

### JC-001 — Approved page shell

- **Screen / Component:** Global desktop shell: navbar, breadcrumb, hero, canvas, footer
- **Status:** Draft
- **Version:** 0.1
- **Authority:** D001 fixes the written shell rules, but no exact shell artifact has durable approval evidence.
- **Related Design System section:** 4, 7
- **Related Product Specification:** Canonical V1 Contract; Employer UX V1 where the shell contains employer views
- **Related future UX Atlas entry:** JC-ATLAS-001
- **Related implementation ticket(s):** None assigned
- **Notes:** Candidate shell treatments occur across JC-002, JC-011, and JC-020 artifacts. No candidate is a conformance target.

### JC-002 — Job Center landing

- **Screen / Component:** Job Center landing, Search/Browse entry states, responsive variants
- **Status:** Draft
- **Version:** 0.7
- **Authority:** JLANDING artifacts exist; filenames and iteration order do not establish approval.
- **Related Design System section:** 4, 7, 9, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-002
- **Related implementation ticket(s):** JLANDING001–JLANDING007 (artifact labels only)
- **Notes:** Includes desktop, tablet, and mobile explorations. `final` in a filename is non-authoritative.

### JC-010 — Job Finder State 1

- **Screen / Component:** Logged-out first-touch `/jobs/` state with full inventory sorted by Most Recent
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/job-finder-state-1-01b-design-target.png`
- **Approval date:** 2026-07-12
- **Approval authority:** Engineering Director, VA002-FINAL
- **Approval scope:** Desktop logged-out first-touch `/jobs/` State 1 exactly as shown: default breadcrumb and heading, closed integrated search control, collapsed Browse and Refine actions, full-inventory heading and Most Recent sort, ten-listing inventory within this screen, logged-out conversion rail and remaining rail order, desktop advertising placements, pagination/result range, leaderboard, and footer.
- **Authority:** VA002-FINAL explicitly approves the exact 01b raster as the canonical JC-010 implementation and QA reference within the stated boundary.
- **Related Design System section:** 9
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** Represents logged-out first-touch `/jobs/` before keyword, location, browse, or refinement input: full 205-job inventory, first ten jobs, and default Most Recent sort. Lineage is 0.1 Placeholder → DESIGN008 01a Draft 0.2 → DESIGN009 01b → VA002-FINAL Approved 1.0. The 01a artifact remains preserved as Draft history. Browse reveal and the location modal remain separate future artifacts. The visible `25 miles` control is illustrative; implementation must hide or disable distance until a valid location or origin exists. Future implementation should reduce the visual size and stroke weight of the listing Save (heart) icon. This refinement is intentionally deferred to implementation and does not affect approval of JC-010. Approval is bounded to JC-010 and does not independently approve JC-001, JC-020, JC-021, JC-024, JC-070, unseen interaction states, or implementation.

### JC-011 — Job Finder State 2

- **Screen / Component:** Refined search/results state and locked page shell
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/refined-search-model-01k-design-target.png`
- **Approval date:** 2026-07-12
- **Approval authority:** Engineering Director, VA001-FINAL
- **Approval scope:** Desktop Job Finder State 2 exactly as shown: constrained page shell, compact refined-search/results state, applied chips, results header and sort, ten-listing composition within this screen, right rail, desktop advertising placements, pagination/result range, leaderboard, and footer.
- **Authority:** VA001-FINAL explicitly approves the exact 01k raster as the canonical JC-011 implementation and QA reference within the stated boundary.
- **Related Design System section:** 4, 7, 9, 10, 13–16
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** DESIGN007 derived 01k from 01j solely to change `Clear` to `Clear filters` and correct the bottom leaderboard reservation identified by VA001. Lineage is DESIGN006 v4 → v5 → refined-search 01g → 01h → 01j → approved 01k. JC-090–JC-093 preserve the explicitly Superseded iterations; 01j remains a historical Draft. Approval is bounded to JC-011 and does not independently approve JC-001, JC-020, JC-021, JC-024, responsive states, interaction behavior not visible in the raster, or implementation.

### JC-012 — Job Finder State 3

- **Screen / Component:** Further-expanded or subsequent Job Finder state
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** No governed artifact identified.
- **Related Design System section:** 9
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** State boundary and visual treatment remain unresolved.

### JC-013 — Expert Search

- **Screen / Component:** Expert/advanced search controls
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Panel explorations exist, but no artifact is governed as the complete Expert Search screen.
- **Related Design System section:** 8, 9, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** The advanced panel images are Draft evidence under JC-021.

### JC-014 — Job Finder Location Selection Modal

- **Screen / Component:** Logged-out first-touch `/jobs/`, Location segment activated, modal open
- **Status:** Draft
- **Version:** 0.1
- **Active Draft Artifact:** `art/mockups/job-center/generated/job-finder-location-modal-lo-01a-design-target.png`
- **Authority:** DESIGN010 creates the logged-out 01a modal as the canonical Draft candidate for review. It has no approval authority.
- **Related Design System section:** 8, 9, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** JC-010 v1.0 is the locked background authority; this artifact does not revise or supersede it. The logged-out artifact governs the shared modal structure. Logged-in implementation uses the same modal but omits the `Sign in to access advanced location features.` teaser; do not create a separate logged-in raster unless later review identifies a meaningful visual difference. Distance remains dependent on a valid location or origin, and Apply remains inactive until one is established. Browse Reveal remains a separate future artifact. This Draft incorporates the deferred smaller, lighter listing Save-heart treatment without changing the Approved JC-010 raster.

### JC-020 — Canonical listing

- **Screen / Component:** Two-zone professional directory entry
- **Status:** Draft
- **Version:** 0.6
- **Authority:** D001 governs composition in writing; refined-search and JFIN images are candidates without exact artifact approval.
- **Related Design System section:** 10, 16, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-005
- **Related implementation ticket(s):** JFIN001, JFIN002 (artifact labels only)
- **Notes:** Narrative metadata stays left; salary, distance, and outline Save heart occupy the compact secondary block. Written rules are authoritative while the visual reference remains Draft.

### JC-021 — Progressive search and sort panels

- **Screen / Component:** Basic/advanced Search and Sort controllers
- **Status:** Draft
- **Version:** 0.1
- **Authority:** Component-panel artifacts exist without approval evidence.
- **Related Design System section:** 8, 9, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** Duplicate stored paths are inventoried below; byte identity does not create authority.

### JC-022 — Results count and pagination

- **Screen / Component:** Result-count copy, page controls, and their placement
- **Status:** Draft
- **Version:** 0.1
- **Authority:** Candidate full-page images exist; no component-level artifact is approved.
- **Related Design System section:** 15, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-005
- **Related implementation ticket(s):** JFIN001, JFIN002 (artifact labels only)
- **Notes:** D001 written rules control until an exact reference is approved.

### JC-023 — Results right rail

- **Screen / Component:** Results secondary rail and its card structure
- **Status:** Draft
- **Version:** 0.1
- **Authority:** Present in candidate refined-search images; no exact rail artifact is approved.
- **Related Design System section:** 13, 14
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-005, JC-ATLAS-013
- **Related implementation ticket(s):** None assigned
- **Notes:** Advertising dimensions may be governed in writing without approving surrounding rail composition.

### JC-024 — Advertising placements

- **Screen / Component:** 300 × 250 right-rail and 728 × 90 leaderboard reservations
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** D001 governs placement dimensions; the available sample creative is illustrative and no placement mockup is separately approved.
- **Related Design System section:** 14
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-013
- **Related implementation ticket(s):** None assigned
- **Notes:** Reserved space is intentional. `sample-MediumRectangle-01a.png` is not approved creative.

## Job-seeker destinations

### JC-030 — Job Detail

- **Screen / Component:** Public canonical job-detail view
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** No governed artifact identified.
- **Related Design System section:** 11, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-006
- **Related implementation ticket(s):** None assigned
- **Notes:** Presentation remains explicitly unresolved beyond written principles.

### JC-040 — Saved Jobs

- **Screen / Component:** Job-seeker saved-jobs destination and states
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** No governed artifact identified.
- **Related Design System section:** 8, 12, 16, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-007
- **Related implementation ticket(s):** None assigned
- **Notes:** Includes signed-out transition, empty, populated, and removal states.

### JC-041 — Alerts

- **Screen / Component:** Job alerts creation and management
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** No governed artifact identified.
- **Related Design System section:** 8, 9, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-007
- **Related implementation ticket(s):** None assigned
- **Notes:** Function and visual states remain unresolved unless separately specified by product governance.

## Employer interfaces

### JC-050 — Employer Dashboard

- **Screen / Component:** Employer summary dashboard
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 defines purpose; no governed visual artifact identified.
- **Related Design System section:** 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-009
- **Related implementation ticket(s):** None assigned
- **Notes:** Dashboard summarizes; it does not duplicate My Jobs management.

### JC-051 — Employer My Jobs

- **Screen / Component:** Employer job-management inventory
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 defines purpose; no governed visual artifact identified.
- **Related Design System section:** 8, 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-010
- **Related implementation ticket(s):** None assigned
- **Notes:** Required lifecycle and action states need future approved references.

### JC-052 — Employer Wizard

- **Screen / Component:** Create/Edit job wizard
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 defines interaction principles; no governed visual artifact identified.
- **Related Design System section:** 8, 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-011
- **Related implementation ticket(s):** None assigned
- **Notes:** Create and Edit share patterns; exact step layouts remain unresolved.

### JC-053 — Employer Claim

- **Screen / Component:** Claim Existing Employer / Request New Employer
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 defines lifecycle placement; no governed visual artifact identified.
- **Related Design System section:** 8, 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-008
- **Related implementation ticket(s):** None assigned
- **Notes:** Authority, trust, pending, denial, and switching states need references.

### JC-054 — Employer Review

- **Screen / Component:** Pre-submission review
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 distinguishes Review from Preview; no governed artifact identified.
- **Related Design System section:** 8, 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-011
- **Related implementation ticket(s):** None assigned
- **Notes:** Review validates entered data and submission readiness.

### JC-055 — Employer Preview

- **Screen / Component:** Employer-facing public-presentation preview
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 distinguishes Preview from Review; no governed artifact identified.
- **Related Design System section:** 11, 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-011
- **Related implementation ticket(s):** None assigned
- **Notes:** Preview must share the public presentation model without implying publication.

### JC-056 — Employer Metrics

- **Screen / Component:** Employer engagement reporting
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** Employer UX V1 freezes the metrics model; no governed visual artifact identified.
- **Related Design System section:** 12, 17
- **Related Product Specification:** Employer UX V1; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-009, JC-ATLAS-010
- **Related implementation ticket(s):** None assigned
- **Notes:** Views, Saved, and Interested require clear definitions and time context.

## Operations and cross-viewport needs

### JC-060 — Moderator

- **Screen / Component:** Moderator review and lifecycle controls
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** No governed visual artifact identified.
- **Related Design System section:** 8, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** Not yet assigned
- **Related implementation ticket(s):** None assigned
- **Notes:** Future UX Atlas coverage must be assigned before visual approval.

### JC-061 — Administration

- **Screen / Component:** Job Center administration
- **Status:** Placeholder
- **Version:** 0.1
- **Authority:** No governed visual artifact identified.
- **Related Design System section:** 8, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** Not yet assigned
- **Related implementation ticket(s):** None assigned
- **Notes:** Administrative boundaries and states remain unresolved.

### JC-070 — Responsive behavior

- **Screen / Component:** Landing, Finder, and results at desktop, tablet, narrow, and mobile viewports
- **Status:** Draft
- **Version:** 0.1
- **Authority:** Responsive screenshots exist, but none has durable approval evidence.
- **Related Design System section:** 4, 12, 17
- **Related Product Specification:** Canonical V1 Contract; Employer UX V1
- **Related future UX Atlas entry:** JC-ATLAS-012
- **Related implementation ticket(s):** JFIN001, JFIN002, JLANDING001–JLANDING007 (artifact labels only)
- **Notes:** Approval must name each covered breakpoint/state; desktop approval would not imply mobile approval.

### JC-080 — Landing hero artwork

- **Screen / Component:** Job Center landing hero imagery
- **Status:** Draft
- **Version:** 0.1
- **Authority:** Assets in the Jobs plugin are current implementation evidence, not visual authority.
- **Related Design System section:** 7
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-002
- **Related implementation ticket(s):** None assigned
- **Notes:** Tracking in the plugin does not approve an asset or a crop.

## Superseded design candidates

### JC-090 — DESIGN006 State 2 v4

- **Screen / Component:** Job Finder State 2 full-page candidate
- **Status:** Superseded
- **Version:** 0.4
- **Authority:** Replaced in the identified iteration lineage by v5.
- **Related Design System section:** 4, 7, 9, 10, 13–16
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** Supersession does not approve v5.

### JC-091 — DESIGN006 State 2 v5

- **Screen / Component:** Job Finder State 2 full-page candidate
- **Status:** Superseded
- **Version:** 0.5
- **Authority:** Replaced in the identified iteration lineage by refined-search 01g.
- **Related Design System section:** 4, 7, 9, 10, 13–16
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** Supersession does not approve 01g.

### JC-092 — Refined Search 01g

- **Screen / Component:** Refined results/listing full-page candidate
- **Status:** Superseded
- **Version:** 0.5.1
- **Authority:** Replaced in the identified iteration lineage by 01h.
- **Related Design System section:** 4, 7, 9, 10, 13–16
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** Supersession does not approve 01h.

### JC-093 — Refined Search 01h

- **Screen / Component:** Refined search-panel full-page candidate
- **Status:** Superseded
- **Version:** 0.5.2
- **Authority:** Replaced in the identified iteration lineage by 01j.
- **Related Design System section:** 4, 7, 9, 10, 13–16
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** Supersession does not approve 01j; 01j remains Draft under JC-011 and JC-020.

## Artifact inventory

The status below is the artifact's governed classification. A path may relate to several component entries; the primary entry is listed. Duplicate files remain separate inventory rows because each known stored artifact must be traceable.

| Artifact | Represents | Status | Manifest entry | Authority note |
|---|---|---:|---|---|
| `art/mockups/job-center/generated/job-finder-location-modal-lo-01a-design-target.png` | Logged-out first-touch Location modal open | Draft | JC-014 | Active DESIGN010 candidate; shared modal structure with logged-out teaser |
| `art/mockups/job-center/generated/job-finder-state-1-01a-design-target.png` | Logged-out first-touch Job Finder, full inventory, Most Recent | Draft | JC-010 | Historical DESIGN008 Draft retained after 01b approval |
| `art/mockups/job-center/generated/job-finder-state-1-01b-design-target.png` | Polished logged-out first-touch Job Finder | Approved | JC-010 | Exact source approved by VA002-FINAL for JC-010 only |
| `docs/job-center/design/approved/job-finder-state-1-01b-design-target.png` | Controlled JC-010 reference copy | Approved | JC-010 | Canonical library copy; byte-identical to the approved source artifact |
| `art/mockups/job-center/generated/design006-job-finder-state-2-v4.png` | Finder State 2 | Superseded | JC-090 | Replaced by v5 candidate |
| `art/mockups/job-center/generated/design006-job-finder-state-2-v5.png` | Finder State 2 | Superseded | JC-091 | Replaced by 01g candidate |
| `art/mockups/job-center/generated/refined-search-model-01g-polished.png` | Results/listing | Superseded | JC-092 | Replaced by 01h candidate |
| `art/mockups/job-center/generated/refined-search-model-01h-panel-polish.png` | Search panel/results | Superseded | JC-093 | Replaced by 01j candidate |
| `art/mockups/job-center/generated/refined-search-model-01j-design-target.png` | Finder State 2/canonical listing | Draft | JC-011, JC-020 | Prior active Draft candidate; retained in lineage after DESIGN007 |
| `art/mockups/job-center/generated/refined-search-model-01k-design-target.png` | Finder State 2/canonical listing | Approved | JC-011, JC-020 | Exact source approved by VA001-FINAL for JC-011 only; JC-020 remains Draft |
| `docs/job-center/design/approved/refined-search-model-01k-design-target.png` | Controlled JC-011 reference copy | Approved | JC-011 | Canonical library copy; byte-identical to the approved source artifact |
| `tmp/search-and-sort-panels-01d.png` | Search and Sort panels | Draft | JC-021 | Candidate component sheet |
| `tmp/design-refs/search-and-sort-panels-01d.png` | Search and Sort panels | Draft | JC-021 | Byte-identical duplicate path |
| `tmp/search-panels-basic-and-advanced.png` | Search panels | Draft | JC-021 | Candidate component sheet |
| `tmp/design-refs/search-panels-basic-and-advanced.png` | Search panels | Draft | JC-021 | Byte-identical duplicate path |
| `tmp/sort-panels-basic-and-advanced.png` | Sort panels | Draft | JC-021 | Candidate component sheet |
| `tmp/design-refs/sort-panels-basic-and-advanced.png` | Sort panels | Draft | JC-021 | Byte-identical duplicate path |
| `tmp/jfin001-desktop.png` | Finder desktop | Draft | JC-020, JC-070 | JFIN001 candidate |
| `tmp/jfin001-search-basic-desktop.png` | Finder basic Search desktop | Draft | JC-021 | JFIN001 candidate |
| `tmp/jfin001-browse-basic-desktop.png` | Finder basic Browse desktop | Draft | JC-021 | JFIN001 candidate |
| `tmp/jfin001-tablet.png` | Finder tablet | Draft | JC-070 | JFIN001 candidate |
| `tmp/jfin001-narrow.png` | Finder narrow | Draft | JC-070 | JFIN001 candidate |
| `tmp/jfin001-mobile.png` | Finder mobile | Draft | JC-070 | JFIN001 candidate |
| `tmp/jfin002-browse-basic-desktop.png` | Finder Browse desktop | Draft | JC-021, JC-070 | JFIN002 candidate |
| `tmp/jfin002-browse-basic-tablet.png` | Finder Browse tablet | Draft | JC-021, JC-070 | JFIN002 candidate |
| `tmp/jfin002-browse-basic-narrow.png` | Finder Browse narrow | Draft | JC-021, JC-070 | JFIN002 candidate |
| `tmp/jfin002-browse-basic-mobile.png` | Finder Browse mobile | Draft | JC-021, JC-070 | JFIN002 candidate |
| `tmp/jlanding001/jobs-desktop.png` | Landing desktop | Draft | JC-002 | JLANDING001 candidate |
| `tmp/jlanding001/jobs-desktop-final.png` | Landing desktop | Draft | JC-002 | `final` is not approval evidence |
| `tmp/jlanding001/jobs-tablet.png` | Landing tablet | Draft | JC-002, JC-070 | JLANDING001 candidate |
| `tmp/jlanding001/jobs-tablet-final.png` | Landing tablet | Draft | JC-002, JC-070 | `final` is not approval evidence |
| `tmp/jlanding001/jobs-mobile.png` | Landing mobile | Draft | JC-002, JC-070 | JLANDING001 candidate |
| `tmp/jlanding001/jobs-mobile-final.png` | Landing mobile | Draft | JC-002, JC-070 | `final` is not approval evidence |
| `tmp/jlanding001/jobs-mobile-final-2.png` | Landing mobile | Draft | JC-002, JC-070 | `final` is not approval evidence |
| `tmp/jlanding002/jobs-desktop.png` | Landing desktop | Draft | JC-002 | JLANDING002 candidate |
| `tmp/jlanding002/jobs-tablet.png` | Landing tablet | Draft | JC-002, JC-070 | JLANDING002 candidate |
| `tmp/jlanding002/jobs-mobile.png` | Landing mobile | Draft | JC-002, JC-070 | JLANDING002 candidate |
| `tmp/jlanding003/jobs-desktop.png` | Landing desktop | Draft | JC-002 | JLANDING003 candidate |
| `tmp/jlanding003/jobs-tablet.png` | Landing tablet | Draft | JC-002, JC-070 | JLANDING003 candidate |
| `tmp/jlanding003/jobs-mobile.png` | Landing mobile | Draft | JC-002, JC-070 | JLANDING003 candidate |
| `tmp/jlanding004/jobs-desktop.png` | Landing desktop | Draft | JC-002 | JLANDING004 candidate |
| `tmp/jlanding004/jobs-tablet.png` | Landing tablet | Draft | JC-002, JC-070 | JLANDING004 candidate |
| `tmp/jlanding004/jobs-mobile.png` | Landing mobile | Draft | JC-002, JC-070 | JLANDING004 candidate |
| `tmp/jlanding005-toggle-style/jobs-desktop.png` | Landing toggle style desktop | Draft | JC-002 | JLANDING005 candidate |
| `tmp/jlanding005-toggle-style/jobs-tablet.png` | Landing toggle style tablet | Draft | JC-002, JC-070 | JLANDING005 candidate |
| `tmp/jlanding005-toggle-style/jobs-mobile.png` | Landing toggle style mobile | Draft | JC-002, JC-070 | JLANDING005 candidate |
| `tmp/jlanding007/jobs-search-desktop.png` | Landing Search desktop | Draft | JC-002, JC-021 | JLANDING007 candidate |
| `tmp/jlanding007/jobs-browse-desktop.png` | Landing Browse desktop | Draft | JC-002, JC-021 | JLANDING007 candidate |
| `tmp/jlanding007/jobs-browse-tablet.png` | Landing Browse tablet | Draft | JC-002, JC-070 | JLANDING007 candidate |
| `tmp/jlanding007/jobs-browse-mobile.png` | Landing Browse mobile | Draft | JC-002, JC-070 | JLANDING007 candidate |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/hero-chalkboard-1200x450.webp` | Landing hero artwork | Draft | JC-080 | Implementation asset only |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/hero-teacher-classroom.webp` | Landing hero artwork | Draft | JC-080 | Implementation asset only |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/hero-teacher-classroom-01a.webp` | Landing hero artwork | Draft | JC-080 | Implementation asset only |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/jobs-landing-header-slate-art-1200x453.webp` | Landing hero artwork | Draft | JC-080 | Implementation asset only |
| `wordpress/wp-content/plugins/tnet-jobs/public/assets/images/sample-MediumRectangle-01a.png` | Sample 300 × 250 ad creative | Placeholder | JC-024 | Illustrative creative only |

`jobs-landing-02a.png:Zone.Identifier` and the `Zone.Identifier` files beside temporary panel images are filesystem metadata streams, not renderable visual artifacts; they are intentionally excluded from the artifact inventory.

## Approval gaps

The `approved/` directory contains the JC-010 v1.0 and JC-011 v1.0 controlled references. Separate approval decisions are still required for the shell as JC-001, landing, other Finder states, the JC-014 Location modal, progressive Search, canonical listing as JC-020, advertising as JC-024, and responsive variants. Employer, Job Detail, Saved Jobs, Alerts, Moderator, and Administration references remain governed placeholders and must not be inferred from current implementation or from JC-010/JC-011 approval.
