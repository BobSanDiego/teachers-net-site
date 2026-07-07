# Teachers.Net Jobs Roadmap

## Roadmap Context

This roadmap is a durable planning reference, not the live project cursor.

For current Job Center state, read:

1. `docs/job-center/project-cursor.md`
2. `docs/job-center/engineering-handoff.md`

## Current Version State

- Jobs code version: `0.1.0`
- Jobs DB schema target: `0.8.0`
- Current implementation state is tracked in the Job Center Engineering
  Handoff and the Jobs plugin Git history.

## Completed V1 Areas

- Core Terms integration.
- Jobs schema and repository/service foundation.
- Employer authorization and request-access workflow.
- Recruiter dashboard, create wizard, edit form, and My Jobs management.
- Public browse, search results, saved jobs, job detail, and application instructions.
- Salary, location, address, and requirements/qualifications data support.
- Design System v1 public presentation shell.
- Admin jobs, employers, memberships, moderation, bulk moderation, metrics, activity log, and import tools.
- Communications service and admin/recruiter lifecycle emails.
- Job Alerts MVP:
  - create
  - manage
  - matching
  - scheduled daily delivery
  - email pause/manage path
- DDEV Playwright browser verification foundation.

## Remaining V1 Readiness Work

1. Human visual QA across public browse/search/detail, saved jobs, alerts, recruiter flows, and admin flows.
2. Release-candidate declaration ticket.
3. Launch checklist and deployment plan.
4. Production monitoring and rollback plan.
5. Post-launch issue triage process.

## V2 / Deferred

- Radius/proximity search.
- Geocoding automation.
- Maps.
- Salary matching and advanced salary filtering.
- Notification center and bell alerts.
- Per-job alert dedupe and match history.
- Advanced alert preferences and frequencies.
- ATS/internal applications.
- Resumes and candidate search.
- Reviews and district/school profiles.
- Paid plans, subscriptions, and promotion commerce.
- Automated JSON/feed/API/scraper import beyond the CSV foundation.
