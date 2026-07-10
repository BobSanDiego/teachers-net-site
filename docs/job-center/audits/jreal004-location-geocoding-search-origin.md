# JREAL004 - Location, Geocoding, and Search-Origin Audit

**Project:** Teachers.Net - Job Center

**Audit mode:** Inspection and documentation only

**Audit date:** 2026-07-10

**Evidence base:** JREAL001-JREAL003, completed JDIST002-JDIST006 code, current local runtime, and current Jobs location documentation. This report recommends a V1 location/origin contract only; it does not authorize implementation, schema changes, provider calls, datasets, or UI changes.

## 1. Scope and stop boundary

This audit defines the smallest reliable V1 location model for real jobs and Distance Search. It covers Jobs-owned physical location facts, coordinate quality, geocode metadata, typed origin resolution, browser current location, radius search, cross-state behavior, public location presentation, repair, and typeahead boundaries.

No data, schema, options, Jobs code, Core Terms data, external provider, browser permission, or source website was changed or contacted. JDIST002-JDIST006 are treated as completed current functionality.

## 2. Current implementation

| Area | Verified current behavior | Evidence |
|---|---|---|
| Job location storage | Jobs stores location mode, address lines, city, postal code, country, latitude/longitude, and geocode metadata in `wp_tnet_jobs` | `TNet_Jobs_Schema::create_table_sql()` |
| Coordinate validation | Job Service accepts numeric latitude/longitude in geographic bounds and stores seven decimal places | `TNet_Jobs_Job_Service::prepare_location_data()` |
| Geocode metadata | Job Service accepts status `unknown`, `pending`, `geocoded`, `failed`, `stale`, or `manual`, plus provider, precision, time, and error fields | `prepare_geocode_data()` |
| Employer-created location intake | Employer form asks for arrangement, address, city, ZIP/postal, and country; it does not ask for coordinates or invoke geocoding | `render_employer_location_metadata_fields()` and create/edit handlers |
| Seed location intake | Seed JSON importer accepts city, postal, country, latitude, longitude, and normalizes legacy multiple/district modes | `TNet_Jobs_Seed_Import_Service::job_row()` |
| CSV location intake | CSV accepts state, city, postal and location mode; it does not populate coordinates | `TNet_Jobs_CSV_Import_Service::prepare_import_row()` |
| Radius query | Local bounding-box prefilter plus Haversine calculation; jobs without coordinate pairs are excluded; active radius sort is distance ascending | `browse_jobs_query()` and `radius_search_sql_clause()` |
| Advanced controls | Search and Browse Advanced panels provide typed origin, 10/25/50/100/250 mile radius, cross-state option, and browser location button | `render_jobs_landing_*` helpers |
| Cross-state behavior | When radius is active and cross-state is selected, the location Core Terms filter is skipped; normal location filtering remains in place otherwise | `browse_jobs_query()` |
| Browser location | User-initiated browser Geolocation API; rounded to four decimals; submitted through POST; no profile/database write path found | `public/js/tnet-jobs-public.js` |
| Typeahead/autocomplete | Not found in Jobs PHP/JS; no provider client or local suggestion service found | targeted source search |
| Provider geocoding | Not found in Jobs code; no Google or other provider client/interface/queue/repair command exists | targeted source search |

### Local runtime coordinate snapshot

Read-only database inspection found all 250 fixture jobs have latitude/longitude pairs: 180 onsite, 25 hybrid, 22 multiple, 20 remote, and 3 confidential. All 250 rows have `geocode_status = unknown`; 24 distinct cities and 24 distinct postal codes are present, all in one country. The fixture therefore demonstrates coordinate storage and radius queries, but it does not establish trustworthy coordinate source, precision, or repair history.

## 3. Coordinate lifecycle

### Current lifecycle

1. Seed input may supply coordinates directly.
2. Employer create/edit and CSV input collect human-readable location but do not derive coordinates.
3. Job Service validates coordinates/metadata only if a caller supplies them.
4. Radius search reads stored coordinates locally; it makes no provider call.
5. Missing coordinate pairs exclude only radius-limited results, not normal search/browse.

There is no current automatic geocoding, local reference lookup, retry, stale-location detection, repair queue, administrator correction workflow, or coordinate-source audit beyond nullable metadata fields.

### Recommended V1 coordinate-quality contract

| Job location mode | Minimum location fact | Distance eligibility | Required coordinate quality |
|---|---|---|---|
| On-site | country, state/location classification, city, and ZIP for pilot jobs | yes | coordinate derived from an authoritative local ZIP reference; record ZIP-level precision/source |
| Hybrid | same as on-site, reflecting the anchor location | yes | same ZIP-level coordinate rule; public display remains Hybrid + city/state |
| Remote | explicit remote arrangement; no physical address required | no by default | no job coordinate required; do not include merely because employer has a location |
| Multiple locations | declared multi-location scope plus an individual eligible location only when source data supports it | not by default for an aggregate listing | do not fabricate one coordinate for a broad/multi-site opportunity |
| Confidential | explicit confidential arrangement and safe public location policy | no by default | no public/distance coordinate unless an approved safe internal location exists |

For the controlled US pilot, ZIP-centroid precision is sufficient for distance discovery if the product describes results as approximate distance and does not claim street-address precision. A city/state centroid is a fallback only when no ZIP is available; it must be marked as lower precision and should be eligible for repair. The pilot should not accept a non-remote distance-eligible job with only an unverified address line or no usable city/state/ZIP evidence.

## 4. Origin-resolution model

### Current behavior

| Origin type | Current resolution | Limitation |
|---|---|---|
| Typed five-digit ZIP | Averages coordinates from existing Jobs rows sharing the postal prefix | Fails if no current job has coordinates in that ZIP; origin quality depends on inventory |
| Typed City, State | Averages coordinates from existing Jobs rows matching city; explicit state accepts only `CA` or `California` | Inventory-dependent and California-specific; ambiguous city names cannot be safely resolved nationwide |
| Browser current location | Browser supplies coordinate pair after user action | request-scoped convenience only; no typed-name representation or durable continuation mechanism |
| Direct internal radius params | accepts valid latitude/longitude/radius request params | internal verification path, not a public origin model |

Typed origin resolution is therefore currently inventory-dependent. It is not a ZIP/city reference service and cannot support a national real-job marketplace reliably.

### Recommended V1 origin-resolution contract

1. **Typed ZIP:** resolve against a versioned local US ZIP reference dataset to one documented centroid/quality record; do not query current Jobs inventory or a provider during public search.
2. **Typed City, State:** require an unambiguous city plus state/territory input, resolve through the same local reference dataset, and return an explicit ambiguity/error result rather than guessing across states.
3. **Browser current location:** use only after a user click and only for the current search; no profile, alert, analytics, URL, or long-lived location storage.
4. **Radius query:** continue using only local Jobs coordinates, the existing latitude/longitude index, bounding-box prefilter, Haversine calculation, normal visibility filters, and distance ordering.
5. **Cross-state option:** preserve current opt-in behavior. It broadens radius matching by omitting the selected location-classification constraint only when radius search is active.
6. **Origin failure:** show inline guidance and do not run a substitute state/category search that appears to be distance search.

The local reference dataset is a Jobs-owned operational lookup. It is not Core Terms taxonomy and does not replace state/location classification.

## 5. V1 geocoding model

### Recommendation

For the controlled V1 pilot, derive distance-eligible job coordinates from the same high-quality local ZIP/city reference dataset used for typed origins. Require ZIP-first evidence for on-site/hybrid jobs, allow city/state fallback at explicitly lower precision, and route unresolved or conflicting values to a repair exception. Preserve source-provided coordinates only when their source/precision can be identified and they pass validation.

This is a **reference-data lookup model**, not live third-party geocoding. It does not require street-address resolution, Google Maps Geocoding, Google Places, or provider calls during public search or employer save.

### Provider decision

**Google services are not required for V1.** A versioned, licensed, maintainable local ZIP/city reference dataset plus browser geolocation can fully support the stated V1 needs when those needs are limited to US ZIP/city-level origin and job distance precision. It is sufficient for the pilot and removes external API cost, provider availability, privacy, quota, and vendor-lock-in from the V1 critical path.

Google Places autocomplete and rooftop/street-address geocoding remain V1.1 candidates only if later evidence requires greater precision or a richer address-entry experience. They are not prerequisites for a controlled pilot, nor should they be used to resolve public searches.

## 6. Local dataset versus provider comparison

| Capability | Local ZIP/city reference dataset | Google/provider services | V1 conclusion |
|---|---|---|---|
| Typed ZIP origin | deterministic, offline/local, fast, privacy-preserving | provider call possible but unnecessary | local dataset required |
| Typed City, State origin | deterministic with state disambiguation and aliases | richer fuzzy matching but external dependency | local dataset sufficient |
| Job coordinate from ZIP/city | ZIP/city centroid, documented precision | can produce rooftop/address precision | local precision sufficient for V1 pilot |
| Browser current location | browser API; no provider | provider not needed | preserve browser API |
| Public radius query | local database only | provider should never be queried | preserve existing local query |
| Typeahead/autocomplete | optional local prefix/alias index | provider has polished UX | defer; provider not required |
| Street-address correction | limited without an address reference | provider may help later | exception/manual repair in V1; V1.1 candidate for provider assistance |
| Privacy / cost / uptime | controlled locally; dataset licensing/refresh responsibility | external terms, quota, cost, availability, data disclosure | local model preferred for V1 |

The local-dataset option is viable only with a documented source/license, version, coverage scope, refresh process, city/state aliases, ZIP centroid coordinates, and a way to flag unsupported/outdated records. Those are operational requirements, not a reason to return to inventory-derived resolution.

## 7. Browser-location behavior and origin persistence

### Current behavior

- Permission is requested only from the visible `Use my current location` control.
- JavaScript uses `enableHighAccuracy: false`, `maximumAge: 60000`, and a 10-second timeout.
- Coordinates are rounded to four decimals before POST submission.
- Denied, unavailable, and timeout paths show typed-origin fallback guidance.
- No DB/profile/alert/analytics persistence path was found.

### Current limitation

The generated pagination URL preserves typed origin and radius but intentionally omits browser coordinates. That protects precise location from URLs, but the browser-origin result cannot be reproduced reliably on a later pagination request without another location request or a privacy-preserving continuation mechanism. This is a real current-origin persistence gap, not a reason to place exact coordinates in the URL.

### V1 contract

- Typed ZIP/city origin is the stable, shareable, pageable distance-search origin.
- Browser current location is an opt-in convenience and must remain non-persistent outside the active search context.
- Exact coordinates must not enter profile records, alerts, analytics, long-lived options, or shareable URLs.
- Before pilot acceptance, the browser-origin continuation behavior must be explicit and privacy-preserving; it cannot silently degrade to an unrelated result set on pagination/refresh.

## 8. Typeahead strategy

No autocomplete/typeahead exists today. V1 does not need provider-backed autocomplete.

The smallest appropriate V1 entry model is strict typed input with clear examples: five-digit ZIP or `City, ST`. A local dataset may later provide deterministic validation and a short list of city/state disambiguations, but full fuzzy address autocomplete is not required for the pilot. Google Places should remain outside V1.

## 9. Repair and retry model

| Condition | V1 disposition | Evidence / recovery |
|---|---|---|
| Valid ZIP resolves through local dataset | coordinate ready | record local reference source, ZIP-level precision, lookup version/time |
| City/state fallback resolves | coordinate ready with lower confidence | record city-level precision; queue for ZIP improvement if available |
| Source supplies valid, attributable coordinates | accept after bounds/consistency validation | retain source/precision evidence; do not replace silently with lower precision |
| Location inconsistent with ZIP/city/state reference | exception; do not use for radius until resolved | retain raw source input and conflict result |
| Missing ZIP for non-remote pilot job | exception/manual correction | may remain non-distance-eligible only under approved pilot exception policy |
| Local reference record unavailable/stale | exception/retry; not provider fallback by default | retain lookup version/error; operator repairs data or maps location |
| Remote/multiple/confidential job | no coordinate repair required unless safe eligible location is explicitly supplied | keep normal public eligibility separate from radius eligibility |
| Employer edits physical location | mark coordinate evidence stale and re-evaluate before distance inclusion | preserve old evidence until updated mapping is verified |

The current `geocode_status`, provider/source, precision, time, and error metadata fields are the right conceptual record of these outcomes. The current implementation does not yet automate this workflow, enqueue retries, or offer repair UI.

## 10. Public location presentation

Current browse presentation already distinguishes:

- on-site: city/state where available;
- hybrid: `Hybrid · City, ST` (compact browse display may show city with full title);
- remote: `Remote`;
- multiple: `Multiple locations` with state where available; and
- confidential: `Location disclosed after inquiry`.

This presentation should remain independent of coordinate precision. Public users should see city/state and approximate distance when a radius search supplies it, not ZIP centroids, provider names, geocode status, raw coordinates, or internal repair state.

## 11. Implementation gaps

| Gap | Classification | Reason |
|---|---|---|
| Inventory-independent typed ZIP/city origin reference | V1 blocker | Current resolver averages current Jobs rows and is California-specific. |
| Authoritative local reference dataset governance | V1 blocker | Local lookup needs licensed/versioned coverage, aliases, quality, and refresh responsibility. |
| V1 coordinate derivation and precision/source recording for imported/employer jobs | V1 blocker | Current location intake does not create trustworthy operational coordinate evidence. |
| Browser-origin pagination/continuation behavior | V1 pilot blocker | Current privacy-safe URL omission leaves repeat/pagination behavior unresolved. |
| Repair/retry exception workflow for missing/stale/inconsistent coordinates | V1 pilot blocker | Metadata exists but no operational use path exists. |
| Coordinate quality acceptance verification | V1 pilot blocker | Fixture coordinates are present but all quality status is unknown. |
| Local city disambiguation/typeahead assistance | V1 launch polish | Strict ZIP or City, ST input is sufficient initially. |
| Provider-backed street geocoding / Google Places autocomplete | V1.1 | Not required for city/ZIP precision pilot. |
| Maps, commute time, route calculations, saved personal location profile | Deferred | Outside V1 Distance Search scope. |

## 12. Engineering Director decisions

1. Approve the V1 scope as US ZIP/city precision rather than rooftop/street-address precision.
2. Approve a versioned local ZIP/city reference dataset as the V1 source for typed origins and job coordinate derivation, with no Google services in V1.
3. Decide whether ZIP is mandatory for on-site/hybrid pilot jobs or whether city/state fallback is allowed as a documented lower-precision exception.
4. Approve the default that remote, multiple-location, and confidential jobs do not participate in radius results unless they carry a safe, explicit eligible location.
5. Approve the browser-origin continuation/privacy policy required for pagination and refresh without putting exact coordinates in the URL.
6. Decide whether a minimal local city/state suggestion/disambiguation aid is included in V1 launch polish or deferred to V1.1.

## 13. Preserved architecture

- Jobs, not Core Terms, owns physical location, coordinates, quality metadata, and radius search.
- Core Terms remains the reusable state/location classification source; it is not a geocoder or ZIP/city reference database.
- The existing latitude/longitude fields, metadata fields, index, local radius query, Haversine calculation, normal filter/pagination integration, and opt-in cross-state behavior remain the correct foundation.
- Public search remains local-database-only: no external provider call during query execution.
- Browser geolocation remains user-initiated, rounded, request-scoped, and absent from profiles, alerts, analytics, and shareable URLs.
- Current public location-mode presentation remains useful and must not disclose internal coordinate precision or metadata.

## 14. Documentation and implementation conflicts

`wordpress/wp-content/plugins/tnet-jobs/docs/distance-search-architecture.md` is now historical in parts: it says the document is planning-only, proposes Google Maps Geocoding as the V1 default, and lists JDIST003-JDIST006 steps as future. The current code has already implemented coordinate metadata/indexing, radius query, typed origin controls, cross-state behavior, and browser current location. No documentation was changed in this audit.

`job-location-strategy.md` similarly describes coordinates as future support, while the current schema and public radius implementation are live. The current code, not those historical planning sections, reflects implemented behavior.

## Verification record

- Confirmed JREAL001 (`3549f20`), JREAL002 (`3ed50af`), and JREAL003 (`361a113`) are present and pushed; all three audit documents exist.
- Inspected root `main` at `361a113` and Jobs plugin `main` at `9c031ad` before report creation; preserved pre-existing root untracked files.
- Inspected Jobs schema, Job Service validation, seed/CSV location handling, employer location form, completed JDIST resolver/radius controls, browser JS, query integration, pagination handling, public location rendering, scheduled events, and historical location/distance docs.
- Ran read-only coordinate completeness, geocode status, city/ZIP, cron, and source searches. No provider/API/source-site/browser permission calls were made.
- No application code, plugin code, Core Terms data, database rows, options, schema, or cron schedule changed. `git diff --check` is required before commit.
