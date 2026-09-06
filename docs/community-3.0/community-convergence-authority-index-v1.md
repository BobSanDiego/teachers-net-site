# Community 3.0 Convergence Authority Index v1

Status: active index ratified by `COMMUNITY3-CONVERGENCE-CONTRACTS001`.
This index replaces the prior reconciliation package as the active scheduling
index while preserving that package as historical evidence.

## Authority order

1. `community-convergence-authority-addendum-v1.md` and explicit Director
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

## Keep authority

Canonical Community/board identity, publisher persistence/event, publishing
capability, post/threading, thread architecture/permalink, URL architecture,
legacy URL redirect, and identity/slug contracts remain KEEP authority from
the reconciliation. Nothing in this index weakens privacy, consent,
moderation, audit, or `path_id != group_id` invariants.

## Next boundary

`COMMUNITY3-MIGRATION-FOUNDATION001` is the next implementation objective. It is
local/reversible and must establish source identity, the migration ledger,
board mapping, and aliases without public import, route cutover, or writer
switch. Production readiness and native HUMAN_QA precede any public cutover.
