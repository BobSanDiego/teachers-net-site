# Community Production Writer and Routing Readiness Contract v1

Status: readiness gate; no production change authorized.

Before any public writer or route cutover, prove one governed Community source
and runtime, WordPress identity and permissions, board mapping, canonical public
URLs and aliases, visibility/moderation/reporting, notification destination
behavior, observability, backup/archive, rollback, and operator recovery.

The production writer must be Community-owned and transactionally idempotent.
The legacy writer is read-only for the migrated scope during shadow/pilot and
is disabled before Community becomes sole writer. A per-board switch may be
used for staged cutover only if it cannot enable both writers.

Readiness requires local/reversible pilot reconciliation, native HUMAN_QA,
public/private/exception URL checks, source/target count checks, and explicit
Director authorization. DDEV proof, noindex prototype routes, or a local
adapter do not establish production readiness.
