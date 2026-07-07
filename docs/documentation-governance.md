# Teachers.Net Documentation Governance

Status: Canonical workflow structure
Scope: Multi-project Teachers.Net engineering documentation

## Purpose

Teachers.Net has multiple concurrent workstreams. Shared engineering governance
belongs in shared documents. Workstream-specific state belongs in the relevant
project directory.

Do not use Job Center state as the default for Core Terms, Membership Taxonomy,
or future Teachers.Net modules.

## Local Shared Governance Docs

Local shared governance documents apply across Teachers.Net workstreams and
live in this repository:

- `docs/codex-direction-manual.md`
- `docs/codex-ticket-discipline.md`
- `docs/design-system-v1.md`
- `docs/plugin-architecture.md`
- `docs/decision-log.md`
- `docs/local-dev.md`
- `docs/stack.md`

External shared Google Drive documents:

- Engineering Director Playbook
- Codex Direction Manual
- Engineering Workflow

Shared documents should contain reusable methodology, environment rules,
cross-plugin boundaries, global design direction, and global decisions. They
should not hold one workstream's live ticket cursor or short-term handoff state.

## Local Project-Specific Docs

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

## Google Drive Operational Docs

Google Drive is the operational recovery layer for ChatGPT Engineering Director
sessions. It is not a mirror of this repository.

Canonical Google Drive structure:

- `Teachers.Net Engineering/Shared/Engineering Director Playbook`
- `Teachers.Net Engineering/Shared/Codex Direction Manual`
- `Teachers.Net Engineering/Shared/Engineering Workflow`
- `Teachers.Net Engineering/Projects/<Project Name>/<Project Name> Project Cursor`
- `Teachers.Net Engineering/Projects/<Project Name>/<Project Name> Engineering Handoff`

Google Drive Project Cursor and Engineering Handoff documents are concise
continuity documents. Local repository docs remain the durable source for
implementation details, architecture, roadmap, design system, decision log, and
plugin-specific specifications.

## Startup Rule

Every new project session should read:

1. Shared governance docs.
2. The target project's project-specific Project Cursor.
3. The target project's project-specific Engineering Handoff.
4. Deeper local project docs only as needed for the ticket.

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
