# Community Live Link Adapter Interface v1

The future pipeline is intentionally split into interfaces:

1. `UrlAdmissionPolicy` — normalize and approve the submitted URL.
2. `DestinationAuthorizer` — resolve/classify destinations and revalidate redirects.
3. `BoundedTransport` — enforce TLS, timeout, size, MIME, redirect, and concurrency policy.
4. `MetadataExtractor` — parse allowlisted candidate metadata.
5. `MetadataSanitizer` — cap, redact, normalize, and remove unsafe values.
6. `ProviderAdapter` — optional approved-provider specialization.
7. `PreviewRepository` — cache identity, lifecycle, suppression, and audit.
8. `PreviewRenderer` — safe card or readable raw-link fallback.

Each interface returns typed success/failure reasons and is deterministic under
mocked inputs. The composer depends on an application service, not transport.
The current fixture attachment service remains the implementation authority
until a later ticket supplies these interfaces.
