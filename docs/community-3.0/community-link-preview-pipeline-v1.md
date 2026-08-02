# Community Link Preview Pipeline v1

## C3-PUB003 implementation boundary

This increment establishes a safe local foundation for link previews without
performing network retrieval. `TNet_Community_Link_Preview` is the cached
enrichment object model; `TNet_Community_Link_Attachment_Service` is the
attachment boundary and fixture pipeline. It accepts a sanitized URL, returns
deterministic local metadata, and applies the author’s choice: `keep` retains
fixture metadata, `remove` removes preview metadata while retaining the link,
and `raw` retains the raw link only.

Unknown or invalid URLs degrade to raw-link-only behavior. The composer shows a
local fixture preview placeholder and the three author choices. It never calls
Open Graph, Twitter/X, oEmbed, a crawler, or an external network.

Preview metadata is carried in existing `compatibility_refs.composer.preview`
JSON. `persist_cached_preview()` provides repository support without adding
tables or columns. A future live adapter must add SSRF controls, redirect/DNS
restrictions, bounded fetches, cache expiry, moderation, redaction, and
refresh policy before it is enabled.

Plain link fallback is authoritative: preview failure or removal never blocks
publishing and never replaces the submitted URL. Feeds, notifications, media
uploads, and production remain outside this ticket.
