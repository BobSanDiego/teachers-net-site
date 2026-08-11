# Durable Handoff Report/Hopper Specification

Workflow version: V2. `tools/hopper/clean_cycle.py` is the single terminal
publication/finalization owner and resolves project paths from registered
project records.

## Report-cache routing default

Every generated completion, bootstrap, diagnostic, or lifecycle report must be
published to the active project's registered Report cache, resolved from its
project record and clean-cycle routing. Do not leave the authoritative report
only in a repository `tmp/` staging location or another project's cache.

Cycle ownership follows the workstream/objective owner, not an acceptance
fixture. A Profile, Views, or other project used to exercise shared tooling
does not own the shared tooling report unless it is itself the objective owner.

An alternate report location is permitted only when the Engineer explicitly
specifies it. Record that exception in the cycle manifest and terminal report.

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
# Core cycle fields

The machine-readable cycle record's `artifacts` array is an optional list of
additional collected evidence. It may be empty when the required terminal
files are represented by the record's first-class `report_file`,
`manifest_file`, `cycle_record_file`, and generated output fields. An empty
`artifacts` array never waives those required files: `tools/hopper/clean_cycle.py`
must still publish and validate them in both Report and Hopper.

## Workflow V2 report tiers

Every formal cycle contains the terminal report, manifest, cycle JSON, and
source ticket. FAST adds only decisive evidence when needed. STANDARD adds
focused changed-hunk/test evidence as needed. DIAGNOSTIC contains targeted
causal observations. CONVERGENCE contains the terminal consolidated evidence,
acceptance ledger/checkpoints as useful, and final diff/commit identity; it
does not package each internal experiment as a separate formal cycle.

Committed Git-addressable source files are not copied automatically. Record
commit/blob identity unless the full source is uncommitted, generated/external,
not Git-addressable, or explicitly required for review/provenance.

Cycle JSON records Workflow V2, objective owner, acceptance fixture(s), mode,
evidence class, objective state, acceptance ledger, known reasoning posture,
recommended next posture, attempt/checkpoint counts, rework cause, Report/Hopper
bytes, and reliable execution/human-wait timing when available. Unknown values
remain null; never infer timing or reasoning posture.
