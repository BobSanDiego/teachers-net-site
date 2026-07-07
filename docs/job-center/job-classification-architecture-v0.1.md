# Teachers.Net Jobs - Job Classification Architecture v0.1

Status: Planning Baseline
Purpose: Define how Teachers.Net Jobs will consume Core Terms for job classification and job form generation before implementation.

This is documentation only. It is not plugin code, not schema, not a migration, and not an admin UI.

---

# 1. Goals

Teachers.Net Jobs should consume Core Terms as the classification source for jobs.

Core Terms remains the source of truth for:

- term identity
- term hierarchy
- term labels
- term metadata
- stable term IDs
- classification APIs

Jobs must not write to Core Terms.

Jobs should not expose the full raw Core Terms tree to recruiters. Recruiters should see curated job-form fields that are mapped by admins from Core Terms into the Jobs posting flow.

The classification flow should support:

- admin-controlled job form fields
- required classification rules
- single-select and multi-select fields
- later employer-facing job forms
- Jobs-owned storage of selected Core Terms IDs
- stale mapping warnings when Core Terms changes

---

# 2. Data Ownership

## Core Terms Owns

Core Terms owns the classification library:

- terms
- term hierarchy
- term metadata
- term identity and stable IDs
- term compilation
- Core Terms APIs and hooks

Core Terms does not own:

- Jobs form layout
- Jobs publication rules
- recruiter workflows
- employer-facing field labels
- job-term assignment rows in Jobs tables
- Jobs-specific required field rules

## Jobs Owns

Jobs owns the way classification appears and behaves inside Jobs workflows:

- field configuration
- field display order
- field section labels
- Jobs-side display labels or overrides
- field behavior such as single select or multi select
- required rules for job posting
- active/inactive state for mapped fields
- selected Core Terms IDs for each job
- stale/needs-review state for mappings

Jobs may store Core Terms IDs in Jobs-owned tables. Jobs must not modify Core Terms records.

---

# 3. Proposed Storage

The current MVP schema already includes `tnet_jobs_terms`, the Jobs-owned bridge for selected Core Terms IDs attached to jobs.

Candidate future storage should be Jobs-owned and separate from Core Terms.

## `tnet_jobs_terms`

Purpose:
Store selected Core Terms IDs for a job.

Expected responsibility:

- job ID
- Core Terms term ID
- optional Core Terms group/axis ID if exposed by Core Terms APIs
- assignment source
- created timestamp

Rules:

- stores references to Core Terms IDs only
- does not duplicate full Core Terms hierarchy
- does not write back to Core Terms
- can be rebuilt or reviewed if mappings change

## `tnet_jobs_form_fields`

Purpose:
Store admin-defined classification fields shown in job forms.

Candidate fields:

- field ID
- display order
- job form section label
- Core Terms source group or axis ID
- behavior: single select, multi select, or text input
- required flag
- active flag
- created/updated timestamps

Rules:

- owns form behavior, not Core Terms identity
- references Core Terms groups/axes/terms through stable IDs
- should allow inactive mappings without deleting historical meaning

## Mapping Overrides

Jobs may need Jobs-side display overrides where the employer-facing label should differ from the Core Terms label.

Candidate override fields:

- Jobs-side display label
- help text
- placeholder text
- ordering override
- field grouping/section label
- preserved label snapshot

Core Terms remains the source of truth. Overrides affect only how Jobs presents a configured field.

## Stale Mapping Detection

Jobs should be able to detect when mapped Core Terms sources have changed.

Possible signals:

- Core Terms label changed
- Core Terms hierarchy moved
- term archived
- term split or merged
- referenced term/group missing
- Core Terms stable ID is still present but metadata changed

Jobs should store enough snapshot data to warn admins that a mapping needs review without taking ownership of Core Terms.

---

# 4. Admin UX

Future screen:

`wp-admin -> Teachers.Net Jobs -> Job Categories`

The screen should configure how Core Terms appears in Jobs forms.

## Tab: Overview

Purpose:
Summarize the current classification setup.

May show:

- active mapped fields
- required fields
- stale mappings
- unmapped Core Terms groups
- recently changed Core Terms sources

## Tab: Map To Job Form

Purpose:
Let admins map Core Terms groups into curated Jobs form fields.

Expected columns:

- display order
- job form section label
- Core Terms source
- behavior: single select, multi select, or text input
- required
- active
- actions: edit/remove

Rules:

- removing should deactivate or archive a mapping, not destroy history
- Jobs stores mapping configuration only
- Core Terms records remain read-only from this screen

## Tab: Job Form Preview

Purpose:
Show how configured fields will appear to employers creating a job.

Preview should include:

- section labels
- field order
- required indicators
- single/multi select behavior
- inactive fields omitted
- stale fields visibly flagged for admins

## Tab: Settings

Purpose:
Configure classification behavior.

Candidate settings:

- required classification rules
- fallback behavior if Core Terms changes
- stale/changed term warnings
- whether Jobs-side display overrides are allowed
- whether inactive mappings remain visible to admins

---

# 5. Change Management

Core Terms will change over time. Jobs should treat those changes as external source changes and respond safely.

## Core Terms Rename

If a Core Terms label changes:

- Jobs should flag affected mappings as changed or needs review.
- Admin may accept the updated Core Terms label.
- Admin may preserve a Jobs-side display override.
- Existing job assignments should continue to point to the same stable Core Terms ID.

## Core Terms Archive

If a Core Terms term or group is archived:

- Jobs should flag mappings that reference it.
- Existing job assignments remain historically intact.
- New job forms should avoid offering archived options unless explicitly allowed.
- Admin should decide whether to deactivate or remap the Jobs field.

## Core Terms Move

If a term moves within the hierarchy:

- Jobs should flag mappings where hierarchy context matters.
- Jobs should not rewrite job assignments automatically.
- Admin review decides whether the mapped form field still points to the right source.

## Core Terms Split

If one term becomes multiple terms:

- Jobs should flag affected mappings and assignments as needing review.
- Jobs should not guess how old selections map to new terms.
- Admin may choose a replacement mapping for new job forms.

## Core Terms Merge

If multiple terms become one term:

- Jobs should flag affected mappings.
- Existing assignments should remain traceable where possible.
- Admin may accept the merged Core Terms target for future forms.

---

# 6. Recruiter Experience

Recruiters should see curated job-form fields, not the full raw Core Terms tree.

The form should use progressive disclosure:

- required classification fields first
- optional classification fields later
- advanced or less common categories behind secondary UI if needed

The recruiter-facing form should provide search assistance where useful:

- searchable select inputs for long term lists
- grouping by admin-defined form sections
- concise labels
- clear required indicators

The recruiter experience should optimize for posting a valid job, not browsing the entire classification system.

---

# 7. Proposed Implementation Milestones

## J17b - Job Term Assignment Repository

Implement low-level persistence for the Jobs-owned `tnet_jobs_terms` bridge.

Scope:

- repository only
- no Core Terms writes
- no admin UI
- no public frontend
- no REST endpoints

## J17c - Job Term Assignment Service

Add business-layer orchestration for assigning Core Terms IDs to jobs.

Scope:

- validate job IDs
- validate term IDs as positive integers
- use Jobs-owned bridge storage
- no Core Terms writes

## J18 - Admin Mapping UI

Create the first admin UI for mapping Core Terms sources to Jobs form fields.

Scope:

- `Teachers.Net Jobs -> Job Categories`
- read Core Terms sources through accepted public APIs
- store Jobs-owned mapping config
- show active/stale status

## J19 - Employer Job Form Uses Configured Mappings

Use configured mappings in the employer-facing job creation flow.

Scope:

- render curated fields
- enforce required mappings
- save selected Core Terms IDs to Jobs-owned bridge rows
- avoid exposing the raw Core Terms tree

---

# Non-Goals

This architecture does not implement:

- schema changes
- repository code
- service code
- admin UI
- public frontend
- REST endpoints
- Core Terms writes
- recruiter authorization
- publication workflow
