# Community Thread Data Migration Plan v1

No migration or schema mutation is executed by C3-ARCH001.

## Backfill

For each reply, verify its community and thread. Walk `parent_post_id` to the
nearest L1 comment and write `conversation_root_id`. Set
`reply_to_post_id = parent_post_id`; snapshot `reply_to_author_id` only when
the source identity is authoritative and privacy-safe. Preserve the original
parent, source IDs, timestamps, and ambiguity evidence. Missing parents, cycles,
cross-thread edges, and conflicting identities enter an unresolved queue.

The operation is idempotent by source record and target-field checksum. Run
pre/post counts, null/duplicate checks, same-thread checks, cycle detection,
branch-root checks, and sampled lineage comparisons. Do not silently flatten or
delete historical depth.

## Legacy/import behavior and rollback

Legacy chatboard rows remain reference evidence until mappings are verified.
Imported rows retain source references and do not infer `reply_to_author_id`
from display text. A failed batch is rolled back transactionally; a completed
batch is reversible by restoring the prior nullable fields from the migration
audit. Unresolved records remain readable only under existing visibility rules
and are not auto-published or notified.

The selected hybrid storage requires one future schema ticket, C3-ARCH002,
followed by a separately authorized backfill/migration ticket if evidence
supports execution.
