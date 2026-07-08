# Job Center Engineering Handoff

This is the compact session-continuity handoff for ChatGPT/Codex. Read it after
the global Engineering Director Playbook. It is not a roadmap, architecture doc,
or full project history.

## 1. Current Working State

- Project state: Active Development.
- Current phase: Teachers.Net Jobs V1 release-candidate readiness, with the
  supporting Core Terms platform now stabilized as shared classification
  infrastructure.
- Current milestone: Core Terms platform foundation is tagged; Jobs seed dataset
  planning has moved from field inventory to generated JSON.
- Current focus: Teachers.Net Jobs Seed Dataset readiness before importer work.
- Current reference page/flow/component: Jobs seed data documents and
  `data/jobs-seed.json`, not the public `/jobs/` visual shell.
- Current ticket/workstream: J157 completed an inspection-only content review of
  the generated seed dataset. The dataset is structurally useful but should get
  a content refinement pass before importer implementation.

## 2. Recently Completed

Newest first:

- ED004 multi-project governance tightening
  - What changed: local and Google Drive operational docs were tightened so
    Job Center, Core Terms, Membership Taxonomy, BirdMart, and other workstreams
    use separate Project Cursor / Engineering Handoff state.
  - Verification status: documentation-only; no Jobs plugin or application code
    changed.
  - Commit/tag: root repo commit `1cb1990`; no tag.
  - Why it mattered: prevents new ChatGPT/Codex sessions from importing another
    workstream's state into Job Center seed/importer work.

- J157 Jobs Seed Dataset Content Review
  - What changed: inspection only; reviewed a deterministic sample of roughly
    25 employers and 50 jobs from `data/jobs-seed.json`.
  - Verification status: no files changed. Review found strong structural
    coverage but weak content variety.
  - Commit/tag: none; inspection only.
  - Why it mattered: confirmed the generated JSON is usable as a structural
    starting point, but should not yet be treated as canonical demo content.

- `3ba082c` J156 generate Jobs seed dataset JSON
  - What changed: created `data/jobs-seed.json` with 50 employers and 250
    representative synthetic jobs.
  - Verification status: JSON parsed successfully; employer/job counts were
    validated; lifecycle, salary, location, Core Terms, fixture metadata, and
    edge-case coverage were checked; `git diff --check` passed.
  - Commit/tag: `3ba082c`; no tag.
  - Why it mattered: produced the first complete structured dataset for future
    importer, QA, demo, and regression work.

- J150-J155 Jobs seed dataset planning/specification sequence
  - What changed: field inventory, reusable-classification planning, location
    readiness, employer matrix, and generation rules were established.
  - Verification status: inspection/docs only; no importer, schema change, or
    scraping.
  - Commit/tag: see plugin history for individual docs commits; no release tag.
  - Why it mattered: gave the generated seed dataset clear scope and safety
    rules.

- Core Terms foundation milestone
  - What changed: Core Terms reached a documented shared-infrastructure
    baseline, including editor, admin IA, archive lifecycle, Active Connections,
    and integration contract.
  - Verification status: milestone tag pushed in the Core Terms plugin.
  - Commit/tag: `v0.6.2-core-terms-foundation`.
  - Why it mattered: Jobs can rely on Core Terms as stable classification
    infrastructure while seed/importer work continues.

## 3. Immediately On Tap

1. J158 seed dataset content refinement
   - Objective: improve `data/jobs-seed.json` content quality before importer
     work.
   - Expected scope: generated JSON only, or generation script/scratch process
     only if the ticket explicitly allows it.
   - Inspect first: J157 findings, especially repetitive wording, duplicate
     title patterns, grammar issues, role/employer mismatches, and salary
     realism.
   - Do not change: importer, schema, plugin behavior, Core Terms, public Jobs
     UI, or generated employer matrix unless explicitly requested.

2. Jobs seed importer planning
   - Objective: design the smallest safe importer after the dataset content is
     accepted.
   - Expected scope: planning or implementation only after the Engineering
     Director approves the dataset quality.
   - Inspect first: Jobs schema/repositories/services, CSV import patterns,
     lifecycle rules, employer creation rules, Core Terms assignment mapping,
     and cleanup requirements.
   - Do not change: schema unless explicitly approved; do not auto-publish
     outside established trusted-employer behavior.

3. V1 release-candidate readiness review
   - Objective: decide when to return from seed-data preparation to the larger
     V1 RC checklist.
   - Expected scope: docs/checklist/verification planning.
   - Inspect first: `docs/job-center/jobs-roadmap.md`, `docs/job-center/project-cursor.md`,
     `docs/codex-direction-manual.md`, Jobs plugin status, and Core Terms foundation
     docs.
   - Do not start: V2 features such as geocoding/radius, ATS, resumes, commerce,
     notification center, or advanced alerts.

## 4. Active Decisions / Current Discussion

- Core Terms is now the canonical production classification platform for
  Teachers.Net and should be treated as stable shared infrastructure unless a
  focused Core Terms ticket says otherwise.
- Jobs seed data is the current focus because it will support QA, demo,
  regression testing, importer development, search/filter/alert coverage, and
  future Reference World work.
- The seed dataset now has three layers:
  1. `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-seed-dataset-specification.md`
  2. `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-seed-job-generation-specification.md`
  3. `wordpress/wp-content/plugins/tnet-jobs/data/jobs-seed.json`
- J157 found the JSON structurally strong but content-repetitive. The likely
  next step is a JSON content refinement pass, not an importer.
- The dataset should remain synthetic in job-facing prose. Real institutions
  may be used as factual employer identifiers, but job descriptions,
  requirements, summaries, logos, marketing copy, and postings must not be
  copied.
- Location/proximity remains future-facing: seed records include proximity-ready
  fields, but Jobs does not yet implement radius search or geocoding.
- Importer work should include a safe cleanup/removal strategy using fixture
  metadata so seed data can be identified and removed cleanly later.

## 5. Open Risks / Blockers

- The generated seed jobs are structurally valid but content quality is not yet
  high enough for canonical demo use. Main issues: formulaic summaries and
  descriptions, repeated titles, grammar artifacts, and some employer/role or
  salary mismatches.
- No importer exists yet. `data/jobs-seed.json` is data only and has not been
  loaded into WordPress.
- Some older historical planning docs still point to public `/jobs/` visual QA,
  while the active discussion has moved to seed dataset readiness.
- V1 release-candidate declaration remains pending Engineering Director
  approval.
- Production deployment, monitoring, rollback, and launch operations are not
  finalized.

## 6. Stop-After Boundary

For seed dataset work, stop after each inspection/refinement pass and wait for
human review before building the importer. Do not broaden into schema changes,
live imports, Core Terms edits, public Jobs UI changes, search behavior, Job
Alerts behavior, recruiter/admin workflow changes, or V2 features unless
explicitly directed. Handoff updates are documentation-only and should not be
committed unless explicitly approved.

## 7. What Not To Do

- Do not work from OneDrive or Windows UNC paths.
- Do not add Jobs code to Core Terms.
- Do not rename the `profilaxes` folder, CFM classes, `cfm` prefixes, DB
  tables, URLs, slugs, or namespaces unless explicitly instructed.
- Do not edit third-party/vendor plugins.
- Do not reset, prune, delete, rebuild, or uninstall Docker/DDEV/WordPress/plugin
  state unless explicitly instructed.
- Do not import other project routes, branding, repo facts, shell decisions, or
  workflow state into Teachers.Net.
- Do not begin V2 features before V1 release-candidate status is declared or the
  Engineering Director redirects.
- Do not refactor or reopen the stable global Engineering Director Playbook
  during ordinary project work.
- Do not add radius/geocoding, salary matching/filtering, notification center,
  ATS/resumes/internal applications, commerce, or advanced alert behavior unless
  explicitly requested.
- Do not use real job descriptions, current job postings, employer profile
  prose, logos, or marketing copy in the Jobs Seed Dataset.

## 8. Source Documents

- `AGENTS.md`
- `docs/job-center/project-cursor.md`
- `docs/codex-direction-manual.md`
- `docs/codex-ticket-discipline.md`
- `docs/design-system-v1.md`
- `docs/job-center/product-definition-v1.md`
- `docs/job-center/jobs-roadmap.md`
- `docs/decision-log.md`
- `docs/plugin-architecture.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/development-constitution.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-seed-dataset-specification.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-seed-job-generation-specification.md`
- `wordpress/wp-content/plugins/tnet-jobs/data/jobs-seed.json`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-capability-snapshot.md`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-admin-ia.md`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-integration-contract.md`
