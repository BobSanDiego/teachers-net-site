# Community Runtime Authority Preflight v1

This gate is mandatory before any Community UX implementation or acceptance
report that depends on browser-visible behavior.

## Required authority

- Worktree: `/home/bobreap/projects/teachers-net-community3`
- Branch: `COMMUNITY3-ui-working`
- DDEV project: `teachers-net-community3`
- Review hostname: `https://teachers-net-community3.ddev.site`
- Plugin source: complete `wordpress/wp-content/plugins/tnet-community` tree

## Gate

1. Run `ddev status` from the authoritative worktree.
2. Confirm the DDEV project name, path, hostname, branch, and exact HEAD.
3. Confirm the mounted plugin path is the complete tree from that worktree.
4. Compare the mounted tree to the authoritative tree with a deterministic
   recursive hash or document the exact DDEV mount that proves identity.
5. Probe Feed, Topic Composer, and a representative Thread at the canonical
   hostname.
6. Confirm authentication and browser review readiness before claiming UX
   acceptance.

Any mismatch stops the ticket. Do not silently substitute `teachers-net`, copy
partial plugin files, or continue from code inspection alone. Continue only
after explicit user acknowledgment of the mismatch.

Every UX completion report must include the canonical URL, DDEV project and
path, mounted plugin path, branch, exact commit, identity evidence, and browser
verification status. A code-only result is not browser acceptance when the
ticket requires rendered evidence.
