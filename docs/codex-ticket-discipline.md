# Codex Ticket Discipline

One ticket.

One goal.

Audit first.

Implement second.

Verify thoroughly.

Commit.

Tag.

Push.

Visually inspect before issuing another implementation ticket.

Prefer smallest viable diff.

Reuse existing routes, services, repositories, and validation.

Avoid duplicate systems.

Separate architecture, implementation, and presentation.

Do not expand scope unless explicitly instructed.

Treat approved mockups as implementation specifications.

Optimize for minimal compute, minimal churn, and deterministic progress.

When project documents conflict, use this precedence:

1. the current user ticket
2. the active project's Engineering Handoff
3. the active project's Project Cursor
4. `docs/codex-direction-manual.md`
5. active product/design docs
6. historical planning docs

Every ticket should improve one screen, one workflow, or one defect—not all three.

## Local Persistence Model

Teachers.Net-specific facts, decisions, implementation state, and Project
Cursor state belong in this repository's local docs.

The global Engineering Director Playbook lives outside this repo and should
contain reusable methodology only. Do not move Teachers.Net-specific state into
the global playbook, and do not depend on another project's workflow state when
working here.

Shared governance docs live at the root of `docs/`. Project-specific docs live
inside the relevant project directory.

Shared documents:

- Engineering Director Playbook
- Codex Direction Manual
- Engineering Workflow
- Ticket Discipline
- Design System
- Product Definition when it applies across workstreams
- Plugin Architecture
- Global Decision Log

Project documents:

- Project Cursor
- Engineering Handoff
- Capability Snapshot
- Architecture
- Roadmap
- Project-specific specifications

Current project directories:

- `docs/job-center/`
- `docs/core-terms/`
- `docs/membership-taxonomy/`

Google Drive operational documents live under:

- `Teachers.Net Engineering/Shared/`
- `Teachers.Net Engineering/Projects/<Project Name>/`

Default ChatGPT startup reads only the Engineering Director Playbook and the
active project-specific Engineering Handoff. Supporting governance documents
may remain in Drive and are consulted only when the ticket requires them. Drive
must not mirror local repository architecture, implementation detail, full
roadmaps, contracts, design systems, manifests, or ticket history.

Every new ChatGPT project session should read the Playbook and active Handoff,
adopt their state without summarizing them, report the five required state
fields, and stop. Codex continues to read local governance and ticket-specific
documents before changing files. If the active workstream is unclear, stop and
ask before assuming Job Center context.

Every Project Cursor should declare one project state:

- Planning
- Active Development
- Stabilization
- Maintenance
- Archived

## Phase-Transition Governance

These rules govern phase changes prospectively from DOC020R onward:

- Never infer a phase transition.
- A transition requires verified Git evidence, reconciled Project Cursor and
  Engineering Handoff, Google Drive handoff synchronization/readback, and
  explicit Engineering Director authorization.
- Verify repository state before phase reconciliation; keep root governance and
  nested implementation repositories distinct.
- Approval does not imply implementation. Implementation does not imply browser
  convergence. Browser convergence does not imply release-candidate acceptance
  or production deployment.
- An audit backlog is not the product roadmap, and no ticket creates historical
  fact merely by asserting it.
- Keep one objective per ticket, reuse existing infrastructure, avoid broad
  refactors during convergence, and require human browser review after
  meaningful UI implementation.

Core Terms and Membership Taxonomy are related but distinct:

- Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.
- Membership Taxonomy is the curation/classification/human-review workstream for
  legacy taxonomy.
- Membership Taxonomy must not be treated as a Core Terms rename or
  implementation ticket stream.

ChatGPT role:

- product direction
- UX guidance
- architecture review
- prioritization
- planning

Codex role:

- inspection
- implementation
- verification
- Git operations
- documentation updates

Default workflow:

Inspect → plan → approve → implement → verify → commit → push.

Working style:

1. Choose one reference page or flow.
2. Refine it until approved.
3. Extract reusable components or tokens only when they reduce future effort,
   risk, or maintenance.
4. Propagate carefully after the reference is approved.

Default behavior:

Do not create new process unless it reduces effort, risk, or maintenance.

## PREPARE HANDOFF

When the user says `prepare handoff` or asks for session handoff preparation,
Codex should first confirm the active project. If the active project is unclear,
ask before editing any handoff.

Handoff updates must follow `docs/engineering-handoff-template.md` and answer
only:

1. Current Phase
2. Current Ticket
3. Last Completed Milestone
4. Next Five Planned Tickets
5. Current Blockers
6. Recently Adopted Governance Documents
7. Recently Approved Product Decisions
8. Recently Approved Visual References
9. Active Design Authority
10. Immediate Engineering Priorities

Do not add project history, settled architecture, duplicated governance,
contract/design-system content, or implementation details preserved elsewhere.
Update the Project Cursor only for durable project-state, phase, milestone,
decision, risk, or stop-boundary changes. End with the v2 startup prompt.

PREPARE HANDOFF is documentation-only. Do not modify application code. Commit
documentation only if explicitly approved.

Before updating Drive, establish current repository facts, update local
continuity documents first, compare the exact Drive Handoff, and reconcile
legitimate newer Drive facts before writing. Verify the Drive write by connector
readback. Do not claim synchronization without confirmation.

Update the execution plan only when the critical path, priority order, phase
boundary, V1/V1.1/V2 classification, settled decision, major dependency, or
pilot/release acceptance changes. Update the roadmap only when durable sequence
or scope changes.

Project-aware ChatGPT startup prompt template (also maintained at
`docs/chatgpt-startup-prompt.md`):

```text
Project: <Project Name>

Retrieve and read these exact Google Drive documents in order:

1. Engineering Director Playbook
   https://docs.google.com/document/d/1GMT6pOFlhxC3wo4pfx6sxbxjzanPZJduvetY2CD6mWQ
2. <Project Name> Engineering Handoff
   <FULL GOOGLE DOC URL FROM THE ACTIVE PROJECT CURSOR>

Adopt their workflow and current engineering state. Do not reconstruct missing
state from conversational memory and do not summarize the documents. If either
document is unavailable, ask for its link or content.

Consult the Project Cursor, product contract, UX specification, design system,
visual manifest, roadmap, or implementation docs only when the current ticket
requires them.

Reply with only:
- current phase
- current ticket
- last completed milestone
- next five planned tickets
- current blockers

Then stop and wait for my instruction.
```

Every generated handoff prompt must include the full Google Docs URL beneath
each document title. Do not emit title-only retrieval instructions. The active
Project Cursor owns the project-specific Handoff URL; if it is absent or cannot
be verified, report that deficiency instead of guessing a link.

## Visual Verification Policy

Default implementation tickets should use engineering verification, not routine screenshot generation.

Engineering verification should normally include:

- confirming affected routes return 200
- confirming no console errors were introduced
- confirming no horizontal overflow
- measuring affected elements where appropriate
- validating CSS when CSS changes
- running PHP lint only when PHP files change
- running git diff --check

Do not generate screenshots by default.

Generate screenshots only when:

- explicitly requested
- documenting a diagnostic investigation
- documenting a significant before/after milestone
- a rendering anomaly requires evidence
- visual evidence is required for acceptance

Human visual QA is performed by the Engineering Director after implementation.

Codex should optimize for minimum compute while maintaining engineering confidence.

## VISUAL TUNE MODE

VISUAL TUNE MODE is a temporary fast-polish mode for human-guided visual
refinement. It may be entered only when the Engineering Director or site owner
explicitly requests it.

VISUAL TUNE MODE is intended for:

- spacing
- typography
- colors
- radii
- icon sizing
- layout tokens
- CSS variables
- positioning
- component polish

VISUAL TUNE MODE must not be used for:

- PHP
- schema
- routing
- authentication or authorization
- services
- cron
- email
- business logic
- architecture
- feature work

Supported lifecycle commands:

```text
Enter VISUAL TUNE MODE
FINALIZE VISUAL TUNE MODE
ABORT VISUAL TUNE MODE
```

While VISUAL TUNE MODE is active:

- apply only the exact requested visual CSS/token changes
- do not audit broadly
- do not refactor
- do not update docs
- do not commit
- do not tag
- do not push
- touch only the smallest CSS/token surface necessary
- run only the fastest relevant syntax check, usually CSS brace validation or
  equivalent
- report changed tokens/rules and final computed values when quick to measure
- stop and wait for the next instruction

Maintain a concise running ledger while VISUAL TUNE MODE is active.

Example:

```text
VISUAL TUNE SESSION

✓ Header height
96 → 105

✓ Logo size
43 → 36

✓ Nav font
14.08 → 17.6
```

Ledger rules:

- only record values actually changed
- record before → after
- keep newest changes at the bottom
- do not repeat unchanged items
- do not include implementation commentary

Do not generate screenshots by default in VISUAL TUNE MODE unless explicitly
requested.

Stop immediately and request exit from VISUAL TUNE MODE if:

- PHP changes become necessary
- template changes require structure beyond minimal presentation
- a request affects functionality
- architecture would change
- database/schema changes would be required
- a visual tweak causes overflow or a broken layout

When finalizing VISUAL TUNE MODE:

- include the completed session ledger in the final report before verification,
  documentation updates, commit, and push
- run normal verification
- update design-system docs if final token values changed
- run browser verification where relevant
- run smoke tests
- run CSS brace validation
- run `git diff --check`
- commit and push the appropriate repo or repos
- do not tag unless explicitly instructed
- report final measurements and commit hashes

When aborting VISUAL TUNE MODE:

- discard all uncommitted visual tuning changes made during the current Visual
  Tune session
- restore the project to the last committed state
- do not update docs
- do not commit
- do not push
- report that VISUAL TUNE MODE was aborted successfully

## COMPONENT MATCH MODE

COMPONENT MATCH MODE is a temporary high-fidelity convergence workflow for one
existing UI component against an approved visual reference. Use it when the
component already exists and needs to be matched closely without broad redesign
or feature work.

Enter COMPONENT MATCH MODE only when the Engineering Director or site owner
explicitly requests it.

Supported lifecycle commands:

```text
Enter COMPONENT MATCH MODE: [component name]
FINALIZE COMPONENT MATCH MODE
ABORT COMPONENT MATCH MODE
```

Example:

```text
Enter COMPONENT MATCH MODE: Results Toolbar
```

COMPONENT MATCH MODE is intended for one component only. Adjacent components may
be touched only when required to preserve alignment with the target component.

COMPONENT MATCH MODE must not be used for:

- business logic changes
- new features
- data behavior changes
- schema changes
- routing
- authentication or authorization
- email
- cron
- admin workflows
- broad architecture changes

While COMPONENT MATCH MODE is active:

- identify the approved reference and component boundary
- measure the current component
- measure the target component where practical
- prefer CSS, token, and layout refinements
- preserve existing functionality and accessibility
- use existing design tokens where possible
- introduce new tokens only when the value is reusable and clearly belongs in
  the design system
- apply the smallest visual/layout change
- do not commit
- do not tag
- do not push
- do not update docs
- run fast validation only
- report the changed-values ledger, current measured values, and remaining
  visible differences
- wait for human visual review before continuing

Stop immediately and report if:

- architecture changes are required to match the component safely
- PHP/template changes become structural rather than presentation-only
- a request would change functionality
- a request would change data behavior
- a request would touch schema, routing, auth, email, cron, admin workflows, or
  business logic
- a visual tweak causes overflow or a broken layout

Fast validation during COMPONENT MATCH MODE:

- CSS brace validation if CSS changed
- PHP lint if PHP/template files changed
- quick no-overflow check if layout changed
- no full browser suite unless specifically requested during the active tuning
  loop

Maintain a concise running ledger while COMPONENT MATCH MODE is active.

Ledger rules:

- only record values actually changed
- record before → after
- keep newest changes at the bottom
- do not repeat unchanged items
- do not include implementation commentary

Example:

```text
COMPONENT MATCH SESSION: Results Toolbar

✓ Control height
44 → 46

✓ Icon size
18 → 16
```

When finalizing COMPONENT MATCH MODE:

- include the completed session ledger in the final report before verification,
  documentation updates, commit, and push
- run full required verification for affected routes
- run `ddev exec npm run browser:verify`
- run `ddev exec npm run browser:smoke`
- run PHP lint if PHP changed
- run CSS validation if CSS changed
- run `git diff --check`
- update docs only if tokens or component rules changed
- commit and push the appropriate repo or repos
- do not tag unless explicitly instructed

When aborting COMPONENT MATCH MODE:

- revert only uncommitted changes from the active Component Match session
- do not touch unrelated work
- do not update docs
- do not commit
- do not push
- report reverted files and repo cleanliness

## Browser Verification Environment

Teachers.Net browser verification is project-owned and runs from the root repo
through DDEV.

Use:

```bash
ddev exec npm run browser:verify
```

Do not run browser checks through Windows `npx` from WSL. Do not add Node
dependencies to `wordpress/wp-content/plugins/tnet-jobs`; the root repo owns the
minimal Playwright setup.

The default browser verification is non-screenshot smoke coverage. It should
confirm route health, console/page errors, horizontal overflow, and canonical
container measurements where relevant. Screenshots remain opt-in under the
Visual Verification Policy.
