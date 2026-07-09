# Teachers.Net Jobs Roadmap

## Roadmap Context

This roadmap is a durable planning reference, not the live project cursor.

For current Job Center state, read:

1. `docs/job-center/project-cursor.md`
2. `docs/job-center/engineering-handoff.md`

## Current Version State

- Jobs code version: `0.1.0`
- Jobs DB schema target: `0.8.0`
- Current implementation state is tracked in the Job Center Engineering
  Handoff and the Jobs plugin Git history.

## Completed V1 Areas

- Core Terms integration.
- Jobs schema and repository/service foundation.
- Employer authorization and request-access workflow.
- Recruiter dashboard, create wizard, edit form, and My Jobs management.
- Public browse, search results, saved jobs, job detail, and application instructions.
- Salary, location, address, and requirements/qualifications data support.
- Design System v1 public presentation shell.
- Admin jobs, employers, memberships, moderation, bulk moderation, metrics, activity log, and import tools.
- Communications service and admin/recruiter lifecycle emails.
- Job Alerts MVP:
  - create
  - manage
  - matching
  - scheduled daily delivery
  - email pause/manage path
- DDEV Playwright browser verification foundation.

## Current Job Finder Direction

- `/jobs/` is one shared Job Finder route.
- Search and Browse are interface states on that route, not separate pages.
- Default `/jobs/` opens in Search mode with the keyword search field visible.
- Browse mode remains same-route and category-first.
- Both modes use the same result engine, filters, pagination, active filter
  chips, analytics surface, and URL/query model.
- Search/Browse panel polish and responsive QA remain near-term tasks.
- Quiet employer CTAs may appear on public job-seeker surfaces using:
  `Hiring? Post a Free Job`.
- Employer CTAs must remain secondary and must not compete with job-seeker
  actions such as search, filters, saves, alerts, or apply paths.

## Remaining V1 Readiness Work

1. Search/Browse panel polish and responsive QA on `/jobs/`.
2. Human visual QA across public browse/search/detail, saved jobs, alerts,
   recruiter flows, and admin flows.
3. Release-candidate declaration ticket.
4. Launch checklist and deployment plan.
5. Production monitoring and rollback plan.
6. Post-launch issue triage process.

## Post-Launch / Follow-Through Phases

These phases are deferred until the V1 public surface is release-candidate
ready unless explicitly pulled forward.

### 1. Search/Browse Job Finder Completion

- Preserve `/jobs/` as the shared Search/Browse route.
- Continue treating Search and Browse as interface states.
- Finish Browse panel polish, keyboard behavior, and responsive QA.
- Keep the same result engine, filters, pagination, chips, and URL/query model.
- Do not add schema-dependent filters until the underlying data model is ready.

### 2. Analytics / GA4 Instrumentation

- Add a shared JavaScript analytics wrapper such as `tnet.track(...)`.
- Do not scatter raw `gtag()` calls through templates or component scripts.
- Track:
  - Search/Browse mode toggle
  - keyword search
  - browse submit
  - filter add/remove
  - chip removal
  - clear all
  - sort changes
  - pagination
  - listing opens
  - save job
  - apply clicks
  - create job alert
  - employer CTA / post free job clicks

### 3. Advertising / AdSense System

- Add a shared ad helper before replacing placeholders.
- Replace placeholder ad modules with AdSense later, not during V1 polish.
- Preserve fallback house ads.
- Support 300x250 and 728x90 slot sizes.
- Define slot names/channels by page context:
  - Jobs landing/search
  - job detail
  - location results
  - grade results
  - subject results
- Keep visible page context and relevant metadata near ad slots so future ad
  configuration can distinguish page intent without template guessing.

### 4. Jobs SEO / Indexing

- Define canonical URL strategy for Jobs pages.
- Use `noindex, follow` for arbitrary query combinations where appropriate.
- Create clean indexable URLs for valuable hubs:
  - location pages
  - grade pages
  - subject pages
  - selected high-value combinations
- Add sitemap strategy for indexable hubs and public job detail pages.
- Audit breadcrumb schema.
- Audit JobPosting schema.
- Add internal links from listing pages to employer, location, grade, and
  subject hubs where useful.
- Avoid indexing infinite filter combinations.

### 5. Core Terms / Taxonomy Hubs

- Treat Core Terms as the reusable classification source for crawlable hubs.
- Build Jobs hubs first, then reuse the pattern later for Lessons,
  Chatboards, Directories, Classifieds, and future Teachers.Net modules.
- Location, Grade, and Subject can become reusable SEO/category surfaces.
- Do not mutate Core Terms taxonomy from Jobs roadmap work; taxonomy changes
  require their own Core Terms or Membership Taxonomy review path.

### 6. Design System Capture

- Document the reusable Search/Browse framework.
- Document editorial list rows.
- Document detail conversion layout.
- Document right rail patterns.
- Document breadcrumb pattern.
- Document ad placeholder and ad slot conventions.
- Keep the patterns reusable across Lessons, Chatboards, Directories, and
  Classifieds.

## V2 / Deferred

- Radius/proximity search.
- Geocoding automation.
- Maps.
- Employer Type filtering.
- Work Arrangement filtering.
- Salary filtering and salary matching.
- Autocomplete.
- Notification center and bell alerts.
- Per-job alert dedupe and match history.
- Advanced alert preferences and frequencies.
- ATS/internal applications.
- Resumes and candidate search.
- Reviews and district/school profiles.
- Paid plans, subscriptions, and promotion commerce.
- Automated JSON/feed/API/scraper import beyond the CSV foundation.
