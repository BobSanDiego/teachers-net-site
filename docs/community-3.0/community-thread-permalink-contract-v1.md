# Community 3.0 Thread Permalink Contract v1

Status: proposed; implementation deferred to C3-URL002.

## Canonical permalink

`/community/{community-slug}/{thread-slug}/` is the one canonical permalink
for a public thread. The stored opaque `community_id` and post/thread ID are
the lookup authority and are not exposed in normal URLs. A reply is addressed
by the parent thread URL and a stable fragment, for example
`#reply-r42`; the fragment must resolve to the reply without creating a second
canonical document. If reply references cannot be safely exposed, use an
opaque bounded reply reference while keeping it non-authoritative.

The first published slug is retained. Title changes affect display metadata
and search text only. A moved thread requires an audited relationship between
old and new community context. A retracted or deleted thread cannot be used as
a redirect oracle for private content: return the policy-selected 404, 410,
restricted response, or immutable archive render.

## Normalization and pagination

Normalize at the edge to HTTPS, lowercase host/path, one trailing slash, and
one bounded percent-decoding pass. Reject malformed encodings and encoded
slashes before lookup. Normalize duplicate separators and dot segments without
allowing path traversal. Pagination is a route-owned parameter; page 1 uses
the base URL, later pages use the selected stable form. Sort and filter query
strings are canonicalized only when the route explicitly declares them.

## Metadata

Public pages emit one canonical tag owned by the Community route renderer,
not by Core Terms or Portable Views. Public sitemap entries include only
indexable canonical Community and thread URLs. Member/settings/archive routes
follow their visibility policy and are excluded or marked noindex as required.
Reply fragments are never separate sitemap entries.

## Required state transitions

Slug generation occurs at the public-publication boundary after canonical
identity and community resolution. Collision allocation is deterministic and
bounded. A title rename does not allocate a new slug. A community move writes
an auditable alias decision before any redirect is enabled. A moderation
transition is evaluated before canonical tags, sitemap inclusion, or redirect
responses are generated.

## Acceptance cases

The implementation must test a normal route, duplicate titles, rename,
community move, reply anchor, pagination, private thread, retracted/deleted
thread, malformed input, and canonical-tag/sitemap ownership. No production
route is changed by this document.
