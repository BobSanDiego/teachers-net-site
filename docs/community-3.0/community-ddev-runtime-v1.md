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

At the C3-OPS001 checkpoint, DDEV configuration exists and the dedicated
containers were created, but the web/db services did not remain healthy after
the runtime copy and the dedicated hostname returned HTTP 502. No database
import, URL update, activation, schema install, rewrite flush, or browser QA
was performed. The original `teachers-net` runtime and database were not
stopped or modified.

Recovery must continue in this worktree. First establish healthy `ddev status`
for `teachers-net-community3`; then inspect the dedicated database, set only
its local URL values if needed, activate `tnet-community`, install its local
prototype tables, flush rewrites once, and perform the C3-UI003 browser QA.
