# Community Notification Destination Contract v1

Status: required contract; no delivery implementation authorized.

Every notification stores a semantic target: community, discussion, and
optional immutable reply identity, plus the visibility-checked canonical
destination. It does not store an ordinal feed position. Discovery notifications
favor the canonical discussion page. A same-context conversational action may
open the supported focused modal; read state remains independent of engagement
and destination navigation.

Destination resolution rechecks visibility, moderation, recipient authority,
and alias status at access time. Unavailable targets use a truthful fallback and
do not disclose restricted content. The global bell remains available during
modal presentation. Providers may add transport metadata, but producer and
renderer must not invent a route from display text.
