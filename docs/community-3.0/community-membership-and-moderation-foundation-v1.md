# Community 3.0 Membership and Moderation Foundation v1

Status: implemented local foundation; integrated runtime acceptance only. No
Sandy/production migration, writer cutover, notification delivery, or email
enrollment is enabled by this document.

Source/runtime proof: `/home/bobreap/projects/teachers-net-community3`, branch
`COMMUNITY3-ui-working`, commit
`24b6fbc98307f580663a6dbd2f484d2300288dcb`; integrated `teachers-net-live`
runtime status `ok`, plugin tree hash
`ac0353f54987f74d457f1a3aa0340617ccb1b37e3f8943d2d8fef272c734649a`.

## Ownership

WordPress supplies authenticated identity. C3 owns the opaque relationship
between `community_id` and canonical `user:{id}`. Legacy `tnet_memberships`
remains compatibility evidence and is never used as the C3 write store. A
legacy `group_id` may reach C3 only through an explicit group-to-Community
mapping; `path_id` and `group_id` remain distinct.

`community_memberships` has one durable row per Community/user pair. `active`
and `left` are the supported states. Join and leave are idempotent, state
changes are timestamped, and membership audit rows record the actor and
transition. Membership does not imply follow, notification preference, or
email consent.

`community_membership_migrations` is a bounded, restartable ledger. It retains
source membership identity, legacy group/user references, checksum, mapping and
identity disposition, run/rule identity, target membership, and source
snapshot. An unresolved mapping or identity records `UNRESOLVED` without
creating a target. A local batch rollback removes only target memberships
created with source provenance and retains the ledger until explicitly cleaned
by the local fixture/operator.

## Moderation minimum

Authenticated users may report a published/restored C3 topic or reply using a
bounded reason vocabulary: abuse, harassment, spam, privacy, copyright, or
other. Reports have opaque identity, target/type/community, private reporter
identity, bounded note, state, idempotency key, and timestamps. Reporter
identity is not exposed by the public feed; the authorized local moderation
queue is the only current review surface.

`manage_options` is the proven local administrator boundary. The service also
recognizes a future `moderate_community` capability if a governed role later
supplies it; this objective grants no new role or broad moderator power.
Authorized review may dismiss a report or take one reversible publisher-owned
action (`hidden`, `spam`, or `retracted`). The service performs the target
publisher lifecycle transition and report resolution/audit in one transaction.
Invalid or already-resolved report transitions fail closed. Publisher audit
remains the content-lifecycle authority; report audit remains the report-state
authority. Appeals, sanctions beyond the bounded content actions, duplicate
aggregation, rate controls, reporter disclosure policy, and user suspension/
ban are later policy/work objectives.

No membership or moderation method calls the notification provider, email
preferences, mail functions, or delivery queue. Those are intentionally
reserved for `COMMUNITY3-V1-RELATIONSHIP-NOTIFICATION-PREFERENCE001`.

## Local proof

`tools/community3/test_membership_moderation_foundation.php` installs the
additive schema, proves join/leave idempotency and reload state, maps one legacy
membership with provenance, records an unresolved mapping without a target,
proves migration rerun and rollback, persists a report, denies an unauthorized
queue read, resolves a report through publisher lifecycle, validates audit
agreement, and cleans its synthetic rows. The test runs only in the local
WordPress/DDEV runtime.
