# TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001 — Controlled Recovery Gate

**Terminal state:** BLOCKED — credential separation is required before source recovery.

## Decision

The Director authorized controlled recovery of the active Lessons/Lessonplans renderer into `teachers-net-legacy`. That recovery cannot safely begin because the required active source `/var/www/cgi-bin/newwrapper.pm` embeds live database credentials in its DBI connection logic. The ticket expressly prohibits importing secrets and requires a stop when a required dependency cannot safely enter source control.

No source file was copied, redacted, or changed. No production deployment, Apache change, database action, Google-account action, or ad-inventory change occurred.

## Preserved non-sensitive production provenance

| Production path | SHA-256 | Mode / owner |
| --- | --- | --- |
| `/var/www/cgi-bin/newwrapper.cgi` | `2a8ac78a73d091a0f5865d3ff6e24e6d146644488a5d54a5234f823da1c290e8` | `775`, `www-data:www-data` |
| `/var/www/cgi-bin/newwrapper.pm` | `2c6392102f9e8d789230336f3cb55fc61e022841f653030a9908c600621977d7` | `775`, `www-data:www-data` |
| `/var/www/htdocs/lessons/index.html` | `ad6d37d02c21aca5e26185c66a8db074b7612f9456942e560315e17f8b0f0785` | `775`, `www-data:www-data` |
| `/var/www/htdocs/lessons/grades/3-5/index.html` | `61e5a4ef64567ba1a1242ad967aff9b4b27be9ffc7ed102f142a90f6049540c7` | `775`, `www-data:www-data` |

`/var/www/htdocs/lessonplans` resolves to `/var/www/htdocs/lessons`; `/www/cgi-bin` resolves to `/var/www/cgi-bin`. Both CGI files passed read-only `perl -c` syntax checks.

## Required configuration boundary

A new security/operations decision is required before recovery resumes:

1. Define a protected production-only secret source, excluded from Git and readable by the CGI runtime.
2. Define the source-safe renderer interface that consumes it without embedding credentials.
3. Approve a credential rotation and rollback plan; the current credential must not be copied into repository artifacts or reports.
4. Define a bounded deployment contract that installs the source-controlled renderer and preserves the protected local secret configuration.

This is material security and production scope beyond the recovery ticket's permitted mechanics. The carried-forward mobile-rendering defects remain PROVEN but cannot be repaired until this boundary is resolved.
