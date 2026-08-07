# DV-FIX002 Completion Report

Status: COMPLETE
Date: 2026-08-07

## Outcome

The repository is now the durable enforcement boundary for Current View
descendant removal. A request containing only an included parent entry closes
over all included descendant entries in the same draft version and framework
before deletion.

## Implementation

`CFM_Views_Repository::delete_entries()` now:

- resolves requested IDs against entries belonging to the requested draft
  version;
- resolves canonical parent relationships through Core Terms;
- recursively identifies included descendant entries in the same framework;
- normalizes duplicate and overlapping requests through a deletion set;
- deletes only entries belonging to the requested version;
- leaves excluded entries and other versions/frameworks outside the closure.

The controller path and DV-FIX001 client behavior were preserved. No schema,
resolver, UUID, Jobs, Library, or lifecycle changes were made.

## Verification

Canonical review URL:
`https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`

Authenticated identity: `jobman`.

Focused local repository check:

- temporary draft with included `Early Childhood` parent and `Early Learners`
  child;
- parent-only request;
- repository result deleted both rows (`result=2`);
- temporary draft cleaned up.

Authenticated browser verification:

- Current View before removal contained `Grade Level → Elementary → Grade 1`
  and the `Early Childhood` branch;
- selecting parent entry `18` through DV-FIX001 produced payload IDs `18` and
  `19`;
- Remove Selected was submitted on authorized local QA draft version 17;
- after reload, only the `Grade Level` ancestor remained;
- `Early Childhood` and `Grade 1` were absent, so no orphan descendant
  remained;
- Library represented-state refreshed to show only `Grade Level`;
- no console warnings or errors.

The repository query requires `version_id` for both entry resolution and
mutation, and closure candidates must match `core_terms_framework`; this is
the cross-version/framework isolation boundary.

No production or published data was touched.

## Git

Profilaxes branch: `agent/durable-views-dv003-persistence`

Profilaxes commit: `60e04c4` — pushed successfully.

Root documentation and cycle artifacts are recorded in the completion cycle.

## Evidence

- `DV-FIX002-before.png`
- `DV-FIX002-after.png`

The Current View orphan-removal defect is fully closed at both the client and
repository layers.
