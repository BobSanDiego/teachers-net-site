# Community 3.0 Engineering Handoff

## 1. Current Phase

Bounded implementation preparation — C3-GO001 authorized preparation; no feature implementation begun.

## 2. Current Ticket

C3-RR001, C3-PLAN003, C3-PLAN004, C3-TRUST001, C3-TRUST002, C3-NOT001, C3-NOT002, and C3-NOT003 are complete as
documentation/read-only evidence work. The accepted subscriber-policy contract
is `docs/community-3.0/subscriber-policy-contract-v1.md`; the accepted
suppression and abuse-control contract is
`docs/community-3.0/suppression-and-abuse-control-contract-v1.md`; the accepted
domain-event and notification contract is
`docs/community-3.0/domain-event-and-notification-contract-v1.md`; the accepted
bell and read-state contract is
`docs/community-3.0/bell-and-read-state-contract-v1.md`; the accepted reply
notification contract is
`docs/community-3.0/reply-notification-contract-v1.md`.
C3-GO001 records GO authorization for bounded implementation preparation. The
The master plan is the product authority above engineering roadmaps and tickets.
C3-NOT004 may be considered after Engineering Director review; it is not
authorized by this handoff.

## 3. Last Completed Milestone

Teacher-group operations were corrected to resolve the canonical group through
`tnet_local_data.local_path -> tnet_groups.local_path ->
tnet_groups.group_id`. The hidden legacy assumption that `path_id` and
`group_id` are interchangeable is no longer used for membership operations.

The correction covered global group state, chatboard modal/settings reads,
group-join mail-frequency lookup, Chat Center member counts, header star,
sidebar membership/count/avatar presentation, and temporary diagnostic cleanup.

## 4. Verification Record

The completed work was reported verified for join, leave, reload persistence,
header and sidebar membership state, member counts, avatars, group settings,
email-frequency persistence, Chat Center counts, the divergent AI in Education
board, and a legacy board control.

## 5. Architectural Caution

`path_id` remains the chatboard/post/feed identity. It must not be used as a
teacher-group or membership identity without an explicit mapping to the
canonical `tnet_groups.group_id`. Downstream templates should prefer the
canonical preloaded group state and retain only bounded fallbacks for partial
legacy execution paths.

## 6. Process Lessons

- Debug from returned server state rather than inferring from a stalled UI.
- Resolve the shared identity assumption instead of treating each symptom as a
  separate defect.
- Compare a divergent record with a legacy control record.
- Remove temporary HTML, logging, comments, and dump/exit diagnostics before
  milestone closure.

## 7. Strategic Documentation Alignment

The current Community 3.0 roadmap is
`docs/community-3.0/roadmap.md`. It captures the shift from application-centric
planning toward a semantic platform whose subscribers consume Core Terms,
Portable Views, Subscriber Policies, Relationship Graphs, and the
Communications Platform without surrendering product authority.

The next planning sequence is authority alignment, Core Terms/meta-term audit,
subscriber contracts, Portable View governance, a bounded Job Center View
pilot, chatboard/group mapping, interest and onboarding evidence, communications
consent and event architecture, relationship approval, explainable
recommendation proof, and only then product-by-product subscriber expansion.

Semantic Studio remains a planning concept for a future governance surface; it
is not an approved implementation. Open decisions and stop conditions remain
in the roadmap and the execution-plan companion.

The synchronized Google Drive handoff is:
<https://docs.google.com/document/d/1oxqqgFHkPwrJQpQ563-hho0jPf_MWrTEPE_qCJa-BeY>

## 8. Recently Approved Visual References

None. The current milestone is the identity correction and documentation
alignment; no new visual authority is established by this handoff.

## 9. Active Design Authority

The canonical Semantic, Community, and Communications Platform working draft,
the Semantic Platform Project Approach and Execution Plan, the Community 3.0
roadmap, and the teacher-group identity correction record. Preserve the
distinction between verified implementation, approved direction, proposed
design, exploratory concept, and deferred work.

## 10. Immediate Engineering Priorities

Review the C3-NOT003 contract and decide whether to authorize C3-NOT004.
Any follow-up evidence gathering remains read-only. Stop before mail sending,
schema changes, preference migration, delivery enablement, production edits,
relationship activation, or unrelated Job Center implementation.
