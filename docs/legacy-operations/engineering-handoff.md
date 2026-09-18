# Teachers.Net Legacy Operations — Engineering Handoff

## Startup authority

Start from the registered `legacy-operations` project record, then read the
Project Cursor, this handoff, the inception baseline, and the governed
legacy-source recovery policy. Historical chats and the prior Codex session
are evidence only; they are not required for startup and do not override the
current repository, production, or AWS state.

## Worktree and routing

- Production/source worktree: `/home/bobreap/projects/teachers-net-legacy`
- Governance/continuity root: `/home/bobreap/projects/teachers-net-site`
- Primary Report: `tmp/hopper/legacy-operations/Report (Legacy Operations)`
- Primary Hopper: `tmp/hopper/legacy-operations/Hopper (Legacy Operations)`
- Archive: `tmp/hopper/legacy-operations/archive`
- Production access: use WSL `ssh sandy`; do not use Windows-native SSH alias
  assumptions.

Preserve unrelated dirty work in the governance repository and selectively
stage only Legacy Operations changes. The legacy source worktree must be
identified and checked before implementation or deployment.

## Inherited accepted evidence

The pre-registration Sandy work is captured in
[inception-baseline.md](inception-baseline.md). It establishes accepted
performance repairs, evidence-preserving recovery behavior, daily and weekly
recovery paths, a restore rehearsal, completed local cleanup, protected
resources, and known residual risks. Do not rerun an accepted cleanup merely
to generate a new workflow record.

## Canonical future ticket form

Workflow V2 validation currently accepts this exact header form:

```text
TICKET READY FOR CODEX
Ticket: <TICKET-ID>

Mode: STANDARD
OWNER: <explicit workstream owner>

OUTCOME
<terminal outcome>

STOP BOUNDARY
<real authority or risk boundary>

END TICKET — <TICKET-ID>
```

`Ticket:` and single-line `OWNER:` are required by the current validator.
This is a ticket-transport rule, not a license to normalize a malformed ticket
silently.

## Current verification boundary

AWS verification of the pre-registration cleanup remains read-only and is
pending if the `tnet-prod` session is expired. Reauthenticate before querying
AWS; do not use authentication failure to infer deletion, retention, or cost.

## Current Lessons/Lessonplans delivery boundary

`TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001` stopped before a repair.
The active TLS vhost `/etc/apache2/sites-enabled/000-teachers.net-ssl.conf`
maps the legacy lesson family through its `/var/www/htdocs/lesson*` directory
rule; `/var/www/htdocs/lessonplans` is a symlink to `lessons`. Read-only live
response correlation proves the emitted GPT definitions and AdSense slot
`1692171583` come from `/var/www/cgi-bin/newwrapper.pm` (not the checked-in
WordPress theme or `lessonz.pm`). `/var/www/htdocs`, `lessons`, and `cgi-bin`
are not Git worktrees and this renderer is absent from both registered source
repositories. Director authority subsequently named `teachers-net-legacy` as
the canonical recovery owner, but the required `/var/www/cgi-bin/newwrapper.pm`
contains embedded live database credentials. The ticket prohibits importing
secrets, so the current stop boundary is credential separation—not source-owner
identity. Do not copy, redact-in-place, rotate, or deploy this renderer until a
source-safe configuration interface and production secret-management/deployment
boundary are explicitly authorized.

## Next operations objective

Decide and authorize credential separation for the live legacy
Lessons/Lessonplans renderer, including secret rotation and rollback policy.
Then resume `TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001` with its
existing PROVEN mobile failure evidence; do not re-audit unrelated Sandy
operations or edit the untracked deployed paths directly.
