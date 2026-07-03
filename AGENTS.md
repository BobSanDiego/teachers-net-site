This is a WordPress/DDEV project in WSL. Do not work from OneDrive or Windows UNC paths.

Owned repos:

- `wordpress/wp-content/plugins/profilaxes` = Core Terms dependency. Visible product name: Core Terms.
- `wordpress/wp-content/plugins/tnet-jobs` = Teachers.Net Jobs plugin.
- `wordpress/wp-content/themes/teachers-net` = future Teachers.Net theme.

Core rule:
Terms classify.
Jobs authorizes.
WordPress authenticates.

Do not add Jobs code to Core Terms.
Do not rename the `profilaxes` folder, CFM classes, `cfm` prefixes, DB tables, URLs, slugs, or namespaces unless explicitly instructed.
Do not edit third-party/vendor plugins.
Do not reset, prune, delete, rebuild, or uninstall Docker/DDEV/WordPress/plugin state unless explicitly instructed.

Before coding:

1. Read `docs/CODEX_HANDOFF.md`.
2. Read `docs/current-cursor.md`.
3. Read `docs/plugin-architecture.md`.
4. Read `docs/decision-log.md`.
5. For Jobs tickets, also read the ticket-requested Jobs docs such as:
   - `wordpress/wp-content/plugins/tnet-jobs/docs/development-constitution.md`
   - `docs/jobs-roadmap.md`
   - `docs/v1-product-definition.md`
   - `docs/design-system-v1.md`
   - `docs/codex-ticket-discipline.md`

`docs/codex-ticket-discipline.md` defines temporary VISUAL TUNE MODE for
Engineering Director/site-owner guided CSS/token tuning. Use it only when
explicitly entered with `Enter VISUAL TUNE MODE`, and follow its finalize or
abort lifecycle commands exactly.

`docs/codex-ticket-discipline.md` also defines COMPONENT MATCH MODE for
high-fidelity matching of one existing UI component to an approved reference.
Use it only when explicitly entered with `Enter COMPONENT MATCH MODE: [component
name]`, and follow its `FINALIZE COMPONENT MATCH MODE` or `ABORT COMPONENT
MATCH MODE` lifecycle commands exactly.

Current next task:
V1 release-candidate readiness.

Start with:
Human visual QA, release-candidate declaration, and launch operations planning unless the Engineering Director opens a focused defect ticket.

Current launch blocker:
No P0 runtime blocker is currently known after the J127 readiness re-audit. Do not begin V2 features until V1 release-candidate status is explicitly declared or the Engineering Director redirects.
