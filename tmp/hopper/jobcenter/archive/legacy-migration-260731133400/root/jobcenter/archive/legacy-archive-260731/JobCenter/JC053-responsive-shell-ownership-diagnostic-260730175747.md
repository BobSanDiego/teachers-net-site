# JC053 Responsive Shell Ownership and Override Diagnostic

**Date:** 2026-07-30
**Scope:** Static JC053 workbench only
**Method:** External `chrome-devtools-mcp` against the served workbench at
`http://127.0.0.1:8768/#wizard-authority-v1`; source inspection plus live DOM,
computed-style, and bounding-rectangle evaluation.
**Implementation changes:** None.

## A. Render ownership map

| Region | Source | Owner / render order | Runtime type |
|---|---|---|---|
| Application card and topbar | `tmp/jc053-wizard-workbench/index.html:49-74` | Static shell markup, before the body | Static markup |
| Brand and Job Center identity | `index.html:51-58` | `.tnet-jobs-app-brand-region`, then `.tnet-jobs-app-product-region` | Static markup |
| Primary navbar and Resources overflow | `index.html:59-69` | Static links/menu; visibility is CSS-owned | Static markup; menu state is JS-mutated on activation |
| Notification and My Account | `index.html:70-72` | `.tnet-jobs-app-utility-region` | Static markup |
| Employer rail | `index.html:76-84` | `.left-rail` | Static markup |
| Wizard workspace and panels | `index.html:86-123` | `mockup.js:603-628`, then shell wrapper at `mockup.js:1215-1221` | Static panels, visibility/state injected |
| Footer | `index.html:126` | `.tnet-jobs-app-footer` | Static markup; CSS-only responsive layout |
| Stepper | `mockup.js:269-321`, `932-956`, `1182-1184` | `WizardStepper.render()` | Generated markup inside one static stepper root |
| Wizard shell | `mockup.js:1152-1212` | `renderWizardShell()` | Shared renderer; replaces panel content and bottom navigation |
| Authority mobile menu | `mockup.js:1155-1168` | Created once when authority config is rendered | Generated once; class mutation on toggle |

No second application-card, footer, navbar, or WizardShell root is created.
The shared shell is structurally canonical, but its CSS has multiple competing
responsive authorities.

## B. Breakpoint inventory

Relevant rules are concentrated in `tmp/jc053-wizard-workbench/mockup.css`:

- **Lines 243-246:** older authority rules set the card to fluid below 1200px,
  change the body to block, stack the footer below 768px, and alter topbar
  layout below 767px.
- **Lines 250-261:** an earlier “authority-only responsive refinement” sets a
  210px rail for 768–1199px and hides the ordinary navbar.
- **Lines 262-268:** an overlapping 768–900px rule changes the rail to 180px.
- **Lines 269-272:** authority divider positioning is tied to
  `--jc053-shell-column`.
- **Lines 273-288:** mobile authority rules hide the rail and alter stepper,
  workspace, and action layout below 768px.
- **Lines 290-309:** shared-axis rules redefine the shell column to 250px at
  1024–1199px, 210px at 901–1023px, and 180px at 768–900px.
- **Lines 310-314:** mobile authority body/brand rules apply below 768px.
- **Lines 315-316:** later unscoped navbar rules redefine the grid and contain
  legacy breakpoint rules that hide Teacher Resources at 1100–1199px, hide
  Career and Teacher Resources at 901–1099px, and expose all legacy overflow
  content below 901px.
- **Lines 318-320:** later unscoped and authority-scoped grid declarations
  compete for the same topbar children and grid rows.
- **Lines 321-324:** later authority rules attempt to restore full links at
  `min-width:1025px`, compact links at `768–1024px`, and 250/210/180 shell
  axes, but do not neutralize the later unscoped mobile rules in all cases.

The primary contradiction is that the intended 1025–1199 state requires all
three full navbar links, while the unscoped rule at line 315 hides Teacher
Resources at 1100–1199px. The intended mobile state requires one unified
Resources trigger, while the unscoped rules expose the legacy navbar links at
600px and 500px.

## C. Runtime mutation inventory

`mockup.js` does not contain a resize handler that changes responsive classes,
inline layout styles, or shell markup. The only resize listener is
`mockup.js:1015`, which calls `measure()` and updates the hidden diagnostics
text at `990-1006`.

State/render mutations are separate from viewport responsiveness:

- `mockup.js:603-628` selects the hash view, toggles panel `hidden`, and sets
  `html[data-authority]` and `card[data-authority]`.
- `mockup.js:647-715` replaces `.view-nav` and binds the generated navigation.
- `mockup.js:917-930` inserts or removes `#authority-marker`.
- `mockup.js:932-960` updates the stepper and state navigation.
- `mockup.js:961-987` changes hash state with `history.replaceState()` and
  responds to hash changes.
- `mockup.js:1152-1212` creates the authority mobile toggle once, toggles
  `authority-nav-open`, replaces wizard panel content, and regenerates bottom
  navigation.

No runtime path writes responsive `display`, `grid`, `flex`, `order`,
`position`, `transform`, width, or margin values. The observed divergence is
CSS cascade ownership, not JavaScript viewport mutation.

## D. Live computed-state matrix

All rows were captured from the selected live page with
`card[data-authority="true"]`. Shell width is the rendered card width;
workspace origin is the live `getBoundingClientRect().x`.

| Requested | Actual | Shell | Brand | Rail | Workspace origin | Visible primary navbar | Alignment | Account right inset |
|---:|---:|---:|---:|---:|---:|---|---|---:|
| 1440 | 1440 | 1200 | 250 | 250 | 363.5 | My Jobs, Career Resources, Teacher Resources | flex-start | 128.5 |
| 1200 | 1200 | 1200 | 250 | 250 | 251 | My Jobs, Career Resources, Teacher Resources | flex-start | 1 |
| 1199 | 1199 | 1160 | 250 | 250 | 263 | My Jobs, Career Resources, overflow Resources | flex-end | 28 |
| 1100 | 1100 | 1061 | 250 | 250 | 263 | My Jobs, Career Resources, overflow Resources | flex-end | 28 |
| 1025 | 1025 | 986 | 250 | 250 | 263 | My Jobs, Career Resources, overflow Resources | flex-end | 28 |
| 1024 | 1024 | 985 | 210 | 210 | 223 | unified Resources | center | 28 |
| 900 | 900 | 861 | 210 | 210 | 223 | unified Resources | center | 28 |
| 768 | 768 | 729 | 210 | 210 | 223 | unified Resources | center | 28 |
| 600 | 600 | 561 | 347.2 | 0 | 13 | My Jobs, Career Resources, Teacher Resources, Resources | flex-end | 40 |
| 500 | 500 | 461 | 247.2 | 0 | 13 | My Jobs, Career Resources, Teacher Resources, Resources | flex-end | 40 |
| 390 | 500 | 461 | 247.2 | 0 | 13 | My Jobs, Career Resources, Teacher Resources, Resources | flex-end | 40 |

At 390px the browser clamps the actual viewport to 500px. The 600/500/390
rows are the confirmed rendered-state divergence: the rail is removed, but the
legacy navbar links reappear and the brand region expands to absorb the row.

The active CSS state is represented by `data-authority="true"`; no responsive
class or inline style is added by JavaScript. At 1199/1100/1025 the active
winning layout is the later 1025–1199 rule plus the unscoped link-hiding rule.
At 600 and below, the later unscoped mobile grid/link rules win over the
intended unified Resources presentation.

## E. Root-cause findings

### 1. Primary — competing CSS authorities in one stylesheet

`mockup.css` contains several successive responsive passes. The authority
rules at lines 290–324 are not the sole owner because later unscoped rules at
315–316 and overlapping declarations at 318–321 still target the same shell.
This creates width-dependent behavior that is not derivable from one breakpoint
contract.

### 2. Secondary — legacy partial-collapse rules violate the current contract

The unscoped rules hide Teacher Resources at 1100–1199px and hide Career and
Teacher Resources at 901–1099px. This directly violates the intended full
navbar through 1025px. The runtime matrix confirms the effect at 1199, 1100,
and 1025.

### 3. Secondary — unscoped mobile rules reopen the legacy navbar

At 600, 500, and the browser-clamped 390 request, the unscoped rules expose
the three legacy links while the authority still exposes the Resources control.
This produces four visible primary-navigation items and changes the brand/rail
geometry. This is the strongest rendered-state divergence.

### 4. Contributing — breakpoint definitions are internally inconsistent

The file uses 1199, 1099, 1024, 1023, 900, 901, 768, and 767 boundaries, with
different meanings across successive passes. The 250px rule is authoritative
at 1025–1199, while the earlier generic 210px rule also covers 768–1199.
Later order currently makes the 250px result win, but the duplication is fragile.

### Harmless symptoms

- `topbar` being `hidden` overflow at constrained desktop widths is not itself
  the root cause; it prevents visual spill but does not choose the wrong links.
- The 390 requested width being clamped to 500px is a browser limitation and is
  correctly recorded as requested versus actual width.
- The 1440 workspace origin is inside a centered 1200px card and is not a
  shell-axis failure.

### Not found / uncertain

- No JavaScript resize mutation was found.
- No duplicate shell DOM was found.
- No production route or production CSS was involved.
- Event-listener enumeration was not required to establish the ownership root
  cause; the source/runtime evidence is already conclusive.

## F. Smallest correction plan (not implemented)

1. Make one authority-scoped responsive block the sole owner of the JC053
   navbar, shell axis, rail, footer, and workspace responsive rules.
2. Remove or subordinate the unscoped legacy rules at `mockup.css:315-316`
   and reconcile the overlapping declarations at `318-324`; do not add a new
   breakpoint or a specificity escalation as the fix.
3. Keep the shared `--jc053-shell-column` token as the owner of brand width,
   visible rail width, body divider, and workspace origin.
4. Keep JavaScript responsible only for state/view rendering and the explicit
   mobile rail toggle; do not add viewport-driven DOM mutation.
5. Verify at exactly 1440, 1200, 1199, 1100, 1025, 1024, 900, 768, 600, 500,
   and browser-reported actual 390 (currently clamped to 500). Assert visible
   links, rail/brand widths, account right anchor, workspace origin, and no
   horizontal overflow.

The later implementation ticket should be CSS-only unless the rendered
verification demonstrates a state-configuration defect. No HTML, JavaScript,
production, or authority change is justified by this diagnostic.

## Verification and repository state

- External Chrome DevTools live inspection: PASS.
- Runtime matrix captured at all requested widths: PASS.
- `git diff --check`: PASS.
- Production files changed: none.
- Parent repository and nested Jobs plugin status were not cleaned or reset;
  pre-existing dirty work was preserved. The diagnostic itself adds only this
  report and its required hopper copies.
