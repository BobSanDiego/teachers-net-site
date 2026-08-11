# Durable Handoff Report/Hopper Specification

The durable handoff payload lives in the Windows-accessible HANDOFFS archive.
Report contains a REPORT_REQUIRED durable-handoff receipt with checkpoint path,
member hashes, validation, and publication status. Hopper contains that receipt
plus supporting lifecycle evidence. Ordinary REPORT_REQUIRED ticket artifacts
remain subject to the normal completeness rule.

## Upload transport is not an artifact-count ceiling

Codex permits at most 20 directly uploaded files in one upload operation. A
ZIP is one direct upload for that purpose and may contain more than 20 members
when appropriate. This ceiling does not limit the number of Report/Hopper
artifacts, handoff members, transcript sources, or archive members.

Use ZIP packaging when it materially simplifies transport of a related set,
but preserve source filenames, provenance, boundaries, and required hashes.
Do not apply an 18-, 19-, or 20-file general packaging threshold, and do not
rewrite historical reports or fossil evidence to satisfy a transport limit.
