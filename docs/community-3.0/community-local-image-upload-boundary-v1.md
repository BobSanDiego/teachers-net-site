# Community Local Image Upload Boundary v1

The boundary is local DDEV only. An authenticated author submits one JPEG, PNG, or WebP through a nonce-protected multipart request. The server enforces 10 MB, checks detected MIME with WordPress, uses `wp_handle_upload`, normalizes the result to an uploads-relative reference, and rejects paths, remote URLs, SVG, GIF, and mismatches.

Compatibility data stores metadata and the normalized reference only. It never stores raw bytes, base64, arbitrary filesystem paths, or remote sources. Production upload is not authorized. Abandoned-file cleanup, quotas, moderation, retention, malware scanning, and observability require a later ticket.

FIX001 validates alt text before `wp_handle_upload` and removes an upload made
by an attempted publication if a later publication step fails. Local-upload
references accept sanitized filenames containing periods and subdirectories;
traversal, schemes, absolute paths, and backslashes remain rejected.
