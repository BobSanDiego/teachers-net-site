# Teachers.Net Jobs — Schema Installer Implementation v0.1

Status: Implementation Specification
Purpose: Define the exact first schema installation approach before writing installer code.

This is a planning document only. It is not plugin code, not a SQL file, and not a migration.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Scope

This document defines the first installable Jobs schema.

It does not create:

- PHP implementation
- database tables
- SQL files
- migrations
- WordPress options
- WordPress state changes

---

# Carry-Forward Constraints

Install only the six MVP Jobs tables:

- `tnet_jobs`
- `tnet_jobs_employers`
- `tnet_jobs_employer_users`
- `tnet_jobs_terms`
- `tnet_jobs_events`
- `tnet_jobs_signals`

Do not install:

- reserved future tables
- applications
- application events
- ATS tables
- resume tables
- candidate profile tables
- candidate search tables
- billing tables
- entitlement tables
- promotion tables
- Core Terms tables

Do not write:

- Core Terms tables
- Core Terms options
- Core Terms term data
- sample Jobs business data

Safety rule:
Archive/delete safety wins over convenience. No install, upgrade, rollback, or deactivation flow should delete business records.

---

# Installation Approach

Use explicit `CREATE TABLE` statements executed through the Jobs schema class.

Do not use `dbDelta` for the first implementation.

Reason:

- the first schema is small and fully controlled
- explicit statements make success/failure clearer
- health checks already verify table existence
- future additive migrations can be handled by explicit guarded checks
- `dbDelta` formatting and index behavior can be surprising

Rules:

- use WordPress `$wpdb` and `$wpdb->get_charset_collate()`
- use one create statement per table
- run each create only when that physical table is missing
- verify all six tables after create attempts
- write schema version only after verification succeeds
- do not create foreign key constraints in v0.1.0

Foreign-key rationale:

- WordPress core tables generally do not rely on enforced foreign keys
- Core Terms remains a separate plugin boundary
- archive/history requirements are better served by application-level constraints initially

---

# Schema Version

Schema version constant:

`TNET_JOBS_DB_VERSION`

Initial value:

`0.1.0`

Schema version option:

`tnet_jobs_schema_version`

Last-error option:

`tnet_jobs_schema_last_error`

Version write timing:

- do not write before table creation
- do not write after partial install
- write `tnet_jobs_schema_version = 0.1.0` only after all six MVP tables exist
- clear `tnet_jobs_schema_last_error` only after successful verification

---

# Physical Table Names

Assuming the current WordPress prefix is `wp_`, the first physical table names are:

- `wp_tnet_jobs`
- `wp_tnet_jobs_employers`
- `wp_tnet_jobs_employer_users`
- `wp_tnet_jobs_terms`
- `wp_tnet_jobs_events`
- `wp_tnet_jobs_signals`

Implementation must compute names dynamically with `$wpdb->prefix`.

Logical-to-physical mapping:

- `tnet_jobs` -> `$wpdb->prefix . 'tnet_jobs'`
- `tnet_jobs_employers` -> `$wpdb->prefix . 'tnet_jobs_employers'`
- `tnet_jobs_employer_users` -> `$wpdb->prefix . 'tnet_jobs_employer_users'`
- `tnet_jobs_terms` -> `$wpdb->prefix . 'tnet_jobs_terms'`
- `tnet_jobs_events` -> `$wpdb->prefix . 'tnet_jobs_events'`
- `tnet_jobs_signals` -> `$wpdb->prefix . 'tnet_jobs_signals'`

---

# Table: `tnet_jobs`

Purpose:
Canonical job/opportunity record.

Exact columns:

- `job_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
- `job_slug` VARCHAR(191) NOT NULL
- `status` VARCHAR(32) NOT NULL DEFAULT 'draft'
- `title` VARCHAR(255) NOT NULL
- `summary` TEXT NULL
- `description` LONGTEXT NOT NULL
- `employment_type` VARCHAR(64) NOT NULL
- `apply_method` VARCHAR(32) NOT NULL DEFAULT 'external_url'
- `apply_url` TEXT NOT NULL
- `employer_id` BIGINT UNSIGNED NULL
- `created_by_user_id` BIGINT UNSIGNED NOT NULL
- `moderated_by_user_id` BIGINT UNSIGNED NULL
- `created_at` DATETIME NOT NULL
- `updated_at` DATETIME NOT NULL
- `submitted_at` DATETIME NULL
- `approved_at` DATETIME NULL
- `published_at` DATETIME NULL
- `closed_at` DATETIME NULL
- `expired_at` DATETIME NULL
- `archived_at` DATETIME NULL
- `expires_at` DATETIME NOT NULL

Exact indexes:

- PRIMARY KEY (`job_id`)
- UNIQUE KEY `job_slug` (`job_slug`)
- KEY `status` (`status`)
- KEY `employer_id` (`employer_id`)
- KEY `created_by_user_id` (`created_by_user_id`)
- KEY `employment_type` (`employment_type`)
- KEY `published_at` (`published_at`)
- KEY `expires_at` (`expires_at`)
- KEY `status_published_at` (`status`, `published_at`)
- KEY `status_expires_at` (`status`, `expires_at`)
- FULLTEXT KEY `job_search` (`title`, `description`)

Notes:

- `description` is canonical long-form content.
- `summary` is optional/deferred and may be admin-entered or derived later.
- `employer_id` is nullable for draft flow, but publication validation should require employer.
- no counters
- no billing fields
- no promotion fields
- no application fields

---

# Table: `tnet_jobs_employers`

Purpose:
First-class employer/posting organization.

Exact columns:

- `employer_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
- `employer_slug` VARCHAR(191) NULL
- `name` VARCHAR(255) NOT NULL
- `status` VARCHAR(32) NOT NULL DEFAULT 'created'
- `verification_status` VARCHAR(32) NOT NULL DEFAULT 'unverified'
- `website_url` TEXT NULL
- `description` LONGTEXT NULL
- `created_by_user_id` BIGINT UNSIGNED NOT NULL
- `verified_by_user_id` BIGINT UNSIGNED NULL
- `created_at` DATETIME NOT NULL
- `updated_at` DATETIME NOT NULL
- `verified_at` DATETIME NULL
- `archived_at` DATETIME NULL

Exact indexes:

- PRIMARY KEY (`employer_id`)
- UNIQUE KEY `employer_slug` (`employer_slug`)
- KEY `status` (`status`)
- KEY `verification_status` (`verification_status`)
- KEY `created_by_user_id` (`created_by_user_id`)
- KEY `name` (`name`(191))

Notes:

- employer slug is optional for MVP but indexed if present
- employer is not a Core Term
- no billing state
- no trust score
- no resume-search entitlement

---

# Table: `tnet_jobs_employer_users`

Purpose:
Employer-scoped membership between WordPress users and Jobs employers.

Exact columns:

- `employer_user_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
- `employer_id` BIGINT UNSIGNED NOT NULL
- `user_id` BIGINT UNSIGNED NOT NULL
- `membership_role` VARCHAR(32) NOT NULL DEFAULT 'poster'
- `status` VARCHAR(32) NOT NULL DEFAULT 'active'
- `created_by_user_id` BIGINT UNSIGNED NULL
- `created_at` DATETIME NOT NULL
- `updated_at` DATETIME NOT NULL
- `deactivated_at` DATETIME NULL
- `deactivated_by_user_id` BIGINT UNSIGNED NULL

Exact indexes:

- PRIMARY KEY (`employer_user_id`)
- UNIQUE KEY `employer_user` (`employer_id`, `user_id`)
- KEY `user_id` (`user_id`)
- KEY `employer_id` (`employer_id`)
- KEY `membership_role` (`membership_role`)
- KEY `status` (`status`)

Notes:

- do not model Jobs authorization as one global WordPress role
- no billing ownership
- no candidate-search grants
- no automatic trust score

---

# Table: `tnet_jobs_terms`

Purpose:
Jobs-owned assignment from jobs to Core Terms.

Exact columns:

- `job_term_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
- `job_id` BIGINT UNSIGNED NOT NULL
- `term_id` BIGINT UNSIGNED NOT NULL
- `term_axis` VARCHAR(64) NULL
- `created_by_user_id` BIGINT UNSIGNED NULL
- `created_at` DATETIME NOT NULL
- `archived_at` DATETIME NULL

Exact indexes:

- PRIMARY KEY (`job_term_id`)
- UNIQUE KEY `job_term` (`job_id`, `term_id`)
- KEY `job_id` (`job_id`)
- KEY `term_id` (`term_id`)
- KEY `term_axis` (`term_axis`)
- KEY `term_job` (`term_id`, `job_id`)

Notes:

- `term_id` references Core Terms identity but is not enforced as a database foreign key
- Jobs must not write Core Terms tables
- no term labels
- no term tree copy
- no permission state

---

# Table: `tnet_jobs_events`

Purpose:
Append-oriented job lifecycle and business event history.

Exact columns:

- `event_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
- `job_id` BIGINT UNSIGNED NOT NULL
- `event_type` VARCHAR(64) NOT NULL
- `actor_user_id` BIGINT UNSIGNED NULL
- `employer_id` BIGINT UNSIGNED NULL
- `source` VARCHAR(64) NULL
- `message` TEXT NULL
- `metadata_json` LONGTEXT NULL
- `created_at` DATETIME NOT NULL

Exact indexes:

- PRIMARY KEY (`event_id`)
- KEY `job_id` (`job_id`)
- KEY `event_type` (`event_type`)
- KEY `actor_user_id` (`actor_user_id`)
- KEY `employer_id` (`employer_id`)
- KEY `created_at` (`created_at`)
- KEY `job_created_at` (`job_id`, `created_at`)
- KEY `event_type_created_at` (`event_type`, `created_at`)

Notes:

- append-oriented
- no application records
- no resumes
- no payment records
- metadata must not become hidden business storage

---

# Table: `tnet_jobs_signals`

Purpose:
Lightweight job interaction signals, including views and apply-click intent.

Exact columns:

- `signal_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
- `job_id` BIGINT UNSIGNED NOT NULL
- `signal_type` VARCHAR(64) NOT NULL
- `user_id` BIGINT UNSIGNED NULL
- `session_hash` VARCHAR(191) NULL
- `referrer_host` VARCHAR(191) NULL
- `source` VARCHAR(64) NULL
- `created_at` DATETIME NOT NULL

Exact indexes:

- PRIMARY KEY (`signal_id`)
- KEY `job_id` (`job_id`)
- KEY `signal_type` (`signal_type`)
- KEY `created_at` (`created_at`)
- KEY `job_signal_created_at` (`job_id`, `signal_type`, `created_at`)

Notes:

- no counters on `tnet_jobs`
- no application status
- no candidate profile data
- no recruiter funnel state
- no billing attribution
- no promotion attribution

---

# Install Flow

First implementation should expose an explicit install method but should not run from health page reads.

Recommended install flow:

1. Confirm Core Terms dependency is satisfied.
2. Resolve physical table names using `$wpdb->prefix`.
3. Read `tnet_jobs_schema_version`.
4. If schema version is already `0.1.0`, verify table health and return success/no-op.
5. If any MVP table is missing, create missing MVP tables only.
6. After all create attempts, run table existence verification.
7. If all six MVP tables exist, write `tnet_jobs_schema_version = 0.1.0`.
8. Clear `tnet_jobs_schema_last_error`.
9. Return structured success result.

Install must not:

- create future/reserved tables
- create sample data
- write Core Terms
- create public routes
- create services/repositories
- run on health page render
- run if dependency is blocked

---

# Upgrade Flow

Initial implementation has no upgrade migrations beyond fresh install.

Flow:

1. Read installed schema version.
2. If missing, route to install flow.
3. If equal to target version, verify health and return no-op.
4. If lower than target in future, run ordered additive migrations.
5. If greater than target, report unsupported newer schema and do not modify.

Future migration rules:

- additive only by default
- no automatic drops
- no destructive rewrites
- no business record deletion
- update version only after verification

---

# Idempotency Rules

Installer must be safe to run repeatedly.

Rules:

- existing tables are skipped
- missing tables are created
- existing data is not modified
- schema version write occurs only after all expected tables exist
- partial installs report `partial`
- retry after partial install should continue from missing tables
- dependency-blocked installs create nothing
- health checks remain read-only

---

# Failure Behavior

Failure should leave the plugin active but schema-unhealthy.

On failure:

- do not update `tnet_jobs_schema_version`
- write structured diagnostic to `tnet_jobs_schema_last_error`
- return structured error result
- leave any already-created table in place
- do not drop or truncate tables
- show health-page error/partial state

Suggested error fields:

- `status`
- `message`
- `failed_step`
- `table`
- `wpdb_last_error`
- `schema_version_before`
- `schema_version_target`

Dependency failure:

- no table creation
- no schema version write
- health remains dependency warning

Unsupported newer schema:

- no writes
- clear message that code is older than installed schema

---

# Schema Version Write Timing

Write `tnet_jobs_schema_version` only when all are true:

- Core Terms dependency is satisfied
- create/verify cycle completed
- all six MVP tables exist
- no installer error remains

Never write schema version:

- before table creation
- after partial table creation
- when Core Terms dependency is missing/unsupported
- when any table verification fails
- during read-only health checks

---

# Health Page Expected Output

## Before Install

Expected current state:

- dependency: `satisfied`
- runtime state: `enabled`
- schema status: `missing`
- installed schema version: `not set`
- target schema version: `0.1.0`
- missing tables:
  - `wp_tnet_jobs`
  - `wp_tnet_jobs_employers`
  - `wp_tnet_jobs_employer_users`
  - `wp_tnet_jobs_terms`
  - `wp_tnet_jobs_events`
  - `wp_tnet_jobs_signals`

## After Successful Install

Expected state:

- dependency: `satisfied`
- runtime state: `enabled`
- schema status: `installed`
- installed schema version: `0.1.0`
- target schema version: `0.1.0`
- missing tables: `none`
- table status message: `All MVP Jobs tables are present.`

## After Partial Failure

Expected state:

- schema status: `partial`
- installed schema version: `not set` or prior successful version
- missing tables: list remaining missing tables
- last schema error visible
- retry should be possible

---

# Rollback / No-Delete Rules

No automatic rollback deletes.

Forbidden:

- `DROP TABLE`
- `TRUNCATE TABLE`
- deleting job rows
- deleting employer rows
- deleting membership rows
- deleting term assignment rows
- deleting event rows
- deleting signal rows
- deleting Core Terms data
- deleting WordPress users

Deactivation:

- does not delete tables
- does not delete options
- does not modify Core Terms

Uninstall:

- remains unimplemented until a written retention policy exists

Manual reset:

- requires explicit user instruction
- must be separate from normal installer/upgrade flow

---

# Test Commands

Read current plugin list:

```bash
ddev wp plugin list --path=wordpress
```

Lint touched plugin PHP:

```bash
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/tnet-jobs.php
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs.php
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs-schema.php
ddev exec php -l wordpress/wp-content/plugins/tnet-jobs/admin/class-tnet-jobs-admin.php
```

Read health output:

```bash
ddev wp eval --path=wordpress 'echo wp_json_encode(TNet_Jobs::health(), JSON_PRETTY_PRINT);'
```

Read table existence manually:

```bash
ddev mysql -e "SHOW TABLES LIKE 'wp_tnet_jobs%';"
```

After install implementation only, verify option:

```bash
ddev wp option get tnet_jobs_schema_version --path=wordpress
```

Do not run destructive database commands as tests.

---

# Implementation Acceptance Criteria

The future installer implementation is acceptable when:

- it creates exactly six MVP tables
- it creates no reserved future tables
- it writes no Core Terms data
- it writes `tnet_jobs_schema_version` only after successful verification
- it is idempotent
- it reports partial failure without deleting data
- it preserves archive/delete safety
- health output changes from `missing` to `installed` only when all six tables exist
- PHP lint passes
- no ATS/resume/candidate-search/billing/promotion schema exists
