# Community Publisher Thread Browser v1

The sandbox renders the selected persisted thread with type, content summary,
post ID, parent, lifecycle state, and creation time. The reply composer creates
synthetic replies through the PHP application/domain service; a reply inherits
the parent thread and is rejected when the parent is absent, cross-community,
restricted, or locked. Nested replies use the same boundary. This is developer
tooling, not public reply UI.
