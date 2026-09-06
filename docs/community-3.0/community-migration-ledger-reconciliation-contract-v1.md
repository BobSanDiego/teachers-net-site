# Community Migration Ledger and Reconciliation Contract v1

Status: required contract; documentation only.

Each source row has one unique `(source_namespace, legacy_post_id)` ledger key,
source checksum, target post/thread IDs when imported, batch/run ID, decision
time, disposition, reason code, rule version, and exception/review reference.
Required dispositions are `MIGRATE_PUBLIC`, `ARCHIVE_ONLY`, `QUARANTINE`, and
explicit excluded reason codes.

The ledger is append-audited and idempotent. A restart resumes by source key and
checksum; it never creates a second target for an already accepted source. A
thread batch imports root, direct replies in chronological order, source
provenance, and URL aliases atomically. Required-row failure rolls back the
batch; unresolved rows remain non-public in the exception queue.

Every reconciliation compares source/target root and reply counts, source-key
uniqueness, disposition counts, raw status/moderation counts, board mappings,
identity states, URL aliases, cross-thread edges, media/preview availability,
and sampled rendered public/private outcomes. A cutover cannot pass with
unmapped, duplicate, or unexplained records.

Rollback is batch-scoped: disable the migrated scope, restore the prior routing
state, retain ledger and alias evidence, and preserve the immutable archive.
No source deletion is part of migration.
