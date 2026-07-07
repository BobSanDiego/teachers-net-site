# Teachers.Net Documentation Governance

Status: Canonical workflow structure
Scope: Multi-project Teachers.Net engineering documentation

## Purpose

Teachers.Net has multiple concurrent workstreams. Shared engineering governance
belongs in shared documents. Workstream-specific state belongs in the relevant
project directory.

Do not use Job Center state as the default for Core Terms, Membership Taxonomy,
or future Teachers.Net modules.

## Shared Documents

Shared documents apply across Teachers.Net workstreams:

- `docs/codex-direction-manual.md`
- `docs/codex-ticket-discipline.md`
- `docs/design-system-v1.md`
- `docs/plugin-architecture.md`
- `docs/decision-log.md`
- `docs/local-dev.md`
- `docs/stack.md`

External shared document:

- Engineering Director Playbook

Shared documents should contain reusable methodology, environment rules,
cross-plugin boundaries, global design direction, and global decisions. They
should not hold one workstream's live ticket cursor.

## Project Documents

Project documents belong under their project directory:

- `docs/job-center/`
- `docs/core-terms/`
- `docs/membership-taxonomy/`

Each active project should maintain:

- Project Cursor
- Engineering Handoff
- capability snapshot, if applicable
- architecture notes, if applicable
- roadmap, if applicable
- project-specific specifications

The Project Cursor is short and immediate. The Engineering Handoff preserves
session continuity. Roadmaps, architecture docs, and specifications remain
deeper references.

## Startup Rule

Every new project session should read:

1. Shared governance docs.
2. The target project's Project Cursor.
3. The target project's Engineering Handoff.
4. Deeper project docs only as needed for the ticket.

If the project is unclear, stop and ask which workstream is active before using
Job Center, Core Terms, or Membership Taxonomy state.

## Current Project Directories

### Job Center

Path: `docs/job-center/`

Primary startup docs:

- `docs/job-center/project-cursor.md`
- `docs/job-center/engineering-handoff.md`

### Core Terms

Path: `docs/core-terms/`

Primary startup docs:

- `docs/core-terms/project-cursor.md`
- `docs/core-terms/engineering-handoff.md`

Core Terms capability, admin IA, and integration contract docs currently live in
the Core Terms plugin repository under
`wordpress/wp-content/plugins/profilaxes/docs/`. Root Core Terms docs may point
there rather than duplicating plugin-owned detail.

### Membership Taxonomy

Path: `docs/membership-taxonomy/`

Primary startup docs:

- `docs/membership-taxonomy/project-cursor.md`
- `docs/membership-taxonomy/engineering-handoff.md`

This directory is reserved for the future membership taxonomy workstream. Do not
seed it with Job Center state.
