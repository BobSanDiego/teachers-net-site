# Community Thread View v1

The local-only route is `/community/thread/{post_id}/`, served by the
Community-owned plugin. It renders one canonical topic, ordered direct and
nested replies, parent/depth relationships, safe synthetic author display,
timestamps, and lifecycle-aware tombstones. It uses a read service; the
controller/template does not issue SQL or expose IDs, audit metadata,
compatibility metadata, idempotency keys, or events.

The seed command is:

```text
ddev wp eval-file tools/community3/local_seed_thread_view.php
```

It resets only local prototype tables, creates a topic, two direct replies, a
nested reply, and a retracted child, then prints the local URL. No reply
composer is included because identity/permission/privacy contracts remain
unresolved. The route is noindex and not added to site navigation.
