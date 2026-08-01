# Community Rich Composer Foundation v1

## C3-PUB002 implementation boundary

The local `/community/new/` composer is now a modular publishing surface while
remaining text-first. Its sections are context, post mode, writing, and an
advanced attachment area. Native HTML controls provide keyboard access and
progressive disclosure without requiring JavaScript.

Discussion, Question, Candle, and Idea are explicit placeholders. They
currently publish through the ordinary topic pipeline; no specialized
semantics are implied. The attachment area has an empty state and disabled
future media controls. A link can be recorded as an attachment reference with
enrichment explicitly deferred; no URL fetching or preview generation occurs.

Composer compatibility is carried in existing `compatibility_refs` as a
`composer` object containing `post_mode`, `attachments`, and `links`. This
prepares future media families without adding schema, storage, uploads, feed
cards, notifications, AI, or production behavior.

Accessibility includes associated labels, fieldset/legend structure,
descriptive help, alert semantics, native select/details controls, visible
keyboard focus, and mobile-safe action layout.
