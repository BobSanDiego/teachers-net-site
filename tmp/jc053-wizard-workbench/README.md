# JC053 Job Posting Wizard Workbench

Static, non-production design workbench for the JC053 Step 1 School / Jobsite
calibration view. It does not use WordPress, the Jobs plugin, a database, or
production services.

## Open

From the repository root, use the workbench server so transition edits are not
cached by the browser:

```bash
python3 tmp/jc053-wizard-workbench/serve.py
```

Open `http://127.0.0.1:8766/#step-01-return`. Use the Workbench Views select
or the Previous/Next links; do not manually construct view URLs. The stable
implemented view id is `step-01-return`.

## Codex browser verification route

Use the external `chrome-devtools` MCP only for browser verification. The
verified MCP configuration is recorded in `C:\Users\bobre\.codex\config.toml`:

```toml
[mcp_servers.chrome-devtools]
command = "cmd.exe"
args = ["/c", "npx", "-y", "chrome-devtools-mcp@1.6.0", "--browser-url=http://127.0.0.1:9222", "--allow-unrestricted-paths", "--no-usage-statistics"]
```

The external Chrome DevTools target is reached through
`http://127.0.0.1:9222`. Do not retry the WSL `node_repl` or built-in
browser-control bridge for this workbench. Launch or reuse the dedicated QA
Chrome profile with CDP on that endpoint, then open the workbench at
`http://127.0.0.1:8768/#step-01-return` (the current server port may be
documented by the active local server process). Verify the target with the
external MCP page list before inspection.

Verified in the current session: external MCP page discovery and evaluation
connected to the dedicated Chrome target; the selected workbench page was
`http://127.0.0.1:8768/#step-02-job-basics`, with a 1200px shell at a 1440px
viewport. This route is the durable browser-QA method for future workbench
sessions.

The server adds `Cache-Control: no-store, no-cache, must-revalidate, max-age=0`,
`Pragma: no-cache`, and `Expires: 0` to every response. A generic static server
may still be used for inspection, but it does not provide this no-store
guarantee.

## Views

Registered view ids are:

`step-01-first-touch`, `step-01-school-selected`, `step-01-return`,
`step-01-add-physical-us`, `step-01-add-international`,
`step-01-add-multiple-locations`, `step-01-add-additional-info`,
`step-02-job-basics`, `step-03-job-description`, `step-04-application-process`,
and `step-05-review-publish`.

`step-01-initial`, `step-01-school-selected`, and `step-01-return` are implemented. The remaining views
are disabled placeholders and must not be mistaken for authority or production
routes. Both implemented views render through the same shared workbench shell
and wizard shell; their panels contain only state-specific content.

## Adding future states

Reuse the persistent shell in `index.html`. Add a view id to the `views` array
in `mockup.js`, then add only the state-specific markup needed for that view.
Keep shell geometry and shared tokens in `mockup.css`; do not duplicate the
navbar, rail, footer, or stepper.

## Calibration and authority workflow

Central tokens are at the top of `mockup.css`, including the 1200px shell,
250px rail, 950px workspace, spacing, controls, and colors. Diagnostics are
hidden by default. Click **Show diagnostics** to display measured bounding boxes,
viewport, active view id, and horizontal overflow; hide them before any review
screenshot.

At 1440 × 1000, confirm `.application-card`, `.left-rail`, and
`.main-workspace` measure 1200px, 250px, and 950px respectively. This workbench
is supporting design evidence only and does not replace JC-051A or any approved
visual authority.

## Responsive acceptance status

The responsive shell series is provisional implementation evidence. The latest
rollback has been captured at 1440, 1200, 1199, 1100, 1025, and 1024px in the
Windows-visible screenshot directory. At 1025px the full navbar remains; at
1024px compact Resources begins with a 210px brand/rail. Final browser
inspection and human visual acceptance remain open.
## WSL / Windows File Paths and Screenshot Output

### Chrome DevTools MCP Local Screenshot Export

The verified configuration retains the pinned `chrome-devtools-mcp@1.6.0`
server and includes `--allow-unrestricted-paths` because the Codex desktop client
does not currently negotiate MCP roots for this server. The configured Codex
writable root is `C:\\Main\\Active\\Projects\\Teachers.Net`; the canonical
Windows output directory is:

`C:\\Main\\Active\\Projects\\Teachers.Net\\tmp\\jc053-wizard-workbench\\screenshots\\responsive-authority-2026-07-30\\`

The same directory in WSL is:

`/mnt/c/Main/Active/Projects/Teachers.Net/tmp/jc053-wizard-workbench/screenshots/responsive-authority-2026-07-30/`

Windows File Explorer: [open screenshot directory](C:/Main/Active/Projects/Teachers.Net/tmp/jc053-wizard-workbench/screenshots/responsive-authority-2026-07-30/)

Copyable Windows path:
`C:\\Main\\Active\\Projects\\Teachers.Net\\tmp\\jc053-wizard-workbench\\screenshots\\responsive-authority-2026-07-30\\`

After changing MCP configuration, reconnect/restart the MCP server before testing.
Select `http://127.0.0.1:8768/#wizard-authority-v1`, call `take_screenshot` with
the native Windows `filePath`, and verify the PNG exists, has a valid PNG signature,
nonzero size, plausible dimensions, and is readable through both path forms.
Use the naming pattern `authority-requested-<requested>-actual-<actual>.png`;
use sequential `-part-01` segments only if full-page capture fails. Visual
verification is incomplete until requested screenshots exist as accessible local
image files; inline-only captures do not satisfy the deliverable.

The prior error was:
`Access denied: path ... is not within any of the configured workspace roots.`
The proven recovery is the writable Windows root plus
`--allow-unrestricted-paths`; this combination produced the local PNG series
on 2026-07-30. Do not use `/home/...` or create `C:\\home\\...`.

Use `/home/bobreap/projects/teachers-net-site` for shell commands, Git, editing,
and tests. The Windows-mounted artifact/output location is
`/mnt/c/Main/Active/Projects/Teachers.Net`, visible in File Explorer as
`C:\Main\Active\Projects\Teachers.Net`. These locations are not assumed to be
the same checkout; verify repository markers and Git roots before treating them
as synchronized. Never convert `/home/...` into `C:\home\...`.

For external Chrome DevTools MCP screenshot output, use the Windows-visible
directory:

`C:\Main\Active\Projects\Teachers.Net\tmp\jc053-wizard-workbench\screenshots\`

and its WSL form:

`/mnt/c/Main/Active/Projects/Teachers.Net/tmp/jc053-wizard-workbench/screenshots/`

Before capture, create a harmless probe in the output directory, confirm it is
visible through both path forms, and remove it. The MCP must accept the native
Windows path as a configured writable workspace root; do not report screenshot
output as complete unless the PNG exists locally, has a valid signature and
nonzero size, and can be opened from File Explorer. Save local PNGs rather than
only attaching inline screenshots. If a full-page capture fails, save named
viewport segments (`-part-01`, `-part-02`, etc.) covering the complete page.

If screenshot output is blocked, verify the WSL-to-Windows mapping, use the
approved Windows-visible directory, reconnect the external Chrome MCP, and
retry local export. A workspace-root rejection means the corrected MCP command
or writable root is not active; do not claim completion until a real local PNG
exists.

### Screenshot evidence report contract

Every screenshot-producing ticket must report these fields separately:

- Screenshots captured: Yes / No
- Requested export path
- Adapter-returned path
- Canonical saved path
- Canonical file exists: Yes / No
- File readable from WSL: Yes / No
- File readable from Windows: Yes / No

Pass the native Windows path to `take_screenshot.filePath`; do not pass its
`/mnt/c/...` WSL equivalent. The adapter treats the caller-supplied string as
Windows-oriented and a WSL path can be rewritten literally into an invalid
`C:\\mnt\\c\\...` destination. A successful MCP response is not evidence of
durable storage until the canonical PNG is checked at both path forms.

For the shared Teachers.Net evidence root, use:

- Windows: `C:\\Main\\Active\\Projects\\Teachers.Net\\tmp\\evidence\\`
- WSL: `/mnt/c/Main/Active/Projects/Teachers.Net/tmp/evidence/`
- Explorer: [open evidence directory](C:/Main/Active/Projects/Teachers.Net/tmp/evidence/)

This same root may be used by responsive capture and contact-sheet workflows;
do not create a second project-specific export root without an explicit ticket.
