# Profile Screen 6 — Member Context Contract

Status: `HUMAN_QA_PENDING` under
`PROFILE-ONBOARDING-MEMBER-CONTEXT-SCREEN6-001`.

Screen 6 is the optional, explicit member-context step after the frozen Screen
5 Location flow. It supports independent member roles and intents; it does not
assign a mutually exclusive member type.

## Canonical data owner

Profile owns the normalized `{$wpdb->prefix}tnet_profile_member_context`
relation through `TNet_Profile_Member_Context`. Identity owns only Screen 6
routing, its onboarding-pending marker, and form coordination.

Each row retains:

- `user_id`;
- `context_type` and canonical `context_value`;
- `provenance`;
- `created_at` and `updated_at`.

The current explicit provenance is `self_reported`. Future inferred or
behavioral data, if separately authorized, must use a distinct provenance;
they must not be written as self-reported facts or silently overwritten by the
Screen 6 form. No confidence field is stored until a future owner has a
meaningful use for it.

Current domains are deliberately narrow:

- `role`: `teacher`, `administrator`, `education_student`, `retired_teacher`,
  `vendor`, `tutor`, `other`;
- `intent`: `hiring`.

One member may retain any combination, including `teacher` + `administrator`
+ `hiring`. A Post-a-Job continuation remains a route-origin fact and is not
equivalent to a self-reported `hiring` row.

## Query seam

The relation has a unique `(user_id, context_type, context_value, provenance)`
key plus indexed `context_user` and `user_context` access paths. The Profile
owner exposes:

- `for_user()` for all current context for a member;
- `user_ids_for_context()` for an indexed role or intent reverse lookup;
- `user_ids_matching()` for an indexed intersection of multiple contexts with
  optional existing Screen 5 country/region constraints.

Later authorized grade or subject contexts extend the same normalized relation
and query seam through the Profile-v1 canonical Core Terms reference contract
in `docs/profile/member-fact-contract-v1.md`. They are not collected or
modeled by Screen 6.

## Lifecycle and privacy

Verified new accounts receive the Identity-owned Screen 6 pending marker.
Screen 5 Continue and Skip route to `/account/context/` while it is pending.
Screen 6 Continue atomically synchronizes only current self-reported roles and
intents; deselected values are removed. Skip clears only self-reported Screen 6
role/intent rows and completes the step without fabricating a classification.
It cannot remove canonical Profile facts introduced by later authorized work.
A completed member is routed to the ordinary post-onboarding destination and
does not replay Screen 6 during normal Profile edits.

The feature collects no behavioral browse facts, advertising-targeting data,
or inferred personal classification. It collects no grade, subject, school,
employer membership, recruiter entitlement, or later onboarding-fork detail.
Location remains owned by the frozen Screen 5 contract and is neither copied
into nor reinterpreted by this relation.
