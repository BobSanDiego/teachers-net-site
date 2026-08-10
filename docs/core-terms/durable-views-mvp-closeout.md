# Durable Views MVP Closeout

Status: Standalone MVP ready for consumer integration — 2026-08-10

The Durable Views standalone MVP is complete and ready for a separately
authorized consumer integration. The first controlled Job Center consumer
adapter remains available, but the Job Center wizard was not migrated in the
standalone closeout.
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
consumer seam ticket. The existing Job Center binding remains intentionally
pinned to View 10 / published version 12.

Certification evidence: `docs/core-terms/durable-views-dv013-job-center-certification.md`.
Standalone lifecycle evidence: DV-ACCEPT002 Report/Hopper cycle dated
2026-08-10.
