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

At tablet width, the `728 × 90` leaderboard retains its intrinsic proportion
when it fits inside the governed content inset; on mobile it is replaced by a
legible responsive advertisement reservation rather than scaled into
illegibility. The `300 × 250` placement retains its intrinsic reservation and
is centered in the stacked support flow at tablet and mobile widths. Reserved
space remains stable while inventory loads and collapses only when the governed
serving policy explicitly identifies the placement as empty; every placement
remains labeled and may not cause horizontal overflow.

## 6. Job Detail

JC-030 mobile uses this conversion order: **Apply → Save → Share → Narrative →
Employer → Related Jobs → Advertisement**. Apply appears once as the primary
conversion action immediately after job identity, Save and Share remain
secondary, and the later application information explains method and external
handoff without repeating a competing Apply control; job facts remain with the
narrative before Employer. This is an intentional exception to ordinary
support-rail stacking because application conversion is the primary Job Detail
task.

## 7. Modal Behavior

On mobile, the location modal is an inset viewport-bound dialog with a maximum
height that preserves surrounding context; its heading, close control, tabs,
and bottom action row remain reachable while the content region scrolls
internally when height is constrained. **Cancel**, the close control, and
Escape dismiss without applying changes; backdrop interaction does not dismiss
the dialog. Focus remains contained while open and returns to the Location
trigger after dismissal.
