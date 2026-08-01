# Community Publishing Capability Contract v1

## Purpose and boundary

Community publishing is a durable platform capability, not merely a textarea.
The mature composer must produce safe, attributable, moderatable content while
remaining compatible with Community topics and future subject-owned discussions
for Lessons, Articles, Resources, Jobs, Marketplace, and later products.

The current V1 implementation remains intentionally narrow: authenticated text
topics and replies, exact parent/reply-target lineage, moderation state, and
local persistence. Rich media and mature composer behavior must not be added
until the contracts in this package are accepted.

## Capability decisions

| Capability | Decision | Classification |
|---|---|---|
| Plain text topic/reply | V1; canonical baseline | Required before implementation |
| Rich text | Planned after foundation | Safe to defer |
| Images, galleries, video, audio, PDFs, Office documents | Planned media families | Safe to defer |
| Resources and external links | Planned through link/resource contracts | Safe to defer |
| Polls, questions, ideas, candles, announcements, events | Product modes requiring separate semantics | Safe to defer |
| AI summary/classification/translation/recommendation | Assistive, never authoritative | Speculative until governance exists |

Every published object must carry author, owner product, subject reference when
attached, visibility, moderation state, publication state, created time, and
stable lineage. A capability is not implementation-ready until its storage,
feed, notification, moderation, accessibility, abuse, and lifecycle effects
are named.

## Compatibility principles

Subject products own their subjects; Community owns conversation identity and
thread behavior. Notifications consume approved domain events rather than
scraping rendered cards. Moderation can restrict a post or media asset without
destroying historical lineage. Rich presentation must never make text-only
participation second-class.

No capability in this contract authorizes schema changes, migration, UI work,
feed work, notification work, or production deployment. Those require a
separate implementation ticket.
