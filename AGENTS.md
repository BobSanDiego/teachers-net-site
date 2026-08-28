This is a WordPress/DDEV project in WSL. Do not work from OneDrive or Windows UNC paths.

Owned repos:

- `wordpress/wp-content/plugins/profilaxes` = Core Terms dependency. Visible product name: Core Terms.
- `wordpress/wp-content/plugins/tnet-jobs` = Teachers.Net Jobs plugin.
- `wordpress/wp-content/themes/teachers-net` = future Teachers.Net theme.

Core rule:
Terms classify.
Jobs authorizes.
WordPress authenticates.

Shared workflow rule: resolve
`docs/process/conversation-handoff/shared/workflow-v2.json` and
`WORKFLOW-V2.md`. The exact user command `BOOTSTRAP` is the stable shared
reconciliation entry and never authorizes product implementation. Before a
formal cycle mutates Report/Hopper or begins repository/browser work, run the
Workflow V2 T+0 ticket preflight. Keep one terminal objective open through
causally related blockers and convergence.

Project context rule:
Teachers.Net-specific facts, decisions, and Project Cursor state belong in this
repo's local docs. The global Engineering Director Playbook contains reusable
methodology only. Do not import workflow state, routes, branding, plugin
decisions, or product assumptions from other projects.

Community VS Code execution context:
For formal Community tickets, open `Community-VSCode.code-workspace` from this
control-plane repository. Keep `teachers-net-community3` as the registered
source folder and `teachers-net-live` as a runtime mirror only. A runtime-only
workspace is not a valid formal execution context because it cannot discover
the central Workflow V2 tools, records, or Report/Hopper owner. A Community
ticket must not be reported terminally complete until `clean_cycle.py finalize`
and `clean_cycle.py validate` have both succeeded and updated the current
Report/Hopper state.

Repository continuity and inspection rule: accepted authority manifests,
contracts, governance, roadmaps, execution plans, and verified implementation
facts remain valid unless the current ticket requires re-audit or direct
repository evidence contradicts them. Inspect only the directly affected
files, routes, services, repositories, components, documents, and dependencies.
Do not perform broad archaeology or reread/re-ingest companion chat merely to
rediscover settled facts. If direct evidence contradicts accepted authority,
stop and report the contradiction. Repository authority order is: Authority
Manifest, approved contracts, governance, execution plans/roadmaps, accepted
implementation, then companion chat for unresolved context only.

Project startup worktree preflight is mandatory before implementation. For
Community, the intended worktree is `/home/bobreap/projects/teachers-net-community3`.
For JC053 production UI work, use the repository containing the production
`tnet-jobs` source at `/home/bobreap/projects/teachers-net-site`. The former
standalone Job Center workbench at `/home/bobreap/projects/teachers-net-jobcenter`
is archived reference only. Verify the current working directory/worktree
against the requested project first. If it does not match, stop and report the
mismatch.

Documentation governance:

- Shared governance docs live in `docs/`.
- Project-specific docs live in project directories such as:
  - `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/`
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
   - `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/project-cursor.md`
   - `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/engineering-handoff.md`
   - `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/jobs-roadmap.md`
   - `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/product-definition-v1.md`
   - `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/jc053-wizard-design-system-v1.md`
   - `docs/design-system-v1.md`
   - `docs/codex-ticket-discipline.md`

ChatGPT is responsible for product direction, UX guidance, architecture review,
prioritization, and planning. Codex is responsible for inspection,
implementation, verification, Git operations, and documentation updates.

Default workflow:
Inspect → plan → approve → implement → verify → commit → push.

Ticket delivery/reporting authority:

- For Codex Desktop, ChatGPT issues executable engineering tickets as inline
  fenced code blocks in the active conversation. Downloadable `.txt` tickets
  are supporting artifacts, not the default delivery format.
- ChatGPT owns product direction, review commentary, and next-ticket sequence.
  Codex owns implementation, verification, Git, and the status-first
  completion report plus Report/Hopper artifacts.
- `CURRENT CYCLE CHANGE` and `EXPECTED NEXT FIVE TICKETS` belong to ChatGPT's
  review/handoff post and are not required in Codex reports unless explicitly
  required by the active ticket.
- Job Center runtime asset migration is a prerequisite after runtime-parity
  diagnostics and before `JC053-STEP1-ADD-SCHOOL-JOBSITE-INTEGRATION`.

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
`wordpress/wp-content/plugins/tnet-jobs/docs/job-center/jc053-wizard-design-system-v1.md`, including the Wizard
Responsive Form Grid, Form Control with Trailing Icon, shared stepper, bottom
navigation, and Choice Card patterns. The Job Center roadmap and Engineering
Handoff must point to this authority; do not re-explain or fork those patterns
in step-specific guidance unless the content is genuinely unique.

Current launch blocker:
No global P0 runtime blocker is currently known. Do not begin V2 features until
V1 release-candidate status is explicitly declared or the Engineering Director
redirects.

## Project-Specific Clean-Cycle Hopper Procedure

Workflow V2 controls every formal project cycle. Before rotation, run the T+0
preflight through `tools/hopper/clean_cycle.py begin` with `--ticket` and
`--ticket-source`. The helper resolves Report/Hopper paths and compatibility
aliases from the registered project record, archives every active directory,
preserves `output.txt`, and refuses malformed tickets before mutation.

Finalize once through `tools/hopper/clean_cycle.py finalize`, then validate.
Every mode contains the terminal report, manifest, cycle JSON, and source
ticket. Add only decisive evidence at the mode's V2 report tier. Do not copy
complete committed source automatically; record Git commit/blob identity
unless source is uncommitted, generated/external, not Git-addressable, or
explicitly required. Preserve blocked/incomplete cycles and unrelated dirty
work; never delete archive history.

Final responses list the project-record-resolved Report/Hopper directories,
current-cycle files, WSL paths, and a copyable Windows Explorer command.

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
