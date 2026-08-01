# Community Thread Data Support QA v1

## Required checks

1. Run the schema installer twice and confirm version `2`, all eight nullable
   columns, and the five new lookup indexes remain present without duplicates.
2. Create a standalone topic, direct reply, nested reply, and deeper reply.
   Confirm the topic subject is `community/community_topic/<topic id>`, every
   reply preserves the exact parent, direct replies point at the topic author,
   and nested replies retain the nearest L1 `conversation_root_id`.
3. Re-submit the same idempotency key and confirm the original row is returned;
   submit changed content with that key and confirm `IDEMPOTENCY_CONFLICT`.
4. Exercise missing parent, cyclic parent, cross-community, cross-thread,
   restricted-parent, malformed subject, and unsupported namespace cases.
5. Persist and reread a record, confirming all subject and target fields
   round-trip transactionally. Confirm pre-upgrade rows with NULL compatibility
   fields remain readable.
6. Persist synthetic Lesson Bank and Teachers.Net subject references. Confirm
   they are accepted as data-only identities and no attached-subject UI is
   introduced.

## Boundary

PHP lint and focused domain/repository checks are required in the existing
local DDEV clone. Full visual Thread View QA is outside this ticket; rendering
and explicit target UI belong to C3-ARCH003. No production host or database is
modified.
