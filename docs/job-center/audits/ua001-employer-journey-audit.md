# UA001 - Employer Journey Audit

**Project:** Teachers.Net - Job Center

**Mode:** Documentation-only inspection

**Audit date:** 2026-07-11

**Contract:** `docs/job-center/canonical-v1-contract.md`

**Implementation inspected:** current `wordpress/wp-content/plugins/tnet-jobs`

## Executive summary

The current employer journey has a coherent authorization foundation and most
of the management capabilities required by the Canonical V1 Contract. WordPress
authenticates; active employer memberships authorize dashboard and job actions;
jobs remain attached to one canonical employer; claims require an authenticated
existing account and administrator approval; and create, edit, close, renew,
duplicate, archive-draft, and engagement-count operations enforce employer/job
scope server-side.

The journey is not yet one fully resolved V1 experience. Two onboarding models
coexist: **Request Employer Access** creates a new pending employer and may
create a WordPress subscriber, while **Claim Existing Employer** correctly
attaches verified authority to an existing employer and never grants access
before review. These paths serve different legitimate cases, but the product
does not yet make the distinction sufficiently explicit or provide a clear
existing-employer discovery handoff.

The management journey also contains avoidable discontinuities. Create uses a
multi-step wizard with a live review preview; edit uses a long single-page form
and only offers a public preview when the job is already publicly visible.
Submit is implicit in the create button rather than a separately durable draft
step. Duplicate and renew both create draft copies, but their different intent
and lineage consequences are not strongly explained. Archive is available only
for drafts, leaving no employer-facing archival action for closed or expired
records even though the contract distinguishes close from archive. Metrics are
contract-aligned basic engagement signals, but their icon-only presentation and
the label **Opened** obscure the canonical meaning of **Interested**.

No alternate portal, new schema, new lifecycle, or redesign is recommended.
The highest-value work is to unify journey language and acceptance criteria,
reuse the existing authorization and lifecycle services, reconcile create/edit
presentation, and make the current actions and metrics unambiguous.

## Audit basis and boundaries

This audit compares the stable contract with current routes, public renderers,
services, repositories, administration review, schema, and existing operational
documentation. It does not execute mutating runtime scenarios and does not
authorize implementation.

Contract principles applied throughout:

- one canonical employer and one canonical job entity;
- a source relationship is not employer authority;
- authenticated claim plus human verification precedes active membership;
- employer management continues through the existing dashboard and My Jobs;
- renewals preserve lineage and historical metrics;
- close, expiration, suppression, and archive remain distinct;
- metrics are engagement signals, not applications.

## Workflow inventory

| Workflow | Current behavior | Contract compliance | Primary friction or gap | Implementation opportunity |
|---|---|---|---|---|
| Employer registration / new-employer access | Public **Request Employer Access** collects organization and contact data. The employer service creates a pending employer and inactive membership; it may create a subscriber account when the email has no user. Admin review later activates access. | **Partial.** Appropriate for a genuinely new employer, but account auto-creation conflicts with the contract's claim rule if users mistake this for a claim path. | The route combines organization onboarding, account creation, and access request. It does not first ask whether the employer already exists. Confirmation sends users toward the portal before access exists. | Keep new-employer onboarding distinct, but route authenticated users through WordPress-owned registration/login and explicitly branch **new employer** versus **existing employer claim** before collecting data. |
| Employer claim | `/jobs/employer/claim/` requires login, targets an existing employer ID, collects work email, website, and authority evidence, creates a pending claim, and grants no access until administrator review. Approval uses the existing membership model and records claim events. | **Compliant.** It preserves employer/job identity, requires an existing account and human review, and does not infer authority from matching data. | The claimant must arrive with an employer ID; no obvious employer search/select step was found in the employer journey. Claim status is confirmed once but is not surfaced as a claimant-facing status view. | Add a discovery/handoff entry to the existing claim route and expose pending/returned decision context without creating a second portal. Reuse the current claim service and admin review. |
| Employer dashboard | `/jobs/employer/` resolves active memberships, supports one or multiple employers, shows job counts, recent jobs, views, status, and a **Post a New Job** action. | **Compliant.** Employer-ID scope and active membership preserve authority boundaries and provide immediate post-claim continuity. | Dashboard and My Jobs overlap. Recent jobs offer a subset of the richer management information, and multi-employer selection adds an extra decision before work can begin. | Define dashboard as summary/next-action and My Jobs as complete management. Reuse one status vocabulary and one selected-employer context across both. |
| My Jobs | `/jobs/employer/my-jobs/` lists employer-scoped jobs, grouped and filtered by Live, Awaiting Review, Drafts, and Closed & Expired, with pagination, metrics, and contextual actions. | **Mostly compliant.** It is the correct shared post-claim management surface and enforces membership scope. | Status grouping and action availability require interpretation; archived jobs are excluded from normal management, and icon-only metric headings are difficult to scan or understand. | Preserve the existing surface; standardize plain-language statuses, action descriptions, and accessible metric labels. Add acceptance coverage for every lifecycle group and multi-employer selection. |
| Create wizard | **Post a Job** uses active membership selection, a four-step client-side wizard, Core Terms selectors, application instructions, salary/location fields, and a live preview. Trusted employers publish directly; others submit for moderation. | **Mostly compliant.** Uses one job model, employer scope, application instructions, and established moderation/trust behavior. | There is no durable **Save draft** checkpoint in the visible create journey. The final action changes by trust state, and trust derives from prior publication rather than claim verification. A browser interruption can lose a long entry. | Keep the existing service and wizard. Add explicit draft persistence/continuation and make the final state consequence visible before submission. Audit the trust rule separately; do not conflate it with verified employer authority. |
| Edit flow | Employers may edit owned draft, submitted, or published jobs. Untrusted edits to published jobs return to review. The edit page is one long form and reuses field renderers but not the create wizard structure. | **Mostly compliant.** Ownership, status, nonce, moderation, and archived/expired rejection are enforced. | Create and edit are parallel UX workflows with different structure and preview behavior. Submitted jobs remain editable, but the effect on the moderation queue is not explained in one consistent place. | Reuse one field grouping, validation summary, and review component for create/edit while retaining existing services and lifecycle rules. |
| Preview | Create provides a live in-form review preview. Edit provides **Preview Job** only when the record already qualifies for a public detail URL. | **Partial.** Public records remain truthful, but draft/submitted edits cannot preview the actual detail presentation before submission. | “Live Preview” is a form summary, while “Preview Job” is a public page; the two terms imply equivalence but provide different fidelity and availability. | Define a single preview contract. Reuse the safe detail renderer in a non-public, ownership-checked preview mode or clearly label the wizard summary as **Review** rather than public preview. |
| Submit | Create writes a job as `published` for a trusted employer or `submitted` for standard review and shows a tailored confirmation. Edit saves in place or returns an untrusted published job to review. | **Mostly compliant.** Moderation and public visibility rules are preserved. | Submission is inseparable from initial creation; there is no explicit save-versus-submit choice. Confirmation language is good, but the pre-submit state consequence is not equally prominent. | Add an explicit review summary and final-state disclosure; use existing create/update services and moderation queue rather than a new submission workflow. |
| Renew | Closed or date-expired jobs can be renewed. Renewal creates a new draft, records `renewed_from_job_id`, copies classifications, sends renewal communication, and opens the new draft for editing. | **Compliant in model.** A new posting preserves lineage instead of reactivating stale history. | “Renew” may be read as extending the same listing, while current behavior clones to a draft. Historical metrics remain on the source and are not explained at the decision point. | Add confirmation copy explaining new draft, new publication review, source history, and metric separation. Preserve `renewed_from_job_id`. |
| Duplicate | Eligible non-archived jobs can be duplicated to a draft, with employer scope and copied classifications. | **Compliant as a convenience capability.** It does not overwrite the source job. | Duplicate and renew produce nearly identical immediate outcomes, but only renewal carries lineage semantics. The distinction is not clear in the action labels alone. | Keep both operations but explain intent: **Duplicate as new job** versus **Renew this closed/expired job**. Prevent accidental near-duplicate publication through existing review/validation paths. |
| Archive | Employers can archive drafts only. The service marks the job archived and removes it from normal employer management. | **Partial.** Soft archive is correct, but V1 contract treats archive as distinct from close and expiration across the lifecycle, not only for abandoned drafts. | Closed and expired jobs have no employer-facing archive action. Archived records disappear without an employer archive/history view or recovery explanation. | Decide and document the employer-visible retention rule: either archive remains admin/draft-only, or closed/expired records gain a separately authorized archive action and history view. Do not overload close. |
| Close | Published jobs can be closed; already closed jobs pass access checks but the UI only renders the action when closure is meaningful. Close removes normal public eligibility and preserves the record. | **Compliant.** Close remains distinct from archive and retains history. | The destructive consequence is presented as a direct inline form with no evidence of a confirmation step or filled-position reason. There is no reopen action; renewal is the available next lifecycle. | Add a concise confirmation and explain **close** versus **renew**. A reason may be useful operationally, but should be a separate product decision, not silently added. |
| Metrics | Dashboard/My Jobs aggregate unique engagement rows into Views, Saved, and Interested/revealed-application counts. Dashboard emphasizes views; My Jobs shows three icon columns and renders canonical `interested` as **Opened**. | **Mostly compliant.** Counts are user signals, unique per user/job, and do not claim to be applications. | Icon-only headers are ambiguous and use emoji-like glyphs. **Opened** is less precise than the contract definition and may suggest an application open rather than revealed instructions. No trend or time window is supplied, which is acceptable for V1 but should be explicit. | Use text/tooltips aligned to canonical definitions: Views, Saves, Application instructions opened (or Interested). State **all-time unique users**. Keep V1 basic; do not build an analytics dashboard. |

## Findings

### 1. The authorization model is the strongest part of the journey

All employer management routes resolve active memberships and verify that the
job's `employer_id` belongs to one of those memberships. Mutations use nonces
and service-layer lifecycle operations. Archived and expired jobs are rejected
from ordinary edit paths. This is consistent with the architecture rule that
Jobs authorizes while WordPress authenticates.

The existing claim implementation has superseded the obsolete pre-JREAL017
finding that no claim path existed. It correctly uses a pending claim plus
administrator decision, then activates the existing employer membership rather
than replacing the employer or jobs.

### 2. Onboarding has two valid concepts but one ambiguous front door

New-employer onboarding and existing-employer claim are not duplicates at the
service level. They become duplicate-feeling workflows at the product level
because both appear to promise “employer access” without an early identity
decision. The request-access path's account auto-creation is particularly risky
when used by someone who should claim an existing employer.

The V1 journey should explicitly ask: **Is your organization already listed on
Teachers.Net?** The answer should route to existing claim or new-employer review.
This is workflow clarification, not a new authority system.

### 3. Create and edit duplicate presentation, not business logic

Both paths use established services, field helpers, validation, Core Terms, and
membership authorization. The duplicate surface lies in interaction structure:
create is stepped and preview-driven; edit is long-form and public-preview-
dependent. This increases learning cost and creates inconsistent acceptance
criteria for the same job facts.

### 4. Lifecycle actions are technically sound but under-explained

Close changes the current record's lifecycle. Duplicate creates an unrelated
new draft. Renew creates a new draft with lineage. Archive removes an abandoned
draft from normal management. Those distinctions are meaningful in code and in
the contract, but the UI relies on terse action labels to teach them.

### 5. The current metric scope is appropriate for V1

Views, saves, and application-instruction reveals are sufficient basic
reporting. The product gap is comprehension, not additional analytics. The
current labels should make uniqueness, time scope, and non-application meaning
clear before any richer reporting is considered.

## Product gaps

1. A single employer onboarding decision point that separates an existing
   employer claim from a new organization request.
2. Existing-employer discovery before the claim form.
3. Claimant-visible pending/returned/rejected status and next step.
4. Durable save-draft/continue-later behavior in create.
5. One coherent review/preview contract across create and edit.
6. A settled employer archive/retention policy for closed and expired jobs.
7. Clear metric definitions and time scope.
8. End-to-end acceptance evidence covering every employer lifecycle and a
   claimed imported employer with pre-existing jobs.

## UX observations

- The portal navigation is compact and consistent, but the distinction between
  Dashboard and My Jobs depends on content rather than explicit task framing.
- Multi-employer selection is supported thoughtfully, although selection should
  persist consistently across Dashboard, My Jobs, Post, and Edit.
- The create confirmation is the clearest lifecycle communication in the
  journey; its explicit status and next-step pattern should inform edit, close,
  renew, duplicate, and claim confirmations.
- The create wizard reduces cognitive load, but absent durable draft behavior
  makes the apparent safety of multiple steps misleading.
- Destructive or identity-changing actions need consequence copy. They do not
  require modal-heavy redesign; a confirmation page or compact inline review is
  sufficient.
- Emoji-like metric headings conflict with the design-system requirement for
  simple, consistent outline icons and reduce accessibility.
- Status labels vary between raw lifecycle language and user-facing phrases.
  One vocabulary should be shared by dashboard counts, My Jobs groups, pills,
  notices, emails, and moderation explanations.

## Engineering observations

- Reuse `TNet_Jobs_Employer_Claim_Service`, employer memberships, and the
  current admin claim review. No parallel claimed-employer portal is needed.
- Preserve `TNet_Jobs_Job_Service` as the lifecycle authority. UI improvements
  should not duplicate close, renew, duplicate, archive, trust, or moderation
  rules in renderers or JavaScript.
- Renewal lineage is durable through `renewed_from_job_id`; duplication does not
  carry equivalent lineage. Acceptance tests should protect that distinction.
- Term copying after renew/duplicate is orchestrated in the public layer after
  job creation. A partial failure can leave a new draft without copied
  classifications; future implementation should make the operation recoverable
  or transactional without changing user-visible lifecycle semantics.
- Request access and claim have different authentication behavior. That
  difference should be deliberate, documented, and covered by security tests.
- Current employer actions are handled through the edit route and dispatched by
  a posted action value. This is compact, but requires explicit regression tests
  for nonce, ownership, status, archive, and cross-employer rejection per action.
- Archived jobs are intentionally excluded from ordinary employer operations.
  Any history/recovery capability should reuse repository queries and preserve
  soft deletion rather than expose direct hard deletion.
- Metrics aggregate engagement repository data per job. Preserve unique-user
  semantics and avoid calling these applications, applicants, or conversions.

## Obsolete behavior and duplicate workflow summary

### Obsolete or misleading

- Treating **Request Employer Access** as the universal employer onboarding
  path is obsolete now that verified existing-employer claim exists.
- Auto-created subscriber accounts are incompatible with the claim contract and
  should never occur on the claim branch.
- The historical statement that claim capability is absent is no longer true.
- **Opened** is an imprecise presentation label for the canonical Interested /
  application-instructions-revealed metric.

### Duplicate or overlapping

- Dashboard recent jobs and My Jobs overlap, but can remain if their roles are
  explicitly summary versus management.
- Create wizard and edit form duplicate job-field presentation and review
  behavior; they should converge on shared components and acceptance rules.
- Renew and duplicate both create drafts; they must remain separate operations
  because only renew represents lifecycle lineage.
- New-employer request and existing-employer claim collect overlapping identity
  evidence, but they must not be merged into one service because they target
  different employer identities.

## Prioritized recommendations

### P0 - V1 employer-journey acceptance

1. **Define and verify the onboarding fork.** Present new-employer request and
   existing-employer claim as explicit alternatives. Ensure claim always uses an
   authenticated existing account and never creates an employer or user.
2. **Run one end-to-end claimed-employer acceptance scenario.** Verify approval
   activates membership to the existing employer, exposes existing imported
   jobs in Dashboard/My Jobs, preserves IDs/URLs/provenance/history/metrics, and
   rejects access before approval and after revocation.
3. **Run lifecycle action acceptance across all statuses.** Verify create,
   submit/direct publish, edit/resubmit, close, renew, duplicate, and draft
   archive, including nonce, ownership, cross-employer, expired, and archived
   rejection cases.
4. **Settle create draft persistence.** Either implement durable save/continue
   as a V1 requirement or explicitly document that submission is one-session;
   do not imply recoverability that does not exist.
5. **Define one preview contract.** Employers must be able to review the actual
   intended posting before initial submit and material edit, without making a
   draft or submitted job public.

### P1 - Clarity and workflow consolidation

6. Reuse one job-field grouping and review component across create and edit.
7. Add concise consequence confirmation for close, renew, duplicate, and
   archive; state that renew creates a new draft with lineage and duplicate
   creates a separate new draft.
8. Standardize lifecycle vocabulary and confirmation patterns across Dashboard,
   My Jobs, forms, moderation, and communications.
9. Replace icon-only metric headings with accessible text/icon labels and state
   the canonical definitions and all-time unique-user scope.
10. Decide whether employers may archive closed/expired jobs or whether those
    records remain permanently in a closed/expired history view.

### P2 - Operational resilience after V1 acceptance

11. Make classification copying for renew/duplicate recoverable as one coherent
    operation and report partial failures clearly.
12. Add claimant-facing claim status/history using the existing claim records;
    do not create a notification center.
13. Add richer metric trends only after basic definitions and data quality are
    validated with the pilot. Advanced analytics remain outside V1.

## Recommended acceptance sequence

1. New-employer request versus existing-employer claim.
2. Claim approval, dashboard continuity, revocation, and cross-employer denial.
3. Create draft decision, review/preview, submit, and moderation/direct publish.
4. Edit and resubmit behavior for draft, submitted, and published jobs.
5. Close, renew lineage, duplicate independence, and archive retention.
6. Dashboard/My Jobs status and metric comprehension.
7. Controlled pilot employer walkthrough with recorded friction and no code
   expansion beyond separately approved tickets.

## Verification record

- Documentation-only scope: this audit file only.
- Jobs plugin application code: unchanged by UA001.
- Runtime mutation: not performed.
- Final repository and whitespace verification are reported with the commit.
