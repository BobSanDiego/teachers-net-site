# Community Thread Rendering Contract v1

Render one topic, chronological L1 comments, and chronological descendants
within each L1 branch. All descendants deeper than L2 occupy the same visual
L2 layer; depth is not used to create a third visible indentation level.

Every L2 item displays a safe linked target such as “Replying to Local member”.
The target link points to `#reply-post:{opaque-id}` and has screen-reader text
that identifies the relationship without exposing restricted metadata. The
reply article has a stable `id="reply-post:{opaque-id}"` or normalized
equivalent, with escaping and a compatibility alias where needed.

Visible states are published/restored; retracted or deleted content becomes a
tombstone when policy allows; hidden/spam content is omitted for ordinary
readers. Pagination must preserve branch headers and deterministic cursors.
Incremental reveal may append rows but must not reorder already rendered items.

The current implementation reads all rows with `created_at, id`, computes
depth, and renders nested indentation. That behavior is verified current state,
not the adopted v1 visual authority. No rendering change is made here.
