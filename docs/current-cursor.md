# Current Cursor

Current project phase:
Teachers.Net Jobs V1 launch completion.

## Active Ticket

J116 - Job Alerts MVP Audit and Implementation Plan is complete.

Next implementation ticket:
J117 - Job Alerts Schema + Service Foundation.

## Last Completed Ticket

J115A/B - Governance Docs + Hero Asset Canonicalization.

Jobs repo latest commit at cursor refresh:
`98d7fa8 J115 canonicalize Jobs hero asset`

Latest implementation tag:
`v0.9.156-admin-moderation-markup-a11y`

## Versions

Jobs plugin code version:
`0.1.0`

Jobs DB schema target:
`0.7.0`

Core Terms minimum dependency:
`0.6.0`

## Core Terms

- Plugin folder/repo remains `profilaxes`.
- Visible product name is Core Terms.
- Owns term hierarchy, assignment, compilation, stable IDs, APIs, hooks, and Labs diagnostics.
- Does not own jobs, resumes, billing, ATS, recruiter permissions, alerts, or candidate search.
- Jobs consumes Core Terms through the adapter and Jobs-owned mappings.
- Core Terms counts may legitimately change after taxonomy maintenance; do not assume an old fixed count.

## Jobs Current State

Jobs owns:

- job schema and lifecycle
- employer and employer-membership authorization
- public browse/search/detail routes
- saved jobs engagement
- application-instructions reveal behavior
- recruiter dashboard, My Jobs, create wizard, and edit form
- admin moderation and operational admin pages
- communications service and email templates
- CSV import foundation/create flow
- Design System v1 public page presentation

Current important tables:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_form_fields`
- `tnet_jobs_form_field_terms`
- `tnet_jobs_engagements`
- `tnet_jobs_events`
- `tnet_jobs_signals`

## Remaining Ordered Backlog

1. J117 - Job Alerts Schema + Service Foundation
2. J118 - Public `/jobs/alerts/` Manage Page
3. J119 - Create Alert From Current Search Context
4. J120 - Job Alert Matching Service
5. J121 - Daily Job Alert Email Delivery
6. J122 - Alert Email Compliance / Unsubscribe Hardening
7. Responsive/mobile QA pass
8. Accessibility pass
9. Performance and smoke-test pass
10. Launch readiness re-audit

## Known Blockers

- Job Alerts are not implemented.
- Alert email delivery is not wired.
- Salary/location filtering is intentionally incomplete.
- Radius/proximity/geocoding is not implemented.

## Deferred To V2 Unless Explicitly Reopened

- radius/proximity search
- geocoding automation
- maps
- advanced salary filtering
- notification center and bell alerts
- internal applications / ATS
- resume database and candidate search
- employer reviews, school reviews, and district profiles
- paid plans, subscriptions, and promotion commerce
- JSON/feed/API/scraper import automation beyond CSV foundation

## Architectural Files

- `AGENTS.md`
- `docs/CODEX_HANDOFF.md`
- `docs/current-cursor.md`
- `docs/plugin-architecture.md`
- `docs/decision-log.md`
- `docs/v1-product-definition.md`
- `docs/design-system-v1.md`
- `docs/codex-ticket-discipline.md`
- `wordpress/wp-content/plugins/tnet-jobs/tnet-jobs.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs-schema.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/integrations/`
- `wordpress/wp-content/plugins/tnet-jobs/includes/repositories/`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/`
- `wordpress/wp-content/plugins/tnet-jobs/admin/`
- `wordpress/wp-content/plugins/tnet-jobs/public/class-tnet-jobs-public.php`
- `wordpress/wp-content/plugins/tnet-jobs/templates/emails/`

## Current Decisions

- Jobs uses custom tables, not WordPress posts, as primary storage.
- Employer is first-class and not a Core Term.
- Classification attaches through Core Terms and Jobs-owned bridge/configuration tables.
- Jobs must not write to Core Terms.
- Saved Jobs use the Jobs engagement system.
- Job Alerts should be implemented as a small user-owned alerts system, not as a general notification center.
- Communications must use the Jobs communication service.
- The canonical public hero runtime asset is `hero-chalkboard-1200x450.webp`.
