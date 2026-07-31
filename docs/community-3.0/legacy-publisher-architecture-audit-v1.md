# Legacy Community Publisher Architecture Audit v1

Status: read-only audit; no production mutation.

## Executive finding

The Sandy publisher is worth preserving as a behavioral reference and as a
legacy-content compatibility boundary. It is not a suitable foundation for a
WordPress-native Community 3.0 publisher. The verified implementation is a
Perl CGI/file/database system whose correctness depends on deployment-local
paths, SSI, mutable include files, and legacy identity inputs.

The active writer is `/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi`.
Related surfaces are `chatpost.cgi` and `posted.cgi`; output is under
`/var/www/www.teachers.net/htdocs/chatboard`. The writer validates input,
creates timestamp-named HTML, rewrites include/cap material, and inserts a
`chat_posts` row. It does not expose a verified canonical `group_id` mapping or
a modern post-publication event.

## Subsystem findings

| Subsystem | Verified behavior and dependencies | Classification | Migration note |
|---|---|---|---|
| Routing/form/submission | `chatpost.cgi` submits to `chatboard.cgi`; CGI parameters select board/topic | TRANSLATE TO WORDPRESS-NATIVE IMPLEMENTATION | Preserve public routes through an explicit compatibility resolver |
| Identity/authentication | Legacy `uid`, `wplogin`, and related context may be carried | UNKNOWN — EVIDENCE REQUIRED | Do not equate legacy identity with current WordPress authentication |
| Validation/spam | `check_profanity`, `check_errors`, spam-file checks, and post errors gate writes | PRESERVE AS PRODUCT RULE | Re-specify with current policy, moderation, and privacy controls |
| Board/path resolution | `get_path_data` reads `local_data` and returns `local_path`/legacy fields | PRESERVE ONLY FOR LEGACY CONTENT | Require an explicit `local_path` to `group_id` mapping |
| Publication | `CreatePost` writes HTML; `UpdatePostsDB` writes `chat_posts` | TRANSLATE TO WORDPRESS-NATIVE IMPLEMENTATION | WordPress post state must be canonical |
| Replies/threads | `topic_dir` selects the thread; rows distinguish `post` and `reply` | PRESERVE AS PRODUCT RULE | Map to explicit parent/thread identities |
| Include/cap/SSI | `IncludeFiles`, `UpdateCapfiles`, and SSI/static rendering maintain views | PRESERVE ONLY FOR LEGACY CONTENT | Replace with Portable Views and cache invalidation |
| Editing/deletion/moderation | Not verified in the inspected writer boundary | UNKNOWN — EVIDENCE REQUIRED | Require separate evidence before promising compatibility |
| Mailring/notifications | Related remnants may exist, but no modern event seam was verified | RETIRE | Use Community 3.0 notification contracts only after publisher authority exists |
| URLs/search/archive | Static paths and indexed URLs are operationally valuable | PRESERVE ONLY FOR LEGACY CONTENT | Build redirects/immutable archive records and verify crawl behavior |
| Errors/recovery | Multi-file writes can leave file/database divergence | RETIRE | Use transactional state and replayable audit records |
| Concurrency/backup | No sufficient locking/idempotency evidence was established | UNKNOWN — EVIDENCE REQUIRED | Characterize before any migration cutover |

## Architecture assessment

Perl CGI request handling, direct filesystem writes, include rewriting, cap
files, SSI, timestamp filenames, and `local_path` are reusable as reference
only or transitional compatibility mechanisms. Static HTML and file-plus-
database dual state are operational risks for new writes. `chat_posts` is a
valuable legacy index, but not by itself a modern aggregate. Spam routines are
valuable behavioral evidence, not code to port without review. `topic_dir`
threading is a useful observed rule. No safe basis was found for direct reuse.

## Security and reliability

The principal risks are trust in CGI parameters and legacy identity fields,
filesystem path construction, injection surfaces in old Perl/database paths,
authorization ambiguity, partial HTML/database writes, privacy leakage in
legacy records, and single-host dependency on Sandy/SSI. This was not a
penetration test. Severity is migration-relevant: high for identity,
authorization, path safety, and dual-state divergence; medium for URL and
archive compatibility; unknown for unverified edit/delete/admin paths.

## Conclusion

Preserve observed user and archive behavior; translate it into WordPress-native
contracts; wrap only read-only legacy URLs/content during transition; retire
legacy execution for new Community 3.0 writes.
