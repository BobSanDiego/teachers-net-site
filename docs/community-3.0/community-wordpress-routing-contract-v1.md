# Community 3.0 WordPress Routing Contract v1

Status: proposed; no production routing change authorized.

The Community plugin owns Community route registration, query vars, canonical
redirect decisions, visibility checks, canonical tags, sitemap integration,
and 404/restricted responses. Core Terms owns semantic identity and Portable
Views own reusable presentation; neither owns canonical Community URLs. Jobs,
Lesson Bank, and third-party plugins retain their existing route authority.

The production route family is `/community/{community-slug}/` and
`/community/{community-slug}/{thread-slug}/`, with deeper groups/member and
settings routes explicitly reserved. Route precedence must be tested against
existing pages, Jobs routes, Core Terms routes, feeds, REST endpoints, login,
and static assets. Query vars must be narrowly named and sanitized. The
implementation must not route by legacy `path_id`, `group_id`, or raw internal
IDs as canonical identity.

Rewrite rules are registered on activation or an explicit administrative
flush procedure, never on page load. Activation/deactivation must document the
flush boundary and leave existing unrelated rules intact. Multisite behavior
must be explicit before enabling production routes; site-local community
slugs cannot silently collide across network sites.

Canonical redirects are generated only after route resolution, visibility, and
same-site target validation. The renderer owns one canonical tag. Public
indexable routes may enter the sitemap; private, member-only, settings,
retracted, and archive-policy routes do not. Cache keys must vary on route and
visibility state, and restricted responses must not be cached as public pages.

The current local `/community/thread/{post_id}/` implementation is DDEV-only,
noindex, navigation-free, and noncanonical. C3-URL002 may retain it as an
explicit local temporary alias while proving the new route. It must not be
enabled in production or silently treated as a canonical fallback.

## Verification boundary

Before production consideration, verify rewrite registration, route precedence,
flush safety, canonical tags, sitemap inclusion/exclusion, cache headers, 404
behavior, private-content protection, malformed input, and redirect-loop
prevention in local tests. Production evidence collection and deployment are
separate approvals.
