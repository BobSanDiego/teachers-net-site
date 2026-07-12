# TA001 - Job Seeker Journey Audit

**Project:** Teachers.Net - Job Center

**Mode:** Documentation-only inspection

**Audit date:** 2026-07-11

**Contract sources:** `docs/job-center/canonical-v1-contract.md`,
`docs/job-center/employer-ux-v1.md`, and
`docs/job-center/job-center-design-system-v1.md`

**Implementation inspected:** current `wordpress/wp-content/plugins/tnet-jobs`

## Executive summary

The current teacher/job-seeker journey has a credible V1 technical foundation.
One `/jobs/` route hosts Search and Browse states over the same local Jobs data.
Public queries apply the canonical application-integrity and lifecycle predicate.
Typed location and radius search use the local location reference and stored job
coordinates rather than a live provider call. Browser current location is
opt-in and submitted at reduced precision. Job Detail routes external
applications directly to verified destinations, protects email/free-text
instructions behind login and explicit reveal, and prevents application actions
for closed, expired, or non-actionable jobs. Saved Jobs and Job Alerts enforce
authenticated ownership and nonce-protected mutations.

The journey is not yet coherent at the product or design-system level. The
implementation exposes two layers of search complexity: a Search/Browse plus
Basic/Advanced model in the landing panel, and a second legacy refinement/
toolbar model still present in the code. The active Advanced panels display
several disabled future controls as if they are part of the available search
system. The right rail duplicates Advanced Filters but its **Open Advanced
Filters** anchor does not activate the disclosure. Search and Browse share the
query engine, but mode/depth are not consistently preserved across pagination.
Distance is not represented in active-filter chips or Job Alert criteria.

The current result row does not conform to the approved canonical listing. It
has a narrative area, a metadata area, and a separate heart column rather than
two broad zones. Taxonomy chips correctly remain grouped with the narrative,
but concise summaries are omitted. Employment type is duplicated in a taxonomy
chip and the secondary metadata block; posting age occupies the secondary
block; distance is not rendered; and the Save heart is a third grid column.
This is the clearest implementation difference from the approved design target.

The approved shell is substantially present: the app is constrained to a
centered 1200px canvas, the navbar is white, the footer is dark Teachers.Net
blue, the desktop rail is 300px, and both standard ad dimensions exist. Shell
continuity is weakened by duplicate navigation inside Saved Jobs and Alerts,
an inactive Notifications icon, nonfunctional shortcut destinations such as
Recent Searches, and differing header/nav structures between public and
employer routes. Exact navbar/footer artifact details remain unresolved under
the design-system reference-control rules, so this audit does not promote an
Unclassified screenshot as authority.

Job Detail is generally contract-aligned but repeats Save/Application controls
and core job facts between the hero, main content, and sidebar. Saved Jobs is a
useful basic collection but is capped at 100 with no pagination and reuses
employer-oriented status language. Job Alerts can create, pause, resume,
unsubscribe, and delete alerts, but the normal **Create Job Alert** entry points
do not carry the active search context; location text, distance, salary, and
browser-origin criteria are not part of the active alert handoff.

The priorities are therefore coherence and truth, not a redesign: complete the
progressive search contract with only supported controls; bring listings to the
approved two-zone composition; preserve search state through pagination,
login, saved, and alert transitions; make navigation destinations real; and
eliminate duplicate/legacy interaction paths before visual implementation
tickets treat the current screen as authority.

## Audit basis and approved-target control

This audit compares current behavior and presentation against:

- the Canonical V1 Contract for visibility, applications, location, and one-job
  identity;
- Employer UX V1 where seeker and employer truth meet, including Published
  versus Live and metric meaning;
- Job Center Design System v1 for the approved progressive-search rule,
  canonical listing composition, constrained shell, ad placements, navigation,
  accessibility, and reference control;
- the current implementation map for implemented prerequisites and known
  boundaries; and
- current PHP, JavaScript, CSS, services, and repositories as evidence.

The comparison requested by TA001 uses these approved design-system targets:

1. **Progressive search model:** one Job Finder; Search and Browse states; Basic
   first; supported advanced controls disclosed in one connected panel; shared
   results, chips, sort, and pagination.
2. **Canonical listing:** professional two-zone directory entry; taxonomy stays
   in the left narrative block; salary, distance, and Save stay together in the
   compact secondary block; no table-like metadata columns.
3. **Approved shell:** centered 1200px maximum canvas; constrained white navbar;
   constrained dark-blue footer; intentional 300px rail and reserved ad slots.

No raster artifact is treated as Approved. D001 records the available finder
images as Superseded or Unclassified. Current implementation is evidence, not
visual authority.

## Workflow inventory

| Workflow | Current behavior | Contract/design compliance | Primary friction or difference | Implementation opportunity |
|---|---|---|---|---|
| Job Center landing | `/jobs/` renders breadcrumb, hero heading, Search/Browse panel, results, right rail, ads, and footer in one app canvas. Default results show latest eligible jobs. | **Mostly compliant.** One Finder and constrained shell are correct. | “Landing” and results are one long operational page, but several rail shortcuts and Advanced controls imply unavailable behavior. Active-job breadcrumb count is status-based and may not represent the full public-eligibility predicate. | Preserve the one-route model; make every visible control and count truthful to the canonical public query. |
| Search mode | Keyword plus Location, Grade, and Subject; Search/Basic and Search/Advanced states use one form. | **Partially compliant.** Progressive disclosure exists. | Search Advanced shows disabled Employer Type, Work Arrangement, Posted Within, Job Type, Experience Level, extra keyword, and Remote controls. Keyword appears again as “Keyword optional.” | Keep one connected disclosure and expose only supported filters; one concept should have one input. |
| Browse mode | Location, Grade, Subject, optional keyword, salary, and Browse action; Advanced adds more controls and distance. | **Partially compliant.** Shared engine and structured browse intent are correct. | Salary appears in Basic Browse and again as disabled Salary Range in Advanced. Search/Browse mode may be lost on pagination without distance. | Preserve mode/depth explicitly with shared state and remove duplicate/placeholder inputs from the active experience. |
| Filters and chips | Core Terms filters, employment type, keyword, and salary query support exist. Active chips represent keyword, employment type, and Core Terms selections. | **Partial.** Supported filters query the same eligible corpus. | Distance origin/radius, cross-state, salary, and mode/depth are not represented in chips. Clear All resets the whole search with no scoped explanation. A separate results toolbar contains disabled placeholder sort/salary controls. | Define one active-filter model used by UI, URL, pagination, alerts, and clear behavior. |
| Typed location | Advanced panels accept ZIP or City, State and a supported distance. Local reference service resolves origin; query uses stored coordinates. | **Compliant foundation.** Local-only search and no live provider query match the contract. | Error depends on paired origin/distance; Basic location is Core Terms classification while Advanced origin is typed geography, but their relationship is not explained. Distance eligibility and omitted jobs are not summarized. | Use one truthful location vocabulary and explain classification versus radius origin without merging their data ownership. |
| Browser location | **Use my current location** requests geolocation, defaults empty distance to 25 miles, rounds coordinates to four decimals, submits by POST, and replaces the visible URL with a continuation token URL. | **Mostly compliant.** Opt-in, request-scoped intent and fallback messages are present. | Button mutates to Getting location; denied/timeout messages are good, but cross-state and distance consequence precede no explicit privacy summary. Continuation expiration can replace results with an error. Browser origin cannot become an alert criterion. | Keep the private continuation model; make privacy, radius, cross-state, expiry, and fallback consequences explicit in the same control group. |
| Results | Eligible jobs are queried locally with lifecycle/application-integrity SQL, term filters, keyword search, radius option, total, and 10-per-page paging. | **Contract compliant at query level.** | Result heading/range terminology varies. Current listing composition differs materially from the approved target. Sort controls are presentation placeholders rather than functional sort choice. | Retain the eligible query; align result presentation and controls to one canonical state model. |
| Pagination | Browse paginator shows Previous, pages 1-5, ellipsis/last, Next; URLs preserve keyword, employment type, terms, and distance continuation. | **Partial.** Paging and core filters persist. | Finder Search/Browse mode and Basic/Advanced depth persist only when a distance request is present. A current page above 5 may not be shown in the fixed page-number window. No bottom “Showing 1–10 of N jobs” text accompanies controls. | Preserve complete Finder state and use a current-page-centered window with canonical result-range treatment. |
| Job Detail | Shows breadcrumb/back link, title, employer, facts, summary, description, requirements, classifications, How to Apply, employer summary, sidebar details/actions, and ad. Closed/expired guidance replaces apply action. | **Mostly compliant.** Application and inactive-state truth are strong. | Published storage status is displayed as a badge even though Live is the user-facing visibility concept. Facts and actions repeat across hero/sidebar/body. About Employer contains generic placeholder copy rather than useful employer content. | Use canonical status/visibility terminology and reduce duplicate presentation while preserving the protected application model. |
| External application | Valid external URL is public and opens the employer destination in a new tab with `noopener noreferrer`; copy states Teachers.Net does not process applications. | **Compliant.** | CTA is repeated in hero/sidebar and How to Apply. New-tab behavior is not visibly announced. | Keep direct routing and truth; define one primary action and accessible destination/new-context messaging. |
| Email/free-text application | Logged-out user is sent to login; logged-in user explicitly reveals protected instructions; reveal records engagement; email becomes a `mailto:` link. | **Mostly compliant.** Protected email/instructions and non-ATS truth are correct. | Copy says “confirm interest,” while the metrics model defines Interested as save or reveal. Login and reveal add steps, and the page contains repeated entry controls. | Use one protected-application entry point and terminology aligned to the canonical Interested definition. |
| Saved Jobs | Save/unsave from list/detail; logged-out save redirects to login with a return URL. Saved Jobs requires login, lists up to 100 saved records, supports Unsave, and allows detail when visible. | **Mostly compliant.** Ownership and dead-application awareness are present. | No pagination or search/filter; closed/expired state language reuses employer status helpers. Saved state may be represented by text buttons in some surfaces and a heart in others. | Use the canonical heart/state language and seeker-specific availability guidance; preserve return context. |
| Job Alerts | Logged-in users can create alerts from accepted context, list up to 100, pause/resume/delete, and unsubscribe by token. Delivery is daily. | **Partial.** Core lifecycle works. | Normal rail/header **Create Job Alert** links carry no current criteria, so the create form does not appear. Alert context excludes typed location text, distance, salary, cross-state, browser origin, and disabled future filters. There is no edit flow. | Define one alert-criteria subset and pass it from the active search; do not imply unsupported criteria are saved. |
| Login transitions | Save/apply/alerts use WordPress login URLs. List save and detail save preserve a return URL; protected apply returns to detail/application anchor. | **Mostly compliant.** WordPress authenticates and key return paths exist. | Account link routes logged-in users to Employer Portal even for job seekers. Alert creation context is not reliably preserved. There is no seeker account landing/context distinction. | Preserve intended seeker action and search state across authentication; avoid treating every logged-in user as an employer. |
| Navigation | Public shell has Jobs, Lessons, Chatboards, More, Saved heart, Notifications placeholder, and Account/Login. Saved/Alerts also render a second Jobs navigation. Rail includes shortcuts, alert, employer CTA, and ad. | **Partial against approved shell rules.** White constrained navbar and dark constrained footer are present. | Duplicate navigation appears inside Saved/Alerts. Notifications is a nonfunctional icon. Recent Searches, Browse by Subject, and Browse by Grade Level all link to the generic Finder rather than a distinct truthful destination/state. Jobs active state lacks explicit `aria-current`. | Keep one public shell navigation and make every destination/state real; unsupported utilities remain absent, not decorative. |

## Approved-target comparison

### Progressive search model

#### Current alignment

- One `/jobs/` Finder.
- Search and Browse tabs use the same form and result query.
- Basic and Advanced depth exists as an in-place disclosure.
- Active Core Terms/keyword/type filters produce removable chips.
- Typed origin, radius, cross-state, and browser location live in Advanced.
- The JavaScript sets tab semantics, `aria-selected`, `aria-expanded`, hidden
  panels, and disabled inactive controls.

#### Current differences

1. Advanced disclosure contains visible disabled future controls. Progressive
   disclosure currently reveals unavailable features rather than supported
   complexity.
2. Keyword and salary concepts are duplicated across Basic/Advanced or Search/
   Browse layouts.
3. The right-rail **Open Advanced Filters** is an anchor to the form and does not
   set Advanced depth or focus the disclosed content.
4. The results toolbar creates a second refinement model with disabled Newest
   First and Salary controls and broad links back to the form.
5. Search/Browse mode and depth are not always serialized into pagination URLs.
6. Chips do not represent the complete active query, especially typed distance.
7. Search-state handoff to Job Alerts is incomplete.
8. Unsupported Remote/Work Arrangement/Posted Within/Experience/Employer Type
   controls are visible despite not participating in the query.

**Assessment:** The implementation contains the correct structural primitive
but not yet one coherent progressive-search interaction.

### Canonical listing

#### Current alignment

- Title, employer, and location remain associated.
- Grade, subject, and employment type are rendered as grouped chips.
- Salary is visually separated as comparison metadata.
- Save uses a heart rather than a bookmark/ribbon.
- Cards/rows use restrained borders and responsive reflow.

#### Current differences

1. Renderer/CSS define three desktop columns: primary narrative, metadata, and a
   separate Save column. The target permits only a narrative zone and one
   compact secondary zone.
2. Summary is not rendered at all, although the target requires a concise
   summary in the narrative block.
3. Employment type is rendered both as a taxonomy chip and again in the
   secondary metadata block.
4. Posting age is rendered in the secondary metadata block even though the
   approved secondary composition is salary, distance, and Save.
5. Distance returned by radius search is not rendered in the listing.
6. Save is visually separated from salary/distance and shifted upward rather
   than aligned within the compact compensation block.
7. The DOM places employer/location before title and relies on CSS `order` to
   create the visual hierarchy, weakening no-CSS/assistive reading order.
8. Current CSS contains multiple generations of table-head/column and card/list
   rules, increasing the risk that legacy table-like presentation reappears at
   breakpoints.
9. Result entries are separated inside one bordered list container rather than
   consistently behaving as distinct directory cards; D001 leaves exact card
   separation unresolved, but the two-zone rule is not unresolved.

**Assessment:** Taxonomy grouping and the heart primitive are useful existing
pieces, but the active listing is not compliant with the canonical composition.

### Approved shell

#### Current alignment

- `.tnet-jobs-app-canvas` wraps all rendered public content.
- Active CSS constrains the public shell to 1200px.
- Navbar is white.
- Footer uses dark Teachers.Net blue and stays inside the canvas.
- Desktop Finder uses a main column plus 300px rail.
- Medium rectangle is rendered at 300 × 250.
- Leaderboard is reserved as 728 × 90 below results/pagination and above footer.
- Breadcrumb, main, complementary rail, pagination, and footer landmarks exist.

#### Current differences

1. Public seeker routes and employer routes use different top-bar structures;
   exact employer design is unresolved, but the product does not yet read as
   one shell.
2. Saved Jobs and Alerts add an internal Jobs navigation below the global top
   bar, duplicating primary destinations.
3. Notifications appears as a noninteractive placeholder in the approved shell
   area despite being outside V1.
4. Several footer and rail links resolve to generic or placeholder destinations.
5. The rail's Advanced Filters card duplicates the primary panel but does not
   control it.
6. The rail omits the shared-system Featured Resources slot even though a
   renderer exists; exact rail order remains unresolved, so this is a completeness
   observation rather than a visual noncompliance finding.
7. The 300 × 250 placement uses sample creative rather than the reserved neutral
   treatment; the placement is compliant, the creative is not design authority.
8. D001 records exact navbar items, footer columns/newsletter, and mobile rail/ad
   ordering as unresolved. This audit does not treat candidate-image differences
   in those areas as defects.

**Assessment:** The core canvas and advertising geometry comply. Navigation
truth and cross-route consistency remain the shell's main gaps.

## Detailed findings

### 1. One public visibility predicate is substantially implemented

Finder queries use the application-integrity service's public SQL clause plus
lifecycle constraints. Save actions reject non-public jobs. Detail availability
blocks action for closed, expired, suppressed, or invalid application paths.
This is the strongest contract alignment in the seeker journey.

The remaining risk is presentation drift: raw `published` can still appear in
detail UI even though Employer UX V1 freezes Live as the user-facing visibility
fact.

### 2. Current Search and Browse are one engine but not one state contract

The implementation correctly uses one query and one result list. UI state is
spread across hidden inputs, active controls, disabled future controls, distance
POST data, continuation IDs, chips, and pagination URL reconstruction. Because
not all of these sources serialize the same state, mode, depth, distance, and
alert transitions can diverge.

### 3. Placeholder controls create false affordances

Advanced panels show controls that are intentionally disabled with accessibility
labels indicating “coming later.” This is honest at the semantic level but still
adds visual complexity and suggests capability. The design system requires
unsupported controls to remain unresolved rather than appear implemented.

### 4. Results presentation remains legacy-table influenced

The current renderer calls the component a row, and CSS still contains table
head and independent grade/subject/salary/type/posted/action column rules from
earlier iterations. Later CSS overrides some of that presentation, but the
active structure still has three zones and duplicated metadata. The canonical
listing target deliberately rejects that model.

### 5. Application truth is strong, but action hierarchy is duplicated

External application routing and protected instruction behavior are clear and
contract-aligned. Save and Apply controls appear in the detail hero and again in
the sidebar/action section, while How to Apply may repeat the application CTA a
third time. Repetition weakens which action is primary and can produce different
labels for the same next step.

### 6. Saved Jobs is functional but not a complete collection experience

Saved Jobs persists per user, supports Unsave, retains closed jobs, and uses
availability to determine detail access. It has no paging beyond 100 records,
no ordering/filter control, and no explicit seeker vocabulary for why a saved
job is unavailable. V1 may not need advanced organization, but lifecycle
clarity and canonical heart state are required.

### 7. Job Alerts is operationally real but disconnected from the Finder

Alert ownership, status changes, deletion, daily frequency, and unsubscribe are
implemented. The alert creation form appears only when the Alerts URL contains
recognized criteria. Finder calls to Create Job Alert do not pass those
criteria, and alert context intentionally/accidentally omits several active
search dimensions. The feature exists but the primary journey into it is
incomplete.

### 8. Login transitions are action-specific rather than account-coherent

Save and protected Apply preserve useful return destinations. Alert and account
transitions are less coherent, and the logged-in top-bar account destination is
always Employer Portal. A teacher can be logged in without employer authority;
the navigation should not imply that account identity equals employer context.

### 9. Navigation includes unavailable destinations

Notifications is a placeholder. Recent Searches has no inspected recent-search
system. Browse-by shortcuts route to the unfiltered Finder. Footer links reuse
generic destinations for multiple labels. These are duplicate/false paths, not
merely missing polish.

### 10. Browser location follows the privacy direction with continuity limits

The browser request is explicit, low-accuracy, rounded, and not placed directly
in a shareable URL. A server-side continuation identifier supports pagination
and raw coordinates are removed from visible history. The user still needs a
clear explanation of temporary scope, continuation expiry, cross-state behavior,
and why browser-origin criteria cannot become an alert.

## Product gaps

1. One complete Finder-state contract shared by controls, URL, chips,
   pagination, login return, alerts, and browser-origin continuation.
2. Functional sort choice or removal of sort affordances that do not sort.
3. Clear supported-filter boundary; unavailable future controls should not
   appear as part of Advanced.
4. Canonical two-zone listing with summaries, salary, truthful distance, and
   one Save placement.
5. Current-search-to-alert handoff with an explicitly supported alert-criteria
   subset.
6. Seeker account destination/context distinct from Employer Portal.
7. Truthful Recent Searches and browse-by navigation, or absence until those
   destinations exist.
8. Consistent Saved state across list, detail, header, and Saved Jobs.
9. Saved Jobs paging/retention policy beyond 100 records.
10. Seeker-facing lifecycle language for saved closed/expired jobs.
11. One primary application action per detail context.
12. Job Detail design approval; D001 records exact composition as unresolved.
13. Saved Jobs and Job Alerts approved visual references.
14. Responsive approval for Finder, listings, detail, rail, ads, saved, alerts,
    pagination, and footer.

## Duplicate interactions and information

- Search/Browse plus Basic/Advanced is valid progressive disclosure, but the
  separate results-refinement and placeholder toolbar code represents a second
  interaction vocabulary.
- Keyword appears as Search Terms and again as Keyword optional.
- Salary appears in Basic Browse and disabled Advanced Salary Range.
- Advanced Filters exists in the main panel and as a rail card/link.
- Application actions appear in the detail hero, action sidebar, and How to
  Apply section.
- Save control appears in the detail hero and sidebar, plus a separate general
  engagement block in supporting code.
- Employer, location, employment type, and salary appear in detail hero facts
  and the sidebar details card.
- Public top navigation is followed by a second Jobs nav on Saved Jobs/Alerts.
- Employment type appears twice within each active result entry.
- Active filters and toolbar controls describe overlapping refinement paths but
  do not share full state.

## Terminology audit

| Current term | Finding |
|---|---|
| Search Jobs / Browse Jobs | Clear and compatible with one Finder. |
| Basic Search / Advanced Search; Basic Browse / Advanced Browse | Understandable, but current disclosure reveals unsupported fields. |
| Additional Filters / Advanced Filters | Two names for the same conceptual depth. Freeze one vocabulary when the panel design is approved. |
| Search / Browse Jobs / Update Search | Similar submit actions use three label patterns. Context is understandable but not unified. |
| Newest First | Presented as disabled placeholder; implies a sort state without a functional choice. |
| Active filters | Correct, but incomplete for distance/salary/mode state. |
| Posted | Reasonable listing metadata, but not part of the approved compact secondary block. |
| Published status badge | Storage term shown on Job Detail; seeker-facing visibility term should be Live when true. |
| Log in to Apply / Log in to view instructions | Both can lead to protected instructions; action wording depends on placement. |
| Confirm interest | Reveal copy does not fully match the union Interested metric definition. |
| Unsave Job / Remove saved job | State meaning is clear, but label vocabulary varies by surface. |
| Alert Active / Paused | Clear. |
| Recent Searches | Unsupported destination in current inspected implementation. |
| Notifications | Unsupported placeholder in the public navbar. |

## Navigation and information hierarchy observations

- The Finder hero establishes purpose clearly and places search before results.
- Breadcrumb and result count are present, but the results group uses a Browse
  Jobs eyebrow even when the title is Search Results.
- The right rail gives equal structural weight to useful Saved/Alerts links and
  nonfunctional Recent Searches/browse shortcuts.
- Employer **Post a Free Job** is appropriate secondary conversion but must not
  dominate the teacher task.
- Listing title should lead each entry in DOM and visual order. Current DOM leads
  with employer/location and depends on CSS ordering.
- Listing summary absence reduces the teacher's ability to compare roles without
  opening every detail.
- Job Detail title and key facts are strong, but repeated facts/actions create
  competing focal points.
- Saved Jobs and Alerts use clear page titles but lack breadcrumb continuity and
  add a duplicate local nav.
- Empty results provides a useful broader-search recovery but no direct way to
  remove one active chip from the empty state itself.

## UX observations

- Search/Browse tabs and Basic/Advanced disclosure are learnable and should be
  preserved as the current interaction primitive.
- Disabled future controls increase scan cost and make Advanced look unfinished.
- Basic versus Advanced is stateful, but that state can disappear when moving
  through results.
- Typed origin and Core Terms Location are two different location concepts with
  similar labels. Users need to understand classification filtering versus
  distance origin.
- Browser current location has good denial/error fallback text.
- Cross-state is explicit, which protects the canonical location rule.
- Fixed five-page pagination is simple on early pages but loses orientation on
  later pages.
- Small heart presentation is appropriate, but visual/state consistency and hit
  target need QA against D001.
- Job Alert daily frequency is transparent after creation but not part of the
  initial Finder call to action.
- Delete Alert and Unsave are immediate actions without an inspected undo model;
  confirmation expectations remain a product/interaction decision.

## Engineering observations

- `browse_jobs_query()` correctly composes the application-integrity public SQL
  clause with filters, lifecycle, radius, total count, and paging.
- `render_jobs_search_results_page()` and its refinement form appear to be
  legacy/unreached alongside the active integrated landing/results path. Keeping
  two render systems increases terminology and CSS drift.
- The stylesheet contains multiple generations of listing/table/card rules with
  later overrides. This makes the active canonical component difficult to
  reason about and breakpoint regressions likely.
- Several controls are intentionally rendered disabled with
  `data-tnet-jobs-disabled-control`; they should not be mistaken for implemented
  filters by QA or future tickets.
- `pagination_url()` serializes finder mode/depth only when distance state is
  present. Non-distance Search/Browse continuity is therefore incomplete.
- Filter-chip construction excludes salary and distance state.
- Alert-context construction includes keyword, employment type, and Core Terms
  but does not capture typed origin, radius, cross-state, browser continuation,
  or active salary. `location_text` is initialized but not populated from the
  current Finder request.
- The right-rail alert links use the Alerts route without the active context;
  `render_job_alert_create_from_context()` therefore cannot appear through the
  primary Finder CTA.
- Browser-origin coordinates are submitted at four decimals and replaced in
  visible history by a continuation URL. The continuation expiry path is an
  explicit error rather than a silent fallback, which is preferable but needs
  journey QA.
- Public top bar renders Notifications as a role-image placeholder rather than
  a link or button. It should not be interpreted as implemented behavior.
- Public Save/Apply mutations enforce login, nonce, job ID, and visibility; this
  security boundary should remain centralized.
- Saved Jobs and Alerts both cap list queries at 100 with no paging.
- Detail uses the availability model for CTA behavior, but a raw status badge
  and some helper text can still diverge from canonical Live terminology.
- Social and footer links include generic/home destinations; navigation QA must
  verify destination truth, not only HTTP success.

## Missing capabilities by V1 relevance

### V1 journey gaps

- current-search Job Alert creation;
- complete state persistence through pagination and login;
- canonical listing summary and radius distance presentation;
- truthful, functional sort treatment;
- supported-only Advanced filtering;
- real Recent Searches/browse shortcuts or their removal from active navigation;
- consistent seeker account destination;
- approved, coherent Saved Jobs and Alerts presentation;
- end-to-end browser-origin continuation acceptance;
- complete responsive acceptance against the approved shell/listing rules.

### Explicitly not required by this audit

- maps or commute-time routing;
- internal applications, ATS, resumes, or messaging;
- anonymous application tracking;
- notification center/bell behavior;
- AI recommendations;
- advanced alert preferences;
- unapproved provider-backed autocomplete;
- redesign of unresolved Job Detail or account screens.

## Prioritized recommendations

### P0 - Contract and journey truth

1. **Establish one serialized Finder state.** Search/Browse, Basic/Advanced,
   supported filters, typed origin, radius, cross-state, page, chips, and login
   return must describe the same query.
2. **Connect Create Job Alert to the active supported search criteria.** Clearly
   exclude browser location or any criterion not allowed in alerts.
3. **Remove false affordances from the active journey.** Disabled future filters,
   Notifications, Recent Searches, and generic browse shortcuts must not appear
   functional before their product behavior exists.
4. **Align all seeker-visible state to canonical eligibility.** Finder, detail,
   saved jobs, alerts, application actions, and status copy must agree on Live,
   Closed, Expired, and unavailable conditions.
5. **Verify browser-location privacy and continuation end to end.** Cover consent,
   denied/timeout fallback, rounding, URL replacement, paging, refresh, expiry,
   and no persistence into alerts/profiles/analytics/shareable URLs.

### P1 - Approved design-target alignment

6. **Conform results to the canonical two-zone listing.** Narrative contains
   title, employer/location, taxonomy chips, and summary; secondary contains
   salary, truthful distance, and one Save heart.
7. Eliminate duplicate employment-type/posted columns and ensure title leads DOM
   and visual reading order.
8. Preserve the approved 1200px shell while consolidating duplicate seeker
   navigation and making every destination truthful.
9. Use one progressive-search vocabulary and one functional sort/refine model.
10. Add canonical top and bottom result-count/range treatment and page-centered
    pagination state.
11. Use one primary application action on Job Detail while retaining the clear
    How to Apply explanation and protected reveal.
12. Standardize Save heart state, accessible label, placement, and return
    behavior across list, detail, header, and Saved Jobs.

### P2 - Pilot-informed refinement

13. Determine Saved Jobs paging/filter needs from pilot use beyond the current
    100-record cap.
14. Determine whether alert editing is needed after create/pause/resume/delete
    is accepted.
15. Approve Job Detail, Saved Jobs, Alerts, and responsive Atlas references
    before visual implementation tickets expand their unresolved design.
16. Evaluate richer sort options, autocomplete, and recent searches only after
    the supported V1 search and alert journey is accepted.

## Recommended acceptance sequence

1. Open `/jobs/` logged out and verify approved shell, public count, default
   eligible results, rail, ads, and navigation truth.
2. Exercise Basic Search and Basic Browse against the same result engine.
3. Open/close Advanced in each mode and verify only supported controls appear,
   focus/keyboard behavior is correct, and state persists.
4. Apply keyword, Core Terms, employment type, salary, typed origin, radius, and
   cross-state criteria in their supported combinations.
5. Verify chips, clear scope, count, sort, listing content, and empty state agree
   with the query.
6. Page forward/back from Search and Browse, Basic and Advanced, including a
   current page above five; verify all state and range text.
7. Compare every result against the canonical listing composition at desktop,
   tablet, narrow, mobile, zoom, and keyboard focus.
8. Use browser current location through success, denial, timeout, paging,
   refresh, continuation expiry, and URL-sharing checks.
9. Open an eligible Job Detail, return to the exact Finder state, Save logged
   out/in, and verify saved state consistency.
10. Exercise external URL, protected email, and protected free-text application
    flows; confirm no internal-application implication.
11. Verify closed, expired, suppressed, invalid-application, and non-public jobs
    agree across Finder, Detail, Saved Jobs, alerts, and actions.
12. Create an alert from the active search, log in if needed, and confirm exact
    supported criteria, daily frequency, pause, resume, unsubscribe, and delete.
13. Verify Saved Jobs lifecycle guidance, Unsave, cap/paging decision, and dead
    destination handling.
14. Verify navbar, breadcrumb, right rail, footer, Saved Jobs, Alerts, and all
    login returns use truthful destinations and no duplicate/placeholder UI.
15. Run accessibility acceptance for landmarks, headings, tabs/disclosures,
    labels/errors, live regions, focus-visible, targets, contrast, zoom, reflow,
    reduced motion, and keyboard order.

## Verification record

- Documentation-only scope: this audit file only.
- Jobs plugin application code: unchanged by TA001.
- Runtime mutation: not performed.
- No mockup or Unclassified visual artifact was promoted to Approved.
- Final repository and whitespace verification are reported with the commit.
