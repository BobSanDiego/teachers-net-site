# Community Link Metadata Extraction Contract v1

Extraction is separate from transport. Accept canonical URL, Open Graph,
Twitter/X Card, standard title/description, and approved oEmbed discovery only
as untrusted candidate values. Normalize conflicting values deterministically,
cap lengths, strip markup, reject unsafe schemes, and validate image URLs,
MIME, dimensions, and size before storing. Provider adapters must return a
common normalized object and never return raw HTML or arbitrary iframe code.

Required normalized fields are URL, title, description, image reference,
provider, extraction status, metadata version, and timestamps. Missing,
malformed, conflicting, or provider-failed metadata degrades to the raw link.
The author may remove the preview; moderation may suppress it independently.
