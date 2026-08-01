# Community 3.0 URL Architecture v1

Status: proposed production contract; no production routing change authorized.

## Decision

The canonical public pattern is `/community/{community-slug}/{thread-slug}/`.
The community slug supplies readable context; the thread slug supplies the
human-readable subject. Canonical IDs remain stored and are used for lookup,
authorization, compatibility, and audit, but do not appear in normal public
URLs. A canonical URL is generated only after a public thread has a resolved
canonical community and an approved public visibility state.

Examples:

* Community: `/community/ai-in-education/`
* Thread: `/community/ai-in-education/using-ai-for-formative-assessment/`
* Reply: the same thread URL plus `#reply-{reply-slug-or-opaque-id}`.

Thread slugs are immutable after first publication. A title edit changes the
display title, not the canonical URL. A moved thread receives a new canonical
URL only through an explicit migration decision; the prior URL remains a
recorded alias and redirects only when the target is public and unambiguous.
The first implementation should prefer retaining the original community
context and issuing a 301 from a verified old URL to the new target.

## Why this pattern

Including the community makes two otherwise identical thread titles
unambiguous, gives search engines meaningful hierarchy, and leaves room for
community landing pages, group context, Portable Views, and future archives.
The route does not confuse a chatboard `path_id` with a teacher `group_id` or
with canonical `community_id`; those remain domain and compatibility data.
The pattern is less opaque than an ID-only route and avoids making mutable
titles or internal identifiers part of the public contract.

The alternatives were rejected as follows: a global thread slug has weaker
context and collision pressure; `/thread/` in every URL adds little value once
the first segment is a community; ID-plus-slug leaks an implementation detail
and makes migration look canonical; a legacy-shaped URL would preserve the
wrong authority. A future evidence-backed alternative must preserve the same
separation of canonical identity, compatibility metadata, and presentation.

## Route family

| Resource | Canonical shape | Visibility and authority |
|---|---|---|
| Community landing | `/community/{community-slug}/` | Community identity and public visibility |
| Thread | `/community/{community-slug}/{thread-slug}/` | Canonical post/thread identity and thread visibility |
| Reply | thread URL + `#reply-{reply-ref}` | Reply identity; fragment is not a separate page |
| Group/member page | `/community/{community-slug}/groups/{group-slug}/` | Group authority and membership policy |
| Member roster | `/community/{community-slug}/members/` | Authenticated/public roster policy |
| Settings | `/community/{community-slug}/settings/` | Authenticated capability checks; noindex |
| Optional archive | `/community/{community-slug}/archive/{year}/` | Immutable archive policy; noindex where required |

The companion group may share the visible community slug as context, but the
group remains a distinct governed relationship. Core Terms do not own URLs.
Portable Views can change labels, ordering, and presentation inside an
authorized view; they cannot create or replace the canonical Community URL.

## Canonical behavior

URLs are lowercase, UTF-8 normalized, transliterated where practical, and
trimmed to bounded ASCII slugs. One trailing slash is required. Query-string
variants are removed from canonical links unless a named route explicitly owns
a functional parameter such as pagination. `?page=2` canonicalizes to the
pagination form selected by the implementation; tracking parameters never do.
Private, member-only, retracted, deleted, and spam content is not promoted to
an indexable canonical URL. It must not redirect an anonymous visitor to a
private target.

The current local `/community/thread/{post_id}/` route is explicitly a DDEV
prototype, noindex, navigation-free, and noncanonical. It remains available
only for local Thread View verification until C3-URL002 supplies a local
canonical route and an explicit alias test. It must never silently become a
production canonical route.

## Boundaries

This contract does not authorize a schema migration, production rewrite,
legacy redirect deployment, sitemap change, or public UI release. Those are
separate implementation and migration gates.
