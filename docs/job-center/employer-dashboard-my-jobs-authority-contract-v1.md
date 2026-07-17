# Employer Dashboard and My Jobs Authority Contract v1

Status: Contract for visual-authority work
Ticket: EMP-AUTH003
Scope: Employer Dashboard and My Jobs only
Dependency: [Employer Access and Authority Contract v1](employer-access-authority-contract-v1.md)

This contract defines the minimum V1 visual authority for the authorized
employer operating workspace and job inventory. My Jobs is the operating
workspace; prior Dashboard summary concepts are absorbed into it.
It does not define authoring, Review, Preview, metrics, moderation, or admin.

## 1. Shared employer shell and context

Both surfaces use one reusable employer shell containing:

- approved Job Center shell, typography, spacing, footer, and responsive language;
- authenticated user and selected canonical employer identity;
- employer navigation to All My Jobs, My Schools / Job Sites, Add School / Job
  Site, and Manage Schools / Job Sites;
- explicit context persistence when moving between those surfaces;
- shared notices, status pills, action controls, and accessible focus treatment.

Single-employer members enter the operating workspace with static employer
context. Multi-employer members select only from active authorized memberships
when a selector is needed. A selected employer remains visible and is carried
into My Jobs and Post Job. Unauthorized or revoked
employers are never presented as selectable active context.

## 2. Employer Operations composition

The workspace is an employer-scoped operating surface, not a separate dashboard
route or analytics dashboard. Its hierarchy is:

1. Standard Teachers.Net shell and employer context.
2. One active left-navigation selection: All My Jobs, My Schools / Job Sites,
   Add School / Job Site, or Manage Schools / Job Sites.
3. Workspace title and truthful job count.
4. School / Job Site column and bounded inventory.
5. Job Timeline status, attention, and valid actions.
6. Pagination and rows-per-page selector.

All My Jobs and an individual School / Job Site view are mutually exclusive.
The selected school is highlighted, the single-school view provides Back to All
My Jobs, and both views retain the School / Job Site column and pagination.

## 3. My Jobs composition

The selected inventory view is authoritative at job level. Its hierarchy is:

1. Employer shell and selected employer context.
2. **All My Jobs** or selected School / Job Site heading and employer identity.
3. Primary **Post a Job** action.
4. Status filters.
5. Bounded job inventory with one card/row per canonical job.
6. Per-job lifecycle status, visibility guidance, dates, and meaningful
   engagement values.
7. Only actions valid for the current state.
8. Pagination where the inventory exceeds one page.
9. Empty, error, or no-access treatment where applicable.

The inventory preserves canonical job identity and does not create separate
employer-posted versus imported job products.

## 4. Status vocabulary and lifecycle truth

The visual authority uses these governed labels and distinctions:

| Status | Meaning | Public truth |
|---|---|---|
| Draft | Work retained but not submitted | Not public |
| Awaiting Review | Submitted to the standard review path | Not Live |
| Published — Live | Eligible and currently public | Live |
| Published — Needs Attention | Stored as published but not currently public because an eligibility condition needs resolution | Not Live |
| Closed | Employer intentionally ended the opportunity | Not public; history retained |
| Expired | Date-derived end of ordinary eligibility | Not public; history retained |
| Archived | Retained under the approved archive rule | Not public |

Status, visibility, next-step guidance, and relevant dates must agree. “Live”
must not be inferred from stored `published` status alone.

## 5. Filters, pagination, and state matrix

The My Jobs filter vocabulary is bounded to:

- All
- Live / Published
- Awaiting Review
- Drafts
- Closed / Expired

Pagination is shown only when another inventory page exists. The Dashboard may
link directly to a filtered My Jobs view but does not reproduce its pagination.

| Condition | Dashboard treatment | My Jobs treatment |
|---|---|---|
| Logged out | Shared login-required state with return to employer portal | Same, returning to My Jobs |
| No active membership | No employer access notice and Request Employer Access path | Same; no inventory shown |
| Invalid selected employer | Access-unavailable notice; do not expose data | Access-unavailable/selector state |
| Multiple active employers, none selected | Employer selector only | Employer selector only |
| Multiple active employers, selected | Selector/context plus summary | Selector/context plus inventory |
| Empty inventory | Summary counts at zero and Post a New Job action | No jobs posted yet plus Post a Job action |
| Filter has no matches | Counts remain truthful | Explicit no-results state with reset/All path |
| Data/service error | Recoverable error notice | Recoverable error notice; no fabricated rows |
| Inventory present | Counts, attention, and bounded recent summary | Complete retained-policy inventory |

## 6. Allowed actions by job status

| Status/condition | Allowed visual actions |
|---|---|
| Draft | Edit, Duplicate, Archive; Post/submit remains in authoring scope |
| Awaiting Review | Edit only when permitted by lifecycle rule; View only if public visibility exists; no false Live action |
| Published — Live | Edit, View, Close, Duplicate |
| Published — Needs Attention | Edit or View according to permission/public state; explain the blocking condition |
| Closed | View, Renew, Duplicate |
| Expired | View, Renew, Duplicate |
| Archived | Archived indicator only unless the approved retention rule grants another action |

The contract does not add actions or permissions. Existing route enforcement
remains authoritative.

## 7. Responsive inheritance and employer-specific deltas

- Inherit the approved shared shell, drawer, footer, typography, controls,
  spacing rhythm, and responsive layout classes.
- Preserve employer context at every breakpoint.
- Dashboard summary panels may stack vertically on narrow widths.
- My Jobs cards/rows may stack their metadata and actions using the shared
  responsive language; status, title, dates, and valid actions remain prominent.
- Do not create employer-specific breakpoints, a second mobile shell, or a
  separate tablet information architecture.
- Touch targets, focus order, and visible status distinctions remain compliant
  at all governed widths.

## 8. Required renders and state economy

Maximum: two rendering passes.

1. **Base authority render:** one representative selected-employer Dashboard
   and My Jobs composition using the shared shell and normal populated data.
2. **Bounded state sheet:** one compact sheet covering login/no-access,
   multi-employer selection, empty/no-results, service error, pagination, and
   the governed lifecycle-status/action variants.

Individual renders for every status, filter, or action are not required.
Minor typography, spacing, and browser polish belong in implementation notes.

## 9. Explicit V1.1 deferrals

- Employer Metrics as a distinct screen or analytics dashboard.
- Rich employer-profile editing and organization administration.
- New lifecycle actions, role-specific dashboards, or permission variants.
- Separate visual authorities for every filter, pagination page, or mutation
  confirmation.
- Advanced search, sorting, bulk actions, exports, saved views, and notification
  centers.
- Any Atlas employer placeholder outside Dashboard/My Jobs and their mandatory
  access, empty, permission, and lifecycle states.

## 10. Acceptance criteria

EMP-AUTH003 visual authority is complete when:

- Dashboard clearly summarizes and routes attention without duplicating My Jobs;
- My Jobs presents the complete required inventory with truthful status,
  visibility, dates, and valid actions;
- the shared employer shell and selected-employer context persist across both
  surfaces and multi-employer switching is bounded to active memberships;
- login, no-access, invalid-context, empty, no-results, error, and pagination
  states are covered by the authority or explicit inheritance;
- all seven lifecycle labels remain distinguishable and public “Live” truth is
  not overstated;
- actions are limited to the existing route and lifecycle rules;
- responsive inheritance is explicit and no employer-specific layout system is
  introduced;
- the two-render maximum is respected and minor polish is implementation-only;
- no authoring, Review, Preview, metrics, moderation, admin, route, status, or
  permission behavior is introduced;
- `git diff --check` passes.
