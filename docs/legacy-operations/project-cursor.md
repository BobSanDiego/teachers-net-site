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

`TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001` remains blocked at the
shared-wrapper security boundary. Director authority authorized controlled
recovery and credential separation, but read-only Sandy inspection established
that the required `newwrapper.cgi`/`newwrapper.pm` pair also serves additional
active legacy route families and its present database principal spans multiple
application databases. A Lessons-only least-privilege replacement cannot be
safely determined without either changing shared behavior, changing Apache
routing, or expanding credential authority. No source recovery, credential,
secret-source, or production patch occurred.

## Known residual risks

- Netdata cost, disk history, retention, and unique operational value are not
  yet rationalized.
- Permanent traffic-admission and crawler policy are not yet designed; prior
  emergency controls are historical containment, not a standing policy.
- Configuration provenance and root/storage lifecycle need an operations audit.
- `get_distinct_topics()` remains a known legacy performance risk.

## Next recommended objective

Choose one of two explicit directions: authorize a full shared-wrapper
security/source-ownership effort covering every active consumer and the shared
database principal, or authorize a separately routed Lessons-specific renderer
(which requires Apache/routing design authority). Do not create a
Lessons-only credential against the existing shared wrapper by assumption.
