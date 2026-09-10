# Community Publisher Cross-Language Parity v1

`tests/fixtures/community3/publisher-domain/shared-parity.json` is the
language-neutral semantic contract. Python tests validate its case inventory;
the local PHP parity script consumes the same file and verifies accepted topic,
canonical event type, and unresolved-community rejection. Parity compares
semantic outcomes, reason codes, relationships, state, event type, idempotency,
and compatibility handling—not byte-level serialization.

Python remains a legacy/domain oracle and fixture-support tool. There is no
subprocess, HTTP, queue, or runtime bridge from WordPress to Python.
