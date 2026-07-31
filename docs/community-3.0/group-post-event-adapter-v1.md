# Community 3.0 Group-Post Event Adapter v1

Status: fixture-backed, process-local, non-persistent, non-delivering.

Implementation: `tools/community3/group_post_event_adapter.py`.

The adapter converts a synthetic Community-shaped group-post source record,
recipient context, and policy context into the canonical event accepted by
`NotificationApplicationService`. The path is source record → adapter →
application service → dry-run pipeline → candidate → in-memory bell.

It requires post identity, author, path/group IDs, local mapping key and
evidence, publication/moderation/visibility state, privacy, timestamp, event
family, and safe content reference. Recipient context requires authenticated,
membership, access, and self-event facts. Policy context requires frequency,
category, bell, email pause, mute, suppression, and kill-switch state.

Event identity is deterministic: `group-post:<post_id>:recipient:<recipient_id>`.
The adapter requires explicit mapping evidence and preserves distinct
`path_id`/`group_id`. It rejects malformed, unsupported, hidden, moderated, or
unmapped sources deterministically and contains no policy engine beyond the
supplied fixture-state mapping.

The scenario suite covers eligible, self-authored, unmapped, private/no-access,
hidden, former member, never frequency, paused email, bell kill switch,
duplicate post/recipient, distinct recipients, and identity separation. No live
hook, production query, WordPress/BuddyPress integration, persistence, schema,
queue, email, digest, UI, network, or Job Center code is involved.

Verification:

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
python3 -m py_compile tools/community3/*.py
```

Rollback is removal of the adapter, test, and document; no durable state exists.
The next bounded ticket requires Engineering Director review before any live
event source or persistence integration.
