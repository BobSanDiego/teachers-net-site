# Community Publisher Local Verification v1

Run these commands only in the local DDEV project:

```text
ddev wp eval-file tools/community3/local_publisher_persistence_smoke.php
ddev wp eval-file tools/community3/local_publisher_persistence_tests.php
```

The smoke command installs synthetic tables, persists a topic/event, repeats
the submission, prints redacted IDs and counts, and cleans up. The transaction
command injects post/audit/event failures, verifies rejection and cleanup,
checks duplicate retry and audit dedupe, creates a new repository instance for
restart retrieval, and uninstalls the prototype tables.
