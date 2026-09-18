# TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001 — Shared Wrapper Security Boundary

**Terminal state:** BLOCKED — a Lessons-only credential rotation cannot be safely scoped to the shared active wrapper.

## Read-only findings

- The active `/var/www/cgi-bin/newwrapper.cgi` loads `/var/www/cgi-bin/newwrapper.pm`.
- The active TLS vhost routes not only the Lessons/Lessonplans family but additional legacy `.html` route families through `newwrapper.cgi`, including configured state- and Gazette-family paths.
- The required module contains embedded database credential material and uses database access across multiple application domains. The current database principal has grants across multiple application databases, not a Lessons-only boundary.
- No existing appropriate protected-secret convention was found in the inspected Sandy candidate locations. This is limited to the inspected convention paths, not a claim that no secret facility exists anywhere.

## Why the authorized rotation cannot proceed safely

Creating a new Lessons-only credential and changing the shared module would leave unknown shared consumers without a proven least-privilege path. Granting the replacement access equivalent to the current shared principal would exceed the ticket's requirement to determine only the privileges required for this renderer. Separating the Lessons renderer would require an Apache/routing change, which the ticket excludes.

The ticket therefore requires a stop before any credential, secret file, recovered source, deployment artifact, or production behavior is changed.

## Preserved boundary

- No credential was created, changed, disabled, or retired.
- No secret source was created.
- No renderer source was copied into Git.
- No Apache/vhost, database schema, ad, analytics, or production application change occurred.
- The prior non-sensitive hashes, modes, ownership, and syntax checks remain the authoritative pre-change provenance.

## Required Director decision

Choose one direction:

1. **Shared-wrapper program:** explicitly expand authority to recover, source-control, secret-separate, test, rotate, and deploy the whole active `newwrapper` consumer set and its shared database principal, with rollback.
2. **Lessons-specific routing design:** explicitly authorize an Apache/routing and deployment design that isolates Lessons/Lessonplans behind a separately owned renderer, then permit a least-privilege credential for that owner.

Neither direction is implied by the present ticket. The mobile ad-delivery repair remains deferred behind this security/architecture decision.
