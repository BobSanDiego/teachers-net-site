# Job Center Canonical V1 Contract

**Project:** Teachers.Net - Job Center
**Status:** Stable implementation contract
**Basis:** JREAL001-JREAL005 and accepted Engineering Director decisions

## Purpose

This contract defines the stable V1 rules for real Jobs data. It guides implementation without reopening the completed audits. Detailed rationale remains in `docs/job-center/audits/`.

## Preserved architecture

- Terms classify. Jobs authorizes. WordPress authenticates.
- Core Terms owns reusable classification. Jobs owns job facts, source provenance, employer authority, physical coordinates, and public lifecycle.
- `/jobs/` remains one public Job Finder. Employer-posted and imported jobs use the same public entity, route, result, detail, and lifecycle model.
- Provenance, source identity, claim state, and import metadata are internal facts. They do not create a second-class public job type.
- Public search remains local-database-only. No external provider call occurs during a public query.

## Canonical job model

Every public job is one Jobs record associated with one canonical employer record. The record may originate with an employer, administrator, curator, or authorized external source, but source origin does not alter its public identity.

The job retains accountable origin, source identity, observation, lifecycle, and authority evidence internally. A source relationship is not recruiter authority. A verified employer claim changes who may manage eligible job facts; it does not replace the job, employer, URL, classification, history, engagement, or moderation record.

## Ingestion rules

- Ingestion accepts only authorized, declared source scope and produces reviewable dry-run and approved-execution outcomes.
- Stable source-controlled identity is required for unattended update/reconciliation. External job IDs and canonical job URLs take precedence over titles, content, generated slugs, or display names.
- Exact same-source identity may update a record. Probable duplicates, cross-source matches, and employer-posted/imported similarities require review and never destructive automatic merge.
- Every import outcome is accountable: created, updated, unchanged, rejected, warning, or exception. A failed or partial run is not source-disappearance evidence.
- A job cannot publish until it has an approved employer association, intelligible content, a usable application path, valid location or explicit remote treatment, source/lifecycle evidence, and no unresolved duplicate or safety exception.

## Lifecycle rules

- Visibility follows one canonical rule across finder, detail, alerts, saved jobs, application actions, and structured data.
- Normal public eligibility is date-derived and is backed by a durable operational expiry fact. The hybrid model does not rely on a separate ordinary `expired` status.
- Close, archive, suppression, invalid application, source disappearance, and expiration have distinct meanings and must not make historical or non-actionable records reappear publicly.
- A source disappearance may affect lifecycle only after a successful, complete authoritative source run. Failed, incomplete, or partial runs never close or expire jobs by absence.
- Renewals and reposts preserve lineage and historical metrics. Claim does not erase source history or moderation history.

## Application and publication rules

- Verified external application URLs are public and route directly to the real destination.
- Email application remains protected from harvesting while still providing a truthful, usable application path.
- Teachers.Net never implies it receives or relays an application unless it actually does so.
- Application integrity is a publication requirement. Missing, malformed, contradictory, or known-invalid destinations hold a record from public publication until resolved.
- Public source/correction/removal/claim affordances are discreet and must not imply employer endorsement or internal application receipt.

## Location model

- Jobs owns city, postal, country, location mode, coordinates, coordinate quality, and distance eligibility. Core Terms continues to classify state/location concepts.
- V1 uses a licensed, versioned local US ZIP/city reference dataset for typed ZIP and City, State origins and for pilot coordinate derivation. It replaces Google Places and live provider lookup for V1.
- Browser current location is opt-in, request-scoped, rounded, and private. It is never stored in profiles, alerts, analytics, or shareable URLs.
- Radius search uses stored job coordinates locally, preserves normal visibility/filter rules, and may cross state lines only through explicit user opt-in.
- Remote, multiple-location, and confidential jobs are not distance-eligible by default. A missing or untrusted coordinate prevents radius inclusion, not normal public eligibility when the job otherwise qualifies.

## Employer authority

- An imported employer is a stable canonical employer, not a lead to recreate when a representative appears.
- Import/operator authority and employer authority are distinct. Importing or associating a job grants no recruiter identity, membership, dashboard access, or publishing trust.
- A claim begins with an authenticated existing WordPress account. Recruiter accounts are never auto-created by a claim or access request.
- Human administrator review and authority verification are mandatory before a claimant receives an active employer membership. Matching name, email domain, website, or slug is supporting evidence only.
- After approval, the claimant uses the existing employer dashboard and My Jobs surfaces for the existing employer and its eligible jobs. Employer authority supersedes importer authority for explicitly delegated facts, while source provenance, administrator moderation, and historical lifecycle facts remain protected.

## Pilot rules

- A controlled pilot precedes bulk load.
- The pilot covers approximately 3-5 employers and 50-100 jobs across multiple ZIPs/cities, on-site, hybrid, remote, varied application methods, expiration behavior, source changes/removals, a duplicate, and a simulated verified claim.
- Pilot acceptance requires idempotent reruns; accurate create/update/unchanged/reject outcomes; safe reconciliation; truthful working application paths; correct visibility/expiration; distance eligibility where supported; exception reporting; and verified employer dashboard continuity.
- Bulk loading, release-candidate declaration, and V1.1 enrichment remain outside the pilot until its acceptance evidence is reviewed.

## Accepted Engineering Director decisions

1. Employer-posted and imported jobs remain one public entity.
2. Provenance is internal metadata.
3. Verified external URLs are public; email application remains protected from harvesting.
4. The hybrid expiration model is canonical.
5. Source disappearance never results from failed or partial runs.
6. Jobs owns coordinates; Core Terms owns classification.
7. A local ZIP/city dataset replaces Google Places for V1.
8. Browser location remains request-scoped and private.
9. Imported employers become ordinary employers only after verified claim.
10. Employer authority supersedes importer authority after claim, within the protected provenance and moderation boundary.
