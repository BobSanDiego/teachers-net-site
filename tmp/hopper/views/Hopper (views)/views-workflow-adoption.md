# Views Workflow Adoption

Status: Adopted by `WORKFLOW-ADOPTION001`  
Project: Views  
Mode: DOCUMENT

The Views project uses the standardized ChatGPT ↔ Codex workflow through the
project-aware helper:

```text
python3 tools/workflow/workflow.py --project views LIST COMMANDS
python3 tools/workflow/workflow.py --project views WORKFLOW STATUS
```

Only text beginning with `TICKET READY FOR CODEX` is a formal executable ticket.
Before queue-related commands, Codex internally refreshes the current
conversation, rebuilds the formal queue, compares it to
`tmp/hopper/views/workflow-ledger.json`, and detects new, superseded,
duplicate, completed, and pending tickets. The user does not need to request
the refresh.

Views maintains two current working directories:

- `tmp/hopper/views/Report (Views)/` — human-review payload;
- `tmp/hopper/views/Hopper (Views)/` — complete forensic payload.

Before each cycle, both are archived together under
`tmp/hopper/views/archive/<cycle>/` and recreated empty. The Report contains
`ARCHITECT-REPORT.txt`, `ARCHITECTURE-DELTA.md`, `completion-report.txt`,
`COMMAND-RESULT.txt`, `EVIDENCE-INDEX.txt`, and `NEXT-STEP.txt`; authority
documents changed during a cycle are added automatically. The Hopper contains
the source ticket, reports, manifests, cycle record, Git evidence, diagnostics,
browser evidence, screenshots, logs, tests, and implementation artifacts.

This workflow adoption changes no Durable Views architecture, schema, UI,
service, business logic, or product data. Codex remains responsible for
inspection, bounded implementation, verification, Git, documentation, and
forensic packaging. ChatGPT remains responsible for architecture, product
direction, sequencing, governance, acceptance criteria, and review.
