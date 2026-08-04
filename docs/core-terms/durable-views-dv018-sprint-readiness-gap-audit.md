# DV-018 — Job Center Sprint Readiness and Authoring Capability Gap Audit

Status: Complete — audit only; remediation not performed
Date: 2026-08-04

## 1. Executive Finding

The certified Durable Views MVP is technically capable of resolving a composed
View and binding a published version to a Jobs form field through service/code
paths. It is **not yet administrator-operable end to end through the browser**.

The immediate Job Center sprint is blocked by two gaps:

1. Durable Views administration does not expose a complete composition editor.
2. The Jobs parallel Durable Views adapter is not wired into the live
   `configured_options_for_field()` rendering path.

The MVP certification should remain unchanged but be qualified: **platform and
service MVP certified; browser authoring and live Job Center cutover not yet
certified**.

## 2. Evidence Inspected

Repositories and commits:

- Profilaxes: `agent/durable-views-dv003-persistence`, `83eebfb`.
- Jobs: `main`, `2f31a93`.
- Root continuity: DV-013/014/015/016 documents and user manual.
- Canonical milestone: `durable-views-platform-foundation-complete`.

Runtime:

- DDEV WordPress runtime loaded the classes during prior certification.
- Canonical review URLs tested by HTTP:
  - `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views`
  - `https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-job-categories`
  - `https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-jobs`
- All three returned WordPress authentication redirects to `wp-login.php`.
- Browser automation could not attach in this session because the browser
  runtime rejected the WSL workspace connection (`sandboxCwd` error).
- No screenshot or authenticated browser acceptance is claimed.

## 3. Administrator Journey Matrix

| Step | Current state | Evidence | Sprint impact |
| --- | --- | --- | --- |
| Open Durable Views | Partially available | `CFM_Views_Admin::register_menu()` | Page exists; browser acceptance pending |
| Create View identity | Available in browser surface | `create_view` form | Works for a draft identity |
| Create/edit draft version | Internal/service path | repository creates drafts; page creates initial draft | No browser editor for an existing draft |
| Select framework | Absent from admin UI | `save_entry` expects posted framework | Blocks safe self-service authoring |
| Browse/search terms | Absent from admin UI | no term picker in `class-cfm-views-admin.php` | Blocks composition |
| Include/exclude terms | Internal path only | repository supports inclusion; admin form does not expose it | Blocks composition |
| Descendant toggle | Internal path only | `include_descendants` exists in repository | Blocks composition |
| Create/manage groups | Internal path only | `save_group()` exists; no admin controls | Blocks grouped output |
| Nested groups | Schema-reserved only | `parent_group_id` schema; save/resolve do not author hierarchy | Not required for first Job Center dimension; later gap |
| Order/labels/metadata | Internal path only | repository fields exist; no controls | Blocks safe presentation control |
| Preview | Service path only | `preview_version()` exists; no page action/view | Blocks review |
| Validate | Lifecycle action only | publish handler calls validation | No visible validation detail |
| Publish | Browser button exists | draft list action | Operable only after composition exists |
| Bind to Jobs | Service/code path only | `bind_field()` exists | No administrator binding UI |
| Verify live Jobs options | Not proven | adapter smoke-tested only | Blocks cutover |
| Rollback/unbind | Service path only | `unbind_field()` and legacy fallback exist | Browser controls absent |

## 4. Technical Capability Matrix

| Capability | Classification | Finding |
| --- | --- | --- |
| Include/exclude | Implemented/service-verified | Resolver applies include/exclude precedence |
| Descendant expansion | Implemented/service-verified | Core Terms closure expansion works |
| Duplicate handling | Implemented/validation | Duplicate scopes invalidate a draft |
| Stable ordering | Implemented/service-verified | Entry order and UUID tie-break are deterministic |
| Groups | Implemented/service-verified | Flat groups resolve; authoring is code-only |
| Nested groups | Schema-reserved/partial | `parent_group_id` exists, but author/save/resolve presentation is incomplete |
| Featured/hidden | Implemented/service fields | Not exposed in browser editor |
| Label overrides/metadata | Implemented/service fields | Not exposed in browser editor |
| Preview | Implemented/service-only | No admin preview action or rendered model display |
| Validation | Implemented/service-verified | Publish rejects invalid versions; admin feedback is weak |
| Immutable publication | Implemented/verified | Draft-from-published workflow preserves published data |
| Clone | Implemented/service-verified | Groups and entries copied into a new draft |
| Retire/restore | Implemented/admin controls | Lifecycle actions exist |
| Service consumer access | Implemented/verified | `CFM_Views_Service` is the platform boundary |
| Fixed-version binding | Implemented/service path | Jobs stores View and version IDs |
| Current-published binding | Not implemented | Jobs binding is fixed-version; policy should be explicit before sprint use |
| Taxonomy evolution | Qualified | Descendant expansion can change with active Core Terms state; published snapshot semantics need a sprint decision |

## 5. Job Center Readiness

The first target remains one View per classification dimension, such as Subject
Area or Grade Level. A composite Job Center View is not recommended.

Jobs has a durable binding on `tnet_jobs_form_fields` and a service adapter, but
the actual `configured_options_for_field()` path still synchronizes and reads
the legacy `form_field_terms` compatibility model. The parallel adapter is not
called by the live authoring/listing UI.

Therefore:

- a real administrator cannot currently create and bind a complete View without
  code, WP-CLI, or direct data intervention;
- live Job Center consumption is not proven;
- legacy fallback remains the safe operational path;
- browser acceptance is missing.

## 6. Authoring Interface Gaps

### Immediate sprint blockers

- draft workspace/editor route;
- framework and Core Terms browser/search;
- include/exclude and descendant controls;
- entry removal and ordering;
- basic group creation/assignment;
- visible validation results;
- preview result;
- administrator-operable Jobs binding;
- live option-generation integration and rollback switch.

### Practical baseline, not required for first proof

- display label, hidden, and featured controls;
- metadata editing;
- clone from the UI;
- clear success/error/empty states;
- contextual guidance and confirmation dialogs.

### Later UX refinement

- drag-and-drop ordering;
- responsive polish;
- advanced search/filtering;
- nested group productivity features;
- bulk term operations.

## 7. Nesting Findings

### Taxonomy nesting

Supported through `include_descendants`. The resolver expands the selected
canonical term through the active Core Terms closure and applies exclusions
after expansion. This is service-verified, not browser-operable. Later Core
Terms additions can affect a View using descendant expansion; fixed snapshot
semantics require an explicit product decision.

### Presentation-group nesting

`parent_group_id` exists in the installed schema. However, the current
authoring method does not set it, cloning does not preserve parent relationships,
and the resolved model does not emit a nested group tree. It is schema-reserved
and incomplete. It is not required for the first single-dimension Job Center
View and should remain outside the sprint unblock.

## 8. Blocking Gap Matrix

| ID | Layer | Severity | Blocks sprint | Smallest remedy |
| --- | --- | --- | --- | --- |
| DV018-A | Authoring | P0 | Yes | Add protected draft editor with term picker and entry controls |
| DV018-B | Authoring | P0 | Yes | Add group/order/preview/validation workflow |
| DV018-C | Jobs | P0 | Yes | Wire bound-field option generation to platform service with fallback |
| DV018-D | Jobs/admin | P0 | Yes | Add administrator binding/unbind control for a published version |
| DV018-E | Verification | P0 | Yes | Authenticated browser evidence across Views, Jobs admin, and listing UI |
| DV018-F | Contract | P1 | Yes | Decide fixed-version versus current-published binding for sprint |
| DV018-G | UX | P1 | No | Improve guidance, labels, empty/error/success states |
| DV018-H | Platform | P2 | No | Complete nested group tree semantics |

## 9. Minimum Completion Sequence

### A. Mandatory Job Center sprint unblock

1. **DV-019 — Protected draft composition workspace.** One browser objective:
   create/edit a draft with framework selection, term browse/search,
   include/exclude, descendant toggle, entry removal, and deterministic order.
2. **DV-020 — Basic groups, preview, and validation feedback.** One browser
   objective: create flat groups, assign entries, preview the resolved model,
   and show actionable validation messages.
3. **DV-021 — Administrator Jobs binding.** One browser objective: bind one
   published View/version to one Jobs field and unbind it safely.
4. **DV-022 — Live Jobs adapter cutover.** One implementation objective: use
   the platform service for bound fields while preserving legacy fallback.
5. **DV-023 — Authenticated browser certification.** One verification objective:
   create, compose, validate, publish, bind, verify live options, retire,
   restore, and confirm rollback.

### B. Practical authoring baseline

- display label and hidden/featured controls;
- clone from admin;
- explicit fixed-version policy;
- audit-readable success/error states;
- manual update and handoff.

### C. UX refinement backlog

- drag/drop ordering;
- nested group authoring;
- advanced term search and bulk actions;
- responsive and accessibility refinement.

### D. Advanced platform capabilities

Inheritance, composition, personalization, subscriptions, analytics, AI,
public URLs, and distributed caching remain deferred.

## 10. Recommendation and Stop Conditions

Keep the certified MVP status unchanged but qualify it as platform/service
complete and browser-authoring/live-consumer incomplete. Do not begin
Community adoption or nested-group work until DV-019 through DV-023 establish a
real administrator-operable Job Center path.

Stop if the required Job Center field cannot be identified, browser
authentication remains unavailable for certification, fixed/current binding
semantics remain undecided, or live Jobs behavior contradicts the service
contract.
