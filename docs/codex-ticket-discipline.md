# Codex Ticket Discipline

## Current Ticket Delivery and Reporting Authority

For Codex Desktop, ChatGPT delivers executable engineering tickets in the
conversation as one inline fenced code block. A downloadable `.txt` ticket is
not required and is not authoritative merely because it exists in an older
handoff or archive. A local or Drive ticket artifact may be attached as
supporting evidence, but the current inline ticket and the active project's
continuity documents control.

Responsibilities are deliberately separated:

- ChatGPT owns product direction, architecture, prioritization, ticket
  sequencing, and the conversational review post.
- Codex owns repository inspection, implementation, verification, Git, and the
  status-first completion report plus Report/Hopper payload.
- `CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE TICKETS` are ChatGPT review
  and handoff markers. They are not required in Codex reports or filenames
  unless the active ticket explicitly requires them.

After Codex publishes a completed cycle, ChatGPT reviews the Report Directory,
records the result in its project post, reconciles the next-ticket sequence,
and issues the next inline fenced ticket. Codex must not invent the next ticket
from a stale roadmap when the current handoff establishes a newer prerequisite.

## Repository Continuity and Inspection Governance

Unless the active ticket explicitly requires an audit, rediscovery,
architecture review, authority verification, or repository-wide investigation,
previously accepted project governance, architecture, authority manifests,
synchronization state, approved design decisions, and engineering conclusions
remain valid.

Do not repeatedly rediscover or re-prove settled project facts. Do not perform
broad repository archaeology, global searches, or unrelated comparisons merely
to establish context already accepted by the project. Use the established
authority in this order: Authority Manifest; approved contracts; governance;
execution plans and roadmaps; accepted implementation; then companion chat
only for unresolved context. Do not reread or re-ingest companion chat merely
to rediscover facts already established in repository authority.

If direct evidence contradicts an accepted project fact, stop immediately and
report the contradiction. Do not silently reinterpret project history or launch
a broad re-audit.

When beginning implementation, read only the governing documents required for
the current work and inspect only the directly affected implementation, routes,
services, repositories, components, documentation, and dependencies. Reuse
previously established architecture, decisions, and verified conclusions.

Repository-wide audits are permitted only when explicitly requested, required
by the active ticket, necessary to diagnose a verified contradiction, or needed
to establish new project authority. Use the smallest inspection scope that
still permits safe implementation and proportional verification.

### Companion Chat Handoff Retrieval

For routine `next ticket` directives, do not ingest the complete companion chat
transcript. Read only the latest one to three turns and locate the newest
complete `TICKET READY FOR CODEX` block. Read older turns only when the latest
ticket is truncated, required context is missing or changed, repository
authority cannot resolve the issue, or a contradiction is detected.

ChatGPT handoffs should end with this optional pointer when practical:

```text
CODEX HANDOFF POINTER

Latest ticket:
<ticket title>

Complete ticket body:
YES

Prior context required:
NO

Required local documents:
<paths>

Stop boundary:
<summary>

Current authority manifest:
<path>

Current authority commit:
<commit>
```

The local Project Cursor, Engineering Handoff, authority manifests, contracts,
and current Report/Hopper artifacts remain the source for settled project
state. Companion-chat history is retrieved selectively for the active ticket,
new decisions, and unresolved context.

The current Job Center sequencing gate is:

`JC053-STEPPER-RUNTIME-PARITY-DIAGNOSTIC` →
`JC053-STEP1-RUNTIME-ASSET-MIGRATION` →
`JC053-STEP1-ADD-SCHOOL-JOBSITE-INTEGRATION`.

Runtime asset migration must be completed and verified before further Step 1
integration work proceeds.

## Project-Specific Clean-Cycle Hopper Procedure

### Disposable local Views QA fixtures

For local DDEV verification, Codex may create, reset, mutate, and remove an
explicitly disposable Views QA fixture when deterministic test state is
required by the authorized ticket. Separate engineer approval is not required
for this fixture work. Use an unambiguous name such as `DV-QA-*`, `TEST-*`, or
the ticket identifier followed by `-QA`; do not use an editorial or production
View as a fixture.

This authority applies only to local development data needed by the ticket. It
may include creating a draft, shuttling canonical terms, removing terms,
resetting the draft, autosave and draft-lifecycle checks, destructive
draft-only verification, persistence inspection, and deterministic recreation.
Core Terms remain read-only canonical references.

Production data, published Views unless expressly authorized, user-created or
editorial Views, another active test's fixture, and Core Terms themselves
remain protected. A diagnostic ticket may create or reset a disposable fixture
as test setup; this is not application implementation.

Before mutation, record the fixture name, View/version ID, and intended state.
After verification, clean it up unless the next ticket needs it. If preserved,
record its exact state and location in the Views handoff. Every report must
distinguish application/source changes from disposable QA-data mutations and
state cleanup or preservation status.

Missing disposable QA data alone is not an `ENGINEERING INPUT REQUIRED` gate.
That gate is reserved for genuinely human-only prerequisites such as login,
MFA, operator-only permissions, CAPTCHA, subjective visual acceptance,
external credentials, or inaccessible runtime/browser state.

For the active Teachers.Net project, the canonical slug is `jobcenter`. Every ticket
must begin by running `python3 tools/hopper/clean_cycle.py begin
--project jobcenter --cycle <YYMMDDHHMMSS>`. This archives the prior
`tmp/hopper/jobcenter/Report (Job Center)/` and
`tmp/hopper/jobcenter/Hopper (Job Center)/` contents into the never-deleted
`tmp/hopper/jobcenter/archive/<cycle-id>/Report (Job Center)/` and
`tmp/hopper/jobcenter/archive/<cycle-id>/Hopper (Job Center)/` directories.

### Report Directory versus Hopper

These are intentionally different deliverables. The Report Directory is the
optimized ChatGPT review package; the Hopper is the complete permanent archive.

The Report Directory must contain, when applicable:

- completion report, manifest, cycle record, Architect Report, Command Result,
  Evidence Index, and NEXT-STEP;
- every modified source file, including PHP, CSS, JS, HTML/templates, SQL,
  migrations, and documentation;
- representative screenshots.

Large evidence bundles, ZIP archives, browser traces, generated caches, and
historical archives are excluded from the Report Directory by default unless
ChatGPT requests them. Every source file modified in the cycle is copied into
the Report Directory automatically.

The Hopper remains the complete long-term engineering archive and continues to
preserve reports, manifests, cycle records, tickets, modified source,
screenshots, evidence, ZIP bundles, diagnostics, logs, and supporting files.
No preservation or archive behavior is reduced by the Report Directory split.

ChatGPT should normally review the Report Directory first and retrieve Hopper
artifacts only for historical recovery, audit, or explicitly requested detail.

Views exception: when the active project is Views, the completion cycle must
also publish the final human-readable report and generated cycle output into
both `tmp/hopper/views/Report (Views)/` and
`tmp/hopper/views/Report (views)/`. Confirm that the report is nonzero in both
directories before reporting completion. Mirror the cycle record, manifest,
and report artifact into `tmp/hopper/views/Hopper (views)/`; retain the
validated canonical record in `Hopper (Views)`. The completion report must
list both report directories and their WSL/Windows paths.

Before collecting any new Views artifact, flush all four active directories
(`Report (Views)`, `Report (views)`, `Hopper (Views)`, and `Hopper (views)`) by
moving their prior contents into the new cycle archive. Do not append to a
previous current cycle; preserve the archive and the protected `output.txt`.

During the cycle, collect every created or modified artifact with the helper's
`collect` command. The helper copies forensic files flat into `Hopper (Job Center)/` using
`<base>-jobcenter-<cycle-id>.<extension>`, refuses collisions, records original
paths and hashes, and never touches the protected `output.txt`. Create the
project report, manifest, valid JSON cycle record, and any required evidence
bundle using the same cycle identifier. A ticket is not complete until
`Hopper (Job Center)/` contains the report, manifest, cycle record, every created or
modified file, and required evidence and passes the helper's `validate`
command.

Fail-closed completion rule: never report a ticket complete, and never hand off
to ChatGPT, until the current cycle has been published to both the formal
Report (Job Center) and Hopper (Job Center) directories and validated
successfully. If implementation, verification, commit, or push finishes before
packaging, treat the ticket as incomplete, create the missing cycle from the
existing evidence, and report the packaging repair explicitly. A Git commit
does not substitute for the required Report/Hopper cycle.

Every cycle's human-readable completion report must include a section titled
`Representative URLs Affected`. List the canonical or representative URLs that
the ticket changed, verified, or intentionally left unchanged, with one status
per URL: `changed`, `verified`, `unchanged`, `blocked`, or `not applicable`.
Include the route purpose and, when relevant, the runtime used. Do not invent
URLs: if a ticket has no URL surface, state `None — documentation/backend-only
ticket`. For UI or routing tickets, include at least the canonical review URL
and the affected production route when both exist. Record the same URL list in
the machine-readable cycle record so ChatGPT can recover URL scope without
opening the report.

Codex completion reports should use the ticket's required status-first template
and include `CSS OWNERSHIP REPORT` and `OWNERSHIP MATRIX` when applicable, then
document verification and remaining issues. The conversational markers
`CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE TICKETS` apply to ChatGPT's
project posts and outgoing ticket handoffs, not to Codex completion reports or
hopper filenames unless a specific ticket explicitly requires them.

The final screen report must print the full current-cycle filename list, WSL
path, Windows path, archive path, commit, push result, and the command
`explorer.exe "\\wsl$\\Ubuntu-24.04\\home\\bobreap\\projects\\teachers-net-site\\tmp\\hopper\\jobcenter\\current"`.
Preserve blocked and incomplete cycles, exclude unrelated dirty files, never
silently overwrite artifacts, and never delete historical cycles. The user's
ingestion action is to open `current/`, select all, and drag the set into
ChatGPT.

One ticket.

One goal.

Audit first.

Implement second.

Verify thoroughly.

Commit.

Tag.

Push.

Visually inspect before issuing another implementation ticket.

Prefer smallest viable diff.

## ENGINEERING-GOV001 Runtime Discipline

These runtime controls apply to every future ticket unless the current ticket
explicitly overrides them:

- Keep one objective per ticket. If a newly discovered issue is not a direct
  blocker, report it and stop rather than expanding scope.
- Implement first, then run one structured verification sweep. Reuse proven
  diagnostics and stop once the objective is proven; do not gather redundant
  screenshots or repeat unchanged checks.
- If execution passes approximately eight minutes, checkpoint what is proven,
  what remains, the estimated remaining time, and why continuation is justified.
  If it passes approximately twelve minutes without convergence, stop and report
  the blocker.
- Capture only the minimum browser evidence required by the ticket. Record
  visual QA as `PENDING` when it was not actually performed.
- Prefer removing conflicting CSS owners over layering overrides. Avoid
  `!important` unless an external/shared contract requires it.
- Keep completion reports concise and separate implementation, verification,
  and remaining risks. Stop when further work has diminishing engineering value.

## ENGINEERING-GOV002 Fast Path

Use FAST PATH for a narrow, known-owner UI correction confined to one component
or responsive owner, with no architecture, state, data, validation, production,
security, or accessibility-audit scope. Before editing, inspect only that
component's complete wide, intermediate, and narrow owners, explicit child
placements, relevant geometry, active `!important` declarations, source order,
and winning selector. Measure rendered values rather than estimating them.

FAST PATH then makes one coherent bounded edit, runs a three-state smoke test
(wide, intermediate, narrow), and begins screenshots or broader evidence only
after those states pass. It allows at most two implementation passes and two
smoke-test sweeps. At five minutes, report proven and remaining work; at eight
minutes, stop unless the implementation already passes and only final evidence
or commit/push remains; at ten minutes, stop and report. Capture only the
ticket-required evidence and keep one canonical browser page.

STANDARD PATH remains the default for ordinary implementation work and uses the
ticket's proportionate verification requirements. DIAGNOSTIC PATH is required
for unknown root causes, repeated failures, cross-step state, broad responsive
redesign, or any defect that cannot be resolved within the FAST PATH limits.
ENGINEERING-GOV002 supplements ENGINEERING-GOV001; it does not weaken truth,
verification, stop-boundary, or evidence requirements.

Future narrow tickets should state:

```text
Execution mode: FAST PATH
Required pre-edit inspection: complete local owner only
Smoke-test states: wide, intermediate, narrow
Maximum implementation passes: 2
Full evidence begins only after smoke test passes.
Stop at 8 minutes unless only final evidence or commit/push remains.
Mandatory stop at 10 minutes.
```

When a ticket edits a local Markdown file and the post-ticket report references
that file, first move every active hopper file into the hopper's `archive/`
directory using a UTC timestamp-versioned filename. Then copy the new final
files directly into the hopper root so all active files share one level for
drag-and-drop. Update `HOPPER-MANIFEST.txt` with active paths and the archive
batch, and link the hopper directory and copied files in the report. Do not
copy non-Markdown website files under this default rule. Use the shared hopper
`\\teachers-net-site\\tmp\\hopper\\`. For the active Job Center project,
use the exact archive directory
`\\teachers-net-site\\tmp\\hopper\\archive\\JobCenter\\`. When creating
each handoff or referenced local guidance file, write the active root copy and
a duplicate archive copy in that directory, with a trailing UTC
`YYMMDDHHMMSS` timestamp before the archive extension. Do not move or rename
active files later. Delete only obsolete active document files; never delete
directories or the engineer-owned `output.txt`, which must never be edited,
copied, archived, manifested, or reported.

This preservation rule covers local Markdown guidance, audits, roadmaps,
cursors, handoffs, and related documentation files. It excludes website
non-Markdown files, including HTML, CSS, JavaScript, images, fonts, and other
site/generated assets, unless the user explicitly requests them.

At ticket completion, save a plain-text copy of the final post-ticket report in
the shared hopper root as `output-YYMMDDHHMMSS.txt` and create its same-cycle
archive duplicate under the active project's archive. This is a generated
report based on the final response, not an automatic capture of the rendered
interface. Include its filename/link in the completion report. Keep it
separate from the protected engineer-owned `output.txt`.

Every post-ticket report involving hopper file creation or refresh must link to
the hopper directory and include a separate bullet list of every modified file
included in the current hopper set.

Reports that reference local directories must provide a Windows File Explorer
link/path for every directory referenced, alongside the WSL path used for
commands. Keep the native `C:\\...` path copyable and never translate it to
`C:\\home\\...`.

Reuse existing routes, services, repositories, and validation.

Avoid duplicate systems.

Separate architecture, implementation, and presentation.

Do not expand scope unless explicitly instructed.

Treat approved mockups as implementation specifications.

After an authority mockup is approved, feasibility analysis and implementation
convergence must treat the approved mockup as a fixed requirement. Do not
introduce UX changes, rename governed labels, or revive rejected concepts
without a separate Engineering Director decision. If two bounded convergence
passes fail to align an implementation with the approved authority, stop and
issue a diagnostic ticket instead of continuing speculative tuning.

Optimize for minimal compute, minimal churn, and deterministic progress.

When project documents conflict, use this precedence:

1. the current user ticket
2. the active project's Engineering Handoff
3. the active project's Project Cursor
4. `docs/codex-direction-manual.md`
5. active product/design docs
6. historical planning docs

Every ticket should improve one screen, one workflow, or one defect—not all three.

### Engineer intervention for authentication and physical desktop actions

Codex must stop and visibly flag the engineer whenever progress requires a
login, MFA/credential entry, browser permission, popup response, file picker,
drag/drop, clipboard action, or any other physical desktop response that the
available automation cannot complete and verify. Use the clearest available
Windows/Desktop notification mechanism when one is available, and also state
the required action plainly in Codex commentary/report. Never infer that a
login or popup was completed merely because a window appeared, and never claim
authenticated browser verification until the authenticated page and required
state are visibly confirmed. Resume only after the engineer's action is
observable through MCP or equivalent browser evidence; otherwise report
canonical verification as `NO` and the exact blocker.

### Repeated Human-QA Failure Escalation

If the same visual or behavioral defect survives two or more implementation
passes, Codex reports completion, and human QA still reproduces it, stop
speculative patching and issue a diagnostic ticket. Audit conflicting CSS
authorities (cascade order, specificity, duplicate/later rules, `!important`,
media/container queries, native `[hidden]`, state selectors, and stale or
workbench-derived rules), JavaScript state/handler ownership and post-load
mutation, and DOM/rendering duplicates, wrong instances, collapsed/offscreen
nodes, computed styles, and bounding geometry.

For meaningful visual defects, capture and inspect a final rendered-browser
screenshot, verify relevant computed styles and actual geometry, and compare
the visible result with the approved design/state. DOM attributes, state
variables, selector existence, and automated assertions are supporting
evidence only. If semantic assertions disagree with the rendered result, the
ticket remains FAIL/BLOCKED until the discrepancy is explained.

## Local Persistence Model

Teachers.Net-specific facts, decisions, implementation state, and Project
Cursor state belong in this repository's local docs.

The global Engineering Director Playbook lives outside this repo and should
contain reusable methodology only. Do not move Teachers.Net-specific state into
the global playbook, and do not depend on another project's workflow state when
working here.

Shared governance docs live at the root of `docs/`. Project-specific docs live
inside the relevant project directory.

Shared documents:

- Engineering Director Playbook
- Codex Direction Manual
- Engineering Workflow
- Ticket Discipline
- Design System
- Product Definition when it applies across workstreams
- Plugin Architecture
- Global Decision Log

Project documents:

- Project Cursor
- Engineering Handoff
- Capability Snapshot
- Architecture
- Roadmap
- Project-specific specifications

Current project directories:

- `docs/job-center/`
- `docs/core-terms/`
- `docs/membership-taxonomy/`

Google Drive operational documents live under:

- `Teachers.Net Engineering/Shared/`
- `Teachers.Net Engineering/Projects/<Project Name>/`

Default ChatGPT startup reads only the Engineering Director Playbook and the
active project-specific Engineering Handoff. Supporting governance documents
may remain in Drive and are consulted only when the ticket requires them. Drive
must not mirror local repository architecture, implementation detail, full
roadmaps, contracts, design systems, manifests, or ticket history.

Every new ChatGPT project session should read the Playbook and active Handoff,
adopt their state without summarizing them, report the five required state
fields, and stop. Codex continues to read local governance and ticket-specific
documents before changing files. If the active workstream is unclear, stop and
ask before assuming Job Center context.

Every Project Cursor should declare one project state:

- Planning
- Active Development
- Stabilization
- Maintenance
- Archived

## Phase-Transition Governance

These rules govern phase changes prospectively from DOC020R onward:

- Never infer a phase transition.
- A transition requires verified Git evidence, reconciled Project Cursor and
  Engineering Handoff, Google Drive handoff synchronization/readback, and
  explicit Engineering Director authorization.
- Verify repository state before phase reconciliation; keep root governance and
  nested implementation repositories distinct.
- Approval does not imply implementation. Implementation does not imply browser
  convergence. Browser convergence does not imply release-candidate acceptance
  or production deployment.
- An audit backlog is not the product roadmap, and no ticket creates historical
  fact merely by asserting it.
- Keep one objective per ticket, reuse existing infrastructure, avoid broad
  refactors during convergence, and require human browser review after
  meaningful UI implementation.

Core Terms and Membership Taxonomy are related but distinct:

- Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.
- Membership Taxonomy is the curation/classification/human-review workstream for
  legacy taxonomy.
- Membership Taxonomy must not be treated as a Core Terms rename or
  implementation ticket stream.

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

Working style:

1. Choose one reference page or flow.
2. Refine it until approved.
3. Extract reusable components or tokens only when they reduce future effort,
   risk, or maintenance.
4. Propagate carefully after the reference is approved.

Default behavior:

Do not create new process unless it reduces effort, risk, or maintenance.

For local, reversible, mechanical tasks, use the shared protocol in
`docs/codex-direction-manual.md` by declaring:

`Execution mode: Fast Operations. Known-safe target declared. Direct command first. One targeted inspection pass maximum. Use smoke verification. Stop after verified success.`

This mode permits one targeted inspection pass, direct-command-first execution,
and proportional verification only. It does not apply to production,
irreversible, schema, security, migration, application-behavior, or uncertain
destructive work; after one failed direct approach, stop and report.

## PREPARE HANDOFF

When the user says `prepare handoff` or asks for session handoff preparation,
Codex should first confirm the active project. If the active project is unclear,
ask before editing any handoff.

Handoff updates must follow `docs/engineering-handoff-template.md` and answer
only:

1. Current Phase
2. Current Ticket
3. Last Completed Milestone
4. Next Five Planned Tickets
5. Current Blockers
6. Recently Adopted Governance Documents
7. Recently Approved Product Decisions
8. Recently Approved Visual References
9. Active Design Authority
10. Immediate Engineering Priorities

Do not add project history, settled architecture, duplicated governance,
contract/design-system content, or implementation details preserved elsewhere.
Update the Project Cursor only for durable project-state, phase, milestone,
decision, risk, or stop-boundary changes. End with the v2 startup prompt.

PREPARE HANDOFF is documentation-only. Do not modify application code. Commit
documentation only if explicitly approved.

Before updating Drive, establish current repository facts, update local
continuity documents first, compare the exact Drive Handoff, and reconcile
legitimate newer Drive facts before writing. Verify the Drive write by connector
readback. Do not claim synchronization without confirmation.

PROCESS-GOV002 limits ordinary Drive Handoff synchronization. Sync only for
PREPARE HANDOFF, an explicit Engineering Director request, a major milestone
or phase transition, or ten primary-code transitions. Related suffix tickets
remain one primary code. Keep the durable counter and last successful sync in
the active local Engineering Handoff; reset the counter only after a successful
Drive write and connector readback. Do not invoke Drive when no trigger exists.

Update the execution plan only when the critical path, priority order, phase
boundary, V1/V1.1/V2 classification, settled decision, major dependency, or
pilot/release acceptance changes. Update the roadmap only when durable sequence
or scope changes.

Project-aware ChatGPT startup prompt template (also maintained at
`docs/chatgpt-startup-prompt.md`):

```text
Project: <Project Name>

Retrieve and read these exact Google Drive documents in order:

1. Engineering Director Playbook
   https://docs.google.com/document/d/1GMT6pOFlhxC3wo4pfx6sxbxjzanPZJduvetY2CD6mWQ
2. <Project Name> Engineering Handoff
   <FULL GOOGLE DOC URL FROM THE ACTIVE PROJECT CURSOR>

Adopt their workflow and current engineering state. Do not reconstruct missing
state from conversational memory and do not summarize the documents. If either
document is unavailable, ask for its link or content.

Consult the Project Cursor, product contract, UX specification, design system,
visual manifest, roadmap, or implementation docs only when the current ticket
requires them.

Reply with only:
- current phase
- current ticket
- last completed milestone
- next five planned tickets
- current blockers

Then stop and wait for my instruction.
```

Every generated handoff prompt must include the full Google Docs URL beneath
each document title. Do not emit title-only retrieval instructions. The active
Project Cursor owns the project-specific Handoff URL; if it is absent or cannot
be verified, report that deficiency instead of guessing a link.

## Visual Verification Policy

Default implementation tickets should use engineering verification, not routine screenshot generation.

Engineering verification should normally include:

- confirming affected routes return 200
- confirming no console errors were introduced
- confirming no horizontal overflow
- measuring affected elements where appropriate
- validating CSS when CSS changes
- running PHP lint only when PHP files change
- running git diff --check

Do not generate screenshots by default.

Generate screenshots only when:

- explicitly requested
- documenting a diagnostic investigation
- documenting a significant before/after milestone
- a rendering anomaly requires evidence
- visual evidence is required for acceptance

Human visual QA is performed by the Engineering Director after implementation.

Codex should optimize for minimum compute while maintaining engineering confidence.

## Responsive Convergence Procedure

Apply this procedure only to existing responsive or visual components where
success depends on the rendered browser result: breakpoint behavior, navbar or
footer layout, rails and panels, flex/grid alignment, spacing, visibility,
ordering, dropdowns, carets, icons, and similar CSS/token refinements. Do not
apply it automatically to backend, database, routing, authentication,
authorization, email, cron, documentation-only, or ordinary mechanical work.

Before editing, perform one narrow rendered-state preflight at the failing or
target viewport. Inspect only the affected component's rendered DOM and the
active authority: computed display, flex/grid ownership, gap, margin, padding,
width, position, overflow, white-space, media-query rules, source order,
specificity, pseudo-elements, runtime classes, and JavaScript rerendering where
relevant. Stop once the governing rule is identified; if it cannot be
identified in a bounded 30–90 second pass, escalate diagnostically rather than
making a speculative change.

Implement the smallest authoritative correction, removing a conflicting owner
when appropriate. Do not add `!important` merely to force convergence. After
each attempt, reload with cache bypass, set the exact requested viewport,
confirm the selector/runtime state, inspect the final computed property or
geometry, and compare the rendered result. Source edits, syntax checks, or
screenshots without confirmed viewport and cache state do not prove success.

### Two-stage responsive verification

For responsive verification and inheritance tickets, use a two-stage matrix.

Stage A is discovery: run the complete requested matrix only until a responsive
defect is found or the ticket passes unchanged. If a defect is found, stop the
full matrix, isolate the root cause, and implement the smallest coherent
correction. Do not continue collecting redundant evidence from known-broken
states.

Stage B is post-fix verification. Re-run the full matrix only for the
representative authority view, one representative Step 1 view, and one
representative Step 2 (or highest implemented) view. Verify remaining
implemented views only at the breakpoint family affected by the correction.
The full matrix must be restored across all implemented views when the change
affects renderer registration, authority inheritance, shared shell ownership,
shared navigation, shared footer, shared stepper, routing, layout ownership, or
state management. Otherwise, verify only directly affected views.

Use the earliest implemented step, current implementation step, and authority
reference as the default representative views. Select only the affected
breakpoint family: navbar (1025, 1024, 900, 768, 767), footer (1024, 768,
767, 650, 320), or choice cards (1200, 1024, 932, 900, 768). Do not run
unrelated breakpoint families.

If browser tooling cannot produce a requested viewport, record both requested
and actual widths and explain the limitation. Never claim verification at an
unavailable width. Stop once representative views pass, targeted inheritance
passes when required, and the affected breakpoint family passes.

This optimization applies only to responsive verification and inheritance
tickets. It does not reduce verification breadth for implementation tickets
that introduce new responsive behavior.

If the rendered symptom is materially unchanged, mark the attempt failed
immediately. Do not commit, push, update completion documentation, or generate
a final hopper cycle. Diagnose the active computed style, rendered geometry,
media-query/source-order authority, runtime classes, and rerender path before
another attempt. Permit at most three internal implementation/verification
iterations in one ticket; keep them uncommitted until convergence. Stop and
report a diagnostic blocker if the authority remains unidentified, three
attempts fail, DOM and rendered state disagree, CSS is overwritten after load,
stale or duplicate render sources are suspected, the correction expands into
architecture, browser verification is unavailable after canonical recovery,
or the objective is contradictory.

This procedure supplements existing modes: TWEAK MODE retains deferred commit
and push while using this preflight and acceptance gate; VISUAL TUNE MODE keeps
its fast human-guided loop and uses a minimal rendered check; COMPONENT MATCH
MODE retains its existing measurements and uses this gate. It is a procedure,
not a new lifecycle command.

For responsive convergence tickets, record actual phase timestamps and report
durations: trigger timestamp, preflight start/end, implementation start/end,
verification start/end, diagnosis start/end when invoked, Git start/end,
hopper-packaging start/end, and total elapsed time. Use `Not invoked` for a
phase that did not occur. Do not use approximate narrative timing when the
timestamps are available and do not fabricate values. Also report internal
attempt count, rendered verification pass count, same-symptom diagnosis,
target viewports, governing rule, before/after measurement, final computed
owner, and cache-bypass reload. Avoid routine screenshots unless acceptance or
diagnosis requires them.

The required completion order is implementation, verification, commit, push,
manifest refresh, cycle-record refresh, hopper validation, then completion
report. After a successful push, run:

`python3 tools/hopper/clean_cycle.py refresh --project jobcenter --cycle
<YYMMDDHHMMSS> --commit <hash> --push pushed --committed-source <repo-path>`

once for each committed artifact. The refresh must set committed artifacts to
`true`, preserve genuinely uncommitted evidence as `false`, and rewrite both
the manifest and cycle record. Validation must confirm that report commit,
manifest commit, cycle-record commit, and push status agree before completion.

## CONTACT SHEET PROCEDURE

The explicit Engineering Director trigger `contact sheet` (or an equivalent
request for a responsive contact sheet) creates a durable screenshot package
for the current unambiguous project, workbench/route, authority view, and
responsive breakpoint set. Do not trigger this procedure automatically for an
ordinary ticket.

Store each run beneath:

`tmp/contact-sheets/<project-slug>/<YYYYMMDD-HHMMSS>/`

Use the actual run-start timestamp and never overwrite an earlier run. The
default Job Center viewport set is 1440, 1200, 1024, 1025, 900, 768, 767, 651,
650, 531, 530, 500, 400, 375, 360, and 320px; omit only widths without value
for the target and add explicitly governed transitions when relevant.

For every width, use the established external Chrome DevTools workflow, load
the correct served URL and authority/hash, reload with cache bypass, use device
scale factor 1, settle the layout, ensure transient controls are in their
baseline state, and capture the full relevant page. Record width/height,
document scroll width, overflow result, console-error count, active view,
responsive mode, and screenshot success. If a capture fails, retry once,
record the failure, and continue; stop before capture if the source or target
cannot be confirmed.

Each completed run contains deterministic files named `viewport-<width>.png`,
one `contact-sheet.png` (or wide/tablet/mobile sheets if one image would be
unreadable), `index.md`, and `manifest.json`. The sheet orders captures widest
to narrowest, labels every viewport, uses a neutral background and consistent
spacing, and does not crop or visually correct the captures. The index records
project, ticket/context, served URL, authority/hash, browser, device scale,
timestamp, viewport list, filenames, objective results, and limitations. The
manifest repeats the metadata and file list in machine-readable form.

The final response must include the exact WSL directory, the native Windows
directory, and a clickable Explorer action targeting the timestamped directory
(or the proven Explorer command):

`explorer.exe "\\wsl$\\Ubuntu-24.04\\home\\bobreap\\projects\\teachers-net-site\\tmp\\contact-sheets\\<project-slug>\\<timestamp>"`

Report only objective findings unless visual analysis was requested, and end
the contact-sheet report with: `Human visual review pending.`

## TWEAK MODE

TWEAK MODE is a deferred-commit workflow for small, tightly bounded changes.
Enter it only when the instruction is explicitly prefaced with `tweak` or
`tweak mode`. Do not infer it from context, and do not treat it as a persistent
session restriction.

For an explicitly prefaced tweak:

- inspect only the directly relevant implementation;
- apply only the requested change;
- run the minimum relevant mechanical or focused verification;
- do not commit, tag, or push;
- record the session-owned files and hunks as pending tweak changes.

Normal project work continues after a tweak. Later documentation, governance,
browser, diagnostic, or unrelated implementation instructions are handled
under the normal workflow. Pending tweak changes must not be mixed into an
unrelated commit; selectively stage unrelated work when separation is safe.

Supported commands:

```text
tweak: <instruction>
tweak mode: <instruction>
Finalize
Roll back
```

`Finalize` verifies, commits, and pushes only the pending tweak changes, then
clears the pending tweak state. `Roll back` discards only uncommitted
session-owned tweak changes, preserves pre-existing and unrelated work, and
clears the pending state. Never use a broad destructive reset for rollback.

## VISUAL TUNE MODE

VISUAL TUNE MODE is a temporary fast-polish mode for human-guided visual
refinement. It may be entered only when the Engineering Director or site owner
explicitly requests it.

VISUAL TUNE MODE is intended for:

- spacing
- typography
- colors
- radii
- icon sizing
- layout tokens
- CSS variables
- positioning
- component polish

VISUAL TUNE MODE must not be used for:

- PHP
- schema
- routing
- authentication or authorization
- services
- cron
- email
- business logic
- architecture
- feature work

Supported lifecycle commands:

```text
Enter VISUAL TUNE MODE
FINALIZE VISUAL TUNE MODE
ABORT VISUAL TUNE MODE
```

While VISUAL TUNE MODE is active:

- apply only the exact requested visual CSS/token changes
- do not audit broadly
- do not refactor
- do not update docs
- do not commit
- do not tag
- do not push
- touch only the smallest CSS/token surface necessary
- run only the fastest relevant syntax check, usually CSS brace validation or
  equivalent
- report changed tokens/rules and final computed values when quick to measure
- stop and wait for the next instruction

Maintain a concise running ledger while VISUAL TUNE MODE is active.

Example:

```text
VISUAL TUNE SESSION

✓ Header height
96 → 105

✓ Logo size
43 → 36

✓ Nav font
14.08 → 17.6
```

Ledger rules:

- only record values actually changed
- record before → after
- keep newest changes at the bottom
- do not repeat unchanged items
- do not include implementation commentary

Do not generate screenshots by default in VISUAL TUNE MODE unless explicitly
requested.

Stop immediately and request exit from VISUAL TUNE MODE if:

- PHP changes become necessary
- template changes require structure beyond minimal presentation
- a request affects functionality
- architecture would change
- database/schema changes would be required
- a visual tweak causes overflow or a broken layout

When finalizing VISUAL TUNE MODE:

- include the completed session ledger in the final report before verification,
  documentation updates, commit, and push
- run normal verification
- update design-system docs if final token values changed
- run browser verification where relevant
- run smoke tests
- run CSS brace validation
- run `git diff --check`
- commit and push the appropriate repo or repos
- do not tag unless explicitly instructed
- report final measurements and commit hashes

When aborting VISUAL TUNE MODE:

- discard all uncommitted visual tuning changes made during the current Visual
  Tune session
- restore the project to the last committed state
- do not update docs
- do not commit
- do not push
- report that VISUAL TUNE MODE was aborted successfully

## COMPONENT MATCH MODE

COMPONENT MATCH MODE is a temporary high-fidelity convergence workflow for one
existing UI component against an approved visual reference. Use it when the
component already exists and needs to be matched closely without broad redesign
or feature work.

Enter COMPONENT MATCH MODE only when the Engineering Director or site owner
explicitly requests it.

Supported lifecycle commands:

```text
Enter COMPONENT MATCH MODE: [component name]
FINALIZE COMPONENT MATCH MODE
ABORT COMPONENT MATCH MODE
```

Example:

```text
Enter COMPONENT MATCH MODE: Results Toolbar
```

COMPONENT MATCH MODE is intended for one component only. Adjacent components may
be touched only when required to preserve alignment with the target component.

COMPONENT MATCH MODE must not be used for:

- business logic changes
- new features
- data behavior changes
- schema changes
- routing
- authentication or authorization
- email
- cron
- admin workflows
- broad architecture changes

While COMPONENT MATCH MODE is active:

- identify the approved reference and component boundary
- measure the current component
- measure the target component where practical
- prefer CSS, token, and layout refinements
- preserve existing functionality and accessibility
- use existing design tokens where possible
- introduce new tokens only when the value is reusable and clearly belongs in
  the design system
- apply the smallest visual/layout change
- do not commit
- do not tag
- do not push
- do not update docs
- run fast validation only
- report the changed-values ledger, current measured values, and remaining
  visible differences
- wait for human visual review before continuing

Stop immediately and report if:

- architecture changes are required to match the component safely
- PHP/template changes become structural rather than presentation-only
- a request would change functionality
- a request would change data behavior
- a request would touch schema, routing, auth, email, cron, admin workflows, or
  business logic
- a visual tweak causes overflow or a broken layout

Fast validation during COMPONENT MATCH MODE:

- CSS brace validation if CSS changed
- PHP lint if PHP/template files changed
- quick no-overflow check if layout changed
- no full browser suite unless specifically requested during the active tuning
  loop

Maintain a concise running ledger while COMPONENT MATCH MODE is active.

Ledger rules:

- only record values actually changed
- record before → after
- keep newest changes at the bottom
- do not repeat unchanged items
- do not include implementation commentary

Example:

```text
COMPONENT MATCH SESSION: Results Toolbar

✓ Control height
44 → 46

✓ Icon size
18 → 16
```

When finalizing COMPONENT MATCH MODE:

- include the completed session ledger in the final report before verification,
  documentation updates, commit, and push
- run full required verification for affected routes
- run `ddev exec npm run browser:verify`
- run `ddev exec npm run browser:smoke`
- run PHP lint if PHP changed
- run CSS validation if CSS changed
- run `git diff --check`
- update docs only if tokens or component rules changed
- commit and push the appropriate repo or repos
- do not tag unless explicitly instructed

When aborting COMPONENT MATCH MODE:

- revert only uncommitted changes from the active Component Match session
- do not touch unrelated work
- do not update docs
- do not commit
- do not push
- report reverted files and repo cleanliness

## Canonical Review URL Discipline (PROCESS-GOV001)

Every UI implementation, browser-QA, screenshot, DOM, console, accessibility,
and human-acceptance report must begin with the exact Engineering Director
review URL and `Verified against canonical URL: YES` or `NO`. The canonical
review URL is the authority; another port, server, worktree, launcher, or
runtime is not a substitute.

Before verification, record PID, command line, cwd, docroot, loaded asset paths,
and relevant SHA-256 hashes. Hard-reload with cache bypass and confirm the
expected assets. If the canonical URL is stale, broken, unreachable, or serves
different code, stop verification and repair the canonical runtime first. If
canonical verification is `NO`, identify both URLs and stop without claiming
completion.

## Browser Verification Environment

Teachers.Net browser verification is project-owned and runs from the root repo
through DDEV.

Use:

```bash
ddev exec npm run browser:verify
```

Do not run browser checks through Windows `npx` from WSL. Do not add Node
dependencies to `wordpress/wp-content/plugins/tnet-jobs`; the root repo owns the
minimal Playwright setup.

The default browser verification is non-screenshot smoke coverage. It should
confirm route health, console/page errors, horizontal overflow, and canonical
container measurements where relevant. Screenshots remain opt-in under the
Visual Verification Policy.

### Chrome MCP is mandatory for canonical UI QA

Canonical authenticated browser verification must use the connected Chrome
DevTools MCP bridge. Begin with `list_pages`, navigate/reload the exact
canonical URL through MCP, and use MCP snapshots/evaluation, console messages,
viewport emulation, and screenshots as required by the ticket. `curl`, HTTP
status, lint, source inspection, or Playwright assertions alone do not satisfy
the browser-QA gate.

If MCP cannot inspect the canonical authenticated runtime, invoke the
repository's canonical Chrome CDP launcher and retry MCP once. If recovery
fails, stop and report the exact blocker, set canonical verification to `NO`,
and do not claim completion or silently substitute unauthenticated/source-only
evidence.
