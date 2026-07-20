# Employer Operations / My Jobs Authority Contract v1

Status: Contract for visual-authority work
Ticket: EMP-AUTH003
Scope: Employer Operations / My Jobs only
Dependency: [Employer Access and Authority Contract v1](employer-access-authority-contract-v1.md)

Desktop visual authority: `docs/job-center/design/approved/jc-050-approved-candidate-01a.png` (DESIGN-AUTHORITY007). This supersedes JC-050 v1.1 and v1.0 only for the All My Jobs desktop visual boundary and does not change product rules.

This contract defines the minimum V1 visual authority for the authorized
employer operating workspace and job inventory. My Jobs is the operating
workspace; prior Dashboard summary concepts are absorbed into it as
notifications, attention states, workflow guidance, summary context, and School
/ Job Site scope. A separate Employer Dashboard is no longer the preferred V1
operating direction.
It does not define authoring, Review, Preview, metrics, moderation, or admin.

## 1. Shared employer shell and context

The workspace uses one reusable employer shell containing:

- approved Job Center shell, typography, spacing, footer, and responsive language;
- authenticated user and selected canonical employer identity;
- employer navigation to All My Jobs, My Schools / Job Sites, Add School / Job
  Site, and Manage Schools / Job Sites;
- explicit context persistence when moving between My Jobs, School / Job Site,
  Post Job, and return paths;
- shared notices, status pills, action controls, and accessible focus treatment.

Single-employer members enter My Jobs with static employer context.
Multi-employer members select only from active authorized memberships when a
selector is needed. A selected employer remains visible and is carried into My
Jobs and Post Job. Unauthorized or revoked employers are never presented as
selectable active context.

## 2. Employer Operations composition

The workspace is an employer-scoped operating surface, not a separate dashboard
route or analytics dashboard. Its hierarchy is:

1. Standard Teachers.Net shell and employer context.
2. One active left-navigation selection: All My Jobs, My Schools / Job Sites,
   Add School / Job Site, or Manage Schools / Job Sites.
3. Workspace title and truthful job count.
4. Status filter labeled **Filter Jobs By** and separate sorting control.
5. School / Job Site column and bounded inventory.
6. Job Timeline status, attention, and valid actions.
7. Pagination and rows-per-page selector.

All My Jobs and an individual School / Job Site view are mutually exclusive.
The selected school is highlighted, the single-school view provides Back to All
My Jobs, and both views retain the School / Job Site column and pagination.

## 3. My Jobs composition

The selected inventory view is authoritative at job level. Its hierarchy is:

1. Employer shell and selected employer context.
2. **All My Jobs** or selected School / Job Site heading and employer identity.
3. Primary **Post a Job** action.
4. Status filters and separate sorting.
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
| Live | Eligible and currently public | Live |
| Expiring Soon | Still Live but needs attention before expiry | Live |
| Expired | Date-derived end of ordinary eligibility; temporary recoverable attention state | Not public; history retained |
| Closed | Employer intentionally ended the opportunity; historical state | Not public; history retained |
| Archived | Retained under the approved archive rule | Not public |

Status, visibility, next-step guidance, and relevant dates must agree. “Live”
must not be inferred from stored `published` status alone.

The timeline heading remains **Job Timeline**. Every listing row reserves the
same minimum height and fixed three-line timeline space. Non-draft first lines
combine timing/state and expiration date. Draft rows use the exception `Last
updated <date>` while preserving full row height. State is communicated with
text color; attention is communicated with an exclamation icon only when
appropriate. Colored bullets, colored dots, whole-row red fills, and urgent
Closed treatment are not approved.

## 5. Filters, pagination, and state matrix

The My Jobs filter vocabulary is bounded to:

- Draft
- Awaiting Review
- Live
- Expiring Soon
- Expired
- Closed

Sorting remains separate from filtering. Approved V1 sorting may include only
choices supported by the current model; it must not become an advanced search
interface.

Pagination is required, not optional polish. It includes Previous, numbered
pages, Next, and a rows-per-page selector. Represent pagination in authority,
implementation planning, acceptance criteria, and feasibility review.

| Condition | My Jobs treatment |
|---|---|
| Logged out | Shared login-required state with return to My Jobs |
| No active membership | No employer access notice and contextual authority path |
| Invalid selected employer | Access-unavailable/selector state; do not expose data |
| Multiple active employers, none selected | Employer selector only |
| Multiple active employers, selected | Selector/context plus inventory |
| Empty inventory | No jobs posted yet plus Post a Job action |
| Filter has no matches | Explicit no-results state with reset/All path |
| Data/service error | Recoverable error notice; no fabricated rows |
| Inventory present | Complete retained-policy inventory |

## 6. Allowed actions by job status

| Status/condition | Primary action | Secondary actions |
|---|---|---|
| Draft | Continue | Overflow only |
| Awaiting Review | View | Overflow only |
| Live | View | Overflow only |
| Expiring Soon | Extend | Overflow only |
| Expired | Extend | Overflow only |
| Closed | Duplicate or Repost, unresolved | Overflow only |
| Archived | Archived indicator only unless the approved retention rule grants another action | None unless governed |

One primary action appears per row. Secondary actions belong in an overflow
menu. Duplicate versus Repost remains unresolved; do not settle it in visual or
implementation work without a separate decision. The contract does not add
actions or permissions. Existing route enforcement remains authoritative.

## 7. Responsive inheritance and employer-specific deltas

- Inherit the approved shared shell, drawer, footer, typography, controls,
  spacing rhythm, and responsive layout classes.
- Preserve employer context at every breakpoint.
- My Jobs cards/rows may stack their metadata and actions using the shared
  responsive language; status, title, dates, and valid actions remain prominent.
- Do not create employer-specific breakpoints, a second mobile shell, or a
  separate tablet information architecture.
- Touch targets, focus order, and visible status distinctions remain compliant
  at all governed widths.

## 8. Required renders and state economy

Maximum: two rendering passes.

1. **Base authority render:** one representative My Jobs workspace composition
   covering All My Jobs and selected School / Job Site route views using the
   shared shell and normal populated data.
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
- Any Atlas employer placeholder outside My Jobs and its mandatory
  access, empty, permission, and lifecycle states.

## 10. Acceptance criteria

EMP-AUTH003 visual authority is complete when:

- My Jobs presents the complete required inventory with truthful status,
  visibility, dates, and valid actions;
- the shared employer shell and selected-employer context persist through My
  Jobs, selected School / Job Site views, Post Job, and return paths;
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
