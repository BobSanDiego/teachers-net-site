#!/usr/bin/env python3
"""Assemble the project-record-driven one-drop handoff payload."""
from __future__ import annotations

import argparse
import hashlib
import json
import shutil
import subprocess
import zipfile
from datetime import datetime, timezone
from pathlib import Path

from project_handoff_builder import load_record

ROOT = Path(__file__).resolve().parents[2]
DEFAULT_RECORD = ROOT / "docs/process/conversation-handoff/projects/jobcenter.json"


def sha(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as stream:
        for block in iter(lambda: stream.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-record", type=Path, default=DEFAULT_RECORD)
    parser.add_argument("--source", type=Path)
    parser.add_argument("--out", type=Path)
    args = parser.parse_args()
    record = load_record(args.project_record)
    source = (args.source or ROOT / record.get("handoff_source", ""))
    out = args.out or ROOT / record.get("handoff_build_directory", f"docs/process/conversation-handoff/{record['project_id']}/current-handoff")
    if not source.is_dir():
        raise RuntimeError(f"missing current handoff source: {source}")
    if out.exists():
        shutil.rmtree(out)
    out.mkdir(parents=True)

    for filename in record.get("handoff_payload_members", []):
        src = source / filename
        if not src.is_file():
            raise RuntimeError(f"missing registered handoff member: {src}")
        shutil.copy2(src, out / filename)
    shutil.copy2(args.project_record, out / "project-record.json")

    guidance_dir = out / "guidance"
    guidance_dir.mkdir()
    entries = []
    for item in record.get("guidance_sources", []):
        source_path = ROOT / item["path"]
        if not source_path.is_file():
            raise RuntimeError(f"missing registered guidance source: {source_path}")
        dest = guidance_dir / source_path.name
        shutil.copy2(source_path, dest)
        entries.append({"filename": dest.name, "original_path": item["path"], "authority_role": item.get("role", "registered project authority"), "sha256": sha(dest), "bytes": dest.stat().st_size, "status": "CURRENT_SOURCE"})
    (guidance_dir / "00-GUIDANCE-INDEX.txt").write_text("# GUIDANCE INDEX\n\n" + "\n".join(json.dumps(entry, sort_keys=True) for entry in entries) + "\n", encoding="utf-8")
    with zipfile.ZipFile(out / "02-authoritative-guidance.zip", "w", zipfile.ZIP_DEFLATED) as archive:
        for member in sorted(guidance_dir.iterdir()):
            archive.write(member, member.name)

    generated = datetime.now(timezone.utc).replace(microsecond=0).isoformat()
    start = out / "00-START-HERE.txt"
    start.write_text(f"""{record['display_name'].upper()} ONE-DROP HANDOFF
Generated: {generated}
Project: {record['project_id']}

Read 01-CHATGPT-ENGINEERING-OPERATING-CONTRACT.txt first, then the project
record and authoritative guidance ZIP. Conversation records are evidence;
resolve current truth through the registered project authority hierarchy.
Project-specific values are resolved from the registered project record.
""", encoding="utf-8")
    contract = ROOT / "docs/process/conversation-handoff/shared/chatgpt-engineering-operating-contract.md"
    shutil.copy2(contract, out / "01-CHATGPT-ENGINEERING-OPERATING-CONTRACT.txt")
    manifest = {"schema_version": "2.0", "project": record["project_id"], "project_record": str(args.project_record), "generated_at": generated, "source_handoff": str(source), "components": []}
    for member in sorted(out.iterdir()):
        if member.is_file() and member.name != "handoff-manifest.json":
            manifest["components"].append({"filename": member.name, "bytes": member.stat().st_size, "sha256": sha(member)})
    manifest["guidance_member_count"] = len(entries) + 1
    manifest["raw_codex_jsonl_included"] = False
    (out / "handoff-manifest.json").write_text(json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
