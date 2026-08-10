# Job Center Formal Ticket Cycle Invariant

Every completed formal Job Center `TICKET READY FOR CODEX` objective produces
one normal Report/Hopper cycle, whether or not the ticket creates tracked file
changes, a Git commit, or a push.

The lifecycle dimensions are independent:

```text
FORMAL TICKET
     ↓
ONE REPORT/HOPPER CYCLE — ALWAYS
     ↓
GIT COMMIT — OPTIONAL
     ↓
GIT PUSH — OPTIONAL
```

Report/Hopper publication is mandatory for formal ticket completion. A ticket
must not skip the current Report/Hopper package merely because it is diagnostic,
read-only, temporary-artifact-only, no-change, no-commit, no-push, or explicitly
prohibits repository mutation.

Do not create a fake Git change solely to satisfy packaging. A completed
no-commit ticket is represented as an ordinary completed cycle with explicit Git
disposition:

```yaml
status: complete
git_disposition: NOT_APPLICABLE
commit: null
push: null
```

Committed cycles use:

```yaml
git_disposition: COMMITTED_PUSHED
```

or, where a ticket legitimately permits a local commit without push:

```yaml
git_disposition: COMMITTED_NOT_PUSHED
```

Blocked formal tickets remain blocked cycles and must not be recast as
successful no-commit completion:

```yaml
status: blocked
git_disposition: BLOCKED
commit: null
push: null
```

Report/Hopper completeness means all artifacts required for review and
provenance are represented. It does not mean every temporary byte generated
during execution must be transported. Oversized, sensitive, credential-bearing,
or local-only diagnostic evidence may remain outside Report/Hopper when the
cycle records its disposition, such as:

- `REPORT/HOPPER REQUIRED`
- `LOCAL EVIDENCE ONLY`
- `SENSITIVE / DO NOT PACKAGE`
- `TEMPORARY / DO NOT PACKAGE`

The current project helper owner is `tools/hopper/clean_cycle.py`. It creates
cycle identity, archives prior active Report/Hopper contents, collects selected
artifacts, finalizes cycle JSON and MANIFEST records, publishes the visible
Report directory, records explicit Git disposition, and validates current-cycle
package structure.

`report-copy-only` is a historical manual recovery artifact, not a valid normal
terminal state for a completed formal ticket.
