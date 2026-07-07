# Teachers.Net Jobs — J1 Entity Model Acceptance v0.1

Status: Accepted planning checkpoint
Purpose: Close enough J1 decisions to proceed to relationship modeling and table design without committing to implementation details prematurely.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# J1 Scope

J1 accepts the durable Jobs entity model and the minimum defaults needed before schema design.

J1 does not create:

- plugin code
- database tables
- migrations
- admin UI
- public routes
- Core Terms changes

---

# Accepted Entities

## Job

Owner:
Jobs plugin

Purpose:
Represents one education-related opportunity.

Identity:

- internal `job_id`
- public `job_slug`

Lifecycle:

draft -> submitted -> approved -> published -> closed -> expired -> archived

Rules:

- Published jobs are editable.
- Published edits create job events.
- Jobs are archived rather than hard-deleted.
- Jobs launch with external apply.

## Employer

Owner:
Jobs plugin

Purpose:
Represents an organization or posting entity.

Identity:

- internal `employer_id`
- public/internal slug may be added when needed

Rules:

- Employer is first-class.
- Employer is not a Core Term.
- Employer records are archived rather than hard-deleted.
- Employer hierarchy is reserved for later.

## Employer Membership

Owner:
Jobs plugin

Purpose:
Connects WordPress users to employers.

Identity:

- internal `membership_id`

Rules:

- Users may belong to multiple employers.
- Employer-scoped authorization belongs here and in Jobs capabilities.
- Core Terms must not be used for permissions.

## Job Term Assignment

Owner:
Jobs plugin

Source:
Core Terms

Purpose:
Associates jobs with classification terms such as region, grade, curriculum, role, or audience.

Rules:

- Jobs consumes Core Terms through public APIs/hooks.
- Jobs does not mutate Core Terms internals.
- Assignments store references first; historical snapshots are reserved.

## Job Event

Owner:
Jobs plugin

Purpose:
Records meaningful lifecycle and activity changes.

Rules:

- Append-oriented.
- Used for audit and future analytics.
- Counters should be derived from events instead of stored directly on jobs.

## Job Visibility

Owner:
Jobs plugin

Purpose:
Represents exposure and presentation state.

Rules:

- Reserved for MVP compatibility.
- Promotion and billing remain outside Jobs core.
- Visibility must not become commerce state.

## Apply Method

Owner:
Jobs plugin

Purpose:
Defines how a candidate leaves or enters the apply flow.

MVP:

- `external_url`

Reserved:

- `tracked_external`
- `confirmed_external`
- `internal_apply`

---

# Accepted J1 Defaults

## Employer Attachment

Default:
Employer attachment is optional at initial job creation.

Implications:

- Schema should allow a draft job without `employer_id`.
- The model should preserve a path to require employer attachment before publication or after moderation.
- Recruiter ownership still depends on WordPress user identity and Jobs-owned authorization, not Core Terms.

## Core Term Rendering

Default:
Resolve Core Terms live for MVP.

Implications:

- `job_term_assignments` should store Core Terms identifiers.
- Do not duplicate term labels into the primary jobs table.
- Reserve a future snapshot mechanism if historical rendering requires it.

## Expired Job Visibility

Default:
Expired jobs may remain publicly visible as read-only records.

Implications:

- Expiration changes availability, not identity.
- Expired jobs should not appear as active opportunities in default search.
- Routing should be able to render an expired state.

## Job Duplication

Default:
Reserve duplication, but do not implement it in MVP.

Preferred future behavior:
Clone metadata into a new draft, not lifecycle events or analytics.

## Event Retention

Default:
Keep job events append-oriented with no MVP expiration rule.

Implications:

- Avoid destructive event migrations.
- Aggregation/archival can be added later.

## Search Architecture

Default:
SQL-first search for MVP.

Implications:

- Index for keyword, status, recency, employment type, employer, and term joins.
- Do not introduce an external search service in MVP.
- Leave room for a future search abstraction.

---

# Still Deferred After J1

- ATS
- internal applications
- resume upload
- candidate profiles
- candidate search
- interviews
- offers
- hires
- billing
- promotions
- notifications
- recruiter subscriptions

---

# J1 Exit Criteria

J1 is complete when:

- entity ownership is accepted
- lifecycle defaults are accepted
- schema guardrails are accepted
- MVP scope is accepted
- unresolved non-J1 decisions remain documented

After J1, the next safe task is J2 relationship modeling.
