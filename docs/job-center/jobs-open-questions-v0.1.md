# Teachers.Net Jobs — Open Questions v0.1

Status: Deliberately Unresolved Decisions

Purpose:
Capture architectural decisions that remain open so future implementation does not accidentally lock the project into a direction.

Rule:

Accepted decisions belong in architecture documents.

Unresolved decisions belong here.

This document exists to prevent accidental schema commitments.

---

# Architectural Rule

Terms classify.
Jobs authorizes.
WordPress authenticates.

---

# Decision 1 — Employer Required At Creation?

Question:

Must every job belong to an Employer?

Why It Matters:

Determines onboarding complexity, ownership model, and recruiter workflows.

Options:

A — Employer required immediately

Pros:

- clean ownership
- recruiter identity

Cons:

- friction
- slower launch

B — Employer optional initially

Pros:

- faster launch
- easier testing

Cons:

- migration later

Recommended Default:

B

Impact:

Schema:
nullable employer_id

API:
optional employer attachment

MVP:
simpler

Status:

OPEN (recommended B)

---

# Decision 2 — Snapshot Core Terms?

Question:

Should jobs preserve historical term labels?

Why It Matters:

Core Terms evolves.

Jobs represent historical records.

Options:

A — Resolve terms live

Pros:
simple

Cons:
history changes

B — Snapshot labels

Pros:
stable rendering

Cons:
duplication

Recommended Default:

Start live.

Reserve snapshots.

Impact:

Schema:
term assignment strategy

API:
term rendering

Status:

OPEN

---

# Decision 3 — Published Jobs Editable?

Question:

Can published jobs change?

Why It Matters:

Recruiters frequently revise.

Options:

A — Immutable publish

Pros:
audit

Cons:
poor UX

B — Editable publish

Pros:
practical

Cons:
events needed

Recommended Default:

B

Impact:

Schema:
events

Status:

ACCEPTED

---

# Decision 4 — Public Expired Jobs?

Question:

Should expired jobs remain visible?

Why It Matters:

SEO
history
confusion

Options:

A — Remove

B — Keep read-only

C — Archive view

Recommended Default:

B

Impact:

routing
visibility

Status:

OPEN

---

# Decision 5 — Job Duplication?

Question:

Can recruiters clone jobs?

Why It Matters:

Hiring repeats.

Options:

A — No

B — Clone metadata only

C — Clone full draft

Recommended Default:

B

Impact:

workflow
events

Status:

OPEN

---

# Decision 6 — Automatic Recruiter Trust?

Question:

Should approval elevate recruiter privileges?

Why It Matters:

Moderation load

Options:

A — Always moderate

B — Trust after first approval

C — Employer verification

Recommended Default:

B

Impact:

permissions
future workflows

Status:

OPEN

---

# Decision 7 — Candidate Visibility Model?

Question:

How should future candidate discovery work?

Why It Matters:

Privacy

Options:

A — hidden

B — discoverable

C — active search

Recommended Default:

defer

Impact:

future profiles

Status:

DEFERRED

---

# Decision 8 — Resume Search Monetization?

Question:

Should recruiter access require payment?

Why It Matters:

privacy
business model

Options:

A — free

B — paid

C — verified + paid

Recommended Default:

C

Impact:

commerce
profiles

Status:

DEFERRED

---

# Decision 9 — Employer Verification?

Question:

How should recruiters become trusted?

Options:

email only

manual review

organization validation

Recommended Default:

manual review initially

Impact:

permissions
visibility

Status:

OPEN

---

# Decision 10 — Event Retention?

Question:

Should job events expire?

Options:

keep forever

rollup

archive

Recommended Default:

archive later

Impact:

analytics
storage

Status:

OPEN

---

# Decision 11 — Analytics Storage?

Question:

Store counters or derive?

Options:

counter columns

event pipeline

Recommended Default:

events → aggregate

Impact:

schema

Status:

ACCEPTED

---

# Decision 12 — Search Architecture?

Question:

How far should search scale?

Options:

SQL only

search abstraction

external index

Recommended Default:

SQL first

Impact:

indexing

Status:

OPEN

---

# Decision 13 — Multiple Jobs Identities?

Question:

Can users be candidate and recruiter?

Options:

single role

multi-identity

Recommended Default:

multi-identity

Impact:

permissions

Status:

ACCEPTED

---

# Decision 14 — Jobs Authorization Model?

Question:

How should access be granted?

Options:

Core Terms

WP roles

capabilities + membership

Recommended Default:

capabilities + membership

Impact:

security

Status:

ACCEPTED

---

# Deferred Areas (Explicitly Not Solved)

ATS

candidate inventory

resume upload

interviews

offers

hire tracking

billing

promotion

candidate search

notifications

---

# Exit Criteria For J1 Completion

Entity ownership accepted

Relationship model accepted

Schema guardrails accepted

MVP scope accepted

Open questions recorded

Only then begin schema design.
