# Employer UX V1

**Project:** Teachers.Net - Job Center

**Status:** Product specification

**Version:** 1.0

**Date:** 2026-07-11

**Basis:** Job Center Canonical V1 Contract, UA001, UA002, UA003, and the
current Teachers.Net Jobs implementation

## 1. Executive Summary

Employer UX V1 is one connected experience inside a hybrid authenticated
Employer Workspace within the standard Teachers.Net shell. It lets an
authenticated representative establish authority, publish and manage
canonical job records, understand lifecycle and public visibility, and review
basic engagement without learning separate systems.

Employer personas are descriptive planning models, not permanent account
classes. The authenticated account, active employer memberships, employer/job
scope, lifecycle, and separately granted capabilities determine available
operations. A user may evolve from one employer to multiple related or
unrelated employers without account migration.

The experience begins before the Dashboard. A visitor may browse the Job Center
without employer authority. Employer work requires a WordPress account. After
login or registration, the user chooses the correct organization path:

- **Claim Existing Employer** when the organization already exists in
  Teachers.Net; or
- **Add My School** or **Add Organization** when Teachers.Net does not yet have
  the organization. The internal canonical creation/request route remains an
  implementation detail.

These paths gather overlapping identity evidence but do different work. A claim
requests authority for an existing canonical employer. It never creates a
replacement employer, job, or recruiter identity. An Add My School / Add
Organization request asks Teachers.Net to review a new organization. Neither path grants employer access
before the required review is complete.

Approved authority is represented by an active membership between the
authenticated user and the canonical employer. That membership opens the one
Employer Dashboard and the one My Jobs inventory for that employer. A user with
more than one active employer membership chooses the employer context once and
keeps that context while moving through Dashboard, My Jobs, Post Job, Edit,
Review, and return paths.

Dashboard summarizes. It establishes the selected employer, shows what needs
attention, and routes the employer to the relevant work. It is not a second job
manager and not an analytics dashboard. My Jobs manages. It is the complete
employer-facing lifecycle inventory, explains why each job is or is not public,
shows basic engagement, and exposes only the actions valid for the job's state.

Post Job and Edit are two entry points into one job-authoring model. They use the
same field meanings, grouping, validation rules, Review model, Preview model,
and lifecycle language. Employer identity and system facts remain derived and
read-only. The employer supplies the job content, classifications, location
facts, compensation, and truthful application instructions. A job is not ready
to publish until it has an approved employer association, intelligible content,
a valid application path, valid location or explicit remote treatment,
lifecycle/expiry facts, and no unresolved safety or duplicate exception.

Review and Preview are different. **Review** is the factual readiness checkpoint
before submission or publication. It shows the complete employer-entered job,
the derived next state, and any blocker. **Preview** is the protected visual
representation of how the job will appear publicly. Create and Edit share this
same distinction.

Submission follows one moderation model. A job that does not have explicit
direct-publish authority becomes **Awaiting Review**. A job eligible for direct
publication becomes **Published**, but it is called **Live** only while it
satisfies the complete public-eligibility rule. Published and Live are not
synonyms: Published is a lifecycle/moderation fact; Live is a current public
visibility fact.

Management preserves history. Edit changes the same eligible job. Duplicate
creates a separate new draft without renewal meaning. Renew creates a new draft
with an explicit lineage link to a closed or expired source job. Close ends a
live job intentionally without erasing it. Expiration ends public eligibility
at the operational expiry time. Archive removes a record from active work under
the approved retention policy; it is not a synonym for Close or Expired.

Employer metrics remain deliberately basic. **Views**, **Saved**, and
**Interested** are unique logged-in user/job engagement signals. Interested
means the user saved the job or revealed its Application Instructions. These
signals are not applications, applicants, interviews, or hires. Dashboard may
summarize only complete, accurately scoped values; My Jobs is the job-level
source for the metric set.

V1 succeeds when an employer can move through this entire journey without
encountering conflicting identities, status words, preview models, management
surfaces, or metric definitions.

## 2. Employer Lifecycle

### 2.1 Journey overview

```text
Visitor
  ↓
Login / Registration
  ↓
Choose organization path
  ├─ Claim Existing Employer
  └─ Add My School / Add Organization
  ↓
Authority Review
  ↓
Active Employer Membership
  ↓
Employer Dashboard
  ↓
Post Job
  ↓
Review
  ↓
Preview when visual confirmation is needed
  ↓
Publish directly OR Submit for Review
  ↓
My Jobs / Manage Jobs
  ├─ Edit
  ├─ Duplicate as a new draft
  ├─ Close
  ├─ Expire automatically
  └─ Renew closed/expired job as a lineage-linked draft
  ↓
Archive under the approved retention rule
```

### 2.2 Visitor

A visitor may browse public jobs and employer information. Public browsing does
not establish employer identity or authority. Imported employer and job records
do not create a recruiter relationship.

Employer actions require authentication. A visitor attempting employer work is
sent to WordPress-owned login or registration and returned to the intended
employer context afterward.

### 2.3 Login / Registration

WordPress owns account registration, authentication, password recovery, and
account identity. Jobs does not silently create a recruiter identity from
imported data, a matching email domain, an employer claim, or an access request.

Authentication proves control of a user account. It does not prove authority to
represent an employer. Employer authority begins only after an approved active
membership.

### 2.4 Choose organization path

The user makes one explicit identity decision before requesting employer
authority.

#### Claim Existing Employer

Use when the employer already exists in Teachers.Net.

- The claim targets the existing canonical employer ID.
- The claimant uses an authenticated existing WordPress account.
- The claimant provides authority evidence.
- Name, domain, website, and email alignment are evidence only.
- A pending claim grants no Dashboard, My Jobs, or job-management access.
- Human administrator approval activates or attaches the membership.
- Approval preserves the employer, jobs, URLs, classifications, source history,
  moderation history, lifecycle history, and engagement.
- Return, rejection, or revocation grants or retains no unauthorized access.

#### Add My School / Add Organization

Use only when the employer is not already represented by a canonical Teachers.Net
employer record. The internal canonical employer-creation/request route is not
user-facing product terminology.

- The request identifies the new organization and its representative.
- The organization remains pending until reviewed.
- No active employer membership exists before approval.
- The path must not duplicate an existing employer that should be claimed.
- Approval establishes the canonical employer and active membership used by the
  standard Dashboard and My Jobs experience.

### 2.5 Authority Review

Authority review is an administrator decision, not an automatic match. It
separates organization identity, user identity, employer authority, and
publishing trust.

The user-facing state is one of:

- **Awaiting Review** — the authority request has been received and grants no
  access yet;
- **More Information Needed** — the reviewer returned the request for evidence;
- **Approved** — an active membership now authorizes employer access;
- **Not Approved** — no employer authority was granted; or
- **Access Revoked** — a prior membership no longer authorizes employer work.

These authority states do not alter public jobs or replace employer identity.

### 2.5A Employer Workspace and Progressive Completion

Employer Operations is a hybrid authenticated workspace inside the standard
Teachers.Net shell. Dashboard summarizes; My Jobs manages. The same workspace
serves one employer, multiple related employers, and multiple unrelated
employers; memberships and granted capabilities determine which operations are
available.

Employer identity, employer profile, employer membership, posting account, and
source/provenance are distinct concepts. Claim Employer is a contextual
acquisition workflow, not a routine Employer Operations action. Add My School /
Add Organization is the user-centered path for an organization not yet
represented; internal canonical employer creation remains non-user-facing.

Employer creation follows Progressive Completion:

- require identity fields only when necessary for truthful operation;
- strongly encourage discovery-improving fields with a clear explanation; and
- prefer progressive completion over abandonment-inducing validation when
  missing information reduces quality rather than correctness.

Location guidance should explain that City, State, and ZIP improve teacher
discovery and Distance Search. It should not impose unnecessary validation that
causes avoidable abandonment when the record can remain truthful and pending.

### 2.6 Employer Dashboard

Dashboard is the operational landing page for one selected employer. It:

- confirms the authenticated user and selected employer context;
- summarizes complete employer-level job counts;
- identifies current attention items;
- provides the primary Post Job action;
- routes each summary or attention item to the corresponding My Jobs context;
- avoids duplicating the complete job manager; and
- avoids advanced analytics.

Attention means a job needs a clear employer next step, including a draft to
finish, a submission awaiting review, an upcoming expiration, or a published
record that is not currently Live because an eligibility condition needs
resolution.

### 2.7 Post Job

Post Job creates one canonical job for the selected employer. Employer-entered
facts use one shared authoring contract across Create and Edit:

- role identity: title, summary, description, requirements;
- work facts: employment type and compensation;
- classification: configured Core Terms fields such as grade and subject;
- location: classification plus Jobs-owned work arrangement and physical
  location facts;
- application: method-specific URL, email, or written instructions; and
- employer confirmation that the facts are accurate and ready for Review.

Employer identity, creator, slug, coordinates, geocode state, lifecycle
timestamps, default expiry, moderation facts, claim facts, provenance,
suppression, source identity, and renewal lineage are derived or system-owned.
They are not ordinary employer-editable fields.

Create and Edit share field definitions and validation meaning. A required fact
has the same requirement in both. Browser guidance and server enforcement use
the same rule language. Incomplete work is not presented as publication-ready.

### 2.8 Review

Review is the factual readiness checkpoint. It is not a visual mockup.

Review includes:

- selected employer;
- all employer-entered job facts;
- classifications and public location treatment;
- compensation presentation;
- application method and a protected confirmation of the destination or
  instructions;
- expiry policy;
- expected next lifecycle state;
- expected moderation path; and
- every condition currently preventing submission or publication.

Review answers: **Is this record complete and what will happen when I finish?**

### 2.9 Preview

Preview is the protected visual model of the public job presentation. It uses
the same public content hierarchy and visibility rules without making a draft
or submitted job public.

There is one Preview model for Create and Edit. Preview never changes lifecycle,
submits a job, grants public access, or exposes protected application data to an
unauthorized viewer.

Preview answers: **How will this job appear to a job seeker when it is Live?**

### 2.10 Publish / Submit

The finish action states its consequence before mutation:

- **Submit for Review** creates or moves a complete job to **Awaiting Review**;
  or
- **Publish Job** creates or moves an explicitly trusted, fully eligible job to
  **Published**.

Direct-publish authority is separate from employer identity, approved claim,
membership, and prior job history. A job is called **Live** only after the
canonical public-eligibility rule confirms that it is currently public and
actionable.

Confirmation reports the actual result:

- Draft saved;
- Submitted and Awaiting Review;
- Published and Live;
- Published but Needs Attention / Not Live; or
- unable to complete, with the job and employer work preserved.

### 2.11 Manage Jobs

My Jobs is the complete job-level management surface for the selected employer.
It includes every record allowed by the approved retention policy and presents:

- lifecycle status;
- current visibility state;
- concise reason or next-step guidance;
- relevant created, submitted, published, expiration, closed, or archived date;
- Views, Saved, and Interested where meaningful;
- renewal lineage where present; and
- only actions valid for the current state.

Editing preserves the same job identity when the lifecycle permits editing.
Editing a Live job may preserve publication or return it to Awaiting Review
according to the explicit moderation/trust rule. The consequence is stated
before save.

### 2.12 Renew

Renew applies to a Closed or Expired job. It creates a new Draft linked to the
source job.

- The source record, history, and metrics remain unchanged.
- The renewed Draft has its own future lifecycle and engagement.
- The employer reviews current facts before submission or publication.
- Renewal never silently reactivates an old public record.
- Renewal is not Duplicate.

### 2.13 Close

Close intentionally ends a currently Live job.

- The same job record is retained.
- The job stops accepting applications through the public flow.
- Historical lifecycle and engagement remain intact.
- Close does not create a replacement job.
- A later need to advertise the role again uses Renew, not silent reopen.
- Close is not Archive.

### 2.14 Expire

Expiration is the date-derived end of ordinary public eligibility.

- Expired is a user-facing condition derived from the operational expiry fact.
- Expiration does not erase or archive the job.
- The source job retains its history and metrics.
- A new active posting uses Renew.
- Expired is not Closed and is not Archived.

### 2.15 Archive

Archive removes a retained job from active management according to the approved
retention policy. It is a soft historical state, not deletion.

- Archive does not mean a position was filled.
- Archive does not replace Close or Expiration.
- Archive does not erase lineage, moderation, provenance, or engagement.
- Archived records are not editable or public.
- Whether employers may archive only Drafts or also retained Closed/Expired
  records remains a product decision in Section 6.

## 3. Product Principles

### 3.1 One employer identity

One organization has one canonical employer record. Claim attaches verified
authority to that identity. It does not recreate the employer.

### 3.2 One authority model

WordPress authenticates. Jobs authorizes through active employer membership.
Employer identity, employer authority, member role, and direct-publish trust are
different facts.

### 3.3 One Dashboard

Every approved employer uses the same Dashboard, including a previously
imported employer after claim. There is no separate imported, claimed, or
recruiter dashboard.

### 3.4 One My Jobs inventory

My Jobs is the one employer-facing job manager. Dashboard, confirmations, and
notices route into it rather than creating parallel management surfaces.

### 3.5 One canonical job

Employer-posted and imported jobs are the same public product entity. Authority
transitions never replace the job or reset its URL, history, classification,
provenance, moderation, or engagement.

### 3.6 One lifecycle

Draft, review, publication, visibility, closure, expiration, renewal, and
archive follow one rule set across Dashboard, My Jobs, public finder, detail,
saved jobs, alerts, application actions, communications, and structured data.

### 3.7 One status vocabulary

The same user-facing terms and definitions appear everywhere. Storage status is
never presented as proof of public visibility.

### 3.8 One authoring model

Create and Edit share field meaning, grouping, validation, Review, Preview, and
moderation language. They do not create separate job schemas or business rules.

### 3.9 One Review model

Review is the complete readiness and consequence checkpoint for both Create and
Edit.

### 3.10 One Preview model

Preview is the protected visual public representation for both Create and Edit.
It is not a second review or a public draft route.

### 3.11 One metrics model

Views, Saved, and Interested have the same definitions and scope wherever they
appear. They are engagement signals, never applications.

### 3.12 Preserve history

Close, Expire, Renew, Duplicate, Archive, claim, and moderation preserve the
history appropriate to the canonical employer and job. V1 prefers retained,
explainable records over replacement or deletion.

### 3.13 Truth before speed

No CTA, status, confirmation, or metric may imply that a job is public, accepts
applications, represents employer endorsement, or received an application
unless the product can establish that fact.

## 4. UX Principles

1. **Dashboard summarizes.** It shows employer context, complete counts,
   attention items, and routes to work.
2. **My Jobs manages.** It is the authoritative inventory, status explanation,
   metrics view, and action surface.
3. **Create and Edit share interaction patterns.** A field or rule behaves the
   same regardless of entry point.
4. **Employer context persists.** Multi-employer users always know which
   employer they are managing, and route changes retain that validated context.
5. **Review is not Preview.** Review confirms facts, readiness, blockers, and
   next state. Preview shows the protected public presentation.
6. **Published is not automatically Live.** Live requires current canonical
   public eligibility.
7. **Renew is not Duplicate.** Renew continues the lifecycle lineage of a
   Closed/Expired job through a new Draft. Duplicate creates a separate Draft.
8. **Close is not Archive.** Close ends a public opportunity. Archive removes a
   retained record from active management.
9. **Expired is not Closed.** Expiration is date-derived; Close is an intentional
   employer action.
10. **Authority is explained before access.** Login, claim, new-employer review,
    membership, and publishing trust are never conflated.
11. **Actions state their consequence.** Submit, Publish, Close, Renew,
    Duplicate, and Archive communicate the resulting identity and lifecycle.
12. **Only valid actions appear.** Hidden controls are not security; all actions
    remain enforced by authentication, membership, status, and nonce.
13. **Errors preserve work.** Validation identifies the owning field and stage,
    explains the rule accurately, and does not silently discard employer input.
14. **Dates are named precisely.** Created, Submitted, Published, Expires,
    Closed, and Archived are not combined under ambiguous labels.
15. **Metrics explain themselves.** The UI states unit, uniqueness, and scope
    without implying candidates or applications.
16. **Attention outranks analytics.** V1 prioritizes incomplete, awaiting-review,
    expiring, blocked, closed, or renewable work over charts and trends.
17. **Protected facts remain protected.** Email application instructions and
    non-public job data are visible only to authorized contexts.
18. **One action center prevents duplication.** Summary surfaces link to My Jobs
    instead of reproducing its full controls.

## 5. Terminology

### 5.1 Employer authority terms

| Term | Frozen meaning |
|---|---|
| Employer | One canonical organization record in Teachers.Net Jobs. |
| Employer Member | An authenticated WordPress user with an employer membership. |
| Active Employer Membership | The authorization grant that permits the user to access that employer's Dashboard and eligible job actions. |
| Claim Existing Employer | Request verified authority for an existing canonical employer. |
| Add My School / Add Organization | Request review and creation/activation of an employer not already represented. |
| Awaiting Authority Review | Claim or Add My School/Organization request received; no employer access granted yet. |
| Publishing Trust | A separate approved fact that may allow direct publication. It is not synonymous with claim approval, membership, verification, or prior job history. |

### 5.2 Job lifecycle terms

| Term | Frozen meaning |
|---|---|
| Draft | Employer work retained but not submitted or public. |
| Awaiting Review | Complete job submitted to administrator moderation and not public unless/until approved. User-facing replacement for raw `submitted`. |
| Published | Job has passed or validly bypassed moderation and carries the publication lifecycle status. Published alone does not guarantee current public visibility. |
| Live | Job is currently publicly visible and actionable under the complete eligibility rule. Live is a visibility state, not a stored lifecycle synonym. |
| Needs Attention | Job cannot reach or retain its expected public state until a named eligibility issue is resolved. This explains a condition; it is not a replacement lifecycle. |
| Closed | Employer intentionally ended the opportunity. The record and history remain. |
| Expired | Operational expiry time has passed, ending ordinary public eligibility. The record and history remain. |
| Archived | Retained historical record removed from public and active management under the approved retention rule. Not deleted. |
| Renew | Create a new Draft linked to a Closed or Expired source job while preserving source history and metrics. |
| Duplicate | Create a separate new Draft from eligible source content without renewal lineage. |
| Edit | Change the same eligible canonical job record. |
| Review | Factual readiness, blocker, moderation, and next-state checkpoint. |
| Preview | Protected visual representation of the future public job. |

### 5.3 Visibility phrases

Use these combined presentations where lifecycle status alone is insufficient:

- **Published — Live**
- **Published — Needs Attention**
- **Awaiting Review — Not Live**
- **Draft — Not Live**
- **Closed — Not Live**
- **Expired — Not Live**
- **Archived — Not Live**

Do not use **Active**, **Published**, and **Live** interchangeably. Do not call a
job Live merely because its stored status is `published`.

### 5.4 Metrics terms

| Term | Frozen meaning |
|---|---|
| Views | Number of unique logged-in user/job pairs with a recorded job view. Not anonymous page views and not unique people across all employer jobs. |
| Saved | Number of unique logged-in user/job pairs where the user saved the job. |
| Interested | Number of unique logged-in user/job pairs where the user saved the job or revealed Application Instructions. A user counts once per job. |
| Application Instructions Revealed | The user opened the protected application destination or instructions. This is an engagement event, not proof of an application. |

Never use **Applications**, **Applicants**, **Candidates**, **Interviews**, or
**Hires** for these engagement counts.

## 6. Remaining Product Decisions

The following decisions require explicit future approval. This specification
does not choose an implementation.

1. **Direct-publish trust:** What durable fact grants publishing trust, who may
   grant/revoke it, and how does it differ by employer member or employer?
2. **New-organization account prerequisite:** Must Add My School/Organization always begin
   from an authenticated account, consistent with the lifecycle specified here?
3. **Draft persistence:** Is durable Save Draft / Continue Later mandatory for
   V1 acceptance, and what minimum completeness permits first save?
4. **Preview fidelity:** Is the V1 Preview required to use the exact public
   detail presentation, or is a clearly labeled protected representation
   sufficient?
5. **Archive authority:** May employers archive only Drafts, or also retained
   Closed and Expired jobs?
6. **Archive visibility:** Do employers retain a read-only Archived history view,
   and is archive reversible by employer, administrator only, or not at all?
7. **Close reason:** Does V1 capture a reason such as position filled, canceled,
   or other, and is any reason public?
8. **Moderation edit behavior:** What precisely happens to queue position and
   review state when an employer edits an Awaiting Review job?
9. **Employer membership roles:** Do V1 roles create different visible or
   server-side action authority, or is one employer-member role sufficient?
10. **International scope:** Is V1 US-only, allowing country and currency to be
    fixed defaults, or must employer entry support other countries/currencies?
11. **Expiration control:** Is the 30-day expiry fixed for V1 or visible/selectable
    within a bounded policy?
12. **Interested metric presentation:** Should V1 show the union Interested
    metric, the component Application Instructions Revealed metric, or both,
    while retaining the frozen definitions above?
13. **Dashboard metric set:** Does Dashboard include engagement, or only job
    counts and attention items while My Jobs owns all job-level metrics?
14. **Claim status visibility:** What claimant-facing history and returned-
    evidence loop belongs in V1 without creating a notification center?
15. **Duplicate safeguard:** What employer-facing duplicate warning is required
    before direct publication, without automatic merge or destructive blocking?

## 7. Acceptance Journey

The Employer UX V1 acceptance sequence is one end-to-end journey with explicit
state and security checks at each boundary.

### 7.1 Visitor and account

1. A logged-out visitor can browse jobs but cannot access employer management.
2. Employer intent routes through WordPress login or registration.
3. Authentication returns the user to the intended employer task.
4. Authentication alone grants no employer authority.

### 7.2 Organization identity and authority

5. The user can distinguish Claim Existing Employer from Add My School/Organization.
6. Existing-employer discovery targets one canonical employer without creating
   a duplicate.
7. Claim submission requires an authenticated existing account and remains
   pending without employer access.
8. An Add My School/Organization request does not duplicate an employer that should be claimed.
9. Return/rejection grants no access and communicates the next state.
10. Approval creates or activates the correct membership.
11. Revocation immediately removes employer authority without changing employer
    or job identity/history.

### 7.3 Dashboard continuity

12. A single-employer member reaches the correct Dashboard directly.
13. A multi-employer member selects only from active authorized employers.
14. Employer context persists through Dashboard, My Jobs, Post, Edit, Review,
    confirmation, and return paths.
15. An approved claimant sees the existing employer and associated jobs with
    unchanged IDs, URLs, classifications, lifecycle, provenance, and metrics.
16. Dashboard counts are complete and attention items route to the correct My
    Jobs context.

### 7.4 Authoring

17. Post Job and Edit use the same field meanings, grouping, required rules, and
    employer context.
18. Required Core Terms selections accept only current configured options.
19. On-site/hybrid and remote/multiple/confidential location combinations are
    truthful and follow the canonical eligibility rule.
20. Salary values cannot contradict salary type or range ordering.
21. External URL, email, and written application methods each validate and
    preserve the correct privacy behavior.
22. Employer work survives the approved draft/error/retry contract.

### 7.5 Review, Preview, and finish

23. Review shows every material fact, derived next state, expiry, moderation
    path, and blocker.
24. Preview reflects the approved public presentation without making the job
    public or exposing protected facts.
25. Standard-review finish results in Awaiting Review and Not Live.
26. Direct-publish finish is available only under the approved trust decision.
27. A complete eligible direct publication results in Published — Live.
28. A blocked record is never confirmed or labeled Live.
29. Job creation and classification complete coherently or preserve a truthful,
    recoverable non-success state.

### 7.6 Management and lifecycle

30. My Jobs presents the complete inventory required by the approved retention
    policy with truthful lifecycle and visibility.
31. Draft, Awaiting Review, Published — Live, Published — Needs Attention,
    Closed, Expired, and Archived are distinguishable.
32. Only valid actions appear, and every mutation independently enforces login,
    membership, employer/job scope, lifecycle, and nonce.
33. Edit preserves the canonical job and states any moderation consequence.
34. Close ends public eligibility and retains history.
35. Expiration ends public eligibility at the operational expiry fact.
36. Renew creates a lineage-linked Draft and preserves the source record and
    metrics.
37. Duplicate creates a separate Draft without renewal meaning.
38. Archive follows the approved authority, visibility, and retention decision.

### 7.7 Metrics and cross-surface truth

39. Views, Saved, and Interested match their frozen unique user/job definitions.
40. Metrics never imply applications, applicants, interviews, or hires.
41. Dashboard totals are complete and accurately scoped.
42. Renewal and duplication do not merge source and new-job engagement.
43. Dashboard, My Jobs, confirmation, public finder, public detail, saved jobs,
    alerts, application actions, and structured data agree on whether a job is
    Live.

### 7.8 Pilot acceptance

44. The complete journey is exercised with a controlled pilot employer posting
    and a verified claim of an employer with pre-existing imported jobs.
45. Pilot findings are evaluated against this specification and the Canonical
    V1 Contract before Employer UX V1 is declared accepted.

## Document boundary

This specification defines the Employer UX V1 product contract. It does not
authorize code, route, template, schema, data, or behavior changes. Remaining
product decisions require explicit approval, and any resulting work requires
separate implementation tickets.
