# PROJECT BOOTSTRAP SPECIFICATION

This shared authority defines only the transition from an unregistered project
to a registered, lifecycle-ready project. It references the shared Operating
Contract, Project Record, Transcript Archive, Report/Hopper, and Handoff
Lifecycle authorities rather than duplicating them.

## States

**UNREGISTERED** — no valid shared project record exists. Inspection is
permitted; initialization must not be assumed.

**ONBOARDING AUTHORIZED** — the Engineering Director has explicitly authorized
bounded initialization. The exact command `BOOTSTRAP` for a named project is
sufficient authorization for the bounded,
non-product actions in this specification; no second boilerplate phrase is
required. Codex may create only the minimum truthful project infrastructure.

**REGISTERED / LIFECYCLE READY** — the initialization gate has passed. Normal
tickets and PREPARE HANDOFF may proceed. State transitions are never inferred.

## Pre-onboarding inspection

Establish from evidence, where applicable: stable project identity; root and
nested repositories and ownership; branch/status; existing work and
authorities; Cursor/Handoff and roadmap equivalents; runtime and QA pointers;
ChatGPT/Codex history; Report/Hopper; and predecessor relationships. An
unregistered project may be an established legacy project with extensive
history. Do not borrow another project's facts.

## Minimum initialization

After authorization, create a project record using PROJECT-RECORD-SPEC with
verified facts: identity, repositories, conversation/session identity,
Report/Hopper, HANDOFFS and shared projection, archive/master paths, authority
index, continuity authorities, runtime/QA pointers, and guidance rules.
Unknown optional facts remain unresolved.

Reuse existing authority equivalents. Create only the minimum truthful
manifest, Cursor, Handoff, or roadmap layer needed; do not automatically create
design systems, UX atlases, schema contracts, or product definitions.

Initialize transcript and Codex archival state with existing tooling and
TRANSCRIPT-ARCHIVE-SPEC. Preserve source bodies, boundaries, identity, hashes,
and limitations. Exact duplicates may be mechanically deduplicated; uncertain
overlap remains explicit. Never summarize or promote transcript text into
authority.

## Legacy migration

Treat legacy migration separately. Preserve multiple sessions, predecessor
names, overlaps, superseded authorities, and old artifacts with provenance.
Surface predecessor consolidation for Engineering Director decision. Prefer a
mature shared-workflow Codex environment for historical registration; a fresh
Codex should enter through START-CODEX only after registration.

## Readiness gate

REGISTERED / LIFECYCLE READY requires, where applicable: unambiguous identity;
repository ownership; valid project record; minimum continuity authority;
initialized ChatGPT and Codex state; isolated Report/Hopper with a validated
formal cycle; HANDOFFS;
reachable shared authorities; runtime/QA facts; no borrowed assumptions;
contradictions surfaced; verification reported; and required Engineering
Director authorization.

## New-project flow

START-CODEX routes: `BOOTSTRAP` → project-record lookup → this
specification → registration/scaffolding → master/evidence generation →
validated Report/Hopper cycle → immutable checkpoint → checkpoint validation →
readiness gate → normal workflow. This path has one shared owner and must not
require project-specific bootstrap instructions.

Registered projects do not repeat onboarding. BOOTSTRAP resolves the current
machine Workflow V2 version, project record, lifecycle state, repository,
project-specific continuity, and Report/Hopper route. Successful reconciliation
reports BOOTSTRAP COMPLETE, Project, Workflow V2, and Lifecycle READY. A new
project enters the bounded onboarding state without authorizing product work.
