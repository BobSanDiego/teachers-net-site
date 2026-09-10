# WORKFLOW-ADOPTION001 — Community Formal-Ticket Workflow

Status: adopted

This document defines the Community implementation of the standardized
ChatGPT-to-Codex workflow. It governs formal workflow artifacts only and does
not authorize product, architecture, schema, UI, service, or business-logic
changes.

## Formal ticket gate

Only files containing the exact line `TICKET READY FOR CODEX` and the required
fields `Ticket`, `Mode`, `Project`, `Repository`, `Objective`, `Authority`,
`Verification`, and `Stop boundary` enter the queue. Discussion, drafts,
recommendations, and incomplete ideas are ignored.

## Queue and ledger

Before every queue command, the workflow performs an internal conversation
refresh marker, rebuilds the formal queue, compares it with
`tmp/workflow/community/execution-ledger.json`, and excludes completed,
blocked, superseded, and duplicate tickets. `SHOW NEXT` and `EXECUTE NEXT`
select the oldest remaining formal ticket.

The command implementation is `tools/community3/workflow_commands.py`.

## Dual-cycle evidence

Every cycle archives both `tmp/workflow/community/report/current/` and
`tmp/workflow/community/hopper/current/` into one UTC cycle directory, then
recreates both empty current directories. The report current directory contains
the six required report files; the hopper current directory contains the
complete forensic set and its manifest/cycle record.

## Command registry

Supported commands are: `EXECUTE NEXT`, `EXECUTE <ticket>`, `EXECUTE ALL
PENDING`, `SHOW QUEUE`, `SHOW NEXT`, `SHOW REPORT`, `SHOW HOPPER INDEX`, `RETRY
BLOCKED <ticket>`, `ARCHIVE CURRENT`, `WORKFLOW STATUS`, `VALIDATE WORKFLOW`,
and `LIST COMMANDS`. Commands are executed with:

`python3 tools/community3/workflow_commands.py <command words>`

Conversation refresh is an internal lifecycle action recorded in
`tmp/workflow/community/conversation-refresh.json`; it is never delegated to
the user.
