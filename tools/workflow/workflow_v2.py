#!/usr/bin/env python3
"""Canonical machine owner for Teachers.Net Engineering Workflow V2.

This module contains shared workflow mechanics only. Product facts remain in
registered project records and project-specific authorities.
"""
from __future__ import annotations

import json
import hashlib
import re
import shutil
from datetime import datetime, timezone
from dataclasses import dataclass, field
from pathlib import Path
from typing import Any, Iterable


ROOT = Path(__file__).resolve().parents[2]
MANIFEST = ROOT / "docs/process/conversation-handoff/shared/workflow-v2.json"
PROJECTS = ROOT / "docs/process/conversation-handoff/projects"
TICKET_READY_LINE = "TICKET READY FOR CODEX"
TICKET_LIMIT = 15_000
VALID_MODES = {"FAST", "STANDARD", "DIAGNOSTIC", "CONVERGENCE"}
VALID_EVIDENCE = {"FUNCTIONAL", "RESPONSIVE", "VISUAL", "DIAGNOSTIC", "NATIVE", "STATE/DATA"}
VALID_REASONING = {"NORMAL", "MEDIUM", "MAXIMUM"}
VALID_SEAM_STATES = {"PROVEN", "PENDING", "BLOCKED", "INVALIDATED"}


class WorkflowV2Error(ValueError):
    """Fail-closed Workflow V2 validation error."""


def load_manifest(root: Path = ROOT) -> dict[str, Any]:
    path = root / MANIFEST.relative_to(ROOT)
    payload = json.loads(path.read_text(encoding="utf-8"))
    if payload.get("workflow_version") != "V2":
        raise WorkflowV2Error("canonical workflow manifest is not Workflow V2")
    return payload


def workflow_version(root: Path = ROOT) -> str:
    return str(load_manifest(root)["workflow_version"])


def shared_authority_marker(root: Path = ROOT) -> dict[str, Any]:
    """Return the hash used for cheap, deterministic workflow freshness checks."""
    relative = [
        "docs/process/conversation-handoff/shared/workflow-v2.json",
        "docs/process/conversation-handoff/shared/WORKFLOW-V2.md",
        "docs/process/conversation-handoff/shared/START-CODEX.md",
        "docs/process/conversation-handoff/shared/REPORT-HOPPER-SPEC.md",
        "docs/process/conversation-handoff/shared/chatgpt-engineering-operating-contract.md",
    ]
    digest = hashlib.sha256()
    present = [item for item in relative if (root / item).is_file()]
    if not present:
        raise WorkflowV2Error("shared workflow authority is missing")
    for item in present:
        path = root / item
        digest.update(item.encode("utf-8"))
        digest.update(b"\0")
        digest.update(path.read_bytes())
        digest.update(b"\0")
    return {"workflow_version": workflow_version(root), "content_hash": digest.hexdigest(), "sources": present}


def consume_shared_authority(project: str, root: Path = ROOT) -> dict[str, Any]:
    """Compare and, only when changed, record the project's consumed marker."""
    _, record = load_project_record(project, root)
    route = project_report_route(record, root)
    marker_path = route["base"] / "workflow-authority-marker.json"
    marker = shared_authority_marker(root)
    previous = json.loads(marker_path.read_text(encoding="utf-8")) if marker_path.is_file() else None
    result = {**marker, "project": project, "changed": previous != marker, "marker_path": str(marker_path)}
    if previous != marker:
        marker_path.parent.mkdir(parents=True, exist_ok=True)
        marker_path.write_text(json.dumps(marker, indent=2) + "\n", encoding="utf-8")
    return result


def _stub_path(project: str, root: Path = ROOT) -> Path:
    _, record = load_project_record(project, root)
    return project_report_route(record, root)["report"] / "UNEXECUTED-STUB.txt"


def active_unexecuted_stub(project: str, root: Path = ROOT) -> Path:
    return _stub_path(project, root)


def ticket_source_hash(source: Path) -> str:
    return hashlib.sha256(source.read_bytes()).hexdigest()


def retry_interlock(project: str, ticket_id: str, source_hash: str, *, explicit_retry: bool = False,
                    root: Path = ROOT) -> None:
    """Reject an unchanged blocked intake until it is materially revised or retried explicitly."""
    path = _stub_path(project, root)
    if explicit_retry or not path.is_file():
        return
    text = path.read_text(encoding="utf-8")
    if re.search(rf"(?mi)^objective/ticket:\s*{re.escape(ticket_id)}\s*$", text) and re.search(
        rf"(?mi)^source-ticket-sha256:\s*{re.escape(source_hash)}\s*$", text
    ):
        raise WorkflowV2Error(
            f"retry interlock: unchanged unexecuted ticket {ticket_id} matches active stub; use a material revision or RETRY BLOCKED"
        )


def retire_unexecuted_stub(project: str, cycle_id: str, root: Path = ROOT) -> Path | None:
    path = _stub_path(project, root)
    if not path.is_file():
        return None
    _, record = load_project_record(project, root)
    archive = project_report_route(record, root)["archive"] / "unexecuted-stubs"
    archive.mkdir(parents=True, exist_ok=True)
    target = archive / f"UNEXECUTED-STUB-{cycle_id}.txt"
    if target.exists():
        raise WorkflowV2Error(f"unexecuted stub archive collision: {target}")
    shutil.move(str(path), str(target))
    return target


def write_unexecuted_stub(project: str, *, ticket_id: str, title: str, source_hash: str,
                          classification: str, response: str, objective_owner: str,
                          root: Path = ROOT) -> Path:
    path = _stub_path(project, root)
    if path.exists():
        raise WorkflowV2Error(f"active unexecuted stub already exists: {path}")
    path.parent.mkdir(parents=True, exist_ok=True)
    content = (
        f"{datetime.now(timezone.utc).isoformat()}\nproject: {project}\n"
        f"executing agent/project: {project}\nlogical objective owner: {objective_owner}\n"
        f"objective/ticket: {ticket_id}\nticket title: {title}\n"
        f"source-ticket-sha256: {source_hash}\nterminal classification: {classification}\n\n"
        f"{response.rstrip()}\n"
    )
    path.write_text(content, encoding="utf-8")
    return path


def _field(text: str, name: str) -> str:
    match = re.search(rf"(?mi)^{re.escape(name)}\s*:\s*(.+?)\s*$", text)
    return match.group(1).strip() if match else ""


def _section_has_body(text: str, heading: str) -> bool:
    lines = text.splitlines()
    for index, line in enumerate(lines):
        if line.strip().upper() != heading.upper():
            continue
        for candidate in lines[index + 1:]:
            value = candidate.strip()
            if not value:
                continue
            if value.upper() == value and re.fullmatch(r"[A-Z][A-Z0-9 /_-]*", value):
                return False
            return True
    return False


def extract_ticket_id(text: str) -> str:
    match = re.search(
        r"(?mi)^Ticket:\s*([A-Z0-9][A-Z0-9._-]*)(?:\s+(?:—|-)\s+.+)?\s*$",
        text,
    )
    if match:
        return match.group(1)
    lines = [line.strip() for line in text.splitlines() if line.strip()]
    if len(lines) > 1:
        match = re.match(r"^([A-Z0-9][A-Z0-9._-]*)\s+(?:—|-)\s+.+$", lines[1])
        if match:
            return match.group(1)
    raise WorkflowV2Error("ticket ID missing; use `Ticket: <ID>` or `<ID> — <title>`")


def validate_ticket_payload(
    text: str,
    *,
    require_v2_fields: bool = True,
    require_terminator: bool = True,
) -> dict[str, Any]:
    """Validate a live V2 ticket before cycle mutation.

    Historical archive callers may set ``require_v2_fields=False`` while still
    using this single parser/terminator owner.
    """
    lines = [line.rstrip() for line in text.splitlines()]
    nonempty = [line.strip() for line in lines if line.strip()]
    first = nonempty[0] if nonempty else ""
    if first != TICKET_READY_LINE:
        raise WorkflowV2Error(
            f"first executable line must be exactly {TICKET_READY_LINE!r}; got {first!r}"
        )
    ticket_id = extract_ticket_id(text)
    terminator = nonempty[-1] if nonempty else ""
    expected = f"END TICKET — {ticket_id}"
    if require_terminator and terminator != expected:
        if not terminator.startswith("END TICKET"):
            raise WorkflowV2Error(f"ticket truncated or missing terminator; expected {expected!r}")
        raise WorkflowV2Error(
            f"ticket terminator mismatch; expected {expected!r}, got {terminator!r}"
        )

    mode = _field(text, "MODE").upper()
    owner = _field(text, "OWNER")
    if require_v2_fields:
        if mode not in VALID_MODES:
            raise WorkflowV2Error(
                f"ticket mode missing or invalid; expected one of {sorted(VALID_MODES)}"
            )
        if not owner:
            raise WorkflowV2Error("ticket objective owner missing; add `OWNER: <workstream>`")
        if not _section_has_body(text, "OUTCOME"):
            raise WorkflowV2Error("ticket terminal OUTCOME missing or empty")
        if not _section_has_body(text, "STOP BOUNDARY"):
            raise WorkflowV2Error("ticket STOP BOUNDARY missing or empty")
        if re.search(r"(?mi)^RUNTIME REQUIRED:\s*YES\s*$", text) and not _field(text, "CANONICAL URL"):
            raise WorkflowV2Error("runtime-required ticket is missing `CANONICAL URL`")
        if re.search(r"(?mi)^INPUT REQUIRED:\s*YES\s*$", text) and not _field(text, "REQUIRED INPUTS"):
            raise WorkflowV2Error("input-required ticket is missing `REQUIRED INPUTS`")

    warnings: list[str] = []
    characters = len(text)
    if characters > TICKET_LIMIT:
        warnings.append(
            f"ticket is {characters} characters; ChatGPT authoring/transport limit is {TICKET_LIMIT}; complete ticket remains executable"
        )
    return {
        "workflow_version": workflow_version(),
        "ticket_id": ticket_id,
        "mode": mode or None,
        "objective_owner": owner or None,
        "terminator": terminator,
        "terminator_valid": terminator == expected,
        "characters": characters,
        "warnings": warnings,
        "valid": True,
    }


def load_project_record(project: str, root: Path = ROOT) -> tuple[Path, dict[str, Any]]:
    if not re.fullmatch(r"[a-z0-9][a-z0-9-]*", project):
        raise WorkflowV2Error(f"invalid project identifier: {project!r}")
    path = root / "docs/process/conversation-handoff/projects" / f"{project}.json"
    if not path.is_file():
        raise WorkflowV2Error(f"registered project record not found: {path}")
    record = json.loads(path.read_text(encoding="utf-8"))
    if record.get("project_id") != project:
        raise WorkflowV2Error(
            f"project record identity mismatch: expected {project!r}, got {record.get('project_id')!r}"
        )
    return path, record


def project_repository(record: dict[str, Any]) -> str:
    repositories = record.get("repositories") or {}
    root = record.get("root_repository") or repositories.get("root")
    if not root:
        raise WorkflowV2Error(f"project {record.get('project_id')} has no root repository")
    return str(root)


def project_report_route(record: dict[str, Any], root: Path = ROOT) -> dict[str, Any]:
    handoff = record.get("handoff") or {}
    relative = record.get("report_hopper") or handoff.get("report_hopper")
    label = record.get("report_label")
    if not relative or not label:
        raise WorkflowV2Error(
            f"project {record.get('project_id')} must declare report_hopper and report_label"
        )
    base = root / str(relative).strip("/")
    return {
        "base": base,
        "report": base / f"Report ({label})",
        "hopper": base / f"Hopper ({label})",
        "archive": base / "archive",
        "aliases": [base / value for value in record.get("report_hopper_aliases", [])],
    }


def resolve_report_owner(objective_owner: str, acceptance_fixture: str | None = None) -> str:
    owner = objective_owner.strip()
    if not owner:
        raise WorkflowV2Error("formal cycle requires an objective owner")
    return owner


def bootstrap(project: str, *, root: Path = ROOT) -> dict[str, Any]:
    """Resolve registered startup or enter bounded new-project onboarding."""
    manifest = load_manifest(root)
    record_path = root / "docs/process/conversation-handoff/projects" / f"{project}.json"
    if not record_path.is_file():
        return {
            "status": "NEW_PROJECT_ONBOARDING",
            "project": project,
            "workflow": manifest["workflow_version"],
            "lifecycle": "ONBOARDING AUTHORIZED / NOT YET READY",
            "bootstrap_authorization": "BOOTSTRAP",
            "bootstrap_spec": manifest["bootstrap"]["new_project_spec"],
            "product_implementation_authorized": False,
        }
    path, record = load_project_record(project, root)
    local_version = record.get("workflow_version")
    if local_version and local_version != manifest["workflow_version"]:
        raise WorkflowV2Error(
            f"project-local workflow version conflicts with shared V2: {local_version!r}"
        )
    conflicting_guidance = []
    for item in record.get("guidance_sources", []):
        role = str(item.get("role", "")).lower()
        path_value = str(item.get("path", ""))
        if "workflow" not in role or path_value.endswith("/WORKFLOW-V2.md"):
            continue
        if "supplement" in role or "historical" in role:
            continue
        conflicting_guidance.append(path_value)
    if conflicting_guidance:
        raise WorkflowV2Error(
            f"project-local workflow authority conflicts with shared V2: {conflicting_guidance}"
        )
    state = str(record.get("state", ""))
    if "READY" not in state and project != "shared-workflow":
        raise WorkflowV2Error(f"registered project is not lifecycle-ready: {project} state={state!r}")
    route = project_report_route(record, root)
    return {
        "status": "BOOTSTRAP COMPLETE",
        "project": project,
        "display_name": record.get("display_name"),
        "workflow": manifest["workflow_version"],
        "workflow_id": manifest["workflow_id"],
        "lifecycle": "READY",
        "project_record": str(path.relative_to(root)),
        "repository": project_repository(record),
        "report_hopper": str(route["base"].relative_to(root)),
        "report_label": record["report_label"],
        "ticket_preflight_owner": manifest["owners"]["ticket_validation"],
        "workflow_conflicts": [],
        "product_implementation_authorized": False,
    }


def reasoning_boost_notice(posture: str) -> str | None:
    posture = posture.upper()
    if posture == "NORMAL":
        return None
    if posture == "MEDIUM":
        return "FOR NEXT TICKET BOOST AI TO * MEDIUM *"
    if posture == "MAXIMUM":
        return "FOR NEXT TICKET BOOST AI TO *** MAXIMUM ***"
    raise WorkflowV2Error(f"unknown reasoning posture: {posture!r}")


def reasoning_reminder(current: str, recommended_next: str) -> str | None:
    current = current.upper()
    recommended_next = recommended_next.upper()
    if current == "NORMAL":
        return None
    if current not in {"MEDIUM", "MAXIMUM"} or recommended_next not in VALID_REASONING:
        raise WorkflowV2Error("invalid current or recommended reasoning posture")
    shown = "* MEDIUM *" if current == "MEDIUM" else "*** MAXIMUM ***"
    if recommended_next == "NORMAL":
        action = "RECOMMEND SET TO NORMAL"
    elif current == "MAXIMUM" and recommended_next == "MEDIUM":
        action = "RECOMMEND SET TO * MEDIUM *"
    elif current == recommended_next:
        action = "RECOMMEND KEEP SETTING FOR ONE MORE CYCLE"
    else:
        action = f"RECOMMEND SET TO {recommended_next}"
    return f"REMINDER: AI IS NOW {shown} / {action}"


def recommend_reasoning(signals: Iterable[str]) -> tuple[str, str | None]:
    normalized = {value.strip().lower().replace("_", "-") for value in signals}
    maximum = {
        "authority-conflict", "repeated-false-pass", "shared-architecture",
        "multi-project", "security", "authorization", "data-migration",
        "expensive-convergence",
    }
    medium = {
        "browser-runtime-discrepancy", "state-persistence", "multiple-owners",
        "dirty-shared-owner", "migration-compatibility", "first-contradiction",
    }
    if normalized & maximum:
        reason = sorted(normalized & maximum)[0]
        return "MAXIMUM", reason
    if normalized & medium:
        reason = sorted(normalized & medium)[0]
        return "MEDIUM", reason
    return "NORMAL", None


def report_tier(mode: str) -> dict[str, Any]:
    mode = mode.upper()
    policies = load_manifest()["report_tiers"]
    if mode not in policies:
        raise WorkflowV2Error(f"no V2 report tier for mode {mode!r}")
    return policies[mode]


def workflow_cost_signal(recent_cycles: list[dict[str, Any]]) -> dict[str, Any]:
    if not recent_cycles:
        return {"review_required": False, "ratio": None, "consecutive": 0}
    flags = [bool(item.get("workflow_or_tooling")) for item in recent_cycles]
    ratio = sum(flags) / len(flags)
    consecutive = 0
    for flag in reversed(flags):
        if not flag:
            break
        consecutive += 1
    active_blocker = any(item.get("active_quantified_blocker") for item in recent_cycles if item.get("workflow_or_tooling"))
    return {
        "review_required": (ratio > 0.25 or consecutive >= 2) and not active_blocker,
        "ratio": ratio,
        "consecutive": consecutive,
        "active_quantified_blocker": active_blocker,
    }


@dataclass
class AcceptanceSeam:
    seam: str
    evidence_class: str
    owner: str
    owner_identity: str
    status: str = "PENDING"
    evidence: str = ""
    dependency_identity: str = ""

    def as_dict(self) -> dict[str, str]:
        if self.evidence_class not in VALID_EVIDENCE:
            raise WorkflowV2Error(f"invalid evidence class: {self.evidence_class}")
        if self.status not in VALID_SEAM_STATES:
            raise WorkflowV2Error(f"invalid acceptance seam status: {self.status}")
        return self.__dict__.copy()


@dataclass
class AcceptanceLedger:
    objective_id: str
    mode: str
    objective_state: str = "implementation-complete"
    seams: list[AcceptanceSeam] = field(default_factory=list)
    checkpoints: list[dict[str, Any]] = field(default_factory=list)

    def add(self, seam: AcceptanceSeam) -> None:
        self.seams.append(seam)

    def should_rerun(self, seam_name: str, *, owner_identity: str, dependency_identity: str = "") -> bool:
        seam = next((item for item in self.seams if item.seam == seam_name), None)
        if not seam or seam.status != "PROVEN":
            return True
        return seam.owner_identity != owner_identity or seam.dependency_identity != dependency_identity

    def as_dict(self) -> dict[str, Any]:
        if self.mode not in VALID_MODES:
            raise WorkflowV2Error(f"invalid ledger mode: {self.mode}")
        return {
            "workflow_version": workflow_version(),
            "objective_id": self.objective_id,
            "mode": self.mode,
            "objective_state": self.objective_state,
            "seams": [item.as_dict() for item in self.seams],
            "checkpoints": self.checkpoints,
        }
