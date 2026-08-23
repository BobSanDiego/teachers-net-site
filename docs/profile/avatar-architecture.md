# Teachers.Net Profile Avatar Architecture Contract

Status: Accepted architecture boundary for `PROFILE-AVATAR001`.
Project phase: Planning. This contract authorizes only the bounded first-party
user-avatar capability; it does not advance the broader Profile project phase.

## Ownership

- Profile owns avatar upload/change/remove, authorization, the selected-avatar
  reference, fallback semantics, and the consumer resolver.
- The interim identity key is the existing WordPress user identity. No parallel
  identity model is introduced.
- Avatar bytes use the existing WordPress media/attachment system. Profile owns
  the selected attachment reference on the user-owned Profile seam; consumers
  do not duplicate image bytes or canonical URLs.
- Job Center and other consumers may render the resolved avatar but must not own
  upload, persistence, removal, or media authorization.

## Consumer contract

Profile exposes one canonical resolver for a user and requested display size:

`resolve_avatar( user_id, size ) -> { url, source, is_custom }`

The resolver owns attachment sizing/output and returns the preferred current
avatar without exposing consumer storage details.

## Resolution and fallback

Resolution order:

1. the user's first-party uploaded avatar attachment selected by Profile;
2. the existing WordPress/Gravatar resolution;
3. a deterministic neutral/default avatar suitable for shared shell rendering.

Removing a custom avatar clears the Profile-owned selected attachment reference
and returns the resolver to the next fallback. Missing, invalid, unauthorized,
or unavailable attachments fail closed to fallback.

## Implementation and QA boundary

`PROFILE-AVATAR001` may implement the bounded authenticated journey using the
existing WordPress media facilities, with type, size, dimension, and safe-output
validation. It must not add employer, School/Jobsite, Job Center, Gravatar
configuration, social-profile, or unrelated Profile schema behavior.

The implementation must establish its authenticated Profile route/control,
nonce/capability ownership, persistence/readback, replacement/removal behavior,
and consumer-level resolver/read test. Native QA must prove fallback, valid
upload, replacement, safe rejection, removal, persistence, and user isolation.
Job Center integration is a later consumer ticket.

This document is the canonical Profile avatar architecture owner. The Profile
Cursor and Engineering Handoff point here and must not duplicate or fork it.
