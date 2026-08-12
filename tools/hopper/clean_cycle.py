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
if str(ROOT) not in sys.path:
    sys.path.insert(0, str(ROOT))

from tools.workflow.workflow_v2 import (  # noqa: E402
    VALID_EVIDENCE,
    VALID_REASONING,
    WorkflowV2Error,
    load_project_record,
    project_report_route,
    report_tier,
    consume_shared_authority,
    retry_interlock,
    retire_unexecuted_stub,
    ticket_source_hash,
    validate_ticket_payload,
    workflow_version,
)
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
    _, record = load_project_record(project, ROOT)
    route = project_report_route(record, ROOT)
    return route["report"], route["hopper"], route["archive"]


def cycle_directories(project: str) -> tuple[Path, ...]:
    """Return every active directory that must be flushed at cycle start."""
    report, hopper, _ = paths(project)
    _, record = load_project_record(project, ROOT)
    aliases = project_report_route(record, ROOT)["aliases"]
    return tuple([report, hopper, *aliases])


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as handle:
        for block in iter(lambda: handle.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest()


def begin(project: str, identifier: str, ticket_source: Path, *, explicit_retry: bool = False) -> dict:
    if not ticket_source.is_file():
        raise RuntimeError(f"ticket source does not exist: {ticket_source}")
    try:
        preflight = validate_ticket_payload(ticket_source.read_text(encoding="utf-8"))
        retry_interlock(project, preflight["ticket_id"], ticket_source_hash(ticket_source),
                        explicit_retry=explicit_retry, root=ROOT)
    except WorkflowV2Error as exc:
        raise RuntimeError(f"ticket preflight failed before cycle initialization: {exc}") from exc
    report, hopper, archive = paths(project)
    authority = consume_shared_authority(project, ROOT)
    preflight["shared_authority"] = authority
    retire_unexecuted_stub(project, identifier, ROOT)
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
    return preflight


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


def manifest_lines(payload: dict) -> list[str]:
    lines = [
        f"project={payload['project']}", f"ticket={payload['ticket']}",
        f"cycle_id={payload['cycle_id']}", f"workflow_version={payload['workflow_version']}",
        f"objective_owner={payload['objective_owner']}",
        f"acceptance_fixtures={json.dumps(payload['acceptance_fixtures'])}",
        f"mode={payload['mode']}", f"evidence_class={payload['evidence_class']}",
        f"objective_state={payload['objective_state']}",
        f"branch={payload['branch']}",
        f"commit={format_optional(payload['commit'])}",
        f"push={format_optional(payload['push'])}",
        f"git_disposition={payload['git_disposition']}",
        f"reasoning_posture_recommended={payload['reasoning_posture_recommended']}",
        f"reasoning_posture_used={payload['reasoning_posture_used'] or 'null'}",
        f"reasoning_posture_recommended_next={payload['reasoning_posture_recommended_next']}",
        f"reasoning_escalation_reason={payload['reasoning_escalation_reason'] or 'null'}",
        f"current_hopper={payload['current_hopper']}",
        f"archive_path={payload['archive_path']}",
        f"report_file={payload['report_file']}",
        f"manifest_file={payload['manifest_file']}",
        f"cycle_record_file={payload['cycle_record_file']}",
        f"evidence_bundle={payload.get('evidence_bundle') or ''}",
        f"report_hopper_bytes={json.dumps(payload['report_hopper_bytes'], sort_keys=True)}",
        "", "artifacts:",
    ]
    lines.extend(json.dumps(item, sort_keys=True) for item in payload["artifacts"])
    if payload.get("excluded_artifacts"):
        lines.extend(["", "excluded_artifacts:"])
        lines.extend(json.dumps(item, sort_keys=True) for item in payload["excluded_artifacts"])
    return lines


def directory_bytes(path: Path) -> int:
    return sum(item.stat().st_size for item in path.iterdir() if item.is_file())


def persist_records(project: str, payload: dict) -> None:
    report, hopper, _ = paths(project)
    record_path = hopper / payload["cycle_record_file"]
    manifest_path = hopper / payload["manifest_file"]
    for _ in range(4):
        record_path.write_text(json.dumps(payload, indent=2) + "\n")
        manifest_path.write_text("\n".join(manifest_lines(payload)) + "\n")
        publish_report_cycle(project, payload)
        sizes = {
            "report": sum(directory_bytes(path) for path in report_directories(project)),
            "hopper": directory_bytes(hopper),
        }
        if sizes == payload["report_hopper_bytes"]:
            break
        payload["report_hopper_bytes"] = sizes
    record_path.write_text(json.dumps(payload, indent=2) + "\n")
    manifest_path.write_text("\n".join(manifest_lines(payload)) + "\n")
    publish_report_cycle(project, payload)


def write_records(project: str, ticket: str, identifier: str, branch: str,
                  status: str, commit: str | None, push: str,
                  artifacts: list[dict], evidence: str | None = None,
                  git_disposition: str | None = None,
                  excluded_artifacts: list[dict] | None = None,
                  report_source: Path | None = None,
                  *, mode: str = "STANDARD",
                  evidence_class: str = "FUNCTIONAL",
                  objective_owner: str | None = None,
                  acceptance_fixtures: list[str] | None = None,
                  objective_state: str | None = None,
                  acceptance_ledger: dict | None = None,
                  ticket_preflight: dict | None = None,
                  reasoning_posture_recommended: str = "NORMAL",
                  reasoning_posture_used: str | None = None,
                  reasoning_posture_recommended_next: str = "NORMAL",
                  reasoning_escalation_reason: str | None = None,
                  implementation_attempts: int = 1,
                  internal_checkpoints: int = 0,
                  human_checkpoints: int = 0,
                  rework_cause: str | None = None,
                  execution_seconds: float | None = None,
                  human_wait_seconds: float | None = None,
                  execution_project: str | None = None) -> None:
    _, hopper, _ = paths(project)
    status = status.lower()
    commit = normalize_optional(commit)
    push = normalize_optional(push)
    disposition = infer_git_disposition(status, commit, push, git_disposition)
    validate_git_state(status, commit, push, disposition)
    mode = mode.upper()
    tier = report_tier(mode)
    evidence_class = evidence_class.upper()
    if evidence_class not in VALID_EVIDENCE:
        raise RuntimeError(f"invalid Workflow V2 evidence class: {evidence_class!r}")
    if ticket_preflight is None or not ticket_preflight.get("valid"):
        raise RuntimeError("Workflow V2 finalization requires the successful T+0 ticket preflight")
    if ticket_preflight.get("ticket_id") != ticket:
        raise RuntimeError("ticket preflight identity does not match finalized ticket")
    if ticket_preflight.get("mode") != mode:
        raise RuntimeError("ticket preflight mode does not match finalized mode")
    if ticket_preflight.get("objective_owner") != (objective_owner or project):
        raise RuntimeError("ticket preflight owner does not match finalized objective owner")
    reasoning_values = {
        reasoning_posture_recommended.upper(),
        reasoning_posture_recommended_next.upper(),
    }
    if reasoning_posture_used:
        reasoning_values.add(reasoning_posture_used.upper())
    if not reasoning_values <= VALID_REASONING:
        raise RuntimeError(f"invalid Workflow V2 reasoning posture: {sorted(reasoning_values - VALID_REASONING)}")
    # Report/Hopper ownership follows the executing Codex agent/project. The
    # logical objective owner remains metadata and may differ.
    owner = objective_owner or project
    if not any("ticket" in item.get("hopper_filename", "").lower() or
               "pasted-text" in item.get("hopper_filename", "").lower()
               for item in artifacts):
        raise RuntimeError("Workflow V2 cycle requires a packaged source ticket")
    report = f"output-{project}-{identifier}.txt"
    manifest = f"MANIFEST-{project}-{identifier}.txt"
    record = f"cycle-{project}-{identifier}.json"
    if report_source:
        if not report_source.is_file():
            raise RuntimeError(f"report source does not exist: {report_source}")
        shutil.copy2(report_source, hopper / report)
    payload = {
        "project": project, "execution_project": execution_project or project,
        "ticket": ticket, "cycle_id": identifier,
        "workflow_version": workflow_version(ROOT),
        "objective_id": ticket,
        "objective_owner": owner,
        "acceptance_fixtures": acceptance_fixtures or [],
        "mode": mode,
        "report_tier": tier,
        "evidence_class": evidence_class,
        "objective_state": objective_state or ("complete" if status == "complete" else status),
        "acceptance_ledger": acceptance_ledger or {"objective_id": ticket, "seams": [], "checkpoints": []},
        "ticket_preflight": ticket_preflight,
        "reasoning_posture_recommended": reasoning_posture_recommended.upper(),
        "reasoning_posture_used": reasoning_posture_used.upper() if reasoning_posture_used else None,
        "reasoning_posture_recommended_next": reasoning_posture_recommended_next.upper(),
        "reasoning_escalation_reason": reasoning_escalation_reason,
        "implementation_attempts": implementation_attempts,
        "internal_checkpoints": internal_checkpoints,
        "human_checkpoints": human_checkpoints,
        "rework_cause": rework_cause,
        "execution_seconds": execution_seconds,
        "human_wait_seconds": human_wait_seconds,
        "report_hopper_bytes": {"report": 0, "hopper": 0},
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
    persist_records(project, payload)


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
    payload.setdefault("report_hopper_bytes", {"report": 0, "hopper": 0})
    persist_records(project, payload)


def validate(project: str, identifier: str) -> None:
    report, hopper, _ = paths(project)
    if not hopper.exists() or not any(hopper.iterdir()):
        raise RuntimeError("current Hopper directory is empty")
    if any(item.stat().st_size == 0 for item in hopper.iterdir()):
        raise RuntimeError("zero-byte artifact in current Hopper directory")
    record = hopper / f"cycle-{project}-{identifier}.json"
    payload = json.loads(record.read_text())
    if payload.get("workflow_version") != workflow_version(ROOT):
        raise RuntimeError("cycle does not record canonical Workflow V2")
    if payload.get("project") != project or payload.get("execution_project", project) != project:
        raise RuntimeError("cycle execution project does not match Report/Hopper route")
    if payload.get("mode") not in {"FAST", "STANDARD", "DIAGNOSTIC", "CONVERGENCE"}:
        raise RuntimeError("cycle mode is missing or invalid")
    if payload.get("evidence_class") not in VALID_EVIDENCE:
        raise RuntimeError("cycle evidence class is missing")
    if payload.get("reasoning_posture_used") not in {None, *VALID_REASONING}:
        raise RuntimeError("cycle reasoning posture used is invalid")
    preflight = payload.get("ticket_preflight") or {}
    if not preflight.get("valid"):
        raise RuntimeError("cycle successful T+0 ticket preflight is missing")
    if preflight.get("ticket_id") != payload.get("ticket"):
        raise RuntimeError("cycle ticket preflight identity mismatch")
    if preflight.get("mode") != payload.get("mode"):
        raise RuntimeError("cycle ticket preflight mode mismatch")
    if preflight.get("objective_owner") != payload.get("objective_owner"):
        raise RuntimeError("cycle ticket preflight objective-owner mismatch")
    if not any("ticket" in item.get("hopper_filename", "").lower() or
               "pasted-text" in item.get("hopper_filename", "").lower()
               for item in payload.get("artifacts", [])):
        raise RuntimeError("Workflow V2 cycle source ticket is missing")
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
    actual_sizes = {
        "report": sum(directory_bytes(path) for path in report_directories(project)),
        "hopper": directory_bytes(hopper),
    }
    if payload.get("report_hopper_bytes") != actual_sizes:
        raise RuntimeError(
            f"Report/Hopper byte telemetry mismatch: recorded={payload.get('report_hopper_bytes')} actual={actual_sizes}"
        )
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
    parser.add_argument("--ticket-source")
    parser.add_argument("--branch", default="")
    parser.add_argument("--commit")
    parser.add_argument("--push")
    parser.add_argument("--git-disposition")
    parser.add_argument("--artifact-json", action="append", default=[])
    parser.add_argument("--excluded-artifact-json", action="append", default=[])
    parser.add_argument("--report-source")
    parser.add_argument("--evidence")
    parser.add_argument("--committed-source", action="append", default=[])
    parser.add_argument("--mode", choices=("FAST", "STANDARD", "DIAGNOSTIC", "CONVERGENCE"), default="STANDARD")
    parser.add_argument("--evidence-class", default="FUNCTIONAL")
    parser.add_argument("--objective-owner")
    parser.add_argument("--acceptance-fixture", action="append", default=[])
    parser.add_argument("--objective-state")
    parser.add_argument("--acceptance-ledger-json")
    parser.add_argument("--ticket-preflight-json")
    parser.add_argument("--reasoning-posture-recommended", choices=("NORMAL", "MEDIUM", "MAXIMUM"), default="NORMAL")
    parser.add_argument("--reasoning-posture-used", choices=("NORMAL", "MEDIUM", "MAXIMUM"))
    parser.add_argument("--reasoning-posture-recommended-next", choices=("NORMAL", "MEDIUM", "MAXIMUM"), default="NORMAL")
    parser.add_argument("--reasoning-escalation-reason")
    parser.add_argument("--implementation-attempts", type=int, default=1)
    parser.add_argument("--internal-checkpoints", type=int, default=0)
    parser.add_argument("--human-checkpoints", type=int, default=0)
    parser.add_argument("--rework-cause")
    parser.add_argument("--execution-seconds", type=float)
    parser.add_argument("--human-wait-seconds", type=float)
    parser.add_argument("--explicit-retry", action="store_true")
    args = parser.parse_args()
    identifier = args.cycle or cycle_id()
    if args.command == "begin":
        if not args.ticket or not args.ticket_source:
            parser.error("Workflow V2 begin requires --ticket and --ticket-source")
        ticket_path = Path(args.ticket_source).resolve()
        try:
            preflight = validate_ticket_payload(ticket_path.read_text(encoding="utf-8"))
        except (OSError, WorkflowV2Error) as exc:
            raise RuntimeError(f"ticket preflight failed before cycle initialization: {exc}") from exc
        if preflight["ticket_id"] != args.ticket:
            raise RuntimeError(
                f"ticket argument mismatch: --ticket={args.ticket!r} payload={preflight['ticket_id']!r}"
            )
        begin(args.project, identifier, ticket_path, explicit_retry=args.explicit_retry)
        print(json.dumps({"cycle_id": identifier, "ticket_preflight": preflight}, indent=2))
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
                      Path(args.report_source).resolve() if args.report_source else None,
                      mode=args.mode, evidence_class=args.evidence_class,
                      objective_owner=args.objective_owner or args.project,
                      acceptance_fixtures=args.acceptance_fixture,
                      objective_state=args.objective_state,
                      acceptance_ledger=(load_json(args.acceptance_ledger_json)
                                         if args.acceptance_ledger_json else None),
                      ticket_preflight=(load_json(args.ticket_preflight_json)
                                        if args.ticket_preflight_json else None),
                      reasoning_posture_recommended=args.reasoning_posture_recommended,
                      reasoning_posture_used=args.reasoning_posture_used,
                      reasoning_posture_recommended_next=args.reasoning_posture_recommended_next,
                      reasoning_escalation_reason=args.reasoning_escalation_reason,
                      implementation_attempts=args.implementation_attempts,
                      internal_checkpoints=args.internal_checkpoints,
                      human_checkpoints=args.human_checkpoints,
                      rework_cause=args.rework_cause,
                      execution_seconds=args.execution_seconds,
                      human_wait_seconds=args.human_wait_seconds,
                      execution_project=args.project)
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
