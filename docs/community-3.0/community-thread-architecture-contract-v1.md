# Community Thread Architecture Contract v1

Status: adopted architecture authority; documentation and read-only inspection only.

## Adopted visible model

A Community thread has one topic, top-level comments (L1), and one visually
flat reply layer (L2) beneath each L1 comment. Logical targeting remains
unlimited: a reply may answer any visible eligible post. Rendering never
discards lineage; descendants deeper than L2 are displayed in the L2 visual
layer with an explicit linked reply target.

## Canonical fields

`thread_id` identifies the overall discussion. `parent_post_id` is the exact
logical parent and remains the authoritative lineage edge. `conversation_root_id`
identifies the owning L1 branch. `reply_to_post_id` is the explicit post being
answered and is equal to `parent_post_id` for v1. `reply_to_author_id` records
the target author identity at reply time for notification/audit derivation; it
does not replace current visibility or account policy.

The current repository verifies `thread_id` and `parent_post_id` but does not
yet store the three derived/recipient fields. C3-ARCH002 should add and test
those fields before UI normalization.

## Selected storage strategy

Select a hybrid: keep exact `parent_post_id` and `thread_id` as required stored
fields, add nullable stored `conversation_root_id`, `reply_to_post_id`, and
`reply_to_author_id`, and derive display depth/ordering at read time. Stored
branch identity makes moderation, notification targeting, repair, and imported
data auditable; derived depth avoids denormalized indentation drift. Add a
unique submission boundary already represented by `(community_id, author_id,
idempotency_key)`.

No schema mutation occurs in this ticket. Proposed indexes are
`(thread_id, created_at, id)`, `(conversation_root_id, created_at, id)`,
`parent_post_id`, and `(reply_to_post_id, publication_state)`. Nullable derived
fields support unresolved legacy rows without inventing identity.

## Cross-contract behavior

Moderation controls visibility, not lineage. Retraction/deletion produces a
tombstone where policy permits; descendants retain their exact parent and may
remain readable/publishable only when the separate moderation policy permits.
Restricted target labels never expose restricted names or content. Notification
candidate selection uses the explicit reply target and policy contracts; this
ticket implements no notifications.

The stable v1 fragment remains `#reply-post:{opaque-id}`. It identifies a reply
without encoding visual depth and must resolve after flat rendering, pagination,
or normalization.

## Composer contract summary

Only one inline composer is open. Empty composers retarget immediately. Dirty
composers warn before retarget, navigation, reload, close, or beforeunload.
Cancel clears the draft and restores focus to the launching Reply control.
Successful submission clears state and focuses the anchored reply. JavaScript
is optional enhancement; the server form remains authoritative.
