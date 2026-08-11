# Shared Project Record Specification

Project records are declarative facts, not workflow algorithms. A record may
identify the project, repositories, companion conversation, authority files,
runtime, handoff archive, Report/Hopper, and workflow inclusion/exclusion
rules. Shared builders must resolve these values from the record rather than
hard-coding a project's product paths.

Workflow V2 requires each registered record to declare:

- stable `project_id`, `display_name`, and lifecycle `state`;
- repository root;
- Report/Hopper base through `report_hopper` or `handoff.report_hopper`;
- `report_label` used to derive the canonical Report/Hopper directory names;
- any temporary compatibility directory names in `report_hopper_aliases`;
- project-specific authorities and shared guidance pointers.

The shared Workflow V2 manifest and algorithms are not copied into project
records. `tools/workflow/workflow_v2.py` resolves the current shared version and
then consumes these project facts. Objective owner and acceptance fixture are
separate cycle metadata; routing always follows the objective owner.

## Handoff builder fields

The canonical implementation is `tools/codex_archive/project_handoff_builder.py`.
It accepts an explicit registered `project_id`, resolves the matching record,
and publishes a validated immutable checkpoint from the record's
`handoff_source`, `handoff_payload_members`, `guidance_sources`, and
`handoff_build_directory` fields. Unknown, duplicate, or invalid records fail
closed. The builder never infers project identity from the current directory,
conversation title, or stale hopper contents.

## Routine ChatGPT startup fields

`handoff_v2.chatgpt_title_patterns` declares the governed project-identity
patterns accepted for a supplied ChatGPT export.
`handoff_v2.conversation_manifest` declares the additive portable-master
manifest. Conversation and Codex master paths remain in the existing
`conversation.master` / `codex.portable_record` fields (or their legacy
top-level equivalents). These are project facts consumed by the single central
`PREPARE HANDOFF` owner; records must not copy its algorithm.

Shared Workflow declares that it has no independent ChatGPT project and points
to Job Center only for explicit supervisory house context. That relationship
does not alter objective ownership or Report/Hopper routing.
