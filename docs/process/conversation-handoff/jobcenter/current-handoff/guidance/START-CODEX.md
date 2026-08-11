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
assumptions. First inspect the repository identity, local governance, current
state, and available project documentation. Report the minimum facts needed to
propose a project record. Do not create a project record, modify code, publish
an authority, or write a handoff until the Engineering Director authorizes the
new-project onboarding boundary. After authorization, follow
`PROJECT-BOOTSTRAP-SPEC.md`; do not invent a parallel initialization process.

## Non-negotiable safeguards

- Never infer product approval, architecture approval, phase transition, or
  human acceptance.
- Tooling/path failure is not application failure; isolate the failing layer
  before changing product code.
- Formal tickets require `TICKET READY FOR CODEX`, one terminal objective,
  complete acceptance/scope/stop boundary, and a matching `END TICKET`.
- A complete formal Codex ticket MUST NOT exceed 15,000 characters. This is a
  hard validity constraint for every transport, not a target. Reject an
  oversized ticket; ChatGPT must compact it by referencing canonical guidance.
- Report is the terminal human-review result; Hopper adds supporting evidence.
- Durable handoff payloads live in the shared HANDOFFS archive and are reported
  through validated receipts, not duplicated into Report/Hopper.
- Use `PREPARE HANDOFF` for the normal closing lifecycle.
- Preserve unrelated dirty work and stage selectively.

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
