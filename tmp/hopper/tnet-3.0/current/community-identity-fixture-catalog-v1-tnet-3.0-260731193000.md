# Community Identity Resolver Fixture Catalog v1

`tests/fixtures/community3/legacy-publisher/identity-resolver-cases.json`
contains synthetic scenarios for resolved path/local/group references,
missing references, ambiguous and duplicate source mappings, inactive
Communities, and orphaned legacy references. The Python tests additionally
cover multiple references for one Community, separate Communities, immutable
returned values, isolated resolver instances, deterministic repeatability,
and canonical-context-only access.

No production IDs, names, personal data, URLs, post content, or copied records
are present. Numeric legacy examples are not used. The fixture intentionally
does not authorize or model production mapping data.
