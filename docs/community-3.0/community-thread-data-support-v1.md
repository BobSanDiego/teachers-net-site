# Community Thread Data Support v1

## C3-ARCH002 implementation boundary

This increment adds the minimum durable data support for branch-aware replies
without changing rendering, composer JavaScript, feeds, notifications,
revisions, moderation policy, or production data. The existing `posts` table
remains the persistence authority; no universal conversation table is created.

Each reply preserves its exact `parent_post_id`. A direct reply to a topic is
an L1 reply and receives its own `conversation_root_id`; a deeper reply keeps
the nearest L1 root. `reply_to_author_id` is a snapshot of the addressed
parent author, so later author changes do not rewrite the historical target.

Topics use the canonical standalone subject identity
`community/community_topic/<topic post id>`. Replies inherit the parent
subject identity. The nullable columns are deliberately compatible with old
rows; this ticket does not backfill or migrate them.

The schema upgrade is additive and idempotent through `dbDelta()`. Indexes
cover thread/time, conversation-root/time, parent lookup, reply-target/state,
and owner/type/subject lookup. There is intentionally no one-conversation-per-
subject uniqueness constraint: a subject may have multiple independent
conversations.

Validation rejects missing parents, cycles, cross-community or cross-thread
targets, restricted parents, malformed subject references, and unsupported
owner/type pairs. The accepted synthetic subject pairs are Community topics,
Lesson Bank lessons, and Teachers.Net articles; attached subjects are data
support only and have no UI in this increment.
