# Durable Views MVP Closeout

Status: Certified for first consumer — 2026-08-04

The Durable Views MVP is complete for the first controlled Job Center consumer.
The platform provides persistence, lifecycle, validation, deterministic
resolution, protected administration, preview, cloning, retire/restore, a
consumer service boundary, Jobs binding, parallel migration, and rollback.

Core Terms owns taxonomy and canonical UUIDs. Durable Views owns composition,
presentation metadata, validation, lifecycle, and resolution. Jobs owns only
its binding, job data, authorization, and assignments. Consumers resolve only
through `CFM_Views_Service`.

The annotated milestone `durable-views-platform-foundation-complete` is pushed
in both Profilaxes and Teachers.Net Jobs. Community is the next candidate
consumer, but implementation requires separate explicit authorization and a
consumer seam ticket.

Certification evidence: `docs/core-terms/durable-views-dv013-job-center-certification.md`.
