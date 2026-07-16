# EMP-STRAT003 — Employer Capability Evolution and Adaptive Operations Contract

Status: Contract for capability and presentation governance
Scope: Employer Operations adaptation as a poster gains authorized employers or capabilities

## Adaptive capability model

Personas are descriptive planning models, not account classes. The product
derives available tools from the authenticated WordPress account, active Jobs
employer memberships, employer/job scope, lifecycle, and separately granted
capabilities.

The same account may evolve from one school to several related schools, to
unrelated employers, and later to approved high-volume or feed capabilities.
No onboarding decision permanently locks the account into a persona.

Capability layers:

1. **Identity:** authenticated WordPress account.
2. **Employer authority:** active membership for one or more canonical employers.
3. **Job authority:** permitted actions for jobs within the selected employer
   scope and lifecycle.
4. **Operational capability:** separately granted high-volume, feed, or import
   authority.

Each layer is additive and independently revocable. Feed/import authority never
grants employer membership or public-employer identity.

## Account-evolution rules

- A single-employer member can later receive additional memberships without a
  new account or replacement employer record.
- A district/network relationship may be represented by multiple related
  canonical employers; related grouping is a presentation concern, not a new
  public job model.
- Unrelated client employers remain separately selectable and separately
  authorized.
- A user with one active employer receives a simplified operations experience;
  no selector is required to choose the only employer.
- A user with multiple active employers receives employer selection when a route
  needs context; only active authorized memberships appear.
- Employer context persists through Dashboard, My Jobs, Post, Edit, Review,
  Preview, confirmation, and return paths.
- Capability changes do not rewrite employer identity, job identity, source
  provenance, lifecycle, or historical metrics.

## Single-employer presentation

Dashboard and My Jobs show the selected employer as a visible, read-only
context label or card. The context confirms the employer but does not need an
interactive selector. Available actions remain the ordinary employer-member
actions defined by EMP-AUTH003–005.

The simplified presentation must not expose:

- claims or onboarding as routine operations;
- bulk tools;
- team administration;
- feed/import controls;
- agency or applicant-management features.

## Multi-employer presentation

When more than one active membership exists, the operations shell presents an
employer selector only where needed to establish route context. The selector:

- lists active authorized employers only;
- distinguishes the selected employer clearly;
- preserves the selected employer in route transitions;
- supports related and unrelated employers without changing the job model; and
- blocks invalid or revoked selections without exposing employer data.

An all-authorized-employers My Jobs view is not required for V1. The V1 default
is an explicitly selected employer inventory; cross-employer aggregation would
need a separate authority and capability decision.

## Related versus unrelated employer treatment

- **Related employers:** may be grouped or labeled for navigation convenience
  when the existing membership data supports that relationship. Each employer
  remains a distinct canonical record with distinct scope and actions.
- **Unrelated employers:** remain separate selections with no implied corporate,
  district, or agency relationship.
- Neither treatment creates a pooled job inventory, shared public employer, or
  inherited publishing trust.

## V1 capability boundaries

Supported in V1 through existing membership and route behavior:

- one or multiple employer memberships;
- employer-scoped Dashboard and My Jobs;
- Post/Edit, Review, Preview, submit/review, and authorized direct publish;
- lifecycle actions valid for the selected employer and job status.

Hidden or gated until explicitly granted, and otherwise deferred:

- high-volume bulk create/edit/close/publish;
- team roles, delegated administration, and client subaccounts;
- feed/API subscriber controls;
- agency console or cross-client workflow;
- pooled cross-employer inventory;
- applicant management, ATS, messaging, billing, and analytics beyond current
  bounded metrics.

Source/import identity remains separate from employer identity. An agency,
feed, or importer cannot substitute for the public employer.

## Impact on EMP-DESIGN003

- **Employer selector:** conditional. The single-employer view uses a static
  selected-employer context; the selector appears only when multiple active
  memberships require a choice.
- **Selected-employer context:** retained in both Dashboard and My Jobs, with
  the same visual treatment and clear employer name.
- **Dashboard summaries:** always employer-scoped; they must never aggregate
  unrelated employers in the V1 default view.
- **My Jobs all-employers view:** not part of V1; no new aggregate view is
  authorized.
- **Hidden capabilities:** bulk, team, feed, agency, and pooled tools remain
  absent until separately granted and governed.

### Candidate-impact decision

**Approve EMP-DESIGN003 candidate unchanged.**

The candidate already presents a shared selected-employer context and separate
Dashboard/My Jobs route views. Conditional selector behavior is a bounded state
and permission rule, not a required visual redesign. The candidate does not
introduce onboarding, claim, pooled inventory, or unsupported high-volume tools.

## Verification

- Aligns with the existing employer membership and capability architecture.
- Preserves one canonical employer and job model.
- Adds no unsupported functionality or permanent poster classes.
- `git diff --check` passes.
