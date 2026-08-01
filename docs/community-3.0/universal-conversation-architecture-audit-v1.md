# Universal Conversation Architecture Audit v1

Status: read-only architecture audit. No schema, database, UI, migration, or
notification change was made.

## Conclusion

Standalone chatboard replacement topics and future Lesson/Article discussions
should use one Community conversation engine, but the subject owner remains the
owning product. A standalone topic has `subject_type = community_topic` and
`subject_id = topic_post_id`; an attached discussion has a stable external
subject reference. The current post/thread engine remains the v1 sprint engine.

Do not add a universal conversations table before the next data-support ticket.
The smallest durable compatibility adjustment is a subject-reference value
object behind the Community repository, followed by nullable stored fields only
after C3-ARCH002 confirms the exact ownership and indexes.

## Evidence

The current PHP domain creates an opaque `post_id`, a `thread_id`, and nullable
`parent_post_id`; the application owns publication orchestration; the repository
owns transaction/audit/event persistence; and the schema indexes thread/time,
parent, and author/state. C3-ARCH001 establishes exact lineage, future branch
root/target fields, flat L1/L2 rendering, stable reply anchors, and no schema
mutation yet.

Lesson Bank evidence is source/staging-oriented: `source_lesson_id`, immutable
source filename/provenance, parsed lesson records, and later WordPress
integration preparation. It does not provide a verified Community conversation
owner or a safe mutable URL identity. Core Terms classifies; Portable Views
present; neither owns participation or conversation identity. Legacy chatboard
`path_id` remains distinct from teacher-group `group_id`.

## Decision discipline

Required before C3-ARCH002: define subject identity/reference validation,
standalone subject semantics, nullable attachment fields, write-time invariants,
and repository tests. Safe to defer: one/many attached-conversation policy,
feed ranking, recommendations, embedded Portable Views, and notification
delivery. Speculative/excluded: a global relationship graph, universal ranking,
AI conversation synthesis, and cross-product notification transport.
