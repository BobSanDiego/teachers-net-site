# Teachers.Net Legacy Operations — Inception Baseline

## Boundary

This record establishes the `legacy-operations` project on 2026-09-18. It
does not create retroactive Workflow V2 cycles. All execution before
`TNET-LEGACY-OPS-WORKFLOW-BOOTSTRAP-001` is historical pre-registration work.
The facts below are accepted inherited evidence for continuity; current runtime
or AWS state must be rechecked when it becomes material to a later ticket.

## Inherited accepted evidence

### Performance and stability

- The Sandy performance incident was diagnosed as interacting legacy database,
  Apache worker, and abusive-connection pressure.
- `35b92f8` repaired the legacy chatboard related-post query.
- `bf5484f` removed unnecessary board counting from chat topic routes.
- `ed0b74e` established evidence-preserving Monit recovery behavior and
  bounded incident snapshots.
- `get_distinct_topics()` remains a known risk; it was not accepted as repaired.

### Recovery and backup

- `ce063fe` added daily off-host mutable-state protection and weekly current
  Sandy recovery; `8bf2654` corrected the AWS Backup request shapes.
- `1fcd0f2` records a successful isolated logical restore and whole-machine
  recovery rehearsal. It did not rehearse production overwrite, DNS/traffic
  cutover, mail recovery, or a public request journey.
- The governed source of the active recovery policy is
  `/home/bobreap/projects/teachers-net-legacy/ops/sandy/recovery-policy.md`.

### Storage and protected resources

- The pre-registration cleanup removed only the approved migration,
  reactivation, VS Code server, and APT-cache targets, reducing root use from
  roughly 89% to roughly 80–81%.
- Keep boundaries include current Sandy and its current recovery artifacts,
  the retained June 6 checkpoint, the predecessor and its existing recovery,
  mail infrastructure and protected EIP, S3 historical objects and
  `sandy-daily/`, forensic material, logs, Netdata, `/var/backups`, incident
  snapshots, uploads, application data, and MySQL data.
- The cleanup has no retroactive formal cycle. A later read-only AWS check may
  establish current retained/deleted cloud state, but must never rerun it.

## Freshness boundary

The historical evidence was accepted before project registration. The current
startup authorities are this baseline, the Project Cursor, the Engineering
Handoff, the project record, the governed legacy source, and later validated
Report/Hopper cycles. Historical session `01a0b061-d739-7b70-95b6-53519ad4233a`
is retained only as evidence provenance and is not needed to start work.

## Current residual ledger

| Seam | State | Required later action |
| --- | --- | --- |
| Netdata footprint/retention | Pending decision | Measure unique value and costs before reconfigure/remove. |
| Traffic admission/crawler policy | Pending policy | Design durable behavior-based controls; do not perpetuate emergency IP rules by default. |
| Configuration provenance | Incomplete | Inventory Sandy-only configuration and establish durable source authority. |
| Root/storage lifecycle | Pending policy | Establish measured retention and capacity policy. |
| `get_distinct_topics()` | Known risk | Prioritize only after trustworthy production-cost telemetry exists. |
| AWS cleanup verification | Pending read-only check | Verify only after authenticated `tnet-prod` access is available. |

## No-reopen rule

The inherited repairs, recovery paths, restore rehearsal, completed local
cleanup, and protected-resource boundaries are carried forward as accepted
unless a source owner or dependency changes, or decisive current evidence
contradicts them.
