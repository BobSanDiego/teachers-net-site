# RUNTIME-BLOCKER003 — Completion Report

## BLOCKER STATUS: RESOLVED

### Browser badge commit

`4d1b3a3f0f75b620df7faf55dcd1cce4b6d9a03f`

### Authoritative HEAD

`4d1b3a3f0f75b620df7faf55dcd1cce4b6d9a03f`

### Exact canonical URLs

- Feed: `https://teachers-net-community3.ddev.site/community/`
- Topic Composer: `https://teachers-net-community3.ddev.site/community/new/`
- Representative Thread: `https://teachers-net-community3.ddev.site/community/thread/post:8d59f528a2e11564/`

### Root cause

The previous badge read `C3_AUTHORITY_COMMIT` from `.ddev/config.yaml`. That
value was pinned to `4ffa068…` and was not automatically advanced when the
later commit `7a4c585…` was created. The container could not resolve the
worktree `.git` indirection because it referenced a host-only worktree admin
path, so the badge could not independently derive HEAD.

### Correction

- Removed the fixed commit pin as the authority source.
- Added generated `.ddev/runtime-authority.json`, produced by the fail-closed
  `tools/qa/runtime_authority_preflight.sh` from the current worktree HEAD.
- The runtime badge reads the generated branch, commit, and plugin-tree record.
- The badge shows `RUNTIME AUTHORITY MISMATCH` when the record and serving facts
  disagree.
- Restarted the dedicated DDEV runtime to clear PHP/container state.
- Verified the canonical browser response after hard navigation.

### Screenshot command

Start the canonical Chrome session, then run from the Community3 worktree:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File '\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\launch-chrome-cdp-9222.ps1' -Url 'https://teachers-net-community3.ddev.site/community/'
```

```powershell
cmd.exe /c "pushd \\\\wsl.localhost\\Ubuntu-24.04\\home\\bobreap\\projects\\teachers-net-community3 && set C3_QA_USER=community.qa && set C3_QA_PASS=REDACTED_USE_APPROVED_LOCAL_SECRET_STORE && C:\\Users\\bobre\\.cache\\codex-runtimes\\codex-primary-runtime\\dependencies\\node\\bin\\node.exe assets\\runtime-screenshot-capture.mjs"
```

### Screenshot artifacts

Final authenticated capture set:

`/home/bobreap/projects/teachers-net-community3/tmp/qa/runtime-20260803T195904Z/`

The manifest contains 12 successful captures: Feed, Composer, and Thread at
1440, 1024, 768, and 390 pixels. Every capture reports HTTP 200, badge status
`ok`, and commit `4d1b3a3f0f75b620df7faf55dcd1cce4b6d9a03f`.

### Independent verification

- DDEV web/database: OK.
- Browser badge commit equals `git rev-parse HEAD`.
- Runtime plugin path: `/var/www/html/wordpress/wp-content/plugins/tnet-community`.
- Authoritative worktree: `/home/bobreap/projects/teachers-net-community3`.
- Runtime plugin tree hash: `86141af0dc6f41103d0dc5f2aa371fa12b125e5c96310d8a7ece6e790344c0e9`.
- Browser screenshot manifest records the same badge values for all 12 captures.
- Authenticated Topic Composer and Thread captures succeeded.
- PHP lint and `git diff --check` passed.

### Remaining product discrepancies

Runtime alignment is complete; product completion is not being claimed. The
Topic Composer screenshot still shows the existing image alt-text field and
Representative Link/Preview controls. Those are product/UX discrepancies for
separate tickets and were not changed by this runtime blocker.

### Commit and push

- Commit: `4d1b3a3f0f75b620df7faf55dcd1cce4b6d9a03f`
- Branch: `COMMUNITY3-ui-working`
- Push: successful

### Hopper

This report and the final screenshot manifest are included in the validated
`tnet-3.0` hopper cycle for this ticket.
