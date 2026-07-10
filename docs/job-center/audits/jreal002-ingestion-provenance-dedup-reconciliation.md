# JREAL002 - Ingestion, Provenance, Deduplication, and Reconciliation Audit

**Project:** Teachers.Net - Job Center

**Audit mode:** Inspection, analysis, and documentation only

**Audit date:** 2026-07-10

**Evidence base:** JREAL001, current local Jobs source/runtime, current Job Center execution documents, and current Jobs import specifications. This report recommends an ingestion-domain contract only; it does not authorize or implement schema, importer, lifecycle, employer-claim, UI, or pilot changes.

## 1. Scope and stop boundary

This audit determines the smallest reliable ingestion model for a controlled V1 real-job pilot: 3-5 employers, 50-100 jobs, deterministic reruns, changed records, source removal handling, exceptions, and later employer claim compatibility.

It does not select an acquisition provider, define a full public job contract, alter application/lifecycle behavior, design a claim experience, or prescribe migration syntax or field names. No import, database mutation, option update, provider request, source-site request, mail, or application-code change occurred.

## 2. JREAL001 findings carried forward

| Finding | Ingestion implication |
|---|---|
| `wp_tnet_jobs` is the one public job entity with `job_id` and unique `job_slug` | Continue using one public entity; internal ingestion provenance must not create a separate public job class. |
| Seed importer updates employer/job records by slug and writes `tnet_jobs_seed_import_registry` | Useful fixture-only idempotency reference, but slug is not a real-source identity. |
| CSV importer parses, previews, confirms, then creates draft/submitted rows | Reusable guarded operator flow; it is create-only and cannot be the reconciliation engine. |
| No persistent source identity, source URL, external identifier, run, hash, first/last seen, or reconciliation state exists | V1 ingestion requires a minimal durable provenance and run model before a real pilot. |
| CSV employer match is exact active display name | Unsafe as the sole real-job association mechanism; false association and duplicate-employer risks are material. |
| Employer/user membership is separate from employer record; current runtime has zero memberships | Imported records must not auto-create recruiters or transfer management. |
| Public listing eligibility is published + unarchived + unexpired | Import acceptance, publication eligibility, and source reconciliation must remain separate decisions. |
| Events support job-linked event type/source/message/JSON metadata | Reusable supplementary audit trail, not a source/run/reconciliation system. |

## 3. Existing ingestion architecture

### Seed flow

`TNet_Jobs_Seed_Import_Service` reads JSON through the WP-CLI command in `includes/class-tnet-jobs-seed-import-command.php`. It:

1. validates only basic dataset arrays and per-record required fixture fields;
2. finds employers by normalized slug and jobs by normalized slug;
3. creates or updates those rows directly through repositories;
4. replaces job classifications;
5. saves a current fixture mapping to the `tnet_jobs_seed_import_registry` WordPress option; and
6. returns created/updated/skipped and term warning counts.

The fixture registry records fixture IDs and runtime IDs but no durable run history. The seed path is deliberately permissive for fixture-controlled input and bypasses portions of `TNet_Jobs_Job_Service` validation. It is evidence that stable, source-controlled identifiers can enable reruns, not a production-source model.

### CSV flow

`TNet_Jobs_Import_Admin` implements an administrator-only upload -> preview -> explicit-confirm path. Parsed data is kept in a user-scoped transient for 30 minutes; the same preview token may only be confirmed once for 24 hours. `TNet_Jobs_CSV_Import_Service::import_rows()` then:

- accepts only `draft` or `submitted` creation status;
- requires title, employer name, state, application method, and application details;
- requires an exact active employer name match;
- requires a configured location term match;
- permits grade/subject mapping failures as warnings;
- creates a job using `TNet_Jobs_Job_Service::create_job()`; and
- records one `csv_import_job_created` event containing CSV line and warnings.

It does not identify existing external records, update rows, evaluate duplicate candidates, retain a durable batch record, reconcile missing jobs, or offer rollback. A fresh preview can be imported again, so the confirmation token protects duplicate form submission, not logical idempotency.

### Current validation and publication defaults

The Job Service validates application method, location mode, salary ranges, coordinate bounds, and geocode metadata where provided. Repository insert requires job slug, title, description, employment type, application instructions, creator, and expiration. CSV defaults to submitted; seed input can directly set status/timestamps. Public visibility remains a separate query predicate in `TNet_Jobs_Public::browse_jobs_query()`.

### Existing components relevant to a later ingestion path

| Component | Current use | Ingestion relevance |
|---|---|---|
| `TNet_Jobs_Job_Service` | lifecycle-adjacent validation and CRUD | Reuse only after source/provenance and authority rules are established |
| `TNet_Jobs_Job_Repository` | job persistence | Reuse for approved create/update operations, not identity decisions |
| `TNet_Jobs_Employer_Service` / repository | employer lifecycle | Reuse for verified associations; current exact name match is insufficient |
| Core Terms field/term services | configured term matching and assignment | Reuse after source values are normalized and mapping outcomes are recorded |
| `TNet_Jobs_Event_Repository` | job audit events | Supplement an ingestion run/exception model, do not substitute for it |
| CSV parser/preview/confirm | operator review boundary | Reuse as an operator surface or adapter, not as the durable ingestion core |
| Seed registry option | local fixture lookup | Keep fixture-specific; do not make WordPress option state the real-source registry |

## 4. Source taxonomy

| Source category | Typical authority | Expected input completeness | Reconciliation authority | Ingestion posture |
|---|---|---|---|---|
| Employer-posted | Employer representative controls the posting | High for owned fields; no outside provenance required | Employer-managed lifecycle | Never automatically merged or overwritten by an external source without an explicit verified conversion decision |
| Administrator manual | Trusted internal operator | Varies | One-time/manual unless explicitly marked otherwise | Accept with accountable actor and manual review boundary |
| Administrator CSV | Operator-supplied file | Varies by source and preparation | One-time unless metadata declares a complete authoritative set | Treat as an ingestion batch, not an authoritative feed by default |
| Teachers.Net-curated | Internal editorial process | Controlled but possibly source-derived | Only where curator declares scope and refresh policy | Store source and curator accountability; may need review before publication |
| Structured feed/API | Source system or authorized partner | Usually stable IDs, timestamps, and structured fields | Potentially authoritative only after source-scope completeness is established | Preferred pilot source form when authorization and stable identity are available |
| Career-page extraction | Public career page, often incomplete/fragile | May lack stable IDs, dates, or structured location | Never assume complete without explicit scope evidence | Conservative ingestion; require review and no automatic mass closure from absence |
| Local seed/test fixture | Project-owned fixture file | Fully controlled | Fixture import controls current set | Preserve as isolated development data only |

“Authoritative” refers to whether a successful, complete run may later be used as evidence that missing records have disappeared. It does not imply legal authorization, employer endorsement, or automatic publication.

## 5. Recommended source-identity hierarchy

### Principle

Use stable source-controlled identity before any display label. Teachers.Net internal job ID and importer-generated slug are local identifiers, not evidence that two source records are the same. Employer-posted jobs remain a distinct authority boundary and must not be automatically merged with imported jobs.

### Hierarchy

| Rank | Candidate evidence | Reliability and action | Examples / failure cases |
|---|---|---|---|
| 1 | Source system + source-employer identity + stable external job/requisition ID | Strong deterministic identity. Re-run updates the same imported record when all scope components agree. | An ATS requisition ID may repeat across separate employers; source system and employer scope prevent false merge. |
| 2 | Source system + canonical source job URL, after canonicalization | Strong only when URL is stable and source-scoped. Re-run may update the same imported record. | Tracking parameters, locale variants, and redesigned career URLs must normalize first; a generic careers landing URL is not a job identity. |
| 3 | Source system + source-employer identity + stable source-issued requisition number | Strong when the number is documented as employer-scoped. | Number alone may collide across districts, ATS tenants, or historical renewals. |
| 4 | Source system + normalized employer + normalized title + normalized location + bounded posting/closing-date context | Probable candidate only. Never silently merge; create review/exception evidence. | Same district can have two identical “Substitute Teacher” openings, multi-campus roles, or a reposted vacancy. |
| 5 | Content similarity or content hash | Change/no-change evidence after a known identity exists. Never primary identity. | Title, description, location, salary, or application text may change legitimately; unrelated jobs can share boilerplate. |
| 6 | Teachers.Net internal job ID or generated slug | Local persistence identity only. Never cross-source identity. | Seed importer slug update works only because fixtures control slugs; real source titles are mutable and collision-prone. |

### Required fallback behavior

- **Stable external ID present:** deterministic update only inside the same declared source/employer scope.
- **Stable canonical job URL but no ID:** deterministic update only after canonical URL normalization and scope match.
- **No reliable ID or URL:** accept only as a reviewable manual/curated record; do not promise unattended rerun reconciliation.
- **Potential match to an employer-posted record:** preserve both records and create a duplicate/ownership-review case. Do not automatically merge or overwrite.
- **Repost or renewal:** retain source identity history and treat a new source-issued ID as a new record unless the source explicitly indicates continuity. A changed title/location under the same strong identity is an update candidate, not a new identity.

## 6. Provenance contract recommendation

The smallest pilot-safe contract is layered. It records who/what supplied a record and how the current row was reached without turning every source datum into public copy.

| Layer | Required facts | Optional/supporting facts | Visibility |
|---|---|---|---|
| Source-level configuration | source category, owner/operator, acquisition authorization state, declared scope, refresh/reconciliation authority, source access endpoint or reference | contact, source documentation, expected cadence, source policy notes | Internal only |
| Import-run metadata | source reference, actor/process, start/end, mode (dry run or approved execution), scope statement, input and outcome counts, run result, diagnostic summary | version/config reference, retry linkage, source response metadata | Internal only |
| Job-level provenance | source category, source reference, strong identity evidence used, original/canonical source URL where available, first observed, last observed, last verified, latest successful source run, current reconciliation state, source-controlled/claimed/manual authority state | normalized content fingerprint, external posting date, source timestamps, preservation of raw source payload reference | Internal by default; any public disclosure must be separately designed |
| Row/exception-level evidence | input locator, validation outcome, reason code, candidate-record links, reviewer/decision, resolution, retry eligibility | redacted raw values and diagnostics sufficient to reproduce the decision | Internal only |
| Public facts | truthful employer/application relationship and only approved source attribution/correction affordances | source attribution policy, correction/removal link | Public treatment belongs to later public/lifecycle decision work |

Required provenance distinguishes **origin** (how the data entered), **authority** (who may later control values), **observation** (when source evidence was seen), and **ownership** (whether a verified employer later manages it). A generic event `source` string or seed fixture metadata cannot replace these facts.

## 7. Employer-association strategy

### Association hierarchy

| Outcome | Evidence | Ingestion action | Access consequence |
|---|---|---|---|
| Exact | Existing employer already maps to the same source-employer identity, or a reviewed canonical employer/source mapping exists | Associate with that employer | No employer-user account or management access is created |
| Strong but unconfirmed | Exact normalized website domain and corroborating name/location evidence, with no conflict | Hold for administrator confirmation or preapproved mapping; do not auto-associate solely from domain | No access change |
| Probable | Similar names, school/district relationship, city similarity, or display-name match after normalization | Exception/review queue | No access change |
| Ambiguous | Multiple possible employers, district-versus-school collision, shared domain, franchise/system ambiguity | Reject automatic association; require mapping decision | No access change |
| Unmatched | No verified existing employer mapping | Create or stage an explicitly unclaimed employer record only after operator approval | No recruiter identity, membership, or direct-publish trust assignment |

### Guardrails

- Display names are evidence for discovery only; they are mutable and cannot be the sole stable identity.
- Employer slug is useful after an internal canonical employer has been selected, but it is not source-employer proof.
- Website/domain matching can corroborate an association but cannot automatically establish authority where districts, campuses, or hosted ATS domains are shared.
- An imported association must not activate `wp_tnet_jobs_employer_users`, grant portal access, or make a source job employer-managed.
- The source association must retain the ability to describe a job as unclaimed or curator-managed until JREAL005 establishes verified claim/conversion behavior.

## 8. Normalization boundaries

Normalize for matching and validation while retaining the original incoming values for audit evidence where practical. Do not use display transformation as source identity.

| Input | Minimum normalization | Use for identity/matching | Preserve raw value? |
|---|---|---|---|
| Whitespace / Unicode / punctuation | trim, collapse internal whitespace, normalize Unicode representation, normalize quotes/dashes for matching | yes, only as a component of controlled matching | yes |
| Title | whitespace/punctuation normalization and case-folded comparison form | only probable-duplicate evidence | yes |
| Employer name | normalization plus known approved alias/canonical mapping where available | discovery and review; never sole identity | yes |
| City / state / postal / country | standardized country/state representation, postal syntax validation, normalized city comparison form | location matching and validation; not standalone identity | yes |
| Application / source URL | trim, parse, remove known tracking parameters only when safe, use canonical URL if source provides one | source URL can be strong identity only when job-specific and source-scoped; application URL is not source identity | yes |
| Employment type / work arrangement | map documented source values to existing Jobs vocabulary; retain unmapped input as exception evidence | validation and display mapping, not identity | yes |
| Salary | parse currency, interval, period, and disclosure state; preserve original text if source form is richer | validation/display, not identity | yes |
| Grade / subject | map through configured Core Terms, preserving unmatched source labels as evidence | classification validation, not identity | yes |
| Dates / time zones | parse source timezone when supplied; store normalized comparison value plus source representation | source freshness, expiry, repost detection; not sole identity | yes |
| HTML / rich text | sanitize for public display; keep source reference or safely retained raw capture under source policy | content change detection only after strong identity | yes, subject to policy |
| Remote / multiple location | normalize to current Jobs location modes; retain source scope/location text | eligibility and display; multi-location variants should not collapse automatically | yes |

## 9. Validation and publication matrix

| Condition | Import acceptance | Publication eligibility | Action | Evidence retained |
|---|---|---|---|---|
| Strong source identity resolves to current source record | accept | depends on all publication checks | update candidate | identity evidence and run reference |
| No stable ID/URL but curated/manual one-time record | accept only with accountable operator | draft/submitted until review | create manual/curated record | actor, source category, raw input, reason |
| Employer association exact/approved | accept | eligible to continue checks | continue | association evidence |
| Employer association probable/ambiguous/unmatched | accept into exception path only where operator can resolve it | not publishable | hold/reject according to policy | candidates, decision, reason |
| Missing intelligible title or usable description | reject or exception | not publishable | hard reject for absent required content; exception for recoverable source issue | raw row and error |
| On-site/hybrid/multiple job lacks usable location; remote lacks explicit remote indication | exception | not publishable | hold for correction | normalized and raw location evidence |
| Application destination malformed, absent, or contradictory to method | exception or hard reject | not publishable | hold/reject; never silently substitute a destination | source value and validation result |
| Source posting/expiration date malformed or already stale | exception | not publishable | hold, reject, or expire under later lifecycle policy | source date values and run time |
| Duplicate ambiguity | accept into exception path | not publishable until resolved | review; do not auto-merge cross-source records | candidate links and comparison evidence |
| Classification mapping incomplete | accept as draft/exception only when core public facts are valid | publishable only under an approved classification policy | hold or warning based on required axis policy | unmatched labels and mapping outcome |
| Coordinates absent for a non-remote job | accept if location is otherwise valid | publication policy belongs to JREAL004; Distance Search participation is unavailable | exception/retry record; do not invent coordinates | geocode status and location evidence |
| Prohibited, malformed, or unsafe content | reject or exception | not publishable | reject or moderator review | validation reason and source reference |
| Source freshness cannot be established | accept only as draft/curated exception | not publishable without approved fallback policy | hold | source/run timing evidence |
| All required acceptance and publication checks pass | accept | publishable only through existing moderation/trust boundary | submitted or approved publication action according to policy | run, validation, authority, and lifecycle evidence |

## 10. Deduplication decision matrix

| Evidence | Confidence | Same-source action | Cross-source action | Manual review required |
|---|---|---|---|---|
| Same source scope and same external ID | Exact | update same record | do not merge automatically | no, unless authority/ownership conflict |
| Same source scope and same canonical job URL | Exact/strong | update same record | do not merge automatically | only if URL is not job-specific or identity changed |
| Same employer/source scope and same requisition number | Strong | update same record | do not merge automatically | yes if requisition reuse/history is unclear |
| Same source/employer/title/location/date fingerprint | Probable | exception candidate, not automatic update | link candidate only | yes |
| High content similarity but changed title/location | Weak to probable | preserve as changed/new candidate until strong identity resolves it | do not merge | yes |
| Same job appears in two authorized sources | Cross-source probable | preserve source-specific records or link evidence | do not merge by default | yes; one source may be derivative or stale |
| Employer-posted and imported record appear similar | Ownership-sensitive | never overwrite employer-managed record | do not merge automatically | yes, with future claim/conversion handling |
| Same position, different campus/location or multi-location variant | Legitimately separate possible records | create distinct records unless strong source identity says otherwise | do not merge | only where source scope is ambiguous |
| Repost/renewal with new external ID | Generally new record | create new record, retaining source relationship history where available | no automatic merge | no unless policy says otherwise |

The required default is conservative: exact same-source identity can update; probable matching creates evidence; cross-source and employer-managed similarities do not destructively merge.

## 11. Import-run model

### Conceptual run lifecycle

1. **Prepared:** a source definition and declared scope are selected; no row changes yet.
2. **Dry run:** parse, normalize, validate, associate, classify, and calculate proposed outcomes without writing jobs or lifecycle changes.
3. **Reviewable:** results show created, updated, unchanged, rejected, warning, duplicate-candidate, and missing-from-source counts with row evidence.
4. **Approved execution:** an authorized operator explicitly applies the reviewed set.
5. **Completed / completed with exceptions / failed:** immutable result with source scope, counts, and diagnostics.
6. **Retry:** a new run references the prior failed/retryable run; it does not reuse an ambiguous partial result as success.

### Minimum reporting

Every run needs source/process identity, start/end, declared scope completeness, dry-run/approved mode, input count, created/updated/unchanged/rejected/warning counts, duplicate/association exceptions, missing-from-source count, failure reason, and links to row-level evidence. A source run is successful only when its declared scope was completely obtained and processed.

The current `wp_tnet_jobs_events` table can record job-level “ingested/updated/reconciled” events after a successful operation. It cannot replace a dedicated conceptual run record: it has no source scope, run outcome, aggregate counts, no-job exceptions, or failure/retry state.

## 12. Update and field-authority model

| Conceptual authority | Fields / behavior | Rule |
|---|---|---|
| Source-controlled while imported and unclaimed | source title, description, requirements, source location, application destination, salary, employment type, classifications, source dates, source URL | A later successful source run may propose updates after validation and identity match. |
| Jobs-computed | normalized comparison forms, current reconciliation state, visibility eligibility derived from lifecycle, source change indication | Recompute from trusted source/run data; retain evidence of the preceding value where decision auditing needs it. |
| Administrator-controlled | publication/moderation decision, manual mapping, exception resolution, suppression, source trust/scope configuration | Never silently overwrite an operator decision; source rerun may create a review condition. |
| Employer-controlled after verified claim | fields explicitly delegated by later claim/conversion policy | Stop automatic overwrite for protected fields; source observations can continue as comparison evidence. |
| Protected / historically tracked | first observation, original identity evidence, prior source references, claim/authority transition, suppression decision | Append/history model; do not rewrite the audit basis. |

At pilot scale, an imported row should not become employer-controlled merely because names match. The authority boundary changes only after a verified claim/conversion process, which is intentionally deferred to JREAL005.

## 13. Reconciliation model

| Situation | Evidence required | Safe behavior |
|---|---|---|
| Successful complete authoritative source run | explicit source scope, successful completion, expected coverage, identities observed | Update/create records, mark observed records current, calculate missing records; do not immediately mass-close without configured protection |
| Successful partial source run | source indicates page/window/segment is partial or scope cannot be proven complete | update/create observed records only; do not mark unseen records missing |
| Failed source run | fetch/parse/access failure, incomplete pagination, provider/access denial, unexpected response | no missing calculation or closure; record run failure and retain prior source state |
| One job direct URL returns 404 | strong prior identity plus source-specific response evidence | warning/recheck or review first; absence from a single request is not enough to infer permanent removal |
| Job absent from multiple successful complete runs | complete-scope history, consecutive-miss count, no manual/employer override | eligible for grace-period action under later lifecycle policy; record every observation/miss |
| Source redesign or extraction break | unexpected source structure / broad parse anomaly | fail run; no reconciliation consequences; require source repair/review |
| Imported CSV / one-time manual file | no declared recurring authoritative scope | no automatic missing handling; it is a point-in-time create/update operation |
| Claimed/employer-managed job | verified authority/override state | source absence or change cannot automatically close/overwrite protected fields; surface a conflict/review signal |
| Application-link failure | source/application check evidence, not merely user assumption | create exception/review evidence; lifecycle consequence is deferred to JREAL003 |

### Guardrails against accidental mass closure

- Never treat a failed, partial, empty, or scope-unknown run as evidence that jobs disappeared.
- Require explicit source completeness before producing missing-from-source candidates.
- Use a grace/recheck threshold rather than a single miss for any automated lifecycle consequence.
- Protect manually suppressed, manually corrected, and claimed/employer-managed records from unattended source overwrite/closure.
- Require a run-level anomaly threshold/review boundary when missing counts are unexpectedly high.
- Keep dry-run results separate from approved execution.

## 14. Exception taxonomy and recovery model

| Category | Examples | Recovery disposition |
|---|---|---|
| Identity | missing/unstable external ID, noncanonical URL, reused requisition number | review, establish/confirm source identity, retry |
| Employer association | no employer, multiple matches, school/district ambiguity, authority conflict | map, stage unclaimed, or reject; never auto-grant access |
| Duplicate | probable same-source record, cross-source similarity, employer-posted conflict | link candidates for decision; retry after resolution |
| Application | invalid URL/email/instructions, redirect mismatch, missing destination | correct source/manual input, suppress, or reject |
| Location / coordinate | missing location, unsupported mode, malformed postal/date, coordinate unavailable | correction/retry/manual review; JREAL004 governs geocoding policy |
| Classification | unmapped grade/subject/location label, stale configured term | mapping correction or policy-based unpublished exception |
| Content | malformed HTML, prohibited text, empty meaningful description | sanitize/reject/moderate; preserve source evidence subject to policy |
| Freshness / lifecycle | stale posting, invalid expiration, repeated source miss | hold, recheck, close/expire only under approved lifecycle policy |
| Source run | fetch failure, access denial, partial pagination, schema change | mark run failed/partial; no reconciliation side effect |
| Ownership | claim/authority disagreement, manual lock, employer-managed conflict | protect row, require later claim/administrator resolution |

An exception record/report should preserve source/run reference, row locator, normalized and safe raw evidence, category/reason, severity, candidate linkage, current disposition, responsible actor, retry/ignore decision, resolution timestamp, and recurrence trend. The audit does not prescribe an admin UI.

## 15. Correction, removal, and reimport-suppression requirements

The ingestion domain needs internal handling for the following facts before a real pilot can operate safely:

| Need | Minimum internal requirement |
|---|---|
| Employer correction request | associate request with the source/job identity, retain requested correction and decision, and prevent silent source overwrite of resolved/manual fields |
| Employer removal request | retain request/authority evidence, administratively suppress or remove public eligibility through existing lifecycle policy, and record the outcome |
| User-reported dead application link | create an auditable exception against job/source/application evidence; do not assume a source disappearance |
| Source correction | distinguish a source-controlled update from a manual override and preserve prior evidence |
| Duplicate resolution | record candidate links and resolution; suppression must not erase the ability to prevent re-import |
| Legal/policy removal | retain an internal suppression reason and authority; prohibit unattended re-import until reviewed |
| Do-not-reimport | durable suppression keyed to the relevant source identity/authority boundary, not only the current title or slug |

Public wording, employer claim interaction, and exact closure/archive mechanics remain outside this audit's scope.

## 16. Existing components to preserve or reuse

| Component | Classification | Reason |
|---|---|---|
| `TNet_Jobs_Seed_Import_Service` | Reusable only for fixtures | Its slug-based behavior and option registry are valid for project-owned fixtures but unsafe as production provenance. |
| Seed registry option | Reusable only for fixtures | Keeps test records removable/traceable; no run history or real-source isolation. |
| CSV parser / header validation / preview / explicit confirmation | Reusable with extraction | A sound operator review boundary; separate parsing/preview from production upsert/reconciliation logic. |
| `TNet_Jobs_CSV_Import_Service` | Reusable with extraction | Column parsing, date/salary/location normalization, and Core Terms matching help; create-only exact-name association and lack of run model must not become real ingestion semantics. |
| `TNet_Jobs_Job_Service` | Reusable with extraction | Existing field validation and lifecycle side effects should remain centralized after a validated ingestion decision. |
| Job repository | Reusable unchanged for persistence | Appropriate persistence boundary once a service has selected create/update/unchanged action. |
| Employer service/repository | Reusable with extraction | Keep employer and membership boundaries; add no automatic recruiter/membership behavior. |
| Core Terms mapping services | Reusable with extraction | Existing configured-term matching should record unmapped source values as exceptions rather than silently discard the evidence. |
| Events table/service | Reusable with extraction | Useful job-level history; cannot replace run, source, or exception records. |
| Job slug generation | Should remain separate from source identity | Continue for public routing; do not make a generated/mutable slug the external identity. |
| Admin import permissions | Reusable unchanged | Preserve `manage_options` and nonce-protected operator boundary for V1 pilot execution. |
| Existing warnings/errors | Reusable with extraction | Maintain concise operator summaries, but connect them to durable run/row evidence. |

## 17. Current implementation gaps

| Gap | Classification | Reason |
|---|---|---|
| Durable source identity, provenance, and source/run traceability | V1 blocker | Required to know what a real record is, where it came from, and whether a rerun is safe. |
| Deterministic real-source upsert and duplicate decision boundary | V1 blocker | Current seed slug update and CSV creation cannot safely govern a real pilot. |
| Reconciliation state, complete-scope proof, and missing-source guardrails | V1 blocker | Real jobs cannot be refreshed or removed safely without distinguishing failure/partial run from disappearance. |
| Durable exception/recovery evidence | V1 pilot blocker | A small pilot needs operator-visible, retryable failures rather than only transient/error-page feedback. |
| Safe imported employer association / unclaimed state | V1 pilot blocker | Exact display name matching can attach a job to the wrong employer; no claim-safe model exists. |
| Application-destination integrity policy | V1 blocker, cross-domain | Ingestion must prevent malformed destinations, but exact application/lifecycle rules belong to JREAL003. |
| Coordinate readiness / independent origin resolution | V1 blocker, cross-domain | Job coordinate intake and search-origin independence belong to JREAL004; provider automation itself is not automatically required for the first implementation step. |
| Verified claim/conversion and field authority transition | V1 blocker, cross-domain | Required before employer management of imported records; JREAL005 owns detailed analysis. |
| Public provenance/correction presentation | V1 launch polish | Internal truth/protection is required first; final public treatment can follow the domain contract. |
| Automated source adapters, provider automation, broad scraping | V1.1 or deferred | Not needed to validate the smallest controlled pilot path; authorization and source-specific feasibility remain unresolved. |

## 18. Ingestion-domain dependency sequence

1. **Identity/provenance foundation:** establish source categories, strong identity evidence, authority state, and raw/normalized evidence boundary.
2. **Run tracking:** add dry-run/approved-run concepts with declared source scope, results, and durable diagnostics.
3. **Validation and association:** normalize source input, resolve employer by safe hierarchy, map classifications, and assign acceptance/publication outcomes.
4. **Deduplication/upsert behavior:** allow exact same-source create/update/unchanged outcomes; route probable and cross-source matches to exceptions.
5. **Exception/recovery handling:** retain row-level failures, retries, ignores, mappings, and suppressions.
6. **Reconciliation:** apply only after a successful complete source run, with miss thresholds and manual/claimed protections.
7. **Pilot adapter:** connect one approved, authorized source form to the above model under administrator approval, without generalizing to a scraper platform.

This sequence is conceptual. JREAL006 should convert approved cross-domain findings into bounded implementation tickets.

## 19. Risks and failure modes

| Risk | Required guardrail |
|---|---|
| Duplicate public listings | Exact source identity for updates; probable duplicates held for review; no cross-source merge by similarity alone |
| False merges | Scope every external identity by source and employer; do not use display name/title as primary identity |
| Wrong employer association | verified mapping hierarchy; no automatic access grant; ambiguous cases are exceptions |
| Stale jobs | source freshness/run state and reconciliation only after successful complete runs |
| Mass accidental closure | no closure from failed/partial/empty run; grace/recheck/anomaly review thresholds |
| Dead application links | application validation and exception evidence, not silent substitution |
| Overwriting employer edits | source/employer/admin authority categories and manual/claim protections |
| Source terms/licensing concerns | establish authorization, attribution, refresh, and content-use policy before acquisition; no legal conclusion is made here |
| Scraper fragility | treat career-page extraction as partial/fragile and prevent it from driving unattended reconciliation |
| Audit-trail loss | preserve source/run/row evidence and immutable first-observed/identity history |
| Partial-run misclassification | explicit scope completeness and run outcome before missing calculations |
| Non-idempotent retries | stable same-source identity and new retry run records; do not use transient confirmation tokens as logical idempotency |

## 20. Decisions required from Engineering Director

1. Which authorized source category will supply the controlled pilot: a structured employer/partner feed, administrator-prepared CSV, a curated source, or another explicitly approved method?
2. What source authorization and acceptable acquisition policy must be satisfied before real content is retained, refreshed, or publicly displayed? This includes terms of use, technical access, copyrighted description reuse, attribution, correction/removal requests, and employer objections. No legal conclusion is made in this audit.
3. Is a manual/curated record without a stable external ID or job-specific canonical URL eligible for the pilot only with explicit operator review, or should it be excluded from automated reruns?
4. What publication posture applies to pilot imports after validation: all submitted for moderation, or a limited approved-source exception under existing trust controls?
5. What source completeness evidence is sufficient to allow missing-from-source candidates to enter the later grace/recheck lifecycle?

## 21. Questions deferred to later audits

### JREAL003 - Application, Expiration, and Public Lifecycle Audit

- Final application-method validation rules, expired/closed/archived semantics, listing-age fallback, link-failure consequences, notification/reversal policy, and public lifecycle treatment.

### JREAL004 - Location, Geocoding, and Origin Resolution Audit

- Coordinate quality requirements, geocoding/retry/repair path, independent ZIP/city origin dataset or provider behavior, typeahead, and privacy/operational controls.

### JREAL005 - Employer Workflow and Claim Readiness Audit

- Authority verification, claim initiation/review, conversion of an unclaimed imported association, membership activation, field-control transition, and conflict resolution.

### JREAL006 - Final synthesis

- Integrate the approved ingestion, lifecycle, location, and employer findings into the final V1 real-job contract and bounded implementation sequence.

## Verification record

- Confirmed the JREAL001 audit exists and its root commit is `3549f20`.
- Inspected root (`main` at `3549f20`) and Jobs plugin (`main` at `9c031ad`) repositories before creating this report; preserved pre-existing root untracked files.
- Read current project governance, Cursor, Handoff, V1 Execution Plan, roadmap/product documents, JREAL001, Jobs constitution/roadmap/import/employer documents, schema, importer, repository/service, and admin-handler code.
- Used only source inspection, static analysis, and read-only runtime baselines carried forward from JREAL001. Two optional local JSON-option formatting attempts were blocked by unavailable `jq`/`node`; no import or option update was run.
- Did not execute seed/CSV imports, create/update jobs or employers, alter options/schema, send email, call providers, or contact external sources.
- No Jobs plugin or Core Terms files changed. `git diff --check` is required before commit.
