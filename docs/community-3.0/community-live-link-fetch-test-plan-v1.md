# Community Live Link Fetch Test Plan v1

Mock deterministic cases must cover valid public HTTPS URLs; invalid schemes;
credentials, ports, IDN/punycode and encoded hosts; loopback/private/
link-local/multicast and metadata endpoints; IPv4/IPv6; DNS rebinding;
redirect loops and redirect-to-private; arbitrary ports; TLS failure;
connection/total timeout; header/body/decompression limits; MIME rejection;
malformed HTML; metadata conflicts; unsafe image references; provider failure;
positive/negative/stale cache; refresh throttling; author removal; moderator
suppression; restricted-post leakage; raw fallback; idempotency; and audit
records. Assert no external DNS/HTTP occurs in this test suite.

The next ticket may implement only a mocked policy/transport adapter and these
fixtures. Live network tests require separate authorization and isolation.
