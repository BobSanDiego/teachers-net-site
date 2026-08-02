# Community Activity Feed v1

The local `/community/` route is now a bounded activity feed over the existing
Community repository. Each card is a projection, not a second content
authority, and links to the canonical Thread View. Cards show author, title,
excerpt, last activity, reply count, and safe attachment/preview presentation.

The feed supports text-only posts, fixture image/video/audio/document records,
and mocked link previews from compatibility metadata. Text remains the visual
primary: media is supporting context and every card degrades to readable text.
Publication and moderation state are respected; restricted attachment records
are not rendered. No ranking, personalization, infinite scroll, ads, live
fetching, notifications, AI, uploads, or production behavior is included.

The current layout uses a responsive single-column card stream for desktop,
tablet, and mobile. Feed-card polish and responsive refinement remain the next
bounded increment.
