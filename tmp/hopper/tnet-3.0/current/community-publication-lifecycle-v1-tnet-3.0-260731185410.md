# Community 3.0 Publication Lifecycle v1

Status: evidence-backed legacy lifecycle map; no implementation authorization.

1. **Request/input:** `chatpost.cgi` renders a POST form targeting the board
   CGI with name, subject, body, email, board/topic, and reply fields.
2. **Authentication/identity:** `chatboard.cgi` consumes legacy CGI/session
   variables including `uid`, `dmn`, `wplogin`, and poster fields where present.
3. **Validation:** `check_profanity`, `check_errors`, required fields, URL
   checks, and legacy spam routines run before the write branch.
4. **Moderation/spam:** `worst_spam`, `possibly_spam`, `check_spamfile`, and
   related checks can reject or warn; no modern moderation contract was found.
5. **Path resolution:** `GetSkinVariables` calls `get_path_data`, reading
   `local_data` and resolving `local_path`, URLs, labels, and legacy context.
6. **Publication:** after validation, `CreatePost`, `IncludeFiles`,
   `UpdateCapfiles`, and `UpdatePostsDB` run in sequence. No notification
   callback is present.
7. **Persistence:** `CreatePost` writes timestamp-named HTML; `IncludeFiles`
   rewrites indexes; `UpdateCapfiles` updates cap files; `UpdatePostsDB` inserts
   the `chat_posts` index row.
8. **Rendering:** static HTML, `include.html`, cap files, and SSI paths render
   the result.
9. **Edit/delete/retract:** no authoritative lifecycle was found in the
   inspected writer source; this remains an open legacy audit item.

Verified legacy identity includes the post file and `chat_posts.post_id`,
author name/optional `wordpress_id`, `local_path`, and `topic_dir` reply
context. Canonical `path_id`, `group_id`, explicit mapping, modern visibility,
and modern moderation are not verified. `path_id == group_id` is not supported.
