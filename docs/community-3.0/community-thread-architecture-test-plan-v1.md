# Community Thread Architecture Test Plan v1

This is a planning contract; it adds no executable tests in C3-ARCH001.

Test the future data/domain slice with fixtures for: topic/L1/L2 chains;
arbitrary deeper lineage; missing parent; cycle; cross-thread parent;
restricted, retracted, deleted, restored, and locked targets; deleted/renamed
authors; duplicate submission; deterministic timestamp ties; and unresolved
legacy mappings.

Assert stored fields, same-community/thread invariants, branch-root derivation,
exact parent preservation, target-author privacy, idempotency, rollback, and
notification candidate basis without sending notifications. Assert rendering
produces chronological L1/L2 order, one visible reply layer, explicit target
links, stable anchors, tombstones, pagination continuity, and no third visible
indentation.

Interaction tests must cover one-open-composer behavior, empty retargeting,
dirty warnings for target change/navigation/reload/close/beforeunload, Cancel
focus restoration, success anchor focus, failure retention, keyboard operation,
and no-JavaScript POST fallback. Browser QA must cover desktop, 1024px, 768px,
and mobile widths. Evidence must distinguish unit/domain, integration, browser,
and migration rehearsal results.
