# Teachers.Net Jobs — Plugin Skeleton Plan v0.1

Status: Planning Baseline
Purpose: Define the future `tnet-jobs` plugin skeleton before creating plugin files.

This is a planning document only. It is not plugin code, not SQL, and not a migration.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Scope

This plan covers the first safe implementation shape for the future Jobs plugin.

It does not implement:

- plugin files
- database tables
- migrations
- SQL
- WordPress state changes
- ATS
- resumes
- candidate profiles
- candidate search
- billing
- promotion purchase

---

# Accepted Carry-Forward Decisions

- Jobs is a separate plugin from Core Terms.
- Future plugin folder: `wordpress/wp-content/plugins/tnet-jobs`.
- Core Terms remains `wordpress/wp-content/plugins/profilaxes`.
- Core Terms visible product name is Core Terms.
- Jobs uses custom tables.
- No CPT-first architecture.
- Employer is first-class.
- Jobs owns authorization.
- WordPress owns authentication.
- Core Terms owns classification.
- Jobs consumes Core Terms through an adapter boundary.
- Jobs does not write directly into Core Terms internals.
- Signals table name: `tnet_jobs_signals`.
- No ATS/resume/candidate-search implementation in MVP skeleton.

---

# Proposed Plugin Folder Structure

Future folder:

`wordpress/wp-content/plugins/tnet-jobs`

Proposed structure:

```text
tnet-jobs/
  tnet-jobs.php
  includes/
    class-tnet-jobs.php
    class-tnet-jobs-activator.php
    class-tnet-jobs-deactivator.php
    class-tnet-jobs-dependencies.php
    class-tnet-jobs-schema.php
    class-tnet-jobs-capabilities.php
    class-tnet-jobs-core-terms-adapter.php
    services/
      class-tnet-jobs-job-service.php
      class-tnet-jobs-employer-service.php
      class-tnet-jobs-employer-user-service.php
      class-tnet-jobs-term-service.php
      class-tnet-jobs-event-service.php
      class-tnet-jobs-signal-service.php
      class-tnet-jobs-authz-service.php
      class-tnet-jobs-publication-validator.php
    repositories/
      class-tnet-jobs-job-repository.php
      class-tnet-jobs-employer-repository.php
      class-tnet-jobs-employer-user-repository.php
      class-tnet-jobs-term-repository.php
      class-tnet-jobs-event-repository.php
      class-tnet-jobs-signal-repository.php
  admin/
    class-tnet-jobs-admin.php
    class-tnet-jobs-admin-menus.php
    class-tnet-jobs-admin-actions.php
    views/
      dashboard.php
      jobs-list.php
      job-edit.php
      moderation-queue.php
      employers-list.php
      employer-edit.php
      settings.php
  public/
    class-tnet-jobs-public.php
    class-tnet-jobs-public-routes.php
    templates/
      jobs-list.php
      job-detail.php
  assets/
    admin/
      css/
      js/
    public/
      css/
      js/
  docs/
    README.md
```

Notes:

- Folder structure may be reduced during first implementation if a smaller skeleton is cleaner.
- Do not create public templates before route strategy is accepted.
- Do not create assets until there is UI that needs them.
- Keep class names and prefixes distinct from Core Terms `CFM_*` classes.

---

# Main Plugin Bootstrap Responsibilities

File:
`tnet-jobs.php`

Responsibilities:

- define plugin metadata
- define plugin constants
- block direct access
- load required class files
- register activation hook
- register deactivation hook
- initialize the plugin after WordPress is ready
- check Core Terms dependency before enabling full behavior
- initialize admin behavior only in admin context
- initialize public behavior only when public routes/screens exist

Suggested constants:

- `TNET_JOBS_VERSION`
- `TNET_JOBS_PLUGIN_FILE`
- `TNET_JOBS_PLUGIN_DIR`
- `TNET_JOBS_PLUGIN_URL`
- `TNET_JOBS_DB_VERSION`

Bootstrap should not:

- create tables directly outside activation/upgrade flow
- perform business writes on every request
- call Core Terms internals directly
- register large UI surfaces before services exist

---

# Activation Responsibilities

Activation should:

- check minimum PHP/WordPress assumptions if needed
- check whether Core Terms is available or warn/soft-block
- register Jobs capabilities if accepted
- install or upgrade Jobs schema through schema installer
- store Jobs plugin/schema version options
- flush rewrite rules only if public routes are actually registered

Activation should not:

- delete existing Jobs data
- uninstall or modify Core Terms
- create sample jobs
- seed production business data
- create ATS/resume/candidate tables
- install commerce/billing tables

Dependency behavior:

- If Core Terms is missing, activation may fail gracefully or activate in a disabled/admin-notice state.
- Exact behavior remains open before implementation.

---

# Deactivation Responsibilities

Deactivation should:

- stop runtime behavior
- unschedule Jobs-owned cron tasks if any exist
- flush rewrite rules only if routes were registered

Deactivation should not:

- delete tables
- delete options
- delete jobs
- delete employers
- delete Core Terms assignments
- alter Core Terms
- uninstall plugin files

Uninstall behavior:
Do not implement uninstall deletion until an explicit data retention policy exists.

---

# Core Terms Dependency Check

Purpose:
Ensure Jobs can safely consume classification without coupling to Core Terms internals.

Dependency checker should verify:

- Core Terms plugin is active or available
- expected public APIs/classes/hooks are present
- minimum compatible Core Terms version is satisfied if a version contract is accepted

Current known Core Terms version:
`0.6.0`

Boundary rules:

- Jobs uses a Core Terms adapter.
- Jobs does not write directly into Core Terms tables.
- Jobs does not rename or modify Core Terms classes, prefixes, tables, URLs, slugs, or namespaces.
- Jobs does not use Core Terms for permissions.

Open implementation decision:
Exact Core Terms API symbols must be discovered from the Core Terms plugin before coding the adapter.

---

# Table Installer / Migration Plan

No SQL in this plan.

Installer responsibilities:

- own Jobs schema versioning
- create missing Jobs tables on activation
- apply additive migrations on version changes
- avoid destructive migrations
- preserve identifiers
- preserve archived business records

MVP logical tables:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_events`
- `tnet_jobs_signals`

Reserved future tables:

- applications
- application events
- candidate profiles
- candidate documents
- job promotions
- billing/entitlements

Migration rules:

- no ATS/resume/candidate-search tables in skeleton
- no billing or entitlement tables in skeleton
- no direct Core Terms table changes
- schema changes should be versioned
- rollback should not imply data deletion

Open implementation decisions:

- exact physical table names with WordPress prefix
- exact column types
- exact indexes
- `dbDelta` versus explicit migration runner
- schema option name
- install failure behavior

---

# Service / Repository Class Map

## Services

`TNet_Jobs_Job_Service`

- job lifecycle
- create/update/submit/approve/reject/publish/close/expire/archive
- public active job search orchestration

`TNet_Jobs_Employer_Service`

- employer creation
- employer verification
- employer archive/deactivation
- employer lookup

`TNet_Jobs_Employer_User_Service`

- user-to-employer membership
- membership role changes
- membership status

`TNet_Jobs_Term_Service`

- job term assignment orchestration
- publication term validation
- Core Terms adapter calls

`TNet_Jobs_Event_Service`

- append lifecycle/business events
- retrieve job event history

`TNet_Jobs_Signal_Service`

- record apply clicks
- record job views if enabled
- future aggregation entrypoint

`TNet_Jobs_Authz_Service`

- capability checks
- employer-scoped authorization
- admin/moderator checks

`TNet_Jobs_Publication_Validator`

- title/description/employment type/apply URL/expiration validation
- employer-before-publication validation
- Region/location term requirement
- role/category warning when axis is unavailable

## Repositories

`TNet_Jobs_Job_Repository`

- reads/writes `tnet_jobs`

`TNet_Jobs_Employer_Repository`

- reads/writes `tnet_jobs_employers`

`TNet_Jobs_Employer_User_Repository`

- reads/writes `tnet_jobs_employer_users`

`TNet_Jobs_Term_Repository`

- reads/writes `tnet_jobs_terms`

`TNet_Jobs_Event_Repository`

- reads/writes `tnet_jobs_events`

`TNet_Jobs_Signal_Repository`

- reads/writes `tnet_jobs_signals`

Rule:
Repositories should not own business policy. Services own policy.

---

# Admin Menu Plan

Top-level menu:
Teachers.Net Jobs or Jobs

Recommended MVP admin pages:

- Dashboard
- Jobs
- Moderation Queue
- Employers
- Settings

Possible submenu layout:

```text
Jobs
  Dashboard
  All Jobs
  Moderation
  Employers
  Settings
```

Admin menu should require Jobs/admin capabilities.

Admin menu should not expose:

- applications
- resumes
- candidate search
- billing
- promotions
- ATS workflows

---

# MVP Screen List

## Admin Screens

Dashboard:

- basic status
- dependency health
- counts if cheap to compute

All Jobs:

- list jobs
- filter by status
- access edit/review actions

Moderation Queue:

- submitted jobs
- approve
- reject
- publish

Job Edit:

- title
- description
- employer
- employment type
- apply URL
- expiration
- Core Terms assignments
- lifecycle status

Employers:

- list employers
- verification status
- archive/deactivate

Employer Edit:

- employer identity
- status
- membership overview

Settings:

- dependency status
- schema version
- diagnostic-only configuration at first

## Recruiter Screens

MVP may initially use admin screens for authenticated recruiters if capability-scoped correctly.

Required recruiter workflow:

- create draft
- edit draft
- submit
- edit published job
- close job
- view own/employer jobs

## Public Screens

Public Job List:

- browse active jobs
- search/filter
- filter by Region/location
- filter by available Core Terms axes

Public Job Detail:

- title
- employer
- description
- terms
- employment type
- expiration/expired state
- external apply CTA

Public Apply Redirect:

- record apply-click signal
- redirect to external URL

---

# Route / Admin-Action Strategy Recommendation

Recommendation:
Start with classic WordPress admin pages and admin-post/admin action handlers for MVP management workflows. Use public query vars/rewrite routes or shortcodes/templates for public job browsing, depending on the future theme plan.

Reasoning:

- MVP admin workflows are form-heavy and moderation-heavy.
- Classic admin screens fit WordPress authentication and capabilities.
- Services can remain independent of route strategy.
- REST routes can be added later without changing core services.

Admin write strategy:

- nonce-protected form submissions
- capability checks through Jobs authorization service
- service calls for all writes
- redirects with admin notices

Public read strategy:

- cache-friendly read endpoints/templates
- no authentication required for browsing/detail
- direct expired job detail can render read-only state

Signal write strategy:

- apply-click endpoint/action records signal
- redirect to external URL
- no application record created

Open decision:
Whether public browsing begins as a shortcode, custom rewrite route, block, or theme template remains open.

---

# First Implementation Milestone Sequence

## Milestone 1 — Empty Skeleton

- create plugin folder
- create main plugin file
- define constants
- load minimal core class
- add admin notice if Core Terms missing
- no tables yet unless activation plan is accepted

## Milestone 2 — Dependency And Health

- implement dependency checker
- implement Core Terms adapter shell
- show admin diagnostic/health page
- verify Core Terms `0.6.0` compatibility assumptions

## Milestone 3 — Schema Installer

- implement schema version option
- implement table installer for accepted MVP tables
- no future/reserved tables
- verify activation idempotency

## Milestone 4 — Repositories

- implement repositories for MVP tables
- keep writes thin
- no UI-heavy business logic in repositories

## Milestone 5 — Services

- implement job lifecycle service
- implement employer service
- implement membership service
- implement term assignment service
- implement event service
- implement signal service
- implement authorization and validation services

## Milestone 6 — Admin MVP

- admin menu
- jobs list
- job edit
- moderation queue
- employer list/edit

## Milestone 7 — Public MVP

- public browse/search/filter
- public detail
- external apply redirect
- signal recording

## Milestone 8 — Hardening

- permission review
- validation review
- lint
- smoke tests
- DDEV/WordPress activation checks

---

# Test / Lint Commands

Known safe commands:

```bash
ddev wp plugin list --path=wordpress
ddev wp core version --path=wordpress
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/tnet-jobs.php
```

Future lint examples after files exist:

```bash
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs.php
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs-schema.php
```

Useful project checks:

```bash
ddev describe
git -C /home/bobreap/projects/teachers-net-site status --short
git -C /home/bobreap/projects/teachers-net-site diff
```

Do not run destructive DDEV, Docker, WordPress, or plugin commands without explicit approval.

---

# Rollback / Reset Cautions

Do not run unless explicitly instructed:

- `ddev delete`
- `ddev restart --stop-ssh-agent`
- Docker prune/reset commands
- WordPress plugin uninstall for Core Terms
- WordPress plugin uninstall for Jobs after data exists
- database drop/import/reset commands
- destructive migration rollback
- hard-delete job/employer data

Known caution:
Prior testing showed WordPress plugin uninstall can remove plugin files while leaving database tables. Avoid uninstall as a casual reset tool.

Rollback principle:

- deactivation should stop behavior, not delete data
- schema rollback should be planned, not improvised
- migration failures should preserve existing data
- archived business records should remain intact

---

# Open J5 Decisions

Before creating plugin files, decide:

- exact plugin display name
- exact plugin version for initial skeleton
- minimum Core Terms version requirement
- activation behavior if Core Terms is missing
- whether schema installer runs in first skeleton milestone or waits for a second milestone
- admin menu label
- public route strategy: shortcode, rewrite route, block, or theme template
- REST route timing
- exact class prefix convention
- whether to include a `docs/` folder inside the plugin

---

# J5 Acceptance Criteria

J5 planning is ready for implementation when:

- plugin folder structure is accepted
- bootstrap responsibilities are accepted
- activation/deactivation boundaries are accepted
- Core Terms dependency behavior is accepted
- no-delete rollback rules are accepted
- first implementation milestone sequence is accepted
- open J5 decisions are resolved or explicitly deferred

After J5 planning, the next safe task is creating the minimal `tnet-jobs` plugin skeleton.
