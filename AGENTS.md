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

`docs/codex-ticket-discipline.md` also defines COMPONENT MATCH MODE for
high-fidelity matching of one existing UI component to an approved reference.
Use it only when explicitly entered with `Enter COMPONENT MATCH MODE: [component
name]`, and follow its `FINALIZE COMPONENT MATCH MODE` or `ABORT COMPONENT
MATCH MODE` lifecycle commands exactly.

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

Current launch blocker:
No global P0 runtime blocker is currently known. Do not begin V2 features until
V1 release-candidate status is explicitly declared or the Engineering Director
redirects.
