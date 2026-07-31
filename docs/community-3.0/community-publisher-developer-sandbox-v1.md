# Community Publisher Developer Sandbox v1

The local `Tools → Community Publisher Workbench` now exercises the complete
developer lifecycle: create topic, direct/nested reply, browse thread, hide,
retract, restore, soft-delete, inspect audit history, and inspect publication
events. All actions remain DDEV-gated, administrator/nonce protected, synthetic,
and local-only. The page is not a Community 3.0 interface.

Thread rows are ordered by the repository query contract. Lifecycle actions
write audit rows through the repository transaction. No notification dispatch,
public route, legacy CGI, migration, or production behavior is connected.
