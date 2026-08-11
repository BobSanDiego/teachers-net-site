# START-CODEX — Teachers.Net Engineering Front Door

You are entering the shared Teachers.Net engineering workflow. This file is a
router, not a replacement for project authority.

## Read first

Read these canonical shared authorities:

1. `CHATGPT-ENGINEERING-OPERATING-CONTRACT.md`
2. `HANDOFF-LIFECYCLE.md`
3. `PROJECT-RECORD-SPEC.md`
4. `TRANSCRIPT-ARCHIVE-SPEC.md`
5. `REPORT-HOPPER-SPEC.md`

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
assumptions. For the explicit Engineering Director instruction
`bootstrap this project as directed` with a named project, run the shared
bounded onboarding path in `PROJECT-BOOTSTRAP-SPEC.md`. This authorizes only
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
- ChatGPT should keep complete formal tickets below 15,000 characters as a
  hard authoring/transport constraint for safe copying. This is a ChatGPT
  delivery rule only, not a Codex implementation limit; Codex must not reject
  an otherwise complete ticket solely because of character count.
- Report is the terminal human-review result; Hopper adds supporting evidence.
- Durable handoff payloads live in the shared HANDOFFS archive and are reported
  through validated receipts, not duplicated into Report/Hopper.
- Use `PREPARE HANDOFF` for the normal closing lifecycle.
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
