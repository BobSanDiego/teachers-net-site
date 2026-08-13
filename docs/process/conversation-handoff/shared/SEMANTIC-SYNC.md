# Shared Semantic Authority and Synchronization

`semantic-authority.json` is the durable cross-project index for accepted
meaning and catalog state. It supplements, never replaces, project authority.

## Authority boundaries

1. Raw ChatGPT/Codex conversation is evidence, not semantic authority.
2. Implemented canonical Core Terms state is authoritative for what exists.
3. Approved semantic records capture Engineering Director direction.
4. Research is supporting evidence only.
5. Each consumer adoption is explicit and independent.

`catalog_revision` changes only when canonical catalog state changes.
`semantic_revision` changes only for APPROVED or IMPLEMENTED semantic records.
Research, a package, and an unacknowledged delivery advance neither revision.

## Record lifecycle

Every record names its concept, disposition, canonical state, direction,
source project/session/cycle, Engineering Director authority, affected
frameworks/projects, evidence pointers, and supersession links. Valid
dispositions are `PROPOSED`, `SUPPORTED`, `APPROVED`, `IMPLEMENTED`,
`DEFERRED`, `REJECTED`, and `SUPERSEDED`.

The harvest helper admits only a complete non-stale record. A conflicting
active direction for the same concept fails closed with `SEMANTIC DECISION
REQUIRED`; it must not overwrite accepted history. Deferred, rejected, and
superseded records remain provenance, rather than changing implemented state.

## Delivery and acknowledgement

Project cursor state is maintained under the shared workflow Hopper and tracks
catalog/semantic acknowledgement, pending relevant deltas, and explicit
consumer-adoption pointers. Raw ChatGPT generation and semantic delivery share
the existing `G<n>`/verified-ACK transport boundary, but a package alone is
never acknowledgement. A verified recipient ACK advances only that project's
semantic cursor.

An `UPDATE CHATGPT` generation may include a compact labelled
semantic/catalog-delta section alongside its raw transcript delta. It includes
only records relevant to the recipient and does not change raw transcript
boundaries. Irrelevant projects receive no detailed semantic record payload.

`PREPARE HANDOFF` carries the current semantic revision and unresolved relevant
state. A missing semantic source is a warning, not permission to infer or
replace authority.

## Harvest checkpoints

Workflow V2 checkpoints may harvest a candidate after an explicit Engineering
Director approval lock, accepted determination, approved/rejected/deferred
candidate, contradiction, authorized Core Terms mutation, or handoff. Codex
may perform only mechanical reconciliation where the source authority is
unambiguous. It must stop on stale provenance or a semantic conflict.

## Consumer adoptions

Semantic revision does not adopt a consumer. For example, Job Center remains
explicitly subscribed to `Jobs Subjects` Version 2 and `Jobs Grade Levels`
Version 1 until an authorized consumer-binding change records another adoption.
