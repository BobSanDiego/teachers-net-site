# Project Branch Isolation Contract v1

Teachers.Net workstreams own their Git branches. Community 3.0/TNET 3.0 uses
`COMMUNITY3-<ticket-family>` branches; Job Center uses `JOB-CENTER-<ticket-family>`
branches. Other projects use their own explicit project-owned prefix. A shared
cross-project branch is prohibited unless an integration ticket names both
owners, merge strategy, and stop boundary.

Before editing, Codex validates ticket family, active branch, project root,
working-tree state, and hopper slug. A mismatch stops before edits. Dirty work
is not switched, stashed, reset, rebased, or silently absorbed; it is preserved
in its existing worktree or an explicitly created recovery worktree.

Community payloads use the established Community slug `tnet-3.0`; Job Center
payloads use `jobcenter`. The cycle JSON, report, manifest, branch, ticket, and
artifact paths must identify the same workstream. Community validation rejects
Job Center implementation files, screenshots, tickets, and documentation.

The only exception is a separately authorized integration ticket. It must
explicitly permit the cross-project operation and define ownership, merge
direction, verification, and rollback before the preflight exception is used.
