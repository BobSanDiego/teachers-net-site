# ChatGPT ↔ Codex Workflow Protocol v1

## Purpose

ChatGPT owns architecture, product direction, prioritization, and acceptance
definition. Codex owns repository inspection, bounded implementation,
verification, Git operations, documentation updates, and forensic evidence.

## Formal ticket boundary

Only a block beginning with `TICKET READY FOR CODEX` is executable. Discussion,
drafts, recommendations, and architectural exploration are context, not
authorization. A formal ticket must identify Ticket, Mode, Objective, Authority,
Verification, and Stop boundary. Codex must preserve the source ticket in the
cycle Hopper.

## Cycle artifacts

The human-review payload is `tmp/hopper/<project>/Report (Project Name)/`.
The complete forensic record is `tmp/hopper/<project>/Hopper (Project Name)/`.
Both are archived together under a timestamped directory before each cycle.

The Report always contains `ARCHITECT-REPORT.txt`, `completion-report.txt`,
`COMMAND-RESULT.txt`, `manifest-summary.txt`, and `NEXT-STEP.txt`.
When authority or execution architecture changes, it also contains
`ARCHITECTURE-DELTA.md` and the changed authority documents.

The Hopper contains the source ticket, reports, manifests, cycle record, Git
evidence, changed files, tests, logs, screenshots, diagnostics, schema
evidence, and authority documents. Nothing is discarded from a cycle.

## Execution commands

The authoritative command registry is
`tools/workflow/command-registry.json`. `python3 tools/workflow/workflow.py
list-commands` renders it. `SHOW NEXT` is inspection-only; `EXECUTE` is the
only command that authorizes ticket work, and it still obeys the ticket's stop
boundary and repository instructions.

Duplicate execution is prevented by the local ledger at
`tmp/hopper/<project>/workflow-ledger.json`, using ticket ID, cycle, commit,
status, supersession, and conversation fingerprint when available.

## Architecture refresh

At cycle completion, Codex asks whether the ticket materially changed
architecture or execution sequence. If yes, update the relevant Cursor,
Engineering Handoff, Roadmap, and Decision Log and create
`ARCHITECTURE-DELTA.md`. If no, state `No architectural changes this cycle.`

## Human review

When human review is required, `ARCHITECT-REPORT.txt` begins with
`ENGINEER ACTION REQUIRED` and gives the exact review URL, required action,
expected result, and next command after approval.

## Safety

The protocol never authorizes application code, schema, runtime, repository, or
business-logic changes by itself. Each ticket's scope, authority, exclusions,
verification, commit/push instruction, and stop boundary remain controlling.
