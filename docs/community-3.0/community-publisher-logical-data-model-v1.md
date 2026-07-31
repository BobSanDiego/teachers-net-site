# Community Publisher Logical Data Model v1

Logical only; no migration is authorized.

| Record | Responsibility and key fields |
|---|---|
| `community_posts` | post/community IDs, author, thread/parent, type, content, visibility, moderation/publication state, timestamps, idempotency, revision, permalink |
| `community_post_compatibility` | legacy URL/path/group references, archive/source/evidence, immutable status |
| `community_post_revisions` | post/revision, actor, content, reason, time |
| `community_post_audit` | append-only transition/action, actor, reason, before/after, evidence, time |
| `community_publication_events` | event/post/thread/community identity, safe target, state, version, outbox/dedupe status |

Indexes must cover community/state/time, thread/time/post, parent, author/state,
unique idempotency scoped to author/community, and proven one-to-one
compatibility references. Legacy `chat_posts` and static archives remain
evidence, not new-write authority. Core Terms and Portable Views reference
canonical IDs through their own authorities.
