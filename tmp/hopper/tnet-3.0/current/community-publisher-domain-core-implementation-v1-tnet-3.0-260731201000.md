# Community Publisher Domain Core Implementation v1

`tools/community3/community_publisher_domain.py` is a pure process-local domain
core. It creates safely copied immutable-style drafts/posts, validation and
moderation results, lifecycle transitions, publication results, and canonical
post-publication events. It has no WordPress dependency, persistence, network,
filesystem publication, queue, mail, notification, or legacy CGI access.

The core creates topics and replies, inherits a reply's parent thread,
rejects cross-community/missing/restricted/locked parents, normalizes title and
body, enforces explicit limits, and preserves compatibility metadata without
making it canonical. Default publication is post-first; pending requires an
explicit mode. Synthetic moderation inputs are deterministic and do not port
legacy spam code.

The process-local submission registry proves idempotent repeat behavior and
explicit conflict behavior. Successful publication constructs a canonical
event after domain acceptance; injected event-construction failure leaves the
accepted post intact and emits no event. Persistence remains the next boundary.
