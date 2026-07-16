# EMP-AUTH006 — Employer V1 Authority Completion Audit

Status: INCOMPLETE
Ticket: EMP-AUTH006
Scope: Minimum Employer V1 visual-authority set only

This audit reconciles Employer UX V1, the EMP-AUTH002–005 contracts, the UX
Atlas, Visual Manifest, roadmap, Project Cursor, and Engineering Handoff. It
does not authorize implementation or add new employer requirements.

## Completed authority areas

The following areas have complete contracts and bounded state coverage:

- **Access and authority:** Claim Existing Employer, Request New Employer,
  authentication return, pending, returned/not-approved, approved membership,
  revoked access, and employer-context switching are defined by EMP-AUTH002.
- **Dashboard and My Jobs:** shared employer shell, selected-employer context,
  summary/inventory distinction, filters, pagination, empty/no-access states,
  statuses, and allowed actions are defined by EMP-AUTH003.
- **Authoring:** shared Create/Edit composition, field grouping, validation,
  Review, Preview, submit/publish outcomes, and permission/error states are
  defined by EMP-AUTH004.
- **Lifecycle:** Draft, Awaiting Review, Published — Live, Published — Needs
  Attention, Closed, Expired, Archived, mutation consequences, and retained
  history are defined by EMP-AUTH005.

These are contract-complete, not visual-authority-complete.

## Unresolved V1 authority gaps

The Visual Manifest and UX Atlas still record the required employer entries as
Placeholder with no governed visual artifacts:

- JC-050 Employer Dashboard
- JC-051 Employer My Jobs
- JC-052 Employer Wizard / shared authoring
- JC-053 Employer Claim / Request New Employer
- JC-054 Employer Review
- JC-055 Employer Preview

Therefore the minimum Employer V1 visual-authority set is not complete. The
contracts define the required inheritance and state economy, but no approved
render has yet converted those contracts into visual authority.

## Inherited states

- Job Center shell, navbar, footer, typography, spacing, controls, drawer, and
  responsive layout language inherit from approved shared authorities.
- Login/registration inherits the shared account shell with localized employer
  return treatment.
- Claim and Request share one access/authority composition while retaining
  distinct labels and outcomes.
- Single- and multi-employer context share one selector/context pattern.
- Create and Edit share one authoring authority family.
- Review and Preview share the authoring family but remain distinct states.
- Lifecycle conditions remain My Jobs state variants rather than independent
  screens.

## V1.1 deferrals

The contracts explicitly defer metrics, rich employer-profile administration,
new roles, advanced archive/history presentation, bulk actions, analytics,
notifications, exports, moderation/admin compositions, and other Atlas
placeholders outside the minimum employer journey. JC-056 Employer Metrics may
remain deferred without blocking this audit.

## Coverage checks

| Check | Result | Evidence |
|---|---|---|
| Access and authority coverage | Contracted; not visually approved | EMP-AUTH002; JC-053 remains Placeholder |
| Dashboard and My Jobs coverage | Contracted; not visually approved | EMP-AUTH003; JC-050/JC-051 remain Placeholder |
| Authoring coverage | Contracted; not visually approved | EMP-AUTH004; JC-052 remains Placeholder |
| Lifecycle coverage | Contracted as My Jobs variants | EMP-AUTH005; no separate lifecycle screen required |
| Required states classified | PASS | Approved-authority, inheritance, or explicit deferral is stated in the contracts |
| No unresolved V1 employer placeholder | FAIL | JC-050–JC-055 remain Placeholder in Atlas/Manifest |
| Responsive inheritance explicit | PASS | EMP-AUTH002–005 define shared responsive inheritance |
| Dashboard summarizes / My Jobs manages | PASS | EMP-AUTH003 and Employer UX V1 |
| Review distinct from Preview | PASS | EMP-AUTH004 and Employer UX V1 |
| Claim/Request do not imply authority | PASS | EMP-AUTH002 and Canonical V1 Contract |

## Status and next ticket

**Status: INCOMPLETE.** Contracts and inheritance decisions are complete, but
the minimum visual authorities are not approved and the corresponding Atlas /
Manifest entries remain placeholders.

Recommended single next ticket:

**EMP-AUTH007 — Produce and Approve Minimum Employer V1 Authority Set**

That ticket should render and approve the bounded authority set defined by
EMP-AUTH002–005, using the documented state-sheet economy, then reconcile only
JC-050–JC-055. It should not expand into metrics, moderation, admin, or other
deferred placeholders.

Verification: `git diff --check` passes.
