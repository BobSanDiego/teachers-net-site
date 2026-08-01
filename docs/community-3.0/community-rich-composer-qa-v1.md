# Community Rich Composer QA v1

- PHP lint passed in the existing local DDEV web container.
- Unauthenticated `/community/new/` requests redirect to WordPress login.
- Markup checks passed for post modes, attachment empty state, link field,
  progressive `<details>`, labelled sections, and focus styling.
- Ordinary text topic publication passed through the existing publisher
  application and repository.
- `git diff --check` passed.

Uploads, link enrichment, Open Graph, Twitter/X Cards, oEmbed, polls, events,
feed cards, notifications, AI, storage changes, production, and mature
composer JavaScript were not implemented.

Authenticated browser screenshots were unavailable because browser control
could not initialize in this WSL session. Review page:
`https://teachers-net.ddev.site/community/new/`. Repeat visual review in the
canonical Chrome QA session before claiming final visual completion.
