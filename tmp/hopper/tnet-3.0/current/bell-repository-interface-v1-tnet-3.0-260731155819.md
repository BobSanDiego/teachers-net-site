# Community 3.0 In-Memory Bell Repository Interface v1

Status: test-only, process-local bell-state abstraction. No persistent storage
or delivery behavior is included.

## Interface

Implementation: `tools/community3/notification_bell_repository.py`.

`InMemoryBellRepository` provides `create_bell(candidate)`, `get`,
`list_unread`, `mark_read`, `mark_unread`, `archive`, `count_unread`, and
`clear`. It accepts only validated eligible candidate-boundary objects.

Bell IDs are deterministic: `bell:<candidate_id>`. Duplicate IDs are rejected.
Stored and returned objects are deep copies. Supported states are `unread`,
`read`, and `archived`; delivery remains `deferred` and engagement remains
`unmeasured`.

## Boundaries

Event, candidate, bell, delivery, and engagement remain distinct. The
repository preserves `path_id` and `group_id` separately and does not infer
membership, consent, visibility, or delivery. It performs no database,
filesystem, schema, migration, WordPress, queue, email, digest, network,
production, or UI operation.

## Verification and rollback

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
```

The tests cover lifecycle, unread counts, recipient isolation, mapping,
candidate immutability, rejection, duplicates, and clear. Remove the repository
and its test to roll back; no durable state exists.
