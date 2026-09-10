# Minimum Community Publisher Contract v1

Status: required contract before modern notification attachment; not an
implementation plan or authorization.

The publisher must own or expose:

1. Stable `post_id`, authenticated author identity, anonymous policy if
   applicable, parent/thread identity, safe content reference, and timestamp.
2. Explicit sanitization, spam/moderation result, publication decision, error
   state, and idempotent operation identity.
3. `path_id`, `local_path`, canonical `group_id`, and explicit mapping evidence;
   equality must never be assumed.
4. Publication state, group privacy, access basis, moderation state,
   hidden/retracted state, and current-state re-evaluation.
5. A committed post record and authoritative publication moment. A downstream
   post-publication event must not block or alter publication.
6. Edit, delete, retract, restoration, and moderation-correction transitions.
7. One idempotent, redacted post-publication payload with no consent
   inference, preserving event/candidate/bell/delivery/engagement separation.
8. Failure isolation so notification evaluation cannot roll back, retry, or
   expose errors through publication.

The legacy CGI partially supplies file/DB persistence, local-path resolution,
and timestamp/post-type facts but not canonical group mapping, modern
visibility/moderation, or an authoritative event. The exact next ticket is a
read-only source-ownership/compatibility audit for the Sandy publisher,
followed by an Engineering Director decision to modernize, wrap, or retire it.
