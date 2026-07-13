# JC-030 Content and Pipeline Reconciliation

**Ticket:** AUDIT007

**Date:** 2026-07-12
**Scope:** Audit and documentation only

## 1. Executive Finding

JC-030 can be refined from the current canonical Jobs pipeline without adding a
summary field or inventing content. The record already supports title, employer,
optional `summary`, full `description`, optional
`requirements_qualifications`, structured employment, salary, location,
lifecycle, application, Core Terms, and engagement facts. The reconciliation
problem is primarily presentation: the current page duplicates facts/actions,
ignores available employer fields, exposes internal `published` status
prominently, omits governed correction/removal/claim context, and lacks the
required leaderboard.

The requested URL suffix is seed fixture identifier `168`, not the current
database primary key. The inspected canonical record is `job_id=755`, with the
requested slug and `job-168` seed lineage.

## 2. Current Data Flow

```text
Employer create/edit | CSV import | controlled ingestion | seed fixture
                              |
                              v
                   Job Service validation
                              |
                              v
            wp_tnet_jobs + employer + Core Terms
                              |
                              v
          Repository complete row -> public route eligibility
                              |
                              v
             Detail renderer + integrity/engagement helpers
```

The canonical table defines the narrative, structured, lifecycle, application,
and employer-link fields (`includes/class-tnet-jobs-schema.php`). Repository
reads return complete rows; writes sanitize summary as plain textarea text and
the long narrative fields as allowed HTML. The service does not create a
separate public DTO (`includes/services/class-tnet-jobs-job-service.php:793-845`;
`public/class-tnet-jobs-public.php:7171-7201`). Employer Create/Edit expose
Short Summary as optional, Description as required, and Requirements as
optional (`public/class-tnet-jobs-public.php:2428-2435,2646-2660`). CSV,
controlled-ingestion, seed, copy, and renew paths map/preserve summary.

Record 755 contains a summary, two description paragraphs, six qualification
lines, Part Time, Hybrid, USD 70,000-87,000 annually, Garden Grove/California/
US, Grade 5, Mathematics and Science, and an accepted external URL application.
Employer 212 has a website and only fixture-quality description. Its operational
`trusted` value is not a public verified-employer designation. No provenance
association was found for this seed record.

## 3. Content Inventory Matrix

| Content element | Current field/source | Population path | Current public use | Required by Product Definition | Required by UX Specification | Candidate usage | Support status | Gap/Risk | Recommended visual treatment |
|---|---|---|---|---|---|---|---|---|---|
| Title | `jobs.title` | All paths | Detail/listing/metadata | Yes | Primary | Both | Direct | None | Canonical value |
| Employer | `employer_id` -> name | All paths | Detail/listing | Yes | Primary | Both | Direct | Public destination not surfaced | Canonical name; governed link only |
| Summary | `jobs.summary` | Optional employer; CSV/import/seed | Search, listing, detail, metadata fallback | Yes | Core | Both | Optional | Source-dependent population | Show when present; omit when absent |
| Description | `jobs.description` | Required employer; import/seed | Detail/JSON-LD | Yes | Core | Both | Direct | Must not be split into invented facts | Render supplied narrative |
| Responsibilities | No dedicated field | May occur in description | Not separated | When supplied | When supplied | Concept separates | Inconsistent | Unsupported section assumption | Separate only from explicit content |
| Qualifications | `requirements_qualifications` | Optional employer/import/seed | Detail/JSON-LD | When supplied | When supplied | Both | Optional | Variable formatting | Show when populated |
| About employer | Employer name/description/site | Employer/import/seed | Generic copy; description/site ignored | Available public data | Trust | Concept narrative | Inconsistent | Fixture description is not publishable biography | Never invent; use fit public data only |
| Location | Job fields + Core Terms state | All paths | Detail/listing | Yes as available | Primary | Both | Direct | Confidential/missing variants | Show canonical available facts |
| Work/employment | `location_mode`, `employment_type` | All paths | Detail/listing | When supplied | Core | Both | Direct | None | Canonical labels |
| Salary | type/min/max/currency | All paths | Detail/listing | When supplied | Core | Both | Optional | Partial/undisclosed values | Show formatted supported value only |
| Grade/subject | Core Terms assignments | Governed assignment paths | Detail/listing | Yes | Core | Both | Direct | Do not infer from title | Resolved assigned labels |
| Other terms | Core Terms assignments | Governed paths | Detail limits axes | Supported terms | Core | Limited | Inconsistent | Existing data may be suppressed | Public relevant assignments only |
| Posted/updated | `published_at`, `updated_at` | Lifecycle/repository | Detail | Freshness | Trust | Both | Derived | Updated means any later mutation | Low prominence; qualify semantics |
| Expiration/availability | dates + status + integrity | Lifecycle/integrity | Eligibility/state/JSON-LD | Yes | Primary/state | Published chip | Derived | Raw status is internal vocabulary | User-facing actionable/closed/expired truth |
| Application | `apply_method`, `apply_url` | Employer/import/seed | External link or protected reveal | Yes | Primary | Both | Direct | Current CTA duplicated | One truthful primary path |
| Save | Engagement `saved_at` | Authenticated action | Detail/listing | Secondary | Secondary | Both | Direct | Cross-surface state must agree | One secondary action |
| Employer site | `website_url` | Employer paths | Not used on detail | When available | Navigation | Implied | Missing | Stored but not surfaced | Governed public destination only |
| Source | Provenance tables | Controlled ingestion | Not rendered | Where required | Discreet | Neither | Inconsistent | Seed has no association | Omit absent governed public context |
| Correction/removal | No detail affordance found | N/A | Not rendered | Where required | Discreet | Concept | Missing | No current public path | Treat as unresolved, not functional |
| Employer claim | Existing claim route | Authenticated claim workflow | Not linked from detail | Where applicable | Discreet | Concept | Derived | Applicability not resolved on detail | Existing route only when applicable |

## 4. Interaction and State Matrix

| Condition/action | Current behavior | Reconciled finding |
|---|---|---|
| Direct entry | Unconditional Back to Search Results | Do not fabricate prior-results context |
| External application | Public outbound link after integrity evaluation | Supported; explain external destination and Teachers.Net role |
| Email/written method | Login plus explicit reveal | Supported; reveal is engagement, not application |
| Closed/expired | Explanation and no live apply action | Supported; all surfaces and JSON-LD must agree |
| Save logged out | WordPress login with detail return | Supported; no false saved state |
| Save logged in | Save/unsave engagement record | Supported; one control is sufficient |
| Optional narrative absent | Section omitted | Correct; never fabricate fallback facts |
| Employer details absent | Generic employer sentence | Generic copy is not employer information; omit absent facts |
| Internal lifecycle | Raw status badge | Use seeker-facing availability only where useful |

## 5. Creative Reconciliation

The current implementation evidence accurately shows record content but is not
design authority. It demonstrates duplicated hero/sidebar facts, duplicated
Save/Application actions, generic employer copy, prominent internal status, and
no visible 728 x 90 placement.

The single-screen concept is the stronger structural input: constrained shell,
continuous flow, no tabs, and a conversion-led right rail. It is not product
truth. Its separate responsibilities and polished employer narrative are not
supported discrete publication-quality fields for this record; it duplicates
Apply, prominently shows Published, and omits the leaderboard.

The earlier unapproved `job-detail-01a-design-target` was inspected but not
modified or promoted. Its fictional La Jolla content cannot map job 168/755.

## 6. Shell Compliance Findings

- Preserve approved logged-out and logged-in navbar states and standard footer.
- Constrain navbar/footer to the centered 1200px maximum canvas.
- Preserve primary content plus the governed 300px right rail.
- Reserve a true 300 x 250 right-rail advertisement.
- Reserve a centered 728 x 90 leaderboard after primary content and before the
  footer; neither staged image visibly satisfies this.
- Maintain continuous single-page flow with no internal tabs.
- The right rail must support conversion without duplicate primary actions.

## 7. Summary Field Finding

The adequate field already exists: `wp_tnet_jobs.summary` (`TEXT NULL`). It is
exposed directly by repository/service reads. Employer Create/Edit call it
**Short Summary**, recommend 100-180 characters, and leave it optional. CSV maps
`short_summary`; controlled ingestion and seed import map `summary`; copy/renew
preserve it.

Search matches title, summary, and description. Listings display summary when
present. Detail renders Job Summary only when nonempty. There is no detail-body
fallback; metadata alone falls back summary -> description -> title
(`public/class-tnet-jobs-public.php:6902-6911`). No new field is justified. The
risk is optional, inconsistent population.

## 8. Published Status Finding

A prominent **Published/Live** chip adds no unique seeker value for an ordinary
actionable job. Route eligibility, application availability, dates, and
integrity already establish availability. “Published” is internal lifecycle
vocabulary occupying premium identity/conversion space.

Omit the prominent actionable-state badge in the next visual pass. If explicit
availability is needed, use the lowest appropriate prominence and user-facing
language. Closed, expired, or otherwise non-actionable retained details do need
a clear condition and no live application action.

## 9. Safe Inputs for the Next Visual Pass

Safe inputs are the canonical title/employer; available location, work,
employment, salary and assigned Core Terms; supplied summary, description and
qualifications without rewriting; subordinated freshness truth; one primary
application action with destination expectation; one secondary Save action;
fit-for-public employer data only; applicable existing claim context; and the
approved shell with both ad reservations.

Unsafe inputs are invented responsibilities or employer biography, a verified
employer designation, public provenance for this seed job, a functional
correction/removal path that does not exist, or prominent Published as trust
evidence.

## 10. Implementation Risks

1. Fixture suffix `168` and database key `755` can be confused.
2. Optional summary, qualifications, compensation, employer, and location data
   vary in population and quality.
3. Visual sections can imply fields that do not exist.
4. Employer 212 description is fixture provenance, not polished public copy.
5. Current Apply and Save duplication risks state inconsistency.
6. Raw lifecycle vocabulary can conflict with availability language.
7. `updated_at > published_at` means any mutation, not a meaningful revision
   (`public/class-tnet-jobs-public.php:7674-7679`).
8. Correction/removal is absent; claim exists but is not exposed contextually.
9. Controlled provenance exists but this seed record has no source association.
10. Current shell lacks the governed leaderboard.
11. Finder, detail, application, Saved Jobs/Alerts, and JSON-LD must agree.

## Evidence Integrity

| Evidence | Original source | SHA-256 |
|---|---|---|
| Current implementation JPEG | `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/Captures/Screenshot_12-7-2026_201135_teachers-net.ddev.site.jpeg` | `d24d4c9a4294bf4afe9708da6e1b139ded3641910c681331b7947cb57b680bcb` |
| Single-screen concept PNG | `/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/ChatGPT Image Jul 12, 2026, 08_31_26 PM.png` | `15ef318ce14e836587dfac82eb525b6904417a6a4fdf182bf0046407c18d43a2` |

Source and destination checksums matched; both files opened successfully and
their original formats were preserved.
