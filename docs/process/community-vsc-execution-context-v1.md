# Community VS Code/Codex Execution Context v1

## Purpose

This is the durable startup arrangement for Community work while Codex Desktop
is unavailable. It keeps Workflow V2 in its registered control plane and makes
the source and runtime mirror visible without confusing their authority.

## Open procedure

1. Open `/home/bobreap/projects/teachers-net-site/Community-VSCode.code-workspace`
   in VS Code.
2. Confirm the three folders are visible with these roles:
   - `CONTROL PLANE — teachers-net-site`: Workflow V2, project records,
     `AGENTS.md`, and Report/Hopper owner.
   - `SOURCE — teachers-net-community3 (registered)`: registered Community
     repository/source authority.
   - `RUNTIME MIRROR — teachers-net-live`: DDEV runtime fixture only.
3. Use the control-plane folder as the terminal working directory.
4. Run `python3 tools/workflow/workflow.py BOOTSTRAP --project community`.
5. Before any formal ticket execution, run the ticket preflight through
   `tools/hopper/clean_cycle.py begin` with the ticket source.

Do not open the runtime mirror as the sole VS Code workspace for a formal
Community ticket. It has no current Workflow V2 entrypoint or Report/Hopper
owner.

## Mandatory completion gate

An executable Community ticket is not terminally COMPLETE until this sequence
has succeeded from the control plane:

`preflight → begin → implementation/verification → final consolidation →
finalize → validate → Report/Hopper publication + operational-current-state`

`PREPARE HANDOFF` is separate and cannot substitute for `finalize` or
`validate`. If implementation succeeds but terminalization fails, report the
workflow failure and leave the objective non-terminal.

## Authority boundaries

The runtime mirror is never source authority. Do not duplicate Workflow V2 into
`teachers-net-live`. Product changes continue in the registered source owner;
the control plane only orchestrates lifecycle and evidence publication.
