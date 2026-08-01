# Community Rich Media Contract v1

Rich media is an attachment to a post, not an alternate ownership model. A
post may contain zero or more typed media items, each with an immutable source
reference, safe display metadata, accessibility text, moderation state,
copyright/rights provenance, and lifecycle state.

Images and galleries require dimensions, alt text, focal metadata only where
useful, responsive derivatives, and a safe fallback. Video and audio require
provider or uploaded-source identity, duration where known, captions or a
transcript path, thumbnail policy, and embedding restrictions. PDFs and Office
documents require download classification, MIME validation, malware scanning,
size limits, and an accessible text alternative or explicit limitation.

Storage authority, CDN delivery, derivative generation, retention, deletion,
copyright complaints, and moderation holds must be separate decisions from
composer UI. External media must not be fetched server-side without SSRF-safe
allowlists and bounded time/size behavior.

V1 text publishing does not require these fields or tables. Rich media is
planned and safe to defer until the storage, security, accessibility,
moderation, and feed contracts are approved. A future implementation must not
silently turn arbitrary URLs into trusted media.
