# Community 3.0 Notification Implementation Readiness Audit v1

Status: documentation and implementation planning only. No code, schema, queue,
mail, notification record, production, or delivery change was made.

## 1. Executive conclusion

The completed Community notification contracts are sufficient to define a
bounded implementation plan, but not sufficient to begin broad delivery or
multi-channel implementation. The repository contains policy contracts and
separate product capabilities, not an established Community notification
runtime. The smallest safe slice is a dry-run, Community-scoped candidate
evaluator for one visible group-post event and one authenticated recipient,
with no persistence, bell creation, email, queue, or production enablement.

Implementation must first close the missing mention contract, agree event and
recipient fixtures, and document the test harness and observability boundary.
The first implementation ticket is defined in `C3-IMP002` below.

## 2. Contract readiness

| Contract | Readiness | Implementation implication |
|---|---|---|
| Domain events and notifications | Ready as policy | Defines event, candidate, eligibility, channel, dedupe, and audit separation. |
| Subscriber policy | Ready as policy | Provides consent, category, channel, frequency, and pause boundaries. |
| Suppression and abuse control | Ready as policy | Requires scoped blocks, complaint/bounce handling, and re-evaluation. |
| Bell and read state | Ready as policy | Defines recipient-specific presentation and read/archive separation. |
| Reply and reaction | Ready as policy | Define neighboring event families and cross-product isolation. |
| Group activity | Ready as policy | Defines membership, visibility, frequency, grouping, moderation, and mapping. |
| Mention | Missing local contract | Must not be implemented as an inferred extension of reply or group activity. |

The contracts establish permanent invariants: group membership is not consent;
an event is not a notification; eligibility is not delivery; bell is not email;
and `path_id` is not `group_id`.

## 3. Dependency inventory

Required dependencies are: authenticated WordPress identity; Community event
producer or fixture; explicit `path_id -> group_id` mapping; group membership
and visibility resolver; subscriber category/channel/frequency policy; scoped
suppression and abuse resolver; candidate evaluator; dedupe/coalescing policy;
recipient-safe presentation data; append-oriented audit; deterministic test
fixtures; and a kill switch that is disabled for the dry-run slice.

Email provider, queue infrastructure, persistent bell storage, preference
migration, production group data, and delivery credentials are not dependencies
of the recommended dry-run slice and must remain out of scope.

## 4. Repository implementation map

| Area inspected | Finding | Reuse boundary |
|---|---|---|
| `tnet-jobs` email templates and services | Product-specific job alerts and employer notices exist. | Reuse generic WordPress conventions only; do not make Jobs notification authority. |
| `profilaxes` | Core Terms administration and term/meta-group facilities exist. | Terms classify; they do not authorize Community notifications. |
| Teachers.Net theme | No inspected Community notification runtime authority established. | Do not add notification behavior to the theme for this slice. |
| Legacy WordPress/BuddyPress/mail facilities | Platform or legacy capability, not Community policy authority. | Read-only evidence only until explicitly reconciled. |
| Community contracts/docs | Policy source for the proposed evaluator. | Implement against contract interfaces, not legacy behavior. |

No existing local component was verified as a reusable Community candidate,
bell, digest, suppression, or delivery subsystem. Existing Job Center code is
isolated and must not be absorbed.

## 5. Missing persistence, APIs, queues, and UI

Missing or unverified areas include an event envelope API, candidate decision
API, explicit mapping resolver boundary, recipient policy adapter, suppression
adapter, durable audit model, bell persistence/read API, digest scheduler,
email queue, transport abstraction, administrative observability, and Community
bell UI. These are gaps, not authorization to implement them now.

The dry-run slice should return an inspectable decision object in memory or a
test-only artifact. It must not create durable notification state or expose a
new user-facing route.

## 6. Dependency graph

```text
authorized group event
        |
        v
visibility + path_id/group_id mapping
        |
        v
membership/access ---- subscriber policy
        |                       |
        +-----------+-----------+
                    v
             suppression/abuse
                    |
                    v
          dry-run candidate decision
             /          |          \
          bell       email/digest    audit
        deferred       deferred     test-only
```

No downstream channel is enabled by the graph. The dry-run boundary ends at
the candidate decision and test-only evidence.

## 7. Readiness blockers and safeguards

Before delivery implementation, Engineering Director decisions are needed for
the default group-post recipient scope, announcement category, digest and
coalescing windows, private-group safe summaries, membership-change notices,
mention policy, and legacy source reconciliation. The `path_id`/`group_id`
mapping must be explicit in every fixture.

Safety controls are a disabled kill switch, no outbound transport, no schema
write, no preference migration, no production execution, deterministic test
users, and audit-safe redaction of private content.

## 8. Smallest safe implementation slice

Implement only a test-only/dry-run evaluator for a single visible new group
post. Given a synthetic event, an authenticated synthetic member, explicit
group mapping, selected frequency, and suppression state, it returns one of
eligible, blocked, or ineligible plus the policy reasons. It must demonstrate:

1. membership is checked separately from consent;
2. `path_id` and `group_id` are carried separately;
3. private or moderated content is blocked when unauthorized;
4. muted/never/paused-email outcomes remain channel-specific;
5. no bell, email, digest, queue, schema, or production side effect occurs;
6. the decision is deterministic and auditable in test output.

This slice is a feasibility proof, not a product notification feature.

## 9. Acceptance criteria for readiness

The project is ready to consider implementation only when C3-IMP002 produces
the bounded dry-run evidence, the missing mention contract is resolved or
explicitly excluded, fixture ownership is documented, and Engineering Director
approves the next channel or persistence slice. No delivery claim may be made
from a dry-run candidate decision.

## 10. Stop boundary

This audit created planning documents only. It does not authorize code, schema,
queues, bell records, email, notification delivery, production edits,
preference migration, or a user-facing Community notification surface.
