#!/usr/bin/env python3
"""Record-driven, atomic immutable handoff/checkpoint builder.

This module owns publication mechanics only. Project facts come from a
registered project record; callers must provide the already-built handoff
payload directory.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import os
import shutil
import tempfile
from datetime import datetime, timezone
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
DEFAULT_REGISTRY = ROOT / "docs/process/conversation-handoff/projects"


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as stream:
        for block in iter(lambda: stream.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def load_record(path: Path) -> dict:
    try:
        record = json.loads(path.read_text(encoding="utf-8"))
    except (OSError, json.JSONDecodeError) as exc:
        raise ValueError(f"invalid project record: {path}: {exc}") from exc
    project_id = record.get("project_id")
    name = record.get("display_name")
    if not isinstance(project_id, str) or not project_id or not isinstance(name, str) or not name:
        raise ValueError("project record requires non-empty project_id and display_name")
    if not any(key in record for key in ("root_repository", "repositories")):
        raise ValueError("project record requires repository authority")
    return record


def resolve_record(project: str, registry: Path = DEFAULT_REGISTRY) -> tuple[dict, Path]:
    candidates = []
    for path in sorted(registry.glob("*.json")):
        try:
            record = load_record(path)
        except ValueError:
            continue
        if record["project_id"] == project:
            candidates.append((record, path))
    if len(candidates) != 1:
        raise ValueError(f"project selector {project!r} resolved to {len(candidates)} records")
    return candidates[0]


def checkpoint_name(record: dict, stamp: datetime | None = None) -> str:
    stamp = stamp or datetime.now(timezone.utc)
    safe_name = "-".join(record["display_name"].split())
    return f"{safe_name}-{stamp.strftime('%Y%m%d-%H%M%S')}"


def _files(root: Path) -> list[Path]:
    return sorted(path for path in root.rglob("*") if path.is_file())


def publish(record: dict, source: Path, archive_root: Path, *, stamp: datetime | None = None) -> dict:
    """Validate and atomically publish one immutable record-driven package."""
    if not source.is_dir():
        raise ValueError(f"handoff source directory does not exist: {source}")
    files = _files(source)
    if not files:
        raise ValueError("handoff source directory is empty")
    required = {"00-START-HERE.txt", "handoff-manifest.json"}
    missing = required - {path.name for path in files}
    if missing:
        raise ValueError(f"handoff source missing required members: {sorted(missing)}")
    if any(path.stat().st_size == 0 for path in files):
        raise ValueError("handoff source contains an empty member")

    archive_root.mkdir(parents=True, exist_ok=True)
    name = checkpoint_name(record, stamp)
    destination = archive_root / name
    if destination.exists():
        raise FileExistsError(f"refusing to overwrite immutable checkpoint: {destination}")

    with tempfile.TemporaryDirectory(prefix=f"{record['project_id']}-handoff-", dir=archive_root) as temp:
        staged = Path(temp) / name
        shutil.copytree(source, staged)
        manifest_path = staged / "handoff-manifest.json"
        try:
            manifest = json.loads(manifest_path.read_text(encoding="utf-8"))
        except (OSError, json.JSONDecodeError) as exc:
            raise ValueError(f"handoff manifest is not valid JSON: {exc}") from exc
        members = []
        for path in _files(staged):
            if path == manifest_path:
                continue
            members.append({"filename": str(path.relative_to(staged)), "bytes": path.stat().st_size, "sha256": sha256(path)})
        manifest.update({
            "schema_version": "2.0",
            "project": record["project_id"],
            "project_record": str(record.get("_record_path", "")),
            "checkpoint_id": name,
            "generated_at": (stamp or datetime.now(timezone.utc)).isoformat(),
            "immutable_checkpoint": True,
            "publication_status": "VALIDATED_IMMUTABLE_CHECKPOINT",
            "checkpoint_members": members,
            "raw_codex_jsonl_included": False,
        })
        manifest_path.write_text(json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8")
        os.rename(staged, destination)

    return {"handoff_id": name, "project": record["project_id"], "wsl_path": str(destination), "manifest": str(destination / "handoff-manifest.json"), "members": members, "publication_status": "VALIDATED_IMMUTABLE_CHECKPOINT"}


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--project", required=True)
    parser.add_argument("--source", type=Path, required=True)
    parser.add_argument("--archive-root", type=Path, required=True)
    parser.add_argument("--registry", type=Path, default=DEFAULT_REGISTRY)
    parser.add_argument("--receipt-out", type=Path)
    args = parser.parse_args()
    record, record_path = resolve_record(args.project, args.registry)
    record["_record_path"] = str(record_path)
    receipt = publish(record, args.source, args.archive_root)
    if args.receipt_out:
        args.receipt_out.parent.mkdir(parents=True, exist_ok=True)
        args.receipt_out.write_text(json.dumps(receipt, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    print(json.dumps(receipt, indent=2, sort_keys=True))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
