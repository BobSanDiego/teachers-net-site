# ChatGPT ↔ Codex Workflow Protocol v1

Status: HISTORICAL / SUPERSEDED

Canonical replacement: `docs/process/conversation-handoff/shared/WORKFLOW-V2.md`
and machine manifest `workflow-v2.json`. This file remains only as historical
context and is not an active execution owner.

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

Report is the complete human-reviewable terminal deliverable set. It contains
the source ticket, status-first report, manifest, cycle record, and every
primary terminal artifact classified `REPORT_REQUIRED`. A summary or manifest
never substitutes for the actual terminal artifact.

The Hopper contains the source ticket, reports, manifests, cycle record, Git
evidence, changed files, tests, logs, screenshots, diagnostics, schema
evidence, and authority documents. Nothing is discarded from a cycle.

The semantic relationship is `Report ⊆ Hopper`: Hopper contains everything in
Report plus supporting evidence. Use `HOPPER_SUPPORTING` for diagnostics,
tests, logs, tooling, and reproduction material. Use explicit dispositions for
`LOCAL_ONLY`, `SENSITIVE_DO_NOT_PACKAGE`, or
`OVERSIZED_EXTERNAL_REFERENCE`; never silently omit a terminal artifact.

## Execution commands

The authoritative command registry is
`tools/workflow/command-registry.json`. `python3 tools/workflow/workflow.py
list-commands` renders it. `SHOW NEXT` is inspection-only; `EXECUTE` is the
only command that authorizes ticket work, and it still obeys the ticket's stop
boundary and repository instructions.

Conversation refresh is an internal Codex lifecycle action. Before `EXECUTE
NEXT`, `EXECUTE <ticket>`, `EXECUTE ALL PENDING`, `SHOW QUEUE`, or `SHOW NEXT`,
Codex refreshes the current conversation, rebuilds formal tickets, compares
them with the ledger, and reports new, superseded, duplicate, completed, and
pending tickets. If refresh fails, Codex stops without executing.

Refresh failure must distinguish a connection problem from a ticket-format
problem. When a companion ChatGPT title is expected, Codex first lists available
threads/chats and verifies the target exists as a ChatGPT-backed conversation,
not merely a similarly named local Codex transcript. If the ChatGPT source is
missing or unavailable, Codex reports the exact connection evidence and does not
ask for pasted ticket text until that bounded troubleshooting pass is complete.
If the ChatGPT source is readable but the latest block is malformed or
incomplete, Codex reports the ticket-format defect separately.

`SHOW REPORT` lists only the current human-review directory. `LIST COMMANDS`
distinguishes user commands, ChatGPT ticket markers, and internal lifecycle
actions, with examples.

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
