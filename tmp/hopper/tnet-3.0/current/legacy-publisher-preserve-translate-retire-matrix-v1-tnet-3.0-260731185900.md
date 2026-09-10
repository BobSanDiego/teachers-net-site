# Legacy Publisher Preserve / Translate / Retire Matrix v1

| Mechanism or behavior | Decision | Boundary |
|---|---|---|
| Input validation and abuse checks | PRESERVE AS PRODUCT RULE | Re-authorize under current policy and moderation contracts |
| Topic/reply relationship | PRESERVE AS PRODUCT RULE | WordPress parent/thread identity |
| Author/time/title/body semantics | TRANSLATE TO WORDPRESS-NATIVE IMPLEMENTATION | Preserve historical values during import |
| `chat_posts` index | PRESERVE ONLY FOR LEGACY CONTENT | Compatibility/audit record, not new-write authority |
| Public board/post URLs | PRESERVE ONLY FOR LEGACY CONTENT | Resolver, redirects, and immutable archive |
| Static HTML/include/cap output | PRESERVE ONLY FOR LEGACY CONTENT | Snapshot/archive input; Portable Views replace generation |
| Perl CGI and direct file writes | RETIRE | No new Community 3.0 writes |
| SSI execution model | RETIRE | Replace with WordPress rendering/cache boundary |
| `local_path` as group identity | UNKNOWN — EVIDENCE REQUIRED | Explicit mapping required; never assume equality |
| Legacy UID/WP login linkage | UNKNOWN — EVIDENCE REQUIRED | Reconcile to WordPress user identities |
| Legacy mailring remnants | RETIRE | Use approved domain-event/notification contracts |
| Edit/delete/retract/admin behavior | UNKNOWN — EVIDENCE REQUIRED | Separate evidence ticket required |
| Sandy as operational write authority | RETIRE | Only after staged migration and rollback proof |

The central boundary is that business behavior may be preserved while the
execution architecture is retired. “Preserve” never means copy unverified code
or trust an unverified identity mapping.
