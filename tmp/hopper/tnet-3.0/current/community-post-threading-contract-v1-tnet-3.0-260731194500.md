# Community Post Threading Contract v1

Root topics have `parent_post_id = null`; replies have one parent in the same
`community_id` and share a canonical `thread_id`. Direct replies are the
baseline; nested replies remain supported by parent identity, while UI depth
and ordering are separate presentation decisions. Cross-Community parents,
cycles, and missing parents are rejected.

Ordering is deterministic by publication time plus stable post identity.
Pagination is cursor-based for future implementation. Hidden, deleted, spam,
or retracted parents remain in the audit/thread model while child visibility is
reevaluated; no child may expose restricted parent content. Thread locking
blocks new replies but preserves reads and evidence. Move/merge is deferred and
must create an auditable new relationship. Legacy `topic_dir` is evidence, not
authority.
