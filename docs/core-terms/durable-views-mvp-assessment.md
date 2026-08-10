# Teachers.Net Durable Views System

## MVP Assessment and Initial Engineering Work Plan

Status: Superseded planning authority — standalone MVP accepted by DV-ACCEPT002
Date: 2026-08-10
Workstream: Durable Views / Core Terms platform

> This document records the original assessment and work plan. Its historical
> gap statements describe the pre-implementation state and must not override
> the current Project Cursor, Engineering Handoff, roadmap, or DV-ACCEPT002
> closeout.

## 1. Mission and governing authority

The Durable Views System is a shared Teachers.Net platform service. It is not
a Job Center-specific navigation feature and it is not a replacement for Core
Terms. Core Terms remains the canonical authority for what exists. Views define
what an audience or consumer sees and how canonical terms are presented.

The supplied authority hierarchy is:

1. Level 1 Platform Authority and addendum
2. Level 2 Platform Engineering Contract and addendum
3. Level 3 Implementation and Adoption Roadmap and addendum
4. Level 4 Platform Execution Guide and addendum
5. Views supplemental documentation
6. Supporting ChatGPT transcripts
7. Implementation ideas

Where current repository documentation supplies operational facts, verified
repository state and the active Core Terms continuity documents govern those
facts. The supplied Views authority governs the new platform capability.

## 2. Current understanding of the platform

The platform boundary is:

    Core Terms -> Durable Views -> Resolved Presentation Model -> Consumers

Core Terms currently provides the relevant foundation: a hierarchical term
tree, stable term UUIDs, compiled hierarchy lookups, term lookup and traversal,
and documented public integration surfaces. Consumer plugins reference Core
Terms through public APIs and stable UUIDs; they do not write to Core Terms
internals or duplicate taxonomy management.

A View must become a first-class, platform-owned, durable object. It references
canonical Core Term UUIDs and stores presentation composition, not copied
taxonomy. The initial View model must be capable of growing toward stable
identity, independent versioning, draft/publish lifecycle, preview, retirement,
consumer binding, inheritance, composition, subscriptions, analytics, and
public references without requiring a replacement architecture.

Consumers request a View and receive a complete resolved presentation model.
They must not reconstruct inclusion, exclusion, ordering, grouping, or other
View composition locally.

## 3. Current-state and gap analysis

### Existing capability

- Core Terms has a canonical hierarchical taxonomy and stable term UUIDs.
- Core Terms exposes public lookup, hierarchy traversal, UUID resolution, and
  compiled-term access.
- Core Terms has an established administrative tree workbench.
- Core Terms has Meta-Groups that group terms for audience/user resolution.
- Jobs already consumes Core Terms UUIDs for its own classification data and
  has an integration adapter/provider boundary.
- Jobs owns job records, workflows, authorization, and consumer presentation.

### Missing Views capability

- No durable View identity and View-owned persistence model.
- No separate View Version model with immutable published snapshots.
- No View lifecycle of draft, review, published, deprecated, retired, and
  restore.
- No deterministic View composition/resolution service.
- No resolved presentation-model contract for consumers.
- No View validation for stale, missing, duplicate, or invalid references.
- No View administration for create, edit, preview, publish, clone, retire,
  or restore.
- No View consumer API or Job Center View binding.
- No version-aware cache or invalidation policy.
- No migration or rollback path from current Job Center term-selection flows.

### Boundary risk

Existing Meta-Groups must not be renamed into Views or used as a hidden
replacement for the View system. They serve a different documented purpose:
audience/user resolution. A future View may consume or coexist with related
grouping concepts, but the View contract requires independent durable identity,
presentation composition, versioning, and consumer resolution.

## 4. Minimum viable implementation for Job Center

The MVP is the smallest coherent platform increment that permits Job Center to
select and consume a published View of canonical Core Terms.

### Included in MVP

1. **Canonical View object**
   - immutable stable View ID;
   - name, description, owner, visibility, status, timestamps;
   - explicit schema version and metadata payload;
   - no copied taxonomy.

2. **View Version object**
   - independent version identity and lineage;
   - draft and published states;
   - immutable published versions;
   - publication pointer and rollback-safe historical versions.

3. **Term-entry composition**
   - Core Terms UUID references only;
   - inclusion and exclusion;
   - deterministic ordering;
   - presentation groups;
   - optional display label and presentation metadata;
   - provenance sufficient to explain resolution.

4. **Deterministic resolution**
   - resolve one published View version into an ordered presentation model;
   - validate referenced terms against Core Terms;
   - de-duplicate entries deterministically;
   - return warnings and validation state for stale or missing references;
   - keep taxonomy semantics outside the View service.

5. **Minimal administration**
   - create a View;
   - edit a draft version;
   - select canonical terms from the existing Core Terms tree/API;
   - order and group entries;
   - preview a resolved draft;
   - publish, clone, retire, and restore through capability-protected actions.

6. **Consumer contract**
   - a documented internal/public service boundary for get, resolve, preview,
     list, validate, and publish operations as appropriate;
   - a resolved presentation model that Job Center can consume without
     reconstructing taxonomy.

7. **Job Center pilot binding**
   - one controlled Job Center configuration path consumes one published View;
   - Job Center stores a View/version reference in Jobs-owned configuration;
   - existing Jobs authorization and data ownership remain unchanged;
   - rollback to the existing selection path remains possible until acceptance.

8. **Verification and operational documentation**
   - unit/integration coverage for lifecycle, resolution, invalid references,
     publication immutability, and rollback;
   - authority traceability for each shipped capability;
   - migration, failure, and handoff documentation.

### Deferred but architecturally preserved

- inherited Views;
- multiple View composition;
- personalized Views;
- subscriber policy objects;
- subscriptions and notifications;
- analytics dimensions;
- AI-generated suggestions;
- public View URLs and bookmarks;
- full cross-product adoption;
- advanced cache invalidation and distributed delivery.

These are deferred because Job Center does not require them to prove the
initial platform contract. The schema, version lineage, provenance, stable
identity, service boundary, and resolution design must not prevent them.

### Explicitly out of scope

- changing Core Terms taxonomy semantics;
- creating a Job Center-owned taxonomy or View implementation;
- using Views as permissions, search, recommendation, or identity systems;
- replacing Jobs authorization or job lifecycle behavior;
- bulk migration of all Teachers.Net products;
- renaming `profilaxes`, `CFM`, `cfm_`, tables, routes, slugs, or namespaces;
- redesigning existing Core Terms administration beyond the bounded View entry
  point required by the MVP.

## 5. Future-evolution proof

The MVP preserves future evolution through these invariants:

- Core Terms remains the only taxonomy authority.
- Views reference stable canonical IDs rather than labels, slugs, or copied
  hierarchy data.
- View identity and View Version identity are separate, allowing stable public
  references while versions evolve.
- Published versions are immutable, allowing historical rendering, rollback,
  consumer pinning, and intentional migration.
- Resolution is centralized, so inheritance and composition can later extend
  one service rather than requiring consumer rewrites.
- Groups, ordering, metadata, and provenance are modeled as presentation
  concerns rather than taxonomy mutations.
- Job Center stores a consumer reference, not a duplicate View composition.
- Permissions, search, recommendations, analytics, and notifications remain
  separate consumers and services.
- Warnings and validation state permit taxonomy evolution without silently
  changing the meaning of a published View.

## 6. MVP traceability matrix

| MVP capability | Authority source | Immediate Job Center need | Future platform benefit |
| --- | --- | --- | --- |
| Stable View identity | Level 1, Level 1 addendum | Select a named View reliably | URLs, bookmarks, subscriptions, cross-product references |
| Independent View versions | Level 2, Level 2 addendum | Publish a controlled Job Center configuration | History, rollback, consumer migration, taxonomy resilience |
| UUID-only term references | Level 1, Level 2, Core Terms integration contract | Use canonical Job Center vocabulary | Prevents taxonomy duplication and label drift |
| Include/exclude/order/group entries | Level 2, supplemental documentation | Define the employer-facing picker experience | Reusable presentation across products |
| Deterministic resolver | Level 2 addendum | Give Job Center one complete model | Centralized future inheritance/composition behavior |
| Draft/preview/publish lifecycle | Level 2, Level 3, Level 4 | Safely create the first published View | Governance, review, rollback, staged adoption |
| Validation and warnings | Level 2 addendum, Level 4 | Detect stale or invalid Core Term references | Safe taxonomy evolution and operations |
| Minimal View administration | Level 2, Level 3 | Allow administrators to create the Job Center View | Platform-owned experience design |
| Job Center consumer binding | Level 1, Level 3 | Unblock the first production consumer | Repeatable consumer certification and migration |
| Rollback and migration path | Level 3, Level 4 | Avoid breaking current Job Center behavior | Safe adoption by Community and later products |

## 7. Phased implementation roadmap

### Phase 0 — Assessment and contract

Approve this assessment, freeze MVP boundaries, confirm the Job Center entry
point, and record unresolved authority questions before schema work.

### Phase 1 — Platform foundation

Implement canonical schemas, persistence, stable IDs, version lineage, audit
records, lifecycle state, and repository/service boundaries inside Core Terms.

### Phase 2 — Resolution and validation

Implement deterministic resolution, Core Terms UUID lookup, warnings,
validation, published-version immutability, and failure behavior.

### Phase 3 — Administration

Add the smallest protected admin workflow that creates and edits draft Views,
selects canonical terms, orders/groups entries, previews, publishes, clones,
retires, and restores.

### Phase 4 — Job Center pilot

Bind one Job Center configuration path to a published View through the consumer
contract. Run parallel operation, verify output against the current behavior,
and retain rollback until acceptance.

### Phase 5 — Certification and handoff

Document the consumer contract, migration result, unresolved risks, authority
compliance, and next adoption tickets. Do not begin Community or broader
consumer migration until the MVP is explicitly accepted.

## 8. Principal architectural risks

1. **Meta-Group conflation:** treating existing Meta-Groups as Views would
   collapse distinct platform concepts and make future evolution harder.
2. **Label-based references:** storing labels or slugs instead of canonical
   UUIDs would break durability across taxonomy edits.
3. **Mutable published state:** editing a published object in place would
   undermine rollback, historical rendering, and consumer stability.
4. **Consumer-side assembly:** allowing Jobs to assemble the model would create
   duplicated presentation logic and block later consumers.
5. **Product ownership:** placing View persistence or lifecycle inside Jobs
   would violate platform ownership and require a later extraction.
6. **Underspecified resolution:** leaving precedence, duplicates, stale terms,
   and ordering implicit would cause consumer-specific interpretations.
7. **Scope expansion:** implementing inheritance, personalization, or broad
   migration before the first consumer is proven would delay the required MVP.
8. **Dirty repository boundary:** the current checkout contains extensive
   unrelated worktree changes; only explicitly created Views artifacts may be
   staged for this workstream.

## 9. Initial engineering ticket sequence

The first release is expected to require approximately 12–15 tickets:

1. Approve and baseline this MVP assessment and authority matrix.
2. Complete the bounded repository/runtime contract audit and confirm the Job
   Center integration seam.
3. Define canonical View, View Version, entry, group, metadata, and audit
   schemas.
4. Implement View persistence and repository boundaries.
5. Implement lifecycle, publication pointers, immutability, and rollback.
6. Implement Core Terms UUID reference validation and deterministic resolver.
7. Implement resolved presentation-model contract and failure behavior.
8. Implement minimal protected View administration.
9. Implement draft preview, clone, retire, and restore behavior.
10. Implement the consumer API/service boundary.
11. Bind the first Job Center configuration path to one published View.
12. Run parallel migration and end-to-end verification.
13. Perform authority/compliance and regression review.
14. Complete documentation, certification, and engineering handoff.

Ticket boundaries remain one objective per ticket. Each ticket must state its
authority, scope, exclusions, verification, Git requirements, and stop
boundary. No implementation ticket should silently widen the MVP.

## 10. Approval boundary

This document is an assessment and implementation contract proposal. No Views
schema, production data, Job Center binding, or plugin code should be changed
until the MVP boundaries and ticket sequence are approved.

Upon approval, the next ticket is the bounded repository/runtime contract audit
and Job Center integration-seam confirmation. That ticket should produce the
final implementation seam and any required authority questions before schema
implementation begins.
