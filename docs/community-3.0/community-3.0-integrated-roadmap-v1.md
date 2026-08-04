# Community 3.0 Integrated Roadmap v1

| Milestone | Outcome | Gate/stop condition |
|---|---|---|
| M0 | Reconcile capability catalog, census, crosswalk, and decisions. | Stop if authority or identity is disputed. |
| M1 | Approve visibility, subscriber, notification, and event contracts. | Stop if consent, suppression, or audit behavior is unresolved. |
| M2 | Establish Core Terms and Portable Views contracts. | Stop if semantic authority is ambiguous. |
| M3 | Bound a non-delivery Job Center subscriber proof. | Stop if it expands into product implementation. |
| M4 | Reconcile chatboard/path/group mapping and membership behavior. | Stop if `path_id` and `group_id` are conflated. |
| M5 | Define interests and onboarding preference capture. | Stop if profile/consent authority is unclear. |
| M6 | Define bell, reply notices, digest, and administrative notification contracts. | Stop if delivery evidence is absent. |
| M7 | Define oversight, moderation, retention, and relationship governance. | Stop if accountability is not auditable. |
| M8 | Evaluate explainable discovery and recommendations. | Stop if relationship consent or privacy is incomplete. |
| M9 | Consider communications provider, campaign, analytics, AI, and post-view expansion. | Stop unless all prior controls are accepted. |

This sequence is planning authority only. It does not authorize migrations, schema changes, plugin changes, routes, mail delivery, or production edits.

## Recovery Gate

`COMMUNITY-RESTART001` is a temporary, explicit browser-evidence gate over
historical Community UX claims. It authorizes one local-only Modern Topic
Composer v1 correction against the canonical Community DDEV URL. Acceptance
requires authenticated before/after screenshots, a valid runtime badge, and a
browser-gap matrix. It does not authorize roadmap expansion, production UI,
schema, migration, delivery, or any additional UX slice.

`COMMUNITY-RESTART003` is the completed recovery follow-up: one local
browser-visible media-picker correction only. It does not advance M0-M9,
authorize production changes, or reopen the planning stop conditions. Future
Community tickets must begin from `tmp/hopper/tnet-3.0/current`, archive that
directory first, and provide a validated current-cycle payload without copying
screenshots into the hopper.
