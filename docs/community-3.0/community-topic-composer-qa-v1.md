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

## OPS001 runtime boundary

The composer cannot be accepted as visually complete until the dedicated
`teachers-net-community3` DDEV project is healthy. Use the persistent Community
worktree, not the mixed workspace, for all follow-up QA.
