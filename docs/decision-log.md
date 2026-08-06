# Decision Log

- Core Terms stabilized before Jobs began.
- Jobs is a separate plugin from Core Terms.
- Core Terms remains folder/repo `profilaxes` for now.
- Do not rename CFM namespaces, DB tables, slugs, URLs, prefixes, or file paths yet.
- Jobs uses custom tables, not WordPress posts, as primary storage.
- Employer is first-class.
- Employer is not a Core Term.
- Classification belongs in Core Terms.
- Job-specific lifecycle belongs in Jobs.
- Jobs may store selected Core Terms IDs in Jobs-owned bridge/configuration tables.
- Jobs must not write to Core Terms.
- Core Terms taxonomy counts may change through legitimate Core Terms maintenance; Jobs must synchronize by stable identifiers/hierarchy rather than assuming a fixed count.
- Promotion and billing must not contaminate the jobs table.
- External apply is acceptable for launch.
- Application Instructions reveal behavior is a Jobs-owned engagement signal, not an internal application workflow.
- Saved Jobs use the Jobs engagement system.
- Job Alerts are required for V1 and should be implemented as a small user-owned alert system, not as a general notification center.
- Communications must use the Jobs communication service.
- The public Jobs browse/search/detail experience is Jobs-owned and follows Design System v1.
- The canonical runtime hero asset is `hero-chalkboard-1200x450.webp`.
- CSV import is admin-controlled and must not auto-publish by default.
- ATS, resumes, candidate search, interviews, offers, hires, notification center,
  maps, commute-time routing, reviews, and commerce are reserved future objects
  unless explicitly reopened. Distance Search has been explicitly reopened for
  V1; automatic geocoding remains controlled readiness work.
- Users may hold multiple Jobs identities; avoid ranked single-role ladders.
- Permissions should be capability-based and employer-scoped.
- Employer-posted and Teachers.Net-curated/imported jobs share one public job
  entity, lifecycle, search engine, presentation, application behavior, and
  expiration behavior.
- Provenance is required internal metadata and does not create a separate public
  scraped-job class.
- Public source and application behavior must be truthful; external applications
  must route to the stored destination without implying Teachers.Net receives
  them.
- Supported typed origins must resolve independently of current job inventory.
- Google Places Autocomplete is not a V1 release blocker.

## Community 3.0 Documentation Alignment Decisions — 2026-08-04

- **Runtime authority gate:** Community browser work must align browser URL,
  DDEV project, mounted plugin tree, authority worktree, branch, commit,
  plugin hash, route, controller, and rendered runtime badge before acceptance.
- **Browser-visible UX authority:** Repository state and runtime availability
  are supporting evidence; authenticated rendered browser behavior is required
  to claim a browser-facing product milestone.
- **Evidence modes:** Normal, Responsive, and Diagnostic evidence are selected
  by ticket risk. Normal work uses one relevant AFTER screenshot and may reuse
  the prior accepted AFTER as BEFORE; full matrices are not the default.
- **Archive-first Community handoff:** The Community project hopper is
  `tmp/hopper/tnet-3.0/current` in the site repository. Every ticket archives
  the current set before work and validates a current-ticket-only payload.
- **Lightweight composer direction:** The Community composer uses a visible
  Add Photo action with paste/drop, automatic representative-link behavior,
  automatic baseline accessibility metadata, and no heavy editor by default.

These decisions are project-specific and do not authorize production changes,
schema migration, delivery, or broad UX implementation.
- A controlled real-job pilot precedes bulk loading.

## DATA001-REV1 — Approved School / Jobsite Architecture

- School/Jobsite visibility is employer-private by default and reusable through employer relationships.
- Trusted employer members manage resources; affiliation requests and organization recovery replace single-user ownership; administrators handle disputes and unsafe merges.
- U.S. address validity is full name plus ZIP or city+state and country US; international validity is full name plus locality and country; street is optional.
- Duplicate handling is Create / Reuse / Relate / Resolve with confidence scoring and no inline merges.
- One primary image is owned by a Jobs media service with compression, limits, provenance, derivatives, and CDN-ready references.
- Every Job uses exactly one Primary Resource as its organizational anchor, one Work Arrangement, optional additional locations, and optional job-specific overrides. Remote, Hybrid, District-wide, and Multi-site are arrangements, not resource types; they do not remove the Primary Resource requirement.
- `full_name` is required identity; `display_name` is optional presentation metadata.
- The adopted architecture is the staged hybrid documented in `docs/job-center/DATA001-school-jobsite-architecture-decision-v1.md`.
- Employer claims require authority verification; recruiter identities are not
  auto-created from imported records.
- Employer Operations is a hybrid authenticated workspace inside the standard
  Teachers.Net shell; personas are descriptive planning models, not permanent
  account classes.
- Employer identity, profile, membership, posting account, and
  source/provenance remain distinct. Claim is contextual acquisition; user-facing
  new-organization language is Add My School / Add Organization.
- Employer creation follows Progressive Completion, and location guidance should
  explain the discovery and Distance Search benefit of City, State, and ZIP
  without unnecessary abandonment-inducing validation.
- **SEARCH-CONTRACT001 — Remote and distance-sort search contract:** Relevance
  and Date may include eligible remote jobs in the normal paginator; Distance
  excludes remote jobs without assigning synthetic distance, visibly discloses
  the exclusion, and offers a one-click remote-only alternative. Basic Search
  remains low-friction; expanded search exposes Work Location. The full
  contract is canonical in `docs/job-center/job-finder-search-contract-v1.md`.
- **JC052-DESIGN001 — Employer Workspace flow authority:** My Jobs, School /
  Job Site management, Add, Edit, and Manage share one employer-authorized
  shell; Organization Location, Job Work Location, and public Search behavior
  remain separate concepts; Physical U.S. ZIP entry auto-fills City/State;
  imagery is optional with a Teachers.Net default; and progressive disclosure
  governs lower-frequency fields. The full flow and unresolved approval
  questions are canonical in
  `docs/job-center/employer-workspace-flow-authority-v1.md`.

## Architectural Decisions

### ADR001 — JC-030 Implementation Strategy

**Status:** Accepted
**Date:** 2026-07-15

ADR001 codifies the implementation strategy derived from the Responsive
Authority Program. It was adopted after the responsive-authority work and must
not be represented as though it governed earlier implementation. It governs
current and future JC-030 work: implement the approved authority as a new page
composition while reusing the existing route, services, repositories,
business logic, authentication, engagement behavior, formatting helpers,
responsive primitives, and advertisement primitives. The legacy Job Detail
page is not the implementation target; replace page composition, not
underlying behavior, and avoid broad architectural replacement when bounded
composition work is sufficient.

## Repository Ownership

- Root `teachers-net-site` repository: governance, roadmap, architectural
  decisions, audits, approved visual authorities, and continuity documents.
- Nested `tnet-jobs` repository: Job Center implementation.

## Source-of-Truth Precedence

1. Current explicit Engineering Director instruction
2. Verified Git state in the correct repository
3. Reconciled Project Cursor
4. Reconciled Engineering Handoff
5. Approved product, UX, design, and responsive authorities
6. Visual Manifest and Approved Library
7. UX Atlas
8. Historical roadmap and planning documents
9. Conversation summaries
10. Model memory

### DV-UX008 / DV-UX009 — Finalized Views V1 Workflow Supersession

**Status:** Accepted  
**Date:** 2026-08-06

The historical DV-UX008 roadmap placeholder is superseded. Following
DV-ARCH002, DV-UXAUD002, DV-UX006, DV-UX006A, DV-UX007, and DV-UX007A, the
finalized Views V1 workflow is the active product direction. DV-UX009 is the
next implementation ticket, and its ChatGPT-supplied specification is the
required implementation authority. No implementation may begin from the
historical DV-UX008 description.

### DV-SPEC001 — Canonical Views V1 Product Specification

**Status:** Accepted  
**Date:** 2026-08-06

`docs/core-terms/durable-views-v1-product-specification.md` is the canonical
V1 product authority for DV-UX009. It freezes the approved workflow and
interaction states: read-only Library, blue pending shuttle, muted represented
terms, strike-through pending removal, draft/published lifecycle, dialogs,
and explicitly deferred capabilities. Earlier conflicting or more expansive
UX descriptions are subordinate to this specification; unresolved
contradictions must be reported rather than invented away.

### DV-SPEC002 — Finalized V1 Interaction Rules

**Status:** Accepted  
**Date:** 2026-08-06

DV-SPEC001 was finalized by DV-SPEC002. Explicit checkbox actions are distinct
from inherited visual consequences: blue ancestor context on the left and
strike-through descendant removal on the right. Contextual shuttle/removal
toolbars, top-level confirmation, draft-only publication, and the deferred V1
feature boundary are now authoritative for DV-UX009.

### DV-ARCH003 — Renderer Blocking Diagnostic

**Status:** Accepted  
**Date:** 2026-08-06

DV-UX009 is blocked by the current renderer/controller seam. Interleaved
per-entry and group forms prevent safe aggregate removal, and the controller
lacks bounded draft lifecycle actions. This is a renderer/service seam
problem, not a schema, resolver, Core Terms, or Jobs authority problem.
DV-UX009 remains open; DV-UX010 is not authorized.

### DV-UX009 Resume 2 — New Persistence Blocker

**Status:** Blocking  
**Date:** 2026-08-06

The authorized renderer continuation confirmed that Revert to Saved Draft and
Delete Draft cannot be implemented from the current contract. Entry mutations
persist immediately, with no saved-draft snapshot or draft deletion seam.
This is distinct from the DV-ARCH003 renderer blocker and requires a separate
explicit persistence/lifecycle decision. DV-UX009 remains open.

### DV-DEC001 — Approve Autosaved Draft Lifecycle (V1)

**Status:** Accepted
**Date:** 2026-08-06

V1 approves durable autosave for the single active View draft as a recovery
mechanism. Autosave must be atomic and must not publish, mutate published
versions, duplicate Core Terms, or transfer composition authority to Jobs.
Revert restores the latest durable autosave; Delete Draft removes only the
active draft after confirmation. The repository must provide an explicit
snapshot/revision and concurrency contract; the current direct-write model is
not sufficient. This decision authorizes a future implementation ticket only;
it does not itself authorize schema, application, browser, or production
changes. Full contract: `docs/core-terms/durable-views-dv-dec001-autosaved-draft-lifecycle-v1.md`.

### DV-UX009A — Autosaved Draft Lifecycle Implementation

**Status:** Implemented; browser verification pending
**Date:** 2026-08-06

Profilaxes implements immediate draft persistence state, one-active-draft
enforcement, protected Delete Draft, and the Current View removal toolbar.
Published versions, Core Terms, Jobs integration, and resolver boundaries were
preserved. Commits `d5aa0b1` and `f13e2f5` are pushed. Browser certification is
required before closing DV-UX009; Browser Verification Continuation Pass 2
completed that acceptance. DV-UX009A and DV-UX009 are complete; DV-UX010
remains unauthorized.

### DV-UX009C — Restore Core Terms Tree Expansion

**Status:** Complete
**Date:** 2026-08-06

An unguarded event binding for an omitted optional selection control prevented
the Core Terms Library expand handler from initializing. Guarding that binding
restored independent branch expansion without changing selection, persistence,
resolver, Jobs, or lifecycle behavior. Browser verification passed for Grade
Level, Location, and Subject Area.
