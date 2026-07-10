# JREAL003 - Application, Expiration, and Public Lifecycle Audit

**Project:** Teachers.Net - Job Center

**Audit mode:** Inspection, analysis, and documentation only

**Audit date:** 2026-07-10

**Evidence base:** JREAL001 and JREAL002, current Job Center continuity documents, current Jobs implementation, and read-only local runtime checks. This report defines application/lifecycle recommendations only; it does not authorize implementation.

## 1. Scope and stop boundary

This audit defines the smallest coherent V1 contract for public application behavior, application integrity, visibility, expiry, closure, archival, source disappearance, renewal, retention, and lifecycle communications across employer-posted and imported Jobs records.

No job, employer, option, cron event, schema, public template, source URL, provider, or email was changed or invoked. Any path requiring a lifecycle mutation is marked **Implementation found - lifecycle mutation verification deferred**.

## 2. Relevant JREAL001 and JREAL002 findings

| Carried-forward finding | Application/lifecycle consequence |
|---|---|
| Jobs has one public job entity; imported and employer-posted records share the public finder/detail model | V1 needs one public visibility and application rule, with internal authority/provenance differences only. |
| Stored methods are `external_url`, `email`, and `internal`; all currently use free-form `apply_url` text | Method semantics and validation must be made explicit before a real pilot. |
| Browse, detail, alerts, structured data, and employer views have overlapping but different visibility checks | A canonical conceptual visibility predicate is a V1 blocker. |
| Local runtime has 205 published, 8 closed, 7 explicit expired, 5 archived, 15 submitted, and 10 draft rows | Explicit expired records and date-derived expiry both exist and must be reconciled. |
| Source identity, provenance, run tracking, reconciliation, suppression, and claim state are absent | Source disappearance and imported-record authority cannot safely be inferred from current status alone. |
| Employer renew/duplicate create new drafts with copied values and copied Core Terms; no lineage storage exists | V1 needs a conceptual lineage rule so renewal/reposting does not create silent duplicates. |
| Existing event rows and communications exist but no durable run/exception model exists | Lifecycle notifications can be reused only after provenance/run/authority context is available. |

## 3. Existing application architecture

### Storage and validation

`wp_tnet_jobs` stores `apply_method` and one free-form `apply_url` text field. `TNet_Jobs_Job_Service::is_allowed_apply_method()` allows `external_url`, `email`, and `internal`. The current service explicitly requires non-empty instructions only for `external_url`; repository insert requires non-empty `apply_url` for every creation path. No method-specific URL, email, or written-instruction validator exists.

The current labels in `TNet_Jobs_Public::apply_method_label()` are:

| Stored value | Current label | Current meaning in practice |
|---|---|---|
| `external_url` | Employer website | Free-form text displayed as linkified instructions |
| `email` | Email address | Free-form text displayed as linkified instructions; no dedicated email column or mailto construction |
| `internal` | Written instructions | Free-form text displayed as linkified instructions; no internal application workflow exists |

### Rendering and protected reveal

`TNet_Jobs_Public::render_detail_apply_control()` and `render_detail_apply_section()` implement the current flow:

- logged-out visitors see a login CTA and cannot view the stored instructions;
- logged-in visitors must submit a `reveal_instructions` form before the instructions display;
- reveal uses `TNet_Jobs_Engagement_Service::reveal_instructions()` and stores `revealed_at` for that user/job pair;
- `render_apply_instructions()` calls `make_clickable()` on the sanitized stored text; it does not route through an application relay, validate the destination, or detect destination failure.

Closed and expired detail pages show status notices and no apply control. Jobs V1 does not receive applications, send application email, store resumes, or act as an ATS.

## 4. Recommended V1 application-method contract

The smallest useful V1 method taxonomy retains three user-facing concepts, but makes their semantic boundaries explicit.

| Method concept | Required for storage | Required for publication | Optional enhancement | Operational metadata | Notes |
|---|---|---|---|---|---|
| External application destination | A single absolute external destination | Valid HTTPS destination, intelligible association with this role, and a current integrity outcome | external ATS/provider name, public CTA distinction | last verified time, destination result, source/application URL relationship | Covers employer careers and job-specific ATS URLs. The preferred public label is semantic, such as “Apply on employer site” or “Continue to application.” |
| Email application | One syntactically valid email destination and actionable instructions | Valid email plus enough context for the applicant to act | suggested subject line, contact name, role mailbox label | last verified time, domain/source alignment review result | Render a `mailto:` destination rather than relying on arbitrary text linkification. No Teachers.Net forwarding. |
| Written application instructions | Intelligible instructions with a concrete action path | A usable external URL or email within the instructions; otherwise not public | formatted instructions, deadline, contact name | manual review/verification outcome | This is a fallback for genuinely nonstandard applications. It must not imply an internal Teachers.Net application process. |

`internal` is misleading as a stored concept because no internal application workflow exists. The final storage naming is outside this audit; the V1 semantic label should remain “written application instructions,” not “internal application.”

Application deadline, source application URL, fallback contact, and external ATS name are useful optional facts. Destination verification state and last verification time are operational metadata, not public copy by default.

## 5. Application-validation matrix

| Method / condition | Storage acceptance | Publication eligibility | Public behavior | Exception action |
|---|---|---|---|---|
| External HTTPS job-specific URL | accept | publishable when other visibility checks pass | direct external application CTA after approved policy | record verification outcome |
| External HTTPS employer careers page that is not role-specific | accept | publishable with warning only when no job-specific link is available and source policy allows it | accurate “View employer careers” style CTA, not a job-specific application claim | review/verify during pilot |
| Malformed, relative, non-web, unsafe, or blank external URL | hard reject or correction exception | not publishable | no apply CTA | retain raw input and reason |
| Tracking/redirect URL | accept after safe canonicalization and destination review | publishable with warning when destination is still job-specific and safe | external CTA; do not expose misleading redirect semantics | store source/canonical evidence |
| Login-gated ATS URL | accept | publishable when job-specific and current | external CTA; login occurs at the destination | verify source relationship, not browser session outcome |
| Known dead or mismatched external destination | accept only as exception evidence | not publishable while unresolved | suppress apply and public listing under V1 integrity rule | recheck/correct/source review |
| Valid email address plus actionable instructions | accept | publishable when other checks pass | email application CTA / `mailto:` | record validation and source relation |
| Obfuscated, malformed, personal, or unexplained email address | exception/manual review | not publishable until resolved | no CTA | retain source evidence and review decision |
| Written instructions containing a usable URL/email | accept | publishable with warning or publishable under approved policy | “View application instructions” or equivalent | manual source/integrity verification for pilot |
| Written instructions without an actionable path | accept only as draft/exception | not publishable | no CTA | correct, reject, or suppress |
| Closed, expired, archived, source-suppressed, or invalid-application record | retain historical record | not publishable | status/no-longer-accepting message only where historical detail is allowed | lifecycle/source exception path |

## 6. Application-integrity model

For V1, a public actionable job must have all of the following:

1. an approved public lifecycle state;
2. a usable application method with method-specific valid data;
3. no known application-destination failure or mismatch;
4. no elapsed application deadline when one is known;
5. no authoritative source-removal, administrator suppression, or employer removal request that invalidates the listing;
6. source freshness and reconciliation state that permit public visibility; and
7. a current employer/authority relationship that does not make the CTA misleading.

### Integrity outcomes

| Integrity outcome | Public listing | Apply action | Historical detail | Operational handling |
|---|---|---|---|---|
| Verified/current | visible | active method-specific CTA | normal | record verification evidence |
| Unknown but accepted for controlled pilot | visible only under approved warning policy | active CTA with truthful destination semantics | normal | scheduled/manual recheck required |
| Temporary verification issue | not in general results under the V1 guarantee | suppressed | optional limited historical/status page | exception, recheck, no automatic destructive archive |
| Confirmed invalid or dead destination | not public | suppressed | unavailable or limited status page according to retention policy | exception/correction/removal flow |
| Source removed / employer removal | not public | suppressed | retained internally; public historical treatment policy-controlled | reconciliation or administrative action |

Application integrity is not a per-page live HTTP check. For the pilot, it should be established at ingestion/review and revisited on a defined refresh cadence or source change. The audit does not prescribe a crawler or link-monitoring system.

## 7. Login/reveal policy analysis

### Current behavior

The current detail page login-gates all instructions, including external URLs and email addresses, and records a per-user `revealed_at` engagement signal. This gives an “interested” metric and account-conversion prompt, but it inserts a Teachers.Net step between a seeker and an employer/ATS destination.

### Benefits and costs

| Policy | Benefits | Costs |
|---|---|---|
| Preserve login-gated reveal | existing engagement signal; possible account conversion; protects free-form contact details | friction before external application; reduced public utility and conversion; confusing for imported/public source jobs; does not validate destinations |
| Expose direct external/email destinations publicly | truthful employer/ATS routing; less friction; conventional job-board behavior; no implication Teachers.Net receives applications | reduces reveal-based engagement count; contact data becomes public where source permits it |
| Mixed policy | allows public direct URL/email while retaining protected written instructions when warranted | more rules and inconsistent user expectations unless narrowly defined |
| Measure click without gating | preserves a lightweight signal for authenticated users or privacy-safe aggregate instrumentation | existing reveal metric semantics must not be treated as application completion |

### Recommendation and decision

**Recommended V1 policy:** public jobs with verified external URL or email application methods should expose a direct, truthful destination CTA without mandatory login. Written instructions may remain protected only when the source/authority policy requires protection, and only when the instructions still lead to a usable external/email action. Logged-in click/reveal measurement may remain an optional engagement signal, but it must not block an external application path or be described as an application.

This changes an established V1 engagement/conversion choice. **Engineering Director approval is required** before implementation.

## 8. Public CTA semantic matrix

| Condition | Recommended CTA category | Public detail behavior |
|---|---|---|
| Verified employer careers destination | Apply on employer site | direct external destination |
| Verified job-specific ATS destination | Continue to application | direct external destination |
| Valid email application | Email application | mailto/address action with concise instruction |
| Valid written instructions | View application instructions | show instructions; do not imply Teachers.Net application submission |
| Destination unknown/temporary verification issue | Application unavailable | no action until review/recheck |
| Closed job | Position no longer accepting applications | status notice; no apply CTA |
| Expired job | Position no longer accepting applications | status notice; no apply CTA |
| Archived/suppressed/source-removed/invalid application | no normal public CTA | not in public results; public historical page only if retention rule permits | no application action |
| Imported unclaimed job | same method-specific CTA | source/authority must be truthful; no employer endorsement implication |
| Claimed/employer-managed job | same method-specific CTA | employer authority may control the approved destination after claim policy is satisfied |

## 9. Existing lifecycle architecture

| Current primitive | Implementation evidence | Current behavior |
|---|---|---|
| Draft | Job Service/repository | non-public; CSV can create draft; duplicate/renew create draft |
| Submitted | employer create/edit and CSV paths | non-public; moderation queue; lifecycle notification attempted for active recruiter membership |
| Published | admin moderation or prior-published employer flow | normal public finder eligibility is further date/archive/expired-at constrained |
| Returned to draft | `TNet_Jobs_Moderation_Admin` | submitted job set to draft; moderation event and recruiter-returned email path |
| Closed | `TNet_Jobs_Job_Service::close_job()` / repository | sets status closed and `closed_at`; not finder-visible; detail route allows it |
| Expiry | `expires_at`, `expired_at`, explicit seed `expired` status | finder and alerts use date/flag conditions; daily cron only sends notifications; it does not transition status |
| Archived | archive methods/repository | status archived and `archived_at`; excluded from normal public routes |
| Renew / duplicate | Job Service and public employer actions | copies values to a new draft and copies Core Terms assignments; no stored lineage relationship |
| Public detail | `public_detail_job_by_slug()` | allows any unarchived closed job and any unarchived published job, including date-expired published rows |
| Structured data | `jobposting_json_ld()` | emits only when `is_publicly_visible_job()` is true |

### Current predicate differences

| Surface | Current condition |
|---|---|
| Finder `browse_jobs_query()` | published, unarchived, `expired_at` empty, `expires_at` not past |
| Public visibility helper | published, unarchived, `expires_at` present/not past; does **not** test `expired_at` |
| Public detail | any unarchived closed job or any unarchived published job; does **not** apply expiry-date/`expired_at` eligibility before rendering the historical page |
| Alerts | published, unarchived, `expired_at` empty, `expires_at` not past |
| JobPosting JSON-LD | uses public visibility helper; excludes past `expires_at`, but does not explicitly test `expired_at` |
| Employer dashboard | classifies expired using `expired_at` or past `expires_at` |

The checked local runtime had 205 rows meeting finder eligibility, 205 unarchived published rows, 8 unarchived closed detail candidates, and 7 explicit expired rows. This does not prove every edge combination exists, but confirms the system stores explicit-expired and date-derived expiry states concurrently.

## 10. Canonical public-visibility recommendation

### Public actionable visibility

A job belongs in public results, Job Alerts, save/apply controls, and JobPosting structured data only when all of the following are true:

- lifecycle status is published;
- it is not archived, closed, source-removed, suppressed, or on administrator hold;
- it has not reached its effective expiration/deadline and has no canonical expired fact;
- application integrity is current enough for the chosen V1 policy;
- there is no unresolved duplicate, ownership, or source-reconciliation exception that requires a hold; and
- its classification/location conditions meet the established product rules.

Coordinate availability affects Distance Search eligibility, not baseline public visibility. Claim/ownership state changes authority and overwrite rules, not whether an otherwise valid job is a public opportunity.

### Historical detail visibility

Closed and expired jobs may retain a limited historical detail page for audit, recruiter reporting, and user context, but such a page must have no apply/save/alert eligibility, no JobPosting structured data, and a clear non-actionable status. Archived, suppressed, legally removed, and invalid-application jobs should not remain normally public; their historical retention is internal unless a later policy explicitly permits a noindex status page.

## 11. Expiration-model comparison

| Model | Description | Strengths | Weaknesses |
|---|---|---|---|
| A. Status-based | A scheduled action changes published jobs to an explicit expired status | simple dashboard grouping and historical reporting | cron reliability becomes visibility-critical; current service allow-list/runtime differ; source reconciliation and late jobs complicate transitions |
| B. Date-derived | Published status remains; all public behavior derives from `expires_at` | immediate deterministic removal without waiting for cron; aligns with current finder/alerts | weak operational history unless every consumer repeats the same date test; harder to distinguish elapsed expiry from source removal |
| C. Hybrid | Date governs immediate eligibility; an operational expired fact is materialized for history/notifications after expiry | preserves immediate safety, supports audit/dashboard/renewal/email, and avoids making cron the only visibility control | requires one canonical predicate and clear treatment of legacy explicit expired status |

### Preferred V1 model: hybrid

Use **date-derived eligibility with a canonical operational expired fact**. A job becomes non-actionable immediately when its effective expiration passes, regardless of cron. A scheduled or controlled process may then record expiry for operational history, notification deduplication, dashboards, and renewal eligibility. The normal Job Service status model need not rely on a separate `expired` status value; existing explicit expired fixture rows require normalization/migration planning later, not a new parallel state machine.

This model keeps published status as publication history while `expired_at` records the lifecycle fact. The canonical visibility predicate must treat either a passed effective expiration or an expired fact as non-public. Alerts, JobPosting JSON-LD, save/apply controls, and finder queries then agree. Source removal remains a distinct reconciliation/suppression condition rather than being mislabeled as ordinary expiry.

## 12. Lifecycle semantic matrix

| Lifecycle condition | Meaning | Initiator | Public visibility | Reversible | Renewal allowed | History retained |
|---|---|---|---|---|---|---|
| Draft | incomplete/unsubmitted record | employer, administrator, import workflow | no | yes, through edit/submit | not applicable | yes |
| Submitted | awaiting moderation/review | employer or controlled import | no | yes, approve or return to draft | not applicable | yes |
| Published | approved actionable opportunity | administrator or approved trust path | yes only when canonical predicate passes | can be closed, expire, or be held | not while active | yes |
| Returned to draft | moderator requires revision | administrator | no | yes, resubmit after edit | not applicable | yes, with moderation event |
| Closed | employer/admin says the role no longer accepts applications | employer or administrator | no; optional historical detail | not automatically; reopening needs explicit policy | yes, creates successor draft | yes |
| Expired | effective posting/application time elapsed | date-derived; operational fact materialized later | no; optional historical detail | no automatic revival; new/renewed successor | yes, creates successor draft | yes |
| Archived | retained administrative record removed from normal flows | administrator; employer only for draft currently | no | no restore path currently found | no | yes, internal |
| Source removed | authoritative source no longer supports listing after complete-run/grace evidence | reconciliation process or administrator | no | yes if source reappears or review reverses it | not automatically | yes, with source evidence |
| Suppressed | manual, policy, legal, duplicate, or ownership hold | administrator / approved rule | no | yes by authorized resolution | no until resolved | yes, with reason |
| Invalid application | no usable destination currently | validation/integrity process or administrator | no under V1 guarantee | yes after correction/reverification | no until valid | yes, with exception evidence |

“Reject” is an intake/moderation outcome that prevents public publication. “Return to draft” is a reversible moderation request. Neither should be used as a substitute for source removal, suppression, or application failure.

## 13. Source-disappearance lifecycle behavior

| Source situation | Evidence | Recommended lifecycle behavior |
|---|---|---|
| Successful complete authoritative run no longer contains a known job | complete-scope proof, stable source identity, successful run, prior observation | mark a candidate missing; grace/recheck before suppression; do not immediately archive or claim the role is filled |
| Direct source job URL returns 404 | source-specific response with known identity | exception/recheck; no automatic closure from one response |
| Source page redirects | canonical target and job-specific continuity evidence | update source/application reference only after validation; otherwise exception |
| ATS indicates filled/closed | explicit source lifecycle evidence | close/suppress application according to approved source policy; retain history |
| Job absent from partial source | partial scope/pagination/window | no missing calculation or lifecycle action |
| Source run fails/access denied/schema changes | failed run evidence | no missing calculation, suppression, expiry, or close action |
| Employer claims job before disappearance | verified claim/authority state | source changes cannot overwrite/close protected employer-managed fields automatically; create review conflict |
| Employer manually edits job / admin locks record | authority/override state | no unattended source overwrite or lifecycle action; create review conflict |
| Application URL works but source listing disappears | source-scope evidence and destination evidence conflict | review; do not infer current vacancy solely from either fact |

Source disappearance is not ordinary expiration. It is a reconciliation finding that may eventually make a job non-public through suppression or a source-removal condition, subject to JREAL002’s complete/partial/failed run rules.

## 14. Renewal, reposting, and lineage model

Current employer renewal and duplicate operations copy a source job to a new draft, reset expiry to 30 days, create a new slug, and copy terms. There is no explicit parent/child lineage field and no public preservation rule for the old record.

### Recommended V1 semantics

- **Employer renewal:** create a successor draft from a closed/expired employer-managed job; preserve the prior record and its engagement/lifecycle history; do not transfer old metrics to the successor.
- **Employer duplicate:** create a new draft for a genuinely separate/reused posting; it is not a claim that the old opportunity remains active.
- **Imported repost, same strong identity:** update the same imported record only when source evidence confirms continuity; refresh relevant dates after validation and preserve source history.
- **Imported repost, new strong external identity:** create a successor record and retain the earlier record as expired/removed/suppressed according to source evidence.
- **Changed title/location under same identity:** treat as a change candidate, not a silent new job; source authority and claim state determine whether it can update automatically.
- **Claimed/employer-managed record:** do not permit ingestion to silently create a competing renewal or overwrite the employer-managed lifecycle. Route the conflict to the claim/authority process.

Old public URLs should not be silently repointed to successors. A closed/expired historical page may link to current search or a successor only when there is verified continuity; detailed redirect/noindex policy is deferred.

## 15. Historical-retention requirements

| Condition | Internal retention | Employer/recruiter reporting | Public detail / indexing |
|---|---|---|---|
| Closed or expired | retain lifecycle, engagement, source, and notification history | retain for eligible employer reports | optional limited non-actionable detail; no JobPosting; no general results |
| Archived | retain audit history | administrator only unless a later restore path exists | unavailable from normal public flows |
| Source removed | retain source run/miss evidence and prior public facts | only for authorized owner/administrator | no normal public result; limited historical page only if policy permits |
| Duplicate-suppressed | retain identity/candidate/resolution history | administrator/authorized owner | no public detail that can mislead applicants |
| Invalid application | retain validation and correction evidence | administrator/authorized owner | no public actionable page |
| Legal/policy suppression | retain minimum permitted audit/suppression evidence | restricted administrator access | no public page; no structured data |

Retention supports administrator audit, recruiter reporting, future Alert deduplication, source provenance, and operational recovery. It must not permit historical records to reappear in results, alerts, or structured data.

## 16. Lifecycle communications matrix

| Event | Current implementation | Recommended V1 treatment |
|---|---|---|
| Submitted | recruiter email path and admin queue notification | V1 required for employer-posted records with active membership; controlled-import admin notification as appropriate |
| Approved/published | recruiter approval email path | V1 required for employer-posted/claimed records; imported unclaimed record needs operator/run evidence, not recruiter email |
| Returned to draft | recruiter return email path | V1 required for employer-posted/claimed records |
| Expiring soon | daily candidate process with event guard | V1 required for employer-posted/claimed records when expiry is known; unclaimed import goes to operator exception/report only |
| Expired | daily candidate process with event guard | V1 required for employer-posted/claimed records; no need to send a recruiter email for unclaimed imports |
| Closed | no specific close email found | Pilot-required if employer closes a listing and a relevant active member exists; otherwise event/history only |
| Renewed | renewal email path and event guard | V1 required for employer renewal flow |
| Application destination failure | not found | Pilot-required operator exception; notify employer only after claim/authority rules permit it |
| Source disappearance | not found | Pilot-required operator/run exception; employer notification only for claimed/employer-managed conflict |
| Imported claim / correction / removal request | not found | JREAL005 defines the authority path; communications must be introduced with that flow |

`TNet_Jobs_Communication_Service` directly uses `wp_mail()`; `queue_notification()` explicitly reports that queue storage is unavailable. Existing one-time expiration/renewal email guards use job events. A generalized notification center is not required for this V1 contract.

## 17. Public trust and disclosure facts

| Fact | Public / internal treatment |
|---|---|
| Posted date and materially updated date | Public trust facts when available; current detail renders both under limited conditions |
| Expiration date or application deadline | Public when accurate and meaningful; otherwise retain operationally for visibility without inventing a visible deadline |
| Closed / expired state | Public on permitted historical detail; no application action |
| Application method and destination semantics | Public CTA must accurately state external, email, or instructions behavior |
| Source last verified / recently verified | Internal until an approved source-verification policy and wording exists |
| Employer-posted versus curated/imported | Internal provenance by default; public treatment must avoid implying employer endorsement or Teachers.Net receipt of an application |
| Source/correction/removal/claim path | Internal operational evidence first; discrete public path is V1 launch polish after policy approval |
| Claim/ownership state, source runs, suppression reasons, link verification details | Internal only |

## 18. Structured-data requirements

Current `jobposting_json_ld()` emits `JobPosting` only when `is_publicly_visible_job()` returns true. It requires title, description, public slug, `published_at`, and `expires_at`; emits `datePosted`, `validThrough`, hiring organization, employment type when mapped, postal job location when available, and `directApply: false`.

| Requirement | Current state | V1 conceptual correction |
|---|---|---|
| `datePosted` / `validThrough` | emitted for helper-visible records | retain only when truthful and current |
| Hiring organization | emitted from employer record | retain when employer association is trustworthy; do not imply source ownership beyond stored employer fact |
| Employment type | emitted when mapped | retain |
| Salary | not emitted | add only when stored salary is sufficiently structured and accurate |
| Remote job representation | no remote-specific JobPosting representation found | add accurate remote/applicant-location semantics only after JREAL004 location policy |
| `directApply` | always `false` | remains appropriate while Teachers.Net does not receive applications, including direct external CTA behavior |
| Closed/expired/suppressed/invalid records | helper excludes past `expires_at`, but helper does not explicitly test `expired_at` | canonical visibility predicate must gate all structured data |
| Imported/curated provenance | no source metadata exists | do not add misleading source assertions; public disclosure policy is separate |

## 19. Existing components to preserve or reuse

| Component | Classification | Reason |
|---|---|---|
| Job Service validation | Reusable with corrected semantics | retain lifecycle/salary/location validation boundary; application and expiry rules need focused strengthening |
| Current application method storage/rendering | Reusable with corrected semantics | preserve method concepts, but add method-specific validation and remove internal-workflow implication |
| Engagement reveal tracking | Reusable with focused extraction | retain as an optional engagement signal; do not require it to gate verified external/email application without approval |
| Events table/service | Reusable with focused extraction | preserve notification/lifecycle history; pair with JREAL002 run/exception evidence |
| Public visibility helper | Reusable with corrected semantics | make it the shared conceptual predicate; current consumers are inconsistent |
| Close/archive/renew/duplicate services | Reusable with focused extraction | preserve authorization and copy behaviors; add lineage and source-authority guardrails later |
| Expiration notification scheduling | Reusable with corrected semantics | retain daily timing/event guards; do not rely on it as the sole expiry/visibility mechanism |
| Communication service | Reusable unchanged for direct email | preserve type/template boundary; no queue/notification-center expansion required |
| Moderation transitions | Reusable unchanged | preserve administrator/nounce/capability boundaries and event creation |
| Public detail status notices | Reusable with focused extraction | retain non-actionable closed/expired presentation after historical-detail policy is finalized |
| JobPosting generator | Reusable with corrected semantics | preserve output location; gate through canonical visibility and truthful fields |
| Job Alerts active filtering | Reusable with corrected semantics | align it with canonical visibility, source suppression, and effective expiry |

## 20. Current implementation gaps

| Gap | Classification | Reason |
|---|---|---|
| Method-specific URL/email/instruction validation and application integrity state | V1 blocker | Every public job must have a usable, truthful destination. |
| Canonical shared visibility predicate across finder, detail, alerts, save/apply, and JSON-LD | V1 blocker | Current rules diverge on `expired_at`, date expiry, and historical detail behavior. |
| Expiration semantic consistency and legacy explicit-expired reconciliation | V1 blocker | Current status allow-list, seed records, date-derived visibility, and cron behavior conflict. |
| Source-removal/suppression application lifecycle | V1 blocker, ingestion-dependent | JREAL002 requires reconciliation guardrails; lifecycle needs a safe public consequence. |
| Historical retention/public-detail policy | V1 pilot blocker | Closed/expired pages are presently reachable but policy/indexing behavior is not unified. |
| Renewal/repost lineage and authority protection | V1 pilot blocker | Current copy-to-draft works but has no explicit lineage/source/claim guardrail. |
| Application verification cadence and exception handling | V1 pilot blocker | Destination validity cannot remain a one-time unchecked free-form assumption. |
| Lifecycle communications for close/source/destination failures | V1 pilot blocker | Current emails cover moderation/expiry/renewal but not operational ingestion exceptions. |
| Public source/correction treatment | V1 launch polish | Internal truth/integrity is required first; final public affordances can follow policy. |
| Rich structured-data enhancements / full SEO lifecycle policy | V1.1 | Salary/remote schema and detailed noindex/redirect policy can follow reliable lifecycle semantics. |
| Internal ATS, application submissions, CRM, notification center, distributed link monitor | Deferred | Outside the minimal V1 job-board contract. |

## 21. Application/lifecycle dependency sequence

1. **Method semantics and validation:** define usable external URL, email, and written-instruction conditions, plus integrity/exception evidence.
2. **Canonical visibility rule:** apply one conceptual predicate to finder, alerts, save/apply, public detail category, and structured data.
3. **Expiration consistency:** adopt hybrid date-derived/operational-expiry semantics and reconcile legacy explicit-expired behavior.
4. **Lifecycle consequences:** distinguish close, archive, source removal, suppression, and invalid application without new accidental public states.
5. **Application integrity verification:** connect ingestion/reconciliation evidence to holds, rechecks, and publication eligibility.
6. **Renewal/reposting authority and lineage:** preserve records/metrics and protect claimed or employer-managed jobs.
7. **Public CTA/detail behavior and structured data:** expose approved semantic CTAs and non-actionable historical treatment.
8. **Communications and pilot verification:** add only the operational notices required to safely test the pilot.

## 22. Risks and failure modes

| Risk | Guardrail |
|---|---|
| Dead apply links | method-specific validation, integrity state, exception/recheck path |
| Misleading CTA | semantic CTA categories tied to actual destination method |
| Login friction reducing applications | explicit ED decision; preferred direct verified URL/email CTA |
| Published job with no usable destination | public visibility requires application integrity |
| Inconsistent expired status | hybrid expiry rule and shared predicate |
| Stale structured data | JSON-LD only for canonical actionable visibility |
| Source disappearance closes valid jobs | JREAL002 complete/partial/failed run guardrails and grace/recheck |
| Failed run leaves stale jobs public | no automatic closure; exception/freshness policy handles it |
| Renewal loses lineage | successor/parent conceptual history; metrics remain with original record |
| Imported records overwrite employer-managed lifecycle | authority/claim protections and conflict review |
| Duplicate expiration notifications | retain event-based one-time guard and canonical expiry fact |
| Historical records reappear publicly | shared visibility predicate for results, alerts, actions, and schema |
| Saved/alert links point to unavailable jobs | historical-detail/no-longer-actionable policy; alerts use canonical visibility |

## 23. Engineering Director decisions required

1. Approve or reject the recommended policy to expose verified external URL/email application destinations publicly rather than requiring a Teachers.Net login/reveal step.
2. Approve the minimal V1 method taxonomy: external destination, email application, and written instructions with a usable action path; no internal Teachers.Net application method.
3. Approve the hybrid expiration model: immediate date-derived eligibility plus a durable operational expiry fact, without relying on a separate normal `expired` status.
4. Choose public historical detail retention for closed/expired jobs: limited no-apply detail versus unavailable/noindex treatment; archived/suppressed/invalid records remain non-public.
5. Approve the pilot application-destination verification cadence and the threshold for holding a job after a known failure.
6. Approve whether imported-job source/correction disclosure is included in V1 launch polish or held until after pilot evidence.

## 24. Questions deferred to later audits

### JREAL004 - Location, geocoding, and search-origin

- Coordinate quality, automatic geocoding/retry/repair, independent typed-origin resolution, typeahead, remote location schema, and Distance Search participation rules.

### JREAL005 - Employer workflow and claim readiness

- Employer authority verification, claim/conversion workflow, unclaimed employer behavior, membership activation, field ownership, lifecycle conflicts, and operational communications to claimed owners.

### JREAL006 - Final synthesis

- Reconcile approved ingestion, application/lifecycle, location, and employer decisions into the final V1 real-job contract and bounded implementation sequence.

## Verification record

- Confirmed JREAL001 (`3549f20`) and JREAL002 (`3ed50af`) are present in root history and both audit files exist.
- Inspected root `main` at `3ed50af` and Jobs plugin `main` at `9c031ad` before report creation; preserved pre-existing root untracked files.
- Inspected current job schema, Job Service/repository, moderation, employer create/edit/close/renew/duplicate/archive paths, public finder/detail/apply renderer, engagement handling, alert matching, expiration cron, communication service/templates, and JobPosting generator.
- Ran only read-only local database status/expiry counts, cron inspection, and HTTP checks for `/jobs/` and a public job detail route. The combined command's final source-path grep used the root worktree and returned a non-mutating path error after the requested read-only results; no state changed.
- Did not mutate lifecycle state, run cron, execute imports, send email, call application URLs, alter options/schema, or modify plugin code. No Jobs plugin or Core Terms files changed.
