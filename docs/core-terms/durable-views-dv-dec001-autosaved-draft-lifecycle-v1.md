# DV-DEC001 — Approve Autosaved Draft Lifecycle (V1)

**Status:** Accepted as a product decision; implementation separately authorized
**Date:** 2026-08-06
**Scope:** Durable Views V1 draft authoring

## Decision

Durable Views V1 will support an autosaved draft lifecycle. Autosave is a
recovery and continuity mechanism for the single mutable draft of a View. It
does not publish, change the active published version, create a new View
version, or make a consumer-visible change.

The user-facing lifecycle is:

```text
Published version → one active draft → autosaved working state
                                      ↘ explicit Publish → new immutable published version
```

The active draft remains the authoritative editable object. Autosave records
its latest valid working composition and metadata so an interrupted editing
session can be recovered. The UI must distinguish autosaved state from the
explicitly published state.

## V1 semantics

- Each View has at most one active draft.
- Published versions remain immutable and continue serving consumers while a
  draft is being edited or autosaved.
- Autosave is scoped to the active draft and is atomic across the draft's
  View-owned metadata, groups, ordering, and canonical UUID placements.
- Autosave may occur after an eligible draft mutation or through a bounded
  debounce. It must not issue a publish transition.
- An autosave failure leaves the last durable draft state available and shows
  a truthful recoverable error; it must not report success.
- Revert restores the most recent durable autosaved draft state after explicit
  confirmation. It does not alter the published version.
- Delete Draft removes only the active draft and its draft-owned composition
  records after explicit confirmation. It leaves the View identity,
  published versions, consumer bindings, and Core Terms unchanged.
- Publish validates the current durable draft, then creates the next immutable
  published version. Autosave never bypasses validation.
- Opening an existing draft resumes that draft; creating a second draft for
  the same View is rejected or directed to the existing-draft flow.

## Authority and security boundaries

Core Terms remains the taxonomy authority. Autosaved records contain canonical
UUID references and View-owned presentation metadata only; they do not copy or
mutate taxonomy. Jobs remains a consumer and retains only its approved binding
and fallback behavior. All write operations remain behind the Views repository
and protected administration/service boundary with capability and nonce checks.

## Required implementation contract

The follow-on implementation ticket must provide an atomic repository/service
contract equivalent to:

- `get_active_draft(view_id)`;
- `autosave_draft(version_id, composition, expected_revision)`;
- `revert_draft(version_id, expected_revision)`;
- `delete_draft(version_id, expected_revision)`;
- `publish_draft(version_id, expected_revision)`.

The implementation must define durable revision or snapshot identity,
last-saved timestamp, actor, failure behavior, and optimistic-concurrency
handling. A status flag alone is insufficient because the current repository
writes draft entries and groups directly and has no saved-state snapshot or
draft deletion operation.

The implementation may choose a dedicated snapshot/revision relation or an
equivalent versioned persistence design, subject to a separate engineering
ticket. It must not overload `based_on_version_id` to mean autosave state, and
must not mutate published rows in place.

## Explicit non-goals

This decision does not authorize:

- schema or repository changes;
- browser/UI implementation;
- multi-user collaborative editing;
- draft branching, version history, templates, inheritance, or approval
  workflows;
- consumer-specific drafts;
- local-browser-only autosave as a substitute for durable server persistence.

## Consequence for roadmap

DV-UX009 remains blocked until an implementation ticket converts this decision
into a tested persistence/service seam. DV-UX010 and later V2 capabilities do
not begin as a result of this decision.

## Verification

Inspected: `class-cfm-views-repository.php`, the Profilaxes schema authority
(`class-cfm-schema.php`), the V1 product specification, the authoring-model
audit, the DV-ARCH003 diagnostic, and the DV-UX009 blocker report. No
application, schema, database, user, membership, Jobs, or browser state was
changed by DV-DEC001.
