# Shared Transcript Archive Specification

Closing transcript sources are preserved verbatim with source path, SHA-256,
conversation identity when known, and an explicit source boundary. Exact
already-incorporated hashes are not appended again. Uncertain overlap is
retained as separate provenance; no AI summarization, semantic filtering, or
invented chronology is permitted.

When an exact historical Codex session is readable through the Codex
thread/session interface but its raw JSONL is unavailable, the shared archive
may use the bounded exact-session visible-thread fallback. Exhaust the
interface's pagination and require an explicit final `hasMore=false` boundary;
otherwise fail closed and request the source export. Preserve visible user and
assistant content, ordering, exposed stable IDs, timestamps, session identity,
page boundaries, and hashes verbatim. Exclude reasoning, tool calls, file
changes, hidden context, and internal payloads. Label the result
`CODEX_VISIBLE_THREAD_DERIVATIVE` and state that it does not claim raw JSONL or
internal-event equivalence. Never substitute a similarly named session.
