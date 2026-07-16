# Employer Access and Authority Contract v1

Status: Contract for visual-authority work
Ticket: EMP-AUTH002
Scope: Employer access, organization authority, and employer context only

This contract defines the minimum V1 visual authority for the employer access
and authority boundary. It does not define Dashboard, My Jobs, authoring,
lifecycle, metrics, moderation, or administration screens.

## 1. Required screen/state matrix

| Area | Required states | Authority treatment |
|---|---|---|
| Employer intent and authentication return | Logged-out employer intent; login/registration return to the requested employer task | Inherit the shared account shell; add one localized employer-return treatment where context must be explained |
| Organization path | Claim Existing Employer; Request New Employer | One shared access/authority shell with distinct labels, evidence prompts, and outcomes |
| Pending review | Claim submitted; new-employer request submitted; awaiting authority review | Shared pending treatment; no Dashboard, My Jobs, or authoring access is implied |
| Returned / not approved | More Information Needed; Not Approved; returned request | Shared resolution treatment with next-step guidance and no active access |
| Approved membership | Authority approved; active employer membership; first entry to selected employer | Shared approved-context treatment leading to Dashboard; approval and membership are explicit |
| Revoked access | Access Revoked; previously available employer context no longer authorized | Shared blocked-context treatment; employer/job identity and history remain intact |
| Employer context | Single active employer; multiple active employers; selected-employer confirmation | One reusable context pattern carried into Dashboard, My Jobs, and authoring |
| Switching | Multi-employer selector containing only active authorized memberships; selection confirmation | State variant of the context pattern, not a separate product composition |

## 2. Shared versus distinct compositions

### Shared base authority

Claim and Request New Employer use one access/authority composition containing:

- shared Job Center shell;
- clear employer-access heading and explanation;
- authenticated-account requirement where applicable;
- organization identity/evidence area;
- primary submit/request action;
- status notice region;
- safe return/navigation action;
- employer-context treatment when an active membership exists.

### Required localized distinctions

- **Claim Existing Employer** identifies and targets an existing canonical
  employer and must not suggest creation of a replacement employer.
- **Request New Employer** describes a new-organization review and must not
  imply that a pending request creates immediate posting access.
- Pending, returned, approved, revoked, and switching states use the same shell
  and notice/action patterns but require state-specific copy and action rules.
- Login/registration remains the shared account experience; only the return
  destination and employer-intent explanation are localized.

## 3. Content hierarchy and actions

The base authority follows this order:

1. Job Center shell and account state.
2. Employer access heading.
3. Plain-language explanation of the current authority state.
4. Organization path or selected employer identity.
5. Required evidence or context fields.
6. Status, permission, or pending notice.
7. Primary action appropriate to the state.
8. Secondary return, cancel, login, or navigation action.

State actions:

| State | Primary action | Secondary treatment |
|---|---|---|
| Logged out | Log in / Register and return | Preserve the intended employer task |
| Claim | Submit Claim for Review | Cancel/return without changing employer identity |
| Request New Employer | Submit Request | Cancel/return to the organization path |
| Pending | None that grants access | Explain review status and safe navigation |
| More Information Needed | Provide requested information | Preserve the pending context |
| Not Approved | None that grants access | Explain the next permitted contact/request path |
| Approved | Continue to Dashboard | Show selected employer and membership context |
| Revoked | None that grants access | Explain that access ended; retain truthful identity/history context |
| Multi-employer | Select an active employer | Do not show unauthorized memberships or imply switching authority |

Approval, membership, and publishing trust must not be conflated. A submitted
claim or request never grants employer access.

## 4. Responsive inheritance and employer-specific deltas

- Inherit the approved Job Center shell, typography, spacing rhythm, controls,
  notices, footer, drawer, and responsive layout classes.
- Preserve the shared account shell for login and registration.
- Employer-specific work is limited to authority explanation, organization
  path distinction, status messaging, and selected-employer context.
- At narrow widths, preserve reading width and touch-target requirements;
  stack fields and actions using the shared responsive language.
- Do not create an employer-specific breakpoint, alternate drawer, or separate
  mobile authority model.

## 5. Error, empty, pending, approved, and revoked treatments

- **Error:** identify the failed field or request, preserve entered information
  where supported by the existing contract, and provide a recoverable action.
- **Empty/no match:** state that no eligible canonical employer was found and
  provide the governed Request New Employer path; do not create a duplicate.
- **Pending:** clearly state that review is incomplete and that no employer
  access or posting authority exists yet.
- **Approved:** identify the canonical employer, active membership, and next
  destination; do not imply broader trust than the approved membership grants.
- **Returned/not approved:** explain the outcome and permitted next step without
  presenting the user as an authorized employer.
- **Revoked:** block employer work, explain that authorization ended, and keep
  employer/job identity and history truthful.
- **Switching:** show only active authorized employers; preserve the selected
  context after confirmation and on subsequent Dashboard, My Jobs, and
  authoring navigation.

## 6. Required renders for approval

Maximum: two rendering passes.

1. **Base authority render:** one representative Claim/Request access shell,
   including logged-out return and selected-employer context treatment.
2. **Bounded state pass:** one composite state treatment covering pending,
   returned/not-approved, approved, revoked, and multi-employer switching.

The renders may use annotated state variants or a compact state sheet. Separate
rasters for every route or status are not required.

## 7. Explicit V1.1 deferrals

- Employer Dashboard, My Jobs, authoring, Review, Preview, lifecycle, metrics,
  moderation, and administration authorities.
- Rich employer-profile editing beyond supported identity/context facts.
- Additional employer roles or role-specific authority presentations.
- Claim-history and switching-history views beyond the required current status.
- Unresolved archive, close-reason, duplicate-warning, internationalization,
  and advanced metrics presentation questions.
- Any employer Atlas placeholder not required to complete the access/authority
  boundary.

## 8. Acceptance criteria

EMP-AUTH002 visual authority is complete when:

- Claim and Request paths share one coherent authority shell while retaining
  truthful labels and outcomes;
- logged-out return, pending, returned/not-approved, approved, revoked, and
  multi-employer states are covered by the authority or explicit inheritance;
- approval is never implied before active membership is granted;
- selected employer context is explicit and persists into Dashboard, My Jobs,
  and authoring;
- unauthorized or revoked users cannot be visually presented as active members;
- empty, error, and recovery states do not create duplicate employers or
  unsupported permissions;
- the two-render maximum is respected and minor polish is recorded as an
  implementation note;
- the contract introduces no route, permission, or employer functionality;
- `git diff --check` passes.
