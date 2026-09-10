# Community 3.0 Publisher Source Census v1

Status: read-only repository and Sandy source discovery. No production change.

## Authority result

**PUBLISHER EXISTS BUT SOURCE IS NOT OWNED/AVAILABLE.** The live Teachers.Net
publisher was identified on Sandy, but the source is not present in the
`teachers-net-site` repository and is not an owned WordPress plugin, mu-plugin,
or theme component.

## Evidence

| Source | Evidence | Status |
|---|---|---|
| `/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi` | Perl CGI contains submission gate, `CreatePost`, `IncludeFiles`, and `UpdatePostsDB` | Active legacy publisher source on Sandy; not locally owned. |
| `/var/www/www.teachers.net/cgi-bin/chatboard/chatpost.cgi` | Renders the chatboard post form and submits to the CGI seam | Active legacy input surface. |
| `/var/www/www.teachers.net/cgi-bin/chatboard/posted.cgi` | Post-success/mailring presentation | Post-publication presentation, not writer. |
| `/var/www/www.teachers.net/htdocs/chatboard` | Published HTML/static chatboard path | Rendered output, not canonical writer. |
| `chat_posts` in `UpdatePostsDB` | Insert includes post URL/type/time/chatboard URL/title/author/WordPress ID | Legacy index/audit persistence. |
| `profilaxes` / Core Terms | Classification and term APIs | Not Community post authority. |
| `tnet-jobs` | Separate Jobs product code | Not Community post authority. |

The local repository search found no owned Community writer or equivalent
`wp_insert_post` path. Sandy inspection found the legacy CGI tree, including
archived variants, but no deployment/source correspondence to this Git
repository was established. Only metadata and bounded source excerpts were
inspected; operational credentials and personal data were not copied.

## Identity and lifecycle evidence

The CGI resolves `target_local_path` through `get_path_data`, using the
`local_data` table. It carries legacy `uid`/`wplogin`-style identity where
available, but the inspected writer does not provide canonical `group_id`.
`path_id` is not present in the writer evidence. Mapping to the canonical
teacher group remains unavailable and must not be inferred.

The writer creates timestamp-named HTML files, updates include/cap files, and
inserts a `chat_posts` row. Replies use `topic_dir` and `post_type=reply`; new
posts use `post_type=post`. No modern post-publication event callback exists in
the inspected source.
