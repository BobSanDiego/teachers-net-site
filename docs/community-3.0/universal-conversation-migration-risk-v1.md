# Universal Conversation Migration Risk v1

The highest risk is treating legacy `path_id`, mutable URLs, lesson filename
IDs, or WordPress post IDs as universal identity without an ownership mapping.
Other risks are cross-product access leakage, duplicate conversations from
embedded views, ambiguous imported parents, and notification recipients derived
from subject visibility rather than explicit policy.

Backfill must be idempotent, preserve source references and exact parent edges,
record unresolved subjects, validate same-owner/type namespaces, and support
transactional rollback. Chatboard migration maps legacy board/path context
through an explicit resolver; it does not equate `path_id` with `group_id`.
Lesson imports retain `source_lesson_id` and provenance until Lesson Bank
publishing supplies an authoritative subject reference.

No data migration is required for C3-ARCH002-AUDIT001. The next implementation
ticket should add tests and nullable compatibility support only, with a later
separately authorized backfill rehearsal.
