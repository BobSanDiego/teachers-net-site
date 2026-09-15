# Community V1 Media Discovery Implementation

Status: IMPLEMENTED — authoritative native acceptance blocked by integrated fixture and browser-control availability
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

The prior Windows Chrome/CDP evidence on the isolated
`https://teachers-net-community3.ddev.site/community/ai-in-education/media/`
fixture remains useful implementation evidence only. It is not authoritative
native acceptance for this objective's integrated Community presentation.

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

## Runtime-owner convergence — 2026-09-15

Cycle `260915120000` establishes `teachers-net-live.ddev.site` as the
authoritative integrated Community presentation runtime for Media acceptance.
`teachers-net-community3.ddev.site` is an isolated source harness, not the
accepted integrated shell. The live runtime mounts the exact
`tnet-community` source tree from
`/home/bobreap/projects/teachers-net-community3` read-only; byte comparison of
the plugin bootstrap, Shared Shell adapter, and Media controller matched the
source tree. Its governed runtime-authority preflight records commit
`433e910720d13057a8ecaf78766e0798f7a83614` and plugin tree hash
`f07273a5078bf5a0650250a49f833f000671e94f6b1cd202cada69ff87a8db72` as
`ok`.

The live runtime's legacy theme, integrated plugin set, and database own the
accepted surrounding shell. Its rewrite rule was refreshed locally so the
Media route resolves, but its governed store contains zero C3
`ai-in-education` posts, `post_media` rows, and media assets. The authoritative
route consequently returns the implemented empty state, not gallery tiles.
No fixture was copied, created, or modified.

Accordingly, only the previous isolated-runtime Shared Shell orientation,
Media presentation, and responsive visual claims are invalidated. Query and
media predicates remain deterministic implementation evidence; prior
processor, signer, delivery, and application-media seams remain carried
forward. Canonical browser control also failed before browser discovery with
`sandboxCwd is not a local file URI: file:///home/bobreap/projects/teachers-net-site`.
No source, HTTP, or DOM observation is treated as native browser acceptance.

Resume requires both a callable canonical browser surface and an existing,
governed integrated live fixture with eligible Media rows. The local commit
`433e910` remains intentionally unpushed until this authoritative acceptance
can be completed.
