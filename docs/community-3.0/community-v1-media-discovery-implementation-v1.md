# Community V1 Media Discovery Implementation

Status: IMPLEMENTED — native public-surface acceptance partial pending governed edge fixtures
Objective: `C3-V1-COMMUNITY-MEDIA-DISCOVERY-IMPLEMENTATION001`
Cycle: `260915103000`
Project: Community

## Implemented contract

The Community-local route is `/community/{community-slug}/media/`. It is a
read-only projection over the existing Community registry, top-level published
posts, `post_media`, ready image assets, and verified `480.webp` variants. The
projection uses one joined, visibility-aware query with keyset pagination at 24
items, preserves attachment position, and links each tile to the existing
canonical originating discussion. No new media lifecycle, persistence, cache,
derivative, or AWS behavior was added.

The implementation locks the ticket's source floor:
`source_width >= 240 AND source_height >= 240`. It excludes replies,
non-public posts, non-published/restored or non-clear posts, non-image assets,
unready assets, and unverified `480.webp` variants. Gallery markup exposes only
the public CloudFront `480.webp` URL, uses `loading="lazy"` and
`decoding="async"`, and contains no quarantine/source metadata.

The page is a rail-free variant inside the existing Shared Shell. Its gallery
uses `auto-fill` with a 160px minimum tile, producing the intended dense
geometry in the native fixture: six columns at 1440px, five at 1024px, four at
768px, and two at 390px.

## Evidence

Deterministic PHP lint, rewrite registration, read-only HTML inspection, and
EXPLAIN passed. The current DDEV corpus contains three eligible attachments:
two ordered images from one top-level multi-image post and one image from a
second top-level post. It also contains four uploading and one failed asset,
but those non-ready assets are not attached to a post in the current governed
fixture.

Canonical Windows Chrome/CDP native evidence on
`https://teachers-net-community3.ddev.site/community/ai-in-education/media/`
proves the active Media tab, intact Shared Shell orientation with both rails
absent, all three eligible images, multi-image order, distinct public
`media.teachers.net/.../480.webp` URLs, no quarantine/source leaks, canonical
tile click-through, and responsive 1440/1024/768/390 geometry without
horizontal overflow. Native tile widths were 186.66, 190.39, 177, and 177px.

The current corpus does not contain governed attached examples for a
below-240 source, reply media, hidden/retracted/deleted/suppressed or
inaccessible source, or a 25th eligible item. Those seams are covered by the
implemented predicates and query contract, but remain `UNPROVEN_NATIVE` until
an existing governed fixture is available. No product fixture was created.

## Boundaries

Previously proven processor, signer, CloudFront, OpenTofu, and application
integration seams remain carried forward and were not retested. “Your Content”
remains a separate future objective. No AWS, production, credential, schema,
or unrelated project state changed.
