# Community Conversation Branch Contract v1

Each topic is the conversation container. Each L1 comment starts a branch;
`conversation_root_id` points to that L1 comment. Every descendant retains its
exact `parent_post_id`, even when it is rendered in the single flat L2 layer.
The root topic has no conversation branch and a null parent.

For an existing chain, walk parents until the nearest L1 comment is found. If a
parent is missing, mark the branch unresolved and preserve the imported parent
reference. If a cycle is detected, stop at the first repeated ID, preserve the
cycle evidence, and exclude the row from automatic normalization.

L1 comments sort chronologically by authoritative creation time and stable
row ID. L2 descendants sort chronologically within their L1 branch using the
same tie-breaker. No ranking or activity promotion is permitted in v1.

An L1 tombstone remains the branch anchor; its descendants retain lineage and
render with a neutral target label. Restored content returns through normal
visibility rules. A locked branch rejects new writes but does not rewrite old
edges.
