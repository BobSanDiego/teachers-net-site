# Teachers.Net Jobs — MVP Scope v0.1

Status: Accepted Launch Scope

Purpose:
Define what must exist for first public release and explicitly exclude everything else.

Principle:

Launch small. Preserve growth.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Launch Objectives

The MVP should:

- allow jobs to exist
- allow jobs to be discovered
- allow recruiters to publish
- allow administrators to moderate
- preserve future growth

The MVP should NOT attempt:

- marketplace completion
- ATS
- resume systems
- recruiter subscriptions

---

# Candidate-Facing Capabilities (MVP)

Anonymous users:

- browse jobs
- search jobs
- filter jobs
- view job details

Authenticated users:

- all anonymous capabilities

Reserved:

save job
mark applied
alerts
candidate profiles

Excluded:

resume upload
candidate search
messaging

---

# Recruiter-Facing Capabilities (MVP)

Authenticated users:

- create job
- edit draft
- submit job
- close job
- duplicate job (reserved)

Publishing:

first publication requires moderation

Future possibility:

trusted recruiters

Excluded:

resume access
candidate search
internal applications
billing

---

# Admin Capabilities (MVP)

Admin can:

approve job
reject job
expire job
close job
archive job

Admin should not manually edit business data except moderation.

Reserved:

featured jobs
promotion
analytics

---

# Required Posting Fields

Minimum launch fields:

Title
Employer (optional initially)
Description
Region
Core Terms assignments
Employment Type
Apply URL
Expiration

Rules:

Classification must come from Core Terms.

Apply URL must support external workflow.

---

# Optional / Deferred Fields

Not required for launch:

Salary
School
District
Start Date
Certification
Remote
Logo
Apply Email
Featured
Attachments

Reserve compatibility only.

---

# Posting Workflow

Authenticated User

↓

Create Draft

↓

Submit

↓

Admin Review

↓

Publish

↓

Close / Expire

↓

Archive

---

# External Apply Behavior

Accepted launch approach:

external_url

Behavior:

user clicks Apply

↓

redirect to destination

Jobs owns:

job

Jobs does not own:

application

Reserved future modes:

tracked_external
confirmed_external
internal_apply

---

# Lifecycle States

draft

submitted

approved

published

closed

expired

archived

Rules:

Published jobs remain editable.

Edits should create events.

Archive preferred over deletion.

---

# Permissions Assumptions

Authentication:

WordPress

Authorization:

Jobs

Classification:

Core Terms

Rules:

Users may hold multiple identities.

Examples:

candidate
recruiter
employer_admin
moderator

Rejected:

single ranked role

Preferred:

capability model

Examples:

can_post_job
can_close_job
can_manage_jobs

---

# Analytics / Signals (Reserve Only)

Track conceptually:

views
apply_clicks
publish_count
job_age

Do NOT overbuild.

Avoid:

conversion
recruiter dashboards
candidate funnels

Signals should derive from events later.

---

# Explicit Exclusions

No ATS

No resumes

No candidate search

No recruiter subscriptions

No payments

No interviews

No hiring workflow

No messaging

No notifications

No application storage

---

# Success Criteria

A recruiter can:

create

submit

publish

close

A candidate can:

discover

understand

leave to apply

System remains extensible.

---

# Accepted Decisions

- external apply launches
- moderation first
- custom tables
- Core Terms classification
- employer optional initially

---

# Open Questions

- should jobs expire automatically?
- should expired jobs remain public?
- should jobs be cloneable?
- should first recruiter approval elevate trust?
