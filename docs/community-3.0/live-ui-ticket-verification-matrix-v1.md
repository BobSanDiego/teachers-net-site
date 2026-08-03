# UX-AUDIT001 — Live UI vs Accepted Ticket Verification Matrix

Diagnostic-only audit of UX005–UX009. “Visible” is reserved for behavior
verified from the browser-reachable response or browser evidence; source
inspection alone is not visual verification.

| Ticket | Promised outcome | Classification | Evidence / limitation |
|---|---|---|---|
| UX005 | Feed cards navigate to the canonical thread | Partially implemented | Entry-point script adds click navigation, and cards contain canonical title/Open conversation links. The script excludes `.feed-excerpt`, so clicking excerpt text does not navigate; no keyboard/card-level browser test was available. |
| UX005 | Feed reply/comment controls remain usable | Implemented in code; runtime-visible for links | Feed response contains the Open conversation link. Authenticated interactive controls were not browser-tested. |
| UX005 | Thread has a clear back control | Visibly verified | Thread response contains `← Back to Community` linking to `/community/`. |
| UX005 | No rogue literal `\\n` in rendered UI | Partially implemented | Runtime HTML contained CR-style text in authored fixture content and no separate literal `\\n` marker was confirmed by response search. The output buffer performs a global literal replacement, which is an unscoped correction risk. |
| UX006 | Feed text expands in place | Implemented in code; runtime DOM present | Runtime feed contains `data-expandable`, `tabindex="0"`, `role="button"`, `aria-expanded="false"`, collapsed and expanded spans, and an inline expansion listener. Browser click/keyboard behavior was not captured. |
| UX006 | Expansion does not hijack links/media | Implemented in code; not browser-verified | Listener excludes links, buttons, media, and previews. Feed-card navigation also excludes `.feed-excerpt`, producing the discrepancy above. |
| UX007 | L1/L2 replies are initially collapsed | Implemented in code; runtime not exposed in anonymous fixture | Output-buffer script groups adjacent `article.reply-level-2` elements and hides the group. Representative thread had no replies, so DOM/runtime behavior is unverified. |
| UX007 | Group toggle is accessible and preserves reply composer context | Implemented in code; not browser-verified | Toggle has button semantics and `aria-expanded`; expansion moves `#reply-composer-shell` into the group. Authenticated thread evidence was unavailable. |
| UX008 | Feed and thread navigation are coherent | Partially implemented | Canonical URLs and Back link are present. Full navigation flow was not browser-tested; the feed-card/excerpt exclusion is a concrete interaction inconsistency. |
| UX009 | Reply composer supports image paste/drop/choose parity | Implemented in code; blocked from visible verification | Normalized reply form contains media zone, file input, paste/drop handlers, preview, removal, and cleanup. Anonymous thread response exposes only login, and CDP at `127.0.0.1:9222` was unavailable. |
| UX009 | Reply upload failure parity and cleanup | Implemented in code; not runtime-tested | Shared upload contract and cleanup calls are present in source; no authenticated browser or upload fixture evidence was available. |

## Required responsive evidence

Screenshots and computed-style/event-listener evidence at 1440, 1024, 768, and
390 pixels were not captured. The canonical Chrome CDP endpoint timed out and
the Community worktree DDEV service was stopped. The report therefore does not
claim browser visual acceptance.

## Highest-confidence discrepancies

1. The reviewed host is the `teachers-net` DDEV project, not the isolated
   `teachers-net-community3` runtime.
2. The main-site plugin tree is a mixed source boundary: its entry point matches
   the Community worktree, but the complete plugin trees differ materially.
3. Feed-card navigation deliberately excludes `.feed-excerpt`, so expanded or
   ordinary feed text is not covered by the card-navigation behavior.
4. Authenticated topic/reply controls cannot be classified as visibly verified
   from anonymous HTTP evidence.
