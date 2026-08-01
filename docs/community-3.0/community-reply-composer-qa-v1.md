# Community Reply Composer v1 QA

Review thread:
`https://teachers-net.ddev.site/community/thread/post:c2b49047485cf50d/`

Verified locally against the existing Teachers.Net clone:

- Anonymous reply submission redirects to login.
- Authenticated direct reply succeeds and returns to
  `#reply-post:3f7dc2c069fb2633`.
- Authenticated nested reply succeeds and returns to
  `#reply-post:7dd3137d60ae6528`.
- Direct and nested replies appear in deterministic order with the expected
  parent/thread relationship.
- Browser replay uses the carried submission identifier and the repository's
  unique submission boundary.
- PHP lint, 89 Community tests, and `git diff --check` pass.
- Mobile screenshot review passed at 390px; no horizontal overflow was found
  at 390px, 768px, or 1024px.

No edit, delete UI, reaction, notification, mention, media, migration, CGI, or
production behavior was added.
