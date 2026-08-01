# Community Flat Thread Rendering QA v1

- DDEV PHP lint passed for the Thread View and controller.
- HTTP 200 smoke test passed at the existing local Thread View URL.
- Synthetic topic/L1/L2 read-model check passed: levels were `1,2`; exact
  parents round-tripped; the L2 target linked to the L1 anchor.
- Hiding an L1 suppressed its complete branch; hiding only an L2 left the L1.
- `git diff --check` passed.

Browser-control could not initialize in this WSL thread because its sandbox
metadata rejected the repository path. Wide desktop, 1024px, 768px, and 390px
screenshot evidence is therefore pending and is not claimed complete. Repeat
those viewport checks in the canonical Chrome QA session.
