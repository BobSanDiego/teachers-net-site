This is a WordPress/DDEV project in WSL. Do not work from OneDrive or Windows UNC paths.

Owned repos:

- wordpress/wp-content/plugins/profilaxes = Core Terms dependency. Visible product name: Core Terms.
- wordpress/wp-content/plugins/tnet-jobs = future Jobs plugin. Not yet implemented.
- wordpress/wp-content/themes/teachers-net = future theme.

Core rule:
Terms classify.
Jobs authorizes.
WordPress authenticates.

Do not add Jobs code to Core Terms.
Do not rename profilaxes folder, CFM classes, cfm prefixes, DB tables, URLs, slugs, or namespaces unless explicitly instructed.
Do not edit third-party/vendor plugins.
Do not reset, prune, delete, rebuild, or uninstall Docker/DDEV/WordPress/plugin state unless explicitly instructed.

Before coding:

1. Read docs/CODEX_HANDOFF.md
2. Read docs/current-cursor.md
3. Read docs/plugin-architecture.md
4. Read docs/decision-log.md

Current next task:
J1 — Jobs Entity Model v0.1. No code yet.
