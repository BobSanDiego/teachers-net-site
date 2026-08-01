# Community Feed Card Contract v1

Feed cards are projections of published posts, not a second content authority.
Every card must retain the stable post identity, subject context where
permitted, author display policy, publication/moderation visibility, timestamp,
reply/target affordance, and a safe link back to the canonical post.

Card variants may represent text, image, gallery, video, audio, document,
link, poll, question, idea, candle, announcement, or event. Variants must
degrade to a readable text card when media or enrichment is unavailable.
Restricted content and identity must not leak through thumbnails, previews,
alt text, notifications, or cached metadata.

The feed must not privilege rich cards so strongly that text-only posts become
invisible. Ranking and layout should expose content type without treating
attachments as an automatic quality signal. Card contracts must consume
domain publication events and honor moderation/suppression state.

No feed implementation is part of this audit. The contract is required before
rich composer implementation and safe to defer as code.
