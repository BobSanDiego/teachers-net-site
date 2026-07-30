# Plugin Architecture

## Core Terms / Profilaxes

- Folder: `wordpress/wp-content/plugins/profilaxes`
- Visible product name: Core Terms.
- Owns reusable classification infrastructure.
- Owns term tree, assignment, compiled relationships, stable IDs, APIs, hooks, and Labs diagnostics.
- Does not know Jobs exists.
- Must not contain Jobs authorization, job lifecycle, employer, alert, apply, billing, resume, or candidate-search logic.

## Teachers.Net Jobs

- Folder: `wordpress/wp-content/plugins/tnet-jobs`
- Owns jobs, employers, employer memberships, job lifecycle, application-instructions reveal behavior, saved jobs, job alerts, metrics, visibility, recruiter workflows, admin workflows, communications, and import workflows.
- Depends on Core Terms for classification.
- Must not write directly into Core Terms internals.
- Uses custom tables as primary storage.
- Uses repositories for persistence and query work.
- Uses services for business rules.
- Uses public/admin classes for routing, rendering, form handling, and action wiring.

## Jobs Internal Boundaries

Repositories:
- read/write Jobs-owned tables
- receive sanitized values from services/admin/public handlers
- do not decide lifecycle or authorization rules

Services:
- own lifecycle, validation orchestration, authorization-adjacent business rules, communication dispatch, import processing, engagement behavior, and future alert matching
- should be reused by public and admin surfaces

Public/admin renderers:
- own route handling, forms, templates, nonces, capability checks, and presentation
- must not duplicate lifecycle rules when a service method exists

## Future Profile / Onboarding

- Owns teacher identity, resume/profile, preferences, availability, visibility, and profile completion.
- May consume Core Terms.
- May later consume Jobs alerts or saved-job signals through explicit service boundaries.

## Future Commerce

- Owns billing, plans, receipts, recruiter subscriptions, paid promotions, and resume-search entitlements.
- Must not contaminate the core Jobs posting/lifecycle tables.

## Boundary Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

## Security Rule

Do not use Core Terms as a permission system. Jobs permissions must use WordPress capabilities and Jobs-owned membership/entitlement state.
