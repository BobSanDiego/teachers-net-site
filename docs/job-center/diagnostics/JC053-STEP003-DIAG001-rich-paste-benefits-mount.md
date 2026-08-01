# JC053-STEP003-DIAG001 — Rich Paste and Benefits Mount Diagnosis

Date: 2026-08-01
Project: Job Center
Branch under test: `JOB-CENTER-JC053-wizard-workbench`
HEAD: `a52f50e` (`JC053 STEP003 UX002 add compact benefits selector`)

## Conclusion

The two reported defects are not caused by one shared renderer defect. They
were reproduced on the stale workbench served at `http://127.0.0.1:8768/`.
That runtime loaded an older JavaScript asset and did not contain either
PATCH001 or UX002. The current Job Center branch rendered correctly when served
from an isolated HTTP root at `http://127.0.0.1:8770/`.

No implementation correction was applied by this diagnostic ticket.

## Runtime identity comparison

| Runtime | Script | Bytes | PATCH001 marker | UX002 marker | Benefits DOM |
|---|---|---:|---|---|---|
| stale `:8768` | `mockup.js?v=jc053-20260730-navbar-01` | 72,064 | absent | absent | absent |
| current `:8770` | `mockup.js?v=jc053-20260730-navbar-01` | current branch build | present | present | present |

The identical query string masked different server roots. Cache was not the
primary cause: fetching the stale asset with `cache: no-store` still returned
the old 72,064-byte file. The server/build root was stale or pointed at the
mixed worktree, while the committed Job Center worktree contained the newer
files.

## Rich-paste trace

Fixture clipboard payload:

- HTML: `<h3>JOB SUMMARY</h3><p><strong>Bold</strong> paragraph one</p><p>Paragraph two</p><ul><li>Item</li></ul><p><a href="https://example.com">Link</a></p>`
- Plain text: `JOB SUMMARY\nBold paragraph one\n\nParagraph two\nItem\nLink`

### Stale `:8768` runtime

1. The dispatched clipboard exposed both HTML and plain text.
2. The active handler read only `text/plain` and called `document.execCommand("insertText")`.
3. Editor DOM became `JOB SUMMARY<div>Bold paragraph one</div><div><br></div><div>Paragraph two</div><div>Item</div><div>Link</div>`.
4. The HTML structure, heading, bold, list, and link were already lost at insertion.
5. Preview then sanitized `innerHTML`, but received flattened content and rendered `JOB SUMMARYBold paragraph one<br>Paragraph twoItemLink`.

### Current `:8770` runtime

1. The same HTML and plain payload were exposed.
2. The handler preferred `text/html`, applied the existing allowlist, and called `insertHTML`.
3. Editor DOM preserved `<h3>`, `<p>`, `<strong>`, `<ul>`, `<li>`, and HTTPS `<a>`.
4. Preview received the sanitized `innerHTML` and rendered the same structure.
5. Script markup was absent from both editor and preview.

`step3Text()` is used for counters/readiness and not for preview markup. It is
not the loss point in the current build.

## Benefits mount trace

### Stale `:8768` runtime

- `.step3-benefits`: absent.
- `#step3-benefits-selected`: absent.
- `#step3-benefits-categories`: absent.
- Options: `0`.
- The visible `Benefits` accordion was the old generic optional editor:
  `<details><summary>Benefits</summary><div id="step3-optional-2" class="step3-editor step3-optional-editor" contenteditable="true" ...></div></details>`.
- There was no `renderStep3Benefits()` in the loaded asset.

### Current `:8770` runtime

- `.step3-benefits`: present and displayed as `block`.
- Selected summary and categories containers: present.
- Four category rows and 25 options: present.
- Selection produced `Selected (2): Medical Insurance × Paid Time Off × Clear all`.
- Selected buttons had `aria-pressed="true"` and preview rendered `Benefits`.
- No duplicate generic Benefits editor remained.
- No horizontal overflow.

The earlier apparent mount failure is therefore a stale build/root mismatch,
not an early-return ordering failure in the current UX002 renderer.

## Console and evidence

- Stale runtime: no console errors or warnings observed.
- Current runtime: no runtime errors observed.
- [Stale editor/preview](C:/Main/Active/Projects/Teachers.Net/tmp/evidence/jc053-step003-diag001-stale-editor-preview.png)
- [Stale Benefits accordion](C:/Main/Active/Projects/Teachers.Net/tmp/evidence/jc053-step003-diag001-stale-benefits.png)
- [Current editor/preview/Benefits](C:/Main/Active/Projects/Teachers.Net/tmp/evidence/jc053-step003-diag001-latest-editor-preview-benefits.png)

## Correction plan

The smallest correction is operational: serve the committed Job Center
workbench root/build, invalidate or version the static asset URL, and verify
the loaded script contains both PATCH001 and UX002 markers before review. No
Step 3 renderer patch is indicated by this trace.

Recommended next ticket: **JC053-STEP003-OPS001 — Align workbench server root
and cache-busted asset identity with Job Center branch**.

## Scope confirmation

- No production route, UI implementation, shell, responsive behavior, or
  unrelated files changed.
- This diagnostic report is the only repository change for DIAG001.
