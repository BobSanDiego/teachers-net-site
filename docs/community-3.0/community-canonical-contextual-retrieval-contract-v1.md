# Community Canonical Contextual Retrieval Contract v1

Status: required contract; interface design only.

Retrieval targets use immutable discussion/reply identity, not ordinal position.
The target is checked against community, visibility, moderation, and identity
authority before expansion. Context is a bounded before/after window around the
target using a stable cursor containing the target identity, direction, and
contract version. The window is independent of feed sort and presentation.

A missing, restricted, quarantined, or ambiguous target returns the contract's
truthful unavailable/archive outcome; it does not fall through to a guessed
record. The same target identity must resolve consistently from a feed card,
notification, canonical URL, search/share entry, and modal context.

Pagination is keyset/cursor based. Implementations may choose token encoding,
batch size, and indexes later, but may not use deep ordinal traversal as the
identity mechanism.
