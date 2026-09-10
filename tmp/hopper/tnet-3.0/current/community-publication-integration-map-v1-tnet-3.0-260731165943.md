# Community 3.0 Community Publication Integration Map v1

Status: repository audit and bounded prototype reference only. No production
publisher, hook, notification, persistence, or delivery behavior was changed.

## Lifecycle trace

The owned repository was inspected for Community/chatboard post creation,
validation, publication, persistence, and rendering seams. Searches for
`wp_insert_post`, `wp_update_post`, post-status transitions, Chatboard/Community
post writers, and the known `path_id`/`group_id` mapping found no owned
Community publisher implementation. The available repository contains Core
Terms, Job Center, Lesson Bank workbench, and theme assets, but no canonical
Community post lifecycle implementation.

Accordingly, the lifecycle is presently an evidence gap:

```text
Community create       not present in owned repository
Community validate     not present in owned repository
Community publish      not present in owned repository
Community persist      not present in owned repository
Community render       not present in owned repository
                         |
                         +-- C3-IMP009 test seam only
                             -> existing adapter/service dry run
```

## Ownership and identity map

| Concern | Verified owner/evidence | Integration conclusion |
|---|---|---|
| Community post authoring | No owned implementation found | Cannot select a production seam. |
| Core Terms | `profilaxes` / Core Terms | Classification only; not post publication. |
| Job Center | `tnet-jobs` | Separate product; not absorbed. |
| Theme rendering | `twentytwentyfive` and theme assets | No authoritative Community post owner. |
| `path_id` / `group_id` mapping | Community contracts and prior verified records | Must be supplied explicitly by a future publisher. |
| Notification shadow proof | `tools/community3/group_post_shadow_hook.py` | Test-owned, disabled, non-authoritative prototype. |

The required post ID, author ID, path ID, group ID/mapping evidence, visibility,
moderation, publication timestamp, and privacy context are available only in
synthetic fixtures at present. No production content or personal data was used.

## Recommended future attachment

The authoritative seam should be the first post-publication boundary owned by
the real Community publisher after stable post identity, mapping evidence,
publication state, visibility, moderation, privacy, and timestamp are committed
or otherwise final. It must precede rendering and follow publication facts. A
future ticket must identify that owner from the actual source implementation;
this audit does not authorize creating it.

## Proof and stop condition

C3-IMP009's `GroupPostPublicationShadowHook` is the bounded proof-of-attachment
mechanism. It is not attached to a live hook and therefore proves only the
canonical adapter/service contract, not production publisher integration.
No notification behavior or publication semantics changed.
