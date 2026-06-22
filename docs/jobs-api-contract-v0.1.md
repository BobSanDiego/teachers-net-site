# Teachers.Net Jobs — API Contract v0.1

Status: Working Baseline
Purpose: Define plugin boundaries, service contracts, Core Terms consumption points, and extension rules before implementation.

This is a planning document only. It is not plugin code.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Scope

This document defines the intended API boundaries for the future `tnet-jobs` plugin.

It does not define:

- concrete PHP classes
- REST route implementations
- database migrations
- SQL files
- admin UI screens
- public templates
- Core Terms internals
- Commerce, ATS, or Profiles implementations

---

# Dependency Contract

## Core Terms Dependency

Jobs depends on Core Terms for classification.

Expected dependency:

- plugin folder: `wordpress/wp-content/plugins/profilaxes`
- visible product name: Core Terms
- current reconciled version: `0.6.0`

Jobs may require Core Terms to be active before Jobs can fully operate.

Dependency rules:

- Jobs must not rename Core Terms folders, classes, prefixes, tables, URLs, slugs, or namespaces.
- Jobs must not write directly to Core Terms tables.
- Jobs must not depend on undocumented Core Terms internals.
- Jobs should fail gracefully if Core Terms is unavailable.
- Jobs activation may warn or soft-block if required Core Terms APIs are missing.

## WordPress Dependency

WordPress owns:

- user identity
- login state
- sessions
- base capability system
- admin shell

Jobs owns:

- employer authorization
- job lifecycle
- employer membership state
- job classification assignments
- job events
- job signals

---

# Internal Jobs Service Boundaries

The future Jobs plugin should expose internal service boundaries before UI code grows around them.

Suggested service areas:

- Job service
- Employer service
- Employer membership service
- Job term assignment service
- Job event service
- Job signal service
- Authorization service
- Publication validation service
- Core Terms adapter

Service rule:
UI, REST routes, admin actions, and CLI commands should call Jobs services rather than writing tables directly.

Repository rule:
Repositories may own table access, but business decisions should live in services.

---

# Core Terms Adapter Contract

Jobs should isolate Core Terms usage behind a Jobs-owned adapter.

Purpose:

- validate term IDs
- discover available term axes
- identify Region/location terms
- identify role/category terms if that axis exists
- render assigned terms for public display
- support search/filter by term

Allowed operations:

- read Core Terms hierarchy through public APIs/hooks
- validate that referenced terms exist
- resolve term labels live for MVP
- detect whether required axes exist

Forbidden operations:

- direct writes to Core Terms tables
- duplicating Core Terms labels into `tnet_jobs`
- using Core Terms as a permission system
- assuming internal Core Terms table structure
- creating Jobs-specific terms during normal job writes

MVP term rules:

- at least one Region/location term is required before publication
- role/category term is required only if that axis exists in Core Terms
- if role/category axis is absent, warn but do not block publication
- term snapshots are reserved, not MVP

Open decision:
Exact Core Terms public API names should be discovered from the Core Terms plugin before implementation.

---

# Authorization Contract

Authentication:
WordPress.

Authorization:
Jobs.

Classification:
Core Terms.

Rules:

- Jobs authorization must be employer-scoped where employer context exists.
- Users may belong to multiple employers.
- Users may hold multiple Jobs identities.
- Avoid single ranked role ladders.
- Do not use Core Terms to grant or deny Jobs permissions.
- Do not use billing or promotion state as core Jobs authorization.

Suggested capability concepts:

- `tnet_jobs_create_job`
- `tnet_jobs_edit_own_job`
- `tnet_jobs_edit_employer_job`
- `tnet_jobs_submit_job`
- `tnet_jobs_close_job`
- `tnet_jobs_manage_jobs`
- `tnet_jobs_manage_employers`
- `tnet_jobs_moderate_jobs`

Suggested employer membership roles:

- `owner`
- `admin`
- `poster`
- `viewer` reserved

MVP trust rule:
No automatic trust elevation.

Reserved future trust inputs:

- repeated approved postings
- employer verification
- manual admin review
- organization/domain validation

---

# Job Service Contract

Purpose:
Own job lifecycle and job content operations.

Expected operations:

- create draft
- update draft
- submit for review
- approve
- reject
- publish
- edit published job
- close
- expire
- archive
- fetch by ID
- fetch by slug
- search active jobs
- list jobs for employer
- list jobs for current user context

Write rules:

- lifecycle changes write `tnet_jobs`
- lifecycle changes append `tnet_jobs_events`
- published edits append `tnet_jobs_events`
- apply clicks do not increment counters on `tnet_jobs`
- job deletion means archive, not hard-delete

Validation rules before publication:

- title required
- description required
- employment type required
- apply method required
- apply URL required for `external_url`
- expiration required
- employer required before publication
- at least one Region/location term required
- role/category term required only if that axis exists in Core Terms

Read rules:

- active public search excludes closed, expired, and archived jobs
- direct detail may render expired jobs as read-only
- archived jobs are not public by default

---

# Employer Service Contract

Purpose:
Own employer identity and employer lifecycle.

Expected operations:

- create employer
- request employer
- update employer
- verify employer
- deactivate employer
- archive employer
- fetch employer by ID
- fetch employer by slug if implemented
- list employers for user
- list employers for admin review

Write rules:

- new poster-created employers begin unverified
- employer archive blocks future publication
- employer archive does not erase historical jobs

Forbidden:

- storing billing plans on employer records
- storing recruiter trust score as MVP state
- treating employer as a Core Term

---

# Employer Membership Service Contract

Purpose:
Own user-to-employer relationships.

Expected operations:

- add membership
- change membership role
- deactivate membership
- list employer users
- list user employers
- check user permission for employer-scoped action

Rules:

- one user may have many employer memberships
- one employer may have many memberships
- membership removal does not delete jobs
- membership removal does not delete events
- first membership may be created when a user creates or requests an employer

Deferred:

- invitations
- ownership transfer
- domain verification
- trust automation

---

# Job Term Assignment Service Contract

Purpose:
Own job-to-Core-Term assignment records.

Expected operations:

- assign terms to job
- replace job term assignments
- list terms for job
- validate publication term requirements
- search jobs by term

Rules:

- term assignment writes belong to Jobs
- term identity belongs to Core Terms
- rendered labels come from Core Terms live for MVP
- term edits on published jobs should append a job event

Open decision:
Whether assignment replacement physically deletes old rows or archives them row-by-row remains open. Events must preserve meaningful edit history either way.

---

# Job Event Service Contract

Purpose:
Append meaningful job lifecycle and business events.

Expected operations:

- append event
- list events for job
- list moderation events
- aggregate event counts later

Expected MVP event types:

- `created`
- `submitted`
- `approved`
- `rejected`
- `published`
- `edited`
- `closed`
- `expired`
- `archived`
- `apply_clicked`
- `viewed` if implemented as event-backed signal

Rules:

- append-oriented
- no hard-delete during normal operation
- no MVP expiration rule
- avoid sensitive personal data in event metadata
- do not use event metadata as a hidden business table

---

# Job Signal Service Contract

Purpose:
Record lightweight job interaction signals separately from canonical job state.

Preferred table:
`tnet_jobs_signals`

Expected operations:

- record job view if enabled
- record apply click
- aggregate signal counts later

Rules:

- do not increment counters on `tnet_jobs`
- do not create application records in MVP
- keep anonymous tracking minimal
- avoid candidate funnel modeling in MVP

MVP signal types:

- `viewed` optional
- `apply_clicked`

Open decision:
Whether views are implemented in MVP or only apply clicks remains open.

---

# Public Read API Contract

Candidate-facing reads should support:

- browse active jobs
- search active jobs
- filter by Region/location term
- filter by additional available Core Terms axes
- filter by employment type
- view job detail by slug
- render expired job detail as read-only when directly accessed

Public reads must not expose:

- archived jobs by default
- employer membership state
- moderation internals
- event metadata intended for admins
- billing, entitlement, or promotion state
- candidate profile data

---

# Recruiter API Contract

Recruiter-facing actions should support:

- create draft
- edit draft
- attach employer
- assign Core Terms
- submit for review
- edit published job
- close job
- view own/employer jobs

Recruiter-facing actions should not support in MVP:

- internal application review
- candidate search
- resume access
- paid promotion purchase
- trusted autopublish
- billing changes

---

# Admin API Contract

Admin/moderation actions should support:

- list submitted jobs
- approve job
- reject job
- publish job
- expire job
- close job
- archive job
- verify employer
- manage employer memberships

Admin actions should append events where they affect job lifecycle or moderation state.

Admin should avoid direct manual edits of business data except moderation and cleanup workflows.

---

# REST And Route Planning

Concrete REST routes are deferred until implementation planning.

Possible route groups:

- public job search
- public job detail
- recruiter job management
- employer management
- admin moderation
- signal capture

Route rules:

- public reads must be cache-friendly where possible
- write routes must require WordPress authentication
- employer-scoped writes must pass Jobs authorization
- term validation must pass through the Core Terms adapter
- route handlers should call services, not repositories directly

Open decision:
Whether MVP uses WordPress admin-post/admin-ajax, REST API routes, classic admin forms, or a hybrid remains open.

---

# Hooks And Extension Events

Jobs should expose extension points after core behavior is stable.

Potential internal hooks/events:

- job created
- job submitted
- job approved
- job rejected
- job published
- job edited
- job closed
- job expired
- job archived
- employer created
- employer verified
- membership changed
- job terms changed
- apply clicked

Rules:

- hooks should fire after successful service operations
- hooks should receive stable IDs and minimal context
- hooks should not require consumers to know table internals
- future Commerce and Profiles should attach through APIs/events rather than direct table mutation

---

# Error And Validation Contract

Errors should be structured enough for admin UI, recruiter UI, and future REST responses.

Suggested error categories:

- authentication required
- authorization denied
- employer required
- employer inactive
- invalid lifecycle transition
- missing required field
- invalid apply URL
- expiration required
- missing required Region/location term
- missing role/category term warning
- Core Terms unavailable
- Core Terms axis unavailable
- Core Terms term invalid

Warning rule:
Missing role/category term is a warning, not a blocker, when that axis does not exist in Core Terms.

---

# Data Privacy And Safety Contract

MVP must avoid collecting data it does not need.

Do not collect/store in MVP:

- resumes
- candidate profile data
- application records
- raw IP addresses unless explicitly approved later
- broad user-agent logs
- candidate search behavior
- recruiter billing/payment data

Signals should remain lightweight and aggregatable.

---

# Open API Decisions

Before implementation, decide:

- exact Core Terms public APIs to call
- exact service class names
- exact repository class names
- REST versus admin form route strategy
- physical route namespace if REST is used
- error object format
- whether views are captured in MVP or only apply clicks
- whether published term assignment replacement archives rows or replaces current state
- whether `job_slug` is globally unique or employer-scoped
- whether employer slugs are MVP
- exact capability names
- exact lifecycle transition matrix

---

# J4 Acceptance Criteria

J4 is ready to support plugin skeleton planning when:

- Core Terms adapter boundary is accepted
- direct Core Terms table writes are rejected
- Jobs service boundaries are accepted
- authorization ownership is accepted
- public/recruiter/admin API surfaces are accepted at a conceptual level
- event and signal write rules are accepted
- open route/class naming decisions are carried forward

After J4, the next safe task is J5 plugin skeleton planning and implementation.
