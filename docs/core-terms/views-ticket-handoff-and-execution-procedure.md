# Views Codex Ticket Handoff and Execution Procedure

This durable Views procedure supplements the shared Codex direction and ticket-
discipline documents.

## Authority and startup

Before implementation, verify the repository root, branch, and status:

```bash
git rev-parse --show-toplevel
git branch --show-current
git status --short
```

Confirm the canonical Views runtime, worktree, branch, and authority commit from
the active Project Cursor, Engineering Handoff, or Authority Manifest. Stop and
report any mismatch. Never switch branches or reset files to bypass preflight.

Use authority in this order: Authority Manifest, approved contracts, durable
governance, Project Cursor and Engineering Handoff, execution plans/roadmaps,
accepted implementation/evidence, then companion chat only for unresolved
context. Load only documents required by the active ticket. For routine handoff,
use the latest complete inline fenced `TICKET READY FOR CODEX` block; downloaded
text files are supporting artifacts unless explicitly made authoritative.

## Ticket execution

Extract the identifier, objective, scope, authority, runtime, route,
verification, Git requirements, exclusions, and stop boundary before editing.
ChatGPT owns product direction and sequencing; Codex owns inspection,
implementation, verification, Git, and completion artifacts.

Inspect narrowly and make the smallest coherent change. Preserve unrelated dirty
files, branches, runtime state, repository boundaries, and approved contracts.
Diagnostics do not authorize fixes unless the ticket says so.

## Views clean-cycle hopper

Every Views ticket starts a fresh cycle and archives all active report and hopper
contents without deleting historical archives:

```bash
python3 tools/hopper/clean_cycle.py begin --project views --cycle YYMMDDHHMMSS
```

Collect every created or modified artifact and required evidence with collision-
safe `<base>-views-<cycle>.<extension>` names. End with a nonzero human report,
manifest, machine-readable cycle record, required evidence, and validated
self-contained current-cycle payload.

Publish the final human report and `output-<cycle>.txt` to both
`tmp/hopper/views/Report (Views)/` and `tmp/hopper/views/Report (views)/`.
Keep the validated record, manifest, copied artifacts, and evidence in
`Hopper (Views)` and mirror the record, manifest, and report artifact in
`Hopper (views)`. Protected `output.txt` remains untouched.

## Verification and Git

Browser work requires the canonical review URL, cache-bypassed reload, runtime
identity and loaded-asset confirmation, exact affected states/viewports,
console inspection, relevant network inspection, and screenshots when required.
Source, lint, HTTP status, and automated assertions do not replace live browser
evidence or human visual QA.

Before commit, run `git diff --check`, `git diff --cached --check`, and inspect
the staged file list. Stage only authorized files, commit only the repository
that changed, push the required branch, and report repository, branch, commit,
upstream, push result, and unrelated remaining status. Never claim a commit or
push until the command succeeds.
