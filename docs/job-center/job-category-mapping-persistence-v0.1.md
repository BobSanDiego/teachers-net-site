# Job Category Mapping Persistence v0.1

## 1. Problem

The Job Categories admin screen currently discovers Core Terms sources read-only. It can show the `teachers-net` framework, available axes, term counts, and suggested form behavior, but it cannot yet save an admin-curated job form.

Admins need a Jobs-owned way to decide which Core Terms sources appear in the job posting flow, how they are labeled, whether they are required, and in what order they appear.

## 2. Ownership

Core Terms owns:

- frameworks
- axes
- terms
- term hierarchy
- term identity and stable IDs
- term lifecycle

Jobs owns:

- job form field configuration
- display order
- Jobs-side labels and help text
- required/active rules
- stale mapping state
- snapshots used to warn admins when Core Terms changes

Jobs must not write to Core Terms. Jobs may store references to Core Terms sources and selected term IDs in Jobs-owned tables.

## 3. Proposed Table: `tnet_jobs_form_fields`

Purpose: store Jobs-owned configuration for Core Terms-backed fields in the job posting flow.

Owner: Teachers.Net Jobs.

Lifecycle: created by the Jobs schema installer in a future schema version. Rows are soft-archived, not hard-deleted by normal app behavior.

Candidate columns:

| Column | Purpose |
|---|---|
| `field_id` | Primary key. |
| `field_key` | Jobs-owned stable key, for example `grade_level`, `subject_area`, or `location`. |
| `framework_slug` | Core Terms framework slug, expected MVP default `teachers-net`. |
| `core_terms_source_id` | Stable Core Terms source identifier, preferably UUID when available. |
| `core_terms_source_type` | Source type such as `axis` or `group`. |
| `label` | Jobs-side field label shown in job forms. |
| `help_text` | Optional Jobs-side helper text. |
| `behavior` | Field behavior: `single_select` or `multi_select`. |
| `is_required` | Whether the field is required in the Jobs form. |
| `is_active` | Whether the field appears in the Jobs form. |
| `display_order` | Admin-controlled form order. |
| `stale_status` | Review state for Core Terms drift. |
| `source_snapshot_json` | Snapshot of Core Terms source name/slug/path/count/version facts for review warnings. |
| `created_at` | Row creation timestamp. |
| `updated_at` | Last update timestamp. |
| `archived_at` | Soft archive timestamp. |

Deferred implementation details:

- exact SQL column types
- indexes
- unique constraints
- schema version number
- whether `core_terms_source_id` stores UUID only or can store slug fallback
- whether `source_snapshot_json` stores term counts only or selected sample/source metadata

## 4. Behavior Values

Allowed `behavior` values:

- `single_select`
- `multi_select`

Initial behavior should stay intentionally small. Text inputs, freeform locations, autocomplete, and hierarchical pickers are reserved for later UX work.

## 5. Stale Status Values

Allowed `stale_status` values:

- `current`
- `needs_review`
- `missing_source`
- `archived_source`

Meaning:

- `current`: stored snapshot still matches the live Core Terms source closely enough for normal use.
- `needs_review`: Core Terms source exists but changed in a way an admin should review.
- `missing_source`: referenced Core Terms source cannot be found.
- `archived_source`: referenced Core Terms source appears archived or inactive.

The stale detection engine is not part of this design milestone.

## 6. MVP Defaults

When mapping persistence is implemented, the default suggested fields should be:

| Core Terms source | Field key | Behavior | Required |
|---|---|---|---|
| Grade Level | `grade_level` | `multi_select` | yes |
| Subject Area | `subject_area` | `multi_select` | yes |
| Location | `location` | `single_select` | yes |

These defaults come from the current read-only Job Categories UX and the local `teachers-net` Core Terms seed.

## 7. Migration Strategy

Future implementation should:

- add a Jobs schema version bump
- create `tnet_jobs_form_fields` through the Jobs schema installer
- keep the migration idempotent
- avoid Core Terms schema changes
- avoid Core Terms data writes
- preserve existing Jobs tables and rows
- avoid deleting mapping rows on rollback

The table should not be added until the repository/service shape is accepted.

## 8. Future Repository And Service

Future repository:

- `TNet_Jobs_Form_Field_Repository`

Likely responsibilities:

- insert mapping row
- update mapping row
- get mapping by ID
- get mapping by `field_key`
- list active mappings ordered by `display_order`
- list all mappings for admin
- archive mapping row

Future service:

- `TNet_Jobs_Form_Field_Service`

Likely responsibilities:

- validate field keys
- validate behavior values
- validate stale status values
- normalize labels/help text
- generate default mappings from Core Terms discovery
- compare live Core Terms source data with stored snapshots
- keep admin behavior policy out of the repository

The service may read Core Terms through `TNet_Jobs_Core_Terms_Adapter`, but must not mutate Core Terms.

## 9. Non-Goals

This design does not implement:

- recruiter-facing job form
- public frontend
- REST endpoints
- Core Terms mutation
- stale detection engine
- import/export
- schema changes
- SQL
- mapping repository or service code
- job term assignment behavior

This design only defines the likely Jobs-owned persistence model for a later milestone.
