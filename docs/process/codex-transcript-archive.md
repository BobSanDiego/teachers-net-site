# Codex Transcript Archive

Status: maintained archival tooling
Owner: `tools/codex_archive/codex_transcript_archive.py`

## Purpose

The Codex transcript archive preserves a compact readable record of selected
Codex conversations for engineering continuity and future ChatGPT handoff.

Raw Codex JSONL session records remain the forensic authority. They live
outside the repository, typically under:

- `/mnt/c/Users/bobre/.codex/archived_sessions/`
- `/mnt/c/Users/bobre/.codex/sessions/`

Raw JSONL must not be committed, copied wholesale into Report/Hopper, or placed
into a handoff bundle.

## Archive paths

- Maintained renderer: `tools/codex_archive/codex_transcript_archive.py`
- Tests: `tools/codex_archive/test_codex_transcript_archive.py`
- Canonical Codex fossil:
  `docs/process/codex-conversation-archive/codex-conversation-fossil.md`
- Machine-readable manifest:
  `docs/process/codex-conversation-archive/codex-conversation-manifest.json`
- Per-session derived transcripts:
  `docs/process/codex-conversation-archive/sessions/`

The Codex fossil is separate from the canonical ChatGPT transcript archive. The
two streams may be consumed together by a future handoff package, but this
archive does not rewrite or merge ChatGPT transcripts.

## FAST mode

FAST mode is the routine path.

It performs one streaming pass over a newly selected closed Codex JSONL source,
extracts only schema-allowlisted visible user/assistant message records, applies
the bounded credential/publication gate, writes a compact derived session
transcript, updates the canonical fossil, and records provenance in the
manifest.

FAST mode does not:

- use Codex thread-reader pagination;
- double-render;
- perform broad diagnostic statistics;
- rerender unchanged incorporated sessions;
- rehash/rerender every historical source during routine handoff.

Run:

```bash
python3 tools/codex_archive/codex_transcript_archive.py archive \
  --mode fast \
  --source /path/to/closed-codex-session.jsonl
```

If a session is already incorporated and its recorded source metadata is
unchanged, FAST reports `NO_NEW_CLOSED_SESSIONS` and leaves the canonical body
unchanged.

If an incorporated source's size or mtime changes, FAST stops rather than
silently replacing history.

If a session is already incorporated from the recorded source and that source's
size/mtime are unchanged, FAST reports `NO_NEW_CLOSED_SESSIONS`; it does not
rerender unchanged incorporated sessions. If another source claims the same
session ID, the alternate source is refused unless a future explicit
reconciliation ticket approves replacement.

## VERIFY mode

VERIFY mode is explicit and exceptional.

Use it when:

- renderer logic changes;
- the raw schema appears to change;
- archival integrity is questioned;
- a session appears anomalous;
- an audit requests stronger proof.

VERIFY mode uses the same core extraction semantics as FAST mode, but may add
double rendering, output comparison, expanded statistics, and source/manifest
reconciliation.

Run:

```bash
python3 tools/codex_archive/codex_transcript_archive.py verify \
  --source /path/to/closed-codex-session.jsonl
```

## Extraction contract

The renderer uses schema-aware allowlisting as the primary boundary:

- include `response_item` records whose payload is a `message`;
- include only `role=user` and `role=assistant`;
- preserve selected conversational text verbatim;
- preserve source order, source line, timestamp, session identity, and stable
  role-aware IDs such as `CODEX-019f605b-U0001`.

The renderer excludes system/developer messages, injected internal context,
tool definitions, tool schemas, hidden/encrypted reasoning, world state,
compaction machinery, raw command output, raw tool calls, raw tool output,
environment dumps, and other execution machinery.

Prefix filtering is retained only as defense in depth for known injected context
that may appear in message-shaped records. It is not the primary inclusion
mechanism.

## Credential and publication gate

Allowlisting is the primary safety boundary. After extraction, the derived
conversation is scanned for bounded credential-like patterns, including password
assignments, token/API-key assignments, Authorization headers, private-key
blocks, and common token prefixes.

If no probable credential pattern is found, the archival and safe handoff
representations may be identical.

If probable credential content is found:

1. preserve the raw source untouched outside the repository;
2. preserve provenance in the manifest;
3. write a redacted safe publication derivative;
4. record that the safe derivative is not byte-verbatim;
5. do not publish the unsafe verbatim derivative into normal handoff material.

This is a bounded safety gate, not enterprise DLP.

## Session discovery

Discovery inventories candidate Codex JSONL sources and classifies them as:

- `INCLUDE`: clearly relevant closed Job Center sessions;
- `ACTIVE / DEFERRED`: relevant but still active or unsafe to finalize;
- `AMBIGUOUS`: relevance or identity is not established safely;
- `OUT_OF_SCOPE`: unrelated sessions.

Run:

```bash
python3 tools/codex_archive/codex_transcript_archive.py discover
```

Do not blindly archive every Codex session on the machine. Ambiguous sessions
must not be incorporated automatically.

Discovery recurses the known Codex session roots because active sessions are
stored under dated subdirectories. The source location is part of the
classification:

- `ARCHIVED`: closed source under `.codex/archived_sessions`;
- `ACTIVE`: live source under `.codex/sessions`;
- `UNKNOWN`: explicitly supplied source outside the known roots.

Same-session sources are grouped before selection. Selection must be
deterministic and must record one of these source-family relationships:

- `SINGLE_SOURCE`;
- `IDENTICAL_DUPLICATES`;
- `PROVEN_SUPERSET`;
- `SOURCE_CONFLICT`;
- `ACTIVE_AND_ARCHIVED_PAIR`;
- `UNKNOWN_RELATIONSHIP`.

Active sources are never incorporated as closed canonical transcripts. They are
listed as `ACTIVE / DEFERRED` until the session is closed or archived.
Conflicting archived same-session sources stop incorporation; the archive must
not silently choose a different fossil history.

## Incremental update procedure

1. Discover candidate sessions.
2. Select newly closed, clearly relevant Job Center sessions.
3. Run FAST mode on each selected source.
4. Confirm no raw JSONL entered Git or Report/Hopper.
5. Run tests before committing renderer or manifest changes.
6. Use VERIFY mode only when stronger archival proof is required.
7. Keep the ChatGPT transcript archive untouched.

## Formal ticket authority terminator

New formal Codex tickets that are issued for transcript/archive or workflow
cycle execution must be complete, self-delimiting authority blocks. The durable
terminator is:

```text
END TICKET — <TICKET-ID>
```

The `<TICKET-ID>` must exactly match the `Ticket:` identifier in the same
`TICKET READY FOR CODEX` block. Missing or mismatched terminators are treated as
truncated or malformed ticket authority and must stop execution until corrected.

Continuation and amendment authority remains valid when supplied by the
Engineering Director, but it must be preserved alongside the original ticket in
the cycle package so the executed authority is auditable.

## Active-session limitation

Active Codex sessions can still be changing. Do not incorporate them into the
canonical fossil until closed/archived unless an explicit future workflow
defines safe active-session snapshot handling.

## Future handoff relationship

A future one-drop handoff package may consume:

- the canonical ChatGPT fossil;
- this canonical Codex fossil;
- the current Project Cursor / Engineering Handoff;
- the current Report/Hopper package.

That handoff package is not implemented by this archive subsystem.
