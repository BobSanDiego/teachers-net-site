# Canonical Community Composer Refactor Sequence v1

1. Extract pure URL detection, Markdown help, and normalized staged-media contracts.
2. Extract local image validation/upload cleanup with JPEG/PNG/WebP, size, alt fallback, and failure-cleanup tests.
3. Create a shared composer view partial with topic and reply context slots; render the topic form first.
4. Add reply media/link handling through the same services while retaining reply nonce, parent/thread validation, idempotency, application call, and PRG.
5. Replace reply markup with the shared view and add parity tests.
6. Run desktop/mobile browser QA, then remove superseded inline markup only after parity evidence.

Risks: uploads, target confusion, idempotency collisions, draft/error preservation, and no-JavaScript text publication. Commit each step separately.

REF001 completed the pure URL and staged-image contract seams without migrating
reply uploads or introducing a shared view. Next: REF002, the topic-first
shared composer view partial.
