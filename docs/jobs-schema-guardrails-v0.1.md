# docs/jobs-schema-guardrails-v0.1.md

# Teachers.Net Jobs — Schema Guardrails v0.1

Status: Accepted Constraints Before Database Design

Purpose:
Prevent premature schema coupling and preserve future extensibility.

This document defines constraints before tables are designed.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Storage Direction

Accepted:

Custom tables.

Rejected as primary storage:

- WordPress posts
- WordPress taxonomies
- CPT-first architecture
- postmeta-first architecture

WordPress remains:

- authentication
- sessions
- admin shell
- capability system

Jobs owns business data.

---

# Jobs Table Philosophy

Jobs should represent:

one opportunity

Jobs should NOT become:

- employer profile
- ATS
- analytics warehouse
- billing record
- application system
- resume container
- reporting cache

---

# What MUST NOT Live On jobs

Do not store:

candidate_count
resume_count
application_count
billing_state
promotion_state
visibility_state
candidate_profiles
analytics rollups
recruiter plans
permissions
candidate inventory
term labels

Reason:
These evolve independently.

---

# What Belongs In Separate Tables

## employers

Owns:

organization identity

Examples:

name
slug
verification
status

---

## employer_memberships

Owns:

user → employer relationship

Examples:

user_id
employer_id
membership_role

---

## job_term_assignments

Owns:

job classification

Source:

Core Terms

Examples:

job_id
term_id

---

## job_events

Owns:

historical activity

Examples:

created
published
edited
closed

---

## job_visibility

Owns:

presentation state

Examples:

featured
boosted
homepage

Reserved.

---

## job_views

Owns:

analytics signals

Examples:

viewed
apply_clicked

Derived.

---

# What Belongs In Core Terms

Core Terms owns:

classification

Examples:

Region
Grade
Curriculum
Role
Audience

Core Terms does NOT own:

permissions
recruiters
plans
visibility

Jobs consumes Core Terms APIs.

Jobs must never mutate Core Terms.

---

# What Belongs In Future Commerce

Commerce owns:

plans
billing
promotions
receipts
resume search entitlements

Commerce must not mutate jobs directly.

---

# What Belongs In Future Profiles

Profiles owns:

candidate profile
resume
visibility
preferences
availability

Profiles does not own jobs.

---

# Cardinality Assumptions

Employer:

1 → many Jobs

User:

many → many Employers

Job:

many → many Terms

Job:

1 → many Events

Job:

1 → many Visibility records (future)

Candidate:

many → many Applications (future)

---

# Search / Index Assumptions

Expected searches:

keyword
region
grade
curriculum
employment type
recent jobs

Expected non-search fields:

description body
analytics
billing

Indexes should favor:

filtering
sorting
joins

Avoid:

wide denormalized tables

---

# Analytics Philosophy

Preferred:

events → derive counters

Avoid:

incrementing business records.

Example:

GOOD

view event
→ aggregate

BAD

jobs.view_count++

---

# Migration Expectations

Jobs schema should assume:

new entities appear later

Expected future additions:

applications
candidate_profiles
candidate_documents
interviews
offers
hire_events

Rules:

- additive migrations preferred
- avoid destructive migrations
- preserve identifiers

---

# Versioning Expectations

Schema:
versioned

Data:
migratable

Events:
append-oriented

No rewrite migrations unless unavoidable.

---

# Risks To Avoid

God table
schema coupling
permission leakage
billing contamination
term duplication
analytics overbuilding
premature ATS
CPT lock-in
term ownership drift

---

# Accepted Decisions

- custom tables
- employer first
- Core Terms owns classification
- Jobs owns workflows
- analytics derived from events

---

# Open Questions

- snapshot term labels?
- event retention?
- search architecture?
- archive retention?
- duplication strategy?
