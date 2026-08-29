# START-CODEX — Teachers.Net Engineering Front Door

You are entering the shared Teachers.Net engineering workflow. This file is a
router, not a replacement for project authority.

Canonical workflow: `WORKFLOW-V2.md` / machine version `workflow-v2.json`.

## Stable one-word command

When the Engineering Director enters exactly `BOOTSTRAP`, resolve the intended
project and consume the canonical entry through `tools/workflow/workflow.py`.
For a registered project, reconcile Workflow V2 and project continuity without
repeating onboarding. For an unregistered named project, the same command
authorizes only the bounded onboarding path in `PROJECT-BOOTSTRAP-SPEC.md`; no
second authorization phrase is required. BOOTSTRAP never authorizes product
implementation.

After successful BOOTSTRAP, `PREPARE HANDOFF` is centrally available for a
supplied OpenAI ChatGPT share URL (the preferred source; the file source is a
validated fallback). Codex resolves project-specific paths from the project
record; the engineer does not provide master, Cursor, Handoff, Report/Hopper,
or package paths. Successful preparation publishes the immutable package under
the canonical HANDOFFS root and returns a directly openable two-file successor
drop containing only `STARTUP-TICKET.txt` and the validated ZIP. The ticket is
zero-context transport instruction; the ZIP is authoritative. A fresh ChatGPT
session ingests those two files, follows the ticket, and types exactly
`LOAD STARTUP`; the supplied `00-LOAD-STARTUP.md` controls ingestion.
The completion response must include a clickable Markdown link to the exact
absolute successor-drop directory, such as
`[Open handoff directory](/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS/<drop>/)`.
This is a shared response requirement for every registered project, not a
Job Center-specific convention.

## Exact shared-command precedence

The exact standalone commands UPDATE CHATGPT and CHATGPT SYNC STATUS are
centrally inherited Shared Workflow commands. After normalizing whitespace and
case, dispatch them before project selection, BOOTSTRAP/local command handling,
or conversational interpretation. A registered project must never answer
either command with a project status summary. UPDATE CHATGPT continues
through the live-reader and bounded sync builder; without valid reader input it
must report the precise transport boundary and stop.

## Read first

Read these canonical shared authorities:

1. `workflow-v2.json`
2. `WORKFLOW-V2.md`
3. `CHATGPT-ENGINEERING-OPERATING-CONTRACT.md`
4. `HANDOFF-LIFECYCLE.md`
5. `PROJECT-RECORD-SPEC.md`
6. `TRANSCRIPT-ARCHIVE-SPEC.md`
7. `REPORT-HOPPER-SPEC.md`

Then inspect the active repository's documentation governance, ticket
discipline, Project Cursor, Engineering Handoff, roadmap, and authority
manifest. Existing project authority outranks conversation recency.

## Identify the project before acting

Confirm the current worktree/repository and determine whether a matching
project record exists under `docs/process/conversation-handoff/projects/`.

### Existing registered project

Read its project record first. Resolve its repositories, authorities, runtime,
handoff archive, and Report/Hopper paths from that record. Inspect only the
files needed for the requested ticket. Reuse the shared workflow and preserve
unrelated dirty work.

### New project

Do not clone another project's facts, routes, branding, architecture, or
assumptions. For the exact Engineering Director command `BOOTSTRAP` with a
named project, run the shared bounded onboarding path in
`PROJECT-BOOTSTRAP-SPEC.md`. This authorizes only
registration, continuity scaffolding, transcript provenance, Report/Hopper
publication/validation, and immutable checkpoint publication. It does not
authorize product code, schema, destructive, or production work. Stop only at
a genuine authority, ownership, destructive/production, or missing-evidence
boundary.

## Non-negotiable safeguards

- Never infer product approval, architecture approval, phase transition, or
  human acceptance.
- Tooling/path failure is not application failure; isolate the failing layer
  before changing product code.
- Formal tickets require `TICKET READY FOR CODEX`, one terminal objective,
  complete acceptance/scope/stop boundary, and a matching `END TICKET`.
- Run the Workflow V2 T+0 mechanical preflight before cycle initialization,
- Use `tools/hopper/terminalize.py` as the canonical fail-closed finalize → validate entrypoint; do not report governed COMPLETE before it succeeds.
  archive rotation, repository/browser inspection, or implementation.
- ChatGPT should keep complete formal tickets below 15,000 characters as a
  hard authoring/transport constraint for safe copying. This is a ChatGPT
  delivery rule only, not a Codex implementation limit; Codex must not reject
  an otherwise complete ticket solely because of character count.
- Report is the terminal human-review result; Hopper adds supporting evidence.
- Routine self-contained ChatGPT startup packages live in the shared HANDOFFS
  location and are referenced, not duplicated, in Report/Hopper. The
  operator-facing drop is transport only; reports remain in the local
  executing project's Report/Hopper.
- Use exactly `PREPARE HANDOFF` for routine startup preparation and exactly
  `LOAD STARTUP` in the fresh ChatGPT session.
- Treat current transcript exports as `OPEN/INCOMPLETE` unless closure is
  proven; always state the incorporated-through boundary.
- Preserve unrelated dirty work and stage selectively.

## Upload transport

The direct Codex upload ceiling is 20 files per upload operation. A ZIP counts
as one uploaded file and is not limited to 20 internal members. This transport
constraint must not be applied as a general Report/Hopper, handoff, transcript,
or archive-content ceiling; preserve provenance when bundling related files.

## First response for a new project

Return a bounded bootstrap proposal containing: identified repository/project,
observed governance, missing authority, proposed minimum project-record fields,
required Engineering Director decisions, and safe next inspection. Do not
pretend the project is registered or begin implementation from another
project's context.

The Windows operational projection is:

`C:\Main\Active\Projects\Teachers.Net\SHARED-WORKFLOW\START-CODEX.md`

The tracked canonical source is:

`docs/process/conversation-handoff/shared/START-CODEX.md`

The shared supervisory behavioral contract is maintained at
`docs/process/conversation-handoff/shared/chatgpt-codex-behavioral-contract.md`.
Successor packages carry and hash it; do not recreate it in project guidance or
infer it from conversation history. Account-level custom instructions are only
the live convenience surface for the same policy.

Before a formal cycle, compare the shared-authority marker with the active
project's consumed marker. Refresh only when the canonical shared guidance
hash changed. If intake is unexecuted, preserve one exact agent-local stub and
stop; do not create a misleading empty cycle. An unchanged retry is blocked
until a material ticket revision or explicit `RETRY BLOCKED`.
