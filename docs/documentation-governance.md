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

External Google Drive operating documents:

- Engineering Director Playbook
- the active project's Engineering Handoff

The Playbook contains reusable methodology. The Handoff contains only current
operational state. Shared local documents should contain environment rules,
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
- any additional continuity document declared by its Project Cursor
- capability snapshot, if applicable
- architecture notes, if applicable
- roadmap, if applicable
- project-specific specifications

The Project Cursor preserves durable project state. Engineering Handoff v2 is
the delta-oriented session continuity document and follows
`docs/engineering-handoff-template.md`. Roadmaps, architecture docs, contracts,
design systems, manifests, and specifications remain deeper references.

Every Project Cursor must declare one project state:

- Planning
- Active Development
- Stabilization
- Maintenance
- Archived

Use the state to orient a cold-start session quickly. Do not use it as a
substitute for the current ticket or handoff.

## Google Drive Operational Docs

Google Drive is the operational recovery layer for ChatGPT Engineering Director
sessions. It is not a mirror of this repository.

Canonical Google Drive startup structure:

- `Teachers.Net Engineering/Shared/Engineering Director Playbook`
- `Teachers.Net Engineering/Projects/<Project Name>/<Project Name> Engineering Handoff`

Google Drive may retain supporting governance documents, but a fresh ChatGPT
session reads only the Playbook and current Engineering Handoff by default. The
Project Cursor, product contract, UX specification, design system, visual
manifest, roadmap, and implementation documents are consulted only when the
ticket requires them. Drive does not mirror repository architecture,
implementation detail, full roadmaps, or ticket history.

Codex should read local repository docs directly. ChatGPT should use Google
Drive only to recover operational context at the start of a new session.

## Startup Rule

Every new ChatGPT project session should read:

1. Engineering Director Playbook.
2. The target project's Engineering Handoff.

It should adopt that state without summarizing either document and report only
current phase, current ticket, last completed milestone, next five planned
tickets, and current blockers. Deeper governance and repository docs are read
only as needed for the ticket. Codex still follows repository `AGENTS.md` and
ticket-specific local read requirements before changing files.

If the project is unclear, stop and ask which workstream is active before using
Job Center, Core Terms, or Membership Taxonomy state.

## Current Project Directories

### Job Center

Path: `docs/job-center/`

Primary local operational docs:

- `docs/job-center/project-cursor.md`
- `docs/job-center/engineering-handoff.md`
- `docs/job-center/v1-execution-plan.md`

### Core Terms

Path: `docs/core-terms/`

Primary local operational docs:

- `docs/core-terms/project-cursor.md`
- `docs/core-terms/engineering-handoff.md`

Core Terms capability, admin IA, and integration contract docs currently live in
the Core Terms plugin repository under
`wordpress/wp-content/plugins/profilaxes/docs/`. Root Core Terms docs may point
there rather than duplicating plugin-owned detail.

### Membership Taxonomy

Path: `docs/membership-taxonomy/`

Primary local operational docs:

- `docs/membership-taxonomy/project-cursor.md`
- `docs/membership-taxonomy/engineering-handoff.md`

Membership Taxonomy is the curation, classification, and human-review workstream
for historic Teachers.Net chatboard taxonomy. It is separate from Core Terms:

- Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.
- Membership Taxonomy is not a Core Terms rename or implementation ticket
  stream.

Do not seed Membership Taxonomy with Job Center state, Core Terms implementation
state, imports, schema changes, or plugin rename assumptions.
