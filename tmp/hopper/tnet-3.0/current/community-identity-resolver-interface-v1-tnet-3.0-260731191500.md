# Community Identity Resolver Interface v1

This is a proposed boundary, not an implementation. It is the only application
surface allowed to translate legacy identity.

```text
resolve_community_by_legacy_path(path_id=None, local_path=None)
resolve_community_by_legacy_group(group_id)
get_legacy_references(community_id)
get_group_context(community_id)
get_publisher_context(community_id)
```

Each resolver returns an explicit result containing `community_id`, status
(`resolved`, `missing`, `ambiguous`, `duplicate`, `inactive`, or `orphaned`),
source reference, evidence/audit reference, and any permitted context. It must
not return a guessed identity. `get_group_context` and
`get_publisher_context` consume canonical identity and return scoped context;
they do not expose translation logic to feature code.

New code may accept `community_id`, view IDs, canonical group context, and
validated post/thread IDs. It may not use legacy numeric IDs in joins,
authorization, notification eligibility, URLs, or persistence except inside
the compatibility repository and migration tools.

Smallest implementation ticket: build a read-only in-memory resolver contract
with resolved/missing/ambiguous/duplicate fixtures, then add a repository
adapter only after an approved mapping source and schema boundary exist.
