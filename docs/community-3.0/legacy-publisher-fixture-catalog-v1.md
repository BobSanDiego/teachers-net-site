# Legacy Publisher Fixture Catalog v1

| Fixture | Purpose | Data policy |
|---|---|---|
| `board.json` | Synthetic board with `local_path=...`, `path_id=241`, `group_id=227` | Explicitly redacted and synthetic |
| `new-topic.json` | Accepted topic and deterministic timestamp observation | No production body or author |
| `reply.json` | Parent/thread preservation | Synthetic identifiers only |
| `edge-cases.json` | Rejection, divergence, duplicate, partial-write, and unknown outcomes | Stable reason codes only |

The divergent `path_id` and `group_id` values are intentional. The fixture
requires an explicit mapping evidence flag and refuses to infer identity from
path. No usernames, email addresses, cookies, tokens, IP addresses,
moderation records, production datasets, or post bodies are present.

Golden files freeze semantic observations, not accidental HTML whitespace or
legacy implementation details. Fixture integrity is verified by JSON parsing,
focused tests, repository diff checks, and source inspection for prohibited
production data.
