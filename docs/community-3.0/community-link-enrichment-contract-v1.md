# Community Link Enrichment Contract v1

Link enrichment is an untrusted, cacheable presentation service. A submitted
URL remains the author’s link even when enrichment fails, is stale, or is
removed. The canonical post body and link target must not be replaced by
scraped metadata.

The future service may use Open Graph, Twitter/X Cards, oEmbed, and approved
YouTube/Vimeo provider adapters. It needs bounded fetches, protocol and DNS
validation, redirect limits, private-network blocking, content-size limits,
content-type checks, timeout controls, cache expiry, refresh throttling, and
redaction of secrets or unsafe metadata. Author override/removal must be
explicit and auditable.

Enrichment is optional: cards render only from safe cached metadata, while a
text-only link remains useful without a preview. Provider failure must not
block publishing. Moderation can suppress a preview independently of the
underlying link. Notifications should reference the post event, not the
enrichment fetch.

All enrichment is planned and safe to defer. SSRF/security controls are
required before any implementation that performs server-side retrieval.
