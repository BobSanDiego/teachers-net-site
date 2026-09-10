# Community 3.0 Group-Post Publication Shadow Hook v1

Status: test-owned, disabled by default, process-local, non-persistent, and
non-delivering.

## Publication seam finding

Repository inspection found no owned Community post-publication implementation
or authoritative `wp_insert_post`/Community hook in the inspected codebase.
Therefore this ticket does not claim a live publication hook was connected.
`GroupPostPublicationShadowHook` is an explicit test seam that models the
post-publication payload boundary until the real Community publisher is
available.

## Implementation and control

Implementation: `tools/community3/group_post_shadow_hook.py`.

The hook is enabled only when constructed with `test_mode=True` and
`enabled=True`; its default is off, and there is no production configuration,
database flag, option, admin control, secret, or environment switch. It accepts
synthetic source, recipient, and policy fixtures, requires published state,
delegates to `GroupPostEventAdapter`, and passes the canonical event to an
injected test recorder/service.

The shadow path catches adapter/service exceptions and returns no result so the
publication caller cannot be blocked, altered, retried, or rolled back. The
recorder receives only the safe canonical fixture payload and stores nothing
unless a test chooses to retain the returned object in memory.

## Verification and limits

Tests cover default-off behavior, enabled adapter/service integration,
divergent `path_id=241` and `group_id=227`, missing mapping, hidden/moderated
and draft posts, exception isolation, duplicate callbacks, and absence of live
recipient enumeration. No live hook, production recipient, database, queue,
mail, digest, UI, network, WordPress/BuddyPress integration, or Job Center code
is involved.

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
python3 -m py_compile tools/community3/*.py
```

Rollback is removal of the hook, test, and document. The exact next bounded
ticket is to identify and authorize integration at the real Community publisher
seam once that source implementation is available; do not enable this test
seam in production.
