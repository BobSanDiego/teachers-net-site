# Dedicated Community DDEV HTTP 502 Diagnosis

## Primary root cause

The dedicated DDEV web backend was not serving a valid WordPress docroot. The
persistent Community worktree initially contained only ignored WordPress
configuration and plugin/source files; `wordpress/index.php` and the remaining
ignored WordPress runtime were absent. DDEV explicitly reported that
`wordpress/index.*` did not exist during startup. With no WordPress bootstrap at
the configured docroot, the dedicated hostname could not serve the application
and returned HTTP 502 while the project was unhealthy.

## Evidence

- `ddev describe` initially showed a project whose docroot lacked
  `wordpress/index.php`.
- The dedicated web/db containers were not stable during the first start/restart
  sequence.
- After restoring the ignored local WordPress runtime from the existing local
  development tree, excluding uploads and preserving the Community plugin,
  Docker reported both `ddev-teachers-net-community3-web` and
  `ddev-teachers-net-community3-db` as healthy/running, with zero restarts and
  no OOM termination.
- Apache and PHP-FPM logs show Apache resumed normally and PHP-FPM reached
  `ready to handle connections`.
- `curl -k -I https://teachers-net-community3.ddev.site/` now returns HTTP 302
  to `/wp-admin/install.php`, proving the router/backend path is live.
- `ddev wp core version` succeeds and reports WordPress 7.0.1.

## Remaining boundary

The dedicated database has not been installed: `ddev wp option get home` and
`siteurl` correctly report that the site is not installed. No database import,
URL update, plugin activation, schema installation, rewrite flush, or product
QA was performed in this diagnostic ticket. The original `teachers-net`
runtime/database and production were untouched.

## Resolution state

The HTTP 502 is resolved at the infrastructure/bootstrap layer. C3-OPS001 is
unblocked for its next bounded step: initialize or import a local development
database in the dedicated project, then verify the Community runtime. That
step must remain separate from production and from the original DDEV project.
