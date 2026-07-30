# Teacher Group ID Resolution — Completed Corrective Record

## Decision

Teacher-group operations use the canonical `tnet_groups.group_id`. A
chatboard `path_id` is not a teacher `group_id`.

## Required Mapping

```text
tnet_local_data.local_path -> tnet_groups.local_path -> tnet_groups.group_id
```

Historic boards obscured this distinction because their values apparently
matched. The AI in Education board exposed the defect with `path_id = 241` and
`group_id = 227`.

## Corrective Surface

The completed correction resolved canonical group state in `functions-globals.php`,
then carried it through the chatboard modal, group-join mail-frequency lookup,
Chat Center member counts, header star, and sidebar membership/count/avatar
presentation. Temporary diagnostics and the false equality comment were
removed.

`path_id` remains valid for chatboard, post, and feed functions where it
represents the local chatboard identity.

## Verification

Reported verification covered join, leave, reload membership state, header star,
sidebar membership/count/avatars, group settings, email-frequency selection and
persistence, Chat Center counts, the divergent AI in Education board, and a
legacy board control.

This record documents a completed retrospective correction, not a redesign or
new group architecture.
