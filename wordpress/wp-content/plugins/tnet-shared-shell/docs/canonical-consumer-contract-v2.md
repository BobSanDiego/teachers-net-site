# Canonical Shared Shell consumer contract v2

`TNet_Shared_Shell::render_host()` renders the accepted Teachers.Net platform
presentation when a consumer supplies `contract => 'canonical'`. This is the
ordinary v2 contract; `accepted_shell_lab_parity` is not a supported consumer
option.

The Shared Shell owns the document frame, header/brand region, primary
navigation and disclosure primitives, notification and account presentation,
compact navigation, footer, responsive corrections, and focus/Escape/outside
click behavior.

Consumers provide only resolved data and callbacks:

- `brand_image` and `home_url` for brand identity;
- `urls` for truthful destinations;
- `identity` for already-resolved name, avatar, and account descriptor;
- `taxonomy` for consumer-supplied navigation taxonomies;
- `content` for the product region;
- `fixture`, `clean`, `presentation`, and access facts only when an isolated
  proof adapter needs them.

The contract must not receive Jobs classes, route services, membership logic,
notification persistence/read state, or Community product semantics. A
consumer may omit unavailable destinations rather than supplying placeholders.

The prior generic v1 `render_host()` branch remains only as a compatibility
surface for the suspended Community migration. It is not the presentation
baseline for new canonical consumers and must be retired only after the first
Community consumer is migrated and accepted.
