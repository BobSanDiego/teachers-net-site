# Community 3.0 Current-State Census v1

Status: read-only reconciliation. Evidence is classified as verified, partial, legacy, absent, or unknown.

| Area | Finding | Classification | Consequence |
|---|---|---|---|
| Authentication | WordPress is the local authentication boundary. | Verified locally | Reuse identity; do not invent a second member authority. |
| Chatboard identity | `tnet_local_data.path_id` and related post/feed context use path identity. | Verified in code/docs | Preserve `path_id`; do not substitute `group_id`. |
| Group identity | `tnet_groups.group_id` is the teacher-group identity. | Verified in code/docs | Resolve `local_path -> group_id` only through an explicit mapping. |
| Membership | `tnet_memberships.group_id` stores group membership. | Verified in code/docs | Membership and chatboard participation must not be conflated. |
| Posts | `tnet_chatposts.local_id` may carry chatboard context. | Partial/legacy | Require a bounded post-context census before implementation. |
| Preferences | `tnet_memberships.email_posts` is an observed preference field. | Partial/legacy | Not sufficient as a complete subscriber contract. |
| Notifications | No verified active chatboard sender, queue, digest worker, throttle, or last-send field was found. | Absent/unknown | Do not infer delivery capability from dormant code. |
| BuddyPress | Separate notification framework exists. | Legacy/unknown authority | It is not safely authoritative for Community 3.0. |
| Local DDEV | Core Terms and Jobs tables exist; Community `tnet_*` tables are not present locally. | Verified locally | Local lab is a planning/compatibility boundary, not a Community data clone. |
| Production | `ssh sandy` identity access was verified; no production data query was performed in this ticket. | Verified access, census incomplete | Mail and membership claims remain unresolved. |

## Required invariants and evidence gaps

1. `path_id != group_id` must remain an explicit identity rule.
2. Membership, post visibility, subscriber consent, and notification delivery need separate authorities.
3. Production counts, mail routing, suppression, provider configuration, and historical notification behavior remain unknown until separately audited.
4. No database write, migration, delivery test, or production edit was performed.
