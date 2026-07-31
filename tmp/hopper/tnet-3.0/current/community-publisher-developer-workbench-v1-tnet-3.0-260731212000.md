# Community Publisher Developer Workbench v1

The local workbench is owned by `wordpress/wp-content/plugins/tnet-community/`
and appears only as `Tools → Community Publisher Workbench` when the DDEV
container marker is present. It requires `manage_options`, WordPress admin
authentication, and a nonce. It is not public navigation, REST, AJAX, or a
production route.

The simple form accepts synthetic Community ID, author ID, submission key,
title, body, visibility, and publication mode. It delegates to the authoritative
PHP publisher domain/application service, the approved repository, and the local
prototype tables. The result
displays accepted/rejected state, IDs, lifecycle/moderation state, timestamps,
audit count, and pending event details with escaping.

Schema install/remove controls are nonce/capability protected and name only
the three prototype tables. Workbench-created rows carry a namespace marker;
no destructive record-cleanup UI was added. Use the documented local schema
uninstall command for full cleanup. No replies, uploads, rich text, group/Core
Terms/Portable View picker, legacy CGI, notification, or public route exists.
