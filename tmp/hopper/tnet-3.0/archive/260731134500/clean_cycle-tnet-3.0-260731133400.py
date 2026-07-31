#!/usr/bin/env python3
"""Deterministic project-specific hopper lifecycle helper.

The helper never deletes archive content and never touches the protected
``output.txt``. It intentionally uses explicit artifact paths for collection.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import shutil
import sys
from datetime import datetime, timezone
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
HOPPER = ROOT / "tmp" / "hopper"


def cycle_id() -> str:
    return datetime.now(timezone.utc).strftime("%y%m%d%H%M%S")


def paths(project: str) -> tuple[Path, Path]:
    base = HOPPER / project
    return base / "current", base / "archive"


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as handle:
        for block in iter(lambda: handle.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def begin(project: str, identifier: str) -> None:
    current, archive = paths(project)
    current.mkdir(parents=True, exist_ok=True)
    archive.mkdir(parents=True, exist_ok=True)
    entries = [item for item in current.iterdir() if item.name != "output.txt"]
    if entries:
        destination = archive / identifier
        destination.mkdir()
        for item in entries:
            shutil.move(str(item), str(destination / item.name))
    if any(current.iterdir()):
        raise RuntimeError(f"current hopper is not empty: {current}")


def safe_name(source: Path, project: str, identifier: str) -> str:
    return f"{source.stem}-{project}-{identifier}{source.suffix}"


def collect(project: str, identifier: str, source: Path, status: str) -> dict:
    current, _ = paths(project)
    if source.name == "output.txt":
        raise RuntimeError("protected output.txt cannot be collected")
    if not source.is_file():
        raise RuntimeError(f"artifact does not exist: {source}")
    target = current / safe_name(source, project, identifier)
    if target.exists():
        raise RuntimeError(f"artifact collision: {target}")
    shutil.copy2(source, target)
    return {
        "hopper_filename": target.name,
        "original_path": str(source.relative_to(ROOT)),
        "status": status,
        "size": target.stat().st_size,
        "sha256": sha256(target),
        "committed": False,
        "purpose": "ticket artifact",
    }


def write_records(project: str, ticket: str, identifier: str, branch: str,
                  status: str, commit: str | None, push: str,
                  artifacts: list[dict], evidence: str | None = None) -> None:
    current, _ = paths(project)
    report = f"output-{project}-{identifier}.txt"
    manifest = f"MANIFEST-{project}-{identifier}.txt"
    record = f"cycle-{project}-{identifier}.json"
    payload = {
        "project": project, "ticket": ticket, "cycle_id": identifier,
        "status": status, "branch": branch, "commit": commit,
        "push": push, "current_hopper": str(current.relative_to(ROOT)),
        "archive_path": str((current.parent / "archive").relative_to(ROOT)),
        "report_file": report, "manifest_file": manifest,
        "cycle_record_file": record, "evidence_bundle": evidence,
        "artifacts": artifacts,
    }
    (current / record).write_text(json.dumps(payload, indent=2) + "\n")
    lines = [
        f"project={project}", f"ticket={ticket}", f"cycle_id={identifier}",
        f"branch={branch}", f"commit={commit or ''}", f"push={push}",
        f"current_hopper={current.relative_to(ROOT)}",
        f"archive_path={(current.parent / 'archive').relative_to(ROOT)}",
        f"report_file={report}", f"manifest_file={manifest}",
        f"cycle_record_file={record}", f"evidence_bundle={evidence or ''}",
        "", "artifacts:",
    ]
    for item in artifacts:
        lines.append(json.dumps(item, sort_keys=True))
    (current / manifest).write_text("\n".join(lines) + "\n")


def validate(project: str, identifier: str) -> None:
    current, _ = paths(project)
    if not current.exists() or not any(current.iterdir()):
        raise RuntimeError("current hopper is empty")
    if any(item.stat().st_size == 0 for item in current.iterdir()):
        raise RuntimeError("zero-byte artifact in current hopper")
    record = current / f"cycle-{project}-{identifier}.json"
    json.loads(record.read_text())
    for item in current.iterdir():
        if item.name == "output.txt":
            raise RuntimeError("protected output.txt must not be in project current")
    print(f"validated {current}")


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("command", choices=("begin", "collect", "validate"))
    parser.add_argument("--project", required=True)
    parser.add_argument("--cycle", default=None)
    parser.add_argument("--source")
    parser.add_argument("--status", default="modified")
    args = parser.parse_args()
    identifier = args.cycle or cycle_id()
    if args.command == "begin":
        begin(args.project, identifier)
        print(identifier)
    elif args.command == "collect":
        if not args.source:
            parser.error("--source is required for collect")
        print(json.dumps(collect(args.project, identifier, Path(args.source).resolve(), args.status)))
    else:
        validate(args.project, identifier)
    return 0


if __name__ == "__main__":
    sys.exit(main())
