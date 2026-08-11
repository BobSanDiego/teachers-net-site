# Shared Project Record Specification

Project records are declarative facts, not workflow algorithms. A record may
identify the project, repositories, companion conversation, authority files,
runtime, handoff archive, Report/Hopper, and workflow inclusion/exclusion
rules. Shared builders must resolve these values from the record rather than
hard-coding a project's product paths.

## Handoff builder fields

The canonical implementation is `tools/codex_archive/project_handoff_builder.py`.
It accepts an explicit registered `project_id`, resolves the matching record,
and publishes a validated immutable checkpoint from the record's
`handoff_source`, `handoff_payload_members`, `guidance_sources`, and
`handoff_build_directory` fields. Unknown, duplicate, or invalid records fail
closed. The builder never infers project identity from the current directory,
conversation title, or stale hopper contents.
