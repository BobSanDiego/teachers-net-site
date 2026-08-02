# Community Link Preview QA v1

- DDEV PHP lint passed for the preview model, attachment service, repository,
  bootstrap, and composer controller.
- Fixture checks passed for `keep`, `remove`, and `raw` author choices.
- Composer markup check passed for preview preference controls and the local
  fixture placeholder.
- Cached preview repository round-trip passed through existing compatibility
  JSON; no schema change was made.
- Existing ordinary text publishing remains covered by prior compatibility
  checks.
- `git diff --check` passed.

Live fetching, SSRF behavior, uploads, feed cards, notifications, and
production were deliberately not implemented. Review route:
`https://teachers-net.ddev.site/community/new/`.
