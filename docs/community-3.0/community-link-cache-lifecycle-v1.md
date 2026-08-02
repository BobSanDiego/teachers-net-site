# Community Link Cache Lifecycle v1

Cache identity is the normalized URL plus extraction-policy version and
provider-policy version, never the raw secret-bearing query string. Store
created, refreshed, expires, last-attempt, status, and metadata-version
timestamps. Support positive, negative, stale, removed, and moderator-
suppressed states. Author removal must survive refresh; refresh must be
throttled and audited. Stale-while-revalidate is preferred after a successful
pilot; synchronous fetches must not block publishing.

Compatibility JSON remains sufficient while previews are post-local, low-
volume, and fixture/mock-only. A dedicated enrichment table becomes necessary
when URLs are shared across posts, refresh jobs need independent locking,
queries/reporting require indexes, retention must be managed separately, or
payload size/row contention becomes material. No schema change is authorized.
