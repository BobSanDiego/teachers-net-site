# Teachers.Net Jobs — Relationship Model v0.1

Status: Working Baseline
Purpose: Define ownership, cardinality, lifecycle coupling, and boundary rules between Jobs entities before table design.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Scope

This document defines relationships for the Teachers.Net Jobs plugin.

It does not define:

- database columns
- migrations
- plugin code
- admin screens
- public routes
- Core Terms internals
- commerce, billing, ATS, or candidate inventory behavior

---

# Relationship Summary

## WordPress User -> Employer Membership

Cardinality:

- one WordPress user may have many employer memberships
- one employer membership belongs to one WordPress user

Owner:
Jobs plugin owns the membership record.

Authentication:
WordPress owns the user identity and login state.

Authorization:
Jobs decides what the user may do for a given employer.

Rules:

- Users may hold multiple Jobs identities.
- Do not model Jobs authorization as a single ranked WordPress role.
- Do not use Core Terms for permissions.
- Employer-scoped permissions should come from Jobs-owned membership state plus WordPress capabilities.

Default membership roles:

- `owner`
- `admin`
- `poster`
- `viewer` reserved

MVP behavior:

- Any authenticated job poster may create or request an employer record.
- New employer records begin unverified unless an admin verifies them.
- Automatic trust elevation is not part of MVP.

Reserved future behavior:

- trusted recruiter state
- trusted employer state
- domain/email validation
- repeated-approved-posting trust rules
- employer verification workflows

---

# Employer -> Jobs

Cardinality:

- one employer may have many jobs
- one job may belong to zero or one employer while drafting
- one published job should belong to one employer

Owner:
Jobs plugin owns both employers and jobs.

Rules:

- Employer is first-class.
- Employer is not a Core Term.
- Employer attachment is optional at initial job creation.
- Employer attachment should be required before publication.
- Employer records should be archived rather than hard-deleted.
- Jobs should be archived rather than hard-deleted.

Implications:

- Draft workflows may begin before complete employer setup.
- Publication/moderation should ensure employer ownership is clear.
- Future employer hierarchy must not be forced into the initial jobs table.

Reserved future behavior:

- district/school hierarchy
- employer aliases
- merged employers
- verified employer pages
- billing ownership

---

# Employer -> Employer Memberships

Cardinality:

- one employer may have many memberships
- one membership belongs to one employer

Owner:
Jobs plugin.

Rules:

- Membership connects a WordPress user to an employer.
- Membership should be employer-scoped, not global.
- Archiving an employer should prevent new job publication under that employer but should not erase membership history.
- Removing a membership should not delete jobs or events.

Reserved future behavior:

- invited users
- invitation expiration
- membership audit events
- ownership transfer
- verification-specific roles

---

# Job -> Core Terms

Cardinality:

- one job may have many Core Term assignments
- one Core Term may classify many jobs

Owner:

- Jobs owns the assignment record.
- Core Terms owns the term hierarchy, term identity, and classification APIs.

Rules:

- Jobs consumes Core Terms through public APIs/hooks.
- Jobs must not write directly into Core Terms internals.
- Jobs must not duplicate Core Term labels into the primary jobs table.
- Core Terms resolve live for MVP.
- Historical term snapshots are reserved, not MVP.

Publication requirements:

- Require at least one Region/location term before publication.
- Require a job classification, role, or category term only if that axis exists in Core Terms.
- If the role/category axis does not yet exist, warn but do not block publication.

Search implications:

- Active search should support filtering by Region/location terms.
- Additional term filters should follow Core Terms axes as they exist.
- SQL-first search is the MVP default.

Reserved future behavior:

- term label snapshots
- term deprecation handling
- term-axis requirements by job type
- richer matching against candidate profiles

---

# Job -> Job Events

Cardinality:

- one job has many events
- one event belongs to one job

Owner:
Jobs plugin.

Rules:

- Events are append-oriented.
- Events should record meaningful lifecycle and business actions.
- Events should not be hard-deleted during normal operation.
- Analytics counters should be derived from events rather than stored directly on jobs.

Expected MVP events:

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
- `viewed` reserved or lightweight

Lifecycle coupling:

- A lifecycle state change should create an event.
- Published edits should create an event.
- Closing, expiration, and archiving should create events.

Reserved future behavior:

- event actor metadata
- event reason codes
- moderation comments
- notification triggers
- event rollups
- event archival

---

# Job -> Job Visibility

Cardinality:

- one job may have many visibility records over time
- one visibility record belongs to one job

Owner:
Jobs plugin.

Rules:

- Visibility describes presentation/exposure state.
- Visibility is not billing.
- Visibility is not promotion purchase state.
- MVP may reserve the relationship without implementing paid promotion.

MVP behavior:

- Standard published jobs are visible in active search.
- Expired jobs do not appear in default active search.
- Expired jobs may remain publicly visible by direct URL as read-only expired records.
- Closed jobs should not appear as active opportunities.
- Archived jobs should not appear publicly by default.

Reserved future behavior:

- featured placement
- boosted listings
- homepage exposure
- commerce-controlled promotion
- admin-curated visibility

---

# Job -> Apply Method

Cardinality:

- one job has one MVP apply method
- future jobs may support multiple apply-related records if internal apply is added

Owner:
Jobs plugin.

MVP apply method:

- `external_url`

Rules:

- Jobs owns the job and apply destination.
- Jobs does not own an application record in MVP.
- Apply clicks may create events.
- External apply does not imply candidate tracking.

Reserved future apply methods:

- `tracked_external`
- `confirmed_external`
- `internal_apply`

Reserved future relationships:

- Job -> Applications
- Candidate Profile -> Applications
- Application -> Application Events

---

# Job Duplication

MVP:
Not implemented.

Reserved future default:
Clone job content and classification into a new draft.

Do not clone:

- lifecycle state
- events
- analytics
- publication dates
- expiration state
- apply-click history

---

# Trust And Verification

MVP default:
No automatic trust elevation.

Rules:

- First publication requires moderation.
- Later trusted recruiter/employer behavior is reserved.
- Trust should not be inferred from Core Terms.
- Trust should not be modeled as billing state.

Reserved future trust inputs:

- repeated approved postings
- employer verification
- manual admin review
- organization/domain validation

---

# Archive And Delete Boundaries

General rule:
Archive business records rather than hard-delete them.

Jobs:

- archive instead of delete
- preserve events
- preserve term assignment history unless a future retention policy says otherwise

Employers:

- archive instead of delete
- prevent future publication when inactive/archived
- preserve historical job ownership

Memberships:

- deactivation/removal should not erase historical job or event records

Core Terms:

- Core Terms lifecycle is external to Jobs
- Jobs should tolerate renamed or deprecated terms through API-level handling

---

# Reserved Future Relationships

## Job -> Applications

Deferred.

Potential cardinality:

- one job has many applications
- one application belongs to one job

## Candidate Profile -> Applications

Deferred.

Potential cardinality:

- one candidate profile has many applications
- one application belongs to one candidate profile

## Candidate Profile -> Resumes/Documents

Deferred.

Owner:
Future Profiles/Candidate Inventory.

## Employer -> Commerce Entitlements

Deferred.

Owner:
Future Commerce.

Rules:

- Commerce must not mutate jobs directly.
- Commerce may affect visibility or entitlement through APIs/events later.

---

# J2 Acceptance Criteria

J2 is ready to support table design when:

- employer/job ownership is accepted
- employer membership cardinality is accepted
- publication requirements are accepted
- Core Terms relationship boundaries are accepted
- event relationship and retention defaults are accepted
- visibility relationship defaults are accepted
- future application/candidate/commerce relationships remain deferred

After J2, the next safe task is J3 table design.
