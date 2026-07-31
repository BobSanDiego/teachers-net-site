# Legacy Community Identity Mapping Audit v1

## Evidence

The repository documents `tnet_local_data.local_path` as chatboard/path context,
`tnet_groups.local_path` as the teacher-group lookup context, and
`tnet_groups.group_id`/`tnet_memberships.group_id` as group identity. The
verified resolution rule is `tnet_local_data.local_path ->
tnet_groups.local_path -> tnet_groups.group_id`; `path_id` remains valid for
chatboard, post, and feed operations. The legacy publisher itself resolves
`local_data` and carries `local_path`, but does not provide a complete modern
Community mapping contract.

Notification fixtures and adapters require explicit mapping evidence and keep
path and group values separate. The characterization fixture uses synthetic
divergent values solely as regression evidence.

## Mapping census

| Mapping class | Current conclusion | Required treatment |
|---|---|---|
| one-to-one | Possible, not universal proof | Record explicit evidence per Community |
| one-to-many streams/views | Supported by target design; legacy census incomplete | Permit multiple legacy references |
| many-to-one | Possible through shared group context; not established globally | Reject silent collapse; reconcile explicitly |
| missing | Expected for some legacy records | Unresolved compatibility state |
| duplicate | Not safely ruled out | Quarantine for human/reconciliation review |
| ambiguous | Not safely ruled out | No membership, publication, or notification action |
| inactive/orphaned | Not exhaustively enumerated | Preserve immutable record and lifecycle status |

No evidence supports numeric realignment. No records were queried or modified
by this ticket, so this is a contract-level audit rather than a row census.

## Authority during migration

An approved mapping registry/repository becomes authoritative for translations.
Each mapping records source type/key, `community_id`, evidence, status,
effective time, reviewer, and audit reference. Legacy source fields remain
immutable snapshots; conflicts never overwrite the last accepted mapping.
