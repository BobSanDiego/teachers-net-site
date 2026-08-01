# Community Flat Thread Rendering v1

## C3-ARCH003 implementation

The Thread View now projects authoritative post lineage into one topic,
chronological L1 branches, and a single visually flat L2 layer. Exact
`parent_post_id` and reply-target metadata remain unchanged; deeper replies
remain logically addressable but render at level 2.

The read model prefers `conversation_root_id` and derives a safe L1 branch for
legacy NULL fields by walking exact parent lineage. Missing parents and cycles
are skipped safely. Restricted L1 branches are suppressed in full; restricted
L2 replies are suppressed individually. Every L2 row exposes a safe
“Replying to …” target link when visible, or a generic historical label when
the target is unavailable. Stable anchors use `#reply-post:{opaque-id}`.

The composer is bounded to one topic-level form with an explicit target
selector. Topic selection creates L1; selecting any visible L1/L2 preserves
the exact target through the existing PHP publisher flow. Movable-composer
JavaScript, dirty-state warnings, and redesign remain out of scope.
