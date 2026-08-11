# Durable Handoff Report/Hopper Specification

The durable handoff payload lives in the Windows-accessible HANDOFFS archive.
Report contains a REPORT_REQUIRED durable-handoff receipt with checkpoint path,
member hashes, validation, and publication status. Hopper contains that receipt
plus supporting lifecycle evidence. Ordinary REPORT_REQUIRED ticket artifacts
remain subject to the normal completeness rule.
