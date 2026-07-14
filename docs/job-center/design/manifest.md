# Job Center Visual Reference Manifest

## Manifest authority

This manifest implements the reference-control rules in Job Center Design System v1. It inventories known visual artifacts and governed visual gaps as of D003. It does not approve a design by implication.

JC-011 is the first Approved filename-specific reference. JC-010, JC-014,
JC-015, JC-030, JC-010 Mobile, JC-010 Tablet, JC-011 Tablet, JC-011 Mobile,
JC-014 Tablet, and mobile navigation drawer components JC-003 and JC-004 are
also Approved through explicit Engineering Director decisions. Each decision
names an exact raster and bounded desktop, mobile, or tablet screen, state,
or component. No other manifest entry inherits those approvals; landing images
and all other uncertain artifacts retain their existing status until separately
approved.

**Search & Discovery Interaction Suite v1 — Complete.** Approved JC-010,
JC-014, JC-015, and JC-011 together define the canonical desktop v1 journey
from first-touch discovery through location selection or browse exploration to
search results. Suite membership does not broaden any artifact's individual
approval scope.

Status totals for manifest entries: **Approved 12; Draft 8; Placeholder 14; Superseded 4.** Artifact paths are inventoried separately below; multiple artifacts can support one entry.

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

### JC-003 — Mobile Navigation Drawer (Logged Out)

- **Screen / Component:** Logged-out mobile navigation drawer and overlay
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-003-mobile-navigation-drawer-logged-out-v1.0.png`
- **Verified Source Raster:** `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/LO-1st-touch-w-hamburger-overlay.png`
- **SHA-256:** `56f893866d643a23269b37fb32b05b1348d5716c092836dca94f85f9b9627d24`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC006
- **Approval scope:** Mobile navigation drawer component only, including drawer structure, logo/header, close control, section hierarchy, typography, spacing rhythm, icon treatment, navigation-row composition, footer treatment, social row, logged-out acquisition panel, and overlay behavior and visual treatment.
- **Authority:** DOC006 explicitly approves the exact Logged Out raster as the canonical shared mobile navigation drawer component.
- **Related Design System section:** 4, 5, 6, 7, 8, 16, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-001, JC-ATLAS-003, JC-ATLAS-006
- **Related implementation ticket(s):** None assigned
- **Notes:** This component authority applies to JC-010, JC-011, JC-014, JC-015, and JC-030 unless a future screen documents an Approved exception. It does not independently approve an underlying page, responsive page layout, tablet layout, or implementation. Future Logged Out drawer work is Patch Mode only: an explicitly named component delta requires separate approval.

### JC-004 — Mobile Navigation Drawer (Logged In)

- **Screen / Component:** Logged-in mobile navigation drawer and overlay
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-004-mobile-navigation-drawer-logged-in-v1.0.png`
- **Verified Source Raster:** `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/LI-1st-touch-w-hamburger-overlay.png`
- **SHA-256:** `9597b80b1a7824a5bf471f59896f5afcb530839f1934608308654ffcdc352e76`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC006
- **Approval scope:** Mobile navigation drawer component only, including drawer structure, logo/header, close control, section hierarchy, typography, spacing rhythm, icon treatment, navigation-row composition, footer treatment, social row, logged-in profile header, logout presentation, and overlay behavior and visual treatment.
- **Authority:** DOC006 explicitly approves the exact Logged In raster as the canonical shared mobile navigation drawer component.
- **Related Design System section:** 4, 5, 6, 7, 8, 16, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-001, JC-ATLAS-003, JC-ATLAS-006
- **Related implementation ticket(s):** None assigned
- **Notes:** This component authority applies to JC-010, JC-011, JC-014, JC-015, and JC-030 unless a future screen documents an Approved exception. It does not independently approve an underlying page, responsive page layout, tablet layout, or implementation. Future Logged In drawer work is Patch Mode only: an explicitly named component delta requires separate approval.

### JC-010 — Job Finder State 1

- **Screen / Component:** Logged-out first-touch `/jobs/` state with full inventory sorted by Most Recent
- **Status:** Approved
- **Version:** 1.1
- **Approved Artifact:** `docs/job-center/design/approved/jc-010-job-finder-state-1-desktop-v1.1.png`
- **SHA-256:** `3653d2d131281a29db8e4643d856ff52f3916222c0bbde3b3bf09e815257dd20`
- **Approval date:** 2026-07-13
- **Approval authority:** Engineering Director, DESIGN009
- **Approval scope:** Desktop logged-out first-touch `/jobs/` State 1 exactly as shown: default breadcrumb and heading, closed integrated search control, collapsed Browse and Refine actions, full-inventory heading and Most Recent sort, ten-listing inventory within this screen, refined logged-out conversion/search/browse/employer/advertising/Career Chatboards rail, desktop advertising placements, pagination/result range, leaderboard, and footer. Responsive authority remains separate.
- **Authority:** DESIGN009 explicitly approves the exact 01f raster as the canonical JC-010 implementation and QA reference within the stated desktop boundary, superseding the previous 01b v1.0 desktop authority.
- **Related Design System section:** 9
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** Represents logged-out first-touch `/jobs/` before keyword, location, browse, or refinement input: full 205-job inventory, first ten jobs, and default Most Recent sort. Lineage is 0.1 Placeholder → DESIGN008 01a Draft 0.2 → DESIGN009 01b → VA002-FINAL 01b v1.0 Approved → DESIGN008 reconciliation/polish 01c–01f → DESIGN009 01f v1.1 Approved. The previous 01b raster is superseded as authority but remains preserved as a historical working artifact outside `approved/`; intermediate right-rail candidates remain working history. Browse Reveal and the Location Selection Modal remain separate approved interaction states and are not modified by this supersession. The visible `25 miles` control is illustrative; implementation must hide or disable distance until a valid location or origin exists. Approval is bounded to desktop JC-010 and does not independently approve JC-001, JC-020, JC-021, JC-024, JC-070, unseen interaction states, or implementation. JC-010 Mobile is separately governed below.

### JC-010 Mobile — Job Finder State 1 Mobile Responsive Authority

- **Screen / Component:** Logged-out first-touch `/jobs/` State 1 at the approved mobile presentation
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-010-job-finder-state-1-mobile-v1.0.png`
- **Verified Source Raster:** `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/1st-touch-mobile-approved.png`
- **SHA-256:** `23d63c4ea62e8bae04f6a70cff8b13414d064d33def0a1b7c682852007478de3`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC003
- **Approval scope:** Mobile presentation only. Within the visible boundary, the exact raster governs typography, spacing, proportions, hierarchy, reading rhythm, touch targets, drawer presentation, advertisement reservation, pagination, acquisition panel, and footer.
- **Authority:** DOC003 explicitly approves the exact 02c raster as the canonical JC-010 Mobile Responsive Authority; DOC005 corrects the previously recorded raster identity without changing the approval decision.
- **Related Design System section:** 4, 9, 12, 14, 15, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** DOC005 verified the external Engineering Director source raster and copied it byte-identically into the controlled library. Desktop JC-010 v1.1 remains the product/content authority. This approval is mobile-only and does not reinterpret or supersede desktop JC-010 v1.1, alter JC-011, JC-014, or JC-030, establish tablet authority, or authorize responsive implementation. Future JC-010 mobile work is Patch Mode: it may address only an explicitly named mobile defect or delta against this exact approved raster and requires separate approval before implementation or broader redesign.

### JC-010 Tablet — Job Finder State 1 Tablet Responsive Authority

- **Screen / Component:** Logged-out first-touch `/jobs/` State 1 at the approved portrait-tablet presentation
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-010-job-finder-state-1-tablet-v1.0.png`
- **Verified Source Raster:** `art/mockups/job-center/generated/jc010-tablet-01b-legibility-03d-candidate.png`
- **SHA-256:** `8b2bcffb36b0a8024f5a680686094dd716b87983159796c502ee9e1b96647248`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC008
- **Approval scope:** Portrait-tablet presentation of JC-010 Logged-Out First Touch, including search composition, listing composition, right-rail presentation, pagination, advertising reservations, footer presentation, responsive hierarchy, and tablet reading rhythm within the visible boundary.
- **Authority:** DOC008 explicitly approves the exact 03d raster as the canonical JC-010 Tablet Responsive Authority.
- **Related Design System section:** 4, 5, 9, 10, 13–17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-012
- **Related implementation ticket(s):** None assigned
- **Notes:** The controlled-library copy is byte-identical to the verified repository candidate. Desktop JC-010 v1.1 remains the product/content authority and JC-010 Mobile v1.0 remains the mobile presentation authority. This approval does not alter JC-011, JC-014, JC-015, or JC-030, establish other responsive variants, or authorize implementation. Known implementation guidance only: confirm comfortable touch targets; preserve the visible Save-heart size while enlarging only its invisible hit area; verify advertisement reservations and true breakpoint behavior during responsive implementation; and make restrained browser-QA typography adjustments only if needed. Future JC-010 Tablet work is Patch Mode and requires a separately approved, explicitly named defect or delta.

### JC-011 — Job Finder State 2

- **Screen / Component:** Refined search/results state and locked page shell
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-011-job-finder-state-2-desktop-v1.0.png`
- **Approval date:** 2026-07-12
- **Approval authority:** Engineering Director, VA001-FINAL
- **Approval scope:** Desktop Job Finder State 2 exactly as shown: constrained page shell, compact refined-search/results state, applied chips, results header and sort, ten-listing composition within this screen, right rail, desktop advertising placements, pagination/result range, leaderboard, and footer.
- **Authority:** VA001-FINAL explicitly approves the exact 01k raster as the canonical JC-011 implementation and QA reference within the stated boundary.
- **Related Design System section:** 4, 7, 9, 10, 13–16
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005
- **Related implementation ticket(s):** None assigned
- **Notes:** DESIGN007 derived 01k from 01j solely to change `Clear` to `Clear filters` and correct the bottom leaderboard reservation identified by VA001. Lineage is DESIGN006 v4 → v5 → refined-search 01g → 01h → 01j → approved 01k. JC-090–JC-093 preserve the explicitly Superseded iterations; 01j remains a historical Draft. Approval is bounded to JC-011 and does not independently approve JC-001, JC-020, JC-021, JC-024, responsive states, interaction behavior not visible in the raster, or implementation.

### JC-011 Tablet — Job Finder State 2 Tablet Responsive Authority

- **Screen / Component:** Refined search/results state at the approved portrait-tablet presentation
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-011-job-finder-state-2-tablet-v1.0.png`
- **Verified Source Raster:** `art/mockups/job-center/generated/jc011-tablet-responsive-v1.0-r003-candidate.png`
- **SHA-256:** `885d3ddb18642560aa6cd3df4e345ff2b66545a49a3c3585d52baefeff117ec8`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC011
- **Approval scope:** Portrait-tablet presentation of JC-011 Job Finder State 2, including populated search state, expanded Refine Search treatment, applied-filter chips, `42 jobs found` results context, ten governed listings, salary and distance treatment, pagination and result range, JC-011-specific right rail including Your Current Search, advertising reservations, footer, responsive hierarchy, and reading rhythm within the visible boundary.
- **Authority:** DOC011 explicitly approves the exact R003 raster as the canonical JC-011 Tablet Responsive Authority.
- **Related Design System section:** 4, 5, 9, 10, 13–17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005, JC-ATLAS-012
- **Related implementation ticket(s):** None assigned
- **Notes:** The controlled-library copy is byte-identical to the verified repository candidate. JC-011 desktop v1.0 remains the product/content authority and JC-010 Tablet v1.0 remains the shared tablet presentation language. This approval is limited to JC-011 portrait-tablet presentation and does not alter JC-011 Mobile v1.0, implementation, or other screens. Known implementation and accessibility guidance only: ensure compact controls, pagination, chip-remove actions, and Save-heart targets meet the preferred approximately 44px target without enlarging visible glyphs; verify footer and dense right-rail text contrast and focus indicators in browser rendering; and verify 728 × 90 and 300 × 250 reservations retain governed proportions without overflow at actual tablet breakpoints. Future JC-011 Tablet work is Patch Mode and requires a separately approved, explicitly named defect or delta.

### JC-011 Mobile — Job Finder State 2 Mobile Responsive Authority

- **Screen / Component:** Refined search/results state at the approved mobile presentation
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-011-job-finder-state-2-mobile-v1.0.png`
- **Verified Source Raster:** `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/mobile-expanded-mobileads-01a.png`
- **Source dimensions:** `360 × 975`
- **SHA-256:** `5e4504e7f6d8f36eb77fca511444ca332f16a48e0842c1e48d87b310b79b96c8`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC012
- **Approval scope:** Mobile presentation of JC-011 Job Finder State 2, including refined State 2 search, populated keyword, location, and active distance, expanded mobile filter panel, removable applied-filter chips, results summary, ten governed listings, salary and distance hierarchy, pagination, `320 × 50` in-list advertisement reservation, `320 × 100` post-pagination advertisement reservation, minimal mobile footer, responsive hierarchy, and mobile reading rhythm within the visible boundary.
- **Authority:** DOC012 explicitly approves the exact audited raster as the canonical JC-011 Mobile Responsive Authority. RESP-DEC002 governs the approved JC-011 Mobile support-content exception.
- **Related Design System section:** 4, 5, 9, 10, 13–17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-005, JC-ATLAS-012
- **Related implementation ticket(s):** None assigned
- **Notes:** The controlled-library copy is byte-identical to the verified external source raster. The 360 × 975 native raster has limited zoom fidelity; this is an accepted provenance limitation only and does not authorize reconstruction, upscaling, AI regeneration, visual reinterpretation, or replacement by a derivative without a separate approval decision. JC-011 Desktop v1.0 remains the product/content authority and JC-010 Mobile v1.0 remains the shared mobile presentation language. This approval is bounded to JC-011 Mobile only and does not alter JC-010, JC-014, JC-015, JC-030, tablet authorities, desktop behavior, or implementation. Known implementation guidance only: preserve approximately 44px interactive targets for controls, pagination, chip removal, and Save-heart actions without enlarging visible glyphs; verify WCAG AA contrast, focus indicators, and accessible names; verify `320 × 50` and `320 × 100` advertisement reservations render correctly at governed mobile breakpoints; and verify browser rendering preserves approved spacing, typography, and order. Future JC-011 Mobile work is Patch Mode and requires a separately approved, explicitly named defect or delta.

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
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-014-location-selection-modal-desktop-v1.0.png`
- **Approval date:** 2026-07-12
- **Approval authority:** Engineering Director explicit approval
- **Approval scope:** Desktop logged-out first-touch `/jobs/` Location-selection state exactly as shown: focused Location trigger, restrained backdrop, centered shared modal structure, Current Location active tab, current-location panel, 5–100 distance control, cross-state option, logged-out advanced-feature teaser, disabled Apply state, and the smaller/lighter Save-heart treatment visible behind the modal.
- **Authority:** The Engineering Director explicitly approved the exact 01b raster as the canonical JC-014 implementation and QA reference within the stated boundary.
- **Related Design System section:** 8, 9, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** JC-014 was derived from the then-approved JC-010 v1.0 background and remains an independently approved interaction-state raster; DESIGN009 supersedes current JC-010 desktop authority with v1.1 but does not alter JC-014. Lineage is DESIGN010 01a Draft 0.1 → DESIGN011 01b Draft 0.2 → explicit Engineering Director approval 01b v1.0; 01a remains preserved as Draft history. The logged-out artifact governs the shared modal structure. Logged-in implementation uses the same modal but omits the `Sign in to access advanced location features.` teaser; do not create a separate logged-in raster unless later review identifies a meaningful visual difference. Distance remains dependent on a valid location or origin, uses the practical 5–100 radius scale, and Apply remains inactive until an origin is established. Browse Reveal remains a separate future artifact. Approval does not independently approve JC-001, JC-020, JC-021, JC-024, JC-070, Browse Reveal, or implementation.

### JC-014 Tablet — Location Selection Modal Tablet Responsive Authority

- **Screen / Component:** Logged-out first-touch `/jobs/`, Location segment activated, modal open at the approved portrait-tablet presentation
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-014-location-selection-modal-tablet-v1.0.png`
- **Verified Source Raster:** `art/mockups/job-center/generated/jc014-tablet-responsive-r002-candidate.png`
- **SHA-256:** `daee39df8274ecc9852824a01747fd8f0a5cd55ec0477b7597ad575e544b6593`
- **Approval date:** 2026-07-14
- **Approval authority:** Engineering Director, DOC013
- **Approval scope:** Portrait-tablet presentation of JC-014 Location Selection Modal, including the JC-010 tablet page beneath the open modal, subdued backdrop, centered Location modal, Search by location heading, close control, Current Location / City, State, or ZIP / Browse by State tabs, current-location panel, distance control, cross-state checkbox, logged-out sign-in note, Cancel, disabled Apply, modal width, viewport position, hierarchy, and reading rhythm within the visible boundary.
- **Authority:** DOC013 explicitly approves the exact R002 raster as the canonical JC-014 Tablet Responsive Authority.
- **Related Design System section:** 4, 8, 9, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003, JC-ATLAS-012
- **Related implementation ticket(s):** None assigned
- **Notes:** The controlled-library copy is byte-identical to the verified repository candidate. JC-014 Desktop v1.0 remains the product/content authority and JC-010 Tablet v1.0 remains the shared tablet page-presentation authority. This approval is limited to JC-014 portrait-tablet presentation and does not approve JC-014 Mobile, implementation, or other screens. Known implementation and accessibility guidance only: preserve focus containment while open; preserve Escape and close behavior; return focus to the Location trigger after dismissal; maintain preferred touch targets for close, tabs, checkbox, Cancel, and Apply; and verify final browser contrast and focus indicators. Future JC-014 Tablet work is Patch Mode and requires a separately approved, explicitly named defect or delta.

### JC-015 — Browse Reveal

- **Screen / Component:** Logged-out first-touch `/jobs/`, Browse by Grade or Subject expanded inline
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-015-browse-reveal-desktop-v1.0.png`
- **Approval date:** 2026-07-12
- **Approval authority:** Engineering Director explicit approval
- **Approval scope:** Desktop logged-out first-touch `/jobs/` Browse Reveal exactly as shown: expanded Browse disclosure control, five-card Grade/Subject exploration row, Refine Search retained opposite the disclosure beneath the integrated search control, unchanged full-inventory state, and the smaller/lighter Save-heart treatment.
- **Authority:** The Engineering Director explicitly approved the exact 01b raster as the canonical JC-015 implementation and QA reference within the stated boundary.
- **Related Design System section:** 8, 9, 12, 17
- **Related Product Specification:** Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-003
- **Related implementation ticket(s):** None assigned
- **Notes:** Derived from the then-approved JC-010 v1.0 background and documents only the expanded inline Browse interaction; DESIGN009 supersedes current JC-010 desktop authority with v1.1 but does not alter JC-015. Lineage is DESIGN012 01a Draft 0.1 → DESIGN013 01b Draft 0.2 → explicit Engineering Director approval 01b v1.0; 01a remains preserved as Draft history. No search has been submitted; there is no keyword, selected location, filter, or applied chip state. The caret and dark heading form one disclosure control, opposite the unchanged Refine Search action beneath the integrated search control. The reveal presents Preschool, Elementary, Middle School, High School, and Browse All Subjects as restrained exploratory cards while Search Jobs remains primary. The artifact uses the refined smaller/lighter Save-heart treatment established in JC-014 without modifying or superseding the Approved JC-010 raster. Approval does not independently approve JC-001, JC-020, JC-021, JC-024, JC-070, unseen responsive states, or implementation.

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
- **Status:** Approved
- **Version:** 1.0
- **Approved Artifact:** `docs/job-center/design/approved/jc-030-job-detail-desktop-v1.0.png`
- **Editable source:** Unavailable
- **Approval date:** 2026-07-13
- **Approval authority:** Engineering Director, DESIGN006
- **Approval scope:** Canonical desktop public Job Detail exactly as shown in the approved raster, including the constrained Job Center shell, job identity and structured facts, supplied narrative and qualifications, application/save presentation, right rail, employer identity, related-job discovery, advertising reservations, employer-claim context, and footer.
- **Authority:** DESIGN006 explicitly approves the exact supplied raster as the canonical JC-030 desktop implementation and visual-QA reference within the stated boundary.
- **Related Design System section:** 11, 17
- **Related Product Specification:** JC-030 Job Detail Product Definition; JC-030 Job Detail UX Specification; Canonical V1 Contract
- **Related future UX Atlas entry:** JC-ATLAS-006
- **Related implementation ticket(s):** None assigned
- **Notes:** Lineage is JC-030 Placeholder 0.1 → unapproved implementation evidence and Job Detail concepts → AUDIT007 reconciliation → Engineering Director-supplied approved raster `ChatGPT Image Jul 13, 2026, 12_05_45 AM.png` → controlled library copy `jc-030-job-detail-desktop-v1.0.png` v1.0. The artifact is a raster visual authority. No matching editable source was produced or retained; `job-detail-01b-canonical-candidate.html` is not its source and is not associated with this approval. Implementation must rely on the approved PNG together with the JC-030 Product Definition, UX Specification, AUDIT007 reconciliation, and Job Center Design System. The missing editable source does not authorize visual reinterpretation. Any future reconstructed source is derivative and must not replace the approved PNG without a separate approval decision. Approval is desktop-only and does not approve responsive authority or implementation.

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
| `art/mockups/job-center/generated/job-finder-browse-reveal-01a-design-target.png` | Logged-out first-touch Browse reveal expanded inline | Draft | JC-015 | Historical DESIGN012 Draft retained after DESIGN013 polish |
| `art/mockups/job-center/generated/job-finder-browse-reveal-01b-design-target.png` | Polished logged-out first-touch Browse reveal expanded inline | Approved | JC-015 | Exact source approved by the Engineering Director for JC-015 only |
| `docs/job-center/design/approved/jc-015-browse-reveal-desktop-v1.0.png` | Controlled JC-015 reference copy | Approved | JC-015 | Canonical library copy; byte-identical to the approved source artifact |
| `docs/job-center/design/approved/jc-030-job-detail-desktop-v1.0.png` | Controlled JC-030 desktop reference copy | Approved | JC-030 | Canonical raster authority; byte-identical to the Engineering Director-supplied PNG; editable source unavailable |
| `art/mockups/job-center/generated/job-finder-location-modal-lo-01a-design-target.png` | Logged-out first-touch Location modal open | Draft | JC-014 | Historical DESIGN010 Draft retained after DESIGN011 polish |
| `art/mockups/job-center/generated/job-finder-location-modal-lo-01b-design-target.png` | Polished logged-out first-touch Location modal open | Approved | JC-014 | Exact source approved by the Engineering Director for JC-014 only |
| `docs/job-center/design/approved/jc-014-location-selection-modal-desktop-v1.0.png` | Controlled JC-014 reference copy | Approved | JC-014 | Canonical library copy; byte-identical to the approved source artifact |
| `art/mockups/job-center/generated/jc014-tablet-responsive-r002-candidate.png` | Engineering Director-approved JC-014 Tablet source raster | Approved | JC-014 Tablet | DOC013 verified the exact repository candidate and SHA-256 |
| `docs/job-center/design/approved/jc-014-location-selection-modal-tablet-v1.0.png` | Controlled JC-014 Tablet v1.0 reference copy | Approved | JC-014 Tablet | Byte-identical to the DOC013-verified source raster; future work is Patch Mode |
| `art/mockups/job-center/generated/job-finder-state-1-01a-design-target.png` | Logged-out first-touch Job Finder, full inventory, Most Recent | Draft | JC-010 | Historical DESIGN008 Draft retained after 01b approval |
| `art/mockups/job-center/generated/job-finder-state-1-01b-design-target.png` | Previous polished logged-out first-touch Job Finder | Superseded | JC-010 | Historical v1.0 source retained outside the Approved library after DESIGN009 v1.1 supersession |
| `art/mockups/job-center/generated/job-finder-state-1-right-rail-01f-candidate.png` | Logged-out first-touch Job Finder with reconciled right rail | Approved | JC-010 | Exact source approved by DESIGN009 as JC-010 v1.1 desktop authority |
| `docs/job-center/design/approved/jc-010-job-finder-state-1-desktop-v1.1.png` | Controlled JC-010 v1.1 reference copy | Approved | JC-010 | Canonical library copy; byte-identical to the approved 01f source artifact |
| `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/1st-touch-mobile-approved.png` | Engineering Director-approved JC-010 Mobile source raster | Approved | JC-010 Mobile | DOC005 verified exact external source and SHA-256 |
| `docs/job-center/design/approved/jc-010-job-finder-state-1-mobile-v1.0.png` | Controlled JC-010 Mobile v1.0 reference copy | Approved | JC-010 Mobile | Byte-identical to the DOC005-verified external source raster; future work is Patch Mode |
| `art/mockups/job-center/generated/jc010-tablet-01b-legibility-03d-candidate.png` | Engineering Director-approved JC-010 Tablet source raster | Approved | JC-010 Tablet | DOC008 verified the exact repository candidate and SHA-256 |
| `docs/job-center/design/approved/jc-010-job-finder-state-1-tablet-v1.0.png` | Controlled JC-010 Tablet v1.0 reference copy | Approved | JC-010 Tablet | Byte-identical to the DOC008-verified source raster; future work is Patch Mode |
| `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/LO-1st-touch-w-hamburger-overlay.png` | Engineering Director-approved Logged Out mobile navigation drawer source raster | Approved | JC-003 | DOC006 verified exact external source and SHA-256 |
| `docs/job-center/design/approved/jc-003-mobile-navigation-drawer-logged-out-v1.0.png` | Controlled Logged Out mobile navigation drawer reference copy | Approved | JC-003 | Byte-identical to the DOC006-verified external source raster; future work is Patch Mode |
| `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/LI-1st-touch-w-hamburger-overlay.png` | Engineering Director-approved Logged In mobile navigation drawer source raster | Approved | JC-004 | DOC006 verified exact external source and SHA-256 |
| `docs/job-center/design/approved/jc-004-mobile-navigation-drawer-logged-in-v1.0.png` | Controlled Logged In mobile navigation drawer reference copy | Approved | JC-004 | Byte-identical to the DOC006-verified external source raster; future work is Patch Mode |
| `art/mockups/job-center/generated/design006-job-finder-state-2-v4.png` | Finder State 2 | Superseded | JC-090 | Replaced by v5 candidate |
| `art/mockups/job-center/generated/design006-job-finder-state-2-v5.png` | Finder State 2 | Superseded | JC-091 | Replaced by 01g candidate |
| `art/mockups/job-center/generated/refined-search-model-01g-polished.png` | Results/listing | Superseded | JC-092 | Replaced by 01h candidate |
| `art/mockups/job-center/generated/refined-search-model-01h-panel-polish.png` | Search panel/results | Superseded | JC-093 | Replaced by 01j candidate |
| `art/mockups/job-center/generated/refined-search-model-01j-design-target.png` | Finder State 2/canonical listing | Draft | JC-011, JC-020 | Prior active Draft candidate; retained in lineage after DESIGN007 |
| `art/mockups/job-center/generated/refined-search-model-01k-design-target.png` | Finder State 2/canonical listing | Approved | JC-011, JC-020 | Exact source approved by VA001-FINAL for JC-011 only; JC-020 remains Draft |
| `docs/job-center/design/approved/jc-011-job-finder-state-2-desktop-v1.0.png` | Controlled JC-011 reference copy | Approved | JC-011 | Canonical library copy; byte-identical to the approved source artifact |
| `art/mockups/job-center/generated/jc011-tablet-responsive-v1.0-r003-candidate.png` | Engineering Director-approved JC-011 Tablet source raster | Approved | JC-011 Tablet | DOC011 verified the exact repository candidate and SHA-256 |
| `docs/job-center/design/approved/jc-011-job-finder-state-2-tablet-v1.0.png` | Controlled JC-011 Tablet v1.0 reference copy | Approved | JC-011 Tablet | Byte-identical to the DOC011-verified source raster; future work is Patch Mode |
| `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/mobile-expanded-mobileads-01a.png` | Engineering Director-approved JC-011 Mobile 360 × 975 source raster with mobile ad reservations | Approved | JC-011 Mobile | DOC012 verified exact external source dimensions and SHA-256; RESP-DEC002 governs the support-content exception |
| `docs/job-center/design/approved/jc-011-job-finder-state-2-mobile-v1.0.png` | Controlled JC-011 Mobile v1.0 reference copy | Approved | JC-011 Mobile | Byte-identical to the DOC012-verified 360 × 975 source raster; future work is Patch Mode |
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

The `approved/` directory contains the JC-010 v1.1, JC-011 desktop v1.0,
JC-011 Tablet v1.0, JC-011 Mobile v1.0, JC-014 desktop v1.0, JC-014 Tablet
v1.0, JC-015 v1.0, JC-030 v1.0, JC-010 Mobile v1.0, JC-010 Tablet v1.0, and
the JC-003/JC-004 mobile navigation drawer component references. DOC005
corrects JC-010 Mobile's raster identity to the verified external 02c source
and its byte-identical controlled-library copy. DOC006 adds only the shared
mobile drawer component. DOC008 adds only the bounded JC-010 portrait-tablet
presentation; it does not approve another page, responsive variant, or
implementation. DOC011 adds only the bounded JC-011 portrait-tablet
presentation. RESP-DEC002 governs a JC-011 Mobile-only support-content
exception; DOC012 adds only the bounded JC-011 Mobile v1.0 presentation and
does not authorize implementation. DOC013 adds only the bounded JC-014
portrait-tablet presentation and does not approve JC-014 Mobile or
implementation. Separate approval decisions are still required for the shell as
JC-001, landing, other Finder states, progressive Search, canonical listing as
JC-020, advertising as JC-024, and responsive variants beyond the bounded
JC-010 Mobile, JC-010 Tablet, JC-011 Tablet, JC-011 Mobile, JC-014 Tablet, and
drawer component approvals. Employer, Saved Jobs, Alerts, Moderator, and
Administration references remain governed placeholders and must not be inferred
from current implementation or from existing approvals.
