# Community relationship and notification-preference foundation v1

Status: implemented local foundation, release-gated; no production delivery.
Objective: `COMMUNITY3-V1-RELATIONSHIP-NOTIFICATION-PREFERENCE001`.

## Ownership

- `TNet_Community_Relationship_Service` and its repository own opaque,
  user-scoped Community and thread relationship state.
- `TNet_Community_Notification_Preference_Service` owns explicit channel and
  frequency state, auditable legacy evidence reconciliation, and email
  suppression records.
- `TNet_Community_Notification_Integration` is the C3 policy adapter to the
  governed `tnet-notifications` provider. The provider owns notification-row
  persistence and read lifecycle; C3 owns recipient qualification, safe
  destination resolution, self-event suppression, and event dedupe inputs.
- Email is evaluated and audited only. This objective sends no email and does
  not create delivery consent.

## Durable invariants

Membership, Community follow, thread follow, inferred participation,
notification preference, and email consent are distinct states. Join does not
create a follow or email preference. Posting/replying may create only an
explicitly marked inferred participation row. Relationship IDs use canonical
opaque C3 target identities and canonical `user:{id}` identities; display
names, slugs, legacy IDs, and numeric coincidence are not identity.

Relationship transitions are idempotent and auditable. A user may have one
stateful row per relationship type and target; explicit thread follow and
inferred thread participation never share a relationship type.

## Notification policy

Committed replies may notify the direct parent author and active thread or
Community followers, excluding the actor. New topics may notify active
Community followers, excluding the actor. Provider rows carry the C3 event,
actor, opaque Community/thread/post target arguments, bounded safe context, and
an event/recipient dedupe key. C3 canonical destination resolution returns the
Community thread route with a reply fragment only after re-reading the target
post and thread root. Publication remains successful when optional notification
enrichment fails; failures are logged and contained after publication commit.

Bell eligibility is independent of email. Bell defaults to immediate unless an
explicit bell preference is `never`. Email eligibility requires an explicit
preference, rejects `never`, and applies active global/category suppression
(`unsubscribe`, `hard_bounce`, `complaint`, or governed administrative state).
An eligible email result is recorded as `EMAIL_ELIGIBLE_NO_DELIVERY` in this
foundation; no mail transport is invoked.

## Preference and legacy evidence contract

Supported explicit frequencies are `immediate`, `daily`, `weekly`, and `never`;
channels are `bell` and `email`; initial categories are
`community_activity` and `community_reply`. Every explicit change is
transactional and audited. Legacy values are retained as source evidence and
classified as `deterministically_mapped`, `explicit_confirmation_required`,
`NO_EMAIL`, or `suppressed`. Ambiguous optional-email evidence defaults to no
email and cannot enroll a user. Historical delivery is evidence, not consent.

The reconciliation seam is bounded and idempotent. It does not silently
convert `tnet_memberships.email_posts`, `email_responses`, group frequency,
WordPress/BuddyPress settings, unsubscribe, bounce, complaint, or prior
delivery into new delivery permission.

## Persistence and verification

Schema version 6 adds additive C3 tables for relationships, audits, explicit
preferences, preference audits, suppressions, reconciliation evidence, and
notification decisions. The existing provider schema and contract are
unchanged. The post-commit publisher hook is the only publication integration
point.

The local proof is
`tools/community3/test_relationship_notification_preference.php`. It is
disposable and cleans only its own synthetic rows. It proves relationship
idempotency and separation, unchanged membership, preference persistence,
bounded legacy classifications, suppression precedence, no preference creation
from ordinary actions, provider availability, exactly-one notification,
canonical target arguments, duplicate suppression, self-event suppression, and
email-suppression/bell independence. Run it serially with other membership
fixtures because both use disposable identity rows.

The unsafe legacy `local_publisher_persistence_tests.php` remains out of scope
and must not be executed because it can uninstall the complete C3 schema.

## Release boundary

This is local target-side foundation evidence only. Native bell/account
inspection remains a separate browser/HUMAN_QA gate when the required browser
control path is available. No production email, digest scheduler, full legacy
preference migration, Sandy action, writer switch, or public cutover is
authorized. The next planned objective is
`COMMUNITY3-V1-MEDIA-OPERATIONS-FOUNDATION001`.
