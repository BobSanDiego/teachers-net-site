# Teachers.Net Notifications Runtime Plan v1

**Status:** Accepted executable implementation plan; phases A–C are implemented
in the dedicated `tnet-notifications` plugin. Native producer integration remains
deferred until the persistence/API seam is accepted.
**Decision ticket:** `TNET-NOTIFICATIONS-RUNTIME001`
**Architecture authority:** `teachers-net-notifications-adr001.md`
**Consumer contract:** `teachers-net-notifications-contract-v1.md`

## 1. Physical owner and dependency model

The physical runtime owner is a new dedicated WordPress plugin:

```text
wordpress/wp-content/plugins/tnet-notifications/
```

It is the smallest durable package that can independently own a shared
cross-product contract, tables, upgrade lifecycle, source registration,
recipient-specific state, authenticated consumer services, and REST transport.
Its canonical plugin/package key is `tnet-notifications`.

```text
Jobs / Community / Lessons
  own facts, recipient eligibility, current authorization, routes
                  │ registered producer + source resolver contracts
                  ▼
tnet-notifications
  validates normalized event → persists recipient records → serves API
                  ▲
                  │ authenticated consumer API
Teachers.Net shell
  owns bell, dropdown, filters, and responsive interaction
```

This direction is supported by ADR001’s explicit independence from every
product and shell, the Notifications Consumer Contract, and the plugin
architecture rule that Core Terms classifies while Jobs authorizes. Repository
inspection found no existing shared-platform plugin that owns an analogous
cross-product runtime. The installed `tnet-jobs`, `tnet-community`,
`lessonbank-workbench`, and `tnet-profile` plugins are product boundaries.

Rejected placements:

- `tnet-jobs`: would make one producer own cross-product schema, ingress,
  release cadence, and consumer availability; ADR001 expressly rejects that
  inference from the shell fixture.
- `profilaxes` / Core Terms: it owns classification and user resolution, and
  its integration contract assigns notification display/delivery to consumers.
  It has no notification-record or authorization ownership.
- WordPress core or the theme: neither is a Teachers.Net product-platform
  package, schema/migration owner, nor safe source-registration boundary. The
  theme remains presentation only.

The plugin loads on the ordinary WordPress plugin lifecycle. Product producers
feature-detect its public internal contract after `plugins_loaded`; the
Notifications plugin must never load or query a product’s private tables.
Products register source adapters; the shell consumes only its REST interface.
This removes cyclic dependencies and permits independent, testable release and
migration ownership.

## 2. Persistence and migration contract

The dedicated plugin owns one recipient-record table named through `$wpdb`
prefixing as `{$wpdb->prefix}tnet_notifications`. It has no foreign keys into
product tables: WordPress user IDs and source object references are opaque,
source-owned references.

| Column | Contract |
| --- | --- |
| `notification_id` | `BIGINT UNSIGNED` primary key; immutable notification identity. |
| `recipient_user_id` | `BIGINT UNSIGNED NOT NULL`; the only query/mutation principal. |
| `source_product` | registered source key, e.g. `jobs`. |
| `event_id` | immutable source-scoped fact identity. |
| `event_type` | registered source event name. |
| `payload_version` | integer registered schema version. |
| `actor_user_id` | nullable WordPress identity reference; never an avatar URL. |
| `object_type`, `object_id` | source-owned opaque object reference. |
| `destination_key`, `destination_args_json` | validated route intent and structured source identifiers, never a raw destination URL. |
| `metadata_json` | schema-validated structured variables only; no prose, HTML, SVG, or copied media URLs. |
| `created_at` | immutable source event time. |
| `read_at` | nullable recipient read timestamp. |
| `active_state`, `archived_at` | `active`, `archived`, or `retracted`; inactive rows never contribute to unread counts. |
| `dedupe_key` | 64-character SHA-256 of the canonical source retry identity. |

Required indexes and invariants are:

- primary key on `notification_id`;
- unique `(recipient_user_id, source_product, dedupe_key)` for recipient-safe
  creation idempotency under retry/concurrency;
- `(recipient_user_id, active_state, read_at, created_at)` for unread count and
  chronological active list;
- `(recipient_user_id, active_state, source_product, created_at)` for source
  filtering; and
- `(source_product, event_id)` for controlled source-event retraction/audit.

`active_state` supports source retraction without erasing history. Retraction
archives/retracts matching source event records atomically and preserves the
fact/reference for audit. A retention worker may purge only inactive records
after a separately approved retention policy; no automatic deletion window is
chosen in v1.

The plugin owns a named schema target/version option and upgrade routine. The
first persistence ticket must use an idempotent, versioned migration with
pre/post table and index assertions, an upgrade-safe no-op path, and a rollback
document that leaves source facts untouched. Products own no Notifications
schema or migration.

## 3. Internal service and authenticated REST adapter

The canonical surface is an internal PHP service with a WordPress REST adapter,
not a REST producer ingress endpoint. The service accepts only registered
source adapters and exposes recipient-scoped reads/mutations.

| Internal service operation | REST adapter |
| --- | --- |
| `create_for_recipients(RegisteredEvent, recipient_ids)` | None in v1; producers call internal service. |
| `unread_count(recipient_user_id)` | `GET /tnet-notifications/v1/unread-count` |
| `list(recipient_user_id, source?, cursor?, limit?)` | `GET /tnet-notifications/v1/notifications` |
| `mark_read(recipient_user_id, notification_id)` | `POST /tnet-notifications/v1/notifications/{id}/read` |
| `mark_all_read(recipient_user_id, source?)` | `POST /tnet-notifications/v1/notifications/read-all` |
| `archive_or_retract(source event reference)` | Internal source-only operation; no public REST route. |

The REST permission callback requires an authenticated WordPress user; each
method takes the recipient only from `get_current_user_id()`, never a client
parameter. List results contain only active records currently authorized for
that recipient. Pagination uses a stable `(created_at, notification_id)`
cursor, an allow-listed `source_product` filter, and a bounded limit. Opening
the bell is not a read-all mutation.

Before list hydration and before resolving a click-through, the service calls
the registered source adapter with recipient, object, and destination intent.
The adapter returns an authorized current destination, optional authorized
actor/media representation, or denial. A denied/retracted/inactive record is
not rendered as an actionable result. The shell receives resolved, consumer-
safe fields; it owns only presentation mapping and interaction.

## 4. Source registry, validation, and security model

The plugin owns a closed source/event registry. A registered entry declares
the source key, event type, accepted `payload_version`, metadata allow-list and
shape, destination key/argument schema, and source-provided eligibility/object/
destination resolver callbacks. Unknown source, event, version, field, or
destination key fails closed before persistence.

Security invariants:

- WordPress authenticates every consumer request; Notifications never accepts a
  recipient ID from a browser client.
- The product derives recipient eligibility and keeps current object access;
  a notification record never authorizes source access.
- Every read, mark-one, mark-all, render, media lookup, and destination lookup
  is recipient-scoped. The source is reauthorized at render and click-through.
- Producers are in-process registered adapters only in v1; there is no public
  write endpoint, arbitrary webhook, or arbitrary external destination.
- Metadata is validated to per-event scalar/structured schemas and encoded as
  JSON; final prose, HTML, SVG, unbounded blobs, copied avatar URLs, and raw
  restricted content are rejected.
- Actor/avatar/media resolution is source-authorized per recipient and may
  return no representation. No identity or media is inferred from a stored
  value.
- The unique recipient/source/dedupe constraint is authoritative for retries;
  duplicate insertion returns the existing notification instead of adding a
  second unread record.

## 5. First native Jobs producer: approved/published

The authoritative seam is
`TNet_Jobs_Moderation_Admin::process_moderation_job()`. On a successful
`TNet_Jobs_Job_Service::update_job()` call, its `approve` action changes a job
from `submitted` to `published` and sets `approved_at`, `published_at`, and
`moderated_by_user_id` together. This is the producer hook. The notification
adapter must run only after that successful write and must use the re-read
current job returned by the service path; it must not infer publication from
an admin form, redirect, browser state, or the existing email side effect.

The v1 registered event is:

| Field | Value |
| --- | --- |
| `source_product` / `event_type` / `payload_version` | `jobs` / `job.approved` / `1` |
| `event_id` | `jobs:job.approved:<job_id>:<approved_at-UTC>` |
| recipient | exactly `created_by_user_id`, only if Jobs verifies it has an active membership for the job’s `employer_id` at producer time |
| actor | the moderator (`moderated_by_user_id`) |
| object | `job` / current `job_id` |
| destination | `jobs.public_job_detail` with `{job_id}` only; Jobs resolves the current slug/URL later |
| metadata | allow-listed neutral presentation variables such as job title and employer display name; no rendered sentence or copied URL |
| recipient dedupe identity | `jobs:job.approved:<job_id>:<approved_at-UTC>:recipient:<user_id>` before SHA-256 normalization |

On retry, the same event/recipient input reaches the unique constraint and
returns the original record. A genuinely later approved transition has a new
`approved_at` fact identity and may create one later notification. If Jobs
cannot prove recipient eligibility or source/destination visibility, it emits
no notification. The registered Jobs resolver rechecks active membership for
eligibility and current public job availability for the click-through.

The second planned producer is `job needs attention`; its event definition,
recipient rule, and lifecycle source require their own bounded design pass.
Scheduled `job expiring` stays deferred until native creation, retry, and
dedupe acceptance have passed.

## 6. Fixture/runtime provider boundary

The current deterministic Notification Center fixture remains an explicit
`FixtureNotificationProvider` used only by isolated shell QA. It does not
implement the service contract, share tables, invoke a producer, or claim
native evidence.

The future shell adapter selects exactly one provider through explicit runtime
configuration: `fixture` in the isolated QA harness and `authenticated` in a
normal WordPress request. The authenticated provider calls the REST API for the
current WordPress user. No fixture record, avatar, read state, or source data
is ever copied into the `tnet_notifications` table; replacement is an adapter
swap, not a fixture migration.

## 7. Implementation and acceptance sequence

| Phase | Scope | Decisive evidence |
| --- | --- | --- |
| A | Create and activate the dedicated plugin/package, registry contract, and migration owner only. | Plugin loads independently; no product-table dependency; registry rejects an unknown source. |
| B | Add versioned recipient table/repository and migration. | Fresh upgrade plus repeat upgrade prove columns, indexes, unique dedupe, and no-op path. |
| C | Add internal service and authenticated REST adapter. | Authenticated recipient cannot read/mutate another recipient; unknown event/version/metadata/destination fails closed. |
| D | Add authenticated shell provider while retaining the explicit fixture provider. | Fixture QA remains deterministic and no fixture persistence appears; runtime list/count/read calls are recipient-scoped. |
| E | Register the Jobs `job.approved` producer at the successful moderation transition. | One submitted job becomes published; one eligible creator receives exactly one persisted record despite a retry. |
| F | Native end-to-end acceptance. | Moderation write, stored normalized record, authenticated bell count/list, mark read, current reauthorization, and resolved destination all pass against the same native record. |
| G | Remove superseded diagnostic scaffolding and consolidate tests/docs. | Final diff contains no fixture-as-runtime path, no product-owned Notifications table, and all carried seams retain identity. |

Seams A–C may be carried forward once their plugin/version, schema, registry,
and service identities are unchanged. Phase F is mandatory native acceptance;
fixture, static inspection, or browser-only shell proof cannot replace it.

## 8. Next executable phase

`TNET-NOTIFICATIONS-PERSISTENCE-API001`: implement phases A–C only in the new
dedicated plugin, with schema/upgrade and authenticated authorization tests.
It must not add the Jobs producer until the persistence/API seam is proven.
