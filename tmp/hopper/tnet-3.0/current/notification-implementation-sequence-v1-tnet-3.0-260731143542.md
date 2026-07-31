# Community 3.0 Notification Implementation Sequence v1

Status: proposed sequence; implementation is not authorized by this document.

## Sequence

1. C3-IMP002: build and verify a test-only dry-run group-post candidate
   evaluator with synthetic identities and explicit path/group mapping.
2. Resolve the missing Mention Notification Contract and reconcile any open
   recipient/category decisions.
3. Add a read-only contract-to-fixture census covering post, reply, reaction,
   group activity, mention, visibility, suppression, and frequency states.
4. Obtain Engineering Director approval for one persistence boundary, if still
   justified, with rollback and kill-switch design.
5. Implement a non-delivering candidate/audit boundary behind tests and a
   disabled feature flag; no bell or email yet.
6. Review a separate bell/read-state implementation ticket.
7. Review a separate optional email/digest/queue ticket only after consent,
   abuse, provider, and operational decisions are complete.

## Dependency order

```text
contract completeness -> dry-run evaluator -> candidate/audit boundary
       -> approved persistence -> bell/read state -> optional delivery
```

Each arrow requires verification and explicit authorization. No step may infer
authority from a later step or from legacy WordPress/BuddyPress behavior.

## C3-IMP002 first implementation ticket

### Objective

Implement a test-only, dry-run evaluator for one visible Community group-post
event and one synthetic authenticated recipient.

### Required behavior

- carry `path_id` and `group_id` as distinct fields with explicit mapping;
- evaluate membership, visibility, frequency, pause, mute, never, and scoped
  suppression independently;
- return a deterministic candidate decision and redacted reason set;
- cover eligible, private/unauthorized, moderated, muted, never, and
  paused-email cases;
- prove no database write, bell record, mail, queue, schema change, or
  production access occurs.

### Exclusions

No persistent notification model, migration, queue, email provider, digest
scheduler, bell UI, production fixture, legacy replacement, or cross-product
integration.

### Exit criteria

Tests and evidence show the required scenarios, stable reason codes, distinct
identity fields, disabled transport, and clean rollback/removal. Engineering
Director review is required before any follow-on persistence or delivery ticket.
