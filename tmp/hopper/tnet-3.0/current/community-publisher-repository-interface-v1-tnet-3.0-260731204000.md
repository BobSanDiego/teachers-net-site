# Community Publisher Repository Interface v1

`TNet_Community_Publisher_Repository` provides:

- `persist_publication(publication_result, actor_context)`;
- `find_post(post_id)`;
- `find_by_submission_key(community_id, author_id, idempotency_key)`;
- `list_thread(thread_id, limit)`;
- `get_audit(post_id)`;
- `get_pending_events(limit)`;
- `mark_event_dispatched(event_id)` for local state testing only.

It accepts the C3-CORE007 result shape and does not reimplement domain
validation, threading, moderation, identity resolution, or event construction.
Raw SQL is confined to this repository/schema boundary. No external dispatch is
performed.
