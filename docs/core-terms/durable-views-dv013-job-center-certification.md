# DV-013 — Job Center Consumer Certification

Status: Complete — MVP certification evidence
Date: 2026-08-04

## Certified Boundary

Job Center owns the durable binding between a form field and one published
Durable View/version. Durable Views owns View composition, Core Terms UUID
validation, lifecycle, and resolution. Job Center consumes the resolved model
through `CFM_Views_Service` and does not read View tables or reconstruct View
composition.

## End-to-End Evidence

The DDEV certification smoke test verified:

- a draft View entry validated as `valid`;
- the View published successfully;
- a Jobs form field bound to the matching published View/version;
- the parallel adapter returned one platform-resolved option;
- retirement changed the View to `retired` and blocked current resolution;
- restoration returned the View to `published` and recovered one resolved entry;
- unbinding returned the adapter to fallback mode;
- temporary certification records were removed.

## MVP Decision

The Durable Views MVP is technically complete for its first controlled Job
Center consumer. The legacy Jobs option path remains available for rollback and
parallel comparison. Community and other consumers remain deferred adoption
workstreams.

## Evidence and Git

- Profilaxes authority branch: `agent/durable-views-dv003-persistence`, clean,
  last commit `83eebfb`.
- Jobs authority branch: `main`, clean, last commit `2f31a93`.
- DDEV PHP lint and runtime smoke verification passed.
- No production deployment or browser acceptance is claimed by this
  certification ticket.
