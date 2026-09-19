# Teachers.Net Profile Avatar Architecture Contract

Status: Canonical resolver contract, converged by
`PROFILE-AVATAR-RESOLVER-CONVERGENCE001`; authenticated native Community and
Jobs evidence is complete, with Engineering Director HUMAN_QA pending. This
contract authorizes only the bounded first-party user-avatar capability; it
does not advance broader Profile work.

## Ownership

- Profile is the single first-party owner of avatar representation selection:
  upload/change/remove, authorization, selected-avatar reference, fallback
  semantics, and the consumer resolver.
- The interim identity key is the existing WordPress user identity. No parallel
  identity model is introduced.
- Avatar bytes use the existing WordPress media/attachment system. Profile owns
  the selected attachment reference on the user-owned Profile seam; consumers
  do not duplicate image bytes or canonical URLs.
- Job Center, Notifications, Community, and Shared Shell may render the
  resolved avatar but must not select a competing source or own upload,
  persistence, removal, or media authorization.

## Consumer contract

Profile exposes one canonical resolver for a user and requested display size:

`resolve_avatar( user_id, size ) -> { url, source, is_custom }`

The resolver owns attachment sizing/output and returns the preferred current
avatar without exposing consumer storage details.

For existing WordPress `get_avatar()` consumers, Profile also owns the
`pre_get_avatar_data` integration. Recognized WordPress users therefore receive
the Profile-selected representation through the standard WordPress rendering
primitive without each consumer adding source-selection logic. Shared Shell is
render-only: it receives a resolved URL/source and does not resolve an avatar.

## Resolution and fallback

Resolution order:

Implemented order:

1. the user's first-party uploaded avatar attachment selected by Profile;
2. the user's selected portrait-bank-v1 ID, resolved from the frozen Profile-
   owned manifest;
3. an authorized legacy BuddyPress custom avatar, only when BuddyPress is
   active and exposes one for that user;
4. the existing WordPress/Gravatar resolution, including its deterministic
   default behavior.

Legacy BuddyPress remains a compatibility input behind Profile, never a
consumer-side override. A Profile selection always wins. BuddyPress is inactive
in local DDEV and no migration is required for that state.

The frozen portrait bank is a Profile-owned runtime input for the authorized
onboarding Screen 3 journey. Its retrieval labels are transient chooser
filters only; Profile persists only the selected opaque portrait ID and never
stores Generation or Women/Men choices as member demographics. Removed or
missing bank entries fail closed to the next fallback.

## Component-set-v1 review candidate and disposition

`PROFILE-AVATAR-COMPONENT-SET001` introduces a review-only Profile-owned,
server-composed SVG component set. Its versioned representations have opaque
seed identities and no raw WordPress user ID in their public URL. The candidate
generator is available only through the administrator review surface at
`/profile/avatar-components/`; the SVG renderer lives at
`/profile/avatar-component.svg/`.

This candidate does not participate in `resolve_avatar()`, stores no untouched
default bitmap, and does not persist or infer demographic data. Component
source, rights, and review limits are recorded in
`docs/profile/avatar-component-set-v1-provenance.md`.

Engineering Director HUMAN_QA rejected this fully procedural artwork as a
production direction: its issue is art quality/coherence, not the technical
capacity for combinations. It remains a diagnostic control and is not a basis
for further component polish or activation. `PROFILE-AVATAR-ART-STRATEGY001`
recommends a future separately authorized art set of curated complete portraits.
That recommendation changes neither this resolver contract nor its required
authorization boundary. `PROFILE-AVATAR-ART-CURATION-PILOT001` is the bounded,
development-only visual review step: its 64 generated finished-portrait
candidates, browser-local review state, and export/provenance artifacts live
outside product source under `tmp/profile-avatar-art-curation-pilot001/`.
They are not resolver inputs, selected-avatar states, runtime assets, or a
production-rights assertion. A subsequent objective and rights decision would
be required before any candidate can affect this contract.

Removing a custom avatar clears the Profile-owned selected attachment reference
and returns the resolver to the next fallback. Missing, invalid, unauthorized,
or unavailable attachments fail closed to fallback.

## Implementation and QA boundary

`PROFILE-AVATAR001` may implement the bounded authenticated journey using the
existing WordPress media facilities, with type, size, dimension, and safe-output
validation. The authorized Screen 3 extension also permits the selected opaque
portrait-bank-v1 ID in this same Profile owner, without a parallel avatar store.
It must not add employer, School/Jobsite, Job Center, Gravatar configuration,
social-profile, or unrelated Profile schema behavior.

The implementation must establish its authenticated Profile route/control,
nonce/capability ownership, persistence/readback, replacement/removal behavior,
and consumer-level resolver/read test. Native QA must prove fallback, valid
upload, replacement, safe rejection, removal, persistence, and user isolation.
Job Center integration is a later consumer ticket.

This document is the canonical Profile avatar architecture owner. The Profile
Cursor and Engineering Handoff point here and must not duplicate or fork it.
