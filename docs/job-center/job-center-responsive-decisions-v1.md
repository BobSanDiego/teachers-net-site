# Job Center Shared Responsive Decisions v1

**Status:** Governing responsive decisions

**Date:** 2026-07-30

Desktop visual authority remains unchanged. These decisions govern only the
responsive interpretation needed before responsive visual authority is
created.

## 0. Employer Operations responsive workstream (JC053)

The JC053 responsive shell series is an active implementation-target
workstream, not an approved production authority. Acceptance requires local
PNG evidence, external browser inspection, and human visual review; inline
captures alone are insufficient.

The verified route is external `chrome-devtools-mcp@1.6.0` at
`http://127.0.0.1:9222`, launched with `--allow-unrestricted-paths
--no-usage-statistics` against the dedicated QA Chrome profile. The built-in
and obsolete WSL browser bridges are not valid QA paths.

Intended Employer Operations rules are: `>=1200px` preserves the 1200px shell,
250px brand/rail, and full navbar; `1025–1199px` retains the 250px brand/rail
and compresses only right-side spacing; `<=1024px` may enter compact mode with
a 210px brand/rail and one unified centered Resources control. Full My Jobs,
Career Resources, and Teacher Resources remain visible through 1025px. Shared
structural axes—brand/rail width, rail/workspace divider, navbar divider, and
workspace origin—must be driven by shared tokens, not view-specific offsets.

The latest JC053 header contract is: 768–1024px uses the compact single-row
header with the left logo region, unified Job Center control, bell, and My
Account; 651–767px remains a single row with the same visible controls and
left-aligned logo; 650px and below uses a compact logo/bell/avatar top row plus
a full-width Job Center trigger that opens the existing three-link navigation
as a right-side drawer. Between 501–650px the header remains a compressed
single row; at 500px and below it uses the two-row final mobile presentation.
No legacy wrapped account row may return, and mobile navigation capability must
not be removed to make the header fit. The drawer uses the existing My Jobs,
Career Resources, and Teacher Resources destinations, has an accessible close
control, Escape dismissal, and returns focus to its trigger. This remains an
implementation target pending external browser and human acceptance.

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

On logged-out Finder and Search surfaces, Responsive Layout Geometry v1 governs
the breakpoint and physical conditions for rail retention or collapse. When the
rail enters the main flow after results, pagination, and the main-flow
advertisement, it uses this exact order:
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
- Right-rail support content may remain visible only where Responsive Layout
  Geometry v1 permits retained-rail geometry and an Approved tablet authority
  establishes that presentation.
- Responsive work adapts Approved authorities rather than redesigning them.
- Once a responsive authority reaches convergence, future work enters Patch
  Mode.

## 10. Desktop Authority Inheritance
The JC-051A Employer My Jobs Desktop Authority v1.0 is the current desktop visual authority. Responsive derivation must follow stable desktop implementation and acceptance; this desktop authority is not itself a mobile behavior rule. No responsive decisions are superseded by JC-051A.

## 11. Responsive Workflow

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
