# Community Publisher Persistence Prototype v1

The local-only runtime owner is `wordpress/wp-content/plugins/tnet-community/`.
It contains an opt-in schema class and narrow repository for C3-CORE007
publication results. There is no activation hook, page-load install, endpoint,
form, UI, notification dispatch, legacy CGI call, or migration.

The repository transaction writes a canonical post, initial audit row, and
publication outbox row together. The process uses `$wpdb`/`dbDelta`, prepared
queries, unique constraints, explicit schema version, and rollback on injected
post/audit/event failure. Repeated identical submission returns the persisted
post; conflicting content returns `IDEMPOTENCY_CONFLICT`.

Developer smoke command:

```text
ddev wp eval-file tools/community3/local_publisher_persistence_smoke.php
```

The script installs synthetic tables, persists one synthetic topic/event,
repeats it, prints redacted IDs/states and audit/event counts, then uninstalls
the prototype tables.
