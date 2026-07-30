# CT-JC002 — Instructional Job Corpus Coverage Audit

Status: read-only factual analysis  
Date: 2026-07-30  
Scope: locally available Teachers.Net Job Center listing corpus and current Grade Level / Subject Area coverage

## 1. Corpus and source inventory

The authoritative local listing corpus is the 250-record R001 seed dataset and its persisted Job Center copy. The seed file identifies itself as synthetic fixture content, normalized from J156, with 50 employers and 250 jobs. The persisted database contains those 250 seed listings plus 11 separate `[QA STATE]` workflow records used for lifecycle testing. The QA records have no instructional title or Grade/Subject assignment and are accounted for as evidence-insufficient rather than classified as jobs.

| Corpus component | Records | Evidence status | Treatment |
|---|---:|---|---|
| R001 approved seed dataset | 250 | repository-owned, synthetic, structured Grade/Subject fields | analyzed |
| Persisted copy of seed listings | 250 | `wp_tnet_jobs`, with 4,300 local term rows across three axes | cross-checked |
| Persisted `[QA STATE]` workflow fixtures | 11 | lifecycle-test titles; no instructional evidence | evidence-insufficient |
| Total persisted job rows | 261 | current local database | all accounted for |

The seed dataset supplies title, summary, description, requirements, status, dates, and `core_terms.grade_level` / `core_terms.subject_area`. The persisted copy supplies job status, title, and local numeric term links. The R001 structured labels are the classification evidence used here; no meaning was invented from location, employer, salary, or synthetic prose.

## 2. Cohort definitions and counts

Primary cohorts are mutually exclusive. “Mixed instructional” is a curricular instructional cohort whose structured Grade Level or Subject Area assignment contains more than one value. It is reported separately so multiple-assignment coverage is visible.

| Primary cohort | Count | Share of 261 persisted rows | Definition |
|---|---:|---:|---|
| Curricular instructional, single assignment | 136 | 52.1% | Teaching, tutoring, intervention, coaching, or instructor role with one Grade and one Subject value |
| Mixed instructional | 52 | 19.9% | Curricular instructional role with multiple Grade and/or Subject values |
| Non-curricular education role | 62 | 23.8% | Administration, counseling/student support, or library/media role whose primary identity is not curricular content |
| Insufficient evidence | 11 | 4.2% | `[QA STATE]` workflow fixtures without instructional evidence |
| **Total** | **261** | **100%** | Every persisted row accounted for exactly once |

Within the 250 seed listings, the curricular instructional denominator is 188: 136 single-assignment plus 52 mixed instructional. There were no seed listings requiring an “unclear curricular classification” cohort after title and structured-field cross-checking. The 11 QA rows remain insufficient rather than being treated as instructional or non-curricular jobs.

## 3. Grade Level evidence and frequency

Frequencies below count structured Grade Level assignments, not unique listings. A mixed listing contributes once to each assigned value. All observed values are exact current Core Terms labels and have current UUID matches.

| Observed label | Current Core Terms UUID | Curricular assignment count |
|---|---|---:|
| Elementary | `2563bfe4-1ba6-4c22-b36e-7779ca6ecbc9` | 67 |
| Middle School | `e449211f-95bb-4287-90d3-0c451a81ef4c` | 55 |
| High School | `3993cd02-964b-4d67-ad3d-267dcb0a6a04` | 52 |
| Grade 4 | `073dd227-e6ba-4dda-8159-2e6067519250` | 6 |
| Grade 7 | `5d189ff1-f372-4eec-9ead-82ebf14c24ac` | 5 |
| Adult Education | `213b627b-f788-4df2-9719-c7822481c750` | 5 |
| Higher Education | `7f650a7a-0541-4830-bf10-e1f4ccd1eadf` | 5 |
| Grade 1 | `768511ef-a7e1-44a6-bb9d-304b09a6b1a9` | 4 |
| Grade 2 | `7f248d0d-e897-4d6a-ab04-000fb82f2cf5` | 4 |
| Grade 3 | `64d72915-437a-4f55-9c47-c01001441c18` | 4 |
| Kindergarten | `d6925081-bbe4-4225-ba57-745292e48ddf` | 4 |
| Transitional Kindergarten | `a022460c-8979-4e52-b3a8-fbab727554bd` | 2 |
| Grade 5 | `3061b243-835f-4f56-8b11-40ae9c8249b2` | 1 |
| **Assignments** |  | **219** |

The seed titles explicitly show individual grades in examples such as Grade 1, Grade 2, Grade 3, Grade 4, Grade 5, Grade 7, Kindergarten, and Transitional Kindergarten. Other titles use the structured bands Elementary, Middle School, High School, Adult Education, or Higher Education. No observed curricular Grade Level label lacked an adequate current term.

## 4. Subject Area evidence and frequency

Frequencies below count structured Subject Area assignments, not unique listings. Mixed listings contribute once to each assigned value.

| Observed label | Current Core Terms UUID | Curricular assignment count |
|---|---|---:|
| Mathematics | `69294fa2-c0c4-4a2f-9864-211848c100a` | 49 |
| English Language Arts | `ad2d902b-9f30-4356-b814-229fc60df450` | 32 |
| Reading / Literacy | `52479565-8e1e-4e91-ab96-3c48d05c71b2` | 31 |
| Science | `8f2243d1-8ea7-4547-b44b-88a5456d318b` | 28 |
| Special Education | `5395981b-4c01-46ba-9b2f-c16ba6fd78f2` | 20 |
| Social Studies | `bce06bd1-8a54-4526-a93e-8b5643b17fcd` | 10 |
| English Learners / ESL | `380dd99a-95e2-490d-ac79-b14ef959854a` | 8 |
| Algebra | `9d0ac9a3-767c-412a-9bd2-7f49db764826` | 7 |
| Physical Education / Health | `c8baf6ee-ecc7-437a-84fd-ab1952df4696` | 6 |
| Spanish | `ee999b0b-0ea5-46e8-ac81-f0828de37f59` | 6 |
| History | `20a6b1c0-8435-437f-849f-cf17d221e1e3` | 5 |
| Career Technical Education | `7160ba2a-aac6-4770-9450-d5e122de6762` | 5 |
| Computer Science | `f2c2c7e6-0220-4314-8837-9971f29d35f5` | 4 |
| Biology | `4586519a-09ca-4d86-97c3-40af5d008502` | 3 |
| **Assignments** |  | **219** |

Observed curricular titles include Mathematics, Algebra, English Language Arts, History, Biology, Science, Computer Science, Social Studies, PE/Health, Spanish, Reading Intervention, Special Education, English Learner/Bilingual Education, and Career Technical Education. Every observed structured value is present in the current Subject Area tree. No genuine Subject Area vocabulary gap was demonstrated by this corpus.

## 5. Current-axis coverage metrics

Denominator: 188 curricular instructional seed listings only. Non-curricular roles and the 11 QA workflow fixtures are excluded.

| Metric | Listings | Percentage |
|---|---:|---:|
| Adequate Grade Level classification | 188 | 100.0% |
| Adequate Subject Area classification | 188 | 100.0% |
| Requires multiple Grade and/or Subject assignments | 52 | 27.7% |
| Genuine current-vocabulary gap | 0 | 0.0% |
| Ambiguous from available title/structured evidence | 0 | 0.0% |

“Adequate” here means the seed’s explicit structured value has a current Core Terms UUID and is consistent with the role evidence available in the title and structured data. It does not mean that every assignment is the only possible product classification.

## 6. Coverage matrix

| Representative listing | Cohort | Grade evidence | Subject evidence | Current match | Adequacy | Factual note |
|---|---|---|---|---|---|---|
| `job-001` Middle School Mathematics Teacher | curricular | Middle School | Mathematics | exact UUIDs | clean | Direct teaching role and one value per axis. |
| `job-014` Grade 1 Classroom Teacher - English Language Arts | mixed instructional | Grade 1 | English Language Arts; Mathematics | exact UUIDs | multiple assignment | Classroom title and two structured subjects. |
| `job-032` Grade 4 Classroom Teacher - English Language Arts | mixed instructional | Grade 4 | English Language Arts; Social Studies | exact UUIDs | multiple assignment | Classroom title and two structured subjects. |
| `job-039` Career Technical Education Teacher | curricular | High School | Career Technical Education | exact UUIDs | clean | Current CTE term directly matches structured evidence. |
| `job-088` Bilingual Education Teacher | mixed instructional | Elementary | English Learners / ESL; Spanish | exact UUIDs | multiple assignment | Both structured subjects are current terms. |
| `job-098` Autism Support Specialist Teacher | curricular | Elementary | Special Education | exact UUIDs | broad but adequate | Specialized instructional role; current Special Education term is the supplied classification. |
| `job-168` Grade 5 Math and Science Teacher | mixed instructional | Grade 5 | Mathematics; Science | exact UUIDs | multiple assignment | Title and structured fields agree. |
| `job-193` Community College Mathematics Instructor | curricular | Higher Education | Mathematics | exact UUIDs | clean | Higher Education is a current Grade Level branch. |
| `job-003` School Counselor - High School | non-curricular | High School | Counseling | excluded | excluded | Subject Area is not required for counseling identity. |
| `job-050` School Librarian - Elementary | non-curricular | Elementary; Middle School | Library / Media | excluded | excluded | Library/media role is excluded from curricular teaching coverage denominator. |
| `job-904` `[QA STATE]` Draft - Continue Action | insufficient evidence | none | none | none | gap in evidence, not vocabulary | Workflow fixture, not an instructional listing. |

## 7. Genuine curricular gap candidates

No candidate vocabulary gap was observed in the 188 curricular instructional seed listings. Every structured curricular Grade Level and Subject Area label maps to a current active UUID. The corpus therefore supplies no evidence for adding, renaming, merging, moving, hiding, or retiring a term. This is a coverage result, not vocabulary approval.

## 8. Non-curricular exclusion ledger

These 62 seed listings are recorded to prove why they are outside the two-axis curricular coverage denominator. No alternate taxonomy is proposed.

| Observed role family | Count | Representative evidence | Exclusion basis |
|---|---:|---|---|
| Administration | 13 | `job-004` Principal; `job-019` Assistant Principal | Primary identity is school administration, not teaching a curricular subject. |
| Counseling | 20 | `job-003` School Counselor; `job-089` College Counseling Instructor | Counseling/support identity; “Instructor” in the college-counseling title does not establish curricular content. |
| Student services | 21 | `job-025` Program Coordinator, Student Supports; `job-011` English Learner Program Coordinator | Coordination/support identity rather than a curricular teaching assignment. |
| Library/media | 8 | `job-050` School Librarian | Library/media service identity; no direct curricular subject is established by the title. |
| **Total** | **62** |  | Excluded from Subject Area coverage requirements; Grade evidence is retained as observed corpus data. |

## 9. Ambiguous records requiring Engineering Director review

No seed record met the audit definition of ambiguous after comparing title and structured fields. The following boundary observations are recorded without reclassifying them:

- `Instructional Coach` and `Department Chair` titles are instructional-support or leadership variants, but their structured Grade/Subject values are current and content-specific; they were counted as curricular instructional for coverage purposes.
- `Special Education Resource Specialist`, `Autism Support Specialist Teacher`, and `Bilingual Academic Coach` are specialized instructional titles with current structured assignments; they were not forced into the non-curricular exclusion ledger.
- The 11 `[QA STATE]` records are not ambiguous instructional jobs; they lack enough listing evidence and remain in the insufficient-evidence cohort.

## 10. Exact sources and queries inspected

Repository sources:

- `wordpress/wp-content/plugins/tnet-jobs/data/jobs-seed.json`
- `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-seed-dataset-specification.md`
- `wordpress/wp-content/plugins/tnet-jobs/docs/jobs-seed-job-generation-specification.md`
- `wordpress/wp-content/plugins/profilaxes/docs/teachers-net-core-terms-ctj004-taxonomy-export.json`
- `docs/community-3.0/CT-JC001-release-vocabulary-baseline-audit.md`

Read-only runtime sources and checks:

- `wp_tnet_jobs`: schema, status counts, total count, titles, and IDs.
- `wp_tnet_jobs_terms`: schema, distinct jobs, row counts by `term_axis`.
- `wp_tnet_jobs_form_fields`: active Grade/Subject field definitions.
- `wp_tnet_jobs_form_field_terms`: current field-term definition counts and UUID counts.
- `wp_cfm_framework_versions` and `wp_cfm_terms_compiled`: active vocabulary and UUID cross-check.
- DDEV project `teachers-net` through `ddev wp db query`.

Queries were read-only `SELECT`, `DESCRIBE`, and `SHOW TABLES` statements. The seed analysis used a local JSON parser to count records and structured labels; no source file was rewritten.

## 11. Verification record

- Every one of the 261 persisted rows is in exactly one primary cohort: 136 single curricular, 52 mixed instructional, 62 non-curricular, or 11 insufficient evidence.
- Coverage percentages use only the 188 curricular instructional seed listings as denominator.
- Representative classifications were cross-checked against titles, structured fields, current Core Terms UUIDs, and persisted job-term evidence.
- No database write occurred.
- No implementation, data, fixture, schema, configuration, or cache file changed.
- `git diff --check` and root/nested repository status are to be recorded after this document is added; unrelated working-tree changes remain untouched.
