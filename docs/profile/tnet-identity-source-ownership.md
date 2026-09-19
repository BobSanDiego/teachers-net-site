# Teachers.Net Identity Source Ownership

Status: canonical source-control boundary established 2026-09-19.

`teachers-net-site` is the canonical Git owner of
`wordpress/wp-content/plugins/tnet-identity/`.

## Why it was previously ignored

The root `.gitignore` rule `wordpress/` originated in commit
`f6f4bba1911158df51d482036e011dd143ee8041` (`Ignore WordPress runtime`). It
ignored the entire runtime tree before Identity was added. Git history contains
no earlier `tnet-identity` path or alternative repository owner. The rule was
not supplied by `.git/info/exclude` or a global excludes file.

This repository already owns first-party runtime plugin sources such as
`tnet-community`, `tnet-notifications`, and `tnet-profile`. The sibling
`tnet-shared-shell` platform source is now separately tracked through its own
complete narrow exception (see
`docs/profile/tnet-shared-shell-source-ownership.md`). Identity therefore
belongs here rather than in a new or duplicated repository.

## Tracked boundary

The complete plugin source required to reproduce the active Identity plugin is:

- `tnet-identity.php` — plugin declaration, routes, and database-schema owner;
- `includes/class-tnet-identity-service.php`;
- `includes/class-tnet-identity-username-policy.php`;
- `includes/class-tnet-identity-cli.php` — DDEV-guarded QA lifecycle command;
- `public/class-tnet-identity-public.php`;
- `public/css/tnet-identity-public.css`;
- `public/js/tnet-identity-public.js`.

The root ignore keeps the broader WordPress runtime excluded and re-includes
only this explicit plugin boundary. There are no vendor, build, cache, upload,
environment, certificate, key, SQL dump, log, or generated directories in the
plugin as established by the recovery audit.

## Security and runtime contract

The source contains code that receives credentials and generates one-time
verification/continuation tokens at runtime. It contains no reusable tokens,
credentials, private keys, local paths, DDEV configuration, or mutable user
data. Verification and continuation values are persisted as database hashes;
the DDEV-only CLI reset path requires explicit DDEV runtime markers.

The plugin depends on WordPress core APIs and the existing Profile resolver
integration. It does not bundle a separate vendor tree or external build
output. Runtime uploads and user data remain outside this source boundary.

## Maintenance rule

Track complete changes beneath this plugin as one coherent source owner. Do not
force-add selected snapshots while omitting required plugin files, and do not
expand the exception to unrelated WordPress plugins or mutable runtime data.
