# Community 3.0 Candidate/Audit Boundary v1

Status: bounded test-only implementation. This boundary consumes C3-IMP002
evaluator output and creates deterministic objects in memory only.

The evaluator remains the sole source of decision, reason codes, event identity,
recipient identity, visibility, and explicit `path_id`/`group_id` mapping. The
boundary wraps that result as a candidate and a redacted append-only test audit
record without reinterpreting membership, consent, suppression, or visibility.

Event, candidate, eligibility, bell, email, digest, delivery, read state, and
engagement remain distinct. Bell, email, digest, and delivery are represented
as deferred outcomes only. No object is persisted.

The deterministic candidate ID is `cand:<event_id>:<recipient_id>`. Reason codes
are sorted and stable. The candidate preserves event/recipient identity, the
decision, reason codes, both mapping IDs, and deferred channel states.

The redacted audit record preserves the decision, reason codes, mapping,
visibility, and candidate identity. Content is always redacted. Explicit
database, schema, queue, bell, email, and digest side-effect flags are false.
The record is returned in memory and is not written to a file or database.

Verification:

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
```

The tests cover eligible, blocked, and ineligible outcomes, stable reason
codes, audit completeness, distinct `path_id` and `group_id`, deferred channels,
and zero persistence side effects. No database writes, schema changes, queues,
bell records, email, digest generation, production data, UI, provider, or
persistent audit store are included.
