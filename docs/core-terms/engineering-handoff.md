# Core Terms Engineering Handoff

Core Terms is the Teachers.Net classification platform. The plugin repo remains
`wordpress/wp-content/plugins/profilaxes`; the visible product name is Core
Terms.

## Current Working State

- Project state: Maintenance.
- Core Terms platform foundation is complete.
- Milestone tag: `v0.6.2-core-terms-foundation`.
- Plugin-owned docs live in `wordpress/wp-content/plugins/profilaxes/docs/`.

## Recently Completed

- Capability Snapshot.
- Admin Information Architecture.
- Integration Contract.
- Archive lifecycle and Active Connections framework.
- Jobs Active Connections provider in the Jobs plugin.

## Immediately On Tap

No Core Terms implementation ticket is active from this handoff.

## Guardrails

- Do not rename `profilaxes`, CFM classes, `cfm` prefixes, DB tables, routes, or
  namespaces unless explicitly instructed.
- Do not add Jobs code to Core Terms.
- Treat public APIs and integration contracts as stable unless intentionally
  versioned.

## Source Documents

- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-capability-snapshot.md`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-admin-ia.md`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-integration-contract.md`
- `docs/core-terms/teachers-net-seed.md`
