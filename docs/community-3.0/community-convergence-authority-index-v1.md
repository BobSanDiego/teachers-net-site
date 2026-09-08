# Community 3.0 Convergence Authority Index v1

Status: active index ratified by `COMMUNITY3-CONVERGENCE-CONTRACTS001`.
This index replaces the prior reconciliation package as the active scheduling
index while preserving that package as historical evidence.

## Authority order

1. `community-convergence-authority-addendum-v1.md`,
   `community-v1-authority-consolidation-v1.md`, and explicit Director
   decisions.
2. Canonical identity, visibility/moderation, publisher, thread, historical
   source-profile, migration-ledger, and URL contracts.
3. Contextual retrieval, feed-card, composer, page/modal, notification
   destination, media provenance, and analytics contracts.
4. Implementation plans, readiness gates, and per-board pilot decisions.

## Ratified package

| Document | Disposition | Role |
|---|---|---|
| convergence authority addendum | CREATE | sole writer, staged migration, aliases, duplicate/status policy, sequence |
| legacy historical source profile | CREATE | immutable source identity, flat replies, identity/state exceptions |
| migration ledger and reconciliation | CREATE | idempotency, restart, batch rollback, counts and audit |
| historical media/static-artifact provenance | CREATE | persisted media/OG evidence and fallback rules |
| canonical contextual retrieval | CREATE | immutable targets and bounded cursor context |
| entry/page/modal navigation | CREATE | canonical page versus same-context modal |
| notification destination | CREATE | semantic target and access-time visibility check |
| page-context analytics/relevance | CREATE | privacy-bounded context events, no consent inference |
| production writer/routing readiness | CREATE | pre-cutover runtime, route, moderation, rollback gates |
| thread data migration plan | AMEND | legacy flat-source branch |
| feed card contract | AMEND | discovery versus canonical-page destination |
| publisher migration recommendation | AMEND | ratified staged full-migration end state |
| publication lifecycle | AMEND | legacy read-only/archive role after cutover |
| preserve/translate/retire matrix | AMEND | no dual writer per migrated scope |
| compatibility observation contract | AMEND | source/ledger provenance fields |
| domain event contract | AMEND | context events and semantic destinations |
| reply notification contract | AMEND | canonical destination policy |
| bell/read-state contract | AMEND | canonical target and modal/page policy |
| master plan, capability catalog, integrated roadmap, project cursor/queue | AMEND | point durable planning to this index and next boundary |
| v1 authority consolidation | CREATE | accepted v1 scope, navigation, feed relationships, notifications, moderation, and deferred social intelligence |

## V1 authority consolidation

`community-v1-authority-consolidation-v1.md` is the canonical compact summary of
the accepted v1 term/community model, feed relationships, Hot Topics boundary,
navigation, composer/feed surfaces, notification states, moderation minimum,
personalization boundary, and essential/near/deferred scope. It records
authority and scope; it does not claim implementation or authorize schema,
runtime, or production changes.

## Keep authority

Canonical Community/board identity, publisher persistence/event, publishing
capability, post/threading, thread architecture/permalink, URL architecture,
legacy URL redirect, and identity/slug contracts remain KEEP authority from
the reconciliation. Nothing in this index weakens privacy, consent,
moderation, audit, or `path_id != group_id` invariants.

## Next boundary

No implementation ticket is opened by this consolidation. The preferred next
v1 foundation is moderation, followed by relationship/feed and notification
foundations, each requiring its own authorized ticket. Migration, routing,
writer, and production/cutover gates remain governed by the existing contracts.
