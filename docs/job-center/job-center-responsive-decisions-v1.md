# Job Center Shared Responsive Decisions v1

**Status:** Governing responsive decisions

**Date:** 2026-07-13

Desktop visual authority remains unchanged. These decisions govern only the
responsive interpretation needed before responsive visual authority is
created.

## 1. Navigation

On mobile, the white constrained navbar preserves the Teachers.Net identity and
visible Job Center context, keeps **Sign in** directly available in the top bar,
and places the remaining primary destinations plus **Create Account** inside one
keyboard-accessible hamburger menu. The menu preserves the desktop destination
labels, order, current-page state, and accessible names; it does not introduce
abbreviated or icon-only destinations.

## 2. Search Controls

Mobile search stacks **Keyword**, **Location**, **Distance**, then the primary
**Search Jobs** action; Location continues to open the governed location
selector, and Distance remains unavailable until a valid location or origin
exists. **Browse by Grade or Subject** and **Refine Search** remain secondary
disclosure actions immediately after the search stack, in that order in reading
and focus sequence, and neither becomes a competing primary button.

## 3. Listings

Responsive listings preserve narrative-first reading order: title, employer,
location, taxonomy chips, and summary precede the compact secondary information.
Salary follows the narrative identity and remains grouped with distance when
distance exists; the outline Save heart remains a consistent secondary action
at the upper trailing edge without preceding the title in semantic order.
Metadata and chips wrap naturally without truncating required job truth or
becoming table columns.

## 4. Right Rail

On logged-out Finder and Search surfaces, the desktop right rail enters the
main flow after results, pagination, and the leaderboard in this exact order:
**Account → Browse → Employer → Advertisement → Community**. JC-010, JC-011,
JC-014, and JC-015 inherit this order, with modal or disclosure states changing
only their named interaction; a missing page-specific card closes its space
without reordering the remaining cards. JC-030 intentionally uses the Job
Detail order below instead of ordinary support-rail stacking.

## 5. Advertising

`docs/job-center/job-center-responsive-advertising-strategy-v1.md` governs
responsive advertisement inventory, intrinsic dimensions, placement hierarchy,
and exception approval. Its desktop, portrait-tablet, and mobile rules replace
generic cross-breakpoint scaling assumptions. Screen-specific approved
exceptions, including the JC-011 Mobile reservations below, remain in force.

## 6. Job Detail

JC-030 mobile uses this conversion order: **Apply → Save → Share → Narrative →
Employer → Related Jobs → Advertisement**. Apply appears once as the primary
conversion action immediately after job identity, Save and Share remain
secondary, and the later application information explains method and external
handoff without repeating a competing Apply control; job facts remain with the
narrative before Employer. This is an intentional exception to ordinary
support-rail stacking because application conversion is the primary Job Detail
task.

## 7. JC-011 Mobile Support-Content Exception

RESP-DEC002 creates a bounded exception to the ordinary logged-out Finder/Search
mobile support-stack rule for JC-011 Mobile only. JC-011 Mobile does not inherit
the full **Account → Browse → Employer → Advertisement → Community** support
stack because refined-results mobile usage prioritizes continued evaluation of
the governed result set.

After the governed ten listings and pagination, JC-011 Mobile uses:

1. the approved lower mobile advertisement reservation; and
2. the minimal mobile footer.

Account, Browse, Employer, and Community destinations remain available through
the approved mobile navigation drawer and are not duplicated below every
refined-results page. The `320 × 50` advertisement between listings 5 and 6 and
the `320 × 100` advertisement below pagination are the governed JC-011 Mobile
reservations.

This exception applies only to JC-011 Mobile. It does not change JC-010,
JC-014, JC-015, JC-030, tablet behavior, desktop behavior, or general
support-stack governance. Any future exception requires a separate explicit
responsive decision.

## 8. Modal Behavior

On mobile, the location modal is an inset viewport-bound dialog with a maximum
height that preserves surrounding context; its heading, close control, tabs,
and bottom action row remain reachable while the content region scrolls
internally when height is constrained. **Cancel**, the close control, and
Escape dismiss without applying changes; backdrop interaction does not dismiss
the dialog. Focus remains contained while open and returns to the Location
trigger after dismissal.

## 9. Portrait Tablet Principles

- Desktop governs product truth.
- Mobile governs presentation.
- Tablet preserves desktop information architecture while adopting mobile
  reading comfort.
- Preserve hierarchy before preserving density.
- Extend vertically before compressing typography.
- Typography favors comfortable sustained reading over maximum information
  density.
- Preserve established page identity across responsive adaptations.
- Right-rail support content may remain visible on portrait tablet where it
  improves first-view usability and an Approved tablet authority establishes
  that presentation.
- Responsive work adapts Approved authorities rather than redesigning them.
- Once a responsive authority reaches convergence, future work enters Patch
  Mode.

## 10. Responsive Workflow

```text
Desktop Authority
        ↓
Tablet Authority
        ↓
Mobile Authority
        ↓
Patch Mode
        ↓
Browser Implementation
        ↓
Browser QA
        ↓
Production Authority
```
