# Teachers.Net Engineering Workflow V2

Status: CANONICAL / ADOPTED

Machine identifier: `teachers-net-engineering-workflow` / `V2`

Machine manifest: `workflow-v2.json`

This is the single shared execution workflow for every registered and future
Teachers.Net project. Project records retain project-specific repositories,
authorities, runtime facts, continuity, and Report/Hopper routes. Projects do
not maintain copies of Workflow V2. A conflicting project-local workflow rule
must be surfaced and reconciled; it cannot silently override V2.

## Terminal objective envelope

One formal objective remains open through causally related inspection,
reversible instrumentation, diagnosis, implementation, adjacent
acceptance-blocker repair, retesting, experiment removal, and consolidation.
Do not open FIX sub-tickets merely because another symptom appears.

A new formal objective is required for a distinct user outcome or a material
new product, architecture, schema/data, security/authorization,
destructive/production, or unsafe review-scope decision.

Within the envelope Codex owns investigation order, local implementation,
reversible instrumentation, focused tests, bounded QA-tool repair, disposable
fixture cleanup, retries, consolidation, selective staging, and coherent commit
construction. Stop for contradictory authority, new human product decisions,
destructive/production risk, security/privacy boundaries, or material scope
expansion.

## T+0 intake and ticket validation

Before cycle initialization, archive rotation, repository/browser inspection,
or implementation, validate the complete live ticket with
`tools/workflow/workflow_v2.py` through the shared workflow entry point.
Validation requires:

- exact first executable line `TICKET READY FOR CODEX`;
- ticket ID;
- FAST, STANDARD, DIAGNOSTIC, or CONVERGENCE mode;
- explicit objective owner;
- nonempty terminal OUTCOME;
- nonempty STOP BOUNDARY;
- matching `END TICKET — <TICKET-ID>` terminator;
- declared runtime/input data when marked required.

Malformed or truncated tickets fail immediately with the exact defect. A live
ticket over 15,000 characters receives a warning because that is ChatGPT's hard
authoring/transport limit. Codex does not reject an otherwise complete received
ticket solely for length.

## Decisive evidence and escalation

Plan the evidence that proves the terminal outcome before implementation.
Evidence classes are FUNCTIONAL, RESPONSIVE, VISUAL, DIAGNOSTIC, NATIVE, and
STATE/DATA. Synthetic evidence is never native/physical acceptance.
Infrastructure readiness is never product acceptance.

Escalate posture after the first contradictory pass when human behavior and
automation disagree, a native seam is not faithfully automated, runtime
contradicts source, multiple state owners appear, browser geometry contradicts
semantic assertions, or persistence crosses uncertain owners. Promotion occurs
inside the same objective. Retain the two-pass threshold only for an ordinary
deterministic defect with one known owner and a faithful test seam.

## Acceptance ledger and truthful state

Maintain one objective-level acceptance ledger. Carry a PROVEN seam forward
until its owning source or dependency changes, direct evidence invalidates it,
or final integrated acceptance necessarily exercises it. Do not repeatedly
re-prove settled request/session/state layers.

An objective is not terminal COMPLETE while decisive evidence or required
Engineering Director acceptance is pending. Use implementation-complete,
verification-pending, human-QA-pending, or blocked as truthful internal states
without closing the objective.

CONVERGENCE keeps one formal cycle across bounded experiments. Before terminal
commit remove superseded experiments and diagnostics, confirm intended owners,
inspect the final diff, and run affected terminal acceptance once.

## Human QA

Request physical/native or subjective human evidence at the first complete
meaningful journey when it is decisive and automation is not faithful. Human
acceptance remains authoritative for subjective visual/product judgment and
native interactions. It does not replace deterministic runtime, request,
persistence, or geometry evidence that automation can reliably obtain.

## Reasoning posture

Canonical postures are NORMAL, MEDIUM, and MAXIMUM.

- NORMAL: known owner, settled authority, deterministic local correction,
  faithful test, no material cross-layer uncertainty.
- MEDIUM: browser/runtime discrepancy, state/persistence journey, multiple
  plausible owners, dirty shared owner, migration compatibility, or first
  contradictory pass.
- MAXIMUM: authority conflict, repeated false PASS, shared/multi-project
  architecture, security/authorization/data migration, or expensive
  convergence where deeper reasoning should prevent multiple cycles.

Codex cannot change the Engineering Director's model/UI setting. Before
substantive expensive work on a future ticket, use exactly:

`FOR NEXT TICKET BOOST AI TO * MEDIUM *`

or:

`FOR NEXT TICKET BOOST AI TO *** MAXIMUM ***`

Then stop at that pre-execution boundary. No notice is required for NORMAL.
Do not ratchet indefinitely; recommend de-escalation at the next sensible
boundary.

When a cycle is known to have used MEDIUM or MAXIMUM, ChatGPT's post-cycle
review must prominently use the applicable exact reminder:

- `REMINDER: AI IS NOW * MEDIUM * / RECOMMEND SET TO NORMAL`
- `REMINDER: AI IS NOW * MEDIUM * / RECOMMEND KEEP SETTING FOR ONE MORE CYCLE`
- `REMINDER: AI IS NOW *** MAXIMUM *** / RECOMMEND SET TO NORMAL`
- `REMINDER: AI IS NOW *** MAXIMUM *** / RECOMMEND SET TO * MEDIUM *`
- `REMINDER: AI IS NOW *** MAXIMUM *** / RECOMMEND KEEP SETTING FOR ONE MORE CYCLE`

## BOOTSTRAP

`BOOTSTRAP` is the stable one-word Codex reconciliation command. It resolves
this shared version and the named/current project record through
`tools/workflow/workflow.py`.

For a registered project it reconciles current shared authority, project
identity, repository, project continuity, lifecycle readiness, Report/Hopper
route, and V2 preflight owner without repeating onboarding. Success states:

```text
BOOTSTRAP COMPLETE
Project: <name>
Workflow: V2
Lifecycle: READY
```

For an unregistered named project, BOOTSTRAP is sufficient authorization to
enter the bounded non-product onboarding path in `PROJECT-BOOTSTRAP-SPEC.md`.
It does not authorize product implementation. Genuine identity, ownership,
authority, destructive/production, or required-evidence contradictions stop
the flow with the actual boundary.

## Portable ChatGPT handoff

`PREPARE HANDOFF` is the canonical routine handoff command for every
BOOTSTRAP-ready registered product project. Codex resolves the attached/current
ChatGPT export and project record, then the central owner
`tools/codex_archive/prepare_chatgpt_handoff.py` must:

- fail before mutation when transcript and project identities conflict;
- classify current exports as `OPEN/INCOMPLETE` through an explicit boundary;
- reconcile stable session/message identities incrementally and refuse changed
  historical message hashes;
- preserve portable ChatGPT and Codex records while stating unavailable Codex
  coverage honestly;
- assemble a self-contained visible-file startup payload containing
  `00-LOAD-STARTUP.md`, project identity, current authority, terminal state,
  portable masters, provenance, freshness, and a format-independent manifest;
- validate every required payload member before returning `HANDOFF READY`.

Repeated preparation from an unchanged source must not duplicate conversation
content. A later export from the same open session may add only safely proven
new messages. The fresh ChatGPT command is exactly `LOAD STARTUP`; its semantics
come from the supplied `00-LOAD-STARTUP.md`, never hidden project configuration
or assumed filesystem access.

Job Center ChatGPT is the Teachers.Net house/shared-workflow supervisory
conversation. That convention does not transfer engineering ownership or
Report/Hopper routing. Another project may receive Job Center house history
only by explicit request, classified as contextual evidence and never target
project authority. Shared Workflow has no separate ChatGPT project.

Routine startup preparation and full immutable recovery checkpoints are
separate operations. `PREPARE HANDOFF` does not force a heavyweight checkpoint.
The physical archive-versus-visible-files delivery decision remains deferred;
the logical package manifest is authoritative across representations. The
current preparer may offer both a visible directory and optional ZIP wrapper as
transport candidates without declaring either the permanent standard.

## Mode operation

### FAST

Known owner, narrow reversible change, faithful discriminating test. Read only
the project record and named authority/owner. Patch once, run one focused
verification sweep, inspect the diff, selectively commit/push, and publish the
compact tier. Promote the same objective on uncertainty; do not open a new
cycle.

### STANDARD

One coherent user outcome. Codex may repair adjacent blockers within the
objective envelope. Run the complete affected journey before terminal PASS and
publish one consolidated commit/report.

### DIAGNOSTIC

Instrument the decisive seam before patching when cause/ownership is uncertain
or evidence contradicts. Return targeted causal evidence. Continue to
correction in the same objective only when the ticket authorizes it.

### CONVERGENCE

One persistent formal cycle through bounded experiments. Maintain checkpoints
and the acceptance ledger, remove superseded work, run final affected
acceptance once, and normally produce one terminal commit/report.

## Report/Hopper tiers

The machine manifest defines required tier members. Every tier contains a
terminal report, manifest, cycle JSON, and source ticket. Add only decisive,
focused evidence. Complete committed source files are not copied by default;
use Git commit/blob identity. Copy full source only when uncommitted,
generated/external, not Git-addressable, or explicitly required.

Formal objective owner, acceptance fixture, and execution/report project are
distinct machine fields. Report/Hopper follows the executing Codex agent's
registered project; the logical objective owner and fixture are recorded as
metadata and never silently reroute the agent's report.

An unexecuted or blocked intake is not a formal cycle. The executing agent
maintains one active `UNEXECUTED-STUB.txt` in its normal current Report area,
including the exact terminal response, ticket/source hash, classification,
project, logical owner, and timestamp. The first non-executed event is a
Report-generation boundary: if the current Report contains an executed
payload, archive that payload under `archive/report-generations/` and create a
fresh stub-only current Report. Additional non-executed events append to the
same stub and create no cycle or per-event archive directory. An unchanged
retry matching that stub is rejected until the ticket is materially revised or
the engineer explicitly uses `RETRY BLOCKED`. When a genuine cycle begins, the
accumulated stub is retired once to the stable `archive/unexecuted-stubs/`
area before the new executed payload is published; it is not counted as a new
event cycle. The current Report area therefore contains exactly one generation:
an executed payload or an active stub, never both.

Workflow freshness is a bounded preflight, not a timer. The shared helper
hashes the canonical shared workflow authorities and compares that marker with
the executing project's last-consumed marker. Unchanged authority is a cheap
no-op; changed authority is consumed before execution and frozen for the
cycle. `BOOTSTRAP` and `PREPARE HANDOFF` remain explicit synchronization
boundaries.

## Telemetry and workflow-cost signal

Formal cycles record Workflow V2, objective ID, mode, evidence class,
objective/fixture, known reasoning posture and recommendation, attempts,
checkpoints, human checkpoints, rework cause, payload bytes, and reliable
execution/human-wait timing when available. Never invent unavailable timing or
reasoning posture.

Signal Engineering Director review when workflow/tooling work exceeds 25% of
the recent formal sample or occupies two consecutive formal cycles, unless it
is an active quantified blocker. The signal is not an automatic prohibition.

## Git and safety

Preserve repository identity, root/nested ownership, selective staging,
unrelated dirty work, focused final diff, coherent commit/push, project
isolation, authority ordering, stop-on-contradiction, human product authority,
destructive-operation boundaries, truthful evidence, and immutable continuity.

## Compact live-ticket shape

Live tickets primarily state terminal outcome, owner/project, accepted product
or architecture decisions, authority pointer/version, acceptance invariant,
decisive evidence class, material exclusions, and genuine stop boundary.
Durable execution procedure belongs here, not in repeated ticket boilerplate.

Use these canonical compact shapes. Include the conditional runtime/input
fields only when they are material to the objective.

```text
TICKET READY FOR CODEX
<TICKET-ID> — <title>

MODE: FAST | STANDARD | DIAGNOSTIC | CONVERGENCE
OWNER: <registered objective owner>
EVIDENCE CLASS: FUNCTIONAL | RESPONSIVE | VISUAL | DIAGNOSTIC | NATIVE | STATE/DATA
RUNTIME REQUIRED: YES | NO
CANONICAL URL: <required only when runtime is required>
INPUT REQUIRED: YES | NO
REQUIRED INPUTS: <required only when inputs are required>

OUTCOME
<one terminal user outcome and accepted decisions>

ACCEPTANCE
<the decisive terminal invariant>

EXCLUSIONS
<material exclusions and protected boundaries>

STOP BOUNDARY
<genuine authority, risk, or terminal boundary>

END TICKET — <TICKET-ID>
```

FAST omits broad background and points directly to a known owner and faithful
test. STANDARD states one coherent outcome and affected journey. DIAGNOSTIC
names the uncertain seam and causal evidence needed. CONVERGENCE names the
terminal invariant and accepted baseline while keeping internal experiments in
the acceptance ledger rather than issuing sub-tickets.
