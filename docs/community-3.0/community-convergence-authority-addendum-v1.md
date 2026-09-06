# Community 3.0 Convergence Authority Addendum v1

Status: ratified program authority; documentation only in this cycle.
Effective scope: historical Teachers.Net discussions and future Community 3.0 discussion writes.

## Terminal authority

Community 3.0 is the terminal canonical discussion store and writer. Historical
migration is staged full migration, not blind copying. During shadow comparison,
pilot, and rollback windows, legacy is a temporary read-only compatibility and
archive boundary. A migrated scope has exactly one writer; dual writers are
forbidden. Legacy writing retires only after verified cutover, reconciliation,
rollback, URL, moderation, and archive gates.

The addendum supersedes the prior Community-discussion documentation-first
NO-GO only for this ratified convergence program. It does not authorize an
import, schema change, route change, production writer, or Sandy mutation.

## Non-negotiable invariants

- Preserve legacy post IDs, topic IDs, URLs, timestamps, raw status, and source
  evidence as immutable provenance. `topic_id` and root `post_id` remain distinct.
- Legacy replies are flat by topic/root. Do not invent historical nesting or
  infer a reply target absent from source evidence. New Community replies may
  use the existing richer lineage model.
- Every discussion has one durable canonical public URL. The discussion page is
  the indexable/monetizable SEO object; replies use stable addressable targets or
  fragments within it.
- Legacy public URLs resolve through a verified one-hop alias to the canonical
  destination only when the mapping is public and unambiguous. Otherwise use an
  approved archive/restricted/exception disposition.
- Feed cards are discovery projections. Modals are noncanonical,
  context-preserving interactions. Cold, external, search, share, cross-context,
  and discovery-notification entry resolves to the canonical page. A modal must
  not chain to another modal; close/focus/scroll return remains required.
- Context transitions may produce privacy-bounded relevance/analytics events.
  They do not grant communication consent, change visibility, or authorize
  disclosure or advertising use.
- Legacy status=9 content is excluded from public migration by Director decision.
  Preserve its raw state, provenance, and archive evidence. Structural or
  unmapped exceptions enter deterministic quarantine; age or brevity alone is
  not an exclusion reason.

## Duplicate policy

Migrate the earliest qualifying source record for a deterministic exact
repeated-submit duplicate and exclude later copies with reason code
`EXCLUDED_DETERMINISTIC_DUPLICATE`. Consecutive source IDs are evidence, not a
required invariant: exact identity, context, content, media/meta, and status
evidence may establish the event without adjacency. Exact same-author content
cross-posted across boards is excluded only when the earliest occurrence and
cross-post relationship are deterministic.

Retain every excluded source ID and original URL in the migration ledger and
alias evidence. Do not automatically collapse fuzzy/near-text matches from
similarity or timing. Ambiguous near-repeats normally migrate rather than
consume disproportionate manual review.

## Authority hierarchy

1. This ratified addendum and Director decisions.
2. Canonical identity, visibility/moderation, publisher, thread, source-profile,
   migration-ledger, and URL contracts.
3. Retrieval, feed, page/modal, notification-destination, and analytics
   contracts.
4. Implementation/runbooks and per-board pilot decisions.

Presentation cannot redefine identity. Analytics/relevance cannot authorize
communication or alter visibility.

## Sequence and gates

1. `COMMUNITY3-MIGRATION-FOUNDATION001`: local/reversible source identity,
   idempotent migration ledger, board mapping, and URL aliases; no public import
   or writer cutover.
2. Satisfy production writer/routing readiness dependencies.
3. Import and reconcile a bounded AI in Education local pilot.
4. Obtain native HUMAN_QA, then explicit Director authorization before any public
   writer or route cutover.
5. Stage mapped boards with one writer per migrated scope.
6. Preserve an immutable archive and separately authorize legacy writer/storage
   retirement after rollback expiration.

The suspended CONT5 legacy reply-target enhancement remains outside this
program until separately reauthorized.
