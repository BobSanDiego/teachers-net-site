# Teachers.Net Jobs — Schema Installer Plan v0.1

Status: Planning Baseline
Purpose: Define the future schema installer and upgrade behavior before writing migration code.

This is a planning document only. It is not plugin code, not SQL, and not a migration.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Scope

This plan covers the future schema installer for the `tnet-jobs` plugin.

It does not create:

- PHP implementation
- SQL files
- database tables
- migrations
- WordPress options
- WordPress state changes

---

# Carry-Forward Constraints

Accepted:

- custom tables
- Jobs-owned schema versioning
- additive migrations preferred
- idempotent installer
- no hard-delete rollback
- health-page visibility

Excluded from this installer phase:

- reserved future tables
- ATS tables
- resume tables
- candidate profile/search tables
- billing tables
- entitlement tables
- promotion tables
- Core Terms table writes
- Core Terms schema changes

MVP logical tables only:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_events`
- `tnet_jobs_signals`

---

# Schema Version Option

Recommended option name:

`tnet_jobs_schema_version`

Related possible options:

- `tnet_jobs_installed_version`
- `tnet_jobs_last_schema_check`
- `tnet_jobs_schema_last_error`

Recommendation:

- Use `tnet_jobs_schema_version` as the canonical database schema version.
- Keep plugin code version separate as `TNET_JOBS_VERSION`.
- Keep schema version separate as `TNET_JOBS_DB_VERSION` or similar.

Initial schema version:

`0.1.0`

Rules:

- Plugin version and schema version may start equal but should not be assumed to remain equal forever.
- Schema version should only update after successful install/upgrade.
- Failed installs/upgrades should leave enough diagnostic state for the health page.

---

# dbDelta vs Explicit Migration Recommendation

Recommendation:
Use a small explicit migration runner with carefully controlled table creation statements, and use `dbDelta` only if implementation confirms its behavior is acceptable for this schema.

Practical recommendation for first implementation:

- Create a `TNet_Jobs_Schema` class.
- Define expected table names centrally.
- Define expected table columns/indexes centrally.
- Use idempotent install methods per table.
- Use explicit table-existence and column-existence checks.
- Prefer additive migrations.

Why not rely blindly on `dbDelta`:

- `dbDelta` has strict formatting expectations.
- Index changes can be surprising.
- Silent partial changes are hard to reason about.
- The project needs clear health reporting.

Why not overbuild a full migration framework yet:

- MVP schema is small.
- No production Jobs data exists yet.
- The first requirement is safe installation and inspection, not complex migration history.

Open implementation decision:
Whether first implementation uses `dbDelta` internally for create/alter statements or manual SQL execution remains open until the schema class is drafted.

Non-negotiable:
No SQL should write to Core Terms tables.

---

# Install Flow

Install should run only after dependency checks pass.

Preconditions:

- WordPress is loaded.
- Jobs plugin is active.
- Core Terms dependency is satisfied.
- Current user/action context is activation, admin-triggered install, or controlled upgrade path.

Recommended flow:

1. Load schema class.
2. Resolve WordPress-prefixed table names.
3. Check Core Terms dependency health.
4. If dependency is not satisfied, skip business table creation.
5. Read `tnet_jobs_schema_version`.
6. If no schema version exists, run fresh install.
7. Create MVP tables only.
8. Verify expected tables exist.
9. Verify required columns/indexes as feasible.
10. Store `tnet_jobs_schema_version` only after successful verification.
11. Clear previous schema error state.
12. Return structured install result for admin/health display.

Fresh install should create only:

- jobs table
- employers table
- employer users table
- job terms table
- job events table
- job signals table

Fresh install should not create:

- applications
- application events
- candidate profiles
- candidate documents
- promotions
- billing
- entitlements
- Core Terms tables

---

# Upgrade Flow

Upgrade should compare stored schema version to current target schema version.

Recommended flow:

1. Read stored `tnet_jobs_schema_version`.
2. Compare against target schema version.
3. If stored version is missing, route to fresh install flow.
4. If stored version equals target, verify table health and exit.
5. If stored version is lower, run ordered additive migrations.
6. Verify migration result.
7. Update `tnet_jobs_schema_version` after successful verification.
8. Store structured error state if any migration fails.

Upgrade rules:

- migrations must be ordered
- migrations must be repeatable or guarded
- do not drop columns automatically
- do not drop tables automatically
- do not delete business records
- preserve identifiers
- preserve archived records
- preserve events

Future migration naming:

- `install_0_1_0`
- `upgrade_to_0_1_1`
- `upgrade_to_0_2_0`

Exact naming can change during implementation.

---

# Idempotency Rules

Installer must be safe to run more than once.

Rules:

- creating an existing table must not fail the whole install
- adding an existing column must be skipped
- adding an existing index must be skipped or verified
- existing data must not be overwritten
- schema version must not advance after partial failure
- dependency failure must not create partial business tables
- health checks must be read-only

Activation idempotency:

- activating an already-installed plugin should not recreate tables destructively
- activation should not reset schema version
- activation should not seed sample data

Admin-triggered repair, if added later:

- should verify and add missing expected pieces
- should not delete unexpected data
- should require explicit capability and nonce checks

---

# Failure Handling

Failure handling should be structured and visible.

Recommended failure result fields:

- `status`
- `message`
- `failed_step`
- `table`
- `schema_version_before`
- `schema_version_target`
- `last_error`

Failure behavior:

- leave plugin active
- mark runtime as dependency/schema warning if appropriate
- do not fatal on normal admin page loads
- do not update schema version on failure
- store last schema error option for health page
- show admin notice for users with manage capability

Dependency failure:

- activation remains allowed
- business tables are not created
- health page shows dependency warning
- install action should be unavailable or blocked until dependency passes

Partial install failure:

- do not delete already-created tables automatically
- report partial state
- allow future idempotent retry

---

# Health Page Integration

Current J6 health page reports:

- dependency status
- runtime state
- schema not installed
- tables not installed
- public routes not registered

J7 implementation should extend health output with:

- target schema version
- installed schema version
- schema install status
- table existence status
- missing table list
- last schema error
- whether installer can run

Suggested health statuses:

- `not_installed`
- `installed`
- `upgrade_available`
- `dependency_blocked`
- `partial`
- `error`

Health page should remain read-only unless a future explicit repair/install action is accepted.

Health page should not:

- create tables on page load
- run migrations on page load
- delete tables
- write Core Terms data

---

# Table Existence Checks

Schema health should check only Jobs-owned MVP tables:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_events`
- `tnet_jobs_signals`

Checks should verify:

- table exists
- required primary key exists if feasible
- required columns exist
- critical indexes exist if feasible

Initial minimum check:

- table existence
- installed schema version option
- missing table list

Later checks:

- column compatibility
- index compatibility
- engine/collation expectations

Table checks must not:

- inspect Core Terms internals except through accepted dependency/API checks
- check reserved future tables as required
- treat missing future tables as unhealthy

---

# Activation Behavior

Accepted activation rule:
Allow activation if Core Terms is missing or too old, but run in disabled/dependency-warning state.

J7 recommendation:

- Activation should not create business tables unless Core Terms dependency checks pass.
- Activation may register plugin options needed for health state.
- Activation should not install schema when dependency is blocked.
- Activation should not fatal because Core Terms is unavailable.

Two possible activation models:

## Model A — Install On Activation When Dependency Passes

Activation checks Core Terms. If satisfied, install MVP schema.

Pros:

- standard WordPress plugin behavior
- fewer manual steps

Cons:

- activation path does more work
- harder to inspect before table creation

## Model B — Activation Only, Manual Install Later

Activation checks Core Terms and health. Admin explicitly runs install later.

Pros:

- maximum control
- aligns with cautious early development
- easier to inspect dependency health before schema writes

Cons:

- extra admin step

Recommendation:
Use Model B for first schema implementation unless the user explicitly approves install-on-activation.

Reason:
The project is still in early domain/schema validation. A manual, capability-protected install action keeps table creation intentional.

---

# Rollback / No-Delete Policy

Rollback principle:
Stop behavior before deleting data.

Do not implement automatic destructive rollback.

Forbidden by default:

- dropping Jobs tables
- truncating Jobs tables
- deleting jobs
- deleting employers
- deleting events
- deleting signals
- deleting term assignments
- deleting Core Terms data
- unregistering Core Terms data
- uninstalling Core Terms

Deactivation should:

- stop runtime behavior
- leave tables and options intact
- not alter Core Terms

Uninstall should:

- not be implemented until a written data retention policy exists

Manual reset:

- requires explicit user instruction
- should be documented separately
- should not be part of normal development flow

---

# First Implementation Sequence

## Step 1 — Extend Constants

Add schema version constant only:

- `TNET_JOBS_DB_VERSION`

No table creation yet.

## Step 2 — Add Schema Class Shell

Create schema class with:

- table name map
- target schema version
- installed version reader
- dependency-aware install eligibility
- read-only health method

No SQL yet if doing an even smaller first step.

## Step 3 — Health Integration

Wire schema health into `TNet_Jobs::health()`.

Health should show:

- target schema version
- installed schema version
- install eligibility
- table status
- missing tables
- last schema error

## Step 4 — Table Existence Checks

Add read-only checks for the six MVP table names.

No table creation.

## Step 5 — Install Method Draft

Add installer methods behind explicit calls.

Still no auto-run on health page.

## Step 6 — Admin Install Action

If accepted later:

- add nonce-protected admin action
- require manage capability
- run installer only when dependency is satisfied
- report structured result

## Step 7 — Fresh Install SQL

Only after the installer plan and table design are accepted for implementation:

- add create statements or `dbDelta` definitions
- create six MVP tables only
- verify idempotency

---

# Open J7 Decisions

Before implementation, decide:

- install-on-activation versus manual admin install
- `dbDelta` versus explicit SQL execution
- exact physical table names with WordPress prefix
- exact schema version constant name
- whether schema error state is stored in an option
- whether table existence checks include column/index checks in first pass
- whether an admin install button belongs in J7 or later
- whether activation writes any schema-related options when dependency is blocked

Recommended defaults:

- manual admin install later
- no table creation in first schema class shell
- table existence health first
- target schema version constant: `TNET_JOBS_DB_VERSION`
- option name: `tnet_jobs_schema_version`
- last error option: `tnet_jobs_schema_last_error`

---

# J7 Acceptance Criteria

J7 planning is ready for implementation when:

- schema option name is accepted
- installer/upgrade flow is accepted
- idempotency rules are accepted
- dependency-blocked behavior is accepted
- health-page integration is accepted
- no-delete rollback policy is accepted
- first implementation sequence is accepted

After J7 planning, the next safe task is implementing a schema health shell without creating tables.
