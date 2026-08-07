# DV-DIAG003 Diagnostic Report

Status: COMPLETE — CANONICAL STATE IDENTIFIED; ENGINEER SESSION DIVERGENCE REMAINS EXTERNAL
Date: 2026-08-07

## Canonical runtime identity

- Hostname: `teachers-net.ddev.site`
- DDEV project: `teachers-net`
- Repository: `/home/bobreap/projects/teachers-net-site`
- WordPress docroot: `/var/www/html/wordpress`
- Active plugin source: `/var/www/html/wordpress/wp-content/plugins/profilaxes`
- Host plugin source: `/home/bobreap/projects/teachers-net-site/wordpress/wp-content/plugins/profilaxes`
- Database host: `db`
- Database name: `db`
- Site URL: `https://teachers-net.ddev.site`
- WordPress site name: `Teachers.Net Local`
- Authenticated user: `jobman` (user ID `316`)
- URL query: `page=cfm-views&version_id=17&_codex=260807180000`

DDEV reported the expected `teachers-net` project, web service, database
service, and `wordpress` docroot. No alternate DDEV project or plugin copy was
found in the active runtime path.

## Authoritative persistence state

Direct WordPress bootstrap and WP-CLI query against `db` both returned exactly
one row for version 17:

| Entry ID | Version | Framework | Term UUID | Inclusion |
| ---: | ---: | --- | --- | --- |
| 17 | 17 | teachers-net | 2c09a868-532a-4e67-a99d-4a8aa44c084c | include |

Version 17 belongs to View 15, is draft status, and has version UUID
`81cf0af9-944d-44b1-88fb-c66c7b382ebb`. The canonical term is `Grade Level`.
The entry table has no stored `parent_uuid` column; ancestry is resolved from
Core Terms UUID relationships, as expected by the repository contract.

The initial query that appeared to return zero rows selected nonexistent
`parent_uuid`; MariaDB rejected that query. A corrected direct query confirmed
the one persisted row above.

## Codex browser state

At the canonical URL, after cache-bypassed navigation, the authenticated Codex
browser rendered exactly one Current View row:

- `Grade Level`, entry ID `17`, version 17.

The page body and server-rendered Current View text both show `1 entry — Grade
Level`. No service worker controller is present. The page includes the expected
Views inline runtime selectors and scripts; no AJAX request or alternate asset
was observed as the source of Current View data. No console warnings or errors
were reported.

## Established divergence

The engineer’s reported five-row display cannot be produced by the current
canonical `teachers-net` web runtime/database combination because:

1. direct database state contains one row;
2. direct WordPress bootstrap sees the same one row;
3. a fresh canonical browser request renders the same one row.

Therefore the engineer’s five rows are coming from a different browser/session
state or a different runtime/data path, not from the current authoritative
database response. The available Codex browser session cannot inspect the
engineer’s HttpOnly authentication cookie, browser cache, or separate browser
profile. No proof of an alternate DDEV project, database, service worker, or
plugin source was found on the Codex path.

## Required parity action

The engineer should capture the five-row state’s browser/runtime identity and
compare it with the values above, or navigate a fresh browser context to:

`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`

Expected parity state is one Current View row, `Grade Level`, entry ID `17`.
If the engineer still sees five rows after a fresh navigation, provide the
engineer session’s hostname, database identity, WordPress user/site context,
and response source so the alternate path can be identified.

DV-DIAG002 must not resume until those identities are proven equal.

## Evidence limitation

The browser screenshot transport timed out during this diagnostic, so no new
image file could be persisted. The direct database output, runtime identity,
browser DOM state, URL, cookies-name inventory, and console findings are
recorded in this report and the cycle manifest.

No application code, QA data, database, DDEV configuration, or browser state
was modified.
