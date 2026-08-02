# Community Live Link Fetch Readiness v1

## Decision

**NOT READY** for live retrieval. The current fixture service and compatibility
JSON are appropriate for deterministic local previews, but live retrieval needs
network admission, DNS/SSRF, bounded transport, extraction, privacy,
moderation, and abuse controls that are not implemented.

## Required preconditions

Use a separate policy/transport boundary; never let the composer call HTTP.
Require absolute HTTPS URLs, canonicalized host admission, credential/port
rejection, DNS resolution and destination revalidation on every redirect,
private/reserved/link-local/multicast/metadata endpoint blocking, TLS
verification, bounded time/body/header/decompression limits, MIME allowlists,
concurrency and per-user/per-host rate limits, negative caching, audit events,
and safe fallback. WordPress HTTP APIs may provide transport primitives but do
not by themselves prove SSRF safety.

The preferred next step is C3-PUB006: a mocked policy/transport adapter. A
separately approved allowlisted live-fetch pilot may follow only after its
preconditions pass.
