# Canonical Community Identity Contract v1

Status: documentation-only architecture contract; no schema or record changes.

## Decision

Community 3.0 will use a stable opaque `community_id` as the canonical
identity of a Community entity. A Community owns participation context and may
have one or more chatboard/publisher streams, companion group contexts, Core
Terms assignments, and Portable Views. Neither a chatboard path nor a teacher
group replaces the Community identity.

The verified invariant is generic: a legacy path identifier is not a teacher
group identifier. Existing evidence includes a divergent mapping, but numeric
examples belong in regression fixtures, not routine product reasoning.

## Target model

`CommunityEntity` has canonical identity, lifecycle, visibility, and authority;
associated publisher/board references; zero or more group relationships;
Core Terms assignments; Portable View relationships; immutable legacy path and
group references; and URL/archive compatibility metadata. The model permits
one Community to have multiple views or streams without making a view a new
entity.

Core Terms classifies Community entities. Portable Views select, order, group,
or label their semantic material. Neither system owns Community identity.
Membership and notifications consume canonical identity plus explicit group
context; they must not derive identity from a legacy path value.

## Boundary rules

New application code must not compare, join, persist as canonical identity, or
route by `path_id` or legacy `group_id`. Only the compatibility resolver may
translate those references. Equality between legacy identifiers is never a
required condition. Missing, duplicate, ambiguous, inactive, or orphaned maps
must produce an explicit unresolved state and stop the dependent operation.

Legacy URLs, posts, replies, memberships, settings, moderator evidence,
redirects, SEO references, and audit history remain queryable as compatibility
records. They are not authority for new writes.

## ID alignment

Choose option C: create canonical `community_id` and retain both legacy key
families as compatibility references. Never renumber records to align values.
Renumbering would change joins, URLs, audit evidence, rollback meaning, and
external references without reducing the semantic distinction.
