# Employer Lifecycle Authority Contract v1

Status: Contract for visual-authority work
Ticket: EMP-AUTH005
Scope: Employer lifecycle state variants within My Jobs
Dependencies: [EMP-AUTH002](employer-access-authority-contract-v1.md), [EMP-AUTH003](employer-dashboard-my-jobs-authority-contract-v1.md), [EMP-AUTH004](employer-authoring-authority-contract-v1.md)

Lifecycle authority is a bounded state treatment of the approved My Jobs
composition. It is not a set of independent screens and does not change the
existing routes, statuses, permissions, or mutations.

## 1. Lifecycle vocabulary and status presentation

Use the existing canonical vocabulary:

| Status | Meaning | Visibility treatment |
|---|---|---|
| Draft | Employer work retained but not submitted | Not public; actionable as incomplete work |
| Awaiting Review | Submitted to the standard review path | Not Live; review is pending |
| Published — Live | Public-eligibility rule confirms current publication | Live and viewable publicly |
| Published — Needs Attention | Stored as published but an eligibility condition prevents current public visibility | Not Live; explain the blocking condition |
| Closed | Employer intentionally ended the opportunity | Not public; record and history retained |
| Expired | Date-derived end of ordinary eligibility | Not public; record and history retained |
| Archived | Retained under the approved archive rule | Not public; retained-history state |

Status, visibility, guidance, dates, and action availability must agree. Stored
`published` status alone must never be shown as Live.

## 2. Allowed actions by status

| Status/condition | Supported actions |
|---|---|
| Draft | Edit, Duplicate, Archive draft |
| Awaiting Review | Edit only where the existing lifecycle rule permits; no false Live action |
| Published — Live | View, Edit, Close, Duplicate |
| Published — Needs Attention | View or Edit according to existing permission/state rules; explain the issue |
| Closed | View, Renew, Duplicate |
| Expired | View, Renew, Duplicate |
| Archived | Archived indicator only unless the existing retention rule explicitly permits another action |

The authority must not add reopen, delete, bulk, restore, or other lifecycle
actions. Renew creates a lineage-linked Draft; Duplicate creates a separate
Draft without renewal meaning; Close ends public eligibility without replacing
the record.

## 3. Confirmation treatment

Confirmation treatment is limited to existing mutation semantics:

- **Close:** state that the job will stop accepting applications and remain in
  retained history.
- **Archive draft:** state that the draft will leave the active working set
  under the approved retention rule.
- **Renew:** state that a new lineage-linked Draft will be created; the source
  record remains unchanged.
- **Duplicate:** state that a separate new Draft will be created without
  renewal lineage.

Confirmation must identify the affected job, consequence, and available
cancel/return path. It must not imply mutation succeeded before the server
result. If the current route uses a direct POST action rather than a modal,
the same consequence copy belongs in the bounded action treatment; no new
confirmation interaction is authorized by this contract.

## 4. Success, failure, and permission-denied outcomes

| Outcome | Required presentation |
|---|---|
| Close succeeds | “Job closed” notice; retained Closed status and history |
| Renew succeeds | “Job renewed as a draft” notice; new Draft identity/context |
| Duplicate succeeds | “Job duplicated as a draft” notice; separate Draft identity/context |
| Archive succeeds | “Draft job archived” notice; retained Archived state |
| Edit/update succeeds | “Job updated” notice; show resulting status/visibility truth |
| Mutation fails | Error notice naming the failed action; preserve source job and no false success |
| Permission denied | Access/lifecycle unavailable notice; no mutation and no unauthorized action affordance |
| Invalid lifecycle | Explain that the action is not valid for the current status; preserve record |

No error or success treatment may imply application receipt, employer
endorsement, publication, or Live status unless the existing eligibility rule
establishes it.

## 5. Expired, archived, empty, and retained-history states

- **Expired:** distinguish from Closed; explain that expiry is date-derived and
  that Renew creates a new Draft rather than silently reactivating the source.
- **Archived:** retain a truthful non-public indicator and do not present the
  item as an active posting.
- **Empty active inventory:** preserve the My Jobs empty state and Post a Job
  action when no jobs exist.
- **Filtered empty:** explain that the selected status filter has no matches
  and provide the existing All/reset path.
- **Retained history:** keep Closed, Expired, and Archived records distinct
  where the approved retention policy exposes them; do not erase identity,
  dates, lineage, or engagement history.

## 6. Responsive inheritance

- Inherit the approved My Jobs shell, Job Center typography, spacing, controls,
  footer, drawer, and responsive layout classes.
- Preserve status, guidance, dates, and valid actions at every breakpoint.
- Stack lifecycle metadata and action groups on narrow widths using shared
  responsive language; do not create lifecycle-specific breakpoints.
- Maintain comfortable touch targets, keyboard order, visible focus, and status
  contrast without changing the visible design language.

## 7. State matrix and render economy

| State family | Covered treatment |
|---|---|
| Active inventory | Draft, Awaiting Review, Published — Live, Published — Needs Attention |
| Retained lifecycle | Closed, Expired, Archived |
| Mutation confirmation | Close, Renew, Duplicate, Archive consequences and cancel/return treatment |
| Mutation result | Success, failure, invalid state, permission denied |
| Inventory conditions | Empty active set, filtered empty, retained history |

Maximum: two rendering passes.

1. **Base authority render:** one populated My Jobs composition showing active
   and retained lifecycle cards with status, guidance, dates, and valid actions.
2. **Bounded state sheet:** one compact treatment of confirmations, success,
   failure, permission-denied, empty/filter-empty, and retained-history states.

Separate renders for every status or action are not required.

## 8. Explicit V1.1 deferrals

- New lifecycle statuses or actions, including reopen, restore, delete, bulk
  operations, or employer-controlled status overrides.
- Advanced archive search, archive restoration, close-reason workflows, and
  lifecycle history timelines beyond the retained state treatment.
- Separate lifecycle screens, analytics, notifications, exports, or saved views.
- Role-specific lifecycle permissions beyond existing route enforcement.
- Any employer authority outside My Jobs lifecycle state variants.

## 9. Acceptance criteria

EMP-AUTH005 visual authority is complete when:

- all seven existing lifecycle states are visually distinguishable and truthful;
- supported actions match current route permissions and status rules;
- Close, Renew, Duplicate, and Archive consequences are explicit without
  introducing a new interaction model;
- success, failure, invalid-state, and permission-denied outcomes are covered;
- Expired and Archived remain distinct from Closed and active postings;
- empty, filtered-empty, and retained-history states preserve truthful context;
- responsive inheritance is explicit and no lifecycle-specific layout system is
  introduced;
- the two-render maximum is respected and minor polish is implementation-only;
- no new statuses, permissions, routes, or lifecycle behavior are introduced;
- `git diff --check` passes.
