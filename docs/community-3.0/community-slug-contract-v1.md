# Community 3.0 Slug Contract v1

Status: proposed; no schema implementation in C3-URL001.

## Sources and scope

Community slugs derive from the governed community display name. Thread slugs
derive from the first public title. Neither is derived from `path_id`, legacy
`group_id`, an internal post ID, a Core Term, or a Portable View label. Core
Terms may provide semantic context, but do not own slug allocation.

Normalize Unicode to NFKC, lowercase, transliterate to a conservative URL-safe
alphabet when possible, replace runs of non-alphanumeric characters with one
hyphen, trim hyphens, and cap the result at 80 characters. Empty or entirely
non-transliterable input receives a deterministic opaque fallback that is not
presented as semantic identity. Reserved route words include `thread`,
`groups`, `members`, `settings`, `archive`, `page`, `feed`, `wp-admin`, and
future reserved namespaces.

## Uniqueness and changes

Community slugs are unique among communities. Thread slugs are unique within
the canonical community. A duplicate receives a deterministic bounded suffix,
preferably `-2`, `-3`, and so on, allocated transactionally and never reused
for a different published thread. The original thread slug is immutable.
Moderator overrides are allowed only with an attributable audit record and
the same collision checks.

Community renames should preserve the community's canonical identity and may
create a new community alias only through migration policy. Thread title
renames do not change the thread slug. Community moves and alias changes must
retain old-to-new evidence and an explicit redirect status.

## Audit record

Record source label, normalized candidate, chosen slug, scope, collision
decision, actor, timestamp, prior slug/alias, and reason. Keep compatibility
metadata separate from canonical identity. The slug is a locator, not proof of
membership, moderation authority, semantic meaning, or subscriber consent.
