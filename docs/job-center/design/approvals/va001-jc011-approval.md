# VA001 — JC-011 Job Finder State 2 Approval Review

**Decision:** NOT YET APPROVED

**Review date:** 2026-07-11

**Manifest entry:** JC-011 — Job Finder State 2

**Historical disposition:** This 2026-07-11 review remains the blocker record for
01j. DESIGN007 produced 01k, and VA001-FINAL subsequently approved 01k as JC-011
v1.0 on 2026-07-12. See `va001-final-jc011-approval.md`.

## 1. Candidate reviewed

The manifest registers one active Draft artifact for JC-011:

- **Filename:** `art/mockups/job-center/generated/refined-search-model-01j-design-target.png`
- **Current manifest version:** 0.6
- **File format and dimensions:** PNG, 872 × 1804 pixels
- **SHA-256:** `a50eb57a01d0b0a490394fdb27c90cded09dd171b7b0df8f26c171c58613be45`
- **Represented boundary:** Desktop refined-search/results state and locked Job Center page shell

This is the single strongest candidate because it is the only active Draft artifact that the canonical manifest registers directly for JC-011. The preceding DESIGN006 v4/v5 and refined-search 01g/01h artifacts are explicitly Superseded and were not reconsidered as approval candidates.

No inventory deficiency or internal inconsistency required discovery outside the manifest.

## 2. Evaluation summary

The candidate accurately represents the intended Job Finder State 2 at a visual-product level: a compact page heading leads into a basic search row, expanded refinements remain connected to the same Finder, applied intent is summarized with removable chips, and one result set follows with shared sorting and pagination. It does not imply a second search product or expose source, verification, moderation, or employer-authority facts in public listings.

The listing system substantially conforms to the canonical professional-directory model. Each result uses a narrative block for title, employer/location, taxonomy chips, and summary, plus a compact secondary block for salary, supported distance, and Save. Ten entries use a consistent visual structure, salary and distance align, Save hearts appear as a uniform lightweight outline glyph, and removal of posting-age text does not leave a conspicuous empty band.

The white navbar, constrained shell, dark Teachers.Net footer, content/rail hierarchy, result count, pagination, 300 × 250 rail advertisement, and post-pagination leaderboard location align with the Design System direction. Employer UX V1 does not materially govern this public job-seeker state; the candidate does not contradict its one-employer, one-job, or truthful-authority principles.

The artifact is close to canonical-reference quality, but two visible conflicts with explicit Design System rules remain. Because approval would make the exact artifact authoritative, those conflicts cannot be deferred to implementation interpretation.

## 3. Strengths

- Presents one coherent Job Finder with progressive disclosure rather than separate basic and advanced result systems.
- Preserves keyword, location, and distance context while exposing refinements in a connected panel.
- Places applied-filter chips beside the search context and above the result count.
- Uses the required two-zone directory-entry composition rather than a table.
- Keeps taxonomy metadata inside the narrative block.
- Aligns salary blocks consistently and places supported distance directly beneath salary.
- Normalizes all visible Save controls as small, light outline hearts aligned with the salary row.
- Uses consistent listing borders, internal hierarchy, and adjacent-entry separation across all ten results.
- Omits unsupported Verified Employer and public provenance/moderation treatment.
- Shows ten results, a total count, shared sort control, pagination, and `Showing 1–10 of 42 jobs` in a coherent sequence.
- Treats the 300 × 250 advertisement as a separately labeled right-rail placement rather than a content card.
- Keeps the main task dominant while right-rail search context, discovery, employer conversion, resources, and advertising remain secondary.
- Uses a white navbar and standard dark Teachers.Net blue footer within the visible page shell.

## 4. Remaining deficiencies

Only the following blockers must be resolved before approval:

1. **Declare the refinement reset scope.** The expanded refinement panel labels its reset action only `Clear`. Job Center Design System v1 Section 9.2 requires the clear action to declare its scope so that refinements are not confused with keyword/location intent. The artifact must use unambiguous scoped wording, such as `Clear filters`, without implying that keyword or location will also be erased.
2. **Preserve the canonical leaderboard reservation.** The post-pagination placement is labeled `728 × 90`, but its drawn reserved box is materially narrower than that proportion when compared with the correctly proportioned 300 × 250 rail placement and the available main-column width. Job Center Design System v1 Section 14.2 requires the reserved creative to be 728 × 90 and centered in the main column. The artifact must depict that reserved proportion accurately rather than relying on the label alone.

## 5. Approval recommendation

**NOT YET APPROVED**

Do not promote JC-011 to Approved, assign version 1.0, or copy the candidate into `docs/job-center/design/approved/` in this review. The manifest remains unchanged. Once an exact revised artifact resolves both blockers, it may receive a new bounded approval review; correction does not inherit approval automatically.

This review does not authorize implementation, redesign, image editing, or changes to product behavior.
