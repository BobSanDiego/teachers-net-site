# JC053 Step 3 Benefits Render Audit

**Ticket:** JC053-STEP003-AUD006  
**Baseline:** `8cb5381` / `JOB-CENTER-JC053-wizard-workbench`  
**Runtime:** `http://127.0.0.1:8768/#step-03-job-description`  
**Scope:** diagnostic only; no runtime JS or CSS changed.

## Live rendered DOM

At the canonical runtime, the ordinary disclosure is:

```html
<details>
  <summary>Responsibilities</summary>
  <div id="step3-optional-0" ...></div>
</details>
```

Benefits is structurally different:

```html
<details class="step3-benefits">
  <summary>
    <span class="step3-benefits-title">Benefits</span>
    <span class="step3-benefits-help">Click items to add or remove benefits from your job listing.</span>
  </summary>
  ...
</details>
```

Both are collapsed by default, expose the native `details`/`summary` semantics,
and have no explicit ARIA role. Benefits has two child spans inside the summary;
Responsibilities has one text node. That difference changes the summary line
box and is material to alignment.

## Computed-style comparison

Measured at the browser-supported 500px viewport after direct load:

| Property | Responsibilities summary | Benefits summary |
|---|---:|---:|
| display | `list-item` | `list-item` |
| list-style | `inside none disclosure-closed` | `outside none none` |
| list-style-type | `disclosure-closed` | `none` |
| list-style-position | `inside` | `outside` |
| padding | `12px 0` | `12px 0` |
| margin | `0` | `0` |
| font | 700 / 12px / 17.4px Manrope | 700 / 12px / 17.4px Manrope |
| position | `static` | `static` |
| marker display | `list-item` | `list-item` |
| pseudo `::before` | none | empty generated content |
| pseudo `::after` | none | none |
| summary height | 41.39px | 58.78px |

Responsibilities’ summary rectangle was `x=84, y=884.73, w=317,
h=41.39`. Benefits’ was `x=84, y=1052.91, w=317, h=58.78`. Benefits’ title
text began at `x=101`, while the guidance occupied `x=84..394.81` and wrapped to
two lines. The Benefits marker/title offset cannot be compared as a native
marker offset because `list-style-type:none` removes the native disclosure
glyph; the visible marker was historically supplied by pseudo-elements.

At 1440px, the same mismatch persists: Benefits’ summary has nested inline
content and its computed list style remains `none`, while ordinary summaries
retain the browser disclosure style.

## Cascade trace

The active/historical Benefits owners are all in the minified Step 3 CSS block
(`mockup.css` source blocks reported at lines 670–673):

| Selector/rule | Effect | Result |
|---|---|---|
| `.step3-optional-sections summary` | shared `padding`, font, color | applies to both |
| `.step3-benefits>summary` | `list-style:none`, Benefits-specific summary owner | wins for Benefits and removes native glyph |
| `.step3-benefits>summary::-webkit-details-marker` | `display:none` | suppresses native WebKit marker |
| `.step3-benefits>summary::after` | rotated CSS chevron | historical Benefits-only marker |
| `.step3-benefits[open]>summary::after` | reversed rotated chevron | historical open-state marker |
| `.step3-optional-sections details.step3-benefits>summary::before` | custom CSS marker | later historical replacement |
| `.step3-optional-sections details.step3-benefits[open]>summary::before` | reversed custom marker | later historical replacement |
| final `.step3-benefits>summary::before/::after` | suppresses pseudo-elements | leaves `list-style:none` without a glyph |
| final `.step3-benefits>summary::-webkit-details-marker` | restores marker display only | cannot restore `list-style-type`/position |

The final state therefore combines `display:list-item` with
`list-style-type:none`, while ordinary summaries retain the native
`disclosure-closed` style. Restoring marker display alone did not restore the
native marker because the Benefits-only `list-style:none` owner remained.

## Root cause

The mismatch is caused by a combination of historical CSS cascade and different
summary DOM, not stale assets or render-time mutation. UX004 and UX005 did not
achieve identity because they neutralized pseudo-element painting and restored
WebKit marker display, but did not remove the earlier Benefits-specific
`list-style:none` declaration. The nested title/guidance spans independently
make the Benefits summary taller and alter marker/title baseline geometry.

## Confirmed secondary defects

- The `0/300 characters` counter is unconditional markup inside the Benefits
  disclosure. It has `hidden=false` and computed `display:block` while the
  textarea has `hidden=true`; the counter is not governed by the Additional
  benefits checkbox.
- Guidance is currently visible while collapsed because it is nested inside
  the summary. The accepted future state is hidden while collapsed, visible
  only when expanded, italic, and secondary.
- Selected category options currently use `.step3-benefit-option.is-selected`
  with `font-weight:700`; they are not underlined. The accepted future state is
  normal weight plus underline. The selected-summary removal controls are a
  separate surface and should retain their current behavior.
- Prior captures were full viewport captures after page-level scroll, not
  deterministic target-region captures. At narrow widths, the target was also
  below the fold. The reliable capture method is to set the exact viewport,
  scroll the disclosure stack or Benefits element into view with
  `scrollIntoView({block:'start'})`, then capture the viewport; record the
  computed dump separately.

## Diagnostic evidence

- `tmp/evidence/jc053-step003-aud006-disclosures-collapsed-1440.png` — tight
  wide disclosure-stack view.
- `tmp/evidence/jc053-step003-aud006-benefits-expanded-1440.png` — wide
  expanded Benefits view.
- `tmp/evidence/jc053-step003-aud006-benefits-expanded-500.png` — narrow
  expanded Benefits view.

All three captures are valid 1:1 PNGs. Live DOM inspection, computed-style
inspection, keyboard-capable native disclosure semantics, and no-overflow
checks were performed. No console errors or warnings were observed; Chrome
reported only pre-existing unrelated form-label accessibility issues.

## Smallest coherent implementation plan (not applied)

1. Make Benefits use the exact ordinary disclosure summary owner and native
   marker/list-style rather than retaining any Benefits-specific marker rules.
2. Move guidance out of `summary` into the revealed panel immediately after
   the title; hide it while collapsed and style it italic secondary text.
3. Render the selected summary and category list below that guidance without
   changing their state model; apply normal-weight underline only to selected
   category options.
4. Gate the Additional benefits counter with the same reveal state as its
   textarea so it is absent while the checkbox is unchecked.

Selectors/markup to change: the Benefits summary markup, the historical
`.step3-benefits>summary` marker/list-style rules, the Benefits guidance
placement, `.step3-benefit-option.is-selected`, and the counter visibility
owner. Do not touch the Benefits taxonomy, `step3State.selectedBenefits`,
preview construction, rich-paste sanitizer, shell, or other optional-section
renderers.

## Next-ticket acceptance contract

- Benefits marker, title start, and title baseline match all three ordinary
  disclosures.
- No Benefits-only pseudo-marker remains unless all ordinary disclosures use
  that same shared owner.
- Guidance is hidden while collapsed, appears only when expanded, and is
  italic secondary text.
- Selected category options are underlined and normal weight.
- `Benefits offered:` removal controls retain whole-item behavior and
  accessible names.
- The Additional benefits counter is hidden whenever its checkbox is unchecked
  and the textarea is hidden, and appears only when the field is revealed.
- Selection, preview, accessibility, persistence, rich paste, counters, shell,
  and responsive behavior remain intact.
