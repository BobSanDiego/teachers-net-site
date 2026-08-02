# Community Rich Media Attachment Foundation v1

Community posts can carry one deterministic local fixture attachment in
existing `compatibility_refs.composer.attachments`. The validated model covers
image, video, audio, and document/PDF records with identity, source,
descriptive, MIME, size/dimension/duration, rights, moderation, and lifecycle
fields.

Only `local_fixture` sources with `fixture:` references are accepted. Images
require alt text. MIME/type mismatch, unsupported types, remote/arbitrary
sources, and missing image alt text are rejected. Restricted attachments are
omitted from ordinary Thread View while post text remains readable.

The composer exposes one-at-a-time fixture controls and removal. Thread View
renders safe image, video, audio, and document cards with readable fallbacks.
No raw embed HTML, iframe, script, data URL, filesystem path, upload, Media
Library, CDN, derivative, transcoding, or remote retrieval is allowed.

Repository persistence uses existing transactional compatibility JSON,
preserving text-only publishing, link previews, subject metadata, and
idempotency. Production storage and security services remain open requirements.
