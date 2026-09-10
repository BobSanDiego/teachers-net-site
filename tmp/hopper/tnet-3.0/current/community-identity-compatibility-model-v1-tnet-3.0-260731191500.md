# Community Identity Compatibility Model v1

The compatibility model has three layers:

1. Canonical `community_id` and lifecycle are the authority for new Community
   application behavior.
2. Explicit relationships connect the entity to chatboard streams, teacher
   groups, Core Terms, Portable Views, memberships, posts, and notifications.
3. Immutable compatibility references retain legacy `path_id`, `local_path`,
   `group_id`, URLs, archive locations, and reconciliation evidence.

A legacy post or membership resolves through the compatibility layer to
`community_id`; an unresolved or conflicting reference remains queryable for
archive/reconciliation purposes but cannot authorize a new write, membership
decision, or notification candidate. Historical URLs use a resolver/redirect
and immutable archive record. New URLs and records use canonical identity while
retaining legacy aliases where needed for inbound links and SEO.

Core Terms assignments and Portable Views are relationships to the Community,
not alternate identity stores. A Portable View may present a governed subset;
it may not redefine the Community or silently turn a legacy path into a group.

Rollback is achieved by disabling a staged resolver route and returning to
immutable legacy reads; it does not require renumbering or dual-write
reconciliation. Every mapping creation, conflict, status change, and import
decision has an append-oriented audit record.
