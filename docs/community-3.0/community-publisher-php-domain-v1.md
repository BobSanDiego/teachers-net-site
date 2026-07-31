# Community Publisher PHP Domain v1

PHP is now the sole intended runtime implementation of the Community publisher
inside `wordpress/wp-content/plugins/tnet-community/`. The domain service owns
topic/reply validation, same-community threading, restricted/locked parent
rules, post-first/pending moderation, deterministic moderation inputs,
idempotency, lifecycle classification, and canonical event construction.

The application service flows draft → PHP domain → publication result → PHP
repository transaction. The workbench no longer constructs canonical post or
event arrays. Python remains characterization/regression support and is never
invoked by WordPress.
