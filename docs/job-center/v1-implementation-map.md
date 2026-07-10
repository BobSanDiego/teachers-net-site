# Job Center V1 Implementation Map

**Project:** Teachers.Net - Job Center
**Status:** Dependency-ordered execution map
**Basis:** `canonical-v1-contract.md` and JREAL001-JREAL005

## Sequencing rule

Each ticket has one primary objective. Complete and accept each prerequisite before opening its dependent ticket. This map does not authorize implementation by itself.

| Order | Ticket | Objective | Dependency | Acceptance summary |
|---:|---|---|---|---|
| 1 | JREAL007 - Real-Job Provenance Foundation | Establish durable internal source, run, job-observation, and exception accountability for real-job ingestion. | JREAL006 | An operator can distinguish source, run, identity evidence, authority, outcome, and exception without relying on seed registry or generic events. |
| 2 | JREAL008 - Real-Job Ingestion Validation | Apply the canonical acceptance, publication-eligibility, and dry-run validation contract to real-job input. | JREAL007 | Dry run reports created, updated, unchanged, rejected, warning, and exception outcomes without mutating public jobs. |
| 3 | JREAL009 - Source Identity and Employer Association | Implement deterministic same-source identity and reviewed employer-association outcomes. | JREAL007, JREAL008 | Strong same-source identity updates safely; uncertain identity, duplicate, and employer-association cases remain non-destructive review outcomes. |
| 4 | JREAL010 - Controlled Ingestion Execution | Execute approved, validated real-job input idempotently with accountable result reporting. | JREAL008, JREAL009 | An authorized approved run creates or updates only validated records, records outcomes, and reruns without duplicate records. |
| 5 | JREAL011 - Reconciliation and Source Freshness | Apply complete-run-only reconciliation, source observations, and exception handling to imported jobs. | JREAL010 | Changed records update, successful complete absence follows the approved lifecycle policy, and failed/partial runs never remove or close jobs by absence. |
| 6 | JREAL012 - Application Integrity and Canonical Visibility | Enforce truthful application methods and one public visibility predicate across Jobs surfaces. | JREAL008, JREAL010 | Valid external URLs are public, email remains protected, invalid destinations hold publication, and inactive jobs do not surface as actionable. |
| 7 | JREAL013 - Hybrid Expiration and Historical Treatment | Apply hybrid expiration semantics and consistent public treatment for expired, closed, archived, and suppressed jobs. | JREAL011, JREAL012 | Date and operational expiry agree; history does not reappear publicly; alerts, details, saves, and application actions follow the same availability state. |
| 8 | JREAL014 - Local Location Reference Foundation | Introduce the governed local ZIP/city reference needed for inventory-independent typed origins and pilot coordinate derivation. | JREAL006 | Typed ZIP and City, State resolve independently of current inventory with documented coverage and precision. |
| 9 | JREAL015 - Job Coordinate Quality and Repair | Apply the V1 coordinate-quality, exception, retry, and repair contract to eligible real jobs. | JREAL010, JREAL014 | Eligible on-site/hybrid jobs have attributable coordinate quality or a reviewable exception; normal browse remains unaffected by missing coordinates. |
| 10 | JREAL016 - Private Browser-Origin Continuation | Complete the privacy-preserving browser-current-location continuation behavior for radius pagination and refresh. | JREAL014 | Browser origin remains request-scoped and private while its results do not silently degrade on continuation. |
| 11 | JREAL017 - Verified Existing-Employer Claim | Create the authenticated, human-reviewed claim path that attaches a verified representative to an existing imported employer. | JREAL007, JREAL009 | No recruiter account is auto-created; no duplicate employer is created; unapproved claimants have no employer access; approval uses the existing membership model. |
| 12 | JREAL018 - Employer Authority Transition | Apply the approved post-claim authority boundary and dashboard continuity to existing imported jobs. | JREAL011, JREAL013, JREAL017 | The same jobs/employer/URLs/history persist; employer members manage eligible delegated facts; importer, provenance, and moderation boundaries remain protected. |
| 13 | JREAL019 - Employer Workflow Acceptance | Verify employer posting, moderation, membership, lifecycle communications, and claimed-employer dashboard continuity as one workflow. | JREAL012, JREAL013, JREAL018 | New and claimed employers follow the approved authority and lifecycle rules without unverified publishing or cross-employer access. |
| 14 | JREAL020 - Controlled Real-Job Pilot | Run and assess the approved limited real-job pilot. | JREAL015, JREAL016, JREAL019 | Pilot evidence meets the canonical acceptance conditions or produces bounded correction findings before any bulk load. |
| 15 | JREAL021 - Pilot Correction and Bulk-Load Decision | Resolve accepted pilot blockers and present the evidence-based bulk-load decision. | JREAL020 | All pilot blockers are either resolved and verified or explicitly prevent bulk load; no bulk load occurs by default. |

## Implementation boundaries

- Basic Search/Browse polish, provider-backed autocomplete, Google services, maps, commute time, personalization, and richer employer automation are not prerequisites in this map.
- The existing Jobs plugin, Core Terms integration, one-route Job Finder, local radius engine, and current employer dashboard are preserved and extended only where the canonical contract requires.
- Each implementation ticket must include its own focused verification and must not absorb an unrelated dependency from a later row.
