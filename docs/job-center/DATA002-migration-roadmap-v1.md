# DATA002+ — Job Center Data Architecture Migration Roadmap v1

Status: Roadmap adopted by DATA001-REV1

## Ordered program

1. DATA002 — Additive schema for School/Jobsite, employer relationship, a required Primary Resource relationship for every new wizard-created Job, address, primary/additional job locations, and one-primary-media reference. No UI migration. Legacy compatibility may remain nullable during backfill, but it is not the target contract.
2. DATA003 — Repositories, services, validation, authorization, duplicate confidence workflow, and resource hydration/serialization.
3. DATA004 — Draft envelope and per-step persistence contract; preserve current mutable draft behavior and idempotent resume.
4. DATA005 — Job classification assignments with Core Terms UUID and Durable View/version provenance.
5. DATA006 — Typed application-process adapter for current URL behavior plus approved email/instructions/contact/deadline/materials fields.
6. DATA007 — Compatibility reads, high-confidence backfill, repair diagnostics, and rollback rehearsal for existing jobs.
7. DATA008 — Lifecycle certification across create, draft/resume, edit, trusted/untrusted edit, duplicate, renew, close, archive, moderation, public/search/JSON-LD, saved/revealed/interested, and analytics consumers.
8. JC053-MIG004 — Feature-flagged Step 1 production integration after DATA002–DATA008 contracts required by Step 1 are certified.
9. JC053-MIG005+ — Step 2 through Step 5 integration as separate bounded tickets.

## Superseded sequence

The former sequence that began directly with JC053-MIG003 Step 1 migration is superseded. JC053-MIG003 exposed the missing contract and remains a valid diagnostic record; it is not an implementation prerequisite until DATA002–DATA008 establish the resource authority.

## Rollback and compatibility rule

All data work is additive. Legacy employer/job address reads remain available. The feature flag remains the rollout boundary. Rollback disables the new wizard path and preserves additive records; no destructive retirement occurs in DATA002–DATA008.
