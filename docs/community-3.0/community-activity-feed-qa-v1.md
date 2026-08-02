# Community Activity Feed QA v1

- DDEV PHP lint passed for the feed read model and controller.
- HTTP 200 smoke test passed at `https://teachers-net.ddev.site/community/`.
- Mixed local content rendered: text-only cards, image fixture card, and
  mocked preview/card projections.
- Card links continued to resolve to canonical `/community/thread/{id}/`
  routes.
- Author, title, excerpt, reply count, and last activity rendered.
- Text remained readable alongside attachment cards.
- No live fetch, ranking, personalization, notification, or production path
  was introduced.
- `git diff --check` passed.

Authenticated browser screenshots at 1440px, 1024px, 768px, and 390px remain
pending because browser control could not initialize in this WSL session. No
visual acceptance is claimed.
