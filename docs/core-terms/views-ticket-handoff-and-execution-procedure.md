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

## Companion chat tail-read procedure

Locate the companion ChatGPT session by exact title and read only its newest
6–10 turns in newest-first order. Search that tail for the latest qualifying
fenced block whose first non-empty line is exactly `TICKET READY FOR CODEX`.
Only that block is executable authority; ignore surrounding commentary,
analysis, prior tickets, and `EXPECTED NEXT FIVE TICKETS`.

If the ticket is truncated or required context is missing, use the returned
cursor to read one older page at a time, stopping once the complete ticket,
required local documents, and explicitly required prior context are recovered.
Do not reread the full transcript unless the ticket remains incomplete, durable
authority cannot resolve required context, the user says project context
changed, a contradiction is found, or the ticket explicitly requires historical
reconstruction. If the complete ticket cannot be recovered, stop and report the
missing portion rather than guessing.

Record in the completion report the companion chat title, that tail-read was
performed, the number of turns/pages read, whether older context was required,
and any contradiction or missing context.

## Ticket execution

Extract the identifier, objective, scope, authority, runtime, route,
verification, Git requirements, exclusions, and stop boundary before editing.
ChatGPT owns product direction and sequencing; Codex owns inspection,
implementation, verification, Git, and completion artifacts.

Inspect narrowly and make the smallest coherent change. Preserve unrelated dirty
files, branches, runtime state, repository boundaries, and approved contracts.
Diagnostics do not authorize fixes unless the ticket says so.

## Disposable local QA fixture authority

For local DDEV verification, Codex may create, reset, use, and remove an
explicitly disposable Views QA draft when deterministic test state is required.
Use a clear name such as `DV-QA-*`, `TEST-*`, or `<ticket-id>-QA`. The default
tree fixture may include Grade Level → Early Childhood → Early Learners and
Grade Level → Elementary → Grade 1.

This permits draft-only term shuttling, removal, reset, autosave, lifecycle
checks, persistence inspection, and recreation. It does not permit mutation of
production data, published or editorial Views, another active test's fixture,
or Core Terms. A diagnostic may create/reset this fixture as test setup without
implementing the diagnosed correction.

Record the fixture name, View/version ID, intended state, operations, and final
cleanup/preservation state in the report. Missing disposable QA data alone is
not an engineer-input blocker; reserve that flag for human-only prerequisites.

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

## Completion report

Every cycle produces a status-first plain-text report containing the ticket and
status, objective result, changed files, source/ownership decision, affected
URLs, verification results, browser/runtime identity, screenshots/evidence,
`!important` disclosure when applicable, commit and push state, preserved
unrelated work, blockers or limitations, and the exact next implementation
boundary. A blocked report states what is missing, what was not changed, why
continuing would violate the stop boundary, and the smallest required
resolution.

## Required cycle artifacts

Every formal Views ticket produces a human-readable report and the validated
cycle artifact set unless the ticket explicitly waives reporting. A chat-only
completion message is not a substitute for publication into the formal Report
and Hopper directories.

The active cycle payload contains a final human report, evidence index or
evidence text, manifest, machine-readable cycle record, and an evidence ZIP when
multiple evidence files exist. The manifest records original path, hopper name,
status, purpose, size, SHA-256, committed state, and repository commit/push
state. The cycle record records project, ticket, cycle, status, branch, commit,
push, current hopper, archive, report/manifest/record/evidence filenames, and
artifact inventory. Validate before completion:

```bash
python3 tools/hopper/clean_cycle.py validate \
  --project views \
  --cycle YYMMDDHHMMSS
```

## ChatGPT handoff

After completion, ChatGPT reviews the Report Directory, reconciles sequencing,
and issues the next inline ticket. A handoff pointer may identify the latest
ticket, whether its complete body is present, prior-context need, required local
documents, stop boundary, authority manifest, and authority commit. Do not
require full companion-transcript rereading when the pointer and repository
authority resolve the next ticket.

## Final response

Lead with completed, blocked, or diagnostic-only status. State the key result,
commit/push status, verification limitations, full current-cycle file list,
WSL paths for Report, Hopper, and current, and the copyable Windows Explorer
command required by local handoff governance. Never hide blockers only in an
evidence ZIP or claim work that was not performed.
## Authenticated browser-QA bootstrap (GOV-VIEWS002)

### GOV-VIEWS003 self-healing recovery

Missing or closed QA tabs/windows are automatic-recovery conditions, not
engineer-action conditions. Before requesting intervention, perform one
bounded recovery sequence: verify DDEV and the isolated profile, inspect
profile-scoped Chrome state, terminate only stale processes belonging to
`C:\\Main\\Active\\Projects\\Teachers.Net\\tmp\\chrome-qa-profile` when
safe, relaunch the established profile, navigate the canonical Views URL, and
rerun the verifier once. Continue the ticket when the page is authenticated
and discoverable.

Request `ENGINEER ACTION REQUIRED` only for genuinely human-only prerequisites
such as credentials, MFA, CAPTCHA, browser permission, or physical desktop
interaction unavailable to automation. If bounded recovery fails for a
non-human tooling layer, report that exact tooling blocker rather than labeling
it an authentication requirement. Record `BROWSER SELF-HEALING: NOT NEEDED |
SUCCESS | FAILED`, recovered layers, human-authentication requirement, and
whether intervention was requested.

Authenticated Views browser verification is mandatory when a ticket requires
it. From the repository root, run the canonical preflight:

```bash
bash tools/qa/verify-views-browser-qa.sh
```

The preflight starts or reuses the isolated Windows Chrome profile at
`127.0.0.1:9222`, destroys any stale Views target, creates a fresh authenticated
canonical Views target, and runs real browser, target, runtime, DOM, and
screenshot commands from Windows-local Node. It returns `READY` only after the
document is complete, the Views editor is present, and a nonzero screenshot
has been written directly to WSL. `/json/version`, target discovery, or a
working WSL port proxy alone never establish readiness.

If the fresh-target command probe fails, the preflight verifies process
identity, force-restarts only the browser process rooted in the dedicated QA
profile, and retries the probe once. It refuses to attach to or terminate a
Chrome endpoint on port `9222` that cannot be matched to that profile. Normal
Chrome processes remain outside the recovery boundary.

The primary command path must remain Windows-local. Do not route canonical
readiness or direct-CDP acceptance through the WSL gateway or bridge port
`9223`. A bridge may remain available for compatibility with an external tool,
but bridge health is not a prerequisite and is not browser-QA evidence.

After preflight, reconcile the fresh target through the connected Chrome
DevTools MCP when MCP acceptance is required. Preflight `READY` proves browser
control and authenticated target health; it does not replace the
ticket-specific interaction assertions. If MCP is unavailable after its one
bounded reconnect, retain the required `UNAVAILABLE` classification and use
the direct fallback only for bounded engineering diagnosis.

The authoritative direct fallback is `tools/qa/run-views-browser-qa.mjs`,
invoked through Windows Node against Windows localhost:

```bash
node.exe "$(wslpath -w "$PWD/tools/qa/run-views-browser-qa.mjs")" http://127.0.0.1:9222
```

The helper closes its CDP session and socket deterministically and writes its
PNG through the repository's WSL UNC working directory. Its output is evidence
only when it reports page-control results, console/page errors, and a nonzero
WSL-local PNG. Screenshots must be confirmed nonzero and then collected into
the validated Views hopper; a Windows-reported path alone is not evidence.

If recovery or the command probe fails, report the exact failed layer. Use
`🚩 ENGINEER ACTION REQUIRED 🚩` only when the remaining step is genuinely
human-only, such as authentication, MFA, CAPTCHA, or physical desktop
interaction. Give the engineer the canonical URL, expected `READY` state, and
a clear immediate-resume condition.
