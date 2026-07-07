# Teachers.Net Jobs — Domain Model v0.1

Status: Working Baseline
Purpose: Define business objects and ownership before schema or implementation.

---

# Architectural Principles

Primary rule:

Terms classify.
Jobs authorizes.
WordPress authenticates.

Supporting rules:

- Core Terms owns classification.
- Jobs owns marketplace workflows.
- WordPress owns identity and authentication.
- Jobs must remain independent from billing, ATS, and resume inventory.
- Future systems must attach through APIs and events rather than direct table mutation.

---

# Product Goals

Jobs should:

- Allow authenticated users to publish education-related opportunities.
- Allow discovery through search and classification.
- Support future employer workflows.
- Support future candidate workflows.
- Preserve historical integrity.
- Scale from MVP to recruiter tooling.

---

# Non-Goals (v0.x)

Jobs does not initially provide:

- ATS
- Resume storage
- Candidate inventory
- Candidate search
- Internal applications
- Recruiter subscriptions
- Messaging
- Interview workflow
- Hiring workflow
- Employer billing

---

# Entity Catalog

## Job

Purpose:
Represents a published opportunity.

Owner:
Jobs plugin

Consumes:

- Employer
- WordPress user
- Core Terms

Identity:
Internal:
job_id

Public:
job_slug

Lifecycle:

draft
→ submitted
→ approved
→ published
→ closed
→ expired
→ archived

Mutability:
Editable after publish.

Published edits should create events.

Delete:
Soft archive only.

Reserved future risks:

- version history
- duplication
- localization
- scheduling

---

## Employer

Purpose:
Represents an organization posting jobs.

Owner:
Jobs plugin

Identity:
employer_id

Examples:

- School
- District
- Recruiter
- Agency

Lifecycle:

created
→ verified
→ active
→ inactive
→ archived

Mutability:
Editable.

Delete:
Archive preferred.

Reserved:
district
school hierarchy

---

## Employer Membership

Purpose:
Connect users to employers.

Owner:
Jobs plugin

Identity:
membership_id

Relationship:

Employer
1→many
Membership

Membership
many→1
User

Rules:
User may belong to multiple employers.

Reserved:
verification
billing ownership

---

## Job Term Assignment

Purpose:
Attach classification.

Owner:
Jobs plugin

Source:
Core Terms

Examples:

- Region
- Grade
- Curriculum
- Role

Rules:
Jobs never modify Core Terms.

Assignments should preserve historical integrity.

Reserved:
term snapshots

---

## Job Event

Purpose:
Record meaningful actions.

Owner:
Jobs plugin

Examples:
created
submitted
published
edited
closed
expired

Rules:
Append-oriented.

Reserved:
analytics
audit
notifications

---

## Job Visibility

Purpose:
Represent exposure rules.

Owner:
Jobs plugin

Examples:
standard
featured
boosted

Not implemented initially.

Reserved:
promotion
commerce

---

# Ownership Summary

WordPress:

- user
- auth
- sessions

Core Terms:

- classification
- hierarchy
- assignment infrastructure

Jobs:

- employer
- jobs
- workflows
- lifecycle
- events

Future Commerce:

- plans
- billing
- promotion

Future Profiles:

- resumes
- candidate inventory
- visibility

---

# Accepted Decisions

- Employer is first-class.
- Jobs use custom tables.
- Published jobs remain editable.
- Jobs attach to Core Terms.
- Jobs launch with external apply.

---

# Open Questions

- Require employer at creation?
- Snapshot terms?
- Public expired jobs?
- Clone jobs?
- Job retention period?
