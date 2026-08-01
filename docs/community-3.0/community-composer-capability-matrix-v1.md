# Community Composer Capability Matrix v1

| Capability | V1 | Planned | Deferred | Schema Now? | Feed Impact | Notification Impact | Moderation Impact |
|---|---:|---:|---:|---|---|---|---|
| Plain text topic/reply | Yes |  |  | Existing post fields | Text card | Existing post events | Existing state/audit |
| Rich text |  | Yes |  | Contract first | Sanitized text card | Post event only | HTML sanitization |
| Images/gallery |  | Yes |  | Attachment design first | Media card with text fallback | Attachment summary later | Rights, scan, alt text |
| Video/audio |  | Yes |  | Provider/upload contract | Player card with fallback | Provider-safe summary | Captions, provider policy |
| PDF/Office documents |  | Yes |  | File lifecycle contract | Download card | File event policy | Malware, MIME, rights |
| Resources/external links |  | Yes |  | Subject/link contract | Link card optional | Post event | SSRF and URL review |
| Multiple links |  | Yes |  | Attachment collection | Balanced link card | Post event | URL limits |
| Polls/questions/ideas |  | Yes |  | Product semantics first | Specialized card | Response policy | Abuse and closure |
| Candles/announcements/events |  | Yes |  | Product semantics first | Specialized card | Subscriber policy | Role, expiry, abuse |
| Link enrichment |  |  | Yes | No | Optional cached preview | None from fetch | SSRF, redaction |
| AI summary/classification/translation/recommendation |  |  | Yes | No | Assistive only | Never authoritative | Disclosure, review, opt-out |

## Classification rule

“V1” means the capability is already bounded by the current local publisher
contract. “Planned” means it has a plausible product path but needs the named
contracts first. “Deferred” means implementation would create avoidable
security, governance, or product ambiguity. “Speculative” recommendations
must not be treated as roadmap commitments.
