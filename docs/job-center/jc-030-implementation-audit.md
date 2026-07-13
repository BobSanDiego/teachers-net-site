# IMPLEMENTATION-AUDIT001 — JC-030 Job Detail Convergence Audit

**Date:** 2026-07-13

**Scope:** Engineering audit only. No implementation authority is created by
this document.

## Executive finding

The existing Job Detail is close in data capability but materially divergent in
composition. The smallest convergence does not require schema, repository,
route, lifecycle, application-integrity, engagement, or Core Terms changes. It
is primarily a bounded rewrite of `TNet_Jobs_Public::render_job_detail()` and
its detail-specific CSS, plus small helpers for approved employer, related-job,
claim, leaderboard, and action presentation.

Current reusable behavior is strong: slug routing, canonical record lookup,
historical-detail eligibility, external/protected application decisions,
save/unsave, Core Terms resolution, metadata/JSON-LD, the 1200px canvas,
shared footer, and the 300 × 250 ad helper. Current output diverges because it
renders a prominent internal status, a bordered main card, three Apply controls,
two Save controls, no chips, no employer identity card/link, no related-job
panel, no claim link, and no leaderboard.

One approved visible element is not implementation-deterministic: **Share this
job** has no product-level behavior, current handler, or JavaScript. Because
this audit may not invent behavior or expand product scope, implementation of
that control must wait for a bounded behavior decision. All other approved
desktop elements can be produced from current fields, routes, and helpers.

## 1. Current implementation architecture

1. `TNet_Jobs_Public::init()` registers WordPress rewrite/query hooks. The
   `/jobs/{slug}/` rule selects the `detail` route
   (`public/class-tnet-jobs-public.php:17-20,34-44`).
2. `template_redirect` dispatches to `render_job_detail_page()` and exits before
   the theme template hierarchy is used (`public/class-tnet-jobs-public.php:55-85`).
3. `public_detail_job_by_slug()` sanitizes the slug, calls
   `TNet_Jobs_Job_Service::get_job_by_slug()`, and gates the record through
   `TNet_Jobs_Application_Integrity_Service` (`public/class-tnet-jobs-public.php:6824-6853`).
4. `render_job_detail_page()` handles engagement POSTs, records authenticated
   views, enqueues the one public stylesheet/script, installs title/meta/JSON-LD
   hooks, opens the plugin-owned shell, calls the detail renderer, and closes
   the shell (`public/class-tnet-jobs-public.php:6733-6755`).
5. `render_job_detail()` retrieves employer data and Core Terms separately,
   derives location/date/salary/availability/engagement labels, and emits all
   page HTML inline (`public/class-tnet-jobs-public.php:7171-7355`). There is no
   Job Detail template or partial.
6. `public/css/tnet-jobs-public.css` styles both the shared shell and the detail
   page. The desktop media query establishes a 300px sidebar
   (`public/css/tnet-jobs-public.css:5336-5349`).
7. `public/js/tnet-jobs-public.js` is globally enqueued. It supports the shared
   More menu and other Finder/employer interactions, but contains no
   detail-specific Apply, Save, Share, or related-job behavior.

The live audited route returned HTTP 200 and matched the staged current-
implementation evidence. No theme template participates.

## 2. Template inventory

| Type | File / symbol | Current responsibility | Convergence relevance |
|---|---|---|---|
| Bootstrap | `wordpress/wp-content/plugins/tnet-jobs/tnet-jobs.php` | Loads `TNet_Jobs_Public` and registers activation hooks | No change expected |
| Route/controller/view | `public/class-tnet-jobs-public.php` | Rewrite dispatch, shell, head hooks, Job Detail HTML, helpers, actions and ads | Primary PHP change surface |
| Theme template | None | Plugin writes the complete document during `template_redirect` | No template override exists |
| Partials | None for Job Detail | All detail markup is inline | Do not create a template system merely for convergence |
| Shared header | `render_app_canvas_header()`, `render_browse_app_top_bar()` | Document and public Jobs navbar | Reuse shell wrapper; detail navbar needs approved labels/composition without drifting other routes |
| Shared footer | `render_app_footer()`, `render_app_canvas_footer()` | Six-column constrained footer | Reuse; visual token/content alignment only |
| Detail renderer | `render_job_detail()` | Breadcrumb, hero, narrative, classification, Apply, employer, sidebar and ad | Major bounded restructuring |
| Detail helpers | `detail_location_*`, `render_detail_fact()`, `render_detail_meta_item()`, `render_detail_classification()`, `render_detail_icon()` | Formatting and accessible visible facts | Reusable with small presentation changes |
| Application helpers | `render_detail_apply_control()`, `render_detail_apply_section()`, `render_apply_instructions()` | External link and protected reveal states | Behavior reusable; placement/count must change |
| Engagement helpers | `maybe_handle_job_engagement()`, `render_detail_save_control()`, `render_job_engagement_form()` | Save/unsave/reveal POSTs, nonce and login transitions | Reuse; render Save once |
| Employer helper | `employer_details()`, `employer_name()` | Loads canonical employer/name | Extend presentation to supported website/location; do not invent biography |
| Ad helpers | `render_medium_rectangle_ad()`, `render_jobs_leaderboard_ad()` | Existing 300 × 250 creative and 728 × 90 reservation | Reuse both; leaderboard is not currently called by detail |
| Job service | `includes/services/class-tnet-jobs-job-service.php` | Canonical job lookup and validation | No change expected |
| Job repository | `includes/repositories/class-tnet-jobs-job-repository.php` | `SELECT *` by slug/ID; canonical field mapping | No change expected |
| Availability service | `includes/services/class-tnet-jobs-application-integrity-service.php` | One visibility/actionability decision for detail and structured data | Reuse unchanged |
| Employer service/repository | `class-tnet-jobs-employer-service.php`, `class-tnet-jobs-employer-repository.php` | Loads employer name, website, description and location fields | Reuse existing public-safe values |
| Term service/repository | `class-tnet-jobs-term-service.php`, `class-tnet-jobs-term-repository.php` | Job-to-Core-Term assignments | Reuse unchanged |
| Core Terms adapter | `classification_term_records()` plus `CFM::get_terms('teachers-net')` | Resolves assigned labels and short labels | Reuse; chips need a new detail presentation helper |
| Engagement service/repository | `class-tnet-jobs-engagement-service.php`, `class-tnet-jobs-engagement-repository.php` | View, Save and instruction-reveal state | Reuse unchanged |
| Stylesheet | `public/css/tnet-jobs-public.css` | Shared tokens/shell plus detail CSS at approximately lines 2530-3120 | Primary visual change surface |
| Script | `public/js/tnet-jobs-public.js` | Shared menus and other public interactions | No detail behavior today; Share is unresolved |
| Structured data | `add_job_detail_head_hooks()`, `jobposting_json_ld()` | Title, meta description and visible-live `JobPosting` JSON-LD | Preserve and regression-test |
| Existing browser test | `tests/browser/jobs-shell.spec.cjs` | HTTP success, no overflow, <=1200px shell for browse/alerts | Reusable helper, but no Job Detail coverage |
| PHP/unit tests | None found for Job Detail renderer | No direct regression coverage | Add focused coverage during implementation |

## 3. Visual comparison

Status vocabulary: **Matches**, **Minor divergence**, **Major divergence**,
**Missing**, **Extra**.

| Approved visible section | Status | Current implementation difference | Minimum convergence |
|---|---|---|---|
| 1200px constrained canvas | Matches | Current shell variable and browser test constrain public containers | Preserve |
| Navbar | Major divergence | Current detail uses Teachers.Net Job Center plus Jobs/Lessons/Chatboards/More, heart, bell and Log In; approved uses Job Center/Find Jobs/Post Jobs/Teacher Resources and Sign In/Create Account | Add approved detail-shell variant through the existing shared header, without changing other approved routes unintentionally |
| Breadcrumb | Major divergence | Current: Home > Jobs > Garden Grove, CA > title plus separate Back link. Approved: Home > Jobs (205) > CA (42) > Garden Grove (8) > title, no Back link | Build count-aware hierarchy from existing query/term data; remove standalone Back link for this approved state |
| Hero title/employer | Minor divergence | Values match, but current lives inside bordered main card and wraps title earlier | Restructure hero across main/rail boundary and match typography/spacing |
| Prominent Published badge | Extra | Current prints raw status | Remove ordinary published badge; preserve closed/expired state notice |
| Hero decision facts | Minor divergence | Current values/icons exist; approved uses one horizontal row | Reflow existing facts and keep optional omission |
| Core Terms chips | Missing | Current terms appear only in lower Classification section | Add chip presentation from existing assigned labels plus supported work/type facts |
| Posted/updated row | Minor divergence | Current facts exist in hero; approved isolates them at lower prominence | Reposition/style existing labels |
| Main/rail top alignment | Major divergence | Current rail starts beside bordered main card and hero actions remain inside main card | Make action rail start at approved hero alignment; remove outer main card treatment |
| Top action panel | Major divergence | Current hero has Save+Apply and sidebar repeats both; approved rail shows Apply, Save and Share rows | Retain one rail Save; retain approved rail Apply entry point; remove hero actions; Share remains behavior-blocked |
| Job Summary | Minor divergence | Content and conditional rendering match; card/border/spacing differ | CSS/section structure only |
| Job Description | Minor divergence | Content matches | CSS/section structure only |
| Qualifications | Minor divergence | Current heading says Requirements / Qualifications and uses bullets; approved says Qualifications with circled checks | Adjust heading and list visual; preserve supplied content |
| Classification section | Extra | Approved exposes classifications as chips in hero, not a separate lower section | Remove separate visible section after chips are available; retain term data |
| How to Apply panel | Major divergence | Current content is plain and contains a third Apply control overall; approved has a styled expectations panel with one lower CTA | Restyle current application section and eliminate the extra hero Apply while preserving method states |
| Employer-claim context | Missing | Existing claim route is not linked from detail | Add conditional link to `employer_claim_url($employer_id)` |
| Job Details rail card | Minor divergence | Current has employer/location/type/salary; approved has location/work/type/compensation/posted/updated and no employer row | Recompose from existing facts |
| Employer rail card | Missing | Current generic employer block is in main column and ignores website | Move canonical identity to rail; show supported website and location only |
| Explore more jobs card | Missing | No detail renderer exists | Generate existing Finder links from grade, subject, city and state inputs; no new search behavior |
| 300 × 250 ad | Matches | Correct helper, size and right-rail placement; current sample creative differs from neutral approved reservation | Preserve helper/dimensions; presentation choice follows approved raster |
| 728 × 90 leaderboard | Missing | Helper exists but detail never calls it | Call existing helper after primary detail content and before footer |
| Footer | Minor divergence | Shared constrained footer is structurally close; typography/logo/content/year differ from raster | Reuse footer and tune only approved shared tokens/content if changes do not drift other approved screens |
| Continuous flow/no tabs | Matches | Current has no tabs, accordions or alternate modes | Preserve |
| Closed/expired notice | Not visible in approved live example | Current supports retained historical detail correctly | Preserve as governed state; do not force live-page styling onto it |

## 4. Data comparison

| Visible field/action | Existing source | Existing renderer | Approved usage | Implementation change required |
|---|---|---|---|---|
| Title | `jobs.title` | `render_job_detail()` hero | Hero and breadcrumb | Presentation only |
| Employer name | `jobs.employer_id` -> employer `name` | Hero, sidebar details, generic main block | Hero plus concise employer rail card | Remove duplicate/generic uses; keep two purposeful identity contexts |
| Location | Job city/postal/country + Core Terms state | `detail_location_lines()` | Hero and Job Details | Reuse; avoid additional duplicate Classification block |
| Work arrangement | `jobs.location_mode` | Hero only | Hero and Job Details; Hybrid chip | Reuse label in rail and chip helper |
| Employment type | `jobs.employment_type` | Hero and Job Details | Hero, chip and Job Details | Reuse; presentation only |
| Compensation | Salary type/min/max/currency | `salary_display()` | Hero and Job Details | Reuse; label as Compensation in rail |
| Grade | Core Terms `grade_level` assignment | Lower Classification | Hero chip and related link | Reuse term record/ID; add chip/link rendering |
| Subjects | Core Terms `subject_area` assignments | Lower Classification | Hero chips and related links | Reuse; preserve approved order/labels |
| Posted | `jobs.published_at` | Hero | Low-prominence hero row and Job Details | Reuse in both approved contexts |
| Last updated | `jobs.updated_at` if later than publish | `should_show_last_updated()` | Low-prominence hero row and Job Details | Reuse conditional; do not invent when absent |
| Summary | `jobs.summary` | Conditional Job Summary | Same | CSS only; preserve omission when empty |
| Description | `jobs.description` | Conditional Job Description | Same | CSS only |
| Qualifications | `jobs.requirements_qualifications` | Conditional list/HTML renderer | Qualifications list | Rename visible heading/style only |
| Apply destination | Integrity service application decision | Hero, sidebar and How to Apply | Top rail entry and lower panel use the same truthful path | Remove one current duplicate; centralize shared URL/state output so approved entries cannot disagree |
| Application expectations | Integrity method/protection + governed copy | How to Apply copy | Styled lower panel and top-row departure cue | Reuse facts; revise composition/copy only within approved truth |
| Save state | Engagement `saved_at`; WordPress login | Hero and sidebar | One rail row | Render once; preserve POST/login behavior |
| Share | None | None | Rail row: “Copy or send this listing.” | **Blocked:** behavior is not governed; do not invent Web Share/clipboard behavior in convergence code |
| Employer initials | Employer name | None | “OC” identity mark | Derive presentation-only initials from canonical name |
| Employer location | Job city/state is available; employer record also has location fields | Not used in employer card | Garden Grove, California | Use an explicitly selected supported source; job location is sufficient for shown record, but do not mislabel it as employer headquarters generally |
| Employer website | Employer `website_url` | Not rendered | External Employer website link | Add guarded external link using existing field |
| Related Grade link | Grade term ID | No detail link | More Grade 5 jobs | Reuse Finder `job_filter[grade_level][]` query |
| Related Subject links | Subject term IDs | No detail link | Mathematics and Science links | Reuse Finder term filters |
| Related city link | `jobs.city` plus current search text/filter model | No detail link | More jobs in Garden Grove | Use existing supported location query; verify exact semantics before label |
| Related state link | Location term ID | Breadcrumb helper already creates one filter URL | More teaching jobs in California | Reuse term-filter URL |
| Claim link | Employer ID + existing claim route | Not rendered | Learn about employer claims | Link existing `employer_claim_url($employer_id)` only |
| 300 × 250 | Existing image asset/helper | `render_medium_rectangle_ad('detail')` | Right rail | Preserve dimensions; approved neutral visual may require CSS rather than data change |
| 728 × 90 | Existing helper | Not called on detail | Below main content | Call `render_jobs_leaderboard_ad('detail')` |
| Breadcrumb counts | Finder totals/term result counts, not preassembled for detail | Only static Jobs plus location/title | Jobs (205), CA (42), Garden Grove (8) | Add read-only count assembly using existing public-query rules; highest data-query addition, no schema change |
| JSON-LD | Canonical job/employer/location/dates | `jobposting_json_ld()` | Not visible; must remain truthful | Preserve; add regression checks, no visual-driven rewrite |

## 5. Component reuse

### Reuse unchanged

- route/query-variable registration and detail dispatch;
- Job Service and Job Repository slug lookup;
- Application Integrity Service and historical-detail eligibility;
- Engagement Service/Repository, nonces and login redirects;
- employer and term repositories/services;
- meta-description and `JobPosting` JSON-LD generation;
- location, salary, date, label and SVG-icon helpers;
- shared 1200px canvas wrapper;
- 300 × 250 helper and asset;
- 728 × 90 helper;
- conditional summary/description/requirements handling.

### Reuse with bounded extension

- shared navbar renderer: add an approved detail context rather than duplicating
  a second full shell;
- breadcrumb location term records: extend to approved hierarchy/counts;
- Core Terms record lookup: add chips and related-query links;
- application helper: expose the same decision to two approved placements while
  avoiding a third control;
- employer helper: render name, initials, supported location and guarded
  `website_url`;
- footer renderer/tokens: visually align without forking the footer;
- existing card/button/icon CSS tokens: compose the approved rail and Apply
  panel rather than creating a parallel design system.

### Do not reuse

- raw status badge for an ordinary published job;
- generic “Employer information is provided...” copy;
- duplicate hero action group;
- duplicate sidebar Job Actions composition;
- standalone lower Classification block after hero chips exist.

## 6. Required implementation work

| Priority | Component | Files | Estimated complexity | Reason | Dependencies |
|---|---|---|---|---|---|
| P0 | Detail structure and visible-field assembly | `public/class-tnet-jobs-public.php` | Medium | Establish approved hero/main/rail/leaderboard order and remove extra status/actions/content | Existing data helpers; approved raster |
| P0 | Application and Save placement | `public/class-tnet-jobs-public.php` | Medium | Reduce current three Apply/two Save controls to approved two Apply/one Save while preserving every method/auth/state | Application Integrity and Engagement services |
| P0 | Approved detail styling | `public/css/tnet-jobs-public.css` | Medium | Current bordered main card, hierarchy, spacing, chips, action rows and panels materially diverge | Final PHP class structure |
| P1 | Detail navbar/shell context | `public/class-tnet-jobs-public.php`, `public/css/tnet-jobs-public.css` | Medium | Approved navbar differs from current shared public shell | Must avoid visual regression to JC-010/011/014/015 routes |
| P1 | Breadcrumb counts/hierarchy | `public/class-tnet-jobs-public.php`; existing repository/query helpers if needed | Medium | Approved breadcrumb includes scoped counts not currently assembled | Existing public visibility/filter SQL; no new schema |
| P1 | Core Terms chips and related links | `public/class-tnet-jobs-public.php`, detail CSS | Low–Medium | Approved hero and Explore panel use existing term assignments | Term IDs/labels and Finder query contract |
| P1 | Employer identity and claim card | `public/class-tnet-jobs-public.php`, detail CSS | Low | Current employer data is underused and generic copy is extra | Employer `website_url`; claim route; supported location choice |
| P1 | Leaderboard placement | `public/class-tnet-jobs-public.php` | Low | Existing helper is absent from detail | Existing `render_jobs_leaderboard_ad()` |
| P1 | Closed/expired/protected state reconciliation | Same renderer/CSS | Medium | Live raster must not break governed alternate states | Integrity and engagement behavior |
| P1 | Detail regression coverage | `tests/browser/jobs-shell.spec.cjs` and focused plugin tests if test harness exists | Medium | Current tests omit Job Detail, action counts, content and structured data | Stable fixture/slug and auth-state strategy |
| Blocked | Share behavior | Future explicitly authorized behavior surface; likely public PHP/JS | Unknown | Approved row has no governed action semantics or current implementation | Product/interaction decision; cannot be invented by convergence ticket |

No schema, route, repository, or service rewrite is required for the supported
visible content.

## 7. Implementation grouping

Recommended smallest coherent boundaries, in order:

1. **JC-030 structural renderer convergence.** One objective: change the live
   published external-application detail markup to the approved information
   hierarchy using current data/helpers, including chips, employer card,
   related links, claim link and both ad placements. Stop after markup and
   renderer-level assertions; do not style beyond classes needed for structure.
2. **JC-030 application and engagement state convergence.** One objective:
   centralize the existing application decision and Save state into the approved
   placements for external, protected email, protected instructions, logged-out,
   logged-in, saved, closed and expired conditions. Stop with behavior tests.
   Exclude Share until separately governed.
3. **JC-030 desktop visual match.** One objective: match the approved raster at
   the governed desktop canvas using detail-scoped CSS and the shared shell.
   Stop after desktop screenshot comparison; do not begin responsive work.
4. **JC-030 shell/breadcrumb convergence.** One objective: provide the approved
   navbar and count-aware breadcrumb through shared components without changing
   other approved Job Center states. Stop with cross-route shell regression
   checks.
5. **JC-030 acceptance regression.** One objective: add deterministic browser
   coverage for visible sections, control counts, optional-content omission,
   ads, no overflow, metadata/JSON-LD and alternate lifecycle/auth states. Stop
   without feature work.

The first two boundaries may be reversed if the implementer wants state-safe
helpers before markup, but they should not be combined with global shell work.

## 8. Risk assessment

### Highest-risk areas

1. **Action-state truth.** The current page repeats controls, but those controls
   encode external versus protected methods, authentication, reveal state,
   save state and non-actionable history. Moving them visually without one
   shared decision can create contradictory actions.
2. **Share ambiguity.** A visible approved row has no governed behavior.
   Implementing Web Share, clipboard, mail, or a menu would invent product
   behavior; omitting it prevents pixel-complete convergence.
3. **Shared-shell regression.** Header/footer code serves browse, detail, saved
   and alerts. A global change could drift already approved Finder states.
4. **Breadcrumb counts.** Counts must use the same public eligibility/filter
   semantics as Finder; ad hoc counts could disagree with approved discovery.
5. **Raster-only authority.** No matching editable source exists. CSS matching
   must use the approved PNG plus written authorities and cannot reinterpret
   unclear details.

### Easiest wins

- remove the ordinary Published badge;
- remove hero Save/Apply duplication;
- change the qualifications heading and list treatment;
- call the existing leaderboard helper;
- link the existing employer claim route;
- replace generic employer copy with supported identity/website;
- add supported chips from already loaded Core Terms.

### CSS-only opportunities

- typography, spacing, separators and continuous main-column flow;
- removal of the outer main-card border/radius;
- chips, qualification check markers, rail cards, action icons and Apply panel;
- 300 × 250 and 728 × 90 neutral reservation styling;
- approved desktop column widths and top alignment.

CSS alone cannot remove duplicate semantic controls, add missing links/data, or
correct the shell labels.

### Template/PHP-only opportunities

- section order and conditional omission;
- action count and placement;
- employer, related-query, claim and leaderboard markup;
- hero chips and approved Job Details fields;
- removal of raw published status and generic employer copy.

There is no standalone template; “template-only” work means the inline renderer
in `class-tnet-jobs-public.php`.

### Behavioral changes

- No behavior change is required for current Apply, protected reveal, Save,
  lifecycle availability, term links or claim routing when their helpers are
  reused.
- Related links require only existing Finder query construction.
- Share is the only visible approved behavior with no current/governed path and
  must remain blocked rather than guessed.
- Count-aware breadcrumbs add read-only queries and must share Finder
  eligibility rules.

### Testing concerns

- external URL, protected email and protected instructions;
- logged-out login continuity and logged-in reveal/save/unsave;
- saved and unsaved labels/states;
- live, closed, expired, invalid-application and not-found detail;
- optional summary, qualifications, salary, employer website and updated date;
- single canonical action decision despite two approved Apply entry points;
- one Save control only;
- term chip/link order and URL encoding;
- breadcrumb counts agreeing with Finder visibility;
- 300 × 250 and 728 × 90 dimensions and placement;
- no horizontal overflow and <=1200px shell;
- unchanged meta description and live-only `JobPosting` JSON-LD;
- no regressions to JC-010, JC-011, JC-014 and JC-015 shared shell/footer.

## Deterministic conclusion

Convergence should begin in the existing public renderer and detail-scoped CSS,
not in the schema or domain services. The implementation should preserve the
current canonical pipeline and replace only the visible composition and missing
read-only affordances. A complete pixel/interaction match is deterministic
except for Share; that one row requires explicit behavior authority before it
can be implemented without violating the no-feature-expansion constraint.
