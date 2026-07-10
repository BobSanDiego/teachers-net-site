# JREAL001 - Real Job Current-State Inventory

**Project:** Teachers.Net - Job Center

**Audit mode:** Inspection and documentation only

**Audit date:** 2026-07-10
**Evidence sources:** current Jobs plugin code, local DDEV/WordPress runtime, read-only database queries, current Job Center continuity documents, and current plugin documentation.

## 1. Audit scope and stop boundary

This report establishes the factual baseline for real-job ingestion and V1 lifecycle readiness. It inventories current storage, routes, services, public behavior, scheduled processes, import paths, and data present in the local runtime.

No jobs, employers, memberships, settings, cron schedules, files outside this report, schema records, imports, email, or provider requests were changed. Runtime verification was read-only. A behavior that would require creating or transitioning a record is marked **Implementation found - runtime mutation test deferred**.

This report does not choose a real-job contract, propose a schema, design a claim flow, select a geocoder, or begin a pilot.

## 2. Repository and runtime baseline

| Item | Observed baseline | Verification |
|---|---|---|
| Root repository | `main` at `0b2bb12`; tracked tree clean before this report; unrelated untracked `jobs-landing-02a.png:Zone.Identifier` and `tmp/` preserved | `git status --short --branch`, `git rev-parse --short HEAD` |
| Jobs plugin repository | `main` at `9c031ad`; clean and aligned with `origin/main` | `git -C wordpress/wp-content/plugins/tnet-jobs status --short --branch` |
| DDEV/runtime | `teachers-net` running at `https://teachers-net.ddev.site`; PHP 8.4 Apache-FPM and MariaDB 11.8 | `ddev describe --json-output` |
| Active plugins | `tnet-jobs` 0.1.0 and Core Terms (`profilaxes`) 0.6.1 active | read-only WP-CLI plugin list |
| Jobs schema option | `tnet_jobs_schema_version = 0.8.1` | read-only `wp_options` query |
| Database prefix | `wp_` | read-only WP-CLI config query |
| Public routes checked | `/jobs/` and a public job detail route each returned HTTP 200 | `curl` HEAD-equivalent checks |
| Scheduled Jobs events | `tnet_jobs_daily_expiration_notifications`; `tnet_jobs_daily_job_alert_delivery`, both daily | read-only `wp cron event list` |
| Automated test entry points | No `package.json`, `phpunit.xml`, Playwright, Cypress, or conventional `tests/` entry point found within the plugin search scope | filesystem search under plugin root |

### Local runtime data snapshot

| Runtime measure | Count / value | Evidence |
|---|---:|---|
| Jobs | 250 | `wp_tnet_jobs` read-only count |
| Job statuses | 205 published, 15 submitted, 10 draft, 8 closed, 7 expired, 5 archived | grouped read-only query |
| Employers | 50, all shown as approved in the local seed state | grouped read-only query |
| Employer memberships | 0 | `wp_tnet_jobs_employer_users` read-only count |
| Active term rows | 818 active of 4,300 historical `wp_tnet_jobs_terms` rows | read-only query |
| Alerts | 0 | read-only count |
| Engagements | 0 | read-only count |
| Events | 1 (`employer_job_expired_email_sent`, source `email`) | grouped read-only query |
| Application methods | 205 `external_url`; 45 `email` | grouped read-only query |
| Job location modes | 180 onsite, 25 hybrid, 22 multiple, 20 remote, 3 confidential; all 250 have `geocode_status = unknown` | grouped read-only query |
| Seed fixture registry | `tnet_jobs_seed_import_registry` option exists (225,389 bytes); no seed registry table exists | read-only options/tables queries |

The runtime data is fixture-oriented. It demonstrates lifecycle rows and public listings, but it does not establish a tested real-employer workflow because the active employer-membership count is zero.

## 3. System map

### Storage

| Table / option | Current responsibility |
|---|---|
| `wp_tnet_jobs` | Primary job entity, lifecycle timestamps, employer association, salary, location, coordinates, geocode metadata, application method/instructions |
| `wp_tnet_jobs_employers` | Employer name, slug, status, verification state, website, description, ownership/audit timestamps |
| `wp_tnet_jobs_employer_users` | WordPress-user to employer membership, role, active/inactive state, deactivation audit |
| `wp_tnet_jobs_terms` | Job-to-Core-Term assignments by axis; rows are archiveable |
| `wp_tnet_jobs_form_fields`, `wp_tnet_jobs_form_field_terms` | Admin-configured Jobs field mappings to Core Terms source axes and available terms |
| `wp_tnet_jobs_events` | Append-only event records with job, actor, employer, source, message, and JSON metadata |
| `wp_tnet_jobs_engagements` | Per-user job view/save/application-instruction reveal timestamps |
| `wp_tnet_jobs_signals` | Lightweight job signals with user/session/referrer/source fields |
| `wp_tnet_jobs_alerts`, `wp_tnet_jobs_alert_terms` | User alerts, delivery state, and term criteria |
| `wp_options.tnet_jobs_seed_import_registry` | Seed fixture-to-runtime-ID registry; updated by the seed importer |

Schema definitions are in `includes/class-tnet-jobs-schema.php` (`schema_definitions()`); table-name access is centralized by `TNet_Jobs_Schema`.

### Repositories and services

| Area | Primary implementation | Current role |
|---|---|---|
| Job persistence | `includes/repositories/class-tnet-jobs-job-repository.php` / `TNet_Jobs_Job_Service` | CRUD, public-active queries, employer lists, close/archive persistence, expiration-notification candidates |
| Employer persistence | `TNet_Jobs_Employer_Repository` / `TNet_Jobs_Employer_Service` | Employer creation, access request, approval/rejection, archive |
| Employer access | `TNet_Jobs_Employer_User_Repository` / `TNet_Jobs_Employer_User_Service` | Employer/user membership and activation/deactivation |
| Classification | `TNet_Jobs_Term_Service`, `TNet_Jobs_Form_Field_Service`, `TNet_Jobs_Form_Field_Term_Service` | Job term assignment and configured Core Terms options |
| Seed import | `TNet_Jobs_Seed_Import_Service`, `TNet_Jobs_Seed_Import_Command` | JSON fixture import, slug-based update/create, registry option |
| CSV import | `TNet_Jobs_CSV_Import_Service`, `TNet_Jobs_Import_Admin` | Upload, preview, confirmation, creation of draft/submitted jobs |
| Moderation | `admin/class-tnet-jobs-moderation-admin.php` | Admin approval and return-to-draft transitions, event logging |
| Engagement | `TNet_Jobs_Engagement_Service` | Save, unsave, view, and application-instruction reveal storage |
| Alerts | Alert service, matching service, and delivery service | Match active jobs to saved criteria and send scheduled email |
| Communications | `TNet_Jobs_Communication_Service` | Direct `wp_mail()` lifecycle and alert mail; queue persistence is not implemented |
| Public rendering | `public/class-tnet-jobs-public.php` | `/jobs/`, details, saved jobs, alerts, and employer portal routes |

### Public, employer, and admin entry points

| Surface | Routes or handlers | Authorization boundary |
|---|---|---|
| Public finder | `/jobs/` via `TNet_Jobs_Public::render_jobs_page()` | public; query limits to published, unarchived, unexpired jobs |
| Public detail | `/jobs/{job-slug}/` via `render_job_detail_page()` | public only while `is_publicly_visible_job()` is true |
| Saved jobs / alerts | `/jobs/saved/`, `/jobs/alerts/` | WordPress login required for mutations and personal data |
| Employer portal | `/jobs/employer/`, `/jobs/employer/new/`, `/jobs/employer/my-jobs/`, `/jobs/employer/{id}/edit/` | active `wp_tnet_jobs_employer_users` membership for the relevant employer |
| Employer access request | `/jobs/employer/request-access/` | form creates a new pending employer/requester relationship; administrator later approves/rejects |
| Admin jobs | `admin_post_tnet_jobs_create_job`, `update_job`, `archive_job`, `close_job` | `manage_options` and nonce checks in `TNet_Jobs_Jobs_Admin` |
| Admin moderation | `admin_post_tnet_jobs_approve_job`, `return_job_to_draft`, bulk moderation | `manage_options` and nonce checks in `TNet_Jobs_Moderation_Admin` |
| Admin employer review | create/archive/approve/reject employer handlers | `manage_options` and nonce checks in `TNet_Jobs_Employers_Admin` |
| Admin CSV import | import admin page and confirmation handler | administrator-controlled UI with upload-preview-confirm flow |
| Seed CLI | `wp tnet-jobs seed-import` registered by `TNet_Jobs_Seed_Import_Command` | WP-CLI actor user required; not executed for this audit |

### Scheduled processes

`TNet_Jobs::ensure_schedules()` registers daily expiration-notification and daily alert-delivery hooks. `run_daily_expiration_notifications()` invokes `TNet_Jobs_Job_Service::process_expiration_notifications()`; it finds expiring/expired published rows and sends one-time notification emails based on prior event checks. The inspected code does not change a row from `published` to `expired` during this scheduled process.

## 4. Capability matrix

| Capability | Status | Implementation location | Current behavior | Verification | Limitation or unknown |
|---|---|---|---|---|---|
| Direct employer job creation | Implemented | `TNet_Jobs_Public::create_employer_job_from_request()` | Creates submitted jobs for new employers; direct publishes only when the employer already has a published job | verified in code | Runtime mutation test deferred; no active memberships in local data |
| Admin job CRUD | Implemented | `TNet_Jobs_Jobs_Admin`, `TNet_Jobs_Job_Service` | Admin can create, update, close, and archive Jobs rows | verified in code | Runtime mutation test deferred |
| Public active-job query | Implemented and runtime-visible | `TNet_Jobs_Public::browse_jobs_query()` | Limits browse results to published, unarchived, non-expired rows with unpassed `expires_at` | code and `/jobs/` HTTP 200 | No provenance criteria in query |
| Moderation | Implemented | `TNet_Jobs_Moderation_Admin` | Submitted job can be approved/published or returned to draft; records event | verified in code | Runtime mutation test deferred |
| Lifecycle email | Implemented | `TNet_Jobs_Job_Service`, `TNet_Jobs_Communication_Service` | Sends submitted/approved/returned/renewal/expiration notices under defined conditions | code plus one runtime email event | Delivery outcome beyond recorded event not audited |
| Seed JSON import | Implemented | `TNet_Jobs_Seed_Import_Service` | Creates/updates employers and jobs by slug; replaces job terms; persists option registry | code plus existing registry option | Fixture-only design; no external-source provenance fields |
| CSV import | Implemented | `TNet_Jobs_CSV_Import_Service`, `TNet_Jobs_Import_Admin` | Upload/preview/confirm creates draft or submitted jobs and term rows | verified in code | No row update/idempotency/reconciliation/rollback path found |
| External feed / JSON source ingestion | Not found | searched Jobs service, admin, CLI, and docs | No implementation found beyond seed JSON file import | targeted source search | Bulk-import specification mentions future JSON/feed behavior only |
| Source provenance | Not found | `wp_tnet_jobs` schema and Jobs source search | No job columns for source URL/type/external ID/batch/hash/first/last seen | schema plus targeted source search | Seed fixture metadata lives only in registry option |
| Reconciliation | Not found | service/admin/CLI source search | No source-delta, disappearance, correction, or removal reconciliation implementation found | targeted source search | JREAL002 scope |
| Employer request/access | Implemented | `TNet_Jobs_Employer_Service::request_employer_access()` and admin review handlers | Creates a new pending employer plus inactive requester membership; approval activates it | verified in code | Does not attach requester to an existing imported employer |
| Employer claim / ownership transfer | Not found | schema and targeted source search | No claim state, transfer handler, domain verification, or imported-job ownership attachment found | schema and source search | JREAL005 scope |
| Job close/archive | Implemented | `TNet_Jobs_Job_Service::close_job()`, `archive_job()` | Close only accepts published jobs; employer archive accepts draft only; admin archive is broader | verified in code | Runtime mutation test deferred |
| Renew / duplicate | Implemented | `duplicate_employer_job()`, `renew_employer_job()` | Copies the source job and its terms to a new draft with a new slug; renew is restricted to closed/expired source | verified in code | No explicit lineage column; linkage only implicit in new copied slug/action event behavior |
| Distance query | Implemented | `browse_jobs_query()`, `radius_search_sql_clause()` | Bounding-box prefilter and Haversine distance; radius result sorting by distance | verified in code | Job coordinates are all runtime `unknown`; typed origin uses current job inventory |
| Browser current location | Implemented | `public/js/tnet-jobs-public.js` | Browser Geolocation API sends rounded current coordinates through the current form request | verified in code | Browser permission/runtime test deferred; no persistent user-coordinate storage found |
| Automatic job geocoding | Not found | job service and provider searches | Metadata fields exist but no provider/client/queue/repair flow found | targeted source search | JREAL004 scope |
| Job alerts | Implemented | alert/matching/delivery services and daily hook | Matches active jobs by keyword, employment type, and configured term criteria; sends daily emails | verified in code and scheduled hook runtime | No radius-origin matching found |

## 5. Job data-field inventory

### Primary job record: `wp_tnet_jobs`

| Data area | Field(s) | Nullable/default | Service/repository exposure and validation | Current use / population evidence |
|---|---|---|---|---|
| Identity | `job_id`, `job_slug` (unique) | ID generated; slug required by repository, otherwise Job Service creates unique slug from title | `TNet_Jobs_Job_Repository`, `TNet_Jobs_Job_Service::unique_slug_from_title()` | Seed importer finds existing rows by slug; public detail resolves by slug |
| Lifecycle | `status` | default `draft`; schema accepts varchar | Job Service allow-list includes draft, submitted, approved, published, closed, archived; repository accepts stored string | Runtime also contains 7 `expired` rows, inserted by seed path that bypasses Job Service validation |
| Content | `title`, `summary`, `description`, `requirements_qualifications` | title/description required; summary/requirements nullable | Job Service sanitizes; repository enforces title/description | displayed on public detail; titles shown in finder |
| Employment / salary | `employment_type`, `salary_type`, `salary_min`, `salary_max`, `salary_currency` | employment type required; salary defaults to undisclosed/USD; amounts nullable | Job Service validates type, numeric non-negative values, and min <= max when both supplied | employer/admin/seed/CSV input; public list/detail display |
| Work and location | `location_mode`, `address_line_1`, `address_line_2`, `city`, `postal_code`, `country` | location mode defaults onsite; address/city/postal nullable; country defaults US | Job Service accepts onsite, hybrid, remote, multiple, confidential; CSV and seed normalize `multiple_locations`/`district_wide` to `multiple` | public display combines stored city/postal with Core Terms location assignment; seed data uses all supported modes |
| Coordinates | `latitude`, `longitude` | nullable decimals | Job Service validates numeric geographic bounds and stores seven decimal places | radius query excludes null coordinates; local rows have `geocode_status=unknown` |
| Geocode metadata | `geocode_status`, `geocode_provider`, `geocode_precision`, `geocoded_at`, `geocode_error` | status default unknown; other fields nullable | `prepare_geocode_data()` validates known status values | schema/data foundation exists; no automated population path found |
| Application | `apply_method`, `apply_url` | method default external_url; instructions required by repository | Job Service allows external_url, email, internal; all use `apply_url` text storage | public detail reveals protected instructions; runtime: 205 external URL and 45 email methods |
| Employer and actor | `employer_id`, `created_by_user_id`, `moderated_by_user_id` | employer/moderator nullable; creator required | Job Service validates positive IDs when supplied | employer scope, moderation audit, seed/CSV ownership |
| Timestamps | `created_at`, `updated_at`, `submitted_at`, `approved_at`, `published_at`, `closed_at`, `expired_at`, `archived_at`, `expires_at` | milestone timestamps nullable; `expires_at` required | Job Service normalizes empty timestamps; repository persists fields | public active query enforces status, archive/expired flags, and future expiration |

### Associated job data

| Data | Storage | Current behavior |
|---|---|---|
| Grade, subject, location and other classifications | `wp_tnet_jobs_terms` with `term_id`, `term_axis`, `archived_at` | Jobs own assignment rows; Core Terms is the configured term source; seed/CSV/employer flows assign or replace terms |
| Engagement | `wp_tnet_jobs_engagements` | Per user/job timestamps for view, save, and instruction reveal; current runtime has no records |
| Event/audit record | `wp_tnet_jobs_events` | `event_type`, actor/employer IDs, source, message, JSON metadata; current runtime contains one expiration-email event |
| Signals | `wp_tnet_jobs_signals` | Generic type/user/session/referrer/source data; not a real-job provenance model |

### Data not found as explicit job storage

The `wp_tnet_jobs` schema and targeted source search found no explicit job fields for school name, state/region, application email separated from free-form instructions, source type, source URL, external source job ID, import batch, content hash, first seen, last seen, last verified, source disappearance, correction/removal request, claim state, or ownership-transfer state. State/work location is currently represented through Jobs-to-Core-Term assignment, not a dedicated job column.

## 6. Lifecycle transition matrix

| Transition / state | Initiating actor and entry point | Authorization / validation | Timestamp and side effects | Public effect | Reversible evidence |
|---|---|---|---|---|---|
| Create -> draft | Admin create, CSV import with `draft`, or employer duplicate/renew | Admin capability or employer membership; repository requires core required fields | `created_at`; duplicate/renew gives 30-day `expires_at` | not public | Draft may be edited; employer draft may be archived |
| Create -> submitted | Employer without prior published job; CSV import default | active employer membership or importing admin; required fields; CSV exact active employer match | `submitted_at`; queue-pending email when first pending item; recruiter submitted email if membership/email available | not public | Moderation returns it to draft or approves it |
| Submitted -> published | Admin moderation approve | `manage_options`, nonce, submitted/active moderation eligibility | sets `moderated_by_user_id`, `approved_at`, `published_at`; records moderation event; recruiter approved email path | public while unarchived and unexpired | Employer edit can send non-trusted employer back to submitted; close/archive actions exist |
| Employer create -> published | Employer with any prior published job for same employer | active membership; trusted behavior is actually `has_prior_published_for_employer()` | creation sets approved/published time | public while active | Same future edit/reclose rules apply |
| Submitted -> draft | Admin moderation return | `manage_options`, nonce, moderation eligibility | updates `moderated_by_user_id`; records return event; recruiter returned email | not public | Can be edited or submitted again by normal creation/edit behavior |
| Published -> closed | Employer close flow or admin close handler | employer membership for job employer or administrator; `close_job()` only allows published | sets `closed_at`, status closed | removed from public active query | Renew creates a separate draft; no reopen transition found |
| Published / any -> archived | Admin archive; employer archive only for draft | admin path or employer membership + draft status | sets `archived_at`, status archived | excluded | No restore transition found |
| Published -> expired by date | Public query and alert visibility treat past `expires_at` as inactive; daily process finds candidates for email | date comparison; no state transition in inspected daily code | daily service may send one-time expiration email event | excluded from public active query | Renew creates a new draft for expired/closed source |
| Explicit `expired` status | Seed importer can insert it directly through repository | seed input validation, not Job Service allowed-status check | seed importer sets `expired_at` from `expires_at` | excluded | Runtime has 7 such records; no normal service transition to this status found |
| `approved` status | Job Service allow-list only | no normal moderator route writes this value | none identified | not included in public active query | No concrete caller found in specified source search |

**Lifecycle evidence and caution:** `TNet_Jobs_Job_Service::is_allowed_status()` does not include `expired`, while the seed importer writes directly through `TNet_Jobs_Job_Repository` and local runtime contains `expired` rows. The database status field is not an enum. This is recorded as a code/runtime inconsistency, not reconciled here.

## 7. Application-method matrix

| Stored method | Supporting storage and validation | Public behavior | Logged-out / logged-in behavior | Destination, tracking, and failure behavior | Current paths |
|---|---|---|---|---|---|
| `external_url` | `apply_url` is required by Job Service when this method is selected | labelled **Employer website** | Logged out users are directed to WordPress login before instructions; logged-in users must reveal instructions | `render_apply_instructions()` turns the stored text into clickable content; `reveal_instructions` records an engagement timestamp | employer form, CSV required `application_details`, seed application instructions |
| `email` | Same `apply_url` free-form storage; no dedicated email column | labelled **Email address** | same protected/reveal flow | rendered as text processed by `make_clickable`; no Teachers.Net application relay found | seed and employer form allow it; runtime has 45 records |
| `internal` | Same `apply_url` free-form storage | labelled **Written instructions** | same protected/reveal flow | rendered as sanitized text/linkified content; no internal application form or submission workflow found | employer form supports it; current runtime grouping did not show records |

`TNet_Jobs_Job_Service::prepare_job_data()` validates method names. The public detail renderer checks closed/expired/public visibility before showing an apply control. A blank instruction text renders the explicit “Application instructions are not available for this listing” message, although normal repository insert requires `apply_url`.

Teachers.Net records a protected-instructions reveal but does not receive, relay, or track the outcome of an external employer application. There is no separate application-email, application-deadline, source-application integrity, or external-destination validation implementation found in the specified Jobs source search.

## 8. Import-path comparison

| Path | Implementation status | Input and employer handling | Lifecycle / term handling | Identity, update, reporting, recovery |
|---|---|---|---|---|
| Seed JSON importer | Implemented | Reads `data/jobs-seed.json` through CLI service; employers identified by slug and marked approved; fixture IDs retained in option registry metadata | accepts supplied status/timestamps; replaces job term assignments from configured labels | Job lookup/update by slug makes reruns idempotent for matching slugs; CLI emits create/update/skipped summary and validation errors; no transaction/rollback/reconciliation found |
| Admin CSV importer | Implemented | Upload -> parser -> preview -> confirm; requires exact active employer name match | only creates `submitted` or `draft` jobs; required location Core Term; grade/subject unmatched values become warnings | creates every accepted row through Job Service; records `csv_import_job_created` event with line/warnings; no update matching, duplicate check beyond generated slug collision, dry-run import, batch identity, rollback, or recovery action found |
| Admin bulk-import specification | Documentation only and conflicts with implementation | `docs/bulk-import-spec.md` says ADM06 foundation does not create jobs | describes planned/future concerns such as source authentication and idempotency | current CSV implementation does create jobs; see conflicts section |
| JSON feed / external source path | Not found | no feed/client/admin/CLI path found outside seed fixture JSON | not found | not found |
| Recruiter UX fixture command | Implemented development fixture | `wp tnet-jobs recruiter-ux-fixture` creates known local visual-QA data | exercises employer lifecycle states | explicitly local fixture tooling, not a real-job ingestion path; not executed for this audit |

## 9. Provenance and reconciliation inventory

| Capability | Finding | Evidence |
|---|---|---|
| Source type / source URL / external source job ID | Not found as a job field or ingestion model | `wp_tnet_jobs` schema and targeted source search for source/external identifiers |
| Deterministic source identity | Seed-only, by fixture/job slug | Seed importer updates existing job by `job_slug`; this is not an external-source identity contract |
| Import batch identity | Not found | no batch column or batch repository/service found; CSV event only stores line/warnings in JSON |
| Content hash / change detection | Not found | schema and source search for hash/diff/change detection |
| First seen / last seen / last verified | Not found | schema and source search |
| Employer/source pairing | Seed registry maps an employer fixture ID to runtime employer ID; CSV matches exact employer name | `tnet_jobs_seed_import_registry`; CSV `get_by_name()` |
| Reconciliation / source disappearance / automatic closure | Not found | no service or scheduled task found beyond expiry notification |
| Temporary source failure or exception record | Not found as source-ingestion capability | errors are returned in seed/CSV summaries; no durable source-run model found |
| Correction/removal request | Not found | no public or admin request/queue capability found in target searches |
| Public source disclosure | Not found | public list/detail templates show employer and application instructions, not source provenance |

The generic `source` field in `wp_tnet_jobs_events` and `wp_tnet_jobs_signals`, and Core Terms form-field `source` identifiers, are not job-origin provenance. They must not be interpreted as such.

## 10. Employer association and workflow inventory

### Employer association and ownership

| Primitive | Status | Evidence | Boundary / limitation |
|---|---|---|---|
| Employer record | Implemented | `wp_tnet_jobs_employers`; `TNet_Jobs_Employer_Service` | name, slug, status, verification, website/description, creator/verifier audit |
| User-to-employer membership | Implemented | `wp_tnet_jobs_employer_users`; employer-user service | active membership required by public employer portal and job action access contexts |
| Job-to-employer association | Implemented | `wp_tnet_jobs.employer_id` | employer action contexts require membership for the job's exact employer |
| Employer access request | Implemented | public request route plus `request_employer_access()` | creates a new pending employer rather than locating/claiming an existing one |
| Administrative employer approval/rejection | Implemented | `TNet_Jobs_Employers_Admin` handlers | `manage_options` and nonce protected |
| Trusted publisher behavior | Implemented, narrowly defined | `employer_has_prior_published_job()` | trust means only prior published job existence, not employer verification status/domain validation |
| Employer dashboard / My Jobs | Implemented | public employer rendering methods | manages selected employer membership scope; metrics derive from engagement rows |
| Imported job with no recruiter account | Partially supported at storage level | `employer_id` nullable, `created_by_user_id` required | no inspected import path creates an unclaimed-employer/recruiter lifecycle or a later attach/claim operation |
| Employer claim, domain verification, transfer | Not found | targeted schema/service/route search | no claim state, domain verification, or transfer handler found |

### Direct employer workflow

| Step | Classification | Current route / implementation | Evidence and limitation |
|---|---|---|---|
| 1. Request access | Implemented | `/jobs/employer/request-access/`; `request_employer_access()` | creates pending employer + inactive membership; runtime mutation test deferred |
| 2. Create employer/profile | Partially implemented | access request captures name, website, description; admins can create/manage employers | public profile editing path was not found in inspected public routes |
| 3. Create job | Implemented | `/jobs/employer/new/` | active membership and selected employer required |
| 4. Save draft | Partially implemented | duplicate/renew creates draft; CSV can import draft | direct new-employer create flow submits/publishes rather than presenting an inspected save-draft control |
| 5. Submit | Implemented | untrusted employer create/edit lifecycle | `submitted_at` set and moderation queue notifications attempted |
| 6. Moderate / auto-publish | Implemented | admin moderation; prior-published employer direct publish | approval/return code verified; runtime mutation test deferred |
| 7. View public listing | Implemented | `/jobs/{slug}/` | public route checked HTTP 200 for current seed record |
| 8. Edit | Implemented | `/jobs/employer/{id}/edit/` | relevant active employer membership and eligible status required |
| 9. Close | Implemented | employer close form / `close_job()` | only published jobs close; runtime mutation test deferred |
| 10. Renew | Implemented | `renew_employer_job()` | creates a separate draft from closed/expired source |
| 11. Duplicate | Implemented | `duplicate_employer_job()` | creates separate draft and copies terms |
| 12. Archive | Implemented with scope difference | admin archive; employer draft archive | employer can archive only drafts; no restore found |
| 13. Inspect metrics | Implemented | employer dashboard/My Jobs render engagement counts | current runtime engagement rows are zero |
| 14. Receive lifecycle communications | Implemented in code | job/communication services | requires active employer membership and valid WordPress user email; delivery mutation test deferred |

## 11. Coordinates, origin resolution, and typeahead inventory

### Job coordinates and geocode metadata

`wp_tnet_jobs` contains `latitude`, `longitude`, `geocode_status`, `geocode_provider`, `geocode_precision`, `geocoded_at`, and `geocode_error`, with a latitude/longitude index. `TNet_Jobs_Job_Service::prepare_location_data()` validates coordinates and `prepare_geocode_data()` validates metadata. Seed input may supply coordinates. Employer create/edit and CSV preparation carry city/postal/location mode, but the inspected paths do not call a provider or populate coordinates automatically.

No Google client, geocoder interface, retry queue, repair command, exception reporter, or manual coordinate-correction UI was found in the inspected Jobs source. Local runtime rows all report `geocode_status = unknown`; that does not prove coordinates are absent, but it confirms no current status has been recorded as geocoded.

### Typed origin and radius query

| Capability | Current verified behavior | Evidence |
|---|---|---|
| Advanced finder controls | Origin (ZIP or City, State), distance options, cross-state checkbox, and browser-current-location control appear in the shared finder advanced panel | `render_jobs_landing_*` helpers |
| ZIP resolution | Averages coordinates from existing Jobs rows with matching postal code | `resolve_distance_origin()` |
| City/state resolution | Averages coordinates from existing Jobs rows by lowercased city; state accepts only `CA`/`California` when supplied | `resolve_distance_origin()` |
| Query | Bounding box plus Haversine predicate; null coordinate jobs excluded; results sort by distance | `radius_search_sql_clause()` and `browse_jobs_query()` |
| Cross-state flag | Suppresses the selected Core Terms location constraint only during an active radius query | `browse_jobs_query()` |
| Validation | Invalid/missing typed origin produces inline guidance, not a substitute state query | `resolved_distance_search_request()` |
| Browser current location | User-initiated Geolocation API request with 10-second timeout; coordinates rounded to four decimals and submitted as hidden form fields by POST | `public/js/tnet-jobs-public.js` |
| Persistence / URL | Typed origin and distance appear in pagination URL; browser coordinates are posted in the current request and are not added to pagination URL through the resolved-distance branch | `pagination_url()` and frontend JS |
| Analytics | No analytics integration or `gtag` tracking found in current Jobs public JS | targeted public-JS search |

The typed-origin resolver is inventory-dependent, not a geographic reference dataset or provider lookup. It will only resolve a ZIP/city when Jobs rows with coordinates already exist. It also currently restricts an explicitly supplied state to California. This is existing behavior, not a nationwide resolver claim.

### Typeahead / autocomplete

No typeahead, local suggestion dataset, or provider autocomplete integration was found in public JS/PHP, services, or dependencies. The architecture document contains provider-oriented planning language, but no implementation was found.

## 12. Public presentation inventory

| Public behavior | Current implementation | Missing / constrained behavior |
|---|---|---|
| Job title, employer, location, salary, employment type, work arrangement, posted/updated date | `render_browse_job_row()`, `render_job_detail()`, and location/salary helpers | Depends on available stored values and Core Terms assignments; no provenance label |
| Expiration/closure | Detail page renders “Position closed” or “Position expired”; browse query excludes inactive jobs | archived/submitted/draft records are not public; no public source-removal explanation found |
| Description / requirements | Detail sections render when content exists | no source provenance/correction pathway displayed |
| Classification | Detail uses Jobs term rows grouped as location, subject, grade | missing term rows omit their presentation; no consumer-side taxonomy hardcoding observed |
| Application CTA | Logged-out users are sent to login; logged-in users reveal protected instructions before external/email/internal text is displayed | no Teachers.Net application relay or application-outcome state |
| Save job | Public detail/list forms use engagement service for authenticated users | runtime has no engagement rows; action mutation not tested |
| Structured data | `render_jobposting_json_ld()` emits JobPosting data from public job fields | provenance and real-source data are not included because no such storage exists |
| Employer information | Detail shows employer name and generic About Employer block | no public employer profile route, source disclosure, claim path, correction request, or removal request found |

The public detail route and `/jobs/` both returned 200 during this audit. No visual redesign or screenshot QA was performed.

## 13. Pilot-readiness baseline

| Pilot capability | Classification | Evidence / current limitation |
|---|---|---|
| 3-5 employers | Supported in implementation; runtime mutation test deferred | employer records, request/approval service, membership model, and portal exist; local runtime currently has zero memberships |
| 50-100 real jobs | Partially supported | job schema and CSV/seed creation paths support records; no real-source contract/provenance/reconciliation basis exists |
| Repeated import runs | Supported and verified for seed fixtures only | seed importer updates by job/employer slug; registry option exists |
| Changed source records | Partially supported | seed slug rerun updates entire row; no external-source identity/change model |
| Removed source records | Not found | no reconciliation/disappearance/closure path found |
| Duplicate records | Partially supported | unique job slug and seed update-by-slug; CSV has no documented duplicate matching beyond collision error/generation |
| Multiple cities and ZIPs | Supported in storage and distance query implementation | city/postal/coordinates supported; local fixture has California-oriented resolver behavior |
| On-site, hybrid, remote | Supported and verified in runtime | all modes are represented in grouped runtime data and validated by Job Service |
| Varied external application destinations | Supported and verified for stored methods | external URL and email rows exist; method behavior inspected; external delivery not tested |
| Varied expiration dates | Supported and verified in runtime | active, closed, expired/archived fixture statuses and expiration timestamps exist |
| Exception reporting | Partially supported | seed/CSV return error/warning summaries and event records; no persistent source-run exception system |
| Distance Search participation | Supported in implementation; runtime mutation test deferred | advanced finder, resolver, Haversine query, browser location flow are implemented; no real-job coordinate population workflow |
| Truthful provenance | Not found | no source/provenance fields or public disclosure path |
| Simulated employer claim | Not found | request access creates a new employer; no attach/claim flow for an existing imported employer/job |

## 14. Documentation/code/runtime conflicts

| Conflict | Current implementation/runtime evidence | Documentation evidence | Treatment in this audit |
|---|---|---|---|
| CSV import creates jobs | `TNet_Jobs_CSV_Import_Service::import_rows()` calls `TNet_Jobs_Job_Service::create_job()` and records a CSV import event | `docs/bulk-import-spec.md` states its ADM06 foundation “does not create jobs” | Recorded; documentation not changed |
| Distance Search planning-only statement | JDIST003-006 implementation is present: schema metadata, indexed coordinates, radius query, advanced controls, typed resolution, browser location | `docs/distance-search-architecture.md` ends with “planning only” and says not to implement schema/public UI/query behavior | Recorded; document not reconciled |
| Location strategy calls coordinates future | current `wp_tnet_jobs` has coordinate/geocode fields and public distance query code | `docs/job-location-strategy.md` calls latitude/longitude future-facing | Recorded; no document change |
| `expired` status differs by path | runtime has 7 explicit expired rows; seed importer can insert status directly; public queries exclude them | Job Service allowed-status list and its invalid-status error omit `expired`; normal expiration process only sends notifications | Recorded; no lifecycle correction |
| Current roadmap expects provenance/claims later | current schema/source search finds no provenance/reconciliation/claim primitives | `docs/jobs-roadmap.md` correctly lists these as upcoming readiness work | Not a contradiction; it confirms the missing capabilities found here |

## 15. Unknowns requiring later focused audits

### JREAL002 - Ingestion, Provenance, Deduplication, and Reconciliation Audit

- What exact real-source identity and import-run behavior is needed; current Jobs storage has none.
- Whether seed slug idempotency can be separated from real-source identity.
- How duplicate candidate handling, changed-record detection, source disappearance, correction/removal requests, and durable exception reporting should behave.
- Whether the existing CSV import can safely participate in a controlled real-job workflow beyond creation.

### JREAL003 - Application, Expiration, and Lifecycle Audit

- Whether free-form `apply_url` is sufficient for external URL, email, and written instructions in a real-job contract.
- Whether ordinary expiry should ever set `status = expired`, and how that relates to current date-based visibility and seed explicit expired rows.
- The intended reversibility/retention of archive, close, renew, duplicate, and moderation transitions.
- Runtime mutation verification of the documented lifecycle notifications and direct-employer transitions.

### JREAL004 - Location, Geocoding, and Origin Resolution Audit

- How employer-created and real-imported jobs receive trustworthy coordinates; no provider, retry, repair, or manual correction path exists.
- Whether inventory-derived ZIP/city resolution is acceptable beyond the current California fixture behavior.
- Required national origin data, typeahead/autocomplete source, error reporting, and coordinate quality policy.
- Runtime browser permission and privacy verification for current-location search.

### JREAL005 - Employer Workflow and Claim Readiness Audit

- How an existing imported employer or job can be discovered, verified, claimed, and attached to a WordPress user without creating a duplicate employer.
- What authority verification, ownership transfer, and role-management behavior is needed.
- Whether current prior-published-job trust is sufficient for direct publication in a real-employer workflow.
- Runtime mutation verification of request, review, portal management, close, renewal, duplicate, archive, and metrics.

## 16. Existing systems that later tickets must preserve

- The single `wp_tnet_jobs` entity and its public visibility predicate: published, unarchived, not explicitly expired, and not past `expires_at`.
- Core Terms integration through Jobs term assignment rows and configured form-field mappings; Jobs does not own taxonomy labels.
- Job, employer, and employer-user repository/service boundaries.
- Public `/jobs/` finder, shared Search/Browse route state, result pagination, classification chips, distance-query integration, saved-job controls, and detail route behavior.
- Existing public employer membership authorization: users manage only jobs attached to an employer for which they hold active membership.
- Current moderation handlers, nonces, administrator capability checks, and moderation event records.
- Existing direct-employer lifecycle controls: create, edit, close, duplicate, renew, and draft archive.
- Existing seed fixture registry option and slug-based seed idempotency for local QA data.
- Existing CSV preview/confirmation/error summary behavior and its Core Terms location/grade/subject mapping.
- Existing scheduled alert-delivery and expiration-notification hooks, including their event-based one-time email guard.
- Coordinate columns, geocode metadata fields, latitude/longitude index, radius query, typed-origin validation, and browser-location privacy behavior already present in JDIST003-JDIST006.

## Read-only verification record

- Inspected root and nested Jobs repository status before report creation.
- Read current continuity/governance documents and requested Jobs documentation.
- Read schema, repositories, services, importer, admin moderation/employer/import code, public renderer, public JavaScript, and route registrations.
- Ran only read-only DDEV/WP-CLI database, plugin, cron, option, schema, status, and HTTP checks.
- Did not execute an importer, create/update a job/employer/membership, alter schema, send email, call providers, or change cron schedules.
- No Core Terms files or database state were changed.
