# Teachers.Net Jobs Roadmap

## Current Cursor

Teachers.Net Jobs is in V1 release-candidate readiness.

The V1 feature implementation path is complete through Job Alerts MVP and browser/accessibility regression.

Current focus:

1. Keep documentation synchronized with implementation.
2. Complete human visual QA.
3. Declare a release candidate if approved by the Engineering Director.
4. Prepare launch operations and production deployment.

## Current Version State

- Jobs code version: `0.1.0`
- Jobs DB schema target: `0.8.0`
- Latest implementation tag: `v0.9.164-job-alert-browser-a11y`
- Latest Jobs repo commit at roadmap refresh: `75dadf5 J126 fix alerts header tablet overflow`

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
