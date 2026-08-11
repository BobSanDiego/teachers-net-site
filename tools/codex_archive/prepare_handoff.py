#!/usr/bin/env python3
"""Prepare and atomically publish a portable project handoff checkpoint."""
from __future__ import annotations

import argparse
import hashlib
import json
import shutil
import subprocess
import tempfile
from datetime import datetime
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
DEFAULT_ARCHIVE = Path("/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS")


def sha(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as f:
        for block in iter(lambda: f.read(1024 * 1024), b""):
            h.update(block)
    return h.hexdigest()


def main() -> int:
    p = argparse.ArgumentParser()
    p.add_argument("--project-record", type=Path, required=True)
    p.add_argument("--closing-transcript", type=Path)
    p.add_argument("--chatgpt-baseline", type=Path, required=True)
    p.add_argument("--chatgpt-live-continuation", type=Path, required=True)
    p.add_argument("--codex-fossil", type=Path, required=True)
    p.add_argument("--active-source", type=Path)
    p.add_argument("--archive-root", type=Path, default=DEFAULT_ARCHIVE)
    p.add_argument("--receipt-out", type=Path)
    args = p.parse_args()
    record = json.loads(args.project_record.read_text(encoding="utf-8"))
    if record.get("project_id") != "jobcenter":
        raise SystemExit("only the configured Job Center proving record is enabled")
    if args.closing_transcript:
        if not args.closing_transcript.is_file() or not args.closing_transcript.stat().st_size:
            raise SystemExit("closing transcript must be readable and non-empty")
        closing_sha = sha(args.closing_transcript)
        baseline = args.chatgpt_baseline.read_text(encoding="utf-8")
        marker = f"CLOSING CHATGPT TRANSCRIPT — {closing_sha}"
        if marker not in baseline:
            baseline += f"\n\n## {marker}\n\nSource: `{args.closing_transcript}`\nSHA-256: `{closing_sha}`\n\n```text\n{args.closing_transcript.read_text(encoding='utf-8')}\n```\n"
            with tempfile.NamedTemporaryFile("w", encoding="utf-8", suffix=".md", delete=False) as f:
                f.write(baseline)
                baseline_path = Path(f.name)
        else:
            baseline_path = args.chatgpt_baseline
    else:
        closing_sha = None
        baseline_path = args.chatgpt_baseline
    stamp = datetime.now().astimezone()
    project_name = record["display_name"].replace(" ", "-")
    checkpoint_name = f"{project_name}-{stamp.strftime('%Y%m%d-%H%M%S')}"
    archive = args.archive_root.resolve()
    archive.mkdir(parents=True, exist_ok=True)
    if (archive / checkpoint_name).exists():
        raise SystemExit(f"refusing to overwrite existing checkpoint: {checkpoint_name}")
    with tempfile.TemporaryDirectory(prefix="handoff-") as td:
        work = Path(td) / "current-handoff"
        subprocess.run(["python3", str(ROOT / "tools/codex_archive/build_current_masters.py"), "--out", str(ROOT / "docs/process/conversation-handoff/jobcenter"), "--chatgpt-baseline", str(baseline_path), "--codex-fossil", str(args.codex_fossil), "--chatgpt-live-continuation", str(args.chatgpt_live_continuation), "--project-record", str(args.project_record)], check=True)
        subprocess.run(["python3", str(ROOT / "tools/codex_archive/build_one_drop_handoff.py")], check=True)
        source = ROOT / "docs/process/conversation-handoff/jobcenter/current-handoff"
        shutil.copytree(source, work)
        shutil.copy2(args.project_record, work / "project-record.json")
        manifest_path = work / "handoff-manifest.json"
        manifest = json.loads(manifest_path.read_text(encoding="utf-8"))
        manifest.update({"checkpoint_id": checkpoint_name, "timezone": str(stamp.tzinfo), "closing_transcript_sha256": closing_sha, "immutable_checkpoint": True, "publication_status": "VALIDATED_IMMUTABLE_CHECKPOINT"})
        members = []
        for child in sorted(work.iterdir()):
            if child.is_file() and child.name != "handoff-manifest.json":
                members.append({"filename": child.name, "bytes": child.stat().st_size, "sha256": sha(child)})
        manifest["checkpoint_members"] = members
        manifest_path.write_text(json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8")
        if not members or any(not (work / m["filename"]).stat().st_size for m in members):
            raise SystemExit("checkpoint validation failed")
        final = archive / checkpoint_name
        work.rename(final)
    receipt = {"handoff_id": checkpoint_name, "project": record["project_id"], "windows_path": str(final).replace("/mnt/c", "C:").replace("/", "\\"), "wsl_path": str(final), "manifest": str(final / "handoff-manifest.json"), "members": members, "closing_transcript_sha256": closing_sha, "publication_status": "VALIDATED_IMMUTABLE_CHECKPOINT"}
    if args.receipt_out:
        args.receipt_out.parent.mkdir(parents=True, exist_ok=True)
        args.receipt_out.write_text(json.dumps(receipt, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    print(json.dumps(receipt, indent=2, sort_keys=True))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
