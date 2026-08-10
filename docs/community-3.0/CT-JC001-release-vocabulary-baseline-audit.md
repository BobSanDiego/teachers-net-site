# CT-JC001 — Release Vocabulary Baseline Audit

Status: read-only factual baseline  
Date: 2026-07-30  
Scope: current Teachers.Net Core Terms Grade Level and Subject Area records and Job Center consumers

## 1. Executive factual summary

The authoritative local vocabulary is the Teachers.Net Core Terms framework, whose canonical tree is stored in `wp_cfm_framework_versions.tree_json` and whose active compiled records are in `wp_cfm_terms_compiled`. The preserved CTJ004 export identifies framework `teachers-net`, framework ID `1`, active version ID `1`, and the two relevant axis UUIDs:

| Axis | Stable ID | Active runtime rows | Export rows | Runtime status |
|---|---|---:|---:|---|
| Grade Level | `2c09a868-532a-4e67-a99d-4a8aa44c084c` | 23 | 23 | active |
| Subject Area | `8b7ec968-acba-4edd-894c-495e4f30e1cd` | 22 | 23 in preserved export; 22 in active canonical/runtime | active |

The active canonical tree and active compiled runtime agree for the two axes: labels, UUIDs, parentage, slugs, and membership agree. The preserved CTJ004 export is not a current-authority match for Subject Area: it contains one additional historical record, `Crafts and such`, that is absent from active `tree_json` and active compiled rows. The export does not carry the runtime `sort_order` values; ordering was verified from `wp_cfm_terms_compiled`. The current active axes contain 23 Grade Level records and 22 Subject Area records, including roots. The remaining 55 active Core Terms records belong to the separate Location axis and are outside this ticket.

Job Center consumes the axes dynamically through its Core Terms adapter and form-field term service, while preserving the stable UUID in `wp_tnet_jobs_form_field_terms.core_terms_term_uuid`. Public selectors, admin selectors, CSV import, seed import, alert matching, and alert delivery use the field keys `grade_level` and `subject_area`. The development UX fixture contains a separate label-only fallback (`9-12`, `3-5`, `Math`, `Science`); it is not a UUID-backed vocabulary source.

This document is a baseline only. It does not select a final employer vocabulary, resolve historical labels, or propose changes.

## 2. Current Grade Level Tree

All rows below are active runtime records. Indentation is parentage. Format: **label** — `UUID`; `slug`; short label. The axis root is included.

- **Grade Level** — `2c09a868-532a-4e67-a99d-4a8aa44c084c`; `grade-level`; Grade Level
  - **Early Childhood** — `c39cfa9b-8f5a-4017-b6dc-eb75d1aa333d`; `early-childhood`; Early Childhood
    - **Early Learners** — `c857bef6-d5d1-448c-8cdc-a918940fdf59`; `early-learners`; Early Learners
    - **Pre-K** — `55de542b-860e-4efb-be29-d6c397565f15`; `pre-k`; Pre-K
    - **Transitional Kindergarten** — `a022460c-8979-4e52-b3a8-fbab727554bd`; `transitional-kindergarten`; TK
    - **Kindergarten** — `d6925081-bbe4-4225-ba57-745292e48ddf`; `kindergarten`; Kindergarten
  - **Elementary** — `2563bfe4-1ba6-4c22-b36e-7779ca6ecbc9`; `elementary`; Elementary
    - **Grade 1** — `768511ef-a7e1-44a6-bb9d-304b09a6b1a9`; `grade-1`; Grade 1
    - **Grade 2** — `7f248d0d-e897-4d6a-ab04-000fb82f2cf5`; `grade-2`; Grade 2
    - **Grade 3** — `64d72915-437a-4f55-9c47-c01001441c18`; `grade-3`; Grade 3
    - **Grade 4** — `073dd227-e6ba-4dda-8159-2e6067519250`; `grade-4`; Grade 4
    - **Grade 5** — `3061b243-835f-4f56-8b11-40ae9c8249b2`; `grade-5`; Grade 5
  - **Middle School** — `e449211f-95bb-4287-90d3-0c451a81ef4c`; `middle-school`; Middle School
    - **Grade 6** — `f167d17f-6d99-4341-9a77-7dda8cc76fd5`; `grade-6`; Grade 6
    - **Grade 7** — `5d189ff1-f372-4eec-9ead-82ebf14c24ac`; `grade-7`; Grade 7
    - **Grade 8** — `1bd6b70d-d58a-4bd9-afae-7826fe738ee0`; `grade-8`; Grade 8
  - **High School** — `3993cd02-964b-4d67-ad3d-267dcb0a6a04`; `high-school`; High School
    - **Grade 9** — `e33d88d1-19bf-4380-b55a-561c37005f18`; `grade-9`; Grade 9
    - **Grade 10** — `50b7c944-7432-4e1b-bbbb-5d2261466aeb`; `grade-10`; Grade 10
    - **Grade 11** — `2bd5ced9-81e7-4b2a-b1e7-74f43ee7a69f`; `grade-11`; Grade 11
    - **Grade 12** — `f190288d-7878-448e-85d0-ce3d431078f7`; `grade-12`; Grade 12
  - **Adult Education** — `213b627b-f788-4df2-9719-c7822481c750`; `adult-education`; Adult Ed
  - **Higher Education** — `7f650a7a-0541-4830-bf10-e1f4ccd1eadf`; `higher-education`; Higher Education

The export records the grade hierarchy and labels but does not include aliases. The runtime compiled rows provide the corresponding short labels and ordering metadata. No separate alias table or active alias field was found in the inspected Core Terms schema.

## 3. Current Subject Area Tree

All rows below are active runtime records. Indentation is parentage. Format: **label** — `UUID`; `slug`; short label.

- **Subject Area** — `8b7ec968-acba-4edd-894c-495e4f30e1cd`; `subject-area`; Subject Area
  - **English Language Arts** — `ad2d902b-9f30-4356-b814-229fc60df450`; `english-language-arts`; ELA
  - **Mathematics** — `69294fa2-c0c4-4a2f-9864-211848c100a`; `mathematics`; Math
    - **Algebra** — `9d0ac9a3-767c-412a-9bd2-7f49db764826`; `algebra`; Algebra
  - **Science** — `8f2243d1-8ea7-4547-b44b-88a5456d318b`; `science`; Science
    - **Biology** — `4586519a-09ca-4d86-97c3-40af5d008502`; `biology`; Biology
  - **Social Studies** — `bce06bd1-8a54-4526-a93e-8b5643b17fcd`; `social-studies`; Social Studies
    - **History** — `20a6b1c0-8435-437f-849f-cf17d221e1e3`; `history`; History
  - **Art** — `6d407f6b-80a5-4f69-b334-9bf8d0c9b40e`; `art`; Art
  - **Music** — `a323e85c-4a34-440c-8df4-80ec5d185bee`; `music`; Music
  - **General / Multiple Subjects** — `a2990cb3-d1c7-4cdf-9f92-dde31d521e5d`; `general-multiple-subjects`; Gen/Mult Subj
  - **Special Education** — `5395981b-4c01-46ba-9b2f-c16ba6fd78f2`; `special-education`; Special Education
  - **English Learners / ESL** — `380dd99a-95e2-490d-ac79-b14ef959854a`; `english-learners-esl`; ESL/ELL
  - **World Languages** — `019e13ed-b304-4897-8029-b4116d11989d`; `world-languages`; World Languages
    - **Spanish** — `ee999b0b-0ea5-46e8-ac81-f0828de37f59`; `spanish`; Spanish
  - **Technology** — `8a619941-e623-4eec-9545-3678f5798830`; `technology`; Technology
    - **Computer Science** — `f2c2c7e6-0220-4314-8837-9971f29d35f5`; `computer-science`; Computer Science
  - **Career Technical Education** — `7160ba2a-aac6-4770-9450-d5e122de6762`; `career-technical-education`; CTE
  - **Physical Education / Health** — `c8baf6ee-ecc7-437a-84fd-ab1952df4696`; `physical-education-health`; PE/Health
  - **Counseling** — `2e0ea2f5-8d10-4bfb-8f3e-6cd1cfa146c8`; `counseling`; Counseling
  - **Reading / Literacy** — `52479565-8e1e-4e91-ab96-3c48d05c71b2`; `reading-literacy`; Reading/Lit
  - **Library / Media** — `816599cb-c627-4ad8-a3b2-ae5f2fb590c8`; `library-media`; Library/Media

The active canonical/runtime Subject Area tree contains the nested leaves Algebra, Biology, History, Spanish, and Computer Science. The preserved CTJ004 export additionally contains `Crafts and such`, but that record is historical/stale relative to the active canonical/runtime source and is not part of the current Subject Area tree.

## 4. Job Center dependency ledger

| Dimension / branch | Core Terms ID | Job Center usage | Existing evidence | Change sensitivity | Factual note |
|---|---|---|---|---|---|
| Grade Level axis | `2c09a868-532a-4e67-a99d-4a8aa44c084c` | Core Terms adapter, public/admin selectors, ordered descendant loading | `class-tnet-jobs-core-terms-adapter.php`; `class-tnet-jobs-form-field-term-service.php`; public controller | High | Axis is discovered by UUID and label; no local replacement tree found. |
| Subject Area axis | `8b7ec968-acba-4edd-894c-495e4f30e1cd` | Same dynamic selector and descendant paths as Grade Level | Same adapter/service and public controller | High | Axis is discovered by UUID and label; no local replacement tree found. |
| Grade/subject form fields | field keys `grade_level`, `subject_area` | Employer authoring, admin editing, CSV import, seed import | `class-tnet-jobs-csv-import-service.php`; `class-tnet-jobs-seed-import-service.php`; `bulk-import-spec.md` | High | Import aliases include `grade`, `grade_level`, `grades` and `subject`, `subject_area`, `subjects`. |
| Persisted field-term links | per-row `core_terms_term_uuid` | Query/filter and term assignment | `wp_tnet_jobs_form_field_terms`; repository and schema classes | High | Runtime count: 229 rows, 103 distinct UUID values across the whole table. |
| Job term links | local `job_term_id`, `term_id`, `term_axis` | Job classification storage and reads | `wp_tnet_jobs_terms`; Jobs schema/repository | High | This table uses local numeric term IDs plus an axis; its UUID mapping was not inferred. |
| Alerts | `grade_level`, `subject_area` | Alert matching and delivery | `class-tnet-jobs-alert-matching-service.php`; `class-tnet-jobs-alert-delivery-service.php` | High | Alert code consumes the field keys, not a second vocabulary list. |
| Development UX fixture | none; labels only | Synthetic recruiter fixture | `class-tnet-jobs-recruiter-ux-fixture-command.php` lines 1066–1067 | Medium | Fallback labels are `9-12`, `3-5`, `Math`, `Science`; they are not current UUID-backed Core Terms records. |

The inspected implementation reads the current Core Terms tree rather than importing a bounded hard-coded list. The seed dataset repeatedly contains `grade_level` and `subject_area` arrays; the preserved CTJ004 documentation records 250/250 seed jobs mapping to Grade and Subject. The seed labels and fixture labels were not treated as authoritative IDs.

## 5. Duplicate, conflict, and gap observations

- A material conflict was found between the preserved CTJ004 export and the active DDEV database for Subject Area: the export contains `Crafts and such`; active canonical/runtime sources do not.
- The JSON export contains canonical labels, UUIDs, hierarchy, and slugs but no explicit alias collection and no explicit sort values. Runtime ordering and short labels therefore come from compiled rows.
- The fixture’s `9-12` and `3-5` labels do not correspond one-to-one with the current Grade Level tree as named records; this is a label-only fixture observation, not a proposed mapping.
- `Math` and `Science` appear as fixture short labels, while the canonical labels are `Mathematics` and `Science`. `Math` is the runtime short label for Mathematics.
- The database contains pre-CTJ002, pre-CTJ004, and pre-CTJ006 snapshot versions. They are historical runtime records and were not treated as current vocabulary.
- No active duplicate UUID, orphaned parent, inactive row, or separate active Grade/Subject list was found in the active canonical/runtime sources.
- Historical labels, imported job values, and the 103 distinct UUID references in the Jobs form-term table require record-level classification if a later task needs a migration or compatibility decision. This audit does not infer those mappings.

## 6. IDs and records not to disturb without explicit approval

Do not alter the active framework/version identity (`framework_id=1`, active version `id=1`, version 1), either axis UUID, any UUID listed in the two trees, parent UUID, slug, or `core_terms_term_uuid` persisted in Job Center records. Do not rewrite or delete the pre-CTJ snapshot rows while they remain part of the local preservation evidence. Do not replace the fixture’s label fallback as part of this baseline.

The export identifies `framework_versions.tree_json` as the canonical source and describes compiled tables as rebuildable while user assignments are stored by stable UUID. That distinction makes the UUIDs and their persisted Job Center references the sensitive records in this audit.

## 7. Unresolved evidence and next inspection boundary

The following were not resolved because doing so would require a separate migration or historical-data audit:

- exact label-to-UUID mappings for every existing Job Center job, alert, and legacy imported record;
- whether every local numeric `wp_tnet_jobs_terms.term_id` maps to a current Core Terms UUID;
- historical production-only vocabularies not present in the local export, local database, or inspected repository files;
- whether the fixture fallback is still used by any runtime path beyond the development command;
- any external production reference not represented in the local clone.

The safe next inspection boundary is a read-only record-level crosswalk of the existing Job Center term references against the two current axis UUID sets. No term decision or data rewrite is implied by that boundary.

## 8. Exact sources inspected

Repository sources:

- `wordpress/wp-content/plugins/profilaxes/docs/teachers-net-core-terms-ctj004-taxonomy-export.json`
- `wordpress/wp-content/plugins/profilaxes/docs/teachers-net-core-terms-ctj004-preservation.md`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-capability-snapshot.md`
- `wordpress/wp-content/plugins/profilaxes/docs/core-terms-integration-contract.md`
- `wordpress/wp-content/plugins/tnet-jobs/includes/integrations/class-tnet-jobs-core-terms-adapter.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/class-tnet-jobs-form-field-term-service.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/class-tnet-jobs-csv-import-service.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/class-tnet-jobs-seed-import-service.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/class-tnet-jobs-alert-matching-service.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/services/class-tnet-jobs-alert-delivery-service.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/dev/class-tnet-jobs-recruiter-ux-fixture-command.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/class-tnet-jobs-schema.php`
- `wordpress/wp-content/plugins/tnet-jobs/includes/repositories/class-tnet-jobs-form-field-term-repository.php`
- `wordpress/wp-content/plugins/tnet-jobs/data/jobs-seed.json`
- `wordpress/wp-content/plugins/tnet-jobs/docs/bulk-import-spec.md`
- `docs/core-terms/project-cursor.md`
- `docs/core-terms/engineering-handoff.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/project-cursor.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/job-center/canonical-v1-contract.md`

Read-only runtime sources and checks:

- DDEV project `teachers-net`, `ddev describe`.
- `wp_cfm_frameworks`, `wp_cfm_framework_versions`, and `wp_cfm_terms_compiled`.
- `wp_tnet_jobs_form_field_terms`, `wp_tnet_jobs_terms`, and the `wp_tnet_jobs%` table inventory.
- Read-only `SELECT`, `DESCRIBE`, and `SHOW TABLES` queries only; no database write command was run.

## Verification record

- No implementation, data, schema, fixture, configuration, or cache file was changed.
- No database write was performed.
- The two trees contain every active term in the requested Grade Level and Subject Area axes: 23 and 22 records respectively, including each axis root.
- Active canonical `tree_json` and active compiled runtime IDs were cross-checked by UUID, label, parentage, slug, and axis membership. The preserved export discrepancy is recorded above.
- `git diff --check` and root/nested repository status are to be recorded after this document is added; unrelated working-tree changes remain outside this audit.
