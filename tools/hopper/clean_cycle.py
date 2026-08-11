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
SUCCESSFUL_PUSH_STATES = {"pushed", "success", "successful"}
TERMINAL_STATUSES = {"complete", "partial", "blocked"}
GIT_DISPOSITIONS = {
    "COMMITTED_PUSHED",
    "COMMITTED_NOT_PUSHED",
    "NOT_APPLICABLE",
    "BLOCKED",
}


def cycle_id() -> str:
    return datetime.now(timezone.utc).strftime("%y%m%d%H%M%S")


def paths(project: str) -> tuple[Path, Path, Path]:
    base = HOPPER / project
    label = "Views" if project == "views" else "Job Center" if project == "jobcenter" else project.replace("-", " ").title()
    return base / f"Report ({label})", base / f"Hopper ({label})", base / "archive"


def cycle_directories(project: str) -> tuple[Path, ...]:
    """Return every active directory that must be flushed at cycle start."""
    report, hopper, _ = paths(project)
    if project == "views":
        base = HOPPER / project
        return report, hopper, base / "Report (views)", base / "Hopper (views)"
    return report, hopper


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as handle:
        for block in iter(lambda: handle.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def begin(project: str, identifier: str) -> None:
    report, hopper, archive = paths(project)
    for source in cycle_directories(project):
        source.mkdir(parents=True, exist_ok=True)
    archive.mkdir(parents=True, exist_ok=True)
    destination = archive / identifier
    if destination.exists():
        raise RuntimeError(f"archive cycle already exists: {destination}")
    destination.mkdir()
    for source in cycle_directories(project):
        target = destination / source.name
        target.mkdir()
        for item in source.iterdir():
            if item.name != "output.txt":
                shutil.move(str(item), str(target / item.name))


def safe_name(source: Path, project: str, identifier: str) -> str:
    return f"{source.stem}-{project}-{identifier}{source.suffix}"


def collect(project: str, identifier: str, source: Path, status: str,
            classification: str = "HOPPER_SUPPORTING") -> dict:
    report, hopper, _ = paths(project)
    if source.name == "output.txt":
        raise RuntimeError("protected output.txt cannot be collected")
    if not source.is_file():
        raise RuntimeError(f"artifact does not exist: {source}")
    target = hopper / safe_name(source, project, identifier)
    if target.exists():
        raise RuntimeError(f"artifact collision: {target}")
    shutil.copy2(source, target)
    if classification == "REPORT_REQUIRED":
        for report_dir in report_directories(project):
            report_dir.mkdir(parents=True, exist_ok=True)
            shutil.copy2(source, report_dir / target.name)
    try:
        original_path = str(source.relative_to(ROOT))
    except ValueError:
        original_path = str(source)
    return {
        "hopper_filename": target.name,
        "original_path": original_path,
        "status": status,
        "size": target.stat().st_size,
        "sha256": sha256(target),
        "committed": False,
        "purpose": "ticket artifact",
        "classification": classification,
    }


def normalize_optional(value: str | None) -> str | None:
    if value in {None, "", "NONE", "NOT_APPLICABLE"}:
        return None
    return value


def format_optional(value: str | None) -> str:
    return value if value is not None else "null"


def infer_git_disposition(status: str, commit: str | None, push: str | None,
                          requested: str | None = None) -> str:
    if requested:
        disposition = requested.upper()
        if disposition not in GIT_DISPOSITIONS:
            raise RuntimeError(f"unknown git disposition: {requested}")
        return disposition
    if status == "blocked":
        return "BLOCKED"
    if not commit:
        return "NOT_APPLICABLE"
    if push in SUCCESSFUL_PUSH_STATES:
        return "COMMITTED_PUSHED"
    return "COMMITTED_NOT_PUSHED"


def validate_git_state(status: str, commit: str | None, push: str | None,
                       disposition: str) -> None:
    if status not in TERMINAL_STATUSES:
        raise RuntimeError(f"unsupported terminal status: {status}")
    if disposition not in GIT_DISPOSITIONS:
        raise RuntimeError(f"unsupported git disposition: {disposition}")
    if disposition == "COMMITTED_PUSHED":
        if not commit:
            raise RuntimeError("COMMITTED_PUSHED requires a commit")
        if push not in SUCCESSFUL_PUSH_STATES:
            raise RuntimeError("COMMITTED_PUSHED requires a successful push")
    elif disposition == "COMMITTED_NOT_PUSHED":
        if not commit:
            raise RuntimeError("COMMITTED_NOT_PUSHED requires a commit")
        if push in SUCCESSFUL_PUSH_STATES:
            raise RuntimeError("COMMITTED_NOT_PUSHED cannot use a successful push state")
    elif disposition == "NOT_APPLICABLE":
        if commit or push:
            raise RuntimeError("NOT_APPLICABLE git disposition requires commit/push to be null")
        if status == "blocked":
            raise RuntimeError("blocked cycles must use BLOCKED git disposition")
    elif disposition == "BLOCKED":
        if status != "blocked":
            raise RuntimeError("BLOCKED git disposition requires blocked status")


def report_directories(project: str) -> list[Path]:
    return [path for path in cycle_directories(project) if path.name.startswith("Report (")]


def publish_report_cycle(project: str, payload: dict) -> None:
    _, hopper, _ = paths(project)
    filenames = [
        payload["report_file"],
        payload["manifest_file"],
        payload["cycle_record_file"],
    ]
    for report_dir in report_directories(project):
        report_dir.mkdir(parents=True, exist_ok=True)
        for filename in filenames:
            source = hopper / filename
            if source.is_file():
                shutil.copy2(source, report_dir / filename)


def write_records(project: str, ticket: str, identifier: str, branch: str,
                  status: str, commit: str | None, push: str,
                  artifacts: list[dict], evidence: str | None = None,
                  git_disposition: str | None = None,
                  excluded_artifacts: list[dict] | None = None,
                  report_source: Path | None = None) -> None:
    _, hopper, _ = paths(project)
    status = status.lower()
    commit = normalize_optional(commit)
    push = normalize_optional(push)
    disposition = infer_git_disposition(status, commit, push, git_disposition)
    validate_git_state(status, commit, push, disposition)
    report = f"output-{project}-{identifier}.txt"
    manifest = f"MANIFEST-{project}-{identifier}.txt"
    record = f"cycle-{project}-{identifier}.json"
    if report_source:
        if not report_source.is_file():
            raise RuntimeError(f"report source does not exist: {report_source}")
        shutil.copy2(report_source, hopper / report)
    payload = {
        "project": project, "ticket": ticket, "cycle_id": identifier,
        "status": status, "branch": branch, "commit": commit,
        "push": push, "git_disposition": disposition,
        "current_hopper": str(hopper.relative_to(ROOT)),
        "archive_path": str((hopper.parent / "archive").relative_to(ROOT)),
        "report_file": report, "manifest_file": manifest,
        "cycle_record_file": record, "evidence_bundle": evidence,
        # Core cycle files are first-class fields below; this list is only for
        # additional collected evidence and may intentionally be empty.
        "artifacts": artifacts,
        "excluded_artifacts": excluded_artifacts or [],
    }
    (hopper / record).write_text(json.dumps(payload, indent=2) + "\n")
    lines = [
        f"project={project}", f"ticket={ticket}", f"cycle_id={identifier}",
        f"branch={branch}", f"commit={format_optional(commit)}",
        f"push={format_optional(push)}",
        f"git_disposition={disposition}",
        f"current_hopper={hopper.relative_to(ROOT)}",
        f"archive_path={(hopper.parent / 'archive').relative_to(ROOT)}",
        f"report_file={report}", f"manifest_file={manifest}",
        f"cycle_record_file={record}", f"evidence_bundle={evidence or ''}",
        "", "artifacts:",
    ]
    for item in artifacts:
        lines.append(json.dumps(item, sort_keys=True))
    if excluded_artifacts:
        lines.extend(["", "excluded_artifacts:"])
        for item in excluded_artifacts:
            lines.append(json.dumps(item, sort_keys=True))
    (hopper / manifest).write_text("\n".join(lines) + "\n")
    publish_report_cycle(project, payload)


def refresh_records(project: str, identifier: str, commit: str,
                    push: str, committed_sources: list[str],
                    git_disposition: str | None = None) -> None:
    _, hopper, _ = paths(project)
    record_path = hopper / f"cycle-{project}-{identifier}.json"
    payload = json.loads(record_path.read_text())
    commit = normalize_optional(commit)
    push = normalize_optional(push)
    disposition = infer_git_disposition(payload.get("status", "complete"),
                                        commit, push, git_disposition)
    validate_git_state(payload.get("status", "complete"), commit, push,
                       disposition)
    committed = set(committed_sources)
    for artifact in payload["artifacts"]:
        if artifact.get("original_path") in committed:
            artifact["committed"] = True
    payload["commit"] = commit
    payload["push"] = push
    payload["git_disposition"] = disposition
    if payload.get("status") not in TERMINAL_STATUSES:
        payload["status"] = "complete"
    record_path.write_text(json.dumps(payload, indent=2) + "\n")
    lines = [
        f"project={payload['project']}", f"ticket={payload['ticket']}",
        f"cycle_id={payload['cycle_id']}", f"branch={payload['branch']}",
        f"commit={format_optional(payload['commit'])}",
        f"push={format_optional(payload['push'])}",
        f"git_disposition={payload['git_disposition']}",
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
    if payload.get("excluded_artifacts"):
        lines.extend(["", "excluded_artifacts:"])
        for artifact in payload["excluded_artifacts"]:
            lines.append(json.dumps(artifact, sort_keys=True))
    (hopper / payload["manifest_file"]).write_text("\n".join(lines) + "\n")
    publish_report_cycle(project, payload)


def validate(project: str, identifier: str) -> None:
    report, hopper, _ = paths(project)
    if not hopper.exists() or not any(hopper.iterdir()):
        raise RuntimeError("current Hopper directory is empty")
    if any(item.stat().st_size == 0 for item in hopper.iterdir()):
        raise RuntimeError("zero-byte artifact in current Hopper directory")
    record = hopper / f"cycle-{project}-{identifier}.json"
    payload = json.loads(record.read_text())
    disposition = payload.get("git_disposition") or infer_git_disposition(
        payload.get("status"), payload.get("commit"), payload.get("push")
    )
    validate_git_state(payload.get("status"), payload.get("commit"),
                       payload.get("push"), disposition)
    manifest = hopper / payload["manifest_file"]
    if not manifest.is_file() or f"commit={format_optional(payload['commit'])}" not in manifest.read_text():
        raise RuntimeError("manifest and cycle record commit state disagree")
    if f"push={format_optional(payload['push'])}" not in manifest.read_text():
        raise RuntimeError("manifest and cycle record push state disagree")
    if payload.get("git_disposition") and f"git_disposition={payload['git_disposition']}" not in manifest.read_text():
        raise RuntimeError("manifest and cycle record git disposition disagree")
    manifest_artifacts = manifest.read_text().splitlines()
    for artifact in payload["artifacts"]:
        encoded = json.dumps(artifact, sort_keys=True)
        if encoded not in manifest_artifacts:
            raise RuntimeError("manifest and cycle record artifacts disagree")
    for artifact in payload.get("excluded_artifacts", []):
        encoded = json.dumps(artifact, sort_keys=True)
        if encoded not in manifest_artifacts:
            raise RuntimeError("manifest and cycle record excluded artifacts disagree")
    for artifact in payload["artifacts"]:
        if artifact.get("classification") == "REPORT_REQUIRED":
            for report_dir in report_directories(project):
                if not (report_dir / artifact["hopper_filename"]).is_file():
                    raise RuntimeError(
                        f"Report missing REPORT_REQUIRED artifact {artifact['hopper_filename']}: {report_dir}"
                    )
    for report_dir in report_directories(project):
        if not report_dir.exists() or not any(report_dir.iterdir()):
            raise RuntimeError(f"current Report directory is empty: {report_dir}")
        if any(item.stat().st_size == 0 for item in report_dir.iterdir()):
            raise RuntimeError(f"zero-byte artifact in current Report directory: {report_dir}")
        for filename in (payload["report_file"], payload["manifest_file"],
                         payload["cycle_record_file"]):
            if not (report_dir / filename).is_file():
                raise RuntimeError(f"Report directory missing {filename}: {report_dir}")
    for item in hopper.iterdir():
        if item.name == "output.txt":
            continue
    print(f"validated report={report} hopper={hopper}")


def load_json(path: str) -> dict:
    return json.loads(Path(path).read_text())


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("command", choices=("begin", "collect", "finalize", "refresh", "validate"))
    parser.add_argument("--project", required=True)
    parser.add_argument("--cycle", default=None)
    parser.add_argument("--source")
    parser.add_argument("--classification", choices=("REPORT_REQUIRED", "HOPPER_SUPPORTING", "LOCAL_ONLY", "SENSITIVE_DO_NOT_PACKAGE", "OVERSIZED_EXTERNAL_REFERENCE"), default="HOPPER_SUPPORTING")
    parser.add_argument("--status", default="modified")
    parser.add_argument("--ticket")
    parser.add_argument("--branch", default="")
    parser.add_argument("--commit")
    parser.add_argument("--push")
    parser.add_argument("--git-disposition")
    parser.add_argument("--artifact-json", action="append", default=[])
    parser.add_argument("--excluded-artifact-json", action="append", default=[])
    parser.add_argument("--report-source")
    parser.add_argument("--evidence")
    parser.add_argument("--committed-source", action="append", default=[])
    args = parser.parse_args()
    identifier = args.cycle or cycle_id()
    if args.command == "begin":
        begin(args.project, identifier)
        print(identifier)
    elif args.command == "collect":
        if not args.source:
            parser.error("--source is required for collect")
        print(json.dumps(collect(args.project, identifier, Path(args.source).resolve(), args.status, args.classification)))
    elif args.command == "finalize":
        if not args.ticket:
            parser.error("--ticket is required for finalize")
        artifacts = [load_json(path) for path in args.artifact_json]
        excluded = [load_json(path) for path in args.excluded_artifact_json]
        write_records(args.project, args.ticket, identifier, args.branch,
                      args.status, args.commit, args.push, artifacts,
                      args.evidence, args.git_disposition, excluded,
                      Path(args.report_source).resolve() if args.report_source else None)
        print(f"finalized {args.project}/{identifier}")
    elif args.command == "refresh":
        refresh_records(args.project, identifier, args.commit, args.push,
                        args.committed_source, args.git_disposition)
        print(f"refreshed {args.project}/{identifier}")
    else:
        validate(args.project, identifier)
    return 0


if __name__ == "__main__":
    sys.exit(main())
