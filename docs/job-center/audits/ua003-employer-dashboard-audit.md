# UA003 - Employer Dashboard Audit

**Project:** Teachers.Net - Job Center

**Mode:** Documentation-only inspection

**Audit date:** 2026-07-11

**Contract:** `docs/job-center/canonical-v1-contract.md`

**Prior audits:** `docs/job-center/audits/ua001-employer-journey-audit.md`
and `docs/job-center/audits/ua002-post-job-wizard-audit.md`

**Implementation inspected:** current `wordpress/wp-content/plugins/tnet-jobs`

## Executive summary

The employer Dashboard and My Jobs surfaces share a sound authorization model:
both require an authenticated user with an active membership for the selected
canonical employer, both scope records by `employer_id`, and both continue to
work for an approved claimant without creating a separate claimed-employer
portal. This complies with the Canonical V1 Contract.

Their product roles are not yet sufficiently distinct. Dashboard shows four
summary cards and six recent jobs. My Jobs shows the complete paginated
management list, lifecycle filters, dates, engagement counts, and actions. The
same job status and view data therefore appear twice, but the Dashboard does not
use the saved space to highlight the most important exceptions or next actions:
jobs awaiting review, expiring soon, non-public despite a published status,
incomplete drafts, or closed/expired jobs ready for renewal.

The recommended purpose boundary is:

- **Dashboard:** a concise employer-level operational summary that answers
  “What needs my attention now?” and routes to the correct My Jobs view.
- **My Jobs:** the authoritative job-level lifecycle inventory and action
  center that answers “What is the state of each posting, why, and what can I do
  next?”

Current lifecycle presentation is usable but not fully truthful. `published`
is labeled **Published**, guided as **Live**, and described in a tooltip as
publicly visible and accepting applications even though application integrity,
suppression, or other canonical eligibility conditions can prevent public
visibility. Expiration is correctly date-derived, but archived records are
excluded from My Jobs and therefore have no employer-visible history. The
Dashboard counts only the newest 100 employer jobs, so its totals and Total
Views can silently become incomplete for larger employers.

Engagement reporting is appropriately limited for V1. Views, Saves, and
application-instruction reveals are unique-user signals, not applications.
However, Dashboard exposes only Total Views, My Jobs labels the reveal metric
**Opened**, and neither surface states the time window or unique-user meaning.
The gap is clarity and consistency, not a need for an analytics dashboard.

No redesign or parallel management system is recommended. The smallest
coherent path is to establish the purpose boundary, use one truthful lifecycle
vocabulary based on actual eligibility, make metrics self-explanatory, preserve
employer selection across routes, and reuse existing filtered My Jobs URLs and
lifecycle services.

## Audit scope and method

Inspected:

- employer portal and My Jobs access resolution;
- single- and multi-employer selection;
- shared employer navigation and account context;
- Dashboard counts, recent jobs, dates, statuses, and links;
- My Jobs queries, filters, grouping, pagination, cards, metrics, and actions;
- engagement aggregation semantics;
- close, archive, duplicate, renew, edit, and view availability;
- Canonical V1 lifecycle, authority, application, and metrics rules;
- UA001 employer-journey and UA002 wizard/trust findings.

This audit did not mutate jobs, memberships, claims, or engagement rows and did
not perform visual redesign or browser acceptance testing.

## Purpose definition

### Employer Dashboard purpose

The Dashboard should be the employer's operational landing page. Its V1 job is
to establish context, summarize current workload, expose exceptions, and route
the employer to a specific My Jobs view or posting task. It should not become a
second full job manager or an advanced analytics dashboard.

The Dashboard should answer:

1. Which employer am I managing?
2. What needs attention now?
3. How many jobs are live, in draft, awaiting review, expiring, or inactive?
4. What is the clearest next action?
5. Where do I manage the complete inventory?

### My Jobs purpose

My Jobs should be the authoritative employer-facing lifecycle inventory and
action center for one selected employer. It should expose every manageable or
historically relevant posting, the truthful reason for its current visibility,
key dates and basic engagement, and only the actions valid for that state.

My Jobs should answer:

1. What jobs belong to this employer?
2. Which are public, awaiting review, draft, closed, expired, blocked, or
   archived?
3. Why is each job in that state?
4. What action is available next?
5. What basic engagement has each public posting received?

## Current surface inventory

| Surface | Current content | Strength | Primary gap |
|---|---|---|---|
| Shared employer shell | Dashboard, My Jobs, Post a Job navigation; user name and selected employer label. | Compact and consistent across employer routes. | No current-page state is evident from the renderer, and selected-employer continuity depends on each generated URL carrying `employer_id`. |
| Dashboard hero | Welcome message and **Post a New Job**. | Clear primary posting action and employer identity. | No secondary route to the full inventory except through nav or Recent Jobs link. |
| Dashboard summary | Active Jobs, Drafts, Waiting for Review, Total Views. First three link to filtered My Jobs. | Good routing pattern and appropriate V1 density. | Omits expiring, closed/expired, blocked/non-public, saves, and interest. Total Views is not actionable and is computed only across the newest 100 jobs. |
| Dashboard Recent Jobs | Six newest employer jobs: title, status, views, posted/created date. Public titles link to detail; non-public titles are plain text. | Quick situational scan. | Repeats My Jobs but has no management action or clear edit link. It can include archived records and uses “Posted” for a created date on non-published records. |
| My Jobs hero | Employer name, **Post a Job**, notices. | Clear context and primary action. | Does not summarize the selected filter/result count or explain its role as full management. |
| My Jobs filters | All, Live / Published, Awaiting Review, Drafts, Closed / Expired. | Maps major V1 states and preserves employer ID in links. | No Archived or Needs Attention/Blocked view; **Live / Published** conflates status and actual public eligibility. |
| My Jobs grouping | Live Jobs, Awaiting Review, Drafts, Closed & Expired, Other Jobs. | Helpful lifecycle scanning. | Groups are applied only to the current page, so headings/counts are page-local, not employer totals. Closed and expired remain meaningfully different but are merged. |
| My Jobs card | Status, title, guidance, Created / Posted, Expiration, Views, Opened, Saved, actions. | Compact complete V1 management row. | Date label is ambiguous; metric terms lack definitions; public eligibility blockers and renewal lineage are absent. |
| My Jobs pagination | 20 jobs per page with Previous, Page number, Next. | Simple and bounded. | No total result count or last page; group counts are not totals. |
| Notices | Created/updated/closed/duplicated/renewed/archived confirmation. | Reuses a consistent success area. | Notices are terse and do not explain lifecycle consequence, especially renew versus duplicate and archive disappearance. |

## Employer switching audit

### Current behavior

- One active membership bypasses selection and opens that employer directly.
- Multiple memberships require a card selection on Dashboard or My Jobs.
- The selector displays employer name, membership role, and membership status.
- The selected employer travels as a validated `employer_id` query argument.
- Dashboard, My Jobs, and create independently resolve membership scope.
- A requested employer outside the user's active memberships is rejected.

### Compliance

This is contract-compliant and preserves the central rule that an active
membership, not a query string or employer identity match, grants authority.
It also supports immediate dashboard continuity after an approved claim.

### Friction and opportunities

- Dashboard and My Jobs use separate selector wrappers and wording for the same
  decision.
- Moving through shared navigation can drop the current employer unless every
  URL is generated with the selected ID.
- The account summary can say **Multiple employers** rather than keeping the
  selected employer visible when context is absent.
- The selected employer is request context, not a durable preference. That is
  acceptable for V1, but route continuity should be deterministic.
- Membership role is displayed but does not appear to change visible actions;
  if roles do not create distinct V1 authority, the role is unnecessary
  operational detail. If roles do matter, action differences are missing.

Implementation should reuse one employer-context selector and URL-building
rule across Dashboard, My Jobs, Post, Edit, and confirmations. Do not add an
alternate account-switching subsystem.

## Navigation audit

The shared employer nav contains exactly the three core tasks: Dashboard, My
Jobs, and Post a Job. This is appropriate for V1 and avoids feature sprawl.

Gaps:

- the current destination is not marked with `aria-current` in the renderer;
- Dashboard Recent Jobs provides **View all jobs**, but non-public titles have
  no direct management link;
- Dashboard count cards route well, but Total Views is a dead-end card;
- no navigation entry is needed for metrics, claims, or moderation in V1;
  those should remain context or status, not new portals;
- no explicit route exposes archived history, which is a lifecycle policy gap,
  not a reason for a broad navigation expansion.

## Lifecycle visibility and status audit

| Lifecycle / condition | Dashboard | My Jobs | Clarity finding |
|---|---|---|---|
| Draft | Count card; may appear in Recent Jobs as Draft / Not submitted yet. | Draft filter/group; Edit, Archive, Duplicate. | Generally clear, but UA002 found the primary create wizard cannot create/save an original draft. |
| Submitted | Waiting for Review count; recent status. | Awaiting Review filter/group; Edit and Duplicate. | “Awaiting review” is clear, but no moderation reason, submitted date, queue context, or effect of editing is shown. |
| Published and publicly eligible | Active count; Published pill, Live guidance, views. | Live / Published; View, Edit, Close, Duplicate; metrics. | Appropriate when truly eligible. “Active,” “Published,” and “Live” are three labels for one condition. |
| `published` but not publicly eligible | Counted as Active unless date-expired; status guidance says Live. | Included in Live filter if not expired; action set resembles live job. | Contract gap. Application integrity, suppression, or other eligibility blocks can make “Live” false. |
| Expiring soon | No dedicated signal. | Expiration date only. | Missing highest-value operational warning; contract has durable expiry and renewal expectations. |
| Expired | Counted internally but no Dashboard card. | Closed / Expired filter/group; Expired / No longer listed; View, Renew, Duplicate. | Usable but merged with Closed; reason and renewal consequence are terse. |
| Closed | Counted internally but no Dashboard card. | Closed / Expired; Closed / No longer active; View, Renew, Duplicate. | Distinct status is visible inside the merged group. Close reason/time is absent. |
| Archived | Can appear in Dashboard counts/recent data because Dashboard fetches all employer jobs. | Excluded from normal query and filters. | Inconsistent visibility. Employer archives a draft and then loses its normal history surface. |
| Suppressed / invalid application / source conflict | No explicit signal. | No explicit status/group; may appear as Published/Live based on status. | Missing canonical eligibility explanation and repair path. |
| Renewed lineage | Source remains Closed/Expired; new draft appears separately. | Both records can appear, but relationship is not shown. | Renewal history exists in data but is invisible to employer. |

### Status vocabulary

Current vocabulary includes **Active Jobs**, **Live / Published**, **Live Jobs**,
**Published**, and **Live** for the public state; **Waiting for Review** and
**Awaiting Review** for submitted; and **Closed / Expired** plus **Closed &
Expired** for inactive.

The terms are individually understandable but collectively inconsistent. A
single employer-facing vocabulary should be used across count cards, filters,
groups, pills, guidance, notices, emails, and moderation:

- Draft
- Awaiting Review
- Live (only when actually publicly eligible)
- Needs Attention / Not Public (when status alone would overstate eligibility)
- Closed
- Expired
- Archived

This is a content contract, not authorization to redesign statuses or add a new
database lifecycle.

## Actions audit

| State | Current actions in My Jobs | Finding |
|---|---|---|
| Draft | Edit, Archive, Duplicate | Appropriate, but Archive is immediate and removes the record from My Jobs. Duplicate is low-value on an incomplete draft and may amplify incomplete content. |
| Submitted | Edit, Duplicate | Edit is useful, but no copy explains whether editing changes moderation position or requires resubmission. Duplicate while pending can create avoidable near-duplicates. |
| Published | Edit, View, Close, Duplicate | Core actions are present. Close is an immediate POST with no confirmation or consequence copy. |
| Closed | View, Renew, Duplicate | Correct capability set, but Renew and Duplicate both produce drafts and are not differentiated. |
| Expired | View, Renew, Duplicate | Correct capability set, with the same ambiguity as Closed. |
| Archived | No normal My Jobs row | No employer history, recovery, or explanation after archive. |
| Other / blocked | Duplicate or No action depending on status | “No action” does not explain the blocker or administrator path. |

Dashboard Recent Jobs intentionally has no action column, but this makes it a
second read-only inventory rather than a next-action surface. The title should
route to edit/manage when a public detail link is unavailable, or the row should
route to the corresponding My Jobs context. Business rules must remain in the
existing service/action handlers.

## Metrics and engagement reporting audit

### Current semantics

The engagement repository counts rows per job where:

- `viewed_at` is present = Views;
- `saved_at` is present = Saved;
- `revealed_at` is present = Interested.

Because engagement is stored per user/job pair, these are unique logged-in user
signals. They are not page views, anonymous traffic, applications, applicants,
or successful hires.

### Current presentation

- Dashboard sums Views across the jobs returned in its newest-100 query and
  labels the card **Total Views** / **Tracked views**.
- Dashboard Recent Jobs shows Views per job.
- My Jobs shows Views, **Opened**, and Saved per job.
- No surface states that values are unique users or all-time counts.
- No period, trend, denominator, or conversion rate is shown.

### Findings

1. **Opened** is unclear and diverges from the canonical **Interested** concept.
   A precise label such as **Application instructions opened** is more truthful.
2. Dashboard **Total Views** is incomplete after 100 employer jobs and should
   not be labeled total unless computed independently of the recent-job limit.
3. Summing per-job unique users is a total of unique job-view events, not unique
   people across the employer; the same user can count once on multiple jobs.
4. Views appear in both Dashboard summary and Recent Jobs, while Saves and
   Interested appear only in My Jobs. This creates inconsistent emphasis.
5. Zero values are meaningful but can look like tracking failure without a
   definition or start date.
6. Advanced reporting remains correctly out of V1. Clarity, not additional
   charts, is the immediate need.

## Duplicated information

- Dashboard Recent Jobs repeats My Jobs title, status, date, and Views.
- Dashboard count cards repeat My Jobs filters and lifecycle grouping, although
  the links make this duplication useful when counts are accurate.
- Employer identity appears in shell account context, page heading, hero copy,
  and selection UI. Some repetition is necessary for authority clarity; it
  should use one selected-employer source.
- Status appears as pill plus guidance and sometimes group/filter context. This
  is useful only when the vocabulary is consistent and guidance adds meaning.
- **Post a Job** appears in shared navigation, Dashboard hero, My Jobs hero, and
  empty state. The redundancy is appropriate for the primary task but can be
  visually prioritized by context rather than treated as four equal CTAs.

## Missing information

- truthful distinction between stored status and actual public eligibility;
- expiring-soon attention and renewal timing;
- application-integrity, suppression, location, or moderation blockers;
- moderation submission date, return reason, or clear next step;
- archived history and archive consequence;
- renewal/source lineage;
- explicit metric definitions, scope, and tracking window;
- complete Dashboard totals beyond 100 jobs;
- My Jobs total result count and page-local versus overall group-count clarity;
- a direct management route from a non-public Dashboard recent job;
- consistent selected-employer context across all employer nav destinations.

## Unnecessary or low-value information

- membership **Status: Active** in an active-only selector is redundant unless
  inactive/pending memberships are intentionally displayed.
- membership role is unnecessary if all roles have identical actions; otherwise
  missing role-based action clarity is the real gap.
- Dashboard **Total Views** is lower operational value than expiring or blocked
  jobs and is misleading when capped.
- Raw employer ID fallback labels are diagnostic rather than employer-facing;
  they should appear only when identity data is genuinely unavailable.
- **Created / Posted** combines two dates into one label while choosing only
  one, creating ambiguity without adding information.
- “No action” is not useful information; it should explain why management is
  unavailable or omit the action area.

## Findings

### 1. Authorization and post-claim continuity comply with the contract

Both surfaces reuse active membership scope and the canonical employer ID.
Approved claimants therefore inherit the same dashboard and existing associated
jobs without replacement, duplication, or erased history.

### 2. Dashboard is a summary but not yet an attention dashboard

Its count cards and recent table are coherent, but they describe inventory
rather than prioritizing employer work. The highest-value V1 exceptions are
missing while a potentially incomplete Total Views card occupies summary space.

### 3. My Jobs is the correct action center

My Jobs already contains the necessary structural foundation: lifecycle filters,
pagination, grouped cards, dates, metrics, and state-specific actions. It should
remain the one complete management surface rather than expanding Dashboard into
a duplicate manager.

### 4. Status is not equivalent to public eligibility

UA002 identified that direct-publish trust and application integrity can create
a mismatch between `published` status and actual public availability. Dashboard
and My Jobs currently translate published directly to Live. This must be
corrected at the presentation-contract level using the canonical eligibility
rule, without inventing a second lifecycle.

### 5. Basic metrics are sufficient but inconsistently named

V1 does not need charts, funnels, anonymous analytics, or an application count.
It needs accurate aggregate queries and one definition shared by Dashboard and
My Jobs.

## Implementation opportunities

These require separate approved tickets:

- separate Dashboard summary queries from its recent-jobs limit so counts and
  engagement totals are complete;
- derive employer-facing visibility from the canonical public-eligibility
  service rather than status alone;
- add a compact attention set for expiring, awaiting-review, and blocked jobs,
  each linking to My Jobs;
- reuse one employer-context resolver and preserve `employer_id` across all
  portal navigation;
- standardize one status-label/guidance mapping across Dashboard, My Jobs,
  detail, confirmations, and communications;
- expose archived history or explicitly settle archive as intentionally hidden;
- display renewal lineage without merging metrics or job identities;
- make metric labels and definitions accessible and precise;
- add query-level tests for employers with more than 100 jobs and My Jobs with
  more than one page;
- keep all mutations routed through existing nonce-, membership-, and
  lifecycle-protected handlers.

## Prioritized recommendations

### P0 - Contract truth and operational correctness

1. **Define one public-eligibility presentation rule.** Never label a job Live
   solely because `status = published`; account for expiry, archive,
   suppression, and application integrity.
2. **Make Dashboard totals complete.** Use aggregate queries independent of the
   newest-100 recent-job collection or clearly label bounded data.
3. **Settle archive visibility.** Either provide an employer history view or
   explicitly document that archive is irreversible/hidden from employer
   management. Do not let Dashboard and My Jobs disagree.
4. **Expose attention conditions.** At minimum, make awaiting review, expiring
   soon, and blocked/non-public jobs understandable and actionable.
5. **Preserve employer context.** Dashboard, My Jobs, Post, Edit, and return
   paths must keep the validated selected employer for multi-employer users.

### P1 - Purpose and clarity

6. Establish Dashboard as summary/attention routing and My Jobs as the complete
   lifecycle/action surface; avoid adding full actions to both.
7. Standardize status vocabulary and lifecycle guidance across every employer
   surface and communication.
8. Rename metrics to Views, Saves, and Application instructions opened (or a
   precisely defined Interested) and state unique-user/all-time semantics.
9. Clarify **Created** versus **Published** dates and expose submitted/closed/
   expired dates only where they explain the next action.
10. Explain Renew, Duplicate, Close, and Archive consequences before mutation.
11. Route non-public Recent Jobs to their My Jobs/edit context rather than
    leaving titles inert.
12. Add My Jobs result totals and clarify that section counts are page-local if
    they remain page-local.

### P2 - Pilot-informed refinement

13. Evaluate whether Dashboard should show Saves/Interested only after pilot
    employers demonstrate they are actionable at summary level.
14. Add filtering/search for larger inventories only after the 20-row paging
    flow is exercised by pilot employers.
15. Consider richer time-bounded engagement reporting only after event quality,
    definitions, and employer comprehension are validated. Advanced analytics
    remain outside V1.

## Recommended acceptance scenarios

1. Single-employer member reaches Dashboard, My Jobs, Post, Edit, and returns
   without losing employer context.
2. Multi-employer member switches context and cannot view or mutate another
   employer outside active memberships.
3. Approved claimant sees the existing employer and pre-existing jobs without
   changed IDs, URLs, status history, provenance, or metrics.
4. Draft, submitted, public live, published-but-blocked, expiring, expired,
   closed, and archived jobs show truthful distinct guidance.
5. Every My Jobs state exposes only its valid actions and rejects invalid,
   cross-employer, expired, or archived mutations server-side.
6. Dashboard counts match authoritative employer totals with more than 100 jobs.
7. My Jobs filters and pagination return complete, non-overlapping inventory;
   group and result counts are accurately scoped.
8. Views, Saves, and Application-instruction opens match unique engagement rows
   and never imply applications or unique people across all employer jobs.
9. Renewed draft and source job remain distinct, retain lineage, and do not
   merge engagement counts.
10. Archived records follow the explicitly approved employer retention policy.

## Verification record

- Documentation-only scope: this audit file only.
- Jobs plugin application code: unchanged by UA003.
- Runtime mutation: not performed.
- Final repository and whitespace verification are reported with the commit.
