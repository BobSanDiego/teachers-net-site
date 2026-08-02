# Community Link Fetch Policy v1

Admission: HTTPS only; absolute URLs only; no credentials, fragments for fetch
identity, userinfo, wildcard/empty hosts, arbitrary ports, data/blob/file/FTP
or JavaScript schemes. Canonicalize Unicode/IDN to a validated punycode form,
reject ambiguous encodings, and classify IPv4/IPv6 and DNS results against
loopback, private, reserved, link-local, multicast, metadata, and internal
hostname ranges.

Transport defaults for a future pilot: short connection and total timeouts,
limited redirects with destination revalidation, bounded headers/body and
decompression, TLS verification, no retries for unsafe failures, and strict
MIME/size allowlists. Use asynchronous work, per-user and per-host rate
limits, bounded concurrency, stale-while-revalidate, and negative caching.

Do not fetch in this ticket. A mocked policy adapter must prove each decision
before live transport is considered.
