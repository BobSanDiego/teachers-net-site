# RUNTIME-BLOCKER001 — Community Runtime Authority Resolution

## Result

The canonical Community runtime is restored and isolated at:

- DDEV project: `teachers-net-community3`
- Filesystem path: `/home/bobreap/projects/teachers-net-community3`
- Branch: `COMMUNITY3-ui-working`
- HEAD: `b6a67149a84049754ed416b7d261155124d1b930`
- Review hostname: `https://teachers-net-community3.ddev.site`
- Mounted plugin path: `/var/www/html/wordpress/wp-content/plugins/tnet-community`
- Authoritative plugin path: `/home/bobreap/projects/teachers-net-community3/wordpress/wp-content/plugins/tnet-community`

## Root cause

The earlier review URL `teachers-net.ddev.site` belonged to the main
`teachers-net` DDEV project. Its plugin tree overlapped the Community entry
point but was not the complete Community3 tree. The Community3 worktree also
had a stopped DDEV service and an uninstalled database, so it could not serve
the intended routes independently.

## Exact correction

1. Started the dedicated `teachers-net-community3` DDEV project.
2. Assigned and adopted its non-colliding hostname:
   `teachers-net-community3.ddev.site`.
3. Imported the existing local `teachers-net` DDEV database into the isolated
   Community3 database only. Production was not accessed or modified.
4. Confirmed the active `tnet-community` plugin in the isolated WordPress
   installation.
5. Confirmed the authoritative and mounted plugin trees are an exact recursive
   hash match. The DDEV web mount exposes the Community3 worktree rather than a
   copied partial plugin.
6. Updated the Community runtime-authority preflight, Cursor, Handoff, and
   runtime authority map.

## Runtime inventory

Other local plugin copies exist in the main Teachers.Net checkout, Job Center
checkout, and historical hopper staging directories. They are not authoritative
for the Community3 hostname and are not mounted into the selected Community3
runtime. The three DDEV projects `teachers-net`, `teachers-net-community3`, and
`teachers-net-live` remain separate; only `teachers-net-community3` is the
canonical Community review runtime.

## URL verification

- Feed: `https://teachers-net-community3.ddev.site/community/` — HTTP 200.
- Topic Composer: `https://teachers-net-community3.ddev.site/community/new/` —
  unauthenticated requests redirect to the same host’s login page; authenticated
  local QA access renders the composer.
- Representative Thread:
  `https://teachers-net-community3.ddev.site/community/thread/post:8d59f528a2e11564/`
  — HTTP 200; authenticated local QA access exposes reply controls.

The supplied screenshot was found and read at
`/mnt/c/Main/Active/Projects/Teachers.Net/art/mockups/job center/Captures/Screenshot_3-8-2026_12156_teachers-net-community3.ddev.site.jpeg`.
It shows the Community3 Topic Composer at the corrected hostname. No prior
before-correction screenshot was available in the supplied materials, so a
before/after pair cannot be claimed.

## Stop boundary

Runtime authority is restored. No product behavior, UI, schema, route,
repository, publication logic, or production system was modified by this
blocker ticket. Community implementation remains paused until the authenticated
responsive evidence ticket is completed.
