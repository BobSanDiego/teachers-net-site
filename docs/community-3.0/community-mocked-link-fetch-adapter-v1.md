# Community Mocked Link Fetch Adapter v1

C3-PUB006 implements the approved no-network adapter boundary. A Community
application service coordinates URL admission, mock destination authorization,
mock bounded transport, extraction, sanitization, provider classification, and
process-local cache lifecycle. Each boundary returns deterministic results with
reason codes; no socket, cURL, stream, WordPress HTTP API, DNS, or external
provider is called.

Admission accepts normalized HTTPS absolute URLs only and rejects credentials,
fragments, unsupported ports, ambiguous/invalid hosts, and unsupported schemes.
Destination fixtures classify public and restricted addresses. Transport
fixtures cover timeout, TLS, redirect, size, decompression, MIME, and failure
outcomes.

Extraction and sanitization remain separate. Fixture HTML yields bounded title
and description values; unsafe markup and URL schemes are removed. Provider
classification is generic/YouTube/Vimeo/unsupported only and never produces
embed HTML. Cache fixtures prove positive/negative results, identity,
refresh throttling, audit history, and suppression states.

The existing visible fixture preview remains authoritative. No live preview
support is claimed and no public diagnostic route was added; focused mock tests
provide the local review surface without exposing policy internals.
