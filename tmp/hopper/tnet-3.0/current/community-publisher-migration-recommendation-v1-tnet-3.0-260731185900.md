# Community Publisher Migration Recommendation v1

## Plain recommendation

The legacy engine is worth carrying forward as a **behavioral reference and
temporary read-only compatibility boundary**. It is **not** a foundation for
Community 3.0, and it should not remain the new-write bridge. Its execution
architecture is a retirement target after URL/archive migration is proven.

## Recommended path

1. Establish authorized source ownership and obtain redacted, reproducible
   characterization evidence.
2. Preserve legacy URLs, static archives, timestamps, thread relationships,
   moderator evidence, and reconciled authorship through compatibility records.
3. Extract validated product rules into WordPress-native publisher contracts.
4. Make WordPress authentication, canonical `group_id`, privacy, moderation,
   Portable Views, Core Terms, and Community notification contracts authoritative.
5. Run staged read-only comparisons, then a bounded native-write pilot with
   explicit rollback and no dual-writer ambiguity.
6. Retire CGI writes only after reconciliation, URL checks, archive checks, and
   operational recovery are accepted.

## Non-negotiable unknowns

Do not infer anonymous posting, edit/delete/retract, moderator authorization,
mailring behavior, local-path/group mapping, or WordPress identity guarantees.
Those require a separate evidence boundary. Until resolved, live notification
attachment and migration writes remain blocked.

This recommendation is documentation-only and does not authorize source
acquisition, schema changes, production hooks, migration, or deployment.
