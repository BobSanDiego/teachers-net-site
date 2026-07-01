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
   - `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-roadmap.md`
   - `docs/v1-product-definition.md`
   - `docs/design-system-v1.md`
   - `docs/codex-ticket-discipline.md`

Current next task:
Job Alerts MVP implementation sequence.

Start with:
J117 - Job Alerts Schema + Service Foundation.

Current launch blocker:
Job Alerts are not implemented yet. Do not begin V2 features until the V1 alert path is complete or explicitly deferred by the Engineering Director.
