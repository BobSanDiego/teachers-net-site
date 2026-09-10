# Community Publisher Test Plan v1

Future implementation must test characterization comparison, topic/reply
creation, same-community parent checks, validation and abuse classification,
duplicate/idempotent retry, lifecycle/moderation transitions, post-commit event
emission and event-failure isolation, URL/archive compatibility, identity
privacy, rollback/transaction failure, concurrency, audit integrity, resolver
use, and absence of direct legacy-ID dependencies.

Use synthetic fixtures and the existing characterization harness. Database
fixtures belong to the implementation ticket. Verify semantic-service
unavailability does not invalidate publication, notifications are post-commit,
and hidden/deleted parents cannot leak content.
