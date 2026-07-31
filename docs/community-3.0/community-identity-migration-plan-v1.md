# Community Identity Migration Plan v1

## Stages

1. Establish source ownership and inventory approved mapping sources.
2. Create a redacted mapping census with checksums, statuses, and conflict
   queues; do not alter legacy records.
3. Allocate stable `community_id` values and create immutable references for
   path, local-path, group, URL, archive, post, membership, and moderator
   evidence.
4. Reconcile missing/duplicate/ambiguous mappings through explicit review.
5. Add read-only resolver comparisons against legacy URLs and synthetic
   characterization fixtures.
6. Migrate compatibility records and historical content with idempotent,
   auditable jobs; retain rollback checkpoints.
7. Introduce canonical Community reads and then a bounded native-write pilot.
8. Deprecate legacy fields outside resolver/migration code only after URL,
   membership, archive, moderation, and recovery acceptance.

## Conflict and rollback rules

No mapping is silently guessed or overwritten. A conflict blocks dependent
membership, publication, notification, and privacy decisions. A staged route
can be disabled to restore immutable legacy reads. Rollback preserves both the
canonical record and original legacy references; it never renumbers IDs.

Legacy fields may stop appearing in normal application code only after static
analysis, resolver-boundary tests, mapping reconciliation, and an Engineering
Director-approved deprecation decision. The exact next implementation ticket
is a test-only in-memory canonical identity resolver and mapping registry
contract; it must not create schema or production mappings.
