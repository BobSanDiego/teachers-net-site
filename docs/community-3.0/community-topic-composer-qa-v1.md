# Community Topic Composer v1 QA

## Automated checks

- PHP lint on the plugin PHP files.
- Existing Community contract tests.
- Static checks for nonce, authentication, escaped output, PRG redirect, and
  absence of SQL in the controller.
- `git diff --check`.

## Browser checklist

At `https://teachers-net.ddev.me/community/new/` in the local DDEV environment,
using an authenticated local account, verify desktop, tablet, and mobile widths:

1. labels, controls, and publish action are readable;
2. no horizontal overflow occurs;
3. missing title/body and invalid Community show nearby errors;
4. valid submission redirects to `/community/thread/{opaque-id}/`;
5. replaying the same browser response does not create a second record;
6. keyboard focus and no-JavaScript form submission work.

Visual completion requires human browser evidence; source or CLI checks alone
do not claim that portion complete.

## C3-OPS003 verification record

Against the existing local Teachers.Net clone at `https://teachers-net.ddev.site`:

- Landing page rendered at HTTP 200 and was reviewed at desktop and mobile widths.
- Seeded Thread View rendered successfully at `/community/thread/post:2064f7fc314c6f0f/`.
- Anonymous `/community/new/` access redirected to WordPress login.
- An authenticated local QA account rendered the composer and published a topic.
- Final successful QA Thread View: `/community/thread/post:8e78f134fdbc8a86/`.
- Community identifier sanitization and encoded-colon redirect defects found in
  QA were corrected; PHP lint and Community tests then passed.
- A hidden submission identifier is carried through the form for replay
  deduplication. The disposable local QA records and account must be removed
  when the QA fixture is retired.

## OPS001 runtime boundary

The composer cannot be accepted as visually complete until the dedicated
`teachers-net-community3` DDEV project is healthy. Use the persistent Community
worktree, not the mixed workspace, for all follow-up QA.
