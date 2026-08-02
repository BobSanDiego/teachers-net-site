# Community Authoring Experience v1 — Design Authority

This document is the permanent product authority governing Community authoring experiences. Future engineering tickets implement this authority rather than rediscovering UX decisions.

## Product philosophy

- Conversation over document editing.
- Technology should disappear.
- Less interface, more capability.
- Reward curiosity.

## Canonical composer

Topics and replies use one composer. Context may change, but the author edits the finished artifact.

## Natural interaction

Type first, paste first, drag/drop second, toolbar as convenience, with progressive disclosure.

## Media and links

Media supports the story without imposing attachment framing. Use one visual container, integrated images, optional captions, and hide filenames and implementation terminology. Detect URLs from the body; the URL remains authoritative while a representative preview may be automatic, dismissed, restored, or selected under the multiple-link policy.

## Formatting and accessibility

Preserve common pasted formatting through a lightweight Markdown subset. Do not introduce a WYSIWYG editor, formatting ribbon, arbitrary font controls, colors, or sizing. Accessibility should be automatic where practical; contextual alt text is preferred with an optional override and without burdening ordinary users.

## Replies and future roadmap

Replies use the same composer, media, and formatting, with reply context replacing topology controls. Future capabilities may include GIF providers, polls, documents, video, standards mentions, AI-assisted authoring, and a structured document model; these do not override the current authority.

## Governance rule

No future author-facing implementation may knowingly contradict the Community Authoring Experience authority without first updating the authority document and explicitly recording the rationale.

## Explicit non-goals

Do not build a Word-style editor, font controls, colors, or arbitrary sizing into the authoring experience.
