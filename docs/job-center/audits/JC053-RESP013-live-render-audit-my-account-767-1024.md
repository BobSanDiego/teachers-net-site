# JC053-RESP013 Live Render Audit

Date: 2026-07-31
Project: Job Center
Classification: Diagnostic blocker — no implementation applied

## Environment

- Repository: `/home/bobreap/projects/teachers-net-site`
- Branch: `COMMUNITY003-semantic-community-communications-working-draft`
- HEAD at audit start: `fc3855749eefe0d23fc033aeddba69e6835bddb5`
- Worktree: pre-existing unrelated dirty files; no product files changed by this audit
- Server: `python3 -m http.server 8768 --bind 0.0.0.0 --directory tmp/jc053-wizard-workbench`
- Server cwd: `/home/bobreap/projects/teachers-net-site`
- URL: `http://127.0.0.1:8768/?jc053resp013=1#wizard-authority-v1`
- Loaded view: `step-02-job-basics`, authority `true`
- CSS: `http://127.0.0.1:8768/mockup.css?v=jc053-20260730-compact-01`
- JavaScript: `http://127.0.0.1:8768/mockup.js?v=jc053-20260730-navbar-01`
- CSS response: HTTP 200, Python SimpleHTTP/0.6, content length 121276, last modified 2026-07-31 14:52:43 GMT; no cache-control header
- Browser: Chrome 150.0.0.0, Windows 10 user agent, device scale factor 1

The server source and loaded resource paths resolve to the active repository;
they are not the prior `jc054-resp001` directory, an archive copy, or another
checkout. Commit `693c83a` is present in history; the current HEAD also
contains its CSS correction.

## Reproduction

The required widths were tested with cache-bypass navigation and normal
responsive emulation: 1025, 1024, 1000, 900, 768, and 767px. At every width,
pointer/keyboard activation changed `aria-expanded` to `true`, removed `hidden`,
and produced a menu rectangle. At 768px, an ordinary DevTools pointer click
also produced the same state. However, the menu was not visibly painted because
the topbar ancestor clipped it.

At 768px after pointer activation:

- trigger: `.tnet-jobs-employer-app-account-link`, rect x=638.203, y=113.281,
  w=85.797, h=34.547, `aria-expanded=true`;
- menu: one `.tnet-navbar-dropdown-menu`, `hidden=false`, display `block`,
  visibility `visible`, opacity `1`, pointer-events `auto`, position `absolute`,
  z-index `5`, rect x=474, y=160.578, w=250, h=347.25, one client rect;
- menu offset parent: `.tnet-navbar-dropdown`;
- `elementFromPoint()` at the intended menu location: `.main-workspace`, not the
  menu;
- event path: trigger → `.tnet-navbar-dropdown` → account utility region →
  `.tnet-jobs-app-topbar-inner` → topbar;
- mutations: `aria-expanded` changed to true and menu `hidden` changed to false;
- no immediate click-away closure occurred.

## Ancestor paint chain

At 1024px and 768px, the relevant chain is:

| Ancestor | Overflow | Position | Z-index | Result |
|---|---|---|---|---|
| `.tnet-navbar-dropdown-menu` | hidden (its own content box) | absolute | 5 | not the clipping cause |
| `.tnet-navbar-dropdown` | visible | relative | auto | does not clip |
| account utility region | visible | static | auto | does not clip |
| `.tnet-jobs-app-topbar-inner` | visible | relative | auto | prior inner clipping correction is active |
| `.tnet-jobs-app-topbar` | **hidden** | relative | 10 | **clips menu below 60px header** |
| `.application-card` | hidden | static | auto | outer card boundary; not reached by menu because header clips first |
| body/html | visible | static | auto | does not clip |

The menu begins below the 60px topbar boundary. The topbar itself is therefore
the active paint boundary. The menu is not hidden behind another stacking
context and is not immediately closed.

## Cascade/source-order result

The active authority-scoped inner rule is:

`@media (min-width: 768px) and (max-width: 1024px) .tnet-jobs-app-topbar-inner { overflow: visible }`

The remaining active owner is the broader topbar rule:

`.application-card .tnet-jobs-app-topbar { height: 60px; overflow: hidden }`

followed by the authority topbar rule retaining `overflow:hidden`. The exact
smallest likely correction owner is the authority-scoped topbar boundary, not
the menu, account component, event handler, or z-index.

## Event-path conclusion

The existing `NavbarDropdown` binding is attached to the same trigger node that
receives the pointer click. The menu opens in the same task, changes both DOM
state attributes, and remains open after the event loop settles. No duplicate
menu, stale node, rerender replacement, click-away closure, or overlapping
trigger interception was found.

## Classification and recommendation

Classification: **A. Active CSS clipping or stacking defect**.

RESP012 reported a pass because it checked `aria-expanded`, `hidden=false`,
menu geometry, and focus but did not inspect the complete ancestor chain or
paint hit-testing. Those checks prove DOM state, not visible deployment.

The next implementation ticket should be narrowly scoped to moving the
topbar’s overflow boundary to `visible` for the affected authority shell,
followed by screenshot and hit-testing verification. Risks include allowing
other topbar descendants to paint beyond the header and interacting with the
application-card boundary; do not apply that change until a bounded patch ticket
authorizes it.

## Telemetry

- Trigger / first recorded environment checkpoint: 2026-07-31T08:26:17-07:00
- Environment identification: 2026-07-31T08:26:17-07:00 to 08:26:27-07:00
- Reproduction matrix: 2026-07-31T08:26:27-07:00 to 08:26:38-07:00
- DOM/computed-style audit: 2026-07-31T08:26:38-07:00 to 08:27:10-07:00
- Event-path audit: 2026-07-31T08:27:10-07:00 to 08:27:45-07:00
- Evidence packaging: recorded in the cycle manifest
- Report: recorded in the cycle manifest
- Git: **Not invoked**; diagnostic report remains uncommitted pending final packaging
- Hopper: recorded in the cycle manifest
- Total elapsed: recorded at final packaging; no estimate supplied

No product implementation was changed.
