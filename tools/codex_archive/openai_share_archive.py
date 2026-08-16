"""Canonical diagnostic/Workflow V2 owner for OpenAI share archives.

The share page contains a devalue-like React Flight loader payload. This module
decodes the reference table, follows the authoritative conversation mapping,
and emits only meaningful visible user/assistant turns.
"""
from __future__ import annotations

import argparse, datetime as dt, hashlib, html, json, re, urllib.request
from pathlib import Path
from typing import Any

VERSION = "openai-share-archive-v1"
DEFAULT_ROOT = Path("tmp/hopper/shared-workflow/openai-share-archive")

def sha(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as f:
        for b in iter(lambda: f.read(1024 * 1024), b""): h.update(b)
    return h.hexdigest()

def retrieve(url: str) -> bytes:
    req = urllib.request.Request(url, headers={"User-Agent": "TeachersNet-WorkflowV2-ShareArchive/1"})
    with urllib.request.urlopen(req, timeout=45) as r: return r.read()

def _decode_html(raw: bytes) -> dict[str, Any]:
    text = raw.decode("utf-8")
    chunks = re.findall(r'streamController\.enqueue\((\"(?:\\.|[^\"\\])*\")\)', text)
    if not chunks:
        chunks = re.findall(r'streamController\.enqueue\((\"(?:\\.|[^\"\\])*\")\)', text)
    encoded = next((json.loads(x) for x in chunks if "loaderData" in json.loads(x)), None)
    if encoded is None: raise ValueError("OpenAI share loader payload not found")
    table = json.loads(encoded); memo: dict[int, Any] = {}
    def ref(v: Any) -> Any:
        if isinstance(v, int):
            if v == -5: return None
            return dec(v) if 0 <= v < len(table) else v
        return v
    def dec(i: int) -> Any:
        if i in memo: return memo[i]
        value = table[i]
        if isinstance(value, (str, int, float, bool)) or value is None: memo[i] = value; return value
        if isinstance(value, list):
            out: list[Any] = []; memo[i] = out; out.extend(ref(x) for x in value); return out
        if isinstance(value, dict):
            out: dict[str, Any] = {}; memo[i] = out
            for key, val in value.items():
                name = key[1:] if key.startswith("_") else key
                name = str(dec(int(name))) if name.isdigit() and int(name) < len(table) else name
                out[name] = ref(val)
            return out
        raise TypeError(type(value).__name__)
    root = dec(0); found: list[dict[str, Any]] = []; seen: set[int] = set()
    def walk(v: Any) -> None:
        if isinstance(v, (dict, list)):
            if id(v) in seen: return
            seen.add(id(v))
        if isinstance(v, dict):
            if isinstance(v.get("mapping"), dict): found.append(v)
            for x in v.values(): walk(x)
        elif isinstance(v, list):
            for x in v: walk(x)
    walk(root)
    if not found: raise ValueError("OpenAI conversation mapping not found")
    conversation = found[0]; mapping = conversation["mapping"]; node_id = conversation["current_node"]
    chain: list[dict[str, Any]] = []; visited: set[str] = set()
    while node_id and node_id not in visited:
        visited.add(node_id); node = mapping.get(node_id, {})
        if node.get("message"): chain.append(node["message"])
        node_id = node.get("parent")
    chain.reverse()
    records = []
    for message in chain:
        role = (message.get("author") or {}).get("role")
        content = message.get("content") or {}; parts = content.get("parts") or []
        if role not in {"user", "assistant"}: continue
        text_parts = [p if isinstance(p, str) else json.dumps(p, ensure_ascii=False, sort_keys=True) for p in parts]
        text_value = "".join(text_parts)
        metadata = message.get("metadata") or {}
        refs = []
        for key in ("attachments", "files", "file_citations", "asset_pointer", "image_asset_pointer"):
            if key in metadata and metadata[key]: refs.append({key: metadata[key]})
        if not text_value.strip() and not refs: continue
        records.append({"id": message.get("id"), "role": role, "timestamp": message.get("create_time"),
                        "updated_timestamp": message.get("update_time"), "parent_id": message.get("parent_message_id"),
                        "content_parts": parts, "text": text_value, "attachments": refs})
    return {"schema_version": VERSION, "conversation_id": conversation.get("conversation_id"),
            "title": conversation.get("title"), "current_node": conversation.get("current_node"), "records": records}

def render_markdown(data: dict[str, Any]) -> str:
    out = [f"# {data.get('title') or 'OpenAI Share Conversation'}", f"\nConversation ID: `{data['conversation_id']}`\n"]
    for n, record in enumerate(data["records"], 1):
        stamp = dt.datetime.fromtimestamp(record["timestamp"], dt.timezone.utc).isoformat() if record.get("timestamp") else "UNKNOWN"
        out += [f"## {n}. {record['role'].upper()} — {record['id']} — {stamp}\n", record["text"].rstrip() + "\n"]
    return "\n".join(out)

def archive(url: str, project: str, root: Path = DEFAULT_ROOT, raw: bytes | None = None) -> dict[str, Any]:
    raw = raw if raw is not None else retrieve(url)
    data = _decode_html(raw); records = data["records"]
    dates = [r["timestamp"] for r in records if r.get("timestamp")]
    start = dt.datetime.fromtimestamp(min(dates), dt.timezone.utc).strftime("%Y%m%d") if dates else "unknown"
    end = dt.datetime.fromtimestamp(max(dates), dt.timezone.utc).strftime("%Y%m%d") if dates else "unknown"
    slug = re.sub(r"[^a-z0-9]+", "-", (data.get("title") or project).lower()).strip("-")
    out = root / project / f"{start}-{end}-{slug}"
    out.mkdir(parents=True, exist_ok=True)
    raw_hash = hashlib.sha256(raw).hexdigest()
    raw_path = out / "raw-openai-share.html"
    if raw_path.exists() and sha(raw_path) != raw_hash:
        raw_path = out / f"raw-openai-share-{raw_hash[:12]}.html"
    if not raw_path.exists():
        raw_path.write_bytes(raw)
    json_text = json.dumps(data, ensure_ascii=False, indent=2) + "\n"
    md_text = render_markdown(data)
    json_path = out / "canonical-transcript.json"
    md_path = out / "canonical-transcript.md"
    if json_path.exists() and sha(json_path) != hashlib.sha256(json_text.encode()).hexdigest():
        raise ValueError(f"canonical archive identity conflict at {json_path}")
    if md_path.exists() and sha(md_path) != hashlib.sha256(md_text.encode()).hexdigest():
        raise ValueError(f"canonical archive identity conflict at {md_path}")
    if not json_path.exists(): json_path.write_text(json_text)
    if not md_path.exists(): md_path.write_text(md_text)
    manifest = {"schema_version": VERSION, "project": project, "share_url": url,
        "conversation_id": data["conversation_id"], "retrieved_at": dt.datetime.now(dt.timezone.utc).isoformat(),
        "decoder": {"path": "tools/codex_archive/openai_share_archive.py", "version": VERSION},
        "raw": {"path": str(raw_path), "bytes": raw_path.stat().st_size, "sha256": sha(raw_path)},
        "canonical": {"json": str(json_path), "json_sha256": sha(json_path), "markdown": str(md_path), "markdown_sha256": sha(md_path)},
        "counts": {"user": sum(r["role"] == "user" for r in records), "assistant": sum(r["role"] == "assistant" for r in records), "visible": len(records), "attachment_turns": sum(bool(r["attachments"]) for r in records)},
        "boundary": {
            "first_id": records[0]["id"] if records else None,
            "last_id": records[-1]["id"] if records else None,
            "first_timestamp": records[0].get("timestamp") if records else None,
            "last_timestamp": records[-1].get("timestamp") if records else None,
        },
        "exclusions": ["system", "tool/runtime", "hidden reasoning", "empty framework records", "RSC implementation noise"]}
    (out / "provenance-manifest.json").write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + "\n")
    return {"directory": str(out), "manifest": manifest, "data": data}

def main() -> int:
    p = argparse.ArgumentParser(); p.add_argument("share_url"); p.add_argument("--project", default="unassigned"); p.add_argument("--root", type=Path, default=DEFAULT_ROOT); p.add_argument("--raw", type=Path)
    a = p.parse_args(); result = archive(a.share_url, a.project, a.root, a.raw.read_bytes() if a.raw else None); print(json.dumps({"directory": result["directory"], "manifest": result["manifest"]}, indent=2)); return 0
if __name__ == "__main__": raise SystemExit(main())
