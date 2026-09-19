# Profile Screen 4 — Public Identity Contract

Status: implemented for HUMAN_QA under
`PROFILE-ONBOARDING-PUBLIC-IDENTITY-SCREEN4-CONVERGENCE001`.

Screen 4 collects only a member's changeable Display Name after the accepted
photo/avatar onboarding step. The WordPress user ID remains the canonical
system identity. `user_login` remains unique, permanent, and is rendered as
the member's persistent `@username` handle. `display_name` is ordinary
member-facing presentation text; it is never a relational key and has no
availability lookup or uniqueness constraint.

## Minimum Display Name policy

- required after trimming;
- 2–50 characters after trimming;
- must contain at least one Unicode letter or number;
- Unicode letters, combining marks, numbers, spaces, and basic punctuation
  (`. , ' ’ ( ) & -`) are accepted;
- control characters, line breaks, markup, invisible-format abuse, and other
  unsupported characters are rejected;
- repeated whitespace is normalized before persistence;
- server validation in `TNet_Identity_Policy` is authoritative;
  browser feedback mirrors it only for immediate UX.

`TNet_Identity_Policy` also owns permanent username validation/reservation for
all signup persistence. Its categorized platform/system, generic namespace,
automation/AI, and staff reservation data remains server-only. See
`docs/profile/identity-policy-contract.md` for the normalization and scope
contract.

The initial field value comes from the permanent username without mutating the
user record at render time. Explicit Continue persists the value to WordPress's
canonical `display_name` field, clears only the Screen 4 pending marker, and
continues to the existing post-onboarding destination. No location, role,
employer, profile-detail, mention, notification, or relationship behavior is
defined by this contract.
