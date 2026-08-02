# Community Link Fetch Threat Model v1

The attacker controls the submitted URL and may also control remote response
headers, HTML, redirects, compression, metadata, image references, timing, and
availability. Threats include SSRF to loopback/private/link-local/multicast
and cloud metadata addresses, DNS rebinding, encoded host bypasses, arbitrary
port scanning, redirect abuse, decompression bombs, oversized responses,
malformed HTML, phishing/malware links, tracking pixels, offensive images,
secret-bearing query strings, and cache poisoning.

Controls must be layered: normalize and admit URLs; resolve and classify every
destination; revalidate after redirects; use bounded transport; parse only
allowlisted metadata; sanitize and normalize; redact secrets; moderate before
display; isolate cache identity; rate-limit and audit. Preview failure must
never block or rewrite the raw link. Restricted posts must not expose cached
metadata through Thread View, feeds, notifications, search, or social previews.
