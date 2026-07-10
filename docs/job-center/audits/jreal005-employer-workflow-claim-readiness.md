# JREAL005 - Employer Workflow and Claim Readiness

**Project:** Teachers.Net - Job Center
**Audit mode:** Inspection and documentation only
**Audit date:** 2026-07-10
**Evidence sources:** JREAL001-JREAL004, current Jobs plugin schema, employer services and repositories, public employer routes, administration handlers, communication paths, and the local fixture runtime described in JREAL001.

## 1. Scope and stop boundary

This audit defines the smallest coherent V1 contract for an imported employer to become a verified, self-managing Teachers.Net employer. It distinguishes the current implementation from the recommended contract. It does not authorize a claim feature, schema change, data migration, recruiter invitation, or runtime test.

JREAL001 (`3549f20`), JREAL002 (`3ed50af`), JREAL003 (`361a113`), and JREAL004 (`d4d8331`) are present on root `main` and pushed to `origin/main` before this report.

## 2. Existing architecture

| Area | Verified current implementation | Consequence for a claim workflow |
|---|---|---|
| Employer identity | `wp_tnet_jobs_employers` has a stable employer ID, unique slug, name, status, verification status, website, description, creator, verifier, and timestamps. | An imported employer can remain the same employer record after a successful claim. |
| Employer access | `wp_tnet_jobs_employer_users` links one WordPress user to one employer with a unique pair, a role, and active/inactive state. | The existing membership is the correct access primitive for an approved claimant. |
| Job association | Every job can reference `employer_id`; the same public Jobs entity serves imported and employer-posted jobs. | Claiming must attach authority to existing jobs, not create replacement public records. |
| Employer portal | `/jobs/employer/`, `/jobs/employer/my-jobs/`, create, edit, close, archive, renew, and duplicate routes resolve access from active employer memberships. | A claimant with an active membership will use the established dashboard and My Jobs surfaces. |
| Administration | Employer request approval/rejection, employer archive, membership administration, and job moderation require `manage_options` with nonce protection. | Administrators already have the appropriate review boundary, but no claim-review operation exists. |
| Communications | Employer request and job-lifecycle templates exist; recruiter lifecycle delivery requires an active employer membership for the job creator. | Claim decisions can reuse communication conventions, but unclaimed imported jobs have no employer-recipient path today. |

The local fixture runtime has 50 imported employers, 250 fixture jobs, and zero employer-user memberships. It therefore validates association records and public listings, not an exercised employer-ownership workflow.

## 3. Employer lifecycle and current authority model

### Current lifecycle

1. An administrator or importer creates an employer record.
2. A public requester may submit **Request Employer Access**.
3. The current request flow creates a *new* pending employer record plus an inactive requester membership.
4. An administrator approves or returns that pending request. Approval makes that new employer active/verified and activates the requester membership.
5. An active membership grants access to the employer dashboard and the employer's jobs.
6. An administrator can archive an employer; a membership can be deactivated.

This is appropriate for onboarding a new employer. It is not a mechanism for finding and claiming an existing imported employer because it creates a distinct employer record and never attaches the requester to an existing one.

### Current authority boundaries

| Actor | Current authority | Required interpretation for V1 |
|---|---|---|
| Import operator | Creates/updates imported employer and job data under operator authority. | Import authority must remain separate from employer authority. It does not establish a recruiter relationship. |
| WordPress user | May access an employer only through an active membership. | WordPress authentication proves account control, not employer authority. |
| Employer member | Can operate the dashboard for the exact employer ID in the membership. | Membership remains the authorization grant after an approved claim. |
| Administrator | Reviews employer requests, memberships, and moderation through `manage_options`. | Administrator is the sole V1 authority-verification and claim-decision actor. |

The current "trusted employer" behavior is not a verification model: an employer is treated as trusted for direct publishing only after it has a prior published job. It must not be used as proof that a claimant controls an imported employer.

## 4. Recruiter onboarding finding

The existing `request_employer_access()` path looks up the submitted email and **creates a WordPress subscriber account when none exists**. That behavior is incompatible with the V1 claim rule.

**V1 decision:** recruiter accounts must never be auto-created by an employer claim or access request. A claimant must authenticate with an existing WordPress account before beginning a claim. Account registration, authentication, and recovery remain WordPress-owned flows.

This is a V1 blocker for a claim-capable release, but it does not affect the current public marketplace or imported jobs.

## 5. Recommended V1 employer authority model

The employer record remains the canonical internal identity. Its public jobs, term assignments, URLs, lifecycle history, and source provenance remain attached to that same record.

| Employer state | Who has authority | Public job treatment |
|---|---|---|
| Imported, unclaimed | Import operator and administrators only. No recruiter membership is implied. | One normal public job entity; imported lifecycle and source controls apply. |
| Claim pending | Import operator/admin retain authority; claimant has no job-management access. | No public listing disruption. |
| Verified and claimed | Active employer memberships authorize the verified employer's dashboard operations; administrators retain moderation and override authority. | Same public job entity and URL; employer can manage eligible jobs under the approved lifecycle rules. |
| Claim returned, rejected, or revoked | Import operator/admin retain authority; any provisional access remains inactive. | Existing public jobs remain governed by their current lifecycle. |

This deliberately separates **identity**, **authority**, and **publication**:

- employer name, slug, website, and imported association identify an organization;
- an approved active membership authorizes a specific authenticated user for that organization; and
- moderation/lifecycle state determines whether an individual job is public.

## 6. Recommended claim workflow

1. A logged-in user selects an existing employer record to claim. The flow must make clear that it is claiming an existing Teachers.Net employer, not creating a new one.
2. The user supplies authority evidence appropriate to the organization. An email domain, matching name, or public website is supporting evidence only; none is sufficient on its own.
3. The claim remains pending with no active employer membership and no dashboard/job authority.
4. An administrator reviews the claimed employer, authenticated requester, evidence, potential duplicate employers, existing memberships, and any source/provenance conflicts.
5. Approval attaches or activates the requester as an employer member for the existing employer ID, records the administrator decision, and sends the existing-style decision communication.
6. Return/rejection leaves imported authority intact and grants no access. A later corrected claim may be reviewed without creating a duplicate employer.

The V1 claim must not silently match by name, slug, website, or email domain; silently merge employers; create a recruiter account; activate a requester before review; or make imported records disappear.

## 7. Recommended ownership transition

An approved claim is an authority transition, not a replacement, merge, or re-import:

- retain the employer ID, job IDs, public URLs, classifications, engagement, lifecycle history, and source/provenance history;
- add the approved claimant through the existing employer-user relationship for that employer ID;
- make the employer dashboard and My Jobs enumerate the already-associated jobs;
- retain administrator moderation authority and audit responsibility;
- preserve an explicit distinction between source-controlled imported facts and employer-managed posting facts; and
- route source-versus-employer disagreement, duplicate employer identity, or multiple competing claimants to administrator review rather than automatic resolution.

Before claim, imported-job corrections and lifecycle actions are operator/admin work. After claim, employer members may use the existing employer workflow for their employer's eligible jobs, subject to moderation and lifecycle constraints. A verified claim does not authorize an employer to overwrite source provenance or an administrator's moderation history.

## 8. Dashboard continuity

The existing dashboard, My Jobs, job create/edit, close, archive, renew, duplicate, status filters, and job metrics are employer-ID scoped. No separate claimed-employer portal is needed.

After approval, dashboard continuity should be immediate: the claimant sees the same employer record and the same already-associated jobs through an active membership. Existing public jobs continue to be one public entity regardless of whether they were imported or employer-posted. The dashboard must not duplicate jobs, create a replacement employer, or reset job status simply because authority changed.

Where an imported job has no active recruiter membership, existing lifecycle email logic has no recipient. Until a claim is approved, source/operator communications remain the authority path. After approval, future employer lifecycle communications should resolve through the active employer membership; prior events remain historical records.

## 9. Security model

- WordPress authenticates users; Jobs memberships authorize employer-specific access.
- The portal must continue to require an active membership for the selected employer ID. Administrators do not currently bypass all employers in the public portal shell, and that separation should remain.
- Claim review and approval/rejection must remain administrator-only, nonce-protected actions.
- Claim authority requires human verification. Email-domain alignment, employer name, slug, website, or a source record are evidence, not autonomous authorization.
- A claimant can never select another employer ID in a request to gain access; server-side membership and employer/job matching remain authoritative.
- Membership deactivation/revocation must immediately remove portal authority without changing public job identity or deleting history.
- Imported and employer-posted jobs remain one public entity. Authority provenance must not become a public authorization bypass.

## 10. Reusable components

| Existing component | Appropriate claim use |
|---|---|
| Employer record and stable ID | Claim target and durable organization identity. |
| Employer-user membership service/repository | Approved claimant access, multiple members, and later deactivation. |
| Active-membership portal checks | Dashboard and job-management authorization after approval. |
| Administrator employer review capability/nonces | Review boundary and operational pattern. |
| Employer statuses and verification fields | Current state vocabulary and review presentation; not sufficient alone to represent a claim. |
| Employer decision email templates | Claim acknowledgement/decision communication pattern. |
| Job employer association and employer-scoped queries | Immediate My Jobs/dashboard continuity for associated imported jobs. |
| Job events | Supplementary event history, not a complete claim/provenance record. |

## 11. Implementation gaps and V1 blockers

| Gap | Classification | Reason |
|---|---|---|
| Existing-employer discovery and claim request | V1 blocker | Current request path always creates a new pending employer. |
| Authenticated, no-auto-account claim entry | V1 blocker | Current request path auto-creates a subscriber for a new email. |
| Human authority-evidence review state | V1 blocker | No claim evidence, claim decision, or existing-employer attachment exists. |
| Claim approval/rejection/revocation audit trail | V1 blocker | Current approval applies only to a newly created employer request. |
| Imported versus claimed ownership/provenance transition rules | V1 blocker | Importer and employer authority have no implemented reconciliation boundary. |
| Duplicate/competing-claim exception handling | Pilot blocker | Existing unique slug and memberships help, but there is no workflow for conflict review. |
| Operator communication routing for unclaimed imported jobs | Pilot blocker | Current recruiter lifecycle email requires an active membership; source contact/provenance is not stored. |
| Additional employer roles and delegated member administration | Launch polish | Current `poster` role and administrator membership tools are sufficient for the smallest verified-claim pilot. |
| Automated domain verification, SSO, bulk claims, public verification badges | Deferred | Human review is the required V1 trust boundary. |

## 12. Engineering Director decisions

1. An imported employer is a stable existing employer identity, not a lead to recreate when contacted.
2. Imported and employer-posted jobs remain one public Jobs entity; claim changes authority only.
3. Import authority and employer authority are distinct at every stage.
4. A claim starts from an authenticated existing WordPress account. Recruiter accounts are never auto-created.
5. Human administrator verification is mandatory before an employer membership becomes active; matching email domains or employer names are insufficient alone.
6. Approval attaches the claimant to the existing employer ID through the existing membership model and preserves the job/dashboard history.
7. Source provenance, administrator moderation, and historical lifecycle data remain protected through and after claim.
8. Existing dashboard and My Jobs routes are the post-claim continuity surface; no alternate employer portal is needed for V1.
9. Trust-by-prior-publication is a publishing convenience only and must not be conflated with employer identity or claim verification.

## 13. Preserved architecture

- Terms classify. Jobs authorizes. WordPress authenticates.
- Core Terms remains outside employer identity, claim authority, and job lifecycle ownership.
- The public job remains a single entity whether its source is import or employer entry.
- The current employer record, membership relationship, employer-ID-scoped dashboard, job lifecycle, moderation controls, and public routes remain the correct foundation.
- The import pipeline may associate an employer with jobs without creating a recruiter account or granting employer authority.

## Verification record

- Confirmed JREAL001-JREAL004 commits and pushed root baseline before writing this audit.
- Inspected the employer schema, employer and membership services/repositories, public access-request route, employer portal and My Jobs access checks, employer create/edit operations, job trusted-publish condition, administration handlers, job lifecycle email recipient resolution, and seed/CSV actor handling.
- Distinguished current behavior from the recommended contract throughout.
- Confirmed the current public request path does auto-create a subscriber; the report explicitly identifies that as incompatible with the V1 claim rule that recruiter accounts are never auto-created.
- No Jobs plugin files, Core Terms files, runtime data, schema, imports, UI, or application code changed. Only this root audit document is added.
