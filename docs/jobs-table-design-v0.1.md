# Teachers.Net Jobs — Table Design v0.1

Status: Working Baseline
Purpose: Propose first-pass custom table responsibilities before implementation.

This is a planning document only. It is not SQL, not a migration, and not plugin code.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Naming And Storage Assumptions

Table names in this document use logical names:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_events`
- `tnet_jobs_signals`

Implementation may apply the WordPress table prefix later, for example `wp_tnet_jobs`.

Accepted storage direction:

- custom tables
- SQL-first MVP search
- additive migrations preferred
- archive business records instead of hard-delete
- no CPT-first or postmeta-first architecture

---

# Table: `tnet_jobs`

## Purpose

Stores one education-related opportunity.

This table is the canonical record for job identity, lifecycle state, core posting content, apply destination, and publication timing.

## Owner

Teachers.Net Jobs plugin.

## Lifecycle

`draft` -> `submitted` -> `approved` -> `published` -> `closed` -> `expired` -> `archived`

Rules:

- Draft jobs may exist without an employer.
- Published jobs should have an employer.
- Published jobs are editable.
- Published edits create events.
- Expired jobs may remain visible by direct URL as read-only records.
- Archived jobs are hidden from public default views.

## Required Columns

- `job_id`
- `job_slug`
- `status`
- `title`
- `description`
- `employment_type`
- `apply_method`
- `apply_url`
- `created_by_user_id`
- `created_at`
- `updated_at`
- `expires_at`

## Optional / Deferred Columns

Optional for MVP:

- `employer_id`
- `submitted_at`
- `approved_at`
- `published_at`
- `closed_at`
- `expired_at`
- `archived_at`
- `moderated_by_user_id`
- `summary`

Summary rule:
`description` is the canonical long-form job content. `summary` should not be required for MVP unless it is admin-entered or clearly derived.

Deferred, possible later:

- `remote_type`
- `salary_min`
- `salary_max`
- `salary_currency`
- `salary_period`
- `start_date`
- `apply_email`

## Indexes

Recommended MVP indexes:

- primary key on `job_id`
- unique index on `job_slug`
- index on `status`
- index on `employer_id`
- index on `created_by_user_id`
- index on `employment_type`
- index on `published_at`
- index on `expires_at`
- compound index on `status`, `published_at`
- compound index on `status`, `expires_at`
- fulltext or SQL keyword strategy for `title` and `description`, depending on MariaDB support and implementation constraints

## Cardinality

- one employer may have many jobs
- one job belongs to zero or one employer while drafting
- one published job should belong to one employer
- one job has many term assignments
- one job has many events
- one job has many views/signals

## Write Paths

MVP writes:

- recruiter creates draft
- recruiter edits draft
- recruiter submits job
- admin approves/rejects
- system or admin publishes
- recruiter/admin closes
- system or admin expires
- admin archives

Rules:

- lifecycle writes should also write `tnet_jobs_events`
- published edits should also write `tnet_jobs_events`
- apply clicks should not update counters on this table

## Read Paths

MVP reads:

- public active job search
- public job detail
- recruiter job list
- recruiter job edit screen
- admin moderation queue
- admin job list

Read filters:

- active public search excludes `closed`, `expired`, and `archived`
- direct detail routes may render `expired` read-only records
- archived jobs are admin/recruiter history only by default

## Archive / Delete Rules

- soft archive only
- do not hard-delete during normal operation
- preserve historical employer association
- preserve events and term assignment history unless a future retention policy says otherwise

## Migration Risk

Medium.

Risks:

- making `employer_id` non-null too early
- storing term labels here
- adding promotion, billing, analytics, or application counters here
- overloading `status` with visibility or commerce state
- choosing a keyword search strategy too early

## Deferred Fields Explicitly Excluded

Do not store on `tnet_jobs`:

- `candidate_count`
- `resume_count`
- `application_count`
- `billing_state`
- `promotion_state`
- `visibility_state`
- `featured`
- `boosted`
- `view_count`
- `apply_click_count`
- `term_labels`
- `candidate_profile_id`
- `recruiter_plan_id`
- `commerce_entitlement_id`
- ATS state
- interview state
- offer state
- hire state

---

# Table: `tnet_jobs_employers`

## Purpose

Stores first-class employer/posting organization identity.

Examples:

- school
- district
- agency
- recruiter organization

## Owner

Teachers.Net Jobs plugin.

## Lifecycle

`created` -> `verified` -> `active` -> `inactive` -> `archived`

MVP may use a simpler status set while preserving future verification.

## Required Columns

- `employer_id`
- `name`
- `status`
- `created_by_user_id`
- `created_at`
- `updated_at`

## Optional / Deferred Columns

Optional for MVP:

- `employer_slug`
- `website_url`
- `description`
- `verification_status`
- `verified_at`
- `verified_by_user_id`
- `archived_at`

Deferred:

- `logo_media_id`
- `primary_domain`
- `parent_employer_id`
- `employer_type`
- `address_line_1`
- `address_line_2`
- `city`
- `state_region`
- `postal_code`
- `country`

## Indexes

Recommended MVP indexes:

- primary key on `employer_id`
- unique index on `employer_slug` if implemented
- index on `status`
- index on `verification_status` if implemented
- index on `created_by_user_id`
- index on `name` for admin lookup

## Cardinality

- one employer has many jobs
- one employer has many employer memberships
- one employer may later have many commerce entitlements
- one employer may later belong to an employer hierarchy

## Write Paths

MVP writes:

- authenticated poster creates or requests employer
- admin verifies employer
- admin edits employer
- admin archives employer
- future owner/admin edits employer profile

Rules:

- new employer records begin unverified unless admin-created or admin-verified
- employer archive should block new publication but preserve historical jobs

## Read Paths

MVP reads:

- job detail employer display
- job edit employer selection
- admin employer review
- recruiter employer dashboard context

## Archive / Delete Rules

- archive instead of hard-delete
- archived employers cannot publish new jobs
- historical jobs retain employer association
- memberships remain as history unless explicitly deactivated

## Migration Risk

Medium.

Risks:

- forcing school/district hierarchy too early
- confusing employer verification with recruiter trust
- storing billing owner fields here
- making employer required for draft jobs

## Deferred Fields Explicitly Excluded

Do not store:

- billing plan
- payment status
- promotion entitlement
- resume-search entitlement
- recruiter trust score
- candidate access grants
- school/district hierarchy beyond reserved parent linkage

---

# Table: `tnet_jobs_employer_users`

## Purpose

Stores employer-scoped membership between WordPress users and Jobs employers.

## Owner

Teachers.Net Jobs plugin.

WordPress owns the user account. Jobs owns the employer membership.

## Lifecycle

`active` -> `inactive` -> `removed` or `archived`

Possible invitation lifecycle is deferred.

## Required Columns

- `employer_user_id`
- `employer_id`
- `user_id`
- `membership_role`
- `status`
- `created_at`
- `updated_at`

## Optional / Deferred Columns

Optional for MVP:

- `created_by_user_id`
- `deactivated_at`
- `deactivated_by_user_id`

Deferred:

- `invited_email`
- `invitation_token_hash`
- `invited_at`
- `accepted_at`
- `last_verified_at`
- `verification_method`

## Indexes

Recommended MVP indexes:

- primary key on `employer_user_id`
- unique index on `employer_id`, `user_id`
- index on `user_id`
- index on `employer_id`
- index on `membership_role`
- index on `status`

## Cardinality

- one WordPress user may have many employer memberships
- one employer may have many memberships
- one membership belongs to one user and one employer

## Write Paths

MVP writes:

- create first membership when a user creates or requests an employer
- admin adds or changes membership
- admin deactivates membership
- future employer owner/admin manages membership

Rules:

- users may hold multiple employer memberships
- no single ranked WordPress role should model Jobs identity
- Core Terms must not grant Jobs permissions

## Read Paths

MVP reads:

- check whether user can create/edit/submit/close jobs for employer
- recruiter employer switcher
- admin employer user list
- audit context for job events

## Archive / Delete Rules

- deactivate/remove membership rather than hard-delete during normal operation
- membership removal does not delete jobs
- membership removal does not delete events
- historical event actor user IDs remain intact

## Migration Risk

Medium.

Risks:

- overfitting roles too early
- using WordPress roles alone for employer-scoped permissions
- adding billing ownership too soon
- making trust automatic in MVP

## Deferred Fields Explicitly Excluded

Do not store:

- billing ownership
- payment authorization
- resume-search access
- candidate-search grants
- global user role
- Core Terms permissions
- automatic trust score

---

# Table: `tnet_jobs_terms`

## Purpose

Stores Jobs-owned assignments from jobs to Core Terms.

Core Terms owns term identity and hierarchy. Jobs owns the fact that a job is classified by a term.

## Owner

Teachers.Net Jobs plugin owns assignments.

Core Terms owns the referenced terms.

## Lifecycle

Assignments are created, replaced, or archived as job classification changes.

MVP can treat assignment updates as current-state replacement while recording changes in `tnet_jobs_events`.

## Required Columns

- `job_term_id`
- `job_id`
- `term_id`
- `created_at`

## Optional / Deferred Columns

Optional for MVP:

- `term_axis`
- `created_by_user_id`
- `archived_at`

Deferred:

- `term_label_snapshot`
- `term_path_snapshot`
- `term_version`
- `confidence`
- `source`

## Indexes

Recommended MVP indexes:

- primary key on `job_term_id`
- unique index on `job_id`, `term_id`
- index on `job_id`
- index on `term_id`
- index on `term_axis` if stored
- compound index on `term_id`, `job_id`

## Cardinality

- one job has many term assignments
- one Core Term may classify many jobs
- one assignment belongs to one job and one Core Term

## Write Paths

MVP writes:

- recruiter/admin assigns terms while editing job
- publication validation checks required term coverage
- published term edits create events

Publication rules:

- at least one Region/location term is required before publication
- role/category term is required only if that axis exists in Core Terms
- if role/category axis is absent, warn but do not block publication

## Read Paths

MVP reads:

- public job detail term rendering through Core Terms APIs
- active job search/filter by term
- admin/recruiter edit form
- publication validation

## Archive / Delete Rules

- preserve assignment history through job events at minimum
- do not hard-delete Core Terms
- assignment deletion/replacement may be physical in MVP if events preserve meaningful edit history
- future snapshots may be needed for historical rendering

## Migration Risk

High.

Risks:

- duplicating Core Term labels too early
- writing directly into Core Terms tables
- depending on unstable Core Terms internals
- failing to preserve enough history when terms are renamed or deprecated
- indexing too weakly for term-filtered search

## Deferred Fields Explicitly Excluded

Do not store:

- primary term label as canonical data
- full term tree copy
- permission state
- employer classification
- candidate matching score
- billing/promotion relevance

---

# Table: `tnet_jobs_events`

## Purpose

Stores append-oriented records of meaningful job lifecycle and business actions.

Events support audit, moderation history, and future analytics.

## Owner

Teachers.Net Jobs plugin.

## Lifecycle

Append-only for MVP.

No MVP event expiration rule.

## Required Columns

- `event_id`
- `job_id`
- `event_type`
- `created_at`

## Optional / Deferred Columns

Optional for MVP:

- `actor_user_id`
- `employer_id`
- `source`
- `message`
- `metadata_json`

Deferred:

- `reason_code`
- `request_id`
- `ip_hash`
- `user_agent_hash`
- `rollup_batch_id`
- `archived_at`

## Indexes

Recommended MVP indexes:

- primary key on `event_id`
- index on `job_id`
- index on `event_type`
- index on `actor_user_id`
- index on `employer_id`
- index on `created_at`
- compound index on `job_id`, `created_at`
- compound index on `event_type`, `created_at`

## Cardinality

- one job has many events
- one event belongs to one job
- one user may be actor for many events
- one employer may appear in many events

## Write Paths

MVP writes:

- job created
- job submitted
- job approved
- job rejected
- job published
- job edited
- job closed
- job expired
- job archived
- apply clicked
- viewed if implemented as event-backed signal

Rules:

- lifecycle state changes should write events
- published edits should write events
- analytics counters should derive from events or signals, not update `tnet_jobs`

## Read Paths

MVP reads:

- admin moderation history
- recruiter job activity history
- future analytics aggregation
- debugging/audit context

## Archive / Delete Rules

- do not hard-delete during normal operation
- event retention remains open beyond MVP
- future archival should be additive or rollup-based

## Migration Risk

Medium.

Risks:

- metadata JSON becoming unstructured business storage
- storing sensitive personal data unnecessarily
- trying to make events the only query model for current state
- deleting events too early

## Deferred Fields Explicitly Excluded

Do not store:

- application records
- candidate profile data
- resumes
- billing records
- payment receipts
- full request logs
- raw personal tracking data

---

# Table: `tnet_jobs_signals`

## Purpose

Stores lightweight job interaction signals or derived signal rows for MVP analytics.

The broader `signals` name is preferred over `views` because MVP may need both job views and apply-click intent. `views` is too narrow for the accepted signal scope.

## Owner

Teachers.Net Jobs plugin.

## Lifecycle

Append-oriented signal capture with future aggregation/retention policy.

## Required Columns

- `signal_id`
- `job_id`
- `signal_type`
- `created_at`

## Optional / Deferred Columns

Optional for MVP:

- `user_id`
- `session_hash`
- `referrer_host`
- `source`

Deferred:

- `ip_hash`
- `user_agent_hash`
- `campaign_id`
- `aggregated_at`
- `rollup_bucket`

## Indexes

Recommended MVP indexes:

- primary key on `signal_id`
- index on `job_id`
- index on `signal_type`
- index on `created_at`
- compound index on `job_id`, `signal_type`, `created_at`

## Cardinality

- one job has many view/signal rows
- one user may have many signal rows if authenticated
- anonymous signals may have no user

## Write Paths

MVP writes:

- public job detail viewed, if enabled
- apply URL clicked

Rules:

- do not increment counters on `tnet_jobs`
- keep capture minimal
- avoid candidate funnel modeling in MVP

## Read Paths

MVP reads:

- optional admin sanity checks
- future recruiter dashboard
- future aggregate analytics jobs

## Archive / Delete Rules

- retention policy remains open
- future aggregation may roll up raw rows
- avoid storing raw personal data requiring early deletion complexity

## Migration Risk

Medium.

Risks:

- overbuilding analytics
- capturing too much personal data
- confusing signals with applications
- storing derived counters as source of truth

## Deferred Fields Explicitly Excluded

Do not store:

- application status
- candidate intent confirmation
- recruiter conversion funnel
- resume data
- candidate profile data
- billing attribution
- paid promotion attribution

---

# Reserved Future Tables

These are intentionally excluded from MVP implementation but reserved to protect the architecture.

## `tnet_jobs_applications`

Purpose:
Future internal or tracked application records.

Owner:
Future Jobs/ATS layer, depending on product boundary.

Reserved columns:

- `application_id`
- `job_id`
- `candidate_profile_id`
- `status`
- `created_at`
- `updated_at`

Excluded from MVP:

- application storage
- applicant review
- recruiter workflow
- candidate communication

Schema decisions open:

- whether Applications belongs in Jobs core or ATS-lite
- whether anonymous applications are allowed
- relationship to external apply confirmation

## `tnet_jobs_application_events`

Purpose:
Future append-oriented application workflow history.

Owner:
Future Jobs/ATS layer.

Reserved columns:

- `application_event_id`
- `application_id`
- `event_type`
- `actor_user_id`
- `created_at`
- `metadata_json`

Excluded from MVP:

- application workflow
- interview scheduling
- offers
- hires

Schema decisions open:

- event retention
- candidate-visible history
- recruiter notes and privacy boundaries

## `tnet_jobs_candidate_profiles`

Purpose:
Future candidate identity, job-search profile, and visibility record.

Owner:
Future Profiles/Candidate Inventory, not Jobs MVP.

Reserved columns:

- `candidate_profile_id`
- `user_id`
- `visibility_status`
- `created_at`
- `updated_at`

Excluded from MVP:

- candidate profiles
- candidate search
- marketplace inventory
- profile completion

Schema decisions open:

- whether this table belongs in a separate Profiles plugin
- consent model
- visibility states
- profile search indexing

## `tnet_jobs_candidate_documents`

Purpose:
Future candidate resumes and related documents.

Owner:
Future Profiles/Candidate Inventory.

Reserved columns:

- `candidate_document_id`
- `candidate_profile_id`
- `document_type`
- `media_id` or storage reference
- `visibility_status`
- `created_at`

Excluded from MVP:

- resume upload
- resume parsing
- recruiter resume access
- document sharing

Schema decisions open:

- storage location
- retention/deletion requirements
- candidate consent
- recruiter access audit

## `tnet_jobs_job_promotions`

Purpose:
Future job exposure/promotion records.

Owner:
Future Commerce or Jobs Visibility boundary, not Jobs core MVP.

Reserved columns:

- `job_promotion_id`
- `job_id`
- `promotion_type`
- `status`
- `starts_at`
- `ends_at`
- `created_at`

Excluded from MVP:

- paid promotion
- featured purchase
- commerce-controlled visibility

Schema decisions open:

- whether promotions live in Commerce tables instead
- how Commerce grants visibility without mutating jobs directly
- refund/cancellation behavior

## Billing / Entitlements Tables

Purpose:
Future plans, receipts, recruiter subscriptions, paid promotion purchases, and resume-search entitlements.

Owner:
Future Commerce.

Reserved examples:

- `tnet_jobs_billing_accounts`
- `tnet_jobs_orders`
- `tnet_jobs_receipts`
- `tnet_jobs_entitlements`

Excluded from MVP:

- payments
- plans
- receipts
- recruiter subscriptions
- resume-search entitlement
- promotion purchase

Schema decisions open:

- whether Commerce should use `tnet_jobs_*` table names or a separate commerce namespace
- entitlement model
- payment provider
- billing account relationship to employer vs user

---

# Open Schema Decisions

The following decisions remain open before implementation:

- exact WordPress-prefixed physical table names
- integer sizes for identifiers
- whether `job_slug` is globally unique or scoped by employer
- whether `employer_slug` is required in MVP
- exact `status` enum values versus varchar constants
- whether `summary` is stored or derived
- keyword search implementation: SQL `LIKE`, fulltext, or abstraction
- physical table name should prefer `tnet_jobs_signals`
- whether view signals are implemented in MVP or only apply clicks
- how much event metadata is allowed in `metadata_json`
- whether term-axis values are stored on `tnet_jobs_terms` or resolved through Core Terms
- whether assignment changes are physically replaced or archived row-by-row
- event/signal retention and aggregation policy
- employer verification states
- membership invitation model
- trusted recruiter/employer state model

---

# J3 Acceptance Criteria

J3 is ready to support API contract planning when:

- MVP table list is accepted
- reserved future tables are explicitly deferred
- forbidden fields are kept out of `tnet_jobs`
- Core Terms references remain references, not duplicated classification data
- analytics remain event/signal-derived
- employer membership remains Jobs-owned and employer-scoped
- open schema decisions are either accepted as defaults or carried forward to implementation planning

After J3, the next safe task is J4 API Contract v0.1.
