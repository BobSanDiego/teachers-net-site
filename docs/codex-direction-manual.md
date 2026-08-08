# Teachers.Net Codex Direction Manual

Teachers.Net is a WordPress/DDEV project in WSL with custom product plugins.

Core Terms is the reusable classification dependency. The repo/folder is still named `profilaxes`, but the visible product name is Core Terms.

Teachers.Net Jobs is the active job board plugin at `wordpress/wp-content/plugins/tnet-jobs`.

Primary rule:
Terms classify. Jobs authorizes. WordPress authenticates.

## Engineering Runtime Standard

## Ticket Delivery and Report Ownership

Views-specific execution follows the durable procedure in
`docs/core-terms/views-ticket-handoff-and-execution-procedure.md`, including
startup worktree preflight, narrow authority loading, inline-ticket precedence,
clean-cycle hopper flushing, canonical browser verification, and status-first
Git reporting.

For companion ChatGPT ticket retrieval, use the Companion Chat Tail-Read
Procedure: locate the exact chat title, read only the newest 6–10 turns in
newest-first order, and execute only the latest complete fenced block whose
first non-empty line is exactly `TICKET READY FOR CODEX`. Use the returned cursor
for one older page only when the ticket is truncated or required context is
missing. Prefer durable repository authority over companion-chat history and
stop rather than guessing if the complete ticket cannot be recovered.

The authoritative Codex Desktop ticket format is an inline fenced code block
in the active ChatGPT conversation. Older downloadable `.txt` ticket guidance
is historical and must not override the current inline ticket. Attachments and
hopper artifacts provide context and evidence; they do not silently replace the
active inline instruction.

ChatGPT is responsible for product/architecture direction, ticket sequencing,
review commentary, and the next-ticket handoff. Codex is responsible for
inspection, implementation, verification, Git, and the status-first completion
report and cycle artifacts. `CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE
TICKETS` belong to ChatGPT's review/handoff post, not Codex's completion report,
unless a ticket explicitly requires those headings.

JC053 production UI work uses the repository containing the production
`tnet-jobs` source at `/home/bobreap/projects/teachers-net-site`. The former
standalone workbench at `/home/bobreap/projects/teachers-net-jobcenter` is an
archived reference only.

For Job Center, the current post-parity gate is:

1. `JC053-STEPPER-RUNTIME-PARITY-DIAGNOSTIC`
2. `JC053-STEP1-RUNTIME-ASSET-MIGRATION`
3. `JC053-STEP1-ADD-SCHOOL-JOBSITE-INTEGRATION`

JC053 runtime UI changes now occur directly in the canonical production
`tnet-jobs` source. No synchronization back to the archived workbench is
required.

## Repository Continuity and Chat Handoff Optimization

Treat the repository's Authority Manifest, approved contracts, governance,
execution plans/roadmaps, and accepted implementation as the durable authority
order. Companion chat is consulted only for unresolved context. Unless the
ticket explicitly requires audit, rediscovery, architecture review, authority
verification, repository-wide investigation, or diagnostic comparison, do not
re-prove settled facts or perform broad repository archaeology. Inspect only
the materially affected implementation and dependencies.

For routine `next ticket` execution, tail-read only the latest companion-chat
portion sufficient to locate the latest completed review and complete `TICKET
READY FOR CODEX` block. Read older turns only when the ticket is truncated,
context is missing or changed, repository authority cannot resolve the issue,
or a contradiction is detected. Stop and report contradictions rather than
silently reinterpreting project history.

ENGINEERING-GOV001 applies to all future tickets: maintain one objective,
reuse proven diagnostics, implement before one structured verification sweep,
and stop when the objective is proven. Use minimum necessary browser evidence;
do not repeat unchanged diagnostics. At approximately eight minutes, provide a
progress checkpoint; at approximately twelve minutes without convergence, stop
and report the blocker. Never claim visual QA that was not performed. Prefer
 removing conflicting responsive owners over adding overrides, and keep reports
 concise with implementation, verification, and remaining risks separated.

ENGINEERING-GOV002 adds three execution paths: FAST PATH for narrow known-owner
UI corrections with a measured preflight, one coherent edit, and wide/
intermediate/narrow smoke test; STANDARD PATH for ordinary implementation; and
DIAGNOSTIC PATH for unknown, repeated-failure, cross-step, or broad defects.
FAST PATH permits at most two implementation passes, delays full evidence until
the smoke test passes, checkpoints at five minutes, stops at eight minutes
unless only finalization remains, and mandates a stop at ten minutes.

ENGINEERING-GOV001 v2 adoption: Community 3.0 tickets must name the active
UX006–UX015 milestone before execution, advance only that milestone, treat
runtime and tooling as support work, perform one structured verification sweep,
and stop once the objective is proven. Completion reports begin with roadmap
status and state the completed capability, remaining capability, and remaining
risk. Later milestone work is not authorized by diagnostics or infrastructure
work.

ENGINEERING-GOV002 adoption: When a Community ticket references Engineering
Director screenshots or visual evidence, Codex must locate and open the actual
attachment through the supported attachment/conversation mechanism, record its
accessible identifier/path, and inspect it before implementation or acceptance.
After one bounded access-repair attempt, Codex must stop if the evidence remains
unavailable; it may not substitute its own screenshots, DOM output, source
inspection, or automated assertions.

## Persistence Model

## Project Startup Worktree Preflight

Every project startup must first verify that the current working directory and
Git worktree match the requested project:

- Job Center: `/home/bobreap/projects/teachers-net-jobcenter`
- Community: `/home/bobreap/projects/teachers-net-community3`

If the startup worktree does not match, stop and report the mismatch before
performing implementation work. Do not switch branches or infer project
identity from the current branch alone.

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

For Job Center cycle review, ChatGPT should read the optimized Report Directory
first. The Report Directory contains the completion package and every modified
source file needed for ordinary review. The Hopper remains the complete
long-term archive, including large evidence bundles, traces, logs, diagnostics,
and historical material; retrieve it when recovery, audit, or deeper evidence
is required.

## Canonical Review URL Discipline

The Engineering Director review URL is the sole authority for UI verification.
Do not substitute another port, server, worktree, launcher, or runtime. Every
UI completion report starts with the exact canonical URL and
`Verified against canonical URL: YES` or `NO`. Record PID, command line, cwd,
docroot, loaded asset paths, and relevant SHA-256 values. Use a cache-bypassed
hard reload and confirm expected assets. If the canonical runtime is stale,
broken, unreachable, or different from the intended source, stop and repair it
before verification; an alternate runtime cannot satisfy the ticket.

## Google Drive Synchronization Cadence (PROCESS-GOV002)

Local repository documentation is authoritative. Sync the operational Drive
Handoff only for PREPARE HANDOFF, an explicit Engineering Director request, a
major milestone or phase transition, or the tenth primary ticket-code
transition since the last successful sync. Count leading primary codes, not
suffixes. Maintain `Drive sync primary-code transitions: N / 10` in the active
local Engineering Handoff and reset it only after Drive write and connector
readback. Otherwise do not invoke Drive.

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

## TWEAK MODE

TWEAK MODE is a deferred-commit workflow for a small bounded change. It is
activated only when the instruction is explicitly prefaced with `tweak` or
`tweak mode`; it is not persistent and must not be inferred from context.

During an explicitly prefaced tweak, make only the requested change and perform
the minimum relevant inspection and verification. Do not commit or push. Track
the files and hunks created by the tweak so they can be finalized or rolled
back selectively.

Normal work continues after a tweak. Documentation, governance, diagnostics,
browser work, and unrelated implementation instructions are handled normally
and must not be refused because pending tweak changes exist. If unrelated work
must be committed first, selectively stage it and leave pending tweak changes
unstaged.

`Finalize` verifies and commits/pushes only pending tweak changes. `Roll back`
discards only pending tweak changes, preserving pre-existing and unrelated
work. Neither operation may use a broad destructive reset.

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

### Views browser-QA self-healing (GOV-VIEWS002)

For Views tickets requiring authenticated browser evidence, invoke
`tools/qa/verify-views-browser-qa.sh` before QA. It launches or reuses the
isolated profile through `tools/qa/launch-chrome-cdp-9222.ps1`, verifies
Windows CDP, establishes the local-only port-proxy bridge on `9223`, verifies
WSL reachability, and discovers the canonical authenticated Views page. MCP
remains the preferred control surface; if MCP is unavailable, direct CDP
automation may use the verified bridge. Missing MCP alone is not a reason to
skip browser QA. If any layer remains unavailable after automatic recovery,
stop and report `🚩 ENGINEERING INPUT REQUIRED 🚩` with Chrome, Windows CDP,
WSL bridge, authenticated-page, and screenshot-persistence status. Do not
substitute curl, PHP lint, source inspection, or DOM inference for required
authenticated browser evidence. Capture screenshots directly to WSL and
verify their nonzero file before hopper collection.

If engineer action is required, the report must give the exact failed layer,
the exact command, the canonical review URL, the expected ready state, and
whether execution resumes immediately. The standard action is to run the
bootstrap from elevated Windows PowerShell:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\bootstrap-views-browser-qa.ps1" -ConfigureBridge
```

The port proxy normally persists. Verify it at every browser-QA cycle and
request this engineer action only when verification shows it is missing or
unusable.

When MCP is unavailable and the verifier reports `READY`, invoke the direct
fallback from WSL:

```bash
/mnt/c/Users/bobre/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/bin/node.exe tools/qa/run-views-browser-qa.mjs http://127.0.0.1:9223
```

The helper attaches to the authenticated session, discovers the canonical
Views tab, reads DOM/computed CSS, performs a reversible disclosure click,
collects console/page errors, and writes a real WSL screenshot. A non-empty
PNG is required before hopper collection.

DDEV is the canonical browser verification environment.

For canonical runtime QA, always use the connected Chrome DevTools MCP bridge
(`mcp__chrome_devtools__list_pages`, navigation, snapshots/evaluation,
console inspection, and screenshots as required). A shell `curl`, source
inspection, HTTP 200, or automated assertion is not a substitute for
authenticated browser QA.

If the MCP bridge has no usable page or cannot inspect the canonical runtime,
run the canonical Chrome CDP launcher and retry the bridge once using the
recovery procedure in the active Engineering Handoff. If MCP inspection still
fails, classify browser verification as `UNAVAILABLE`, report the exact layer,
and continue bounded engineering diagnosis through approved evidence tied to
the same canonical session when possible: direct CDP, network/console
evidence, server logs/traces, authoritative DB/service readback, repository
tests, derivative/media inspection, or persisted canonical screenshots. This
must never be reported as browser PASS or used to satisfy human visual QA.
Stop only when the ticket objective requires the unavailable browser
observation, no relevant fallback remains, a distinct application defect is
proven, or scope would expand. Do not substitute an alternate runtime,
profile, route, worktree, or unauthenticated state.

Reports must separate browser verification, engineering diagnosis, and human
visual acceptance using the values `PASS | PARTIAL | UNAVAILABLE`,
`PASS | FAIL | BLOCKED`, and `PASS | PENDING | NOT REQUIRED`, respectively.

Every UI completion report must state `Verified against canonical URL: YES` or
`NO`, identify the authenticated browser/runtime state, and list any console,
viewport, overflow, and visual results actually observed through MCP.

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
