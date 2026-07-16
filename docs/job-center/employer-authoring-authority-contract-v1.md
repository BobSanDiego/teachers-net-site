# Employer Authoring Authority Contract v1

Status: Contract for visual-authority work
Ticket: EMP-AUTH004
Scope: Shared employer Post/Edit authoring flow
Dependencies: [EMP-AUTH002](employer-access-authority-contract-v1.md), [EMP-AUTH003](employer-dashboard-my-jobs-authority-contract-v1.md)

This contract defines the minimum V1 visual authority for creating and editing
one canonical job. Create and Edit share one authority family and the existing
routes, fields, validation, permissions, lifecycle, and publication behavior.

## 1. Shared Create/Edit composition

Create (`/jobs/employer/new/`) and Edit (`/jobs/employer/{job_id}/edit/`) use the
same shell, field grouping, wizard progression, Review model, Preview model,
and outcome notices. Edit preserves the existing job identity; Create starts
one new canonical job. No second schema or alternate workflow is introduced.

The shared hierarchy is:

1. Employer shell and selected employer context.
2. Create/Edit heading and truthful state explanation.
3. Job identity and summary fields.
4. Work facts and classification fields.
5. Location and work-arrangement fields.
6. Compensation fields.
7. Application method and destination/instructions.
8. Expiry and employer confirmation fields where governed.
9. Review readiness step.
10. Protected Preview state when visual confirmation is requested.
11. Consequence-labeled Submit for Review or Publish action.
12. Success, pending, blocked, or recoverable-error outcome.

## 2. Employer context treatment

- The selected canonical employer is visible and read-only in the authoring
  shell.
- A multi-employer user must select only an active authorized employer before
  Create; Edit validates that the job belongs to the selected membership.
- Employer identity, creator, slug, coordinates, lifecycle timestamps,
  moderation facts, provenance, suppression, and renewal lineage remain
  system-owned or derived; they are not ordinary editable fields.
- Context persists through Create/Edit, Review, Preview, confirmation, and
  return to Dashboard or My Jobs.
- Invalid, missing, revoked, or unauthorized context blocks authoring and
  explains the permitted next step without exposing editable job data.

## 3. Field-entry hierarchy and grouping

The authority uses the existing field meanings and validation rules:

| Group | Employer-entered content |
|---|---|
| Role identity | Title, summary, description, responsibilities/requirements |
| Work facts | Employment type and compensation values/type |
| Classification | Configured Core Terms fields such as grade and subject |
| Location | Work arrangement and physical location facts governed by Jobs |
| Application | URL, email, or written application instructions |
| Confirmation | Employer attestation that facts are accurate and ready for Review |

Required fields, grouping, labels, and error language are shared by Create and
Edit. Incomplete work is visibly a draft/readiness problem, not publication.

## 4. Validation and blocking-error treatment

- Validation identifies the field or rule that prevents progress.
- Errors remain adjacent to the affected group and are summarized at the Review
  boundary for scanability.
- Entered work is preserved through the approved error/retry contract.
- Blocking conditions explain whether the next state is Draft, Awaiting Review,
  or unavailable for publication.
- Classification, location, compensation, application, duplicate, permission,
  and lifecycle failures use existing service and validation messages.
- No visual state may present a blocked record as submitted, published, or Live.

## 5. Review state

Review is the factual readiness and consequence checkpoint, not a public preview.
It shows:

- selected employer;
- every employer-entered job fact;
- classifications and public location treatment;
- compensation presentation;
- application method and protected destination/instructions;
- expiry policy;
- expected next lifecycle state and moderation path; and
- every blocker preventing submission or publication.

Review answers: **Is this record complete, and what will happen when I finish?**
The user can return to the relevant authoring group without losing work.

## 6. Preview state

Preview is the protected visual model of the public job presentation. It uses
the approved public content hierarchy and visibility rules for both Create and
Edit without making the job public.

Preview answers: **How will this job appear to a job seeker when it is Live?**
It does not submit, publish, change lifecycle, grant access, or expose
protected application data to an unauthorized viewer. Review and Preview must
remain visibly and semantically distinct.

## 7. Submit, publish, and outcome states

The finish action states its consequence before mutation:

| Condition | Outcome treatment |
|---|---|
| Standard review path | **Submit for Review** → Awaiting Review and Not Live |
| Approved direct-publish trust and complete eligibility | **Publish Job** → Published and Live only after public-eligibility confirmation |
| Published but eligibility incomplete | Published — Needs Attention / Not Live |
| Draft save or incomplete work | Draft saved; no publication implied |
| Service/validation failure | Unable to complete; preserve employer work and show recovery path |
| Permission or membership failure | Blocked authoring state; no mutation or access implication |

Confirmation must report the actual result. Claim approval, membership, and
direct-publish trust remain separate facts.

## 8. Responsive inheritance and employer-specific deltas

- Inherit the approved Job Center shell, typography, spacing, controls, footer,
  drawer, and responsive layout classes.
- At narrow widths, stack field groups and action regions using shared responsive
  language while preserving order, labels, and touch targets.
- Keep employer context and Review/Preview distinction visible at every width.
- Do not create authoring-specific breakpoints, alternate mobile steps, or a
  second employer shell.
- Minor browser typography and spacing polish belongs in implementation notes.

## 9. State matrix and render economy

| State family | Covered treatment |
|---|---|
| Create/Edit normal | Shared populated authoring composition with Create/Edit label/state substitution |
| First access/context | Login, no membership, invalid selected employer, and multi-employer selector inherited from EMP-AUTH002/003 |
| Validation | Field errors, Review blockers, preserved work, and recoverable retry |
| Review | Shared factual readiness checkpoint |
| Preview | Shared protected public-presentation checkpoint |
| Finish | Submit for Review, direct Publish, Draft saved, pending, Live, Needs Attention, and unable-to-complete outcomes |
| Permission/lifecycle | Existing route access errors and state-specific edit restrictions |

Maximum: two rendering passes.

1. **Base authority render:** one representative Create/Edit authoring flow with
   selected employer, grouped fields, Review, Preview, and finish controls.
2. **Bounded state sheet:** one compact treatment of validation blockers,
   permission failure, pending/Live/Needs Attention, draft save, and unable to
   complete outcomes.

## 10. Explicit V1.1 deferrals

- New authoring steps, routes, schemas, permissions, statuses, or lifecycle
  actions.
- Separate visual authorities for every field error, field combination, or
  confirmation variant.
- Rich autosave/draft-history presentation beyond the approved retry contract.
- Advanced employer roles, analytics, bulk authoring, or administrative review.
- Any Dashboard, My Jobs inventory, lifecycle-management, metrics, moderation,
  or admin composition.

## 11. Acceptance criteria

EMP-AUTH004 visual authority is complete when:

- Create and Edit share one coherent authority family and preserve job identity
  correctly;
- employer context is explicit, read-only where system-owned, and persists
  through authoring, Review, Preview, confirmation, and return paths;
- field hierarchy and grouping match Employer UX V1 and current routes;
- validation and blocking errors are recoverable and never imply publication;
- Review clearly validates facts, readiness, blockers, and consequence;
- Preview clearly shows protected public presentation and never changes state;
- Submit for Review and direct Publish remain distinct and truthful;
- success, pending, blocked, permission-error, and unable-to-complete states are
  covered or explicitly inherited;
- responsive inheritance introduces no alternate workflow or unsupported
  functionality;
- the two-render maximum is respected and minor polish is implementation-only;
- `git diff --check` passes.
