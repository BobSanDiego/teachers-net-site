# Employer Authority Contract v2

Status: Canonical authority for employer identity, membership, roles, trust,
affiliation, authoring, job visibility, and employer asset continuity.
Ticket: JC056-EMPLOYER-AUTHORITY-CONTRACT-REVISION
Supersedes: `employer-capability-evolution-contract-v1.md` wherever it permits
multiple simultaneous active employer memberships or employer-wide My Jobs for
every active member.

This is a documentation contract. It authorizes no application, schema,
migration, UI, or runtime change.

## Authority order and core distinctions

User identity is the ordinary individually authenticated Teachers.Net /
WordPress account. A user may express employer or recruiter intent without
gaining publication authority.

Employer is the canonical organization/workspace identity. It owns employer
assets and relationships independently of any individual user.

Employer Membership is the authorization relationship between one User and one
Employer. It conveys a role and capabilities; it does not transfer employer
ownership to the user. No shared or master employer login is permitted.

The canonical authority order is:

Teachers.Net platform authority > Employer Admin authority > Recruiter authority.

Membership/role authority != platform publication trust authority.
Employer trust != membership status != job lifecycle status.
Job management/trust authority != commercial/purchase authority.

## One-employer affiliation invariant

In this product phase, a user may have at most one active employer affiliation.
Simultaneous Admin/Recruiter memberships across unrelated employers, agency or
multi-client recruiter accounts, and cross-employer active access are not
supported. A change of employer requires an explicit leave, revoke, or
re-affiliate workflow. Historical and inactive memberships remain available for
audit/history and must not be silently migrated.

This rule supersedes the prior adaptive capability statement that supported one
or more active employer memberships.

## Founding employer and founding Admin

A verified Teachers.Net user may establish a new, initially provisional
employer without waiting for employer approval. The founding user automatically
becomes the first Employer Admin. Provisional Admin authority permits employer
information management, shared School / Jobsite management, job drafts,
complete wizard authoring, and submission of the first job for review.

Provisional status must not block authoring. It must block unreviewed public
publication through the platform trust gate.

## Trust and publication

The trust gate occurs after complete authoring and submission and before the
first untrusted employer job becomes publicly approved/published. No policy may
use “has this employer previously published a job?” as an implicit authority;
that fact may only be an input to an explicit canonical platform trust decision.

Teachers.Net owns approval, moderation, publication eligibility, suspension, and
quarantine authority. A later trusted-publisher capability, if approved, must
still be explicit and separately revocable.

## Employer roles and My Jobs scope

### Employer Admin

Employer Admins may manage the employer workspace, all employer jobs, shared
School / Jobsite resources, members, and affiliation requests where the final
capability contract grants the operation. They may create jobs, edit Recruiter
jobs, assign Admin or Recruiter roles, and perform permitted employer-level
operations. The founding user receives this role.

### Recruiter

Recruiters may create jobs for their one affiliated employer, see and manage
jobs they created, and consume the employer's shared School / Jobsite catalog.
Resource creation remains capability-controlled. Recruiters do not
automatically see or manage another Recruiter's jobs.

Role scope is server-owned; UI hiding is never authorization.

My Jobs defaults:

| Role | Canonical scope |
| --- | --- |
| Employer Admin | All manageable jobs belonging to the employer |
| Recruiter | Employer jobs where `created_by_user_id` is that Recruiter |

Future Admin filters may include All creators, Me, and an affiliated Recruiter.
No assigned-recruiter field is authorized without a demonstrated need.

Creator provenance identifies who authored a job and may govern Recruiter
visibility. It is not a second ownership authority. `employer_id` remains the
canonical job owner.

## School / Jobsite ownership and continuity

The accepted model remains:

Employer → EmployerSchoolJobsiteRelationship → shared private School / Jobsite
resource → authorized employer users consume the shared catalog → jobs
reference resources.

Schools / Jobsites are employer assets, never Recruiter-private assets. If a
Recruiter leaves, is removed, suspended, or revoked, jobs and resources remain
with the employer, creator provenance remains unchanged, and Employer Admins
retain management authority. No ownership transfer or deletion is required.

## Named Admin continuity

Employers may have multiple individually authenticated Employer Admins. Future
self-service must not leave an employer with zero active Admins without a
controlled recovery path. Last-Admin recovery UX is deferred, but the eventual
membership authority must make the invariant enforceable.

## Existing-employer affiliation

The canonical flow is:

new user → affiliation request → pending request → authorized Employer Admin
approval/rejection → active membership with assigned Employer Admin or Recruiter
role.

Email-domain, name, or knowledge evidence alone never grants access. Self-
approval is prohibited. If no trusted active Admin exists, the request goes to a
future Teachers.Net-controlled verification/recovery path. Exact email and
in-product UX are deferred.

A user with an active affiliation cannot create or accept another active
affiliation. The system must not silently migrate memberships.

## Canonical lifecycle vocabularies

Membership lifecycle: `pending`, `active`, `suspended`, `revoked`. If legacy
`inactive` remains, it must be explicitly classified as a compatibility alias or
terminal historical state; it is not an additional unexplained authority.

Pending affiliation has one canonical authority: the EmployerClaim /
AffiliationRequest record until approval creates or activates the membership.
An inactive membership must not independently represent a second pending state.

Employer trust lifecycle: `provisional`, `verified`/`trusted`, `under_review`,
`suspended`. Existing status and verification fields may carry these concepts
only after their ownership and transitions are defined. Trust must not be
inferred solely from prior publication.

## Authority matrix

| Decision | Canonical owner |
| --- | --- |
| Create ordinary account | User / Teachers.Net identity |
| Establish provisional employer | User intent plus platform-controlled employer service |
| Founding Admin role | Employer membership authority |
| Manage employer | Employer Admin, bounded by capability |
| Request affiliation | User |
| Approve/reject affiliation | Authorized Employer Admin; platform fallback |
| Assign Admin/Recruiter role | Authorized Employer Admin, bounded by platform policy |
| Create School / Jobsite | Membership/capability authority; shared employer catalog |
| Archive employer-resource relationship | Membership/capability authority |
| Create job/draft | Active membership + role/capability |
| Edit own job | Recruiter creator scope or Admin override + lifecycle |
| Edit another Recruiter's job | Employer Admin + lifecycle |
| View own jobs | Recruiter creator scope |
| View all employer jobs | Employer Admin + employer scope |
| Submit job | Active role/capability + complete authoring |
| Approve/publish first untrusted job | Teachers.Net platform trust/moderation authority |
| Publish later trusted jobs | Explicit platform-granted trust capability |
| Suspend/quarantine job | Teachers.Net platform moderation authority |
| Suspend/revoke member | Authorized Employer Admin or platform authority |
| Suspend employer/publication | Teachers.Net platform trust authority |
| Purchase/promote listing | Future separate commercial authority |

No decision may be re-authorized solely by raw WordPress role, creator ID,
employer ID, job status, or a client-side filter without the canonical service
boundary that combines those facts.

## Platform incidents and recovery compatibility

Platform authority must be able to reject/quarantine one job, suspend one
member, place an employer under review, suspend new publication, and preserve
separately valid jobs. Detailed incident UI is deferred.

Future auditable events must remain possible for Admin security recovery,
affiliation, role change/removal, membership suspension/revocation, and
suspicious publication. Detailed notifications, anomaly detection, and
password-reset flows are deferred.

## Commercial separation

Future paid listings and premium promotion may require explicit individual
confirmation, Employer Admin approval, a separate commercial capability, or a
transaction-level entitlement. Membership revocation must not erase historical
paid transactions or purchased placements. Billing, packages, refunds,
transfer policy, and purchase permissions are deferred.

Post-V1 recruiter analytics may be creator-scoped while Employer Admin analytics
may aggregate employer-wide performance. The post-V1 strategy is governed by
`docs/job-center/jobs-roadmap.md`; this contract defines only the authority
separation and does not define metrics or pricing.

## Explicitly deferred

- affiliation email and in-product approval UX;
- last-Admin recovery UX;
- detailed security/recovery notifications and anomaly detection;
- employer incident-management UI;
- premium and billing mechanics;
- refunds and transaction transfer policy;
- agency/multi-employer recruiter model;
- Recruiter job reassignment absent a demonstrated business need.

## Implementation consequences

No implementation follows from this document alone. The bounded sequence is:

1. inventory historical memberships and produce the minimum schema/service plan;
2. establish the canonical server-owned Admin/Recruiter capability boundary;
3. revise founding employer/provisional authoring;
4. revise affiliation approval and role assignment;
5. implement role-scoped My Jobs;
6. continue employer School / Jobsite management;
7. resume wizard integration against this authority.

Dependent documents must point here. The prior multi-employer, inactive-poster-
before-authoring, employer-wide-member, prior-published-shortcut, and
open-ended trusted-member statements are superseded for this product phase.
