# Community 3.0 End-to-End Dry-Run Notification Pipeline v1

Status: test-only, non-delivering orchestration.

Implementation: `tools/community3/notification_dry_run_pipeline.py`.

The pipeline accepts one synthetic Community group-post event, evaluates it,
creates and stores a candidate only when eligible, creates an in-memory bell
only for that candidate, and returns a deterministic execution report. Event,
eligibility, candidate, bell, delivery, and engagement remain separate.

Blocked, ineligible, and private-group events stop before candidate and bell
creation. A paused optional email case demonstrates a bell-only outcome. A
duplicate event ID returns the original report without adding another candidate
or bell.

The report preserves event/recipient identity, distinct `path_id` and
`group_id`, decision, reasons, candidate/bell IDs, bell state, deferred channel
states, and explicit false side-effect flags. Database, schema, queue, email,
digest, production, and UI behavior are absent.

Verification:

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
```

Rollback is removal of the pipeline module, test, and this document; no durable
state exists.
