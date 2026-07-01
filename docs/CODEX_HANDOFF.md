# Teachers.Net Codex Handoff

Teachers.Net is a WordPress/DDEV project in WSL with custom product plugins.

Core Terms is the reusable classification dependency. The repo/folder is still named `profilaxes`, but the visible product name is Core Terms.

Teachers.Net Jobs is the active job board plugin at `wordpress/wp-content/plugins/tnet-jobs`.

Primary rule:
Terms classify. Jobs authorizes. WordPress authenticates.

## Environment

- Project root: `/home/bobreap/projects/teachers-net-site`
- Local URL: `https://teachers-net.ddev.site`
- Docroot: `wordpress`
- Webserver: `apache-fpm`
- PHP: `8.4`
- DB: `MariaDB 11.8`

## Core Terms

- Plugin path: `wordpress/wp-content/plugins/profilaxes`
- Visible product name: Core Terms
- Minimum Jobs dependency version: `0.6.0`
- Owns term hierarchy, stable IDs, APIs, hooks, compilation, and Labs diagnostics.
- Jobs must treat Core Terms as read-only unless a ticket explicitly says otherwise.
- Do not rename `profilaxes`, CFM classes, prefixes, DB tables, slugs, URLs, or namespaces.

## Jobs

- Plugin path: `wordpress/wp-content/plugins/tnet-jobs`
- Remote: `git@github.com:BobSanDiego/tnet-jobs.git`
- Code version constant: `0.1.0`
- DB schema target: `0.7.0`
- Latest implementation tag: `v0.9.156-admin-moderation-markup-a11y`
- Latest Jobs repo commit at handoff refresh: `98d7fa8 J115 canonicalize Jobs hero asset`

## Current Jobs Capabilities

- Jobs-owned frontend application shell.
- Public browse landing, search results, and job detail pages.
- Design System v1 header, hero, results shell, and footer direction.
- Saved Jobs behavior on browse/search/detail and `/jobs/saved/`.
- Employer request-access workflow.
- Employer dashboard, My Jobs, create wizard, and single-screen edit form.
- Core Terms-backed grade, subject, and location classification.
- Salary and V1 location/requirements fields.
- Admin jobs, employers, memberships, moderation, bulk moderation, activity log, dashboard metrics, and CSV import foundation/create flow.
- Communications foundation, admin queue emails, recruiter lifecycle emails, expiration/renewal emails.
- Runtime hero asset: `public/assets/images/hero-chalkboard-1200x450.webp`.

## Current Active Work

Next safe implementation sequence:

1. J117 - Job Alerts Schema + Service Foundation
2. J118 - Public `/jobs/alerts/` Manage Page
3. J119 - Create Alert From Current Search Context
4. J120 - Job Alert Matching Service
5. J121 - Daily Job Alert Email Delivery
6. J122 - Alert Email Compliance / Unsubscribe Hardening

Current launch blocker:
Job Alerts are not implemented.

## Boundaries

- Do not add Jobs code to Core Terms.
- Do not create duplicate saved-job, alert, communication, moderation, or import systems.
- Do not add radius/proximity/geocoding unless explicitly requested.
- Do not add ATS, resumes, internal applications, messaging, subscriptions, or notification center behavior for V1 unless explicitly requested.
- Do not change lifecycle, moderation, trust, or schema outside a named ticket.
