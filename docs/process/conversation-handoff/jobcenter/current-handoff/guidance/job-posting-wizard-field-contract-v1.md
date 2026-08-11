# Job Posting Wizard Field Contract v1

## Purpose

This contract defines the V1 Job Posting Wizard field inventory, step ownership,
conditional behavior, and explicit deferrals. The wizard should provide a fast,
clean interface and include only fields necessary for, or used by, V1.

The canonical owner for JC053 V1 field ownership, requiredness, defaults,
conditional behavior, progressive disclosure, and step-transition gates is
`docs/job-center/jc053-wizard-product-contract-v1.md`. This field contract is a
concise companion summary and must not override the product contract.

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
- Starting Date / Start Timing
- Override job location — conditional
- Remote eligibility area — conditional
- Hybrid on-site location — conditional
- Multiple-location selector — conditional

Work Location defaults to `Use School / Jobsite Location` when Step 1 has
established the required Primary School / Jobsite. The ordinary V1 flow has no
blank Work Location placeholder before that selection. The recruiter may choose
another currently supported Work Location mode, but the Primary Resource remains
the organizational anchor regardless of Work Location selection. Detailed
Remote, Hybrid, and Multiple behavior remains open except where already
established by accepted authority.

Starting Date is a V1 Step 2 field. Start Timing options are `Immediately`,
`Specific Date`, and `Flexible`. The default is `Immediately`; there is no blank
placeholder option. `Specific Date` reveals Start Date and makes Start Date
required for valid completion of the Starting Date control. `Immediately` and
`Flexible` do not require a specific Start Date.

**Compensation**

- Salary Visibility / salary display mode
- Salary minimum — conditional
- Salary maximum — conditional

Salary Visibility defaults to `Show Salary`. The ordinary V1 flow has no blank
Salary Visibility placeholder before that selection. Detailed salary vocabulary,
formatting, interval, validation, currency, hourly/annual, negotiable, volunteer,
and undisclosed behavior remains open except where already established by
accepted authority.

### Step 3 — Job Description

**Narrative**

- Job Description — required rich text
- Short Summary — recommended authoring field; deterministic summary review/gate before Step 4 when needed. It supports promoted listings, search presentation, featured listings, discovery, and external sharing.

Step 3 authoring direction:

- Rich paste is the primary authoring workflow. Pasted formatting is preserved automatically; persistent editor chrome remains minimal and serves as an escape hatch rather than a word-processor surface.
- Job Description is the only immediate required Step 3 narrative field for ordinary authoring readiness.
- Optional enrichment is grouped under one `Optional Fields` heading: Requirements / Qualifications, Responsibilities, Preferred Qualifications, About Our School, and Benefits.
- The Step 3 preview is incremental and renders only populated content. Empty headings, sections, and containers are suppressed.

**Listing Image**

Mutually exclusive choices:

- Use School / Jobsite image
- Upload listing-specific image
- Use Teachers.Net default

**Advanced Display Options** — optional expandable

- Public employer/display-name override
- Public location display override

### Step 3 optional enrichment behavior

Requirements / Qualifications, Responsibilities, Preferred Qualifications, About
Our School, and Benefits are grouped under `Optional Fields`. Requirements /
Qualifications is recommended for matching and does not block ordinary Step 3
readiness. Benefits uses a compact inline selector with category headings,
clickable benefit names, selected-state highlighting, and an always-visible
selected summary rather than a checkbox grid. Its empty state teaches the
interaction: `Benefits offered: Click any benefit to add or remove it.` The
instruction disappears after selection. Additional Benefits uses progressive
disclosure: helper text, textarea, and character counter appear only after the
control is selected.

Benefits belongs exclusively to Step 3 Optional Fields for V1. Step 2 Job
Basics does not contain a Benefits field or Benefits selector.

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
