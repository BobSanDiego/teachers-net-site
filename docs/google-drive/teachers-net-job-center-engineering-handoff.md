# Teachers.Net Job Center Engineering Handoff

This is the compact session-continuity handoff for ChatGPT/Codex. Read it after
the global Engineering Director Playbook. It is not a roadmap, architecture doc,
or full project history.

## 1. Current Working State

- Current phase: Teachers.Net Jobs V1 release-candidate readiness.
- Current milestone: Job Center V1 feature implementation is complete; public
  landing-page polish and release-candidate review remain.
- Current focus: `/jobs/` public Job Center landing page visual convergence.
- Current reference page/flow/component: browse landing results area, especially
  results toolbar, label row, listing rhythm, promo/leaderboard spacing,
  pagination, right rail relationship, and footer transition.
- Current ticket/workstream: human-guided visual polish after J144 Results
  Toolbar Replication and follow-up Component Match/Tweak passes.

## 2. Recently Completed

Newest first:

- Active uncommitted browse-list spacing/color tune
  - What changed: label row color moved to the nav-text token; list-to-promo,
    list-to-paginator, paginator-to-footer, and browse-inner bottom spacing were
    tightened in public CSS.
  - Verification status: not yet finalized in a committed ticket.
  - Commit/tag: none yet.
  - Why it mattered: this is the current live visual QA surface and must not be
    overwritten casually.

- `c1a8b6a` Tune browse results toolbar spacing
  - What changed: tightened the results toolbar/list spacing after J144.
  - Verification status: committed in the Jobs plugin.
  - Commit/tag: `c1a8b6a`; no tag found.
  - Why it mattered: moved the results controls closer to the approved landing
    composition.

- `502a9f7` J144 replicate results toolbar
  - What changed: rebuilt/refined the desktop toolbar above the results table
    with deterministic SVG-style control treatment and Design System spacing.
  - Verification status: committed in the Jobs plugin.
  - Commit/tag: `502a9f7`; no tag found.
  - Why it mattered: restored the approved North Star toolbar structure without
    changing search/filter behavior.

- `dc03f64` J143 control hero subtitle wrapping
  - What changed: added phrase-level wrapping for the hero subtitle.
  - Verification status: committed in the Jobs plugin.
  - Commit/tag: `dc03f64`; no tag found.
  - Why it mattered: aligned the hero copy rhythm to the approved target.

- `0583ae7` J142 control hero headline wrapping
  - What changed: controlled the headline wrap so desktop reads as the approved
    two-line phrase structure.
  - Verification status: committed in the Jobs plugin.
  - Commit/tag: `0583ae7`; no tag found.
  - Why it mattered: fixed the most visible hero copy mismatch without changing
    search behavior or lower-page layout.

- `0a6b854` J141 align hero search card internals
  - What changed: separated primary search controls from the Advanced Search
    secondary utility row.
  - Verification status: committed in the Jobs plugin.
  - Commit/tag: `0a6b854`; no tag found.
  - Why it mattered: corrected internal search-card alignment while preserving
    form behavior.

## 3. Immediately On Tap

1. Finalize or continue the current browse-list visual tuning
   - Objective: decide whether the active CSS tune should be finalized,
     adjusted, or aborted.
   - Expected scope: public `/jobs/` browse landing CSS only.
   - Inspect first: current `tnet-jobs-public.css` diff and `/jobs/` visual QA.
   - Do not change: backend logic, routes, search behavior, Job Alerts, admin,
     recruiter workflows, schema, or unrelated page sections.

2. Engineering Director visual review
   - Objective: decide whether the Job Center landing page is visually approved
     for release-candidate status.
   - Expected scope: human review of the current page against the approved
     North Star/Design System target.
   - Inspect first: `/jobs/` at desktop and mobile, especially toolbar/list/rail
     spacing and footer transition.
   - Do not change: implementation without a focused follow-up ticket.

3. Release-candidate declaration planning
   - Objective: move from visual polish to RC readiness if approved.
   - Expected scope: docs/checklist/verification planning.
   - Inspect first: `docs/jobs-roadmap.md`, `docs/current-cursor.md`,
     `docs/CODEX_HANDOFF.md`, and Jobs plugin status.
   - Do not change: product scope or V2 feature boundaries.

## 4. Active Decisions / Current Discussion

- The Job Center landing page remains the reference flow because it is the first
  reusable public Teachers.Net 3.0 presentation pattern.
- Current work is visual convergence, not architecture. The app canvas, header,
  hero, search, results shell, Job Alerts MVP, and backend workflows are already
  established.
- The immediate tradeoff is whether the active fine-tuning has reached the
  approved rhythm or needs one more narrow Component Match/Tweak pass.
- Salary matching, radius/geocoding, notification center behavior, advanced
  alert preferences, ATS/internal applications, and commerce remain V2/deferred.
- Human visual QA decides acceptance. Codex should verify engineering health but
  should not keep tuning without explicit direction.

## 5. Open Risks / Blockers

- The Jobs plugin currently has uncommitted visual CSS changes in
  `public/css/tnet-jobs-public.css`.
- V1 release-candidate declaration is pending Engineering Director approval.
- Production deployment, monitoring, rollback, and launch operations are not yet
  finalized.

## 6. Stop-After Boundary

Stop after each focused visual pass and wait for human review. Do not broaden
into V2 features, schema changes, alert architecture, admin workflows, recruiter
workflow changes, or unrelated page redesigns unless explicitly directed.

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
- Do not add radius/geocoding, salary matching/filtering, notification center,
  ATS/resumes/internal applications, commerce, or advanced alert behavior unless
  explicitly requested.

## 8. Source Documents

- `AGENTS.md`
- `docs/current-cursor.md`
- `docs/CODEX_HANDOFF.md`
- `docs/codex-ticket-discipline.md`
- `docs/design-system-v1.md`
- `docs/v1-product-definition.md`
- `docs/jobs-roadmap.md`
- `docs/decision-log.md`
- `docs/plugin-architecture.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/development-constitution.md`
