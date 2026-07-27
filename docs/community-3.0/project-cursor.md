# Community 3.0 Project Cursor

## Project State

Maintenance

## Current Phase

Post-launch corrective stabilization of the legacy teacher-group subsystem.

## Current Milestone

Completed correction of the fossilized `path_id == group_id` assumption. The
AI in Education board demonstrated that a chatboard `path_id` can differ from
its teacher `group_id` (`241` versus `227`). The correction resolves teacher
groups through `local_path -> group_id` and preserves `path_id` for chatboard,
post, and feed operations.

## Permanent Invariant

A chatboard `path_id` is not a teacher `group_id`. Teacher-group and membership
operations must resolve or carry the canonical `tnet_groups.group_id` and must
not query `tnet_memberships` with `tnet_local_data.path_id` unless an explicit
mapping has established equality for that record.

## Verification State

The completed correction was reported verified for join, leave, reload state,
header star, sidebar membership/count/avatars, group settings, email-frequency
reads and persistence, Chat Center counts, the divergent AI in Education board,
and a legacy control board. Temporary diagnostics were removed before closure.

## Next Decision

No new group architecture or redesign is authorized by this correction. Reopen
only through a focused Community 3.0 ticket.
