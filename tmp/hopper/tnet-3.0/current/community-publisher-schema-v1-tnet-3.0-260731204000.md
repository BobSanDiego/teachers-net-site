# Community Publisher Schema v1

Prototype tables are `wp_community_posts`, `wp_community_post_audit`, and
`wp_community_publication_events` (the prefix is supplied by WordPress).
Posts index community/state/time, thread/time/post, parent, and author/state;
the community/author/submission tuple is unique. Audit is append-only.
Events have unique event and dedupe keys plus pending status and dispatch time.

The schema version is `1` in the `tnet_community_schema_version` option. The
prototype defers compatibility and revision tables. It does not alter legacy
tables or create production state.
