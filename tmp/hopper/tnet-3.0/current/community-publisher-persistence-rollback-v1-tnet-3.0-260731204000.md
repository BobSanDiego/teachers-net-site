# Community Publisher Persistence Rollback v1

Rollback is explicit and local: run the schema uninstall routine through a
local WP-CLI bootstrap, then verify the three prototype tables and schema option
are absent. The smoke script performs this cleanup automatically. No legacy
table is named by the uninstall routine.

Injected post, audit, and event failures are raised inside one transaction and
return deterministic failure codes; rollback removes all writes from that
attempt. A later production-safe migration must add independent backup and
approval procedures; this prototype creates no migration state.
