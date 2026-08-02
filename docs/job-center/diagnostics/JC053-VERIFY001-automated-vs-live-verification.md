# JC053-VERIFY001 — Reconcile Automated Verification with Live User Behavior

Status: complete — verification-process correction; no implementation changes.

Canonical review URL: http://127.0.0.1:8768/?#step-03-job-description

## Finding

The repeated false positives came from treating DOM-level automation as equivalent to user interaction. It is not equivalent for this editor. The prior checks commonly did one or more of the following:

- injected HTML directly into `innerHTML`, bypassing paste and input events;
- dispatched a synthetic `paste` event with a hand-built clipboard object rather than the browser/OS clipboard payload;
- created a `Range` programmatically rather than selecting text with the mouse or keyboard;
- invoked the toolbar through DOM evaluation or a tool click after a programmatic range was established;
- asserted HTML equality and computed styles without capturing the rendered visual result;
- tested comparable Indeed/Word/Docs-shaped fixtures rather than the actual source payload.

These methods can prove that a particular synthetic path works. They cannot prove the Engineering Director's live path works. The canonical runtime therefore remained capable of passing automation while failing live acceptance.

## Verification matrix

| Operation | Automated result | Live/manual result | Identical? | Explanation |
|---|---|---|---|---|
| HTML rich paste | Pass for hand-built HTML fixtures | Not proven for raw Indeed/Office clipboard in this run | No | Synthetic clipboard data does not establish source payload, browser focus, or native clipboard event sequence. |
| Nested div paste | Pass; semantic paragraphs survive | Not proven with raw source clipboard | No | DOM normalization is covered; source-specific clipboard topology remains unverified. |
| Plain text paste | Pass for dispatched text payload | Not proven through keyboard/OS clipboard | No | Synthetic event bypasses native clipboard and focus transitions. |
| Blank-line rendering | Pass/fail depends on injected empty blocks and computed styles | Requires screenshot at canonical runtime | No | `p:empty` has intentional minimum height; only source evidence distinguishes authored from unjustified blank blocks. |
| Clear Formatting | Pass after programmatic range plus toolbar click | Ordinary mouse-selection acceptance not established | No | Programmatic range does not prove selection survives real mousedown/focus transitions. |
| Lists and headings | DOM structure can pass | Marker/indent placement requires rendered visual inspection | No | HTML assertions cannot detect the user-visible marker position or indentation. |
| Editor/preview equivalence | Pass structurally in tested fixtures | Visual equivalence not established | No | Preview HTML equality is not a substitute for screenshots at the target viewport. |

## Event and ownership evidence

The current editor uses one paste listener and one clear-formatting path:

- paste reads `text/html` first, then `text/plain`, normalizes, and calls `document.execCommand("insertHTML")`;
- document-level `selectionchange` stores a cloned range for the active editor;
- toolbar `mousedown` prevents focus transfer, and Clear formatting normalizes the saved fragment before restoring focus and selection;
- input/change scheduling updates counters and Listing Preview.

Automation that sets `innerHTML` or dispatches a synthetic event does not reproduce the browser's native event ordering, clipboard data-transfer object, selection collapse behavior, or focus changes. That is the evidenced mismatch boundary. No new implementation defect was conclusively proven by the available live evidence, so no formatting patch is authorized by this ticket.

## Formatting classification

| Reported defect | Classification | Decision |
|---|---|---|
| Paragraph spacing | Verification artifact unless raw source proves an unjustified block; CSS intentionally gives explicit blank paragraphs visible height | Do not change CSS without raw clipboard plus before/after screenshots. |
| List indentation | Verification artifact for the repeated report; DOM checks cannot establish rendered marker geometry | Requires screenshot-based acceptance at canonical URL. |
| Bullet positioning | Verification artifact pending visual evidence | Requires human visual check. |
| Numbered-list positioning | Verification artifact pending visual evidence | Requires human visual check. |
| Clear Formatting | Verification artifact for repeated false-positive reports; current path has a bounded implementation, but mouse-selection proof is still required | Mandatory live visual/manual acceptance. |

## Permanent verification standard

Adopt option B: editor, clipboard, selection, formatting, and rendered-geometry operations require mandatory human visual acceptance. Automation remains required as a preflight characterization layer, covering DOM structure, event instrumentation, counters, preview synchronization, sanitization, and regression fixtures. It may not close a ticket by itself for these operations.

Every future Step 3 formatting ticket must include:

1. the exact canonical URL and runtime banner/commit;
2. raw clipboard provenance or an explicit evidence limitation;
3. before/after screenshots of editor and preview;
4. an ordinary mouse/keyboard selection test for formatting commands;
5. an automated preflight report separated from human acceptance;
6. a matrix stating which claims are automated, visual, or both.

This ticket establishes the process gate. It does not authorize another Step 3 implementation attempt.
