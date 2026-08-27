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
No notification table, migration, native producer, email-channel change,
preferences system, or production write is authorized by this ADR.

## Deferred decisions

The Engineering Director must separately approve the durable storage owner,
producer registration/ingress, retention/retraction policy, preferences and
consent integration, transport/digest policy, and the first native Jobs
producer. Those decisions must preserve this boundary rather than absorbing it
into a product-specific table or shell UI.
