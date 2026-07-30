# CT-JC004R — False Core Term Source Diagnostic

Status: diagnostic and documentation reconciliation  
Date: 2026-07-30  
Authority: Engineering Director-supplied current Subject Area output; active local canonical/runtime evidence

## 1. Executive conclusion

`Crafts and such` is not a current Core Terms Subject Area record. It came from the preserved CTJ004 taxonomy export and the historical/snapshot framework trees, not from the active canonical `framework_versions.tree_json` or active compiled runtime rows.

CT-JC001 incorrectly stated that the preserved export and active runtime contained the same Subject Area records. That statement caused the term to appear in the CT-JC001 tree and then in CT-JC004 as a compatibility-only current term. CT-JC001 and CT-JC004 have been corrected. No Core Terms or Job Center data was changed.

## 2. Authoritative current Subject Area inventory

The active Subject Area axis is `8b7ec968-acba-4edd-894c-495e4f30e1cd`. The active canonical tree and active compiled rows contain these 22 records:

| Depth | Label | UUID | Parent | Slug | Active |
|---:|---|---|---|---|---:|
| 0 | Subject Area | `8b7ec968-acba-4edd-894c-495e4f30e1cd` | — | `subject-area` | 1 |
| 1 | English Language Arts | `ad2d902b-9f30-4356-b814-229fc60df450` | Subject Area | `english-language-arts` | 1 |
| 1 | Mathematics | `69294fa2-c0c4-4a2f-9864-211848c100a` | Subject Area | `mathematics` | 1 |
| 2 | Algebra | `9d0ac9a3-767c-412a-9bd2-7f49db764826` | Mathematics | `algebra` | 1 |
| 1 | Science | `8f2243d1-8ea7-4547-b44b-88a5456d318b` | Subject Area | `science` | 1 |
| 2 | Biology | `4586519a-09ca-4d86-97c3-40af5d008502` | Science | `biology` | 1 |
| 1 | Social Studies | `bce06bd1-8a54-4526-a93e-8b5643b17fcd` | Subject Area | `social-studies` | 1 |
| 2 | History | `20a6b1c0-8435-437f-849f-cf17d221e1e3` | Social Studies | `history` | 1 |
| 1 | Art | `6d407f6b-80a5-4f69-b334-9bf8d0c9b40e` | Subject Area | `art` | 1 |
| 1 | Music | `a323e85c-4a34-440c-8df4-80ec5d185bee` | Subject Area | `music` | 1 |
| 1 | General / Multiple Subjects | `a2990cb3-d1c7-4cdf-9f92-dde31d521e5d` | Subject Area | `general-multiple-subjects` | 1 |
| 1 | Special Education | `5395981b-4c01-46ba-9b2f-c16ba6fd78f2` | Subject Area | `special-education` | 1 |
| 1 | English Learners / ESL | `380dd99a-95e2-490d-ac79-b14ef959854a` | Subject Area | `english-learners-esl` | 1 |
| 1 | World Languages | `019e13ed-b304-4897-8029-b4116d11989d` | Subject Area | `world-languages` | 1 |
| 2 | Spanish | `ee999b0b-0ea5-46e8-ac81-f0828de37f59` | World Languages | `spanish` | 1 |
| 1 | Technology | `8a619941-e623-4eec-9545-3678f5798830` | Subject Area | `technology` | 1 |
| 2 | Computer Science | `f2c2c7e6-0220-4314-8837-9971f29d35f5` | Technology | `computer-science` | 1 |
| 1 | Career Technical Education | `7160ba2a-aac6-4770-9450-d5e122de6762` | Subject Area | `career-technical-education` | 1 |
| 1 | Physical Education / Health | `c8baf6ee-ecc7-437a-84fd-ab1952df4696` | Subject Area | `physical-education-health` | 1 |
| 1 | Counseling | `2e0ea2f5-8d10-4bfb-8f3e-6cd1cfa146c8` | Subject Area | `counseling` | 1 |
| 1 | Reading / Literacy | `52479565-8e1e-4e91-ab96-3c48d05c71b2` | Subject Area | `reading-literacy` | 1 |
| 1 | Library / Media | `816599cb-c627-4ad8-a3b2-ae5f2fb590c8` | Subject Area | `library-media` | 1 |

## 3. Exact current row counts

| Measure | Count |
|---|---:|
| Active Subject Area rows including axis root | 22 |
| Active Subject Area terms excluding axis root | 21 |
| Active Grade Level rows including axis root | 23 |
| Active Grade Level terms excluding axis root | 22 |
| Active `teachers-net` framework version | version 1, database ID 1 |

The Engineering Director-supplied current output agrees with the active local canonical/runtime result: Art is followed by Music, with no Crafts record.

## 4. Occurrence ledger for “Crafts and such”

| Occurrence | Exact source | Status | UUID / path | Current Job Center use | Current Core Term? |
|---|---|---|---|---|---|
| Canonical export text | `wordpress/wp-content/plugins/profilaxes/docs/teachers-net-core-terms-ctj004-taxonomy-export.json` | preserved CTJ004 export; stale relative to active source | `78cde0d3-12c9-42c0-aa9f-21c0ea0ee800`; Subject Area → Art child | none found in current job-term rows | No |
| Downloaded export | `/mnt/c/Users/bobre/Downloads/profilaxes-taxonomy-teachers-net-20260707-134544.json` | older export artifact | same UUID; same label/slug | not a current DB source | No |
| Historical framework version 2 | `wp_cfm_framework_versions.id=3`, `pre_ctj002_snapshot` | historical snapshot | JSON path `children[1].children[4].children[0]` | none found | No |
| Historical framework version 3 | `id=4`, `pre_ctj004_snapshot` | historical snapshot | same | none found | No |
| Historical framework version 4 | `id=5`, `pre_ctj004_snapshot` | historical snapshot | same | none found | No |
| Historical framework version 5 | `id=6`, `pre_ctj006_snapshot` | historical snapshot | same | none found | No |
| Historical framework version 6 | `id=7`, `pre_ctj006_snapshot` | historical snapshot | same | none found | No |
| CT-JC001 | repository report lines previously listing the record | unsupported current-runtime claim | inherited from preserved export | none | No |
| CT-JC004 | repository analysis rows previously classifying it | derived contamination | inherited from CT-JC001 | none | No |

The exact slug `crafts-and-such` occurs in the same preserved export and historical JSON trees. After correction, repository occurrences should remain only in this diagnostic, the correction note in CT-JC001, and historical archive evidence if retained; no current inventory should list it.

## 5. Canonical-tree versus compiled-runtime comparison

Read-only `JSON_SEARCH` against every framework version found the term in versions 2–6 and did not find it in active version 1. The active compiled query returned 22 Subject Area rows and did not return the UUID, label, or slug. The active canonical tree and active compiled rows therefore agree with each other and with the Engineering Director output.

The preserved CTJ004 export metadata says `active_version_id=1`, but its tree content contains the older/historical Crafts branch. Its metadata was not sufficient evidence that its payload matched the current active canonical tree.

## 6. Historical and snapshot findings

The term is historical/stale, not fabricated. It appears in every inspected pre-CTJ002, pre-CTJ004, and pre-CTJ006 snapshot version. The active version was later compiled without it. The repository’s preserved export retained the older branch while labeling itself as a CTJ004 preservation export, which created the source ambiguity.

## 7. Job Center reference findings

The Job Center form-field term table contains two archived definitions carrying the UUID: field-term IDs 219 and 222, both with `archived_at` values and `is_visible=0`. No current Job Center job-term row references the corresponding numeric term IDs 9780 or 23334; the read-only count was zero. No current active Job Center record was found using the term.

## 8. Root cause of the incorrect CT-JC001 claim

The claim was produced by treating the preserved CTJ004 export as current canonical content and then asserting that it matched the active runtime. The active runtime comparison was not actually reconciled against the active `tree_json` before the report stated equality. The export retained a historical Crafts child; the active canonical/runtime source did not. CT-JC001 therefore promoted a stale export-only record into its “current” tree and count.

## 9. Contamination assessment for CT-JC004

CT-JC004 inherited three unsupported current-term references: its comparison row, its compatibility-only classification row, and its grouped decision-matrix row. Those rows have been removed. The six broad candidate additions, Grade Level analysis, exclusions, future-axis observations, and other Subject Area comparisons do not depend on Crafts and remain unchanged.

## 10. Required documentation corrections

- CT-JC001 now reports 22 current Subject Area rows including the root and 21 excluding it; it distinguishes the stale preserved export from active canonical/runtime evidence.
- CT-JC001 no longer lists Crafts in the current Subject Area tree and records the export/runtime discrepancy.
- CT-JC004 no longer treats Crafts as a current term or compatibility decision.
- This CT-JC004R document is the reconciliation authority for the discrepancy.
- No Core Terms, Job Center, schema, fixture, UUID, hierarchy, or database record was changed.

## 11. Verification record

- Active framework identity and active version were inspected read-only.
- Active canonical `tree_json` and active compiled Subject Area rows agree at 22 rows.
- Historical versions and both preserved exports were inspected.
- Job Center form-field and job-term references were inspected read-only.
- Repository search was rerun for both `Crafts and such` and `crafts-and-such`; remaining occurrences are historical evidence, this diagnostic, or the ticket/source material, not current vocabulary.
- `git diff --check` was run.
- No database write or implementation change occurred; nested `tnet-jobs` was not modified.
