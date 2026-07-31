# Community 3.0 Engineering Handoff

## 1. Current Phase

Bounded implementation preparation — C3-CORE001 complete; no delivery implementation begun.

## 2. Current Ticket

C3-RR001, C3-PLAN003, C3-PLAN004, C3-TRUST001, C3-TRUST002, C3-NOT001, C3-NOT002, C3-NOT003, and C3-NOT004 are complete as
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
`docs/community-3.0/reply-notification-contract-v1.md`; the accepted reaction
notification contract is
`docs/community-3.0/reaction-notification-contract-v1.md`.
The accepted group-activity notification contract is
`docs/community-3.0/group-activity-notification-contract-v1.md`.
The C3-IMP001 readiness, gap, and sequence documents are
`docs/community-3.0/notification-implementation-readiness-v1.md`,
`docs/community-3.0/implementation-gap-analysis-v1.md`, and
`docs/community-3.0/notification-implementation-sequence-v1.md`. They identify
the missing Mention Notification Contract and propose C3-IMP002 as a test-only
dry-run evaluator, pending explicit authorization.
The C3-IMP003 candidate/audit boundary is implemented in
`tools/community3/notification_candidate_boundary.py`, covered by
`tools/community3/test_notification_candidate_boundary.py`, and documented in
`docs/community-3.0/candidate-audit-boundary-v1.md`. It is test-only,
non-persistent, redacted, and non-delivering.
The C3-IMP004 process-local candidate store and continuity flush correction are
implemented in `tools/community3/notification_candidate_store.py` and
`tools/hopper/validate_community_continuity.py`, with documentation in
`docs/community-3.0/candidate-store-interface-v1.md` and
`docs/community-3.0/continuity-flush-diagnostic-v1.md`.
C3-IMP002 has no verified implementation commit and is not complete. C3-NOT005
has no verified contract or implementation commit and is not complete.
C3-IMP005 is complete as a test-only in-memory bell repository at
`tools/community3/notification_bell_repository.py`, covered by
`tools/community3/test_notification_bell_repository.py`, and documented in
`docs/community-3.0/bell-repository-interface-v1.md`. It consumes only eligible
validated candidates and preserves bell state separately from delivery and
engagement.
The C3-IMP006 end-to-end dry-run pipeline is implemented in
`tools/community3/notification_dry_run_pipeline.py`, covered by
`tools/community3/test_notification_dry_run_pipeline.py`, and documented in
`docs/community-3.0/dry-run-notification-pipeline-v1.md`. It remains test-only,
non-persistent, and non-delivering.
The C3-IMP007 application service is implemented in
`tools/community3/notification_application_service.py`, covered by
`tools/community3/test_notification_application_service.py`, and documented
in `docs/community-3.0/notification-application-service-v1.md`. It is the sole
public entry point for the current synthetic `group_post` dry run.
C3-IMP007 is complete as the current source milestone; no delivery behavior is
authorized.
The C3-IMP008 fixture-backed group-post adapter is implemented in
`tools/community3/group_post_event_adapter.py`, covered by
`tools/community3/test_group_post_event_adapter.py`, and documented in
`docs/community-3.0/group-post-event-adapter-v1.md`. It remains disconnected
from live hooks and production data.
C3-IMP008 is complete as the current source milestone; no additional event
family or delivery behavior is authorized.
The C3-IMP009 shadow seam is implemented in
`tools/community3/group_post_shadow_hook.py`, covered by
`tools/community3/test_group_post_shadow_hook.py`, and documented in
`docs/community-3.0/group-post-shadow-hook-v1.md`. Repository inspection found
no owned real Community publication hook, so this remains a test-owned model
and is not connected to production.
C3-IMP009 is complete as the current source milestone; no live publication hook
or delivery behavior is authorized.
The C3-IMP010 publication integration audit found no owned authoritative
Community publisher seam. Its integration map and hook-authority decision are
`docs/community-3.0/community-publication-integration-map-v1.md` and
`docs/community-3.0/publication-hook-authority-v1.md`. The C3-IMP009 seam
remains test-owned and non-authoritative.
C3-IMP010 is complete as the current source milestone; no live publisher hook
or delivery behavior is authorized.
C3-CORE001 identified the Sandy legacy Perl publisher at
`/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi`. It exists in
production but is not owned or available in this repository and does not expose
canonical group mapping. Live notification attachment remains blocked. The
source census, lifecycle, authority decision, and minimum contract are the
four C3-CORE001 documents.
OPS-CONT001 is complete as a continuity and hopper process audit. The durable
governance and validation documents are
`docs/process/community-continuity-root-cause-v1.md`,
`docs/process/community-hopper-governance-v1.md`, and
`docs/process/community-continuity-validation-v1.md`.
C3-GO001 records GO authorization for bounded implementation preparation. The
master plan is the product authority above engineering roadmaps and tickets.

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

Review C3-CORE001 and decide whether to authorize source ownership and a
read-only compatibility boundary before any live hook integration or
persistence slice.
persistence or channel slice. Keep delivery disabled. Stop before mail sending,
preference migration, delivery enablement, production edits, relationship
activation, or unrelated Job Center implementation.
Any follow-up evidence gathering remains read-only. Stop before mail sending,
schema changes, preference migration, delivery enablement, production edits,
relationship activation, or unrelated Job Center implementation.
