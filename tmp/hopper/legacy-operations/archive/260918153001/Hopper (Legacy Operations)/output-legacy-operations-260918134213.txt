# TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001 — Legacy Mobile Ad Delivery Convergence

**Terminal state:** BLOCKED — owner recovery completed; no production or Google-account change was made.

## Stop-boundary result

The deployed Lessons/Lessonplans renderer is outside the ticket's registered source/deployment authority. The minimum corrective code is on Sandy in untracked deployment paths, so editing it would be an approximation and violate the explicit stop boundary.

## Proven deployed owner chain

1. The active production TLS vhost is `/etc/apache2/sites-enabled/000-teachers.net-ssl.conf`, with `DocumentRoot /var/www/htdocs`.
2. Its active `<Directory "/var/www/htdocs/lesson*">` rule enables SSI and routes `.html` requests to `/cgi-bin/newwrapper.cgi`.
3. `/var/www/htdocs/lessonplans` is a symbolic link to `lessons`; therefore `/lessons/` and `/lessonplans/grades/3-5/` share the same deployed content family.
4. Direct read-only production responses for both representative URLs emitted the distinctive `newwrapper.pm` signature: `topleader`, `skyscraper`, `bigbox1`, `multiunit`, `footleader`, the corresponding five GPT definitions, and AdSense slot `1692171583`.
5. `/var/www/cgi-bin/newwrapper.pm` contains that exact slot and renderer logic; its observed metadata was 228,892 bytes, owner `www-data:www-data`, modified `2026-07-08 06:08:32 -0700`.
6. `/var/www/cgi-bin/lessonz.pm` does not contain the exact `1692171583` slot signature. It is not the active renderer for this behavior.

The paths `/var/www/htdocs`, `/var/www/htdocs/lessons`, and `/var/www/cgi-bin` each reported `not-a-git-worktree`. The registered legacy source repository `/home/bobreap/projects/teachers-net-legacy` is clean and contains only the checked-in WordPress theme/operations material, not this renderer. The ticket's governance repository `/home/bobreap/projects/teachers-net-site` likewise has no source-controlled copy of the active renderer.

## Carried-forward acceptance evidence

The prior audit's mobile failures remain PROVEN and were not reopened:

- `/lessons/` and `/lessonplans/grades/3-5/` raise `adsbygoogle` `No slot size for availableWidth` at the tested compact width.
- Fixed 728×90/468 px inventory overflows the compact viewport.
- Some GPT requests target zero-geometry containers.
- Desktop inventory initializes on the same legacy route family.

The deployed source correlation explains ownership; it does not invalidate the failure evidence. No patch was attempted, so no post-change browser acceptance is available or claimed.

## Boundary honored

- No Sandy filesystem, Apache, CGI, application, ad inventory, consent, AdSense, GAM, or GA4 configuration changed.
- No direct edit was made to `/var/www/htdocs/lessons`, `/var/www/htdocs/lessonplans`, `/var/www/cgi-bin/newwrapper.cgi`, or `/var/www/cgi-bin/newwrapper.pm`.
- No workaround was added to the WordPress theme or the control-plane repository.

## Required authority to resume

Before a repair can begin, the Engineering Director must establish one source-controlled deployment owner for the live renderer—either by registering an existing authoritative repository/deployment flow or by explicitly authorizing controlled recovery of the deployed renderer into one. That decision must state the source of truth and the safe production deployment path.

Once that authority exists, resume this objective with the carried-forward failure fixtures and make the minimum responsive change in the proven owner. Do not re-audit unrelated routes or change account configuration.
