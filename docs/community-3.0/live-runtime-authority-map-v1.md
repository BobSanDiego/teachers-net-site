# UX-AUDIT001 — Live Runtime Authority Map

## Scope

Diagnostic-only evidence collected 2026-08-03. No implementation, cache, route,
fixture, schema, or publication changes were made.

## Canonical review URLs

- Feed: `https://teachers-net.ddev.site/community/`
- Topic Composer: `https://teachers-net.ddev.site/community/new/`
- Representative Thread: `https://teachers-net.ddev.site/community/thread/post:8d59f528a2e11564/`

The Topic Composer redirects unauthenticated requests to WordPress login. The
representative thread is publicly reachable but reports that reply composition
is available only to authenticated local users and therefore does not expose
the authenticated reply DOM in an anonymous request.

## Runtime identity

| Layer | Evidence | Finding |
|---|---|---|
| Browser-reachable host | DDEV status for `/home/bobreap/projects/teachers-net-site` | `teachers-net.ddev.site` is served by the `teachers-net` project. |
| Expected Community worktree | `/home/bobreap/projects/teachers-net-community3`, branch `COMMUNITY3-ui-working` | Separate project; its DDEV web service was stopped during the audit. |
| Mounted runtime source | `ddev exec` in `teachers-net` | `/var/www/html/wordpress/wp-content/plugins/tnet-community` is mounted from the main Teachers.Net site. |
| Entry-point identity | SHA-256 | Runtime entry point equals Community worktree: `ec1109dec0fbb4f0f109da0ca16eeae10d7aaeb7e25250ccf2e42844c8452e55`. |
| Surrounding plugin identity | Recursive comparison | Runtime/main-site plugin is not identical to the Community worktree. The Community worktree has composer, attachment, link-service, and topic-controller files absent from the main-site checkout. |
| DDEV project | `ddev status` | The Community worktree reports `teachers-net-community3`, stopped; the reviewed host reports `teachers-net`, running. |

## Runtime path

The active request path is:

`/community/` → `tnet_community_landing` rewrite/query var →
`TNet_Community_Landing_Controller::render()` → output buffer in
`tnet-community.php` → inline route CSS plus `community-visual-language-v1.css`
and injected feed-card/reply-group scripts → browser DOM.

`/community/thread/<id>/` follows the analogous thread rewrite/query-var path
into `TNet_Community_Thread_Controller::render()`. Authenticated reply markup
is produced through `reply_form_normalized()` and wrapped by
`TNet_Community_Composer_View::reply()`.

`/community/new/` is registered by
`TNet_Community_Topic_Composer_Controller::register()`, but authentication is
checked before the composer is rendered; anonymous evidence therefore stops at
the login redirect.

## Authority conclusion

The reviewed host is not an isolated `teachers-net-community3` runtime. It is a
shared `teachers-net` runtime whose mounted plugin contains the current
Community entry point and some current Community controller changes, while the
full plugin tree is not source-identical to the Community worktree. This is a
runtime/source-boundary defect, not evidence that the accepted UX work is
absent from the Community branch.
