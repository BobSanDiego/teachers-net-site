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


def paths(project: str) -> tuple[Path, Path, Path]:
    base = HOPPER / project
    label = "Views" if project == "views" else "Job Center" if project == "jobcenter" else project.replace("-", " ").title()
    return base / f"Report ({label})", base / f"Hopper ({label})", base / "archive"


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as handle:
        for block in iter(lambda: handle.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def begin(project: str, identifier: str) -> None:
    report, hopper, archive = paths(project)
    report.mkdir(parents=True, exist_ok=True)
    hopper.mkdir(parents=True, exist_ok=True)
    archive.mkdir(parents=True, exist_ok=True)
    destination = archive / identifier
    if destination.exists():
        raise RuntimeError(f"archive cycle already exists: {destination}")
    destination.mkdir()
    for source in (report, hopper):
        target = destination / source.name
        target.mkdir()
        for item in source.iterdir():
            if item.name != "output.txt":
                shutil.move(str(item), str(target / item.name))


def safe_name(source: Path, project: str, identifier: str) -> str:
    return f"{source.stem}-{project}-{identifier}{source.suffix}"


def collect(project: str, identifier: str, source: Path, status: str) -> dict:
    _, hopper, _ = paths(project)
    if source.name == "output.txt":
        raise RuntimeError("protected output.txt cannot be collected")
    if not source.is_file():
        raise RuntimeError(f"artifact does not exist: {source}")
    target = hopper / safe_name(source, project, identifier)
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
    _, hopper, _ = paths(project)
    report = f"output-{project}-{identifier}.txt"
    manifest = f"MANIFEST-{project}-{identifier}.txt"
    record = f"cycle-{project}-{identifier}.json"
    payload = {
        "project": project, "ticket": ticket, "cycle_id": identifier,
        "status": status, "branch": branch, "commit": commit,
        "push": push, "current_hopper": str(hopper.relative_to(ROOT)),
        "archive_path": str((hopper.parent / "archive").relative_to(ROOT)),
        "report_file": report, "manifest_file": manifest,
        "cycle_record_file": record, "evidence_bundle": evidence,
        "artifacts": artifacts,
    }
    (hopper / record).write_text(json.dumps(payload, indent=2) + "\n")
    lines = [
        f"project={project}", f"ticket={ticket}", f"cycle_id={identifier}",
        f"branch={branch}", f"commit={commit or ''}", f"push={push}",
        f"current_hopper={hopper.relative_to(ROOT)}",
        f"archive_path={(hopper.parent / 'archive').relative_to(ROOT)}",
        f"report_file={report}", f"manifest_file={manifest}",
        f"cycle_record_file={record}", f"evidence_bundle={evidence or ''}",
        "", "artifacts:",
    ]
    for item in artifacts:
        lines.append(json.dumps(item, sort_keys=True))
    (hopper / manifest).write_text("\n".join(lines) + "\n")


def refresh_records(project: str, identifier: str, commit: str,
                    push: str, committed_sources: list[str]) -> None:
    _, hopper, _ = paths(project)
    record_path = hopper / f"cycle-{project}-{identifier}.json"
    payload = json.loads(record_path.read_text())
    if not commit or not push:
        raise RuntimeError("finalization requires both commit and push values")
    committed = set(committed_sources)
    for artifact in payload["artifacts"]:
        if artifact.get("original_path") in committed:
            artifact["committed"] = True
    payload["commit"] = commit
    payload["push"] = push
    payload["status"] = "complete"
    record_path.write_text(json.dumps(payload, indent=2) + "\n")
    lines = [
        f"project={payload['project']}", f"ticket={payload['ticket']}",
        f"cycle_id={payload['cycle_id']}", f"branch={payload['branch']}",
        f"commit={payload['commit']}", f"push={payload['push']}",
        f"current_hopper={payload['current_hopper']}",
        f"archive_path={payload['archive_path']}",
        f"report_file={payload['report_file']}",
        f"manifest_file={payload['manifest_file']}",
        f"cycle_record_file={payload['cycle_record_file']}",
        f"evidence_bundle={payload.get('evidence_bundle') or ''}", "",
        "artifacts:",
    ]
    for artifact in payload["artifacts"]:
        lines.append(json.dumps(artifact, sort_keys=True))
    (hopper / payload["manifest_file"]).write_text("\n".join(lines) + "\n")


def validate(project: str, identifier: str) -> None:
    report, hopper, _ = paths(project)
    if not hopper.exists() or not any(hopper.iterdir()):
        raise RuntimeError("current Hopper directory is empty")
    if any(item.stat().st_size == 0 for item in hopper.iterdir()):
        raise RuntimeError("zero-byte artifact in current Hopper directory")
    record = hopper / f"cycle-{project}-{identifier}.json"
    payload = json.loads(record.read_text())
    if payload.get("status") != "complete" or not payload.get("commit"):
        raise RuntimeError("cycle record is not finalized with a commit")
    if payload.get("push") not in {"pushed", "success", "successful"}:
        raise RuntimeError("cycle record does not contain a successful push status")
    manifest = hopper / payload["manifest_file"]
    if not manifest.is_file() or f"commit={payload['commit']}" not in manifest.read_text():
        raise RuntimeError("manifest and cycle record commit state disagree")
    if f"push={payload['push']}" not in manifest.read_text():
        raise RuntimeError("manifest and cycle record push state disagree")
    manifest_artifacts = manifest.read_text().splitlines()
    for artifact in payload["artifacts"]:
        encoded = json.dumps(artifact, sort_keys=True)
        if encoded not in manifest_artifacts:
            raise RuntimeError("manifest and cycle record artifacts disagree")
    for item in hopper.iterdir():
        if item.name == "output.txt":
            continue
    print(f"validated report={report} hopper={hopper}")


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("command", choices=("begin", "collect", "refresh", "validate"))
    parser.add_argument("--project", required=True)
    parser.add_argument("--cycle", default=None)
    parser.add_argument("--source")
    parser.add_argument("--status", default="modified")
    parser.add_argument("--commit")
    parser.add_argument("--push")
    parser.add_argument("--committed-source", action="append", default=[])
    args = parser.parse_args()
    identifier = args.cycle or cycle_id()
    if args.command == "begin":
        begin(args.project, identifier)
        print(identifier)
    elif args.command == "collect":
        if not args.source:
            parser.error("--source is required for collect")
        print(json.dumps(collect(args.project, identifier, Path(args.source).resolve(), args.status)))
    elif args.command == "refresh":
        if not args.commit or not args.push:
            parser.error("refresh requires --commit and --push")
        refresh_records(args.project, identifier, args.commit, args.push,
                        args.committed_source)
        print(f"refreshed {args.project}/{identifier}")
    else:
        validate(args.project, identifier)
    return 0


if __name__ == "__main__":
    sys.exit(main())
