# Teachers.Net Codex Direction Manual

Teachers.Net is a WordPress/DDEV project in WSL with custom product plugins.

Core Terms is the reusable classification dependency. The repo/folder is still named `profilaxes`, but the visible product name is Core Terms.

Teachers.Net Jobs is the active job board plugin at `wordpress/wp-content/plugins/tnet-jobs`.

Primary rule:
Terms classify. Jobs authorizes. WordPress authenticates.

## Persistence Model

The global Engineering Director Playbook lives outside this repository and
should contain reusable methodology only. Teachers.Net-specific context belongs
in local Teachers.Net docs.

Google Drive contains the operational ChatGPT recovery layer. Default startup
uses only:

- `Teachers.Net Engineering/Shared/Engineering Director Playbook` —
  <https://docs.google.com/document/d/1GMT6pOFlhxC3wo4pfx6sxbxjzanPZJduvetY2CD6mWQ>
- `Teachers.Net Engineering/Projects/<Project Name>/<Project Name> Engineering Handoff`

Every active Project Cursor must record the full Google Docs URL for its
Engineering Handoff. PREPARE HANDOFF output must include both full URLs, not
title-only Drive search instructions.

The Handoff is delta-oriented and follows
`docs/engineering-handoff-template.md`. ChatGPT reads the Project Cursor,
product contract, UX specification, design system, visual manifest, roadmap,
or implementation docs only when needed. Codex uses
`docs/documentation-governance.md` to choose the active local project directory
and follows the repository read order before work.

Google Drive is for ChatGPT operational recovery only. It is not a mirror of
repository architecture, implementation detail, full roadmaps, contracts,
design systems, visual manifests, or ticket history.

Local repository docs remain the durable engineering source for architecture,
roadmaps, specifications, implementation details, and verification instructions.

Current project directories:

- Job Center: `docs/job-center/`
- Core Terms: `docs/core-terms/`
- Membership Taxonomy: `docs/membership-taxonomy/`

Project-state lifecycle values:

- Planning
- Active Development
- Stabilization
- Maintenance
- Archived

Each Project Cursor should declare one state.

Do not depend on workflow state, product decisions, routes, branding, or plugin
facts from other projects. Use other projects only as examples of method when
explicitly helpful.

Core Terms and Membership Taxonomy are related but distinct:

- Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.
- Membership Taxonomy is the curation/classification/human-review workstream for
  historic Teachers.Net chatboard taxonomy.
- Membership Taxonomy is not a Core Terms rename or implementation ticket
  stream.

## ChatGPT And Codex Roles

ChatGPT role:

- product direction
- UX guidance
- architecture review
- prioritization
- planning

Codex role:

- inspection
- implementation
- verification
- Git operations
- documentation updates

Default workflow:

Inspect → plan → approve → implement → verify → commit → push.

Default behavior:

Do not create new process unless it reduces effort, risk, or maintenance.

## Execution Modes

Choose the lightest mode that matches the requested action:

1. **Product Engineering** — inspect, plan, implement, verify, and commit
   application behavior or infrastructure changes.
2. **Governance** — inspect the named authorities, update durable process or
   product records, verify consistency, and commit documentation changes.
3. **Fast Operations** — for local, reversible, mechanical, or disposable
   operations with an already-known command or service path.

### Fast Operations Protocol

Invoke with:

`Execution mode: Fast Operations. Known-safe target declared. Direct command first. One targeted inspection pass maximum. Use smoke verification. Stop after verified success.`

Read-order gating is strict: read the ticket, explicitly named authority or
continuity documents, and directly relevant implementation files only. Do not
read broad history or unrelated architecture unless required by the ticket.

For recurring operations, the default decision budget is at most one targeted
repository search, one command-help query, and two directly relevant file
inspections. These are maximums, not required steps.

- Start with the direct command or existing service call.
- Perform at most one targeted inspection pass before execution.
- Prefer a reversible local action; do not build speculative infrastructure.
- Verify proportionally using the tiers below, then stop immediately after
  success.
- Escalate to Product Engineering or Governance if the direct approach fails
  once, the command is destructive or uncertain, or meaningful project data
  could be damaged.

Fast Operations is prohibited for production mutations, irreversible data
changes, schema changes, security-model changes, migrations, application
behavior changes, or uncertain destructive commands. It never relaxes
authorization, ownership, or architecture rules.

#### Verification tiers

- **Smoke verification:** trivial, reversible, local operations. Perform the
  operation, confirm the requested resulting state, and stop.
- **Targeted verification:** bounded code or documentation changes. Inspect the
  affected diff, run relevant lint or syntax checks and focused tests, verify
  directly affected behavior, and run `git diff --check` when files changed.
- **Full verification:** cross-cutting, security-sensitive, schema, migration,
  production, release, or broad-regression work. Use broader tests and recovery
  checks proportional to risk. Meaningful visual changes still require human
  visual QA.

Declare the known-safe target before execution when applicable:

```text
Environment: local DDEV
Repository: <repo or plugin path>
Route/resource: <route or resource>
User/data target: <target>
Intended mutation: <mutation>
Reversible: yes/no
Production impact: none/describe
```

Separate **Must Verify** (evidence required to prove safe success) from **Nice
to Inspect** (optional context, cleanup, or future improvement). Nice-to-Inspect
work must not delay completion once Must Verify is satisfied.

Examples:

| Task | Fast path | Proportional verification |
|---|---|---|
| Create a local WordPress user | Run the known `wp user create` command | `wp user get` with login, email, and role |
| Reset a local password | Run `wp user update --user_pass=...` | Authenticate or verify the user record |
| Clear local cache | Run the established DDEV/cache command | Recheck the target cache or route |
| Verify a route | Request the known local URL | Confirm status and the expected marker |
| Attach membership data | Call the existing membership service | Read back active membership and scope |

#### Developer Operations Cookbook

Maintain a small verified cookbook for recurring DDEV startup/execution,
WP-CLI users and passwords, cache clearing, route smoke checks, Git status/diff
checks, hashes and image dimensions, and common local fixtures. Keep entries
concise and avoid duplicating commands governed elsewhere. Its preferred home
is a shared local developer-operations document under `docs/`; Codex checks it
before repository discovery for a recurring Fast Operations task. Creating the
cookbook is separate work and is not part of this amendment.

The one-failure stop rule is mandatory: after one failed direct approach,
diagnose the exact failure, choose one materially different next approach, or
stop and report the blocker. Do not enter repeated search/help/retry loops.
Once the requested result and safety boundary are proven, do not continue with
additional searches, broad tests, optional cleanup, unsolicited documentation,
continuity updates, or infrastructure creation.

## Environment

- Project root: `/home/bobreap/projects/teachers-net-site`
- Local URL: `https://teachers-net.ddev.site`
- Docroot: `wordpress`
- Webserver: `apache-fpm`
- PHP: `8.4`
- DB: `MariaDB 11.8`

## Core Terms

- Plugin path: `wordpress/wp-content/plugins/profilaxes`
- Visible product name: Core Terms
- Minimum Jobs dependency version: `0.6.0`
- Owns term hierarchy, stable IDs, APIs, hooks, compilation, and Labs diagnostics.
- Jobs must treat Core Terms as read-only unless a ticket explicitly says otherwise.
- Do not rename `profilaxes`, CFM classes, prefixes, DB tables, slugs, URLs, or namespaces.

## Jobs

- Plugin path: `wordpress/wp-content/plugins/tnet-jobs`
- Remote: `git@github.com:BobSanDiego/tnet-jobs.git`
- Current Job Center status belongs in `docs/job-center/project-cursor.md` and
  `docs/job-center/engineering-handoff.md`.

## Project Cursor

Each active workstream owns its Project Cursor in its project directory.

For Job Center, see `docs/job-center/project-cursor.md` and
`docs/job-center/engineering-handoff.md`.

For Core Terms, see `docs/core-terms/project-cursor.md` and
`docs/core-terms/engineering-handoff.md`.

For Membership Taxonomy, see `docs/membership-taxonomy/project-cursor.md` and
`docs/membership-taxonomy/engineering-handoff.md`.

## Browser Verification

DDEV is the canonical browser verification environment.

Root-level commands:

- `ddev exec npm run browser:verify`
- `ddev exec npm run browser:smoke`

The browser suite runs through the root project Playwright dependency inside DDEV. Do not add Node dependencies to the Jobs plugin repo.

Screenshots are not generated by default. Human visual QA is separate from engineering verification unless a ticket explicitly requests screenshots or diagnostic evidence.

## Boundaries

- Do not add Jobs code to Core Terms.
- Do not create duplicate saved-job, alert, communication, moderation, or import systems.
- Do not add radius/proximity/geocoding unless explicitly requested.
- Do not add salary matching/filtering unless explicitly requested.
- Do not add ATS, resumes, internal applications, messaging, subscriptions, or notification center behavior for V1 unless explicitly requested.
- Do not change lifecycle, moderation, trust, or schema outside a named ticket.
