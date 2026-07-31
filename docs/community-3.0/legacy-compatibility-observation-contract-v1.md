# Legacy Compatibility Observation Contract v1

The characterization model returns a JSON-compatible dictionary. Accepted
observations may contain:

- `outcome`: `accepted`, `rejected`, `inconsistent_state`,
  `idempotency_classification`, or explicit `UNKNOWN — EVIDENCE REQUIRED`;
- `post_type`, `parent_id`, and `thread_id`;
- `url_pattern` and `timestamp_format`;
- approved `chat_posts_fields` names;
- `local_path`, distinct `path_id`, `group_id`, and `mapping_required`;
- immutable `archive_reference` metadata;
- stable `reason_code` for non-accepted outcomes.

The contract excludes rendered HTML snapshots unless later evidence proves
formatting compatibility-critical. It excludes database writes, files,
queues, mail, notifications, UI, network access, and production identifiers.

Unknown behavior is represented as an explicit unsupported result, never as a
successful guess. A future native publisher may be compared against these
observations, but this contract does not authorize native implementation or
define unverified edit/delete, moderation, authentication, mailring,
concurrency, or recovery semantics.
