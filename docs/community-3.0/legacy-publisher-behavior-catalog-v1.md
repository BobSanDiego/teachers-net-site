# Legacy Community Publisher Behavior Catalog v1

This catalog separates observed behavior from intent. A function name or
database field is not treated as proof of a product contract.

| Behavior | Evidence boundary | Inputs/outputs | Classification |
|---|---|---|---|
| Board form submission | `chatpost.cgi` and CGI handoff | request fields → validation/write branch | TRANSLATE TO WORDPRESS-NATIVE IMPLEMENTATION |
| Profanity/error gate | `check_profanity`, `check_errors` | title/body/name → post error or continuation | PRESERVE AS PRODUCT RULE |
| New topic creation | `create_folder`, `CreatePost` | board/topic data → directory and HTML | PRESERVE AS PRODUCT RULE; translate storage |
| Reply creation | `topic_dir`, `CreatePost` | topic and reply data → child HTML/row | PRESERVE AS PRODUCT RULE |
| Timestamp filename | `SetDate` | date/time → filename | PRESERVE ONLY FOR LEGACY CONTENT |
| Include update | `IncludeFiles` | post URL/title → include/index rewrite | PRESERVE ONLY FOR LEGACY CONTENT |
| Cap update | `UpdateCapfiles` | post metadata → cap material | PRESERVE ONLY FOR LEGACY CONTENT |
| Index persistence | `UpdatePostsDB` | URL/type/time/board/title/author/WP ID → `chat_posts` row | TRANSLATE; retain legacy rows |
| Legacy board lookup | `get_path_data`/`local_data` | board key → `local_path` and context | UNKNOWN — EVIDENCE REQUIRED |
| Legacy identity linkage | `wplogin`/UID context | request/session context → author field | UNKNOWN — EVIDENCE REQUIRED |
| Post-success presentation | `posted.cgi` | result state → user-facing response/mailring surface | PRESERVE ONLY FOR LEGACY CONTENT |
| Modern publication event | none found | no verified callback | RETIRE legacy assumption; define new contract |
| Edit/delete/retract | not verified | unknown | UNKNOWN — EVIDENCE REQUIRED |
| Moderation/admin | partial legacy checks only | unknown operator paths | UNKNOWN — EVIDENCE REQUIRED |

The catalog does not authorize implementation. The missing items are precisely
the items that must not be inferred: canonical group mapping, permission
semantics, anonymous posting status, edit/delete behavior, and recovery rules.
