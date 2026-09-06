# Community Legacy Historical Discussion Source Profile v1

Status: migration authority; no importer or data mutation authorized.

## Source identity

The source namespace is `legacy:chatpost`. `post_id` is the immutable source
record key. `topic_id` is a separate immutable conversation key; it is not the
root post ID. Preserve `post_url` and topic URL as inbound aliases. Store source
checksums and the original raw values alongside any normalized projection.

## Thread profile

`post_type=post` is a topic/root. `post_type=reply` is a direct child of its
topic root when the root and visibility mapping are verified. The legacy corpus
has no usable reply-to-reply parent edge (`parent_id` is zero for legacy
replies), so migration must not invent nesting. Rootless replies, cross-thread
references, cycles, unmapped boards, and identity conflicts enter a non-public
exception queue.

## Identity and state

Resolve authoritative WordPress identity only from a verified immutable mapping.
Otherwise preserve historic display name, identity state, and source snapshot;
never guess an account merge. Preserve raw `status`, moderation/report evidence,
author values, timestamps, board/path values, media fields, and static artifact
references. Status 9 is excluded from public Community migration under the
ratified Director decision, but remains archive/provenance evidence.

## Board and media mapping

Resolve `local_path`/legacy path through an explicit board mapping. Never equate
`path_id` and `group_id`. Preserve direct image/video/link fields and persisted
OG metadata as source evidence. Do not refetch remote metadata during feed or
migration rendering; missing or unavailable assets use an explicit fallback.
