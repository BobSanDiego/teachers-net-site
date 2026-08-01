# Community / Job Center Branch Recovery v1

## Recovery completed

The mixed branch `COMMUNITY003-semantic-community-communications-working-draft`
was left untouched. It contains Community history and Job Center commits,
including `4856a4b JC053 STEP003 add authoring workspace preview`; it is not a
valid workstream branch for new tickets.

A clean Community worktree was created from verified Community milestone
`621708b`:

* branch: `COMMUNITY3-ui-working`
* worktree: `/tmp/community3-ui-working`
* upstream: to be published normally after the focused process commit

The existing Job Center recovery worktree remains separate at
`/tmp/jobcenter-ops-git-x001` on `JOB-CENTER-JC053-wizard-workbench`, with its
JC053 history and current work preserved. No reset, clean, rebase, force-push,
branch deletion, or history rewrite was used.

## Operating rule

Community tickets must use the clean Community worktree and `COMMUNITY3-*`
branch family. Job Center tickets must use the Job Center worktree and
`JOB-CENTER-*` branch family. The preflight and payload validators are the
fail-closed checks. C3-UI003 is unblocked only when Codex starts from
`/tmp/community3-ui-working` on `COMMUNITY3-ui-working`; it is not implemented
by this recovery ticket.
