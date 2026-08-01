# Community 3.0 URL Migration Test Plan v1

Status: planning artifact; no migration or production test writes authorized.

## Route matrix

Test the canonical community landing and thread route, duplicate titles,
renamed title, moved thread, deterministic slug collision, non-Latin/empty
title, reply fragment, page 1 and later pagination, lowercase/trailing-slash
normalization, query-string alias, malformed percent encoding, encoded slash,
and route precedence against existing WordPress, Jobs, Core Terms, REST, feed,
login, and asset routes.

## Compatibility matrix

Use redacted, approved fixtures for CGI topic URLs, static topic HTML, static
reply URLs, board indexes, old query strings, moved/renamed boards, duplicate
aliases, unresolved archives, deleted/spam records, and inbound links. Assert
the disposition in the redirect contract: 301, temporary redirect,
compatibility/archive render, restricted/noindex, 404, or 410. Assert that a
redirect has one hop, a same-site canonical target, no open redirect, and no
private-content disclosure.

## SEO and operations

Assert one canonical tag, sitemap inclusion only for public canonical URLs,
noindex/restricted handling for private and archive cases, cache separation,
404/410 metrics, loop detection, audit logging, and a rollback switch. Verify
that title edits preserve the original slug and that a move creates an audited
alias before redirect activation.

## Evidence and gates

Each case records input, expected disposition, observed status/location/body
marker, visibility state, mapping evidence, and test timestamp. Unresolved
legacy mappings remain a blocker for redirect enablement, not an invitation to
guess. C3-URL002 is the next bounded local implementation ticket. Do not issue
UI002 until this contract is accepted and the local route prototype passes the
matrix.
