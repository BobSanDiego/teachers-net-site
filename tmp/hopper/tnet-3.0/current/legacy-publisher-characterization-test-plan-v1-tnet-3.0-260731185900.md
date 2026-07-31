# Legacy Publisher Characterization Test Plan v1

No production requests or mutation are required for this plan.

## Minimum fixtures

Create redacted fixtures for a board, new topic, reply, invalid input,
profanity/spam result, legacy identity context, divergent `local_path` and
`group_id`, duplicate submission, partial file/database write, and historical
URL. Do not copy post bodies, credentials, or personal data.

## Golden observations

Capture expected validation outcome, post/reply relationship, URL shape,
timestamp representation, `chat_posts` fields, include/cap deltas, rendered
HTML shape, error response, and retry behavior. Use golden files for static
output and database fixtures for index rows. Mark production-only behavior as
unverified rather than guessing.

## Required test groups

1. Request routing and form contract.
2. Validation and abuse-control characterization.
3. Board resolution and explicit path/group mapping.
4. Topic/reply threading and duplicate submission.
5. File/database atomicity and recovery.
6. Legacy identity/authorship reconciliation.
7. URL, redirect, archive, search, and SEO preservation.
8. Moderation, deletion, edit, and notification evidence.
9. Load/concurrency assumptions using synthetic fixtures only.

Before implementation, obtain source ownership or a repeatable approved
read-only fixture export, define acceptance for unknown edit/delete/admin
behavior, and establish checksums plus rollback records. The test suite should
compare the native implementation to characterized behavior, not preserve
accidental HTML or security defects.
