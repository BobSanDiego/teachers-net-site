# Community Historical Media and Static-Artifact Provenance v1

Status: required contract; no asset migration or remote fetch in this cycle.

Persisted legacy image/video/link columns, `tnet_chatposts_meta` OG fields, and
static HTML/include/cap artifacts are source evidence, not new presentation
authority. Preserve source post/topic association, original URL/path, available
dimensions, metadata checksum, capture/availability result, and rights or
moderation disposition where known.

Migration and feed rendering must not perform remote OG fetching. Imported
preview metadata is bounded enrichment from persisted evidence. If an asset is
missing, private, unsafe, or unresolved, render a truthful text/link fallback
and retain the exception; never fabricate a thumbnail or dimensions.

Asset checks are part of per-batch reconciliation: source reference count,
available count, missing count, preview-field count, and representative public
render samples. Static artifacts may support archive compatibility but do not
remain a second Community writer.
