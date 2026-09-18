# Teachers.Net Legacy Operations — Project Cursor

## Identity and lifecycle

- Project ID: `legacy-operations`
- Display name: Teachers.Net Legacy Operations
- Project state: Stabilization
- Governed production/source worktree: `/home/bobreap/projects/teachers-net-legacy`
- Continuity/governance repository: `/home/bobreap/projects/teachers-net-site`
- Workflow: Teachers.Net Engineering Workflow V2

This project owns Sandy production operations and the legacy runtime through
safe retirement/cutover. It excludes Community 3.0, Job Center,
Profile/onboarding, Views, and their registered authorities.

## Registration boundary

Formal Workflow V2 governance begins with
`TNET-LEGACY-OPS-WORKFLOW-BOOTSTRAP-001`. The pre-registration Sandy incident,
recovery, backup/restore, and cleanup work remains inherited evidence, not a
retroactive formal cycle. The baseline is
[inception-baseline.md](inception-baseline.md).

## Current operational position

The accepted incident repairs, recovery-safety policy, daily off-host backup,
weekly recovery, and isolated restore rehearsal are source-addressable in the
legacy repository. Current read-only Sandy verification on 2026-09-18 showed
the cleaned root filesystem at about 81% use, healthy Apache/MySQL/Monit and
daily-backup monitoring, and Netdata still active and untouched.

Current cloud retained/deleted-resource verification is pending authenticated
`tnet-prod` access. This does not reopen the pre-registration cleanup or
authorize a repeat deletion.

`TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001` is blocked at owner
recovery. Read-only Sandy evidence on 2026-09-18 established that `/lessons/`
and `/lessonplans/` are served through the active Apache legacy route from
`/var/www/htdocs/lessons` (with `/lessonplans` a symlink) and rendered by
`/var/www/cgi-bin/newwrapper.pm`. Those deployed paths are not Git worktrees
and are absent from the registered source repositories. No production patch is
authorized until one source-controlled deployment owner is registered.

## Known residual risks

- Netdata cost, disk history, retention, and unique operational value are not
  yet rationalized.
- Permanent traffic-admission and crawler policy are not yet designed; prior
  emergency controls are historical containment, not a standing policy.
- Configuration provenance and root/storage lifecycle need an operations audit.
- `get_distinct_topics()` remains a known legacy performance risk.

## Next recommended objective

Resolve source-control and deployment authority for the live legacy
Lessons/Lessonplans renderer, beginning with the concrete Sandy paths recorded
above. Once that owner is registered, resume the blocked mobile ad-delivery
convergence cycle; do not edit the live untracked files directly.
