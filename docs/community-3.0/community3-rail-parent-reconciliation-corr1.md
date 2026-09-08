# Community 3.0 Rail Parent reconciliation correction — COMMUNITY3-RAIL-PARENT-RECONCILIATION-CORR1

Status: complete local Core Terms/Views data-state correction. This document
does not authorize C3 runtime consumption, navigation, legacy-content changes,
Jobs changes, or production deployment.

## Authority

- Semantic authority: `teachers-net`, framework 1, active version 1 in the
  local Core Terms runtime.
- Presentation authority: published typed Views Lists with role
  `c3_rail_parent`.
- Runtime scope: local `teachers-net-live` DDEV only.
- This correction supersedes the prior unresolved nine-row
  `General / Multiple Subjects` family ambiguity.

## Canonical correction

`General / Multiple Subjects` remains exactly once under `Subject Area` with
UUID `a2990cb3-d1c7-4cdf-9f92-dde31d521e5d`. It remains the curricular,
cross-subject catch-all; it is no longer used as the broad fallback for the
nine affected Communities.

The new canonical Terms are:

- top-level `Teachers` — `84ae4204-1c7d-4395-b3c1-90037df2e030`;
- top-level `Social` — `deddb88c-15f8-4446-a745-664c494eec99`;
- `Social > Teacher Travel` — `2fbafc00-c86d-4f32-8544-45f529a41420`;
- `Social > Teacher Social` — `38f2821a-ecac-4a1f-a321-7fededf335fc`;
- `Social > Teacher Recipes` — `986c5913-5db9-4686-bcdb-4b0b5cc7529f`;
- `Social > Just for Fun` — `6892667c-a840-49b3-ba3b-49f90c53b703`;
- `Social > Classroom Humor` — `512e3be3-e66e-4834-898f-64b9270ed2d5`;
- `Social > Teacher Gatherings` — `e1298352-d4e9-435f-9ec9-309d6cfd5901`;
- `Teaching Practice & Theory > General Education` —
  `178c564d-25f8-43b1-b9d7-9f539f13ded2`;
- `Teaching Practice & Theory > Classroom Learning Games` —
  `de569258-9076-4fc0-b145-82864a88f866`.

The nine legacy mappings are recorded in the machine-readable ledger for this
cycle. Social children are owned by the new Social presentation family;
General Education and Classroom Learning Games are owned by Practice &
Theory. Teachers remains a top-level presentation identity; no Teachers rail
List was invented.

## Published Lists

The seven existing Lists were republished with their original members and the
two Practice & Theory additions. A new Social List was published:

| List | View/version | Members |
| --- | --- | ---: |
| Hot Topics | 55/79 | 8 |
| Grade Levels | 56/80 | 12 |
| Subject Areas | 57/81 | 12 |
| Practice & Theory | 58/82 | 18 |
| Professional Groups | 59/83 | 16 |
| Classroom Projects | 60/84 | 11 |
| Careers & Credentials | 61/85 | 5 |
| Social | 62/86 | 6 |

Current published member ownership is globally exclusive: 88 unique included
member UUIDs and zero duplicate-member conflicts. Existing members were not
removed; Practice & Theory gained only General Education and Classroom
Learning Games.

## Verification

- Core Terms compiled: 219 terms, 564 closure rows, 0 relationships.
- `General / Multiple Subjects` has one UUID and remains under `Subject Area`.
- `Teachers` and `Social` each exist exactly once at top level.
- Social has exactly the six approved children.
- General Education and Classroom Learning Games each exist exactly once under
  Teaching Practice & Theory.
- All eight current Rail Parent versions are published and valid.
- No duplicate included member UUID exists across current published Rail Parent
  Lists.
- The pre-existing seven Lists retain their prior members; only the two
  approved Practice & Theory additions are new.
- The failed disposable first harness attempt left draft version 78; that exact
  draft was removed before the successful apply. Pre-existing views, versions,
  entries, and the Core Terms tree were verified unchanged after cleanup.
- No C3 consumer/navigation code, Jobs behavior, legacy content, Sandy, or
  production state changed.

The exact UUID/membership ledger and before/after snapshots are stored under
`tmp/codex-cycle-sources/` for Workflow V2 evidence.

## Next boundary

The dataset is ready for a separate C3 Rail consumption ticket. That consumer
work must consume the published Lists, preserve Core Terms ancestry, and keep
state/location context separate from global Rail Parent List membership.
