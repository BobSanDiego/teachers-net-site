# Project Ticket Preflight Guard v1

Run from the intended clean project worktree before editing:

```bash
python3 tools/community3/project_ticket_preflight.py \
  --project community --ticket C3-UI003 --hopper tnet-3.0
```

The guard rejects a non-Community branch, a non-C3 ticket, a mismatched hopper,
an integration flag without a separately authorized integration ticket, or any
dirty working tree. For Job Center use `--project jobcenter` and its
`JOB-CENTER-` branch/hopper contract. The guard is fail-closed; it does not
switch branches or modify files.

After a Community cycle is assembled, run:

```bash
python3 tools/community3/validate_hopper_payload.py <cycle-json>
```

This rejects non-`tnet-3.0` projects, non-C3 tickets, non-Community branches,
and artifact paths or names that identify Job Center work. A rejected payload
must be repaired or discarded as an incomplete cycle; it must never be
reported as a completed Community handoff.
