# CT-JC006 — Job Center V1 Vocabulary Baseline

Status: frozen documentation baseline  
Date: 2026-07-30  
Authority: Engineering Director decision recorded by CT-JC006

## 1. Executive decision

The Teachers.Net Job Center V1 Grade Level and Subject Area vocabulary baseline is frozen. No evidence-based Grade Level changes are approved for V1. No evidence-based Subject Area changes are approved for V1. The active canonical Core Terms vocabulary is preserved without taxonomy, UUID, hierarchy, alias, or implementation changes.

The external evidence identified Business Education, Dance, Theater/Drama, Agriculture, and Family & Consumer Science/Home Economics as possible future categories. They are deferred to a watch list pending substantial Teachers.Net employer usage or broad independent employer-market evidence. Their appearance in prior analysis does not authorize adding them to Core Terms.

## 2. Approved Grade Level baseline

- Grade Level
  - Early Childhood
    - Early Learners
    - Pre-K
    - Transitional Kindergarten
    - Kindergarten
  - Elementary
    - Grade 1
    - Grade 2
    - Grade 3
    - Grade 4
    - Grade 5
  - Middle School
    - Grade 6
    - Grade 7
    - Grade 8
  - High School
    - Grade 9
    - Grade 10
    - Grade 11
    - Grade 12
  - Adult Education
  - Higher Education

Current active count: 23 records including the axis root, 22 excluding the root. Stable UUIDs and hierarchy remain governed by the active canonical/runtime source documented in CT-JC001 and CT-JC004R.

## 3. Approved Subject Area baseline

- Subject Area
  - English Language Arts
  - Mathematics
    - Algebra
  - Science
    - Biology
  - Social Studies
    - History
  - Art
  - Music
  - General / Multiple Subjects
  - Special Education
  - English Learners / ESL
  - World Languages
    - Spanish
  - Technology
    - Computer Science
  - Career Technical Education
  - Physical Education / Health
  - Counseling
  - Reading / Literacy
  - Library / Media

Current active count: 22 records including the axis root, 21 excluding the root. `Crafts and such` is not part of this baseline; CT-JC004R established that it exists only in the preserved export and historical snapshots, not in the active canonical tree or active compiled runtime rows.

## 4. Validation summary

| Validation area | Result |
|---|---|
| CT-JC001 | Active canonical/runtime counts and records reconciled; stale export discrepancy corrected by CT-JC004R. |
| CT-JC002 | 188 curricular instructional seed listings had adequate Grade Level and Subject Area coverage; no demonstrated current vocabulary gap. |
| CT-JC003 | External vocabulary collected without treating certification or marketplace labels as automatic Teachers.Net taxonomy. |
| CT-JC004 | No Grade Level or current Subject Area change required; broad candidates identified but not approved. |
| CT-JC004R | Confirmed active canonical/runtime agreement and removed the unsupported current-term claim. |
| CT-JC005 | Business Education, Dance, and Theater/Drama showed stronger employer recurrence; Agriculture and Family/Consumer Science remained regional/niche on collected evidence. |

## 5. Deferred watch list

- Business Education
- Dance
- Theater / Drama
- Agriculture
- Family & Consumer Science / Home Economics

This is a watch list, not a pending taxonomy change. No category should be added, renamed, moved, merged, hidden, retired, or assigned a UUID based solely on this list.

## 6. Future admission criteria

Before a deferred category can be considered for a future Core Terms release, evidence should demonstrate one or both of the following:

1. substantial, repeated Teachers.Net employer usage across actual listings; or
2. broad independent employer-market evidence across multiple regions and institution types, beyond certification catalogs or a single marketplace’s exhaustive filter list.

Future review should also verify that the candidate describes curricular content rather than a role, support function, credential, program, or institution-specific curriculum. Any approved change requires a separate decision and compatibility review; this frozen baseline does not authorize implementation.

## 7. Verification record

- CT-JC001 through CT-JC005 were reconciled, including CT-JC004R’s correction.
- No taxonomy, UUID, hierarchy, alias, implementation, schema, fixture, or database change was made.
- No roadmap or Google Drive document was updated.
- `git diff --check` was run before commit.
