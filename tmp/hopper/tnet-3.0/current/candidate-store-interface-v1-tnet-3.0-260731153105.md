# Community 3.0 In-Memory Candidate Store Interface v1

Status: test-only and process-local. This store is not a production service.

## Code location and interface

Implementation: `tools/community3/notification_candidate_store.py`.

`InMemoryCandidateStore` provides `add`, `get`, `contains`,
`list_for_recipient`, `list_for_event`, `count`, and `clear`. It stores deep
copies in insertion order for the duration of the test process.

## Validation and duplicate policy

Only candidates produced by the existing candidate boundary are accepted.
Required identity, decision, reason-code, mapping, channel, and
`persistent=False` fields are validated. Bell, email, digest, and delivery
states must remain deferred. Equal `path_id` and `group_id` are rejected.
Malformed candidates raise `ValueError`. Duplicate candidate IDs raise
`ValueError` deterministically; no overwrite occurs.

## Mutability and exclusions

Inputs are deep-copied on insertion and returned as deep copies, preventing
callers from silently mutating stored state. The store performs no file,
database, cache, option, transient, session, queue, network, WordPress,
production, bell, email, digest, or delivery operation.

## Verification and rollback

Run:

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
```

The store can be removed with its module and test file. `clear()` removes all
process-local state; no data rollback is required because no durable state is
created.

The next bounded ticket must separately authorize any persistence or channel
work after Engineering Director review.
