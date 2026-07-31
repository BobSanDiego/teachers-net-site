# Community 3.0 Notification Application Service v1

Status: test-only, process-local, non-delivering.

## Interface and scope

Implementation: `tools/community3/notification_application_service.py`.
Public interface: `NotificationApplicationService.notify(event) -> execution
report`.

The only supported event family is the proven synthetic `group_post` event.
Reply, reaction, mention, moderator, announcement, and other event families
are rejected. The service validates the event, delegates to
`DryRunNotificationPipeline`, and returns a deep-copied deterministic report.

## Delegation and result contract

The path is service → existing dry-run pipeline → evaluator → candidate
boundary → candidate store → bell repository. The service contains no duplicate
evaluator, candidate, store, or bell logic. Results include event/recipient
identity, distinct `path_id`/`group_id`, eligibility decision, reason codes,
candidate and bell IDs when created, bell state, channel outcomes, and explicit
false side-effect flags.

Duplicate event IDs return the original report without adding state. Returned
reports and submitted events are copied so callers cannot mutate internal state.
Malformed events, unsupported families, missing identity, invalid mapping, and
invalid reason-code shapes raise deterministic `ValueError` results.

Separate service instances own separate pipeline, candidate-store, and bell
repository state. No database, schema, migration, queue, email, digest, UI,
network, production, or persistent filesystem behavior exists.

## Verification and rollback

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
python3 -m py_compile tools/community3/*.py
```

Remove the service, test, and document to roll back; no durable state exists.
The next bounded ticket requires Engineering Director review before any
persistence, delivery channel, or production event integration.
