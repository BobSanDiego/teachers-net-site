# Community 3.0 Desktop Shell Width Lab

Standalone, non-production layout experiment for `COMMUNITY-LAYOUT-LAB001`.
It contains one realistic Community post specimen and four switchable shell-width modes.

## Open

Open `index.html` directly in a desktop browser, or serve this directory with:

```bash
python3 -m http.server 8765 --bind 127.0.0.1
```

Then visit `http://127.0.0.1:8765/`.

## Modes

- 1280px global shell
- 1440px global shell
- 1680px global shell
- No global max-width

Navbar alignment can also be switched between constrained contents and Facebook-style pinned rails. The navbar background remains full viewport width in both cases.

The header and footer remain viewport-wide in all modes. The center post column remains capped at 800px and the right advertising rail is exactly 300px while it fits.

## Responsive breakpoints

- Above 1450px: full left navigation, bounded center, and 300px right rail.
- 1240px and below: rail reduces to 190px and the right rail yields.
- 980px and below: tighter header and shell spacing.
- 760px and below: navigation becomes a horizontal compact strip and the post cards stack.

These are experiment breakpoints, not an architectural decision. The diagnostic overlay reports viewport, active mode, computed shell width, center width, and right-rail visibility.

## Observations

The bounded center remains readable at wide widths because surplus space is absorbed outside the post measure. At narrower desktop widths the fixed advertising rail yields before the central content becomes unusably narrow. This artifact is design evidence only and must not be treated as production implementation.
