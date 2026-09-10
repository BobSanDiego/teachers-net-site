# Community Identity Resolver Implementation v1

This ticket implements the C3-CORE004 boundary as a process-local,
non-persistent `CommunityIdentityResolver` in
`tools/community3/community_identity_resolver.py`. It is a test component, not
a WordPress repository, ORM, migration job, publisher integration, or schema.

`Community` is an immutable value object containing opaque `community_id`,
lifecycle, visibility, legacy path/group references, scoped publisher/group
context, and evidence reference. Registration rejects duplicate canonical
identity. Source mappings are explicit, copied, and never silently overwritten.

Resolution supports path ID, local path, and group ID. Results always contain
status, source reference, evidence, permitted context, reason code, and
`no_guess: true`. Missing, ambiguous, duplicate, inactive, and orphaned
results contain no `community_id`. Returned references and contexts are deep
copies, so callers cannot mutate registry state.

Stable reason codes include `COMMUNITY_RESOLVED`, `LEGACY_PATH_MISSING`,
`LEGACY_GROUP_MISSING`, `LEGACY_MAPPING_AMBIGUOUS`,
`LEGACY_MAPPING_DUPLICATE`, `COMMUNITY_INACTIVE`,
`LEGACY_REFERENCE_ORPHANED`, and registration/missing-community errors.

Run focused and full checks:

```text
PYTHONPATH=tools/community3 python3 -m unittest tools.community3.test_community_identity_resolver
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
python3 -m py_compile tools/community3/*.py
```

Rollback/removal is limited to the resolver, its tests/fixture, and this
documentation in a later authorized ticket. No persistent state exists.
Next ticket: review and authorize a test-only resolver contract integration
against a redacted mapping registry fixture; do not connect production data.
