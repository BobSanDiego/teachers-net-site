# Community Publisher Domain Contract v1

Status: design-only; no runtime or schema.

`CommunityPost` is a Community-owned fact identified by opaque `post_id` and
owned by canonical `community_id`. It contains author identity/display policy,
optional legacy author reference, `thread_id`, nullable `parent_post_id`,
`topic|reply` type, title/body representation, visibility, moderation and
publication states, timestamps, idempotency key, revision, safe permalink,
legacy references, and audit metadata.

WordPress authentication supplies authenticated authorship. Historical authors
are compatibility references reconciled separately. Deleted/deactivated users
retain evidence while public display is policy-controlled. Anonymous or
confidential display is a capability boundary, not an enabled policy; no
anonymous contract is invented here. Legacy fields never infer identity.

Core Terms may classify a post or Community; Portable Views may present or
filter it. Neither owns post identity. Publisher persistence remains valid when
those semantic services are temporarily unavailable.

Before persistence, deterministic validation normalizes and sanitizes content,
checks required fields/limits/community availability/visibility, and evaluates
duplicate submission. Probabilistic spam/profanity scoring produces a flag and
reason, not an unreviewable replacement for moderation.
