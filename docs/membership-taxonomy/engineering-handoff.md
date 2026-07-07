# Membership Taxonomy Engineering Handoff

Membership Taxonomy is the Teachers.Net workstream for curating historic
chatboard taxonomy into future Core Terms candidates.

Core Terms is the plugin/platform/runtime. Membership Taxonomy is the
curation/classification/human-review layer. It does not mutate Core Terms by
itself.

Membership Taxonomy is not a Core Terms rename or implementation ticket stream.
Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.

## Current Working State

- Project state: Planning.
- Current phase: legacy taxonomy discovery and classification.
- Current milestone: CT068 Legacy Chatboard Taxonomy Classification Audit.
- Current focus: classify historic Teachers.Net chatboard taxonomy candidates
  into review categories.
- Current ticket convention: use CT### unless explicitly changed.
- Guiding test: does this represent something teachers meaningfully belong to?

## Recently Completed

- ED001 created the project documentation directory and initial continuity
  docs so future sessions do not accidentally reuse Job Center state.
- ED004 completed the Membership Taxonomy context so the workstream can
  cold-start independently from Google Drive and local docs.

## Immediately On Tap

Run CT068 as an audit/classification pass.

Expected classification categories:

- Core Membership Term
- Chatboard-only topic
- Activity/Event
- Interest/Hobby
- Legacy-only
- Needs Human Review

Human review is required before any taxonomy enters Core Terms.

## Relationship To Jobs

Membership Taxonomy is parallel to Jobs. It does not block Jobs. It may improve
future seed dataset generation by clarifying audience/classification vocabulary.

## Guardrails

- Do not infer Membership Taxonomy behavior from Job Center.
- Do not treat Core Terms implementation as Membership Taxonomy requirements.
- Do not write code.
- Do not import data.
- Do not mutate Core Terms.
- Do not change Jobs.
- Do not rename plugins or Core Terms internals.

## Stop-After Boundary

Stop after classification and audit reporting. Do not proceed to imports,
implementation, Core Terms mutation, Jobs changes, or plugin renaming without an
explicit follow-up ticket.

## Source Documents

- `docs/documentation-governance.md`
- `docs/membership-taxonomy/project-cursor.md`
- `docs/plugin-architecture.md`
- `docs/decision-log.md`
