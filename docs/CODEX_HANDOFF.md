# Teachers.Net Codex Handoff

Teachers.Net is being rebuilt as a WordPress/DDEV project with custom plugins.

Core Terms is complete enough to serve as infrastructure. The existing repo/folder is still named `profilaxes`, but the visible product is Core Terms.

Jobs is a separate plugin named `tnet-jobs`. Jobs depends on Core Terms, but Core Terms must not depend on Jobs.

Immediate objective:
Continue Teachers.Net Jobs implementation from the published employer membership foundation milestone. The next code milestone is J11 — Job Repository.

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

Jobs plugin:
- wordpress/wp-content/plugins/tnet-jobs
- GitHub repo: git@github.com:BobSanDiego/tnet-jobs.git
- Status: published through `c9cb881 Add employer membership admin management`
- Tag: `v0.9.0-employer-membership-foundation`
- Purpose: jobs, employers, job lifecycle, apply flow, metrics, recruiter workflows
- Current foundation:
  - six MVP custom tables exist
  - dependency and schema health checks exist
  - event repository exists
  - employer repository exists
  - employer-user membership repository exists
  - employer-user membership service exists
  - admin UI can view, create, and deactivate employer memberships

Current Jobs boundaries:
- Jobs may later store Core Terms IDs in its own bridge table.
- Jobs must not write to Core Terms.
- Classification remains externalized through Core Terms.
- Billing/promotion, ATS/resumes/candidate search, applications/interviews/offers/hires, public frontend, and REST endpoints remain deferred.

Primary handoff rule:
Terms classify. Jobs authorizes. WordPress authenticates.
