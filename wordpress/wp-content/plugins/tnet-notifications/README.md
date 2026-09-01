# Teachers.Net Notifications

This plugin owns the shared recipient-notification persistence and authenticated
consumer API. It has no dependency on `tnet-jobs`, Community, Lessons, Core
Terms, or the theme, and it exposes no public producer-write endpoint.

## Current presentation contract

The current repository authority includes the Community-accepted shared
notification presentation contract. The accepted downstream closure was
`8d83b33fe0d38199151152aaf0f8966e799c25ca`; the current canonical source is
`9ee1ec2510e716e298ffee4a338903137221e8d9`, which additionally resolves
BuddyPress custom avatars when available and otherwise preserves the existing
resolved-avatar fallback.

The shared API supplies resolved destinations, read state, actor identity and
avatar data, event type/icon, quoted context, bounded `reply_excerpt`, and
timestamps. Shared Shell owns the visible row, whole-row navigation and
presentation mechanics, including actor-first 54px avatars with overlaid 29px
event badges and responsive panel containment. Providers own persistence,
facts, and mark-read operations; Community continues to own `reply.created`
and `like.added` producers. Job Center fixtures prove presentation only and do
not establish producer/provider integration.

## Local activation and migration

From the repository root:

```bash
ddev exec wp --path=/var/www/html/wordpress plugin activate tnet-notifications
ddev exec wp --path=/var/www/html/wordpress option get tnet_notifications_schema_version
ddev exec wp --path=/var/www/html/wordpress db query "SHOW INDEX FROM wp_tnet_notifications"
```

Activation owns the idempotent `dbDelta()` migration and asserts the table,
columns, and required indexes before recording schema version `1.0.0`. Repeating
activation/migration is a no-op at the same version. Producers are not required
for plugin activation.

The local smoke suite is:

```bash
ddev exec wp --path=/var/www/html/wordpress eval-file /var/www/html/wordpress/wp-content/plugins/tnet-notifications/tests/runtime-smoke.php
```

It registers only an in-process test source, deletes only `source_product =
'test'` rows, and verifies that Jobs rows are unchanged. It is not a producer or
fixture migration.

## Deactivation and rollback

```bash
ddev exec wp --path=/var/www/html/wordpress plugin deactivate tnet-notifications
```

Deactivation intentionally leaves `wp_tnet_notifications` and its schema
version intact; it does not delete records or alter source-product tables. To
restore the runtime, activate the plugin again and the same migration performs
its safe no-op/reconciliation path. A future destructive table removal requires
a separately approved migration and retention/export plan; it is not part of
v1 rollback.
