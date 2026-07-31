# Community 3.0 Notification Implementation Gap Analysis v1

Status: planning-only companion to the C3-IMP001 readiness audit.

## Gap register

| ID | Gap | Evidence/status | Safe disposition |
|---|---|---|---|
| G-01 | Mention Notification Contract | Required input is not present as a local canonical contract. | Resolve before mention implementation; do not infer. |
| G-02 | Community event producer | No verified local Community producer was identified. | Use synthetic fixture for C3-IMP002. |
| G-03 | Event envelope and mapping | No verified runtime envelope carrying both IDs and visibility. | Define test interface only; preserve `path_id != group_id`. |
| G-04 | Recipient policy adapter | Subscriber policy exists as documentation, not a verified adapter. | Implement pure test seam later. |
| G-05 | Suppression adapter | Suppression contract exists as documentation, not a verified runtime. | Use explicit synthetic states in dry run. |
| G-06 | Bell persistence/read API | No Community bell runtime established. | Defer persistence and UI. |
| G-07 | Email/digest/queue | No Community delivery authority established. | Defer; keep transport disabled. |
| G-08 | Audit store | Contract requires audit, but no Community audit store was verified. | Emit redacted test evidence only. |
| G-09 | Legacy reconciliation | WordPress/BuddyPress/mail capabilities are legacy or platform evidence. | Do not reuse as authority without a separate audit. |
| G-10 | Product boundary | Job Center has its own alert/email code. | Keep isolated; no absorption. |

## Existing reusable patterns

WordPress authentication and sanitization conventions, explicit service/result
objects in `tnet-jobs`, and repository test/verification conventions may inform
interfaces. They do not establish Community notification semantics. Core Terms
remains classification authority, Jobs remains authorization authority for Jobs,
and WordPress remains authentication authority.

## Not gaps to solve in C3-IMP002

Persistent tables, migrations, asynchronous queues, provider integration,
email templates, bell UI, digest scheduling, production data, and legacy
replacement are deliberately deferred. Treating them as prerequisites for the
dry-run proof would expand the approved slice.
