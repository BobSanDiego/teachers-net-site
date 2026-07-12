# UA002 - Employer Post Job Wizard Audit

**Project:** Teachers.Net - Job Center

**Mode:** Documentation-only inspection

**Audit date:** 2026-07-11

**Contract:** `docs/job-center/canonical-v1-contract.md`

**Prior audit:** `docs/job-center/audits/ua001-employer-journey-audit.md`

**Implementation inspected:** current `wordpress/wp-content/plugins/tnet-jobs`

## Executive summary

The current employer Post Job Wizard is a functional four-step client-side
entry flow backed by active employer membership, server-side validation,
established Core Terms classification, application-integrity evaluation,
moderation, and a 30-day lifecycle. Its sequence is understandable: **Basic
Info**, **Qualifications**, **Apply**, and **Review**. Standard-review employers
submit to moderation; employers with a prior published job publish directly.

The wizard is only partially aligned with the Canonical V1 Contract and UA001's
employer-journey findings. It creates a job only on final submission, so there
is no durable draft, save-and-return path, or recovery from a long interrupted
session. Its browser-side review and live preview do not represent all
publication-critical facts. Most importantly, employer-entered location facts
are optional even for on-site/hybrid jobs, and the generic application field is
required but not presented or validated according to the selected method. The
service records application-integrity state, but a prior-published employer can
still create a record with `published` status before that integrity outcome is
communicated to the recruiter.

The trust rule is the largest policy risk. “Trusted” is derived from whether the
employer has any prior published job, not from verified employer authority,
claim state, member role, or a durable publishing-trust decision. That rule is
explicitly distinct from the Canonical V1 claim/authority contract and should
not be treated as verified-employer status.

No redesign or parallel posting workflow is recommended. The smallest coherent
path is to define the canonical field contract, make draft/review/submission
states truthful, align method-specific application and location validation with
publication requirements, and reuse the existing service, Core Terms, and
moderation components.

## Audit scope and method

Inspected surfaces:

- create route and employer membership selection;
- wizard PHP renderer and JavaScript step behavior;
- field renderers and Core Terms form-field configuration;
- create request mapping and job service normalization;
- repository-required fields and schema;
- application-integrity decision path;
- trust/direct-publish decision and confirmation messaging;
- UA001 create, preview, submit, and trust findings.

This audit did not submit data, create jobs, change configuration, or test a
runtime browser session. Required Core Terms fields are configuration-driven;
the implementation behavior is audited, but a specific live field
configuration is not asserted here.

## Current step sequence

| Step | Current content | Current gate | Finding |
|---|---|---|---|
| Pre-step context | Displays **Posting as** and the selected employer. Multi-employer users select an employer before entering the wizard. | Active membership for the selected employer. | Correct authorization context. The employer identity is derived and should remain read-only. |
| 1. Basic Info | Job Title, Short Summary, Job Description, Requirements / Qualifications, Employment Type, Salary type, Minimum, Maximum, Currency. | Browser `required` for title, description, and employment type; HTML numeric constraints; server service validates enums and salary ordering. | Too much content for “Basic Info”; qualifications prose appears here while structured qualifications appear in Step 2. Salary is optional but review/preview does not clearly expose it. |
| 2. Qualifications | Configuration-driven Grade Level, Subject Area, State / Work Location and other active Core Terms fields; Work Arrangement; address lines; city; postal code; country. | Required Core Terms fields checked in JavaScript and server-side; location metadata is largely optional. | The step combines classification, candidate qualifications, and physical job location. “Qualifications” is therefore an inaccurate step name. Location classification and location facts are related but not duplicates. |
| 3. Apply | Application Instructions Type and one required **Application Instructions** textarea. | Type is required; textarea is required; service evaluates method/value integrity. | The single textarea represents URL, email, or free text but keeps one generic label and help model. Method-specific format and privacy consequences are not clear at entry time. |
| 4. Review | Generated definition list of entered values plus a live-preview card and trust-dependent finish guidance. | Finish button enabled only on the final step; normal form validation runs on submission. | Review is comprehensive as a raw value dump, but the live preview is partial and neither is the actual public detail rendering. Publication blockers and derived lifecycle facts are not summarized. |
| Finish | Button reads **Publish Job** for prior-published employers or **Submit for Review** otherwise. | Nonce, active membership, server normalization, create, then term assignment. | State consequence is stated, but there is no durable save-draft alternative and no deliberate trust grant. Term assignment occurs after job creation and can partially fail. |

## Field inventory

### Employer-entered fields

| Field | Required now | Persistence / use | Classification |
|---|---:|---|---|
| Job Title | Yes | Stored; slug is generated from it; used throughout public/admin UI. | Canonical required field. |
| Short Summary | No | Stored; used in listings and preview. UI recommends 100-180 characters but does not enforce a maximum. | Useful V1 field; guidance-only length creates inconsistent content quality. |
| Job Description | Yes | Stored as rich text; primary detail content. | Canonical required field. |
| Requirements / Qualifications | No | Stored as rich text. | Potentially overlapping with description and structured Grade/Subject, but not a true duplicate when reserved for candidate requirements. |
| Employment Type | Yes | Stored enum; public/filter metadata. | Canonical required field. |
| Salary type | No; defaults to undisclosed | Stored enum; controls display interpretation. | Canonical optional disclosure control. |
| Salary minimum / maximum | No | Stored numeric values and displayed according to salary type. | Conditional fields; should be suppressed or rejected for undisclosed/negotiable and interpreted by period. |
| Salary currency | No; defaults to USD | Stored three-letter code. | Derived/defaultable for the V1 US product; free-text entry adds friction and error opportunity. |
| Core Terms fields | Configuration-driven | Stored as job-term assignments; used for classification/search. | Canonical structured fields. Required status is external configuration, not obvious from static code. |
| Work Arrangement | No; defaults to on-site | Stored as `location_mode`. | Canonical required-in-practice publication fact; defaulting to on-site without required location facts can produce an invalid combination. |
| Address Line 1 / 2 | No | Stored; not a primary public listing fact. | Address Line 2 is optional detail, not obsolete. Full street address may be more precision than V1 search requires and needs a clear privacy/use policy. |
| City | No | Stored; public location and origin/geocoding input. | Missing conditional requirement for on-site/hybrid. |
| ZIP / Postal Code | No | Stored; local origin/geocoding input. | Missing conditional requirement for distance-eligible on-site/hybrid jobs. |
| Country | No; defaults to US | Stored two-letter code. | Derived/defaultable for US-only V1; free-text entry is unnecessary unless international posting is supported. |
| Application Instructions Type | Yes | Stored as `apply_method`: external URL, email, or instructions. | Canonical required field. |
| Application Instructions | Yes | Stored in `apply_url` despite supporting URL, email, and free text. | Canonical content in a legacy/misleading storage name; UI needs method-specific label and validation. |

### Derived or system-owned fields

These should not become editable wizard controls:

- `employer_id`: derived from the active selected membership;
- `created_by_user_id`: current authenticated user;
- `job_slug`: generated uniquely from title;
- `status`: derived from the current direct-publish trust rule;
- `submitted_at`: current time;
- `approved_at` and `published_at`: assigned only for direct publish;
- `expires_at`: fixed at 30 days from creation;
- application integrity status/error: derived from method and instructions;
- coordinates and geocode operational metadata: Jobs-owned derived facts;
- provenance, source identity, claim state, moderation actor, suppression, and
  reconciliation facts: system/admin-owned facts;
- renewal lineage: not applicable to an original create and system-owned on
  renewal.

## Required-field and validation audit

### Browser validation

The enhanced wizard validates only the current step before moving forward. It
uses native `checkValidity()` plus custom checks for required classification
trees. Forward clicks in the progress bar advance only one validated step at a
time; backward navigation is unrestricted. On final submit it unhides all steps
so native validation can target their controls.

Strengths:

- required HTML controls are checked before forward progress;
- required multi-select Core Terms trees receive an explicit inline error;
- invalid controls are focused;
- the server repeats required Core Terms and allowed-term validation;
- nonce and active employer membership are enforced before creation.

Gaps:

- generic error text says “is required” for any invalid native constraint,
  including malformed email/URL or numeric input;
- the summary target is visual guidance only; over-target content is not
  blocked or normalized;
- salary fields do not conditionally validate against salary type in the UI;
- city/postal/state consistency is not validated in the wizard;
- on-site/hybrid location completeness is not enforced;
- remote/multiple/confidential modes do not dynamically clarify which location
  inputs apply;
- application content is not validated with method-specific UI messages;
- no duplicate/near-duplicate warning is surfaced before submission;
- no preflight summary names publication blockers before the finish action.

### Server validation and persistence

The job service validates status, employment type, application method, salary
type/range, work arrangement, country normalization, coordinates when present,
IDs, and application integrity. The repository requires slug, title,
description, employment type, application instructions, creator, and expiry.
Core Terms selections are validated against configured allowed options.

The server is authoritative, but two coherence gaps remain:

1. creation precedes term assignment, so a term-assignment failure can leave a
   created job without the intended classifications while the user receives an
   error; and
2. the service can record invalid application integrity rather than rejecting
   job creation. That is acceptable for a draft/submitted repair workflow, but
   direct `published` status must never imply public eligibility or successful
   publication when integrity is invalid.

## Draft behavior

The create wizard has no draft behavior. A job record is not created until the
final submit; the form has no **Save draft**, autosave, local recovery,
continuation URL, or server-side partial validation mode. The database and
management UI already support `draft`, and renew/duplicate create drafts, but
the primary create path does not expose that lifecycle.

This confirms UA001's P0 decision point: V1 must either provide durable
save-and-continue or explicitly declare that the wizard is a one-session
submission flow. Because the form includes long description, classification,
location, salary, and application sections, silent one-session loss is material
workflow friction.

Draft implementation should reuse the same job record, ownership checks, and
edit path. It must not create a second draft subsystem or treat incomplete
drafts as publication-ready records.

## Preview and review audit

The **Review** definition list includes non-empty form values, including fields
not shown in the live preview. The separate **Live Preview** card shows title,
employer, summary, employment type, grade, subject, location classification,
and a generic indication that application instructions were added.

The live preview omits or abstracts:

- salary and currency;
- full description and requirements;
- work arrangement;
- city, postal code, country, and address;
- exact application destination/instructions;
- expiry;
- publication/moderation blockers;
- the actual public detail layout.

Hiding exact protected application data in a preview can be appropriate, but
the recruiter still needs a truthful review of what will be stored and how it
will appear. Calling the card **Live Preview** overstates its fidelity. The
existing component is better understood as a compact posting summary unless it
is reconciled with the public detail renderer.

## Submission and trust behavior

### Standard-review employer

- final action: **Submit for Review**;
- record status: `submitted`;
- confirmation: waiting for administrator review;
- public visibility: withheld pending approval and eligibility.

This is coherent with the moderation contract.

### Prior-published employer

- final action: **Publish Job**;
- record status: `published`;
- approval and publication timestamps are set immediately;
- confirmation states that the job is live.

The employer is treated as trusted solely because it has a prior published job.
This is not equivalent to verified authority, an approved employer claim, or an
administrator-managed trust grant. Imported jobs associated with an employer
could also make the organization appear prior-published without proving that a
current member should bypass moderation. The rule therefore risks conflating
job history, employer identity, member authority, and publishing trust.

The confirmation can also overstate “live” if application integrity,
suppression, location, or other public-eligibility conditions prevent the
record from being public. The final message must reflect actual eligibility,
not only the stored status.

## Moderation messaging audit

Current strengths:

- the finish button changes to **Submit for Review** for standard-review
  employers;
- review guidance says whether the posting will publish immediately or enter
  administrator review;
- confirmation distinguishes live from awaiting review;
- the management notice states that the job was submitted for review.

Current gaps:

- the reason one employer publishes directly and another enters review is not
  explained;
- “trusted” has no employer-facing definition and should not imply verification;
- application-integrity or location exceptions are not surfaced in the review
  summary;
- no pre-submit statement explains what an administrator reviews or what can be
  edited while awaiting review;
- no durable draft option separates “not ready” from “ready for moderation”;
- confirmation is based primarily on status rather than verified public
  availability.

## Obsolete, missing, duplicate, and derived fields

### Obsolete or misleading

- `job_slug` is accepted from POST by the create mapper even though the wizard
  does not render it and the service derives a unique slug. The unused input
  path should not be considered part of the employer contract.
- Storage/UI naming `apply_url` / **Application Instructions** is misleading
  because the value may be URL, email, or free text.
- Free-entry currency and country are unnecessary friction for a US-only V1
  unless international posting is explicitly supported.
- **Live Preview** is misleading when the component is a partial summary rather
  than the actual public presentation.

### Missing

- durable save draft and resume;
- conditional location requirements for on-site/hybrid publication;
- method-specific application label, help, validation, and privacy treatment;
- clear expiration disclosure, even if 30 days remains system-derived;
- public-eligibility/preflight outcome in Review;
- explicit distinction between organization verification, member authority,
  and direct-publish trust;
- duplicate-risk feedback before a potentially direct publication;
- recoverable all-or-nothing creation of job plus term assignments.

### Duplicate or overlapping

- **Requirements / Qualifications** prose overlaps with Job Description and
  structured Grade/Subject unless its purpose is tightly defined.
- location is represented by a Core Terms State / Work Location selection and
  Jobs-owned mode/address/city/postal/country fields. This is intentional split
  ownership, not redundant data, but the UI does not explain the distinction.
- Review definition list and Live Preview repeat selected facts with different
  fidelity and purposes.
- browser required validation and server validation appropriately overlap for
  defense in depth; they are not duplicate business systems, but their messages
  should describe the same rules.

### Derived

Employer identity, creator, slug, lifecycle timestamps, default expiry,
coordinates/geocode state, application integrity, moderation facts, provenance,
and claim/authority facts are derived or system-owned and should remain outside
the editable wizard.

## Workflow friction

1. A long four-step session can be lost before any record exists.
2. Step 1 combines basic content, qualifications prose, employment type, and a
   multi-control salary editor.
3. Step 2's “Qualifications” label does not describe its location workload.
4. Hierarchical Core Terms trees can be high-effort without concise selected
   summaries or search.
5. Location asks for both classification and physical facts without explaining
   how each affects public display and distance search.
6. Salary controls remain visible and editable even when salary is undisclosed
   or negotiable.
7. Application entry uses one generic control for three materially different
   methods.
8. Review repeats raw values but does not provide an eligibility decision.
9. The finish action may publish immediately based on an opaque historical
   trust rule.
10. A server error returns to the form, but the wizard does not explicitly map
    the error back to the owning step.

## Implementation opportunities

These are opportunities for separately approved tickets, not authorization to
implement:

- define one canonical field schema consumed by create, edit, review, and
  server validation;
- persist incomplete jobs as existing `draft` records under normal ownership;
- conditionally validate salary, location, and application controls using the
  same service rules as submission;
- make Review display an explicit readiness outcome and actual next lifecycle;
- reuse the public detail presentation or accurately rename the compact preview;
- make job creation plus term assignment recoverable as one coherent operation;
- replace prior-published inference with an explicit, audited direct-publish
  trust decision that remains separate from claim verification;
- base success confirmation on actual public eligibility;
- add end-to-end tests for standard review, direct publish, invalid application,
  missing location, classification failure, term-assignment failure, and
  cross-employer access.

## Prioritized recommendations

### P0 - Canonical V1 correctness

1. **Settle direct-publish trust.** Define an explicit publishing-trust fact and
   administrator boundary. Do not use claim verification or a prior imported/
   published job as an implicit substitute.
2. **Enforce publication-ready location combinations.** On-site and hybrid jobs
   need valid location facts; remote, multiple, and confidential jobs need
   truthful, explicit treatment.
3. **Align application entry with integrity rules.** URL, email, and free-text
   instructions need method-specific labeling and validation. Invalid
   application data may be saved for repair only as non-public draft/submitted.
4. **Settle durable draft behavior.** Provide save/continue using the existing
   draft lifecycle or explicitly reject one-session loss as acceptable for V1.
5. **Make final confirmation truthful.** “Live” must mean actually public and
   application-ready, not merely `status = published`.
6. **Protect coherent creation.** Prevent or repair a job created without its
   required term assignments.

### P1 - Workflow clarity and acceptance

7. Define one field/grouping contract for create and edit; preserve the current
   four conceptual phases unless evidence shows a sequence change is necessary.
8. Clarify Requirements prose versus structured classifications and Job
   Description.
9. Treat country/currency as derived US defaults unless international posting
   is explicitly supported.
10. Make Review show salary, work arrangement, public location, application
    method, expiry, moderation outcome, and any blocker without exposing
    protected data publicly.
11. Rename the compact live card to **Posting Summary** or bring it to actual
    public-preview fidelity.
12. Map server validation errors to their wizard step and preserve all entered
    values after failure.

### P2 - Post-pilot improvement

13. Add selected-term summaries/search only if pilot employers demonstrate
    material Core Terms tree friction.
14. Add duplicate-risk assistance using existing review and source-identity
    rules; do not auto-merge employer-posted jobs.
15. Consider configurable expiry only after the canonical 30-day V1 behavior is
    validated. Expiry remains system-owned by default.

## Recommended acceptance scenarios

1. Standard-review employer completes all four steps and enters moderation.
2. Explicitly trusted employer publishes a fully eligible job.
3. Untrusted or invalid-application submission cannot become public.
4. On-site/hybrid job rejects incomplete location; remote job remains truthful
   and non-distance-eligible where required.
5. Email, external URL, and free-text application methods each validate and
   render correctly.
6. Required single- and multi-select Core Terms fields reject missing/invalid
   terms server-side.
7. Salary combinations cover range, single amount, negotiable, stipend, and
   undisclosed without contradictory persisted values.
8. Draft save/resume, browser interruption, validation failure, and retry retain
   employer-entered work.
9. Job and term creation either complete coherently or expose a recoverable
   failure without a misleading success state.
10. Confirmation, Dashboard, My Jobs, public finder, and public detail agree on
    lifecycle and visibility.

## Verification record

- Documentation-only scope: this audit file only.
- Jobs plugin application code: unchanged by UA002.
- Runtime mutation: not performed.
- Final repository and whitespace verification are reported with the commit.
