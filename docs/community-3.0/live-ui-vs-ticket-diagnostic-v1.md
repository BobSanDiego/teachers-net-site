# UX-AUDIT001 — Live UI vs Accepted Ticket Diagnostic

## Executive finding

The accepted UX work is present in the Community source in substantial part,
but the requested live review URL is served by a different DDEV project and a
mixed plugin tree. This explains why code-level completion and visible
experience can diverge. The audit found no basis for implementing a UI fix in
this ticket.

## Evidence captured

- Active review host: `https://teachers-net.ddev.site`.
- Active DDEV project: `/home/bobreap/projects/teachers-net-site`, project
  name `teachers-net`, web service running.
- Isolated Community worktree: `/home/bobreap/projects/teachers-net-community3`,
  branch `COMMUNITY3-ui-working`; its DDEV web service was stopped.
- Community branch HEAD: `2290f976328f5954f8fefa3f45e492ccc7a61909`
  (`feat: complete reply media parity`).
- Runtime entry-point hash:
  `ec1109dec0fbb4f0f109da0ca16eeae10d7aaeb7e25250ccf2e42844c8452e55`.
  This equals the Community worktree entry point.
- Runtime landing-controller hash:
  `36259aa2bac1a3f9fdadf53e6da4d1b4cee886c5c250e62bd415d091faedac84`.
- The runtime plugin tree differs from the Community worktree: composer view,
  composer contracts, topic composer, attachment, authoring, link-service, and
  mocked-fetch files exist only in the Community tree; several shared classes
  also differ.
- Feed HTTP response visibly contains current visual-language classes, feed
  cards, canonical thread links, expandable-feed DOM, and the injected
  navigation script.
- Thread HTTP response visibly contains the Back to Community link and the
  current story image. It has no replies, and anonymous access does not expose
  the reply composer.
- Topic Composer returns a login redirect for an unauthenticated request.
- `127.0.0.1:9222/json/version` timed out; no screenshots or computed-style
  browser capture were available.

## Root causes

### 1. Runtime boundary mismatch — confirmed

The URL named by the ticket resolves to the `teachers-net` project. The
isolated Community project is not the process serving that URL. A reviewer can
therefore see a mixed or stale state even when the Community branch contains
the accepted implementation.

### 2. Mixed plugin source — confirmed

The `teachers-net` checkout and the Community checkout contain materially
different `tnet-community` trees. The runtime entry point is current, but the
runtime’s supporting classes are not source-identical to the Community branch.
This prevents a trustworthy claim that a specific controller/view/service
combination is what the isolated branch would render.

### 3. Feed-card event boundary — confirmed in code

The card listener excludes `.feed-excerpt` as an interactive region. That is
consistent with protecting expandable text from navigation, but it also means
ordinary short excerpt text is outside card navigation. The smallest safe
correction is a dedicated interaction rule that distinguishes an expandable
excerpt from a non-expandable excerpt; it must be a separate correction ticket,
not an implementation change in this audit.

### 4. Browser verification unavailable — confirmed blocker

The canonical CDP endpoint was unavailable, and no authenticated browser state
was available through the HTTP checks. Responsive screenshots and event-path
verification remain pending rather than failed or passed.

## Ordered correction sequence

1. **UX-AUDIT001-CORR001 — Align Community review runtime**: make the named
   review URL resolve to one explicitly selected Community worktree and one
   complete plugin tree; record the runtime path and commit in the cursor.
2. **UX-AUDIT001-CORR002 — Reconcile plugin source boundary**: remove the
   mixed-tree ambiguity by connecting or deliberately copying the complete
   Community plugin source into the selected runtime, without production
   deployment.
3. **UX-AUDIT001-CORR003 — Capture authenticated responsive evidence**: with
   the aligned runtime, capture 1440/1024/768/390 DOM, computed styles, event
   paths, and screenshots for the ticket matrix.
4. **UX-AUDIT001-CORR004 — Correct feed excerpt/card interaction**: decide and
   implement the smallest rule that preserves text expansion while making
   non-expandable feed text behave consistently with card navigation.
5. **UX-AUDIT001-CORR005 — Re-audit UX005–UX009**: update the matrix only after
   the runtime and browser evidence are stable.

## Stop-boundary confirmation

No code, CSS, JavaScript, schema, route, fixture, cache, repository, or
publication changes were made. Only the three diagnostic documents created by
this ticket are authorized changes.

## Next correction ticket

`UX-AUDIT001-CORR001 — Align Community review runtime`.
