# Community Publisher Domain Reason Codes v1

Validation codes include `COMMUNITY_UNRESOLVED`,
`AUTHENTICATED_AUTHOR_REQUIRED`, `POST_TYPE_UNSUPPORTED`, `TITLE_REQUIRED`,
`BODY_REQUIRED`, `REPLY_PARENT_REQUIRED`, `PARENT_NOT_FOUND`,
`PARENT_COMMUNITY_MISMATCH`, `PARENT_RESTRICTED`, `THREAD_LOCKED`,
`THREAD_MISMATCH`, visibility/length/mode errors, and
`MODERATION_INPUT_UNSUPPORTED`.

Operational codes include `IDEMPOTENCY_CONFLICT`,
`LIFECYCLE_TRANSITION_ACCEPTED`, `LIFECYCLE_TRANSITION_INVALID`,
`EVENT_CONSTRUCTION_FAILED`, and moderation codes for clear, flagged, spam,
hidden, and moderator hold. These are deterministic domain classifications,
not transport, database, or notification outcomes.
