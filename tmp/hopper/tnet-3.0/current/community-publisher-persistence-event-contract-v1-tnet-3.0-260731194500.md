# Community Publisher Persistence and Event Contract v1

The atomic write unit is canonical post state, its initial audit transition,
and an outbox/event record in one transaction. A repeated submission key
returns the original result without a second post; conflicts are explicit.
Retries are safe. Partial failures roll back canonical state.

After commit, emit `community.post.published` (or the corresponding lifecycle
event) with event ID/version, post/community/thread/parent IDs, author and safe
display references, publication/visibility/moderation state, timestamps, safe
target, idempotency/version, and permitted compatibility references. The
notification service consumes canonical identity and scoped group context,
not legacy identifiers. Event delivery retries independently and cannot roll
back publication.
