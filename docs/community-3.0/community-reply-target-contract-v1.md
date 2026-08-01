# Community Reply Target Contract v1

`reply_to_post_id` is the canonical explicit target. For current v1 data it is
equal to `parent_post_id`; both are preserved so logical lineage and recipient
intent remain independently auditable. `reply_to_author_id` snapshots the
target author identity at reply time, subject to privacy and moderation policy.

The target must exist, belong to the same `thread_id` and `community_id`, be
eligible for reply, and not be hidden, spam, deleted, restricted, or in a
locked branch. A reply to an L2 target still belongs to that target's L1
`conversation_root_id`. A missing or ambiguous target is rejected or retained
as unresolved during import; it is never silently reassigned.

Rendering shows an explicit “Replying to [safe display name]” link derived from
metadata. If the target is restricted, use “Replying to a removed member” or a
neutral unavailable label. Never copy target text or identity into the reply
body.

Notification systems may use the explicit target to propose the target author
as a candidate, then apply visibility, suppression, subscriber, and abuse
policy. Membership is not consent. No notification implementation is part of
this contract ticket.
