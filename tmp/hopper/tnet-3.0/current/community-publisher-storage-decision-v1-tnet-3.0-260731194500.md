# Community Publisher Storage Decision v1

## Decision

Choose **B: dedicated WordPress custom tables**, accessed through a narrow
publisher repository/service. WordPress users remain the author authority;
custom tables own Community post/thread lifecycle and queryable audit facts.

CPT/postmeta is attractive for native admin and permalinks, but thread feeds,
moderation queues, idempotency, visibility filtering, compatibility references,
and high-volume replies would become meta-query and indexing debt. A hybrid
would add dual authority before the contract is proven. Dedicated tables fit
existing `$wpdb`/`dbDelta` patterns in Core Terms while supporting explicit
indexes, transactions, revisions, and archive imports. This decision creates
no tables.
