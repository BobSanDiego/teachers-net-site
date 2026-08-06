This is a WordPress/DDEV project in WSL. Do not work from OneDrive or Windows UNC paths.

Owned repos:

- `wordpress/wp-content/plugins/profilaxes` = Core Terms dependency. Visible product name: Core Terms.
- `wordpress/wp-content/plugins/tnet-jobs` = Teachers.Net Jobs plugin.
- `wordpress/wp-content/themes/teachers-net` = future Teachers.Net theme.

Core rule:
Terms classify.
Jobs authorizes.
WordPress authenticates.

Project context rule:
Teachers.Net-specific facts, decisions, and Project Cursor state belong in this
repo's local docs. The global Engineering Director Playbook contains reusable
methodology only. Do not import workflow state, routes, branding, plugin
decisions, or product assumptions from other projects.

Documentation governance:

- Shared governance docs live in `docs/`.
- Project-specific docs live in project directories such as:
  - `docs/job-center/`
  - `docs/core-terms/`
  - `docs/membership-taxonomy/`
- Every active workstream should have its own Project Cursor and Engineering
  Handoff.
- Google Drive operational docs live under
  `Teachers.Net Engineering/Shared/` and
  `Teachers.Net Engineering/Projects/<Project Name>/`.
- Google Drive is for ChatGPT operational recovery only. Default startup reads
  the Engineering Director Playbook and active Engineering Handoff. Supporting
  governance may remain in Drive but is consulted only when needed. Drive
  should not mirror this repository.
- Local repository docs remain the durable engineering source for architecture,
  roadmaps, specifications, implementation details, and verification
  instructions.
- If the active project is unclear, ask which workstream is active before using
  Job Center, Core Terms, or Membership Taxonomy state.

Project-state lifecycle:

- Planning
- Active Development
- Stabilization
- Maintenance
- Archived

Each Project Cursor must declare one project state.

Core Terms vs Membership Taxonomy:

- Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.
- Membership Taxonomy is a curation, classification, and human-review
  workstream for legacy taxonomy.
- Membership Taxonomy is not a Core Terms rename or implementation ticket
  stream.

Do not add Jobs code to Core Terms.
Do not rename the `profilaxes` folder, CFM classes, `cfm` prefixes, DB tables, URLs, slugs, or namespaces unless explicitly instructed.
Do not edit third-party/vendor plugins.
Do not reset, prune, delete, rebuild, or uninstall Docker/DDEV/WordPress/plugin state unless explicitly instructed.

Before coding:

1. Read `docs/documentation-governance.md`.
2. Read `docs/codex-direction-manual.md`.
3. Read `docs/codex-ticket-discipline.md`.
4. Read `docs/plugin-architecture.md`.
5. Read `docs/decision-log.md`.
6. Read the active project's Project Cursor.
7. Read the active project's Engineering Handoff.
8. For Jobs tickets, also read the ticket-requested Jobs docs such as:
   - `wordpress/wp-content/plugins/tnet-jobs/docs/development-constitution.md`
   - `docs/job-center/project-cursor.md`
   - `docs/job-center/engineering-handoff.md`
   - `docs/job-center/jobs-roadmap.md`
   - `docs/job-center/product-definition-v1.md`
   - `docs/job-center/jc053-wizard-design-system-v1.md`
   - `docs/design-system-v1.md`
   - `docs/codex-ticket-discipline.md`

ChatGPT is responsible for product direction, UX guidance, architecture review,
prioritization, and planning. Codex is responsible for inspection,
implementation, verification, Git operations, and documentation updates.

Default workflow:
Inspect → plan → approve → implement → verify → commit → push.

`docs/codex-ticket-discipline.md` defines temporary VISUAL TUNE MODE for
Engineering Director/site-owner guided CSS/token tuning. Use it only when
explicitly entered with `Enter VISUAL TUNE MODE`, and follow its finalize or
abort lifecycle commands exactly.

For existing responsive or visual component changes, use the named Responsive
Convergence Procedure in `docs/codex-ticket-discipline.md`: perform a bounded
rendered-state preflight, correct the active governing rule, and require live
cache-bypassed browser evidence before commit. Do not repeat an unchanged
rendered attempt speculatively; diagnose the active authority first.

`docs/codex-ticket-discipline.md` also defines COMPONENT MATCH MODE for
high-fidelity matching of one existing UI component to an approved reference.
Use it only when explicitly entered with `Enter COMPONENT MATCH MODE: [component
name]`, and follow its `FINALIZE COMPONENT MATCH MODE` or `ABORT COMPONENT
MATCH MODE` lifecycle commands exactly.

`docs/codex-ticket-discipline.md` also defines TWEAK MODE for explicitly
prefaced, small bounded changes. TWEAK changes remain uncommitted and
unpushed until `Finalize` or `Roll back`; normal later instructions continue
under the standard workflow and must not be refused merely because pending
TWEAK changes exist.

When the user issues `PREPARE HANDOFF`, follow
`docs/codex-ticket-discipline.md`. Confirm the active project first, update
only that project's continuity set, update its Project Cursor only for durable
state changes, and end by outputting the concise project-aware ChatGPT startup
prompt. The prompt must reference:

- `Engineering Director Playbook v2` and its full canonical Google Docs URL
- `<Project Name> Engineering Handoff` and the full Google Docs URL recorded in
  that project's Project Cursor

Do not emit title-only Drive retrieval instructions in a handoff prompt.

Current next task:
Use the active project's Project Cursor and Engineering Handoff. Do not treat
Job Center V1 visual QA as the default task for Core Terms, Membership
Taxonomy, or future workstreams.

JC053 wizard authority:
Future wizard tickets must reuse the canonical primitives defined in
`docs/job-center/jc053-wizard-design-system-v1.md`, including the Wizard
Responsive Form Grid, Form Control with Trailing Icon, shared stepper, bottom
navigation, and Choice Card patterns. The Job Center roadmap and Engineering
Handoff must point to this authority; do not re-explain or fork those patterns
in step-specific guidance unless the content is genuinely unique.

Current launch blocker:
No global P0 runtime blocker is currently known. Do not begin V2 features until
V1 release-candidate status is explicitly declared or the Engineering Director
redirects.

## Project-Specific Clean-Cycle Hopper Procedure

The active Teachers.Net continuity artifacts use the canonical project hopper
`tmp/hopper/jobcenter/`, with active files only in `current/` and preserved
history only in `archive/`. Every ticket begins by archiving the prior
`current/` contents and generating one `YYMMDDHHMMSS` cycle identifier. Every
created or modified ticket artifact and required evidence is copied into the
current folder using `<base>-jobcenter-<cycle>.<extension>` filenames; the
cycle identifier precedes the extension and collisions fail rather than
overwrite.

Each cycle must contain a plain-text final report, a manifest with original
paths, statuses, sizes, SHA-256 hashes, commit/push state, and purpose, a
machine-readable JSON cycle record, and an evidence ZIP when multiple evidence
files exist. Validate that every reported artifact exists, is nonzero, and is
represented in the manifest and cycle record. Preserve blocked and incomplete
cycles; never delete historical content or unrelated dirty files. The
engineer-owned `output.txt` remains protected and is never edited, copied, or
archived by this procedure.

Every Codex ticket must begin by archiving the prior project-specific
`current/` hopper contents and must end with a validated, self-contained
current-cycle artifact set that the user can drag into ChatGPT in one
operation. A ticket is not complete until `current/` contains the report,
manifest, cycle record, every created or modified file, and required evidence.

Cycle flush rule: the beginning-of-cycle operation must flush every active
Views Report and Hopper directory by archiving its contents into the new
cycle's archive subdirectories before collecting new artifacts. Never merely
append to a prior cycle, and never delete the archive. The protected
`output.txt` exception remains in force.

Views report-publication requirement: at the end of every Views ticket cycle,
copy the final human-readable report and the generated `output-<cycle>.txt`
report into both formal report directories:

- `tmp/hopper/views/Report (Views)/`
- `tmp/hopper/views/Report (views)/`

The report must be present and nonzero in both directories before completion
is reported. The validated machine-readable cycle record, manifest, and copied
ticket artifacts remain in `tmp/hopper/views/Hopper (Views)/`; mirror the
cycle record, manifest, and report artifact into `Hopper (views)/` as well for
the user's established Windows/ChatGPT handoff path. The final response must
list both report directories, the Hopper directory, every current-cycle file,
the WSL paths, and the copyable Windows Explorer command.

Use `python3 tools/hopper/clean_cycle.py` for the deterministic initialization and
collection workflow. Final responses must print the full current-cycle file
list, WSL paths, and copyable Windows Explorer command:
`explorer.exe "<Windows path to current/>"`.

## Canonical Chrome MCP Recovery

When `127.0.0.1:9222` is unavailable, run the canonical launcher before
reporting browser verification blocked:

`tools/qa/launch-chrome-cdp-9222.ps1`

From Windows PowerShell:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File '\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\launch-chrome-cdp-9222.ps1'
```

It uses the dedicated profile
`C:\Main\Active\Projects\Teachers.Net\tmp\chrome-qa-profile`, launches the
installed Windows Chrome at CDP `http://127.0.0.1:9222`, and opens the JC053
workbench. Retain `chrome-devtools-mcp@1.6.0` with
`--browser-url=http://127.0.0.1:9222 --allow-unrestricted-paths
--no-usage-statistics`; verify `/json/version` and MCP inspection, then resume
the ticket. Do not use the normal Chrome profile, built-in browser bridge, or
obsolete WSL bridge. Stop only after the launcher itself fails its bounded
timeout.

Troubleshooting sequence when recovery is needed:

1. Check `127.0.0.1:9222/json/version` and call external MCP `list_pages`.
2. Inspect Windows Chrome command lines; do not terminate normal Chrome
   processes. A normal Chrome process without `--remote-debugging-port=9222`
   is not the QA instance.
3. Invoke the launcher through the WSL UNC path from Windows PowerShell; a
   `C:\home\...` path does not refer to the WSL repository. Use
   `\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\launch-chrome-cdp-9222.ps1`.
4. Treat the launcher output and external MCP as authoritative. WSL `curl`
   to Windows loopback may time out even when MCP can connect successfully.
5. After MCP reports a page, reload with cache bypass before responsive
   screenshots so the current workbench CSS is actually served.
