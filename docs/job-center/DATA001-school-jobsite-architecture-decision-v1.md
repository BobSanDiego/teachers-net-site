# DATA001 — School / Jobsite Architecture Decision v1

Status: Approved product/data architecture contract
Adopted by: DATA001-REV1
Scope: Job Center authoring and lifecycle compatibility

## Decision

Use a staged hybrid School/Jobsite model. School/Jobsite records are employer-private by default and become reusable through explicit employer relationships. A future globally canonical directory is permitted only through controlled verification; it is not created by this decision.

## Approved rules

1. Visibility is employer-private by default. Reuse is relationship-based.
2. Trusted employer members manage resources. Affiliation requests and organization recovery replace single-user ownership. Administrators intervene only for disputes or unsafe merges.
3. Address validity is contract-based: U.S. requires full name plus ZIP or city+state and country `US`; international requires full name plus locality and country. Street address is optional; asynchronous normalization is permitted.
4. Duplicate handling is Create / Reuse / Relate / Resolve with confidence scoring. No inline merges. Provisional private records are allowed.
5. Media is one primary image through a Jobs-owned media service with compression, storage limits, provenance, derivatives, and CDN-ready references. Exact dimensions remain deferred.
6. A job has one primary resource, one Work Arrangement, optional additional locations, and an optional job-specific override. Remote, Hybrid, District-wide, and Multi-site are Work Arrangements, not School/Jobsite types.
7. `full_name` is required identity. `display_name` is optional presentation metadata only.

## Target relationship

`Employer ──< EmployerSchoolJobsiteRelationship >── SchoolJobsite ──< Address/Media`

`Job ──> Employer; Job ──> exactly one primary SchoolJobsite/Resource; Job ──< JobLocation`

Employer membership/capability controls resource management. Resource identity is independent from any one job. Every job has exactly one Primary Resource as its organizational anchor; that resource does not necessarily represent the place where work is physically performed. Remote, hybrid, district-wide, and multi-site jobs retain their Primary Resource and express physical-work semantics through Work Arrangement and optional locations/overrides.

## Superseded recommendations

- The audit’s unresolved Model C recommendation is now approved and no longer unresolved.
- “Globally canonical by default” is rejected for the first implementation; global directory behavior is future governed work.
- Single-user ownership is superseded by trusted-member management plus affiliation/recovery.
- Required street address is superseded by the approved U.S./international minimums.
- Inline duplicate merge is rejected; confidence-scored resolution is required.
- School/Jobsite types must not encode Remote, Hybrid, District-wide, or Multi-site.
- `display_name` must not replace `full_name` as identity; it is presentation metadata only.

## Unresolved decisions retained

- Exact resource roles/capabilities and approval thresholds.
- Global-directory visibility and SEO/public profile admission.
- Duplicate confidence thresholds and merge/resolve administrator workflow.
- Country-code and international locality normalization library.
- Media limits, derivative sizes, retention, and CDN provider.
- Multiple-location public/search projection and geocoding policy.
- Application deadline versus listing expiration; scheduled publication; attestation storage.
