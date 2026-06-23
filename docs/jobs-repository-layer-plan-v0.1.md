# Teachers.Net Jobs — Repository Layer Plan v0.1

Status: Planning Baseline
Purpose: Define the first repository layer before writing repository code.

This is a planning document only. It is not plugin code, not SQL, and not a migration.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Scope

The repository layer owns low-level persistence for the six installed MVP Jobs tables.

It does not own:

- business workflows
- authorization policy
- publication validation
- Core Terms reads/writes
- admin UI
- public routes
- REST routes
- sample data
- schema creation
- migrations
- reserved future tables

Services will own business policy. Repositories will own table access.

---

# Installed MVP Tables

Repositories may access only these Jobs-owned tables:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_events`
- `tnet_jobs_signals`

Physical names must be computed with `$wpdb->prefix`.

Repositories must not access:

- Core Terms tables
- WordPress user tables except by storing/reading user IDs already in Jobs tables
- applications tables
- candidate profile tables
- resume/document tables
- billing/entitlement tables
- promotion tables
- ATS tables

---

# Repository Design Rules

## Responsibilities

Repositories should:

- read from one primary Jobs table
- write to one primary Jobs table
- expose narrow methods
- use `$wpdb->prepare()` for dynamic values
- return arrays or scalar IDs, not HTML
- avoid direct output
- avoid capability checks
- avoid nonce checks
- avoid Core Terms calls
- avoid lifecycle policy decisions

Repositories may:

- accept validated data from services
- apply table-specific defaults
- map database rows to associative arrays
- expose simple list/count/get methods
- expose archive/deactivate helpers where the table has archive fields

Repositories must not:

- decide whether a job may publish
- decide whether a user may edit
- validate required Core Terms axes
- call Core Terms internals
- create events as side effects except inside the event repository itself
- increment counters on business records
- hard-delete business records during normal operation

## Return Shapes

Recommended return conventions:

- insert methods return inserted integer ID or `WP_Error`
- update methods return `true` or `WP_Error`
- get methods return associative array or `null`
- list methods return arrays of associative arrays
- count methods return integer

Do not throw exceptions for expected validation/persistence failures. Prefer `WP_Error` with stable error codes.

## Time Values

Use WordPress current time helpers at service level where possible.

Repository inputs should receive explicit datetime strings in MySQL `Y-m-d H:i:s` format.

Repository layer should not independently decide lifecycle timestamps.

## JSON Fields

Only `tnet_jobs_events.metadata_json` is currently JSON-like storage.

Rules:

- encode arrays with `wp_json_encode()`
- decode to associative arrays when reading if a helper is provided
- do not treat metadata as hidden business storage
- avoid personal data unless explicitly approved later

---

# Class Map

Proposed repository folder:

`wordpress/wp-content/plugins/tnet-jobs/includes/repositories`

Proposed classes:

- `TNet_Jobs_Job_Repository`
- `TNet_Jobs_Employer_Repository`
- `TNet_Jobs_Employer_User_Repository`
- `TNet_Jobs_Term_Repository`
- `TNet_Jobs_Event_Repository`
- `TNet_Jobs_Signal_Repository`

Optional shared base/helper:

- `TNet_Jobs_Repository`

Recommendation:
Skip a shared base class for the first implementation unless duplication becomes meaningful. Use small explicit repositories first.

---

# Table Name Access

Repositories should receive a `TNet_Jobs_Schema` instance or use `TNet_Jobs::schema()` to resolve table names.

Recommended pattern:

- schema owns physical table-name mapping
- repositories do not duplicate table-name constants
- repositories read table names through a public schema method

Needed schema support before repository implementation:

- public method to get a single physical table name by key, or
- repositories call `physical_table_names()` and select by key

Accepted table keys:

- `jobs`
- `employers`
- `employer_users`
- `terms`
- `events`
- `signals`

---

# Repository: `TNet_Jobs_Job_Repository`

Primary table:
`tnet_jobs`

Purpose:
Low-level persistence for job records.

Initial methods:

- `insert(array $data)`
- `update($job_id, array $data)`
- `get($job_id)`
- `get_by_slug($job_slug)`
- `list_by_status($status, array $args = [])`
- `list_by_employer($employer_id, array $args = [])`
- `list_by_created_user($user_id, array $args = [])`
- `list_active(array $args = [])`
- `archive($job_id, $archived_at)`

Allowed writes:

- insert a job row
- update job row fields provided by service
- set archive timestamp/status when service requests archive

Forbidden:

- hard-delete jobs
- write counters
- write term labels
- write billing/promotion/application state
- create lifecycle events as hidden side effects

Read defaults:

- active list should include `published` jobs whose expiration has not passed
- archived rows excluded unless explicitly requested

Open decision:
Whether active filtering belongs in repository or service. Recommended: repository can provide a low-level `list_active()` helper, but service owns public meaning.

---

# Repository: `TNet_Jobs_Employer_Repository`

Primary table:
`tnet_jobs_employers`

Purpose:
Low-level persistence for employer records.

Initial methods:

- `insert(array $data)`
- `update($employer_id, array $data)`
- `get($employer_id)`
- `get_by_slug($employer_slug)`
- `list_by_status($status, array $args = [])`
- `list_for_admin(array $args = [])`
- `archive($employer_id, $archived_at)`

Allowed writes:

- create employer
- update employer identity fields
- update status/verification fields when service requests it
- archive employer

Forbidden:

- hard-delete employers
- store billing plan
- store trust score
- store Core Terms IDs as employer classification
- grant resume-search access

Open decision:
Whether `employer_slug` is required before public employer pages exist. Recommended: repository supports nullable slug; service decides when slug is required.

---

# Repository: `TNet_Jobs_Employer_User_Repository`

Primary table:
`tnet_jobs_employer_users`

Purpose:
Low-level persistence for employer-scoped user membership.

Initial methods:

- `insert(array $data)`
- `update($employer_user_id, array $data)`
- `get($employer_user_id)`
- `get_by_employer_user($employer_id, $user_id)`
- `list_by_employer($employer_id, array $args = [])`
- `list_by_user($user_id, array $args = [])`
- `deactivate($employer_user_id, $deactivated_at, $deactivated_by_user_id = null)`

Allowed writes:

- create membership
- update role/status fields when service requests it
- deactivate membership

Forbidden:

- hard-delete membership during normal operation
- make global WordPress role changes
- grant Core Terms permissions
- store billing ownership
- store candidate-search grants

Unique constraint behavior:

- duplicate `employer_id` + `user_id` should return `WP_Error`
- services may decide whether to reactivate existing inactive membership

---

# Repository: `TNet_Jobs_Term_Repository`

Primary table:
`tnet_jobs_terms`

Purpose:
Low-level persistence for job-to-Core-Term assignment records.

Initial methods:

- `insert(array $data)`
- `get($job_term_id)`
- `list_by_job($job_id, array $args = [])`
- `list_by_term($term_id, array $args = [])`
- `replace_for_job($job_id, array $assignments)`
- `archive_for_job($job_id, $archived_at)`
- `archive_assignment($job_term_id, $archived_at)`

Allowed writes:

- create assignment rows
- archive assignment rows
- replace current assignments when service requests it

Forbidden:

- write Core Terms tables
- duplicate Core Terms labels
- copy Core Terms trees
- decide required term axes
- use terms for permissions

Open decision:
Whether replacement physically deletes current rows or archives them. Recommended for first implementation: archive old rows using `archived_at`, then insert new active rows where needed. This better matches historical safety.

Uniqueness caution:
Current table has unique key on `job_id`, `term_id`. If archived historical duplicate assignments are needed later, this key may need migration. For MVP, one assignment per job/term is acceptable.

---

# Repository: `TNet_Jobs_Event_Repository`

Primary table:
`tnet_jobs_events`

Purpose:
Append-oriented event persistence.

Initial methods:

- `insert(array $data)`
- `get($event_id)`
- `list_by_job($job_id, array $args = [])`
- `list_by_type($event_type, array $args = [])`
- `list_by_actor($actor_user_id, array $args = [])`

Allowed writes:

- append event rows only

Forbidden:

- update event rows during normal operation
- hard-delete event rows
- store application records
- store resumes
- store payment data
- store raw personal tracking data

Metadata:

- accept optional metadata array
- encode to `metadata_json`
- keep metadata small and non-authoritative

---

# Repository: `TNet_Jobs_Signal_Repository`

Primary table:
`tnet_jobs_signals`

Purpose:
Low-level persistence for lightweight job interaction signals.

Initial methods:

- `insert(array $data)`
- `get($signal_id)`
- `list_by_job($job_id, array $args = [])`
- `count_by_job($job_id, $signal_type = null)`
- `count_by_type($signal_type, array $args = [])`

Allowed writes:

- append signal rows

Forbidden:

- increment counters on `tnet_jobs`
- create application records
- infer candidate status
- store resume/candidate profile data
- store billing/promotion attribution

MVP signal types:

- `apply_clicked`
- `viewed` optional

Privacy:

- `session_hash` should be pre-hashed before repository insert
- repository should not hash raw IPs or user agents unless that policy is accepted later

---

# Error Codes

Suggested repository error codes:

- `tnet_jobs_insert_failed`
- `tnet_jobs_update_failed`
- `tnet_jobs_not_found`
- `tnet_jobs_duplicate`
- `tnet_jobs_invalid_table`
- `tnet_jobs_invalid_data`

Repository errors should include minimal context:

- table key
- operation
- row ID if available
- `$wpdb->last_error` when safe

Do not expose raw SQL in user-facing messages.

---

# Query Safety

Rules:

- use `$wpdb->prepare()` for every dynamic WHERE value
- use whitelisted order-by fields
- use whitelisted sort directions
- cast IDs to integers before queries
- sanitize string inputs before writes at service/validation layer
- escape only at output layer, not repository layer

Pagination defaults:

- default limit: 20
- maximum limit: 100
- offset supported for admin lists

Ordering defaults:

- jobs: `created_at DESC` or `published_at DESC` depending on context
- employers: `name ASC`
- events: `created_at DESC`
- signals: `created_at DESC`

---

# Implementation Sequence

## Step 1 — Repository Folder

Create:

`includes/repositories/`

No service code yet.

## Step 2 — Schema Table Lookup Helper

Add a public schema helper for table lookup:

- `table_name($key)`

This should return the physical WordPress-prefixed table name or `null`/`WP_Error` for unknown keys.

## Step 3 — Event Repository First

Implement `TNet_Jobs_Event_Repository` first.

Reason:
Events are append-only and will be needed by later services.

## Step 4 — Employer Repository

Implement employer CRUD/archive basics.

## Step 5 — Employer User Repository

Implement membership persistence.

## Step 6 — Job Repository

Implement job insert/update/get/list/archive basics.

## Step 7 — Term Repository

Implement job term assignment persistence.

## Step 8 — Signal Repository

Implement signal append/count methods.

## Step 9 — Lint And Smoke Checks

Run PHP lint on all new repository files and touched bootstrap files.

Do not seed sample data unless explicitly requested later.

---

# Test Strategy

Initial repository tests can be WP-CLI smoke checks once repositories exist.

Allowed checks:

- instantiate each repository
- confirm table names resolve
- insert and read one controlled test row only if the user explicitly approves test data
- verify repositories return `WP_Error` on invalid input

Default for first implementation:

- lint only
- no test data
- no sample data
- no destructive cleanup

Useful commands:

```bash
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/includes/repositories/class-tnet-jobs-event-repository.php
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/includes/repositories/class-tnet-jobs-job-repository.php
ddev wp eval --path=wordpress 'echo wp_json_encode(TNet_Jobs::health(), JSON_PRETTY_PRINT);'
```

---

# Rollback / No-Delete Policy

Repository layer must not add destructive reset tools.

Forbidden:

- drop tables
- truncate tables
- hard-delete jobs
- hard-delete employers
- hard-delete events
- hard-delete signals
- delete Core Terms data
- delete WordPress users

Archive/deactivate instead:

- jobs use status/archive fields
- employers use status/archive fields
- memberships use status/deactivation fields
- terms use assignment archive where supported

Events and signals are append-oriented.

---

# Open J9 Decisions

Before implementation, decide:

- whether to create a shared repository base class
- whether repositories accept a schema instance through constructor
- whether repository methods return raw arrays or lightweight DTO/value objects
- whether term assignment replacement archives old rows or performs physical replacement in MVP
- whether repository smoke tests may insert test rows
- whether event metadata should be auto-decoded on reads
- whether list methods should include total counts in first pass

Recommended defaults:

- no shared base class initially
- repositories accept optional schema instance in constructor
- return associative arrays
- use `WP_Error` for failures
- no test rows unless explicitly approved
- keep metadata raw initially, add decode helper later
- list methods return rows only; counts can be separate

---

# J9 Acceptance Criteria

J9 is ready for repository implementation when:

- repository class list is accepted
- table ownership boundaries are accepted
- method contracts are accepted
- no-delete rules are accepted
- Core Terms write prohibition is accepted
- no services/business policy leakage is accepted
- implementation sequence is accepted

After J9 planning, the next safe task is implementing the first repository class.
