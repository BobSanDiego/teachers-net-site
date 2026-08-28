# ADR001 — Teachers.Net Notifications Platform Boundary

**Status:** Accepted
**Date:** 2026-08-27
**Authority:** Engineering Director decision in `TNET-NOTIFICATIONS-FOUNDATION001`; diagnostic basis: `TNET-NOTIFICATIONS-ARCH001`

## Decision

Teachers.Net Notifications is a shared platform/communications capability. It
is independent of Jobs, Community/Chatboards, Lessons, Core Terms, the theme,
and any particular shell consumer.

- Products own authoritative event facts, recipient eligibility, object access,
  current authorization, and source routes.
- Notifications owns the normalized cross-product contract and, when separately
  authorized, recipient notification persistence and read/archive state.
- The shell owns Notification Center presentation and interaction.
- Core Terms continues to classify/resolve terms and users; it does not own
  notification records.

The current Notification Center implementation is a browser-only synthetic
fixture consumer in the existing shell. Its physical location in the Jobs
plugin is an implementation convenience for that shell consumer only; it does
not make `tnet-jobs` the persistence, event-ingress, or platform owner.

## Consequences

No producer may persist rendered HTML, final UI prose, SVG markup, copied
avatar URLs, or shell-specific presentation choices as the cross-product
notification contract. Source/event keys and opaque actor/object references are
the durable boundary. The shell resolves presentation through centrally owned
consumer mappings after current authorization is established.

The v1 shared contract is `docs/notifications/teachers-net-notifications-contract-v1.md`.
The physical runtime owner and executable v1 boundary are now established by
`tnet-notifications-runtime-plan-v1.md`. The first implementation remains a
separate persistence/API ticket; this ADR does not itself authorize a
notification table, migration, native producer, email-channel change,
preferences system, or production write.

## Physical runtime owner — TNET-NOTIFICATIONS-RUNTIME001

The durable owner is a new dedicated WordPress plugin with the canonical
package directory `wordpress/wp-content/plugins/tnet-notifications/` and the
runtime/plugin identity `tnet-notifications`.

This is a platform boundary, not a relocation of the current Jobs shell
fixture. It owns Notifications tables, schema/version upgrades, producer
registration, normalized event validation, recipient-notification persistence,
authenticated query/read-state services, and the REST transport adapter. It
loads independently of Jobs, Community, Lessons, Core Terms, the theme, and a
particular shell.

Products depend inward on this contract as registered producers. The shell
depends on the authenticated consumer API. Notifications must never depend on
Jobs lifecycle classes or query Jobs tables directly; source-specific
eligibility, object/media resolution, and destination authorization remain
registered source adapters owned by their products.

The complete accepted implementation plan, including schema, security,
producer, migration, and native acceptance seams, is
`docs/notifications/tnet-notifications-runtime-plan-v1.md`.

## Deferred decisions

Preferences and consent integration, email/transport/digest policy, scheduled
retention execution, Community and Lessons producers, and every channel beyond
the authenticated in-product consumer remain separate decisions. The first
Jobs producer is planned but is not authorized for implementation by this ADR
or its runtime plan.
