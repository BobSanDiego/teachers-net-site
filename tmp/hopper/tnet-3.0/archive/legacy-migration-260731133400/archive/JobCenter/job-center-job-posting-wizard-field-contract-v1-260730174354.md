# Job Posting Wizard Field Contract v1

## Purpose

This contract defines the V1 Job Posting Wizard field inventory, step ownership,
conditional behavior, and explicit deferrals. The wizard should provide a fast,
clean interface and include only fields necessary for, or used by, V1.

Local repository documents are the durable source of truth. The approved JC053
Step 1 workbench states remain the visual authority for that step; this document
defines field architecture rather than duplicating visual specifications.

## Step map

### Step 1 — School / Jobsite

Approved workbench states:

- `wizard-01-initial`
- `wizard-01-school-selected`
- `wizard-01-return`
- `wizard-01-add-school-us`
- `wizard-01-add-school-international`

School / Jobsite is a persistent employer-owned resource distinct from the
current job.

### Step 2 — Job Basics

**Position**

- Job Title — required
- Grade Level(s) — required where applicable
- Subject Area(s)

**Employment**

- Employment Type
- Work Location
- Override job location — conditional
- Remote eligibility area — conditional
- Hybrid on-site location — conditional
- Multiple-location selector — conditional

**Compensation**

- Salary display mode: hourly, annual, or undisclosed
- Salary minimum — conditional
- Salary maximum — conditional

**Benefits**

- Benefits offered — optional expandable checklist

### Step 3 — Job Description

**Narrative**

- Job Description — required rich text
- Requirements / Qualifications — rich text
- Short Summary — optional and strongly encouraged; after the primary narrative fields

**Listing Image**

Mutually exclusive choices:

- Use School / Jobsite image
- Upload listing-specific image
- Use Teachers.Net default

**Advanced Display Options** — optional expandable

- Public employer/display-name override
- Public location display override

### Step 4 — Application Process

**Application Method**

- External URL
- Email
- Instructions

Conditional fields:

- Application URL
- Application Email
- Application Instructions

**Contact**

- Use School / Jobsite default contact
- Override contact
- Contact Name — optional
- Contact Email — conditional
- Contact Phone — optional
- Hide contact details publicly — conditional

**Deadline**

- Specific date
- Open until filled
- No stated deadline
- Application Deadline — conditional
- Close on Application Deadline? — conditional and immediately after the deadline field

### Step 5 — Review & Publish

The final step provides review, preview, validation, and final lifecycle controls.

**Publication**

- Publish immediately / on approval
- Schedule publication
- Publication Date — conditional

**Expiration**

Publication duration and end-publication behavior remain an explicit unresolved
lifecycle decision. This contract does not invent a final rule.

**Certification**

Required confirmation precedes final submission. No dedicated database field is
presumed unless an implementation audit proves one is required. The final action
label depends on trust/moderation state: `Publish` or `Submit for Review`.

## Explicit V1 deferrals

The following are excluded from the V1 wizard unless separately reopened:

- Internal Job ID / Requisition Number
- Department
- Job Role / Category
- Specialties / Program Areas
- Position count / openings
- Experience level
- Credential or certification expectation
- Start timing
- Start Date
- Number of contract months
- Schedule notes

These fields are not required by the current V1 presentation, search, lifecycle,
validation, or structured-data needs; they add form weight without sufficient V1
value and remain future enterprise or enrichment candidates. Other enrichment
fields are likewise deferred unless explicitly admitted.

## Field admission rule

A field belongs in V1 only when at least one condition is true:

1. It materially improves the public listing for applicants.
2. It powers an approved V1 search, filter, or sort function.
3. It is required for lifecycle, moderation, validation, or structured data.
4. It controls approved conditional wizard behavior.

## Open decisions

The following remain open and are not resolved here:

- exact benefits checklist vocabulary;
- exact Employment Type vocabulary;
- Work Location behavior for Remote, Hybrid, and Multiple;
- salary-mode details and formatting;
- public-display override rules;
- listing-image override behavior;
- application deadline versus listing expiration;
- default publication duration and renewal lifecycle;
- scheduled publication V1 status.
