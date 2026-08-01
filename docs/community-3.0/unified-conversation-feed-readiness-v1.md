# Unified Conversation Feed Readiness v1

Standalone and attached conversations can share a read model if each activity
record carries `conversation_id`, `subject_type`, `subject_id`, `owner_product`,
`community_id`, actor, visibility state, event time, and stable source post
identity. Feed generation must resolve subject access before display.

Grade, subject, contributor, follows/friends, and Core Terms relationships may
be future filters or ranking inputs, but they are not conversation identity and
must not create notification consent. A chronological baseline is sufficient
for v1. Recommendation, personalization, and cross-product ranking are
deferred.

Notification events should be conversation-centric facts with product-owned
policy adapters. Thread mute and target mute/block suppress candidates after
visibility evaluation. Subscriber policies, suppression, and delivery remain
separate; no delivery implementation is authorized by this audit.
