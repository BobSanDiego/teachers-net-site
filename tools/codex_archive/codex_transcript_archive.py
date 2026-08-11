#!/usr/bin/env python3
"""Maintain a compact Codex visible-conversation archive.

This tool treats raw Codex JSONL session records as external forensic source
authority and publishes deterministic, human-readable derived transcripts.

Normal mode is FAST: one streaming pass for newly incorporated closed sessions,
bounded credential scan, compact session artifact, and manifest/fossil update.
VERIFY mode adds double-render comparison and expanded source reconciliation.
"""

from __future__ import annotations

import argparse
import collections
import dataclasses
import datetime as dt
import hashlib
import json
import os
import re
import shutil
import sys
import tempfile
import time
from pathlib import Path
from typing import Any, Iterable


RENDERER_VERSION = "codex-transcript-archive-v1"
VISIBLE_THREAD_DERIVATIVE_VERSION = "codex-visible-thread-derivative-v1"
DEFAULT_ARCHIVE_DIR = Path("docs/process/codex-conversation-archive")
DEFAULT_SOURCE_DIRS = [
    Path("/mnt/c/Users/bobre/.codex/archived_sessions"),
    Path("/mnt/c/Users/bobre/.codex/sessions"),
]
KNOWN_JOB_CENTER_SESSION_IDS = {
    "019f605b-5be2-7802-8857-4d545657645a",
}
KNOWN_VIEWS_SESSION_IDS = {
    "019fce24-5428-7230-9464-05c4506821cf",
    "019fe1c8-3cb3-7610-ad3f-36bd12545839",
}
KNOWN_COMMUNITY_SESSION_IDS = {
    "019f7d5b-e9cf-7182-b1c7-f99e21fe9e42",
    "019fa8c0-d693-7923-8bcc-c8d201092e7c",
}

INTERNAL_PREFIXES = (
    "<recommended_plugins>",
    "<app-context>",
    "<permissions instructions>",
    "<collaboration_mode>",
    "<apps_instructions>",
    "<plugins_instructions>",
    "# AGENTS.md instructions",
    "Knowledge cutoff:",
    "You are an AI assistant",
    "You are Codex,",
    "You have access to a memory folder",
)

SECRET_PATTERNS = {
    "password_assignment": re.compile(r"(?i)\b(password|passwd|pwd)\b\s*[:=]\s*\S+"),
    "api_key_assignment": re.compile(r"(?i)\b(api[_-]?key|access[_-]?token|secret|bearer)\b\s*[:=]\s*\S+"),
    "authorization_header": re.compile(r"(?i)\bauthorization\s*:\s*\S+"),
    "private_key_block": re.compile(r"-----BEGIN [A-Z ]*PRIVATE KEY-----"),
    "common_token_prefix": re.compile(r"\b(?:sk-[A-Za-z0-9_-]{20,}|ghp_[A-Za-z0-9_]{20,}|xox[baprs]-[A-Za-z0-9-]{20,})\b"),
}
TICKET_READY_LINE = "TICKET READY FOR CODEX"
TICKET_END_RE = re.compile(r"^END TICKET — (?P<ticket_id>\S+)\s*$")
TICKET_ID_RE = re.compile(r"^\s*Ticket:\s*(?P<ticket_id>\S+)\s*$", re.MULTILINE)


@dataclasses.dataclass
class Message:
    role: str
    text: str
    source_line: int
    timestamp: str | None
    message_id: str


@dataclasses.dataclass
class RenderResult:
    session_id: str
    raw_source_path: str
    raw_source_sha256: str
    raw_bytes: int
    raw_lines: int
    first_timestamp: str | None
    last_timestamp: str | None
    transcript_text: str
    transcript_sha256: str
    transcript_bytes: int
    user_message_count: int
    assistant_message_count: int
    credential_status: str
    credential_match_classes: dict[str, int]
    publication_status: str
    redacted_text: str | None
    completeness_classification: str
    extraction_elapsed_seconds: float
    safety_elapsed_seconds: float
    event_counts: dict[str, int]
    excluded_counts: dict[str, int]


@dataclasses.dataclass(frozen=True)
class SessionSource:
    path: Path
    session_id: str
    location: str
    raw_bytes: int
    raw_sha256: str
    cwd: str
    title: str
    preview: str
    source_mtime: float

    def as_inventory(self) -> dict[str, Any]:
        return {
            "path": str(self.path),
            "session_id": self.session_id,
            "source_location": self.location,
            "bytes": self.raw_bytes,
            "sha256": self.raw_sha256,
            "cwd": self.cwd,
            "title": self.title,
            "preview": self.preview,
            "source_mtime": self.source_mtime,
        }


@dataclasses.dataclass
class SourceFamily:
    session_id: str
    relationship_status: str
    selected_source: SessionSource | None
    include: bool
    active_deferred: list[SessionSource]
    ambiguous: bool
    reason: str
    sources: list[SessionSource]


def sha256_bytes(data: bytes) -> str:
    return hashlib.sha256(data).hexdigest()


def sha256_file(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as f:
        for chunk in iter(lambda: f.read(1024 * 1024), b""):
            h.update(chunk)
    return h.hexdigest()


def session_short(session_id: str) -> str:
    return session_id.split("-", 1)[0]


def safe_slug(value: str) -> str:
    return re.sub(r"[^A-Za-z0-9_.-]+", "-", value).strip("-") or "unknown"


def visible_thread_messages(thread: dict[str, Any]) -> list[dict[str, Any]]:
    """Extract visible user/assistant messages from a Codex app page.

    The app reader returns pages newest-first.  Only userMessage and
    agentMessage items are eligible; reasoning, tool calls, and file changes
    are deliberately excluded.  No semantic filtering or summarization occurs.
    """
    messages: list[dict[str, Any]] = []
    for turn in reversed(thread.get("turns") or []):
        for item in turn.get("items") or []:
            item_type = item.get("type")
            if item_type not in {"userMessage", "agentMessage"}:
                continue
            if item_type == "userMessage":
                content = item.get("content") or []
                text = extract_text(content)
                role = "user"
            else:
                text = item.get("text") or ""
                role = "assistant"
            if not text:
                continue
            messages.append({
                "role": role,
                "text": text,
                "message_id": str(item.get("id") or ""),
                "turn_id": str(turn.get("id") or ""),
                "started_at": turn.get("startedAt"),
                "completed_at": turn.get("completedAt"),
            })
    return messages


def render_visible_thread_derivative(pages: list[dict[str, Any]], output: Path) -> dict[str, Any]:
    """Render a complete, paginated Codex visible-thread recovery.

    ``pages`` must be ordered oldest-to-newest or newest-to-oldest; page
    ordering is normalized from the reader's declared newest-first contract.
    Completeness is fail-closed unless the final page reports hasMore=false.
    """
    if not pages:
        raise ValueError("visible thread recovery has no pages")
    first = pages[0]
    thread = first.get("thread") or {}
    expected_id = thread.get("id")
    if not expected_id:
        raise ValueError("visible thread recovery has no exact session id")
    for page in pages:
        if (page.get("thread") or {}).get("id") != expected_id:
            raise ValueError("visible thread pages contain conflicting session ids")
    if pages[-1].get("page", {}).get("hasMore") is not False:
        raise ValueError("visible thread recovery is incomplete; export required")
    turns: list[dict[str, Any]] = []
    for page in pages:
        turns.extend(page.get("turns") or [])
    merged = dict(first)
    merged["turns"] = turns
    messages = visible_thread_messages(merged)
    if not messages:
        raise ValueError("visible thread recovery contains no visible messages")
    lines = [
        "CODEX VISIBLE THREAD DERIVATIVE",
        f"Derivative version: {VISIBLE_THREAD_DERIVATIVE_VERSION}",
        f"Session ID: {expected_id}",
        f"Title: {thread.get('title', '')}",
        "Provenance: CODEX_VISIBLE_THREAD_DERIVATIVE",
        "Completeness: VISIBLE SESSION RECOVERY COMPLETE",
        "Raw-source equivalence: NOT CLAIMED",
        "Extraction: Codex app exact-session reader, bounded pagination exhausted",
        f"Pages recovered: {len(pages)}",
        f"Visible messages recovered: {len(messages)}",
        f"First visible boundary: {messages[0]['message_id']}",
        f"Last visible boundary: {messages[-1]['message_id']}",
        "",
    ]
    for message in messages:
        lines.extend([
            f"[{message['role'].upper()}] {message['message_id']} turn={message['turn_id']}",
            message["text"],
            "",
        ])
    output.parent.mkdir(parents=True, exist_ok=True)
    output.write_text("\n".join(lines), encoding="utf-8")
    return {
        "session_id": expected_id,
        "title": thread.get("title", ""),
        "provenance": "CODEX_VISIBLE_THREAD_DERIVATIVE",
        "completeness": "VISIBLE SESSION RECOVERY COMPLETE",
        "raw_source_available": False,
        "raw_equivalence_claimed": False,
        "pages": len(pages),
        "visible_message_count": len(messages),
        "derived_bytes": output.stat().st_size,
        "derived_sha256": sha256_file(output),
        "first_message_id": messages[0]["message_id"],
        "last_message_id": messages[-1]["message_id"],
    }


def event_timestamp(obj: dict[str, Any]) -> str | None:
    for container in (obj, obj.get("payload")):
        if not isinstance(container, dict):
            continue
        for key in ("timestamp", "created_at", "createdAt"):
            value = container.get(key)
            if isinstance(value, str) and value:
                return value
            if isinstance(value, (int, float)):
                try:
                    return dt.datetime.fromtimestamp(value, tz=dt.timezone.utc).isoformat()
                except Exception:
                    pass
    return None


def extract_text(content: Any) -> str:
    if isinstance(content, str):
        return content
    parts: list[str] = []
    if isinstance(content, list):
        for item in content:
            if isinstance(item, str):
                parts.append(item)
            elif isinstance(item, dict):
                text = item.get("text")
                if isinstance(text, str):
                    parts.append(text)
    elif isinstance(content, dict):
        text = content.get("text")
        if isinstance(text, str):
            parts.append(text)
    return "".join(parts)


def is_injected_context_text(text: str) -> bool:
    stripped = text.lstrip()
    return any(stripped.startswith(prefix) for prefix in INTERNAL_PREFIXES)


def canonical_visible_message(obj: dict[str, Any]) -> tuple[str, str] | None:
    """Return the canonical visible message representation, if any.

    Primary inclusion is schema-driven: only response_item/message records with
    user or assistant role are eligible. Prefix filtering is defense in depth for
    known injected context that can appear in message-shaped records.
    """
    if obj.get("type") != "response_item":
        return None
    payload = obj.get("payload")
    if not isinstance(payload, dict):
        return None
    if payload.get("type") != "message":
        return None
    role = payload.get("role")
    if role not in {"user", "assistant"}:
        return None
    text = extract_text(payload.get("content"))
    if not text or is_injected_context_text(text):
        return None
    return role, text


def classify_event(obj: dict[str, Any]) -> str:
    top = obj.get("type", "<missing>")
    payload = obj.get("payload")
    if isinstance(payload, dict):
        ptype = payload.get("type")
        role = payload.get("role")
        if ptype and role:
            return f"{top}:{ptype}:{role}"
        if ptype:
            return f"{top}:{ptype}"
    return str(top)


def classify_exclusion(obj: dict[str, Any]) -> str:
    if obj.get("type") == "session_meta":
        return "session_meta"
    payload = obj.get("payload")
    if isinstance(payload, dict):
        ptype = payload.get("type")
        role = payload.get("role")
        if ptype == "message" and role:
            return f"message_excluded:{role}"
        if ptype in {"function_call", "function_call_output", "custom_tool_call", "custom_tool_call_output", "mcpToolCall", "fileChange", "imageGeneration"}:
            return "tool_or_execution_event_excluded"
        if ptype and "reason" in str(ptype):
            return "reasoning_or_reasoning_summary_excluded"
        if ptype:
            return f"payload_excluded:{ptype}"
    return f"record_excluded:{obj.get('type', '<missing>')}"


def read_session_meta(path: Path, max_lines: int = 160) -> dict[str, Any]:
    meta: dict[str, Any] = {
        "path": str(path),
        "bytes": path.stat().st_size if path.exists() else 0,
        "session_id": "",
        "cwd": "",
        "title": "",
        "preview": "",
    }
    previews: list[str] = []
    with path.open("r", encoding="utf-8", errors="replace") as f:
        for index, line in enumerate(f, start=1):
            if index > max_lines:
                break
            try:
                obj = json.loads(line)
            except json.JSONDecodeError:
                continue
            payload = obj.get("payload")
            if obj.get("type") == "session_meta" and isinstance(payload, dict):
                meta["session_id"] = payload.get("id") or payload.get("session_id") or meta["session_id"]
                meta["cwd"] = payload.get("cwd") or meta["cwd"]
                meta["title"] = payload.get("title") or meta["title"]
            if isinstance(payload, dict) and payload.get("type") in {"user_message", "agent_message"}:
                msg = payload.get("message") or payload.get("text")
                if isinstance(msg, str) and msg:
                    previews.append(msg[:160].replace("\n", " "))
            visible = canonical_visible_message(obj)
            if visible:
                previews.append(visible[1][:160].replace("\n", " "))
    meta["preview"] = " | ".join(previews[:4])
    if not meta["session_id"]:
        m = re.search(r"([0-9a-f]{8}-[0-9a-f-]{27,})", path.name)
        if m:
            meta["session_id"] = m.group(1)
    return meta


def classify_candidate(meta: dict[str, Any], already_seen_ids: set[str]) -> str:
    text = " ".join(str(meta.get(k, "")) for k in ("path", "cwd", "title", "preview")).lower()
    session_id = str(meta.get("session_id") or "")
    if session_id in already_seen_ids:
        return "AMBIGUOUS"
    if "lessonbank" in text or "birdmart" in text or "community3" in text:
        return "OUT_OF_SCOPE"
    if session_id in KNOWN_JOB_CENTER_SESSION_IDS:
        return "INCLUDE"
    if session_id in KNOWN_VIEWS_SESSION_IDS:
        return "INCLUDE"
    job_signals = ("job center", "jobcenter", "jc0", "tnet-jobs", "job finder", "job wizard", "job board")
    if any(signal in text for signal in job_signals):
        return "INCLUDE"
    return "AMBIGUOUS"


def source_location(path: Path) -> str:
    resolved = path.resolve()
    for root in DEFAULT_SOURCE_DIRS:
        try:
            resolved.relative_to(root.resolve())
        except ValueError:
            continue
        if root.name == "sessions":
            return "ACTIVE"
        if root.name == "archived_sessions":
            return "ARCHIVED"
    return "UNKNOWN"


def iter_jsonl_sources(source_dirs: Iterable[Path]) -> list[Path]:
    paths: list[Path] = []
    for source_dir in source_dirs:
        if not source_dir.exists():
            continue
        paths.extend(path for path in source_dir.rglob("*.jsonl") if path.is_file())
    return sorted(set(paths), key=lambda p: str(p))


def read_session_source(path: Path) -> SessionSource:
    meta = read_session_meta(path)
    return SessionSource(
        path=path,
        session_id=str(meta.get("session_id") or ""),
        location=source_location(path),
        raw_bytes=int(meta.get("bytes") or path.stat().st_size),
        raw_sha256=sha256_file(path),
        cwd=str(meta.get("cwd") or ""),
        title=str(meta.get("title") or ""),
        preview=str(meta.get("preview") or ""),
        source_mtime=path.stat().st_mtime,
    )


def file_startswith(larger: Path, smaller: Path) -> bool:
    with larger.open("rb") as big, smaller.open("rb") as small:
        for chunk in iter(lambda: small.read(1024 * 1024), b""):
            if big.read(len(chunk)) != chunk:
                return False
    return True


def relevant_classification_for_source(source: SessionSource) -> str:
    meta = source.as_inventory()
    return classify_candidate(meta, set())


def reconcile_source_family(session_id: str, sources: list[SessionSource]) -> SourceFamily:
    ordered = sorted(sources, key=lambda s: (s.location != "ARCHIVED", -s.raw_bytes, str(s.path)))
    active = [s for s in ordered if s.location == "ACTIVE"]
    archived = [s for s in ordered if s.location == "ARCHIVED"]
    selectable_closed = [s for s in ordered if s.location != "ACTIVE"]
    relevant = any(relevant_classification_for_source(s) == "INCLUDE" for s in ordered)
    out_of_scope = all(relevant_classification_for_source(s) == "OUT_OF_SCOPE" for s in ordered)

    if out_of_scope:
        return SourceFamily(session_id, "SINGLE_SOURCE" if len(ordered) == 1 else "UNKNOWN_RELATIONSHIP", None, False, [], False, "out of scope", ordered)
    if not relevant:
        return SourceFamily(session_id, "SINGLE_SOURCE" if len(ordered) == 1 else "UNKNOWN_RELATIONSHIP", None, False, active, True, "ambiguous relevance", ordered)
    if active and not archived:
        return SourceFamily(session_id, "SINGLE_SOURCE" if len(ordered) == 1 else "UNKNOWN_RELATIONSHIP", None, False, active, False, "active source deferred", ordered)

    selectable = selectable_closed
    if len(ordered) == 1:
        selected = selectable[0] if selectable else None
        return SourceFamily(session_id, "SINGLE_SOURCE", selected, selected is not None, active, False, "single archived source" if selected else "single active source deferred", ordered)

    unique_hashes = {s.raw_sha256 for s in ordered}
    if len(unique_hashes) == 1:
        selected = selectable[0] if selectable else None
        return SourceFamily(session_id, "IDENTICAL_DUPLICATES", selected, selected is not None, active, False, "byte-identical duplicate sources", ordered)

    relationship = "ACTIVE_AND_ARCHIVED_PAIR" if active and archived else "UNKNOWN_RELATIONSHIP"
    biggest = max(ordered, key=lambda s: (s.raw_bytes, str(s.path)))
    if all(biggest.path == s.path or file_startswith(biggest.path, s.path) for s in ordered):
        relationship = "PROVEN_SUPERSET" if not active else "ACTIVE_AND_ARCHIVED_PAIR"
        selected = biggest if biggest.location != "ACTIVE" else (max(archived, key=lambda s: (s.raw_bytes, str(s.path))) if archived else None)
        reason = "largest source is a byte-prefix superset" if biggest.location != "ACTIVE" else "active source is superset; archived source deferred for closed-canonical safety"
        return SourceFamily(session_id, relationship, selected, selected is not None, active, False, reason, ordered)

    if active and archived:
        selected = max(archived, key=lambda s: (s.raw_bytes, str(s.path)))
        return SourceFamily(session_id, relationship, selected, True, active, False, "active and archived source differ; closed archived source selected and active source deferred", ordered)

    return SourceFamily(session_id, "SOURCE_CONFLICT", None, False, active, True, "same session id has conflicting archived sources", ordered)


def source_family_inventory(family: SourceFamily) -> dict[str, Any]:
    return {
        "session_id": family.session_id,
        "relationship_status": family.relationship_status,
        "selected_source": str(family.selected_source.path) if family.selected_source else "",
        "include": family.include,
        "ambiguous": family.ambiguous,
        "reason": family.reason,
        "sources": [source.as_inventory() for source in family.sources],
    }


def discover(source_dirs: Iterable[Path]) -> dict[str, list[dict[str, Any]]]:
    grouped: dict[str, list[dict[str, Any]]] = {
        "include": [],
        "active_deferred": [],
        "ambiguous": [],
        "out_of_scope": [],
        "source_families": [],
    }
    families: dict[str, list[SessionSource]] = collections.defaultdict(list)
    for path in iter_jsonl_sources(source_dirs):
        source = read_session_source(path)
        sid = source.session_id or safe_slug(path.stem)
        families[sid].append(source)
    for session_id in sorted(families):
        family = reconcile_source_family(session_id, families[session_id])
        grouped["source_families"].append(source_family_inventory(family))
        if family.include and family.selected_source:
            grouped["include"].append(family.selected_source.as_inventory() | {
                "relationship_status": family.relationship_status,
                "family_reason": family.reason,
            })
        for active in family.active_deferred:
            grouped["active_deferred"].append(active.as_inventory() | {
                "classification": "ACTIVE / DEFERRED",
                "relationship_status": family.relationship_status,
                "family_reason": family.reason,
            })
        if family.ambiguous:
            grouped["ambiguous"].append(source_family_inventory(family))
        if family.reason == "out of scope":
            grouped["out_of_scope"].extend(source.as_inventory() for source in family.sources)
    return grouped


def scan_and_redact(text: str) -> tuple[str, dict[str, int], str | None]:
    counts: dict[str, int] = {}
    redacted = text
    for name, pattern in SECRET_PATTERNS.items():
        def repl(match: re.Match[str]) -> str:
            counts[name] = counts.get(name, 0) + 1
            return f"[REDACTED:{name}]"
        redacted = pattern.sub(repl, redacted)
    if counts:
        return "POTENTIAL_CREDENTIAL_MATCHES", counts, redacted
    return "NO_OBVIOUS_CREDENTIAL_PATTERNS", {}, None


def extract_ticket_id(text: str) -> str:
    match = TICKET_ID_RE.search(text)
    if not match:
        raise ValueError("ticket authority missing Ticket: identifier")
    return match.group("ticket_id").strip()


def validate_ticket_payload(text: str, require_terminator: bool = True) -> dict[str, Any]:
    lines = [line.rstrip() for line in text.splitlines()]
    first = next((line.strip() for line in lines if line.strip()), "")
    if first != TICKET_READY_LINE:
        raise ValueError(f"ticket authority must begin with {TICKET_READY_LINE!r}")
    ticket_id = extract_ticket_id(text)
    nonempty = [line.strip() for line in lines if line.strip()]
    terminator = nonempty[-1] if nonempty else ""
    expected = f"END TICKET — {ticket_id}"
    if require_terminator and terminator != expected:
        raise ValueError(f"ticket authority terminator mismatch: expected {expected!r}, got {terminator!r}")
    return {
        "ticket_id": ticket_id,
        "terminator": terminator,
        "terminator_valid": terminator == expected,
    }


def compose_ticket_authority(parts: list[str]) -> str:
    """Preserve original ticket plus ordered continuations/amendments."""
    cleaned = [part.strip() for part in parts if part and part.strip()]
    if not cleaned:
        raise ValueError("no ticket authority parts supplied")
    validate_ticket_payload(cleaned[0])
    return "\n\n--- CONTINUATION / AMENDMENT ---\n\n".join(cleaned) + "\n"


def render_session(path: Path, verify_stats: bool = False) -> RenderResult:
    raw_bytes = path.stat().st_size
    raw_hash = hashlib.sha256()
    messages: list[Message] = []
    event_counts: collections.Counter[str] = collections.Counter()
    excluded_counts: collections.Counter[str] = collections.Counter()
    first_ts: str | None = None
    last_ts: str | None = None
    session_id = ""
    user_count = 0
    assistant_count = 0
    start = time.perf_counter()
    raw_lines = 0

    with path.open("rb") as raw:
        for raw_line_bytes in raw:
            raw_hash.update(raw_line_bytes)
            raw_lines += 1
            line = raw_line_bytes.decode("utf-8", errors="replace")
            try:
                obj = json.loads(line)
            except json.JSONDecodeError:
                excluded_counts["json_decode_error"] += 1
                continue
            event_counts[classify_event(obj)] += 1
            payload = obj.get("payload")
            if obj.get("type") == "session_meta" and isinstance(payload, dict):
                session_id = payload.get("id") or payload.get("session_id") or session_id
            ts = event_timestamp(obj)
            if ts:
                if first_ts is None:
                    first_ts = ts
                last_ts = ts
            visible = canonical_visible_message(obj)
            if not visible:
                if verify_stats:
                    excluded_counts[classify_exclusion(obj)] += 1
                continue
            role, text = visible
            if role == "user":
                user_count += 1
                mid = f"CODEX-{session_short(session_id or 'unknown')}-U{user_count:04d}"
            else:
                assistant_count += 1
                mid = f"CODEX-{session_short(session_id or 'unknown')}-A{assistant_count:04d}"
            messages.append(Message(role.upper(), text, raw_lines, ts, mid))

    extraction_elapsed = time.perf_counter() - start
    if not session_id:
        session_id = read_session_meta(path).get("session_id") or safe_slug(path.stem)

    header = f"""===============================================================================
GENERATED CODEX CONVERSATION TRANSCRIPT
DERIVED ARTIFACT — RAW SOURCE REMAINS AUTHORITATIVE

Session ID:
  {session_id}
Raw source:
  {path}
Raw source SHA-256:
  {raw_hash.hexdigest()}
Raw source bytes:
  {raw_bytes}
Raw source event/line count:
  {raw_lines}
First source timestamp:
  {first_ts or ""}
Last source timestamp:
  {last_ts or ""}
Renderer/version:
  {RENDERER_VERSION}
Completeness classification:
  HIGH-CONFIDENCE VISIBLE CONVERSATION
===============================================================================
"""
    parts = [header]
    for msg in messages:
        parts.append(
            f"""
-------------------------------------------------------------------------------
{msg.message_id}
Role: {msg.role}
Source line: {msg.source_line}
Timestamp: {msg.timestamp or ""}

Text:
{msg.text}
"""
        )
    transcript = "".join(parts)
    safety_start = time.perf_counter()
    credential_status, credential_classes, redacted = scan_and_redact(transcript)
    safety_elapsed = time.perf_counter() - safety_start
    transcript_bytes = len(transcript.encode("utf-8"))
    return RenderResult(
        session_id=session_id,
        raw_source_path=str(path),
        raw_source_sha256=raw_hash.hexdigest(),
        raw_bytes=raw_bytes,
        raw_lines=raw_lines,
        first_timestamp=first_ts,
        last_timestamp=last_ts,
        transcript_text=transcript,
        transcript_sha256=sha256_bytes(transcript.encode("utf-8")),
        transcript_bytes=transcript_bytes,
        user_message_count=user_count,
        assistant_message_count=assistant_count,
        credential_status=credential_status,
        credential_match_classes=credential_classes,
        publication_status="SAFE_VERBATIM" if redacted is None else "REDACTED_PUBLICATION_DERIVATIVE",
        redacted_text=redacted,
        completeness_classification="HIGH-CONFIDENCE VISIBLE CONVERSATION",
        extraction_elapsed_seconds=extraction_elapsed,
        safety_elapsed_seconds=safety_elapsed,
        event_counts=dict(sorted(event_counts.items())),
        excluded_counts=dict(sorted(excluded_counts.items())),
    )


def load_manifest(path: Path) -> dict[str, Any]:
    if path.exists():
        return json.loads(path.read_text(encoding="utf-8"))
    return {
        "schema_version": "1.0",
        "renderer_version": RENDERER_VERSION,
        "canonical_fossil_path": "docs/process/codex-conversation-archive/codex-conversation-fossil.md",
        "sessions": [],
        "active_deferred_sessions": [],
        "ambiguous_sessions": [],
        "out_of_scope_sessions": [],
    }


def write_manifest(path: Path, manifest: dict[str, Any]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8")


def session_artifact_path(archive_dir: Path, session_id: str, redacted: bool = False) -> Path:
    suffix = "-safe-redacted" if redacted else ""
    return archive_dir / "sessions" / f"{safe_slug(session_id)}{suffix}.txt"


def write_fossil(archive_dir: Path, manifest: dict[str, Any]) -> None:
    fossil_path = archive_dir / "codex-conversation-fossil.md"
    sections: list[str] = [
        "# Canonical Codex Conversation Fossil Record\n\n",
        "Generated from compact derived Codex session transcripts. Raw JSONL session records remain the external forensic authority and are not committed here.\n\n",
    ]
    sessions = sorted(manifest.get("sessions", []), key=lambda s: (s.get("first_timestamp") or "", s.get("session_id") or ""))
    for item in sessions:
        sections.append("\n\n---\n\n")
        sections.append(f"## Codex Session {item['session_id']}\n\n")
        sections.append(f"- First timestamp: {item.get('first_timestamp') or ''}\n")
        sections.append(f"- Last timestamp: {item.get('last_timestamp') or ''}\n")
        sections.append(f"- Raw source: `{item.get('raw_source_path')}`\n")
        sections.append(f"- Raw SHA-256: `{item.get('raw_source_sha256')}`\n")
        sections.append(f"- Renderer: `{item.get('renderer_version')}`\n")
        sections.append(f"- Completeness: `{item.get('completeness_classification')}`\n")
        sections.append(f"- Publication status: `{item.get('publication_status')}`\n\n")
        transcript_path = archive_dir / item["safe_transcript_path"]
        sections.append(transcript_path.read_text(encoding="utf-8"))
    fossil_path.write_text("".join(sections).rstrip() + "\n", encoding="utf-8")


def already_incorporated(manifest: dict[str, Any], session_id: str) -> dict[str, Any] | None:
    for item in manifest.get("sessions", []):
        if item.get("session_id") == session_id:
            return item
    return None


def incorporate(path: Path, archive_dir: Path, mode: str) -> dict[str, Any]:
    archive_dir.mkdir(parents=True, exist_ok=True)
    (archive_dir / "sessions").mkdir(parents=True, exist_ok=True)
    manifest_path = archive_dir / "codex-conversation-manifest.json"
    manifest = load_manifest(manifest_path)
    discover_start = time.perf_counter()
    meta = read_session_meta(path)
    discovery_elapsed = time.perf_counter() - discover_start
    session_id = meta.get("session_id") or safe_slug(path.stem)

    existing = already_incorporated(manifest, session_id)
    if existing:
        if Path(str(existing.get("raw_source_path") or "")).resolve() != path.resolve():
            raise RuntimeError(f"session {session_id} already incorporated from {existing.get('raw_source_path')}; refusing alternate source {path.resolve()}")
        current_size = path.stat().st_size
        current_mtime = path.stat().st_mtime
        if existing.get("raw_bytes") != current_size:
            raise RuntimeError(f"incorporated source size changed for {session_id}; run VERIFY before updating")
        previous_mtime = existing.get("source_mtime")
        if previous_mtime is not None and abs(float(previous_mtime) - current_mtime) > 0.000001:
            raise RuntimeError(f"incorporated source mtime changed for {session_id}; run VERIFY before updating")
        return {
            "status": "NO_NEW_CLOSED_SESSIONS",
            "session_id": session_id,
            "discovery_elapsed_seconds": discovery_elapsed,
            "total_elapsed_seconds": discovery_elapsed,
            "rerendered": False,
            "source_mtime": current_mtime,
        }

    family_search_dirs = [path.parent]
    resolved_path = path.resolve()
    for default_root in DEFAULT_SOURCE_DIRS:
        try:
            resolved_path.relative_to(default_root.resolve())
        except ValueError:
            continue
        family_search_dirs = DEFAULT_SOURCE_DIRS
        break
    family_sources: list[SessionSource] = []
    for candidate in iter_jsonl_sources(family_search_dirs):
        candidate_meta = read_session_meta(candidate)
        if (candidate_meta.get("session_id") or safe_slug(candidate.stem)) == session_id:
            family_sources.append(read_session_source(candidate))
    family = reconcile_source_family(str(session_id), family_sources or [read_session_source(path)])
    selected_path = family.selected_source.path.resolve() if family.selected_source else None
    if family.ambiguous or selected_path is None:
        raise RuntimeError(f"source family for {session_id} is not safe to incorporate: {family.relationship_status} ({family.reason})")
    if selected_path != path.resolve():
        raise RuntimeError(f"source family for {session_id} selected {selected_path}; refusing non-canonical source {path.resolve()}")

    render_start = time.perf_counter()
    rendered = render_session(path, verify_stats=(mode == "verify"))
    verify: dict[str, Any] = {}
    if mode == "verify":
        second = render_session(path, verify_stats=True)
        verify = {
            "second_transcript_sha256": second.transcript_sha256,
            "byte_identical": rendered.transcript_text == second.transcript_text,
            "second_total_extraction_seconds": second.extraction_elapsed_seconds,
        }
        if not verify["byte_identical"]:
            raise RuntimeError(f"VERIFY double-render mismatch for {session_id}")
    extraction_elapsed = time.perf_counter() - render_start

    safe_text = rendered.redacted_text if rendered.redacted_text is not None else rendered.transcript_text
    redacted = rendered.redacted_text is not None
    safe_path = session_artifact_path(archive_dir, rendered.session_id, redacted=redacted)
    safe_path.write_text(safe_text, encoding="utf-8")
    archive_path = session_artifact_path(archive_dir, rendered.session_id, redacted=False)
    if not redacted:
        archive_path = safe_path
    else:
        archive_path.write_text(rendered.transcript_text, encoding="utf-8")
    safe_sha = sha256_file(safe_path)
    derived_sha = sha256_file(archive_path)

    update_start = time.perf_counter()
    record = {
        "session_id": rendered.session_id,
        "raw_source_path": rendered.raw_source_path,
        "raw_source_sha256": rendered.raw_source_sha256,
        "raw_bytes": rendered.raw_bytes,
        "raw_lines": rendered.raw_lines,
        "first_timestamp": rendered.first_timestamp,
        "last_timestamp": rendered.last_timestamp,
        "derived_transcript_path": str(archive_path.relative_to(archive_dir)),
        "derived_transcript_sha256": derived_sha,
        "derived_bytes": archive_path.stat().st_size,
        "safe_transcript_path": str(safe_path.relative_to(archive_dir)),
        "safe_transcript_sha256": safe_sha,
        "safe_bytes": safe_path.stat().st_size,
        "user_message_count": rendered.user_message_count,
        "assistant_message_count": rendered.assistant_message_count,
        "renderer_version": RENDERER_VERSION,
        "completeness_classification": rendered.completeness_classification,
        "credential_publication_status": rendered.credential_status,
        "credential_match_classes": rendered.credential_match_classes,
        "publication_status": rendered.publication_status,
        "archive_status": "INCORPORATED",
        "chronological_order": None,
        "source_mtime": path.stat().st_mtime,
        "source_family_relationship_status": family.relationship_status,
        "source_family_reason": family.reason,
    }
    manifest.setdefault("sessions", []).append(record)
    manifest["sessions"] = sorted(manifest["sessions"], key=lambda s: (s.get("first_timestamp") or "", s.get("session_id") or ""))
    for index, item in enumerate(manifest["sessions"], start=1):
        item["chronological_order"] = index
    write_fossil(archive_dir, manifest)
    write_manifest(manifest_path, manifest)
    manifest_elapsed = time.perf_counter() - update_start
    total_elapsed = discovery_elapsed + extraction_elapsed + manifest_elapsed

    return {
        "status": "INCORPORATED",
        "session_id": rendered.session_id,
        "mode": mode.upper(),
        "source_bytes": rendered.raw_bytes,
        "source_discovery_seconds": round(discovery_elapsed, 4),
        "extraction_seconds": round(rendered.extraction_elapsed_seconds, 4),
        "credential_publication_gate_seconds": round(rendered.safety_elapsed_seconds, 4),
        "manifest_update_seconds": round(manifest_elapsed, 4),
        "total_fast_archival_seconds": round(total_elapsed, 4),
        "user_message_count": rendered.user_message_count,
        "assistant_message_count": rendered.assistant_message_count,
        "publication_status": rendered.publication_status,
        "verify": verify,
    }


def command_discover(args: argparse.Namespace) -> int:
    dirs = [Path(p) for p in args.source_dir] if args.source_dir else DEFAULT_SOURCE_DIRS
    print(json.dumps(discover(dirs), indent=2, sort_keys=True))
    return 0


def command_archive(args: argparse.Namespace) -> int:
    result = incorporate(Path(args.source), Path(args.archive_dir), args.mode)
    print(json.dumps(result, indent=2, sort_keys=True))
    return 0


def command_verify(args: argparse.Namespace) -> int:
    with tempfile.TemporaryDirectory() as tmp:
        result = incorporate(Path(args.source), Path(tmp), "verify")
    print(json.dumps(result, indent=2, sort_keys=True))
    return 0


def command_validate_ticket(args: argparse.Namespace) -> int:
    payload = Path(args.ticket).read_text(encoding="utf-8")
    print(json.dumps(validate_ticket_payload(payload), indent=2, sort_keys=True))
    return 0


def command_visible_thread(args: argparse.Namespace) -> int:
    payload = json.loads(Path(args.input).read_text(encoding="utf-8"))
    pages = payload.get("pages") if isinstance(payload, dict) else payload
    result = render_visible_thread_derivative(pages, Path(args.output))
    print(json.dumps(result, indent=2, sort_keys=True))
    return 0


def main(argv: list[str] | None = None) -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    sub = parser.add_subparsers(dest="command", required=True)

    p_discover = sub.add_parser("discover")
    p_discover.add_argument("--source-dir", action="append")
    p_discover.set_defaults(func=command_discover)

    p_archive = sub.add_parser("archive")
    p_archive.add_argument("--source", required=True)
    p_archive.add_argument("--archive-dir", default=str(DEFAULT_ARCHIVE_DIR))
    p_archive.add_argument("--mode", choices=["fast", "verify"], default="fast")
    p_archive.set_defaults(func=command_archive)

    p_verify = sub.add_parser("verify")
    p_verify.add_argument("--source", required=True)
    p_verify.set_defaults(func=command_verify)

    p_validate = sub.add_parser("validate-ticket")
    p_validate.add_argument("--ticket", required=True)
    p_validate.set_defaults(func=command_validate_ticket)

    p_visible = sub.add_parser("visible-thread")
    p_visible.add_argument("--input", required=True, help="JSON envelope containing paginated exact-session reader pages")
    p_visible.add_argument("--output", required=True)
    p_visible.set_defaults(func=command_visible_thread)

    args = parser.parse_args(argv)
    return args.func(args)


if __name__ == "__main__":
    raise SystemExit(main())
