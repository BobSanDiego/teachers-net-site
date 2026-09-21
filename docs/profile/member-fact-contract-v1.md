# Profile V1 Member Fact Contract

Status: implemented data/persistence contract under `PROFILE-V1-MEMBER-FACT-CONTRACT001`. Public Profile and Edit Profile UI are deferred.

## Ownership

Core Terms framework `teachers-net` owns semantic term identity, hierarchy, and approved aliases. Profile owns the assertion that a member has a fact. Durable Views remains presentation composition, not a Profile fact store. Community membership remains participation context, not a Profile interest.

The physical relation remains Profile's existing `{$wpdb->prefix}tnet_profile_member_context` table through `TNet_Profile_Member_Context`. Its legacy `context_type` / `context_value` columns remain for Screen 6 compatibility. Canonical Profile facts use the same relation with:

- `context_type` as the logical relationship type;
- `context_value` equal to the UUID for relation compatibility;
- `term_framework_slug = teachers-net` and `term_uuid` as the immutable semantic reference;
- `provenance`, `visibility_scope`, and `lifecycle_state` as fact metadata.

No write is made to Core Terms' dormant `wp_cfm_user_terms` bridge. Profile is the only fact write owner; Core Terms is the live resolver.

## Relationship semantics

| Type | Meaning | Initial provenance | Rendering rule |
| --- | --- | --- | --- |
| `professional_identity` | Self-described professional context such as Administrator, Master Teacher, Mentor Teacher, Tutor, or Retired Educator. | `self_reported` | Aggregate Profile-details policy; never a credential or Teachers.Net achievement. |
| `teaching_grade` | Grade/level the member says they teach or serve. | `self_reported` | Aggregate Profile-details policy. |
| `teaching_subject` | Subject/discipline the member says they teach or serve. | `self_reported` | Aggregate Profile-details policy. |
| `interest` | A term explicitly chosen by the member. | `self_reported` | Aggregate Profile-details policy. |

The same UUID can have multiple meanings for one member. Mathematics, for example, can be both `teaching_subject` and `interest`. `verified` and `inferred_contextual` are reserved provenance values; this ticket creates neither a verifier nor behavioral inference. A join, job post, browse event, or inferred location is never `self_reported`.

## Legacy Screen 6 compatibility

Existing Screen 6 rows remain untouched:

- `teacher`, `administrator`, `education_student`, `retired_teacher`, `vendor`, `tutor`, and `other` remain legacy role values;
- `hiring` remains intent/capability, never a professional badge;
- Screen 6 save/skip affects only its legacy `role`/`intent` rows and cannot delete canonical Profile facts.

There is no destructive migration. A later migration may map only an explicitly approved Core Terms identity, preserve provenance, and retain auditable compatibility history.

## Live term resolution and alias boundary

`add_fact()` accepts only a live, non-axis Core Terms UUID. `resolve_live_core_term_identifier()` is discovery-only: it resolves a UUID, canonical slug-equivalent, or a Core Terms-owned tree alias and returns canonical UUID/label. It does not create aliases and is not a persistence shortcut. The current Profile-v1 readiness inventory is `docs/core-terms/profile-v1-readiness.md`.

| Canonical term | Runtime UUID |
| --- | --- |
| Grade 4 | `073dd227-e6ba-4dda-8159-2e6067519250` |
| Mathematics | `69294fa2-c0c4-4a2f-9864-211848c1c00a` |
| Science | `8f2243d1-8ea7-4547-b44b-88a5456d318b` |
| Administrators | `0838edba-879b-46c6-9e51-32fdce5dabcc` |
| Teacher | `1fcd2265-c4a1-47fd-97fc-6d3844e18952` |

The live resolver normalizes `English / Language Arts` to `English Language Arts` and `Library/Media` to `Library / Media`; Core Terms aliases resolve `Math`, `PE/Health`, and `ESL` to canonical UUIDs. `ELL`, `ESL/ELL`, and `multilingual` deliberately remain unresolved because they are not synonymous with ESL. `Counseling`, `Higher Education`, and `Adult Education` resolve directly. Legacy Screen 6 `teacher` remains a compatibility value; no migration or UI selection is introduced here.

## Query and visibility contract

The relation has unique `(user_id, context_type, term_uuid, provenance)`, `fact_user (user_id, context_type, provenance, term_uuid)`, and `fact_context (context_type, term_uuid, provenance, user_id)` keys. `facts_for_user()`, `user_ids_for_fact()`, and `user_ids_matching_facts()` are the Profile query seam. The latter intersects independent facts and may join Screen 5 country/region meta without copying location into this relation.

Avatar, Display Name, and `@username` remain existing public identity. `location_public` remains separately controlled. Grade, subject, professional identity, teaching-since, and bio use aggregate `profile_details` visibility, backed by `_tnet_profile_details_public` when a future UI writer is authorized. Profile additionally reserves `_tnet_profile_teaching_since`, `_tnet_profile_bio`, and `_tnet_profile_location_public`; this ticket supplies read representation only, not a UI writer. Per-fact public visibility is intentionally not introduced.

## Future activation prompt boundary

Activation state is separate from facts and Community membership. A future owner should retain one row per member/source event/candidate UUID with `eligible`, `offered`, `accepted`, `not_now`, `dismissed`, or `eligible_again`; source type/ID; first/last offer time; offer count; and cooldown/re-eligibility policy. Only `accepted` may call `add_fact(..., 'interest', ...)`. Community must first provide a post-commit membership event or durable outbox. This ticket implements no event, prompt UI, notification, or activation table.
