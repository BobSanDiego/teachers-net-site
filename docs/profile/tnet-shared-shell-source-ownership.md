# Teachers.Net Shared Shell Source Ownership

Status: canonical source-control boundary established 2026-09-19 by
`PROFILE-ONBOARDING-PUBLIC-IDENTITY-SCREEN4-CLOSEOUT001`.

`teachers-net-site` is the canonical Git owner of the complete first-party
Shared Shell plugin at `wordpress/wp-content/plugins/tnet-shared-shell/`.
It is the platform presentation owner consumed by Identity and other product
adapters; no other repository or worktree contains a competing implementation.

## Complete tracked boundary

The root WordPress runtime remains ignored. The narrow exception includes this
entire plugin directory, not only the accepted stacking stylesheet:

- plugin bootstrap and canonical renderer/template;
- all public CSS and JavaScript assets enqueued by the renderer;
- supplied first-party wordmark/icon assets;
- consumer-contract and rollback-reference documentation.

This is the smallest coherent source boundary capable of reproducing the
accepted shell behavior, including the focused-identity rail/main stacking
correction in `public/css/tnet-shared-shell-community.css`.

## Security audit

The source inventory contains only PHP, CSS, JavaScript, SVG, and Markdown
source/reference files. It contains no environment files, credentials, private
keys, database dumps, uploads, cache, vendor tree, generated build output, or
runtime user data. The broad `wordpress/` exclusion remains in force for every
other runtime path.

## Maintenance rule

Track changes to this complete plugin as one platform-source owner. Do not
force-add an individual runtime stylesheet, and do not broaden the exception to
unrelated plugins or mutable WordPress data.
