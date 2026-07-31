# Teachers.Net Job Center V1 Product Definition

**Status:** Canonical Product Definition

**Purpose**

Define the complete functional scope of the Teachers.Net Job Center Version 1.

This document exists to prevent feature creep, guide implementation priorities, and provide a clear definition of "launch ready."

---

# Product Vision

Teachers.Net Job Center is not intended to compete by having more features.

It competes by:

- being easier to use
- serving teachers specifically
- leveraging Teachers.Net's community
- providing excellent search
- delivering relevant Job Alerts
- presenting trustworthy opportunities

Version 1 should be intentionally focused.

---

# Core Objectives

Version 1 succeeds if it allows:

- teachers to quickly discover relevant teaching jobs
- recruiters to publish quality teaching jobs easily
- administrators to moderate content efficiently
- Teachers.Net to generate recurring traffic through Job Alerts

Everything else is secondary.

---

# Product Pillars

## 1. Discover

Teachers can:

- Browse jobs
- Search jobs
- Filter results
- Sort results
- View job details

---

## 2. Apply

Teachers can:

- Visit employer application page
- Apply externally
- Save interesting jobs

Teachers.Net may present curated/imported jobs alongside employer-posted jobs,
but all public listings use the same Job Center entity and lifecycle. Source and
application behavior must remain truthful; a listing must not imply the employer
posted it, endorsed Teachers.Net, or receives an internal application unless
that is true.

Version 1 does not manage applications internally.

---

## 3. Return

Teachers can:

- Create Job Alerts
- Save searches
- Return through email notifications

Recurring engagement is a primary business objective.

---

# Teacher Capabilities

Version 1 includes:

✓ Browse Jobs

✓ Search Jobs

✓ Filter Results

✓ Sort Results

✓ View Job Detail

✓ Save Job

✓ Create Job Alert

✓ Manage Job Alerts

✓ Apply using employer instructions

---

# Recruiter Capabilities

Version 1 includes:

✓ Register Employer

✓ Employer Profile

✓ Create Job

✓ Edit Job

✓ Preview Job

✓ Submit Job

✓ Renew Job

✓ Expire Job

✓ View Active Listings

---

# Administrator Capabilities

Version 1 includes:

✓ Moderation Queue

✓ Approve Jobs

✓ Return to Draft

✓ Archive Jobs

✓ Basic Analytics

---

# Job Posting Model

Each job contains structured data where it directly improves search or filtering.

## Structured Fields

Job Title

Employer

School (optional)

Employment Type

Salary

Location

Grade Level(s)

Subject(s)

Posted Date

Expiration Date

Remote / Hybrid / On-site

Application URL or Email

---

## Rich Text Fields

Job Description

Requirements / Qualifications

Version 1 intentionally keeps Requirements as a single free-form field.

---

# Location Model

Version 1 should support:

Address

City

State

ZIP Code

Latitude

Longitude

Location Type

- On-site
- Hybrid
- Remote
- District-wide

Public display should normally use:

City, State

Address exists primarily to prepare for future proximity search and geocoding.

Distance Search is available in Advanced Search and Advanced Browse using local
stored coordinates. Automatic job geocoding and operational repair remain V1
readiness work; maps, commute-time routing, and travel-time search are deferred.

---

# Salary

Version 1 encourages salary disclosure.

Support:

Single Salary

Salary Range

Hourly

Contract

Negotiable

Salary is stored and displayed in Version 1.

Salary matching and salary filtering are deferred to Version 2.

---

# Search

Teachers should be able to search by:

Keyword

Location

Grade Level

Subject

Employment Type

Remote

Version 1 supports Distance Search in Advanced Search and Advanced Browse when
the origin and job coordinates are available. Public radius queries use local
stored coordinates only. Automatic employer-job geocoding, independent typed
origin resolution, and operational coordinate repair remain V1 readiness work;
salary matching/filtering remains deferred.

---

# Job Alerts

Job Alerts are considered a core feature.

Version 1 Job Alerts include:

Keyword

Location text storage, where available from the search context

Grade Level

Subject

Employment Type

Create from current search context

Manage alerts

Active/inactive status

Scheduled daily email delivery

Email manage/pause path

Version 1 Job Alerts do not include:

Automatic geocoding and geocoding repair

Salary matching

Notification center

Advanced frequency preferences

Per-job match history or dedupe tracking

---

# Design System

Version 1 uses:

docs/design-system-v1.md

All visual implementation should follow that document.

---

# Shared Components

The following components are intended for reuse throughout Teachers.Net.

Header

Hero

Search Panel

Filter Toolbar

Results Table

Right Rail

Cards

Footer

Icons

Typography

Spacing System

---

# Explicitly Out of Scope

The following are intentionally excluded from Version 1.

Candidate messaging

Resume database

Employer reviews

School reviews

District profiles

Maps, commute-time routing, and travel-time search

Salary matching and salary filtering

Benefits filtering

Credential filtering

Certification filtering

AI recommendations

Internal application tracking

Internal messaging

Social networking features

Recruiter CRM

Interview scheduling

Analytics dashboards beyond basic reporting

Notification center and bell alerts

Advanced Job Alert preferences

Anything requiring substantial additional workflow.

---

# Product Principles

Every Version 1 feature should satisfy at least one of the following:

Acquire users.

Help teachers find jobs faster.

Help recruiters publish jobs faster.

Increase recurring traffic.

Reduce moderation effort.

Improve platform quality.

If a feature satisfies none of these, it belongs in a future version.

---

# Definition of Launch Ready

Version 1 is considered launch ready when:

✓ Core teacher workflow is complete.

✓ Core recruiter workflow is complete.

✓ Moderation workflow is complete.

✓ Job Alerts function.

✓ Search performs well.

✓ Responsive layouts function.

✓ Design System v1 implemented.

✓ Performance acceptable.

✓ Basic accessibility verified.

✓ A controlled real-job pilot has verified truthful provenance, external
application routing, source reconciliation/expiration behavior, validated
employer association, and a verified employer-claim path before bulk loading.

Everything beyond this becomes Version 2.

---

# Guiding Principle

Version 1 is intentionally small.

Every feature should earn its place by making Teachers.Net more useful for teachers or recruiters.

Avoid complexity until real user behavior demonstrates its necessity.

# Version 1 Decision Rule

When considering any new feature, ask:

1. Is this essential to launching a successful teaching job board?

2. Can the same objective be accomplished with an existing workflow?

3. Does this introduce new concepts that require users to learn additional behavior?

4. Can this reasonably wait until Version 2 without harming launch success?

If the answer to Question 4 is "yes," defer it to a future version.

Version 1 favors completeness over comprehensiveness.
