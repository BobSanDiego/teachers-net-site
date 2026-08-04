# Community 3.0 Roadmap and Semantic Platform Alignment

## Status

Planning alignment document. The platform direction below is converged strategic
guidance, not an implementation authorization.

## Current Community State

Community 3.0 remains in Maintenance after the completed teacher-group identity
correction. The permanent invariant is that a chatboard `path_id` is not a
teacher `group_id`; membership operations resolve `local_path -> group_id` and
preserve `path_id` for chatboard, post, and feed operations.

No new group architecture, notification redesign, production migration, or
semantic-platform implementation is authorized by that corrective milestone.

## Platform Direction

Teachers.Net is increasingly understood as a semantic platform whose
applications consume shared capabilities rather than duplicate terminology and
business logic. The emerging shared layer consists of:

- Core Terms — canonical semantic vocabulary and identity.
- Portable Views — reusable, versioned, subscriber-specific presentations over
  Core Terms references.
- Subscriber Policies — selection and presentation rules owned by each
  subscriber contract.
- Relationship Graphs — human-governed, machine-assisted connections distinct
  from hierarchy.
- Communications Platform — consent, moderation, suppression, event, delivery,
  and audit policy with email treated as one transport.

The working administration concept is **Semantic Studio**: a future governance
surface for Core Terms, Views, relationships, subscriber bindings, policies,
impact analysis, and publication. Semantic Studio is a planning concept, not an
approved product or implementation ticket.

## Subscriber Direction

Job Center is the reference subscriber for the first bounded semantic proof. It
continues to own job facts, provenance, employer authority, lifecycle,
locations, publication, and applications. Core Terms classifies; Jobs
authorizes; WordPress authenticates.

Chatboards and Groups are the second major subscriber. Their identity,
membership, moderation, and notification behavior remain Community-owned. A
membership record may be evidence for an inferred interest, but it is not an
explicit interest and is not communication consent.

Future subscribers may include Profiles and onboarding, Lesson Bank, Search,
Recommendations, Communications, and other products. Each subscriber must own
its product facts and workflow while consuming explicit semantic contracts.

## Ordered Roadmap

1. **Authority and document alignment** — align the canonical architecture
   draft, cursor, handoff, glossary, ownership matrix, and open decisions.
2. **Core Terms and meta-term audit** — identify canonical concepts, structural
   dimensions, presentation groupings, availability controls, ordering metadata,
   and administrative labels before considering retirement.
3. **Subscriber contract model** — define ownership, fields, provenance,
   freshness, correction, compatibility, and failure behavior.
4. **Portable View design** — define selected/excluded terms, ordering, grouping,
   local display nesting, labels, templates, cloning, binding, versioning,
   preview, publication, impact analysis, rollback, deprecation, and retirement.
5. **Bounded Job Center View pilot** — test Subjects or Grade Levels through the
   expandable Core Terms tree, checkbox selection, draft View import,
   drag-and-drop arrangement, display grouping, field preview, one selection
   policy, version evidence, and rollback. No write-back or listing migration.
6. **Chatboard/group census and semantic mapping** — reconcile one cohort’s
   paths, groups, labels, membership, moderation state, and candidate terms.
7. **User-interest and profile evidence model** — separate explicit interests,
   inferred interests, behavioral evidence, source, confidence, retention,
   correction, and expiry.
8. **Onboarding discovery and presentation** — progressively collect high-value
   professional dimensions without presenting the entire taxonomy at once.
9. **Communications preference and consent architecture** — define preference
   history, global pause, opt-out, suppression, category, group override, and
   resolver precedence.
10. **Event ledger and delivery architecture** — define moderation, promotion,
    notification eligibility, queueing, deduplication, coalescing, limits,
    transports, audit, and kill switches.
11. **Relationship candidate and approval model** — define typed relationships,
    evidence, confidence, status, approver, revision, and expiry.
12. **Bounded relationship-discovery pilot** — evaluate machine-proposed
    candidates through human review; do not activate canonical relationships
    automatically.
13. **Explainable recommendation proof** — test bounded related-resource
    results with reason codes and correction controls. Recommendations remain
    separate from Communications because relevance is not delivery consent.
14. **Product-by-product subscriber expansion** — adopt proven contracts only
    where ownership, value, approval, and rollback are clear.

## Governance and State Labels

Planning documents must distinguish verified implementation, approved direction,
proposed design, exploratory concept, and deferred work. The roadmap does not
promote Semantic Studio, relationship automation, subscriber expansion, or
communications delivery to implementation status.

## Open Decisions

- Relationship governance workflow and approval roles.
- Publication lifecycle for semantic assets and View-local labels/nesting.
- Subscriber permission and version-compatibility model.
- Automated relationship suggestions, evidence retention, confidence, and decay.
- Migration strategy for historical meta terms.
- Communications preference hierarchy and the first authorized delivery pilot.
- Rollout sequencing beyond the bounded Job Center View pilot.

## Immediate Stop Boundary

Continue documentation-first convergence. Do not issue implementation tickets,
change schemas, import taxonomy, migrate records, redesign production UI, or
start communication delivery until the first planning package and its stop
conditions are approved.

## Browser-First Recovery Gate — COMMUNITY-RESTART001

Historical Community UX completion claims are informational only. For the
browser-facing recovery sprint, the authenticated browser at
`https://teachers-net-community3.ddev.site` is the acceptance authority.
Source inspection, lint output, commit identity, HTTP checks, and completion
reports do not establish UX acceptance without matching browser evidence and
the runtime authority badge.

The single authorized recovery slice is Modern Topic Composer v1: remove the
visible Image Alt field, Representative Link selector, and Preview selector
while preserving shared media architecture, uploads, validation, publication,
routing, repository, and schema behavior. Stop after before/after screenshots,
the browser-gap matrix, runtime badge evidence, and the hopper payload are
captured. No subsequent UX ticket is authorized by this gate.

## Handoff Update — COMMUNITY-RESTART003

`COMMUNITY-RESTART003 — Modern Media Picker v1 + Hopper Governance Recovery`
is complete for its single authorized local correction. The authenticated
Community DDEV runtime at `https://teachers-net-community3.ddev.site` is the
acceptance authority. The topic composer now exposes one visible `Add Photo`
camera action while retaining chooser activation, paste, drag/drop, preview,
remove, validation, upload, publication, routing, repository, schema, and
shared-media behavior. Browser acceptance was recorded at 1440px with runtime
status `ok`.

The implementation is Community commit
`80878116c05eac550b214079046b180c853415f4` on `COMMUNITY3-ui-working`.
Evidence paths are recorded in
`docs/community-3.0/community-restart003-recovery-report.md`; screenshots are
not hopper artifacts. The authoritative recovery hopper is
`tmp/hopper/tnet-3.0/current` in this site repository. Cycle `260804004439`
archived the prior contents and passed validation. No further correction is
authorized by this gate.
