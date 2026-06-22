# Teachers.Net Codex Handoff

Teachers.Net is being rebuilt as a WordPress/DDEV project with custom plugins.

Core Terms is complete enough to serve as infrastructure. The existing repo/folder is still named `profilaxes`, but the visible product is Core Terms.

Jobs will be a separate plugin named `tnet-jobs`. Jobs depends on Core Terms, but Core Terms must not depend on Jobs.

Immediate objective:
Prepare entity model and schema planning for Teachers.Net Jobs. Do not code the Jobs plugin until entity ownership, relationships, and schema guardrails are accepted.

Current environment:
- Project root: /home/bobreap/projects/teachers-net-site
- Local URL: https://teachers-net.ddev.site
- DDEV WordPress project
- Docroot: wordpress
- Webserver: apache-fpm
- PHP: 8.4
- DB: MariaDB 11.8

Current custom plugin:
- wordpress/wp-content/plugins/profilaxes
- GitHub repo: https://github.com/BobSanDiego/profilaxes
- Status: Core Terms stabilized at v0.6.0

Future plugin:
- wordpress/wp-content/plugins/tnet-jobs
- Status: not created yet
- Purpose: jobs, employers, job lifecycle, apply flow, metrics, recruiter workflows

Primary handoff rule:
Terms classify. Jobs authorizes. WordPress authenticates.
