# Community Dedicated DDEV Runtime v1

The Community implementation is owned by the persistent worktree
`/home/bobreap/projects/teachers-net-community3` on branch
`COMMUNITY3-ui-working`. Its DDEV project is `teachers-net-community3`, with
review hostname `https://teachers-net-community3.ddev.site`.

The runtime is intentionally separate from `teachers-net` and from the mixed
recovery workspace. The ignored WordPress runtime files were copied locally
from the original development tree only because the persistent worktree
contains repository-owned plugin/docs files but not the ignored WordPress core
needed for DDEV's `wordpress` docroot. The Community plugin directory was
preserved from this branch and was not overwritten.

The C3-OPS002-DIAG001 diagnosis found that the initial persistent worktree
lacked the ignored WordPress bootstrap/runtime at the configured docroot. After
restoring that local runtime, the dedicated web and database containers remain
healthy and the hostname returns a WordPress installation redirect rather than
HTTP 502. The dedicated database is still uninstalled; no database import, URL
update, activation, schema install, rewrite flush, or browser QA has been
performed. The original `teachers-net` runtime and database were not stopped or
modified.

Recovery must continue in this worktree. First establish healthy `ddev status`
for `teachers-net-community3`; then inspect the dedicated database, set only
its local URL values if needed, activate `tnet-community`, install its local
prototype tables, flush rewrites once, and perform the C3-UI003 browser QA.
