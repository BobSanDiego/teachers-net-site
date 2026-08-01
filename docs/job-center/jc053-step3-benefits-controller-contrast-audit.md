# JC053 Step 3 Benefits Controller and Contrast Audit

**Ticket:** JC053-STEP003-DIAG009  
**Review URL:** `http://127.0.0.1:8768/#step-03-job-description`  
**Branch:** `JOB-CENTER-JC053-wizard-workbench`  
**Implementation baseline:** `13d0532` / `jc053-13d0532`  
**Runtime root:** `/tmp/jobcenter-ops-git-x001/tmp/jc053-wizard-workbench`

## Runtime identity

The live build banner matched the requested branch, commit, asset identifier,
and runtime root. CSS and JS loaded with `?v=jc053-13d0532`; the runtime was
not stale or sourced from another worktree.

## Live DOM and controller owners

Ordinary disclosures each render:

```html
<details><summary>Responsibilities</summary>...</details>
```

Benefits renders:

```html
<details class="step3-benefits">
  <summary>Benefits<span class="step3-benefits-help">Click items to add or remove benefits from your job listing.</span></summary>
  ...
</details>
```

All four summaries compute as `display: list-item` and
`list-style-type: disclosure-closed`. Responsibilities, Preferred
Qualifications, and About Our School have only a text child. Benefits has a
text node plus a guidance span.

Benefits has two visible controller glyph owners in the live render:

1. **Native owner:** the summary `::marker`, supplied by the browser’s native
   `details` disclosure behavior. This is the correct right/down stateful
   controller and toggles with the details element.
2. **Duplicate owner:** `.step3-optional-sections details.step3-benefits >
   summary::before`, a 6×6px rotated border glyph. It remains visible because
   its specificity is higher than the later `.step3-benefits >
   summary::before` suppression rule.

The duplicate is decorative and does not provide a second accessible control,
but it creates misleading double-controller visuals. Clicking the summary
toggles the same `<details>` element; the native marker changes state, while
the duplicate pseudo-glyph does not have independent behavior.

## Computed comparison

At 1440px, collapsed:

| Property | Ordinary summary | Benefits summary |
|---|---:|---:|
| display | `list-item` | `list-item` |
| list-style | `inside none disclosure-closed` | `inside none disclosure-closed` |
| list-style-type | `disclosure-closed` | `disclosure-closed` |
| list-style-position | `inside` | `inside` |
| padding | `12px 0` | `12px 0` |
| height | 41.390625px | 41.390625px |
| native marker | present | present |
| `::before` | `content:none` | empty generated content, 6×6px rotated border |
| `::after` | `content:none` | `content:none` |

The native marker is the correct stateful glyph. The Benefits `::before` is the
remaining redundant visual owner. The collapsed guidance is `display:none`;
when expanded it is inline, italic, normal-weight, and secondary.

## Cascade trace

The relevant CSS is in the minified Step 3 CSS block (`mockup.css`, source
blocks at lines 670–673):

| Selector | Specificity | Result |
|---|---:|---|
| `.step3-optional-sections summary` | `(0,1,1)` | shared summary font/padding owner; active |
| `.step3-benefits>summary` | `(0,1,1)` | Benefits summary owner; later list-style restoration wins |
| `.step3-benefits>summary::after` | `(0,1,2,1)` | historical caret; neutralized by later `content:none` |
| `.step3-benefits[open]>summary::after` | `(0,2,2,1)` | historical open caret; neutralized |
| `.step3-optional-sections details.step3-benefits>summary::before` | `(0,2,2,1)` | **wins and creates duplicate 6×6px rotated glyph** |
| `.step3-optional-sections details.step3-benefits[open]>summary::before` | `(0,3,2,1)` | open duplicate rotation owner; wins when open |
| `.step3-benefits>summary::before` | `(0,1,2,1)` | lower-specificity suppression; loses to the two rules above |
| `.step3-benefits>summary::after` | `(0,1,2,1)` | later suppression; wins over same-specificity historical after rule |
| `.step3-benefits>summary::-webkit-details-marker` | pseudo-element rule | restores native marker display but does not remove `::before` |

The append-only CSS history is the root cause of the unresolved defect. The
wrong rule was not removed; later lower-specificity suppression attempted to
counteract it and could not win.

## Toggle behavior

Keyboard and pointer activation of the summary toggle the same native
`<details>` element. The native marker changes from collapsed to expanded
state. The pseudo-element duplicate is decorative and has no independent
toggle state, so it remains a misleading redundant glyph.

## Muted-text contrast audit

Computed muted foreground was `rgb(100, 113, 141)` (`#64718d`) at 11–12px,
normal weight. Actual Benefits text sits on the white `.panel` background.
The preview note sits on computed `rgb(247, 249, 252)` (`#f7f9fc`). Ratios
were calculated with the WCAG relative-luminance formula.

| Context | Selector | Size/weight | Actual background | Ratio | AA normal |
|---|---|---|---|---:|---|
| Expanded guidance | `.step3-benefits-help` | 11px / 400 italic | `#ffffff` | 4.90:1 | Pass |
| Unselected option | `.step3-benefit-option:not(.is-selected)` | 12px / 400 | `#ffffff` | 4.90:1 | Pass |
| Empty state | `.step3-benefits-empty` | 11px / 400 | `#ffffff` | 4.90:1 | Pass |
| Additional helper | `.step3-benefits-additional-help` | 11px / 400 | `#ffffff` | 4.90:1 | Pass |
| Character counter | `.step3-counter` | 11px / 400 | `#ffffff` | 4.90:1 | Pass |
| Preview muted note | `.step3-preview-note` | 11px / 400 | `#f7f9fc` | 4.64:1 | Pass |

Reference calculations for the requested backgrounds: `#64718d` is 4.90:1
against `#ffffff`, 4.64:1 against `#f7f9fc`, and 4.48:1 against `#f3f5f8`.
The last context is below the 4.5:1 normal-text threshold by 0.02 and is a
borderline failure if that tinted surface is introduced beneath these text
contexts. No current Benefits element was found on `#f3f5f8`; the current
Benefits panel background is white. Large-text criteria do not apply at these
sizes.

## Evidence

- `tmp/evidence/jc053-step003-diag009-disclosures-collapsed-1440.png` — all
  four collapsed disclosures.
- `tmp/evidence/jc053-step003-diag009-benefits-expanded-1440.png` — expanded
  Benefits header and rows.
- `tmp/evidence/jc053-step003-diag009-benefits-expanded-500.png` — narrow
  expanded Benefits content.

Screenshots were captured after exact viewport sizing and scrolling the target
region into view. The computed DOM/pseudo-style dump above is the authoritative
evidence for marker ownership.

## Smallest coherent implementation plan (not applied)

1. Remove the two higher-specificity Benefits `::before` ownership rules from
   the CSS source, rather than appending another suppression override.
2. Retain only the ordinary native `details` marker owner and plain shared
   summary structure; keep the guidance span only as content, not as a marker.
3. Re-run tight collapsed/expanded screenshots and computed marker checks.
4. Keep muted text at the current token for current white and `#f7f9fc`
   surfaces; scope a darker adjustment only if a future Benefits surface
   actually uses `#f3f5f8`.

Do not touch the taxonomy, selected-state model, preview renderer, rich-paste
pipeline, shell, responsive breakpoints, or unrelated optional disclosures.

## Deferred UX review

After controller and contrast defects are resolved, schedule:

**JC053-STEP003-UX010 — Evaluate Benefits Selector Comprehension, Scanability,
and State Clarity**

The review should preserve the compact text-first concept while evaluating how
to make interaction understandable, options easier to scan, and selected vs.
unselected states immediately legible. It must not redesign or prototype those
improvements as part of this diagnostic.

## Next implementation acceptance contract

- Remove the actual higher-specificity duplicate marker owner; retain one
  native controller only.
- Prove right collapsed/down expanded state with computed styles and tight
  screenshots.
- Keep guidance hidden collapsed and inline italic secondary when expanded.
- Confirm all normal-sized Benefits text meets 4.5:1 AA against its actual
  background; review any future `#f3f5f8` surface separately.
- Preserve all Benefits selection, preview, accessibility, persistence, and
  responsive behavior.
