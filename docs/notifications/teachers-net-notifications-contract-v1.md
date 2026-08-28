# Teachers.Net Notifications Contract v1

**Status:** Accepted contract; synthetic consumer fixture implemented
**Architecture authority:** `teachers-net-notifications-adr001.md`
**Runtime status:** Durable persistence and the authenticated read/read-state API
are implemented in the dedicated `tnet-notifications` plugin. No native producer
is implemented yet.

## 1. Purpose and versioning

This contract is the presentation-neutral boundary between product facts, the
future shared Notifications capability, and Notification Center consumers.
`payload_version` is mandatory on every event. An unknown source/event/version
must fail closed rather than be rendered as an unverified notification.

An event fact is not a recipient notification. Recipient eligibility,
notification persistence, read state, delivery, and engagement remain distinct
operations.

## 2. Normalized producer event

Products will eventually submit a versioned fact with these semantics:

| Field | Requirement |
| --- | --- |
| `event_id` | Immutable, source-scoped fact identity. |
| `source_product` | Stable registered source key: `jobs`, `chatboards`, `lessons`, or later `system`. |
| `event_type` | Stable source event name such as `job.approved`; never a display sentence. |
| `payload_version` | Integer schema version for the event metadata. |
| `actor_user_id` | Nullable opaque WordPress/user identity reference. |
| `object_type`, `object_id` | Source-owned object reference. |
| `destination_intent` | Validated source route key plus source-owned identifiers; not arbitrary HTML/URL. |
| `metadata` | Structured, source-versioned variables only. No final UI prose, markup, SVG, or copied avatar URL. |
| `created_at` | Immutable source event timestamp. |
| `dedupe_key` | Stable idempotency key for safe retries. |
| `visibility_basis` | Source authorization/visibility reference required to determine recipients. |

Products retain the source object and must continue to authorize any route or
media resolution. The future Notifications service validates the registered
source/event schema and may create one record per authorized recipient.

## 3. Recipient notification consumer record

The future persisted record and this fixture consumer use these minimum
semantics:

| Field | Requirement |
| --- | --- |
| `notification_id` | Immutable opaque notification identity. |
| `recipient_user_id` | Recipient WordPress identity; every query/mutation scopes to it. |
| `source_product` | Product/source key for filtering and fallback presentation. |
| `event_id`, `event_type`, `payload_version` | Provenance and versioned interpretation. |
| `actor` | Nullable opaque actor reference; resolved by a consumer resolver. |
| `object` | Source-owned type/ID reference. |
| `destination` | Validated route intent or resolved canonical source route. |
| `metadata` | Structured consumer-safe variables; schema-defined per event. |
| `created_at` | Chronological ordering. |
| `read_state`, `read_at` | Recipient-specific unread/read state; separate from delivery or engagement. |
| `active_state`, `archived_at` | Active/archive/retraction lifecycle; archived items do not affect badge count. |
| `dedupe_key` | Recipient-safe idempotency identity. |

The shell may create flowing display text from a registered event presentation
mapping. That rendering is intentionally not canonical producer data.

## 4. Resolver boundaries

- **Actor/avatar resolver:** Given an actor reference and recipient context,
  returns an authorized avatar representation or no result. It must not leak a
  hidden identity.
- **Object/media resolver:** Given source/object reference and recipient
  context, returns authorized optional media. Absence is normal.
- **Destination resolver:** Given source route intent and recipient context,
  returns a safe current destination or denies it. The destination is checked
  again on click.
- **Authorization resolver:** The source product remains authoritative for
  recipient eligibility and current object visibility. Notifications does not
  grant source access.

Consumers use a central presentation map for source fallback and event badge:
Jobs/briefcase, Chatboards/speech bubble, Lessons/open book, and generic
system/account fallback. SVG assets are presentation-only consumer assets.

## 5. Read, filter, and security semantics

Unread count means active unread records for the authenticated recipient.
Mark-one-read and future mark-all-read are recipient-scoped state mutations;
opening a bell is not a read-all action. `All`, Jobs, Chatboards, and Lessons
filters are consumer projections over `source_product`; products do not own
filter styling or chip color systems.

Restrict queries and mutations to the authenticated recipient, validate source
and payload versions, sanitize metadata and destinations, avoid raw restricted
content in generic payloads, reauthorize object/media/destination access at
render and click, and keep consent/suppression distinct from membership.

## 6. Synthetic fixture contract

The current browser fixture is deterministic, isolated, and marked synthetic.
It contains no database write, producer hook, transport, native recipient
state, or production acceptance claim. It may include fixture display payloads
and fixture-avatar representations solely to exercise the consumer contract.
The adapter is replaced—not migrated—when the future authenticated query API is
implemented.
