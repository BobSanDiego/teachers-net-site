#!/usr/bin/env python3
"""Deterministic shared semantic authority, harvest, and delivery helpers."""
from __future__ import annotations

import json
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

ROOT = Path(__file__).resolve().parents[2]
DEFAULT_AUTHORITY = ROOT / "docs/process/conversation-handoff/shared/semantic-authority.json"
DEFAULT_CURSORS = ROOT / "tmp/hopper/shared-workflow/semantic-sync/project-cursors.json"
VALID_DISPOSITIONS = {"PROPOSED", "SUPPORTED", "APPROVED", "IMPLEMENTED", "DEFERRED", "REJECTED", "SUPERSEDED"}
REQUIRED_RECORD_KEYS = {
    "concept", "disposition", "canonical_state", "direction", "source_project",
    "source_session", "source_cycle", "engineering_director_authority",
    "affected_frameworks", "affected_projects", "evidence_pointers", "supersedes",
}


class SemanticError(RuntimeError):
    pass


def _load(path: Path) -> dict[str, Any]:
    return json.loads(path.read_text(encoding="utf-8"))


def _write(path: Path, value: dict[str, Any]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(value, indent=2, sort_keys=True) + "\n", encoding="utf-8")


def initialize(authority_path: Path = DEFAULT_AUTHORITY, cursor_path: Path = DEFAULT_CURSORS) -> None:
    authority = _load(authority_path)
    if not cursor_path.exists():
        _write(cursor_path, {"schema_version": 1, "projects": {
            project: {"catalog_revision_acknowledged": 0, "semantic_revision_acknowledged": 0,
                      "pending_relevant_deltas": [], "consumer_adoptions": authority["consumer_adoptions"].get(project, [])}
            for project in authority["registered_projects"]
        }})


def _record_map(authority: dict[str, Any]) -> dict[str, dict[str, Any]]:
    return {record["id"]: record for record in authority["records"]}


def _validate_record(record: dict[str, Any]) -> None:
    missing = REQUIRED_RECORD_KEYS - set(record)
    if missing:
        raise SemanticError("semantic record missing: " + ", ".join(sorted(missing)))
    if record["disposition"] not in VALID_DISPOSITIONS:
        raise SemanticError("invalid semantic disposition")


def _revisioned_records(authority: dict[str, Any], project: str, known_semantic: int) -> list[dict[str, Any]]:
    return [record for record in authority["records"]
            if project in record["affected_projects"]
            and record.get("semantic_revision", 0) > known_semantic]


def harvest(authority_path: Path, candidate: dict[str, Any]) -> dict[str, Any]:
    """Mechanically admit a non-conflicting record or fail closed on conflict/stale input."""
    authority = _load(authority_path)
    _validate_record(candidate)
    if (candidate.get("base_catalog_revision") != authority["catalog_revision"]
            or candidate.get("base_semantic_revision") != authority["semantic_revision"]):
        raise SemanticError("STALE SEMANTIC HARVEST")
    existing = [r for r in authority["records"] if r["concept"] == candidate.get("concept") and r["disposition"] in {"APPROVED", "IMPLEMENTED"}]
    if existing and any(r.get("direction") != candidate.get("direction") for r in existing):
        raise SemanticError("SEMANTIC DECISION REQUIRED")
    candidate = dict(candidate)
    candidate.setdefault("id", f"semantic-{len(authority['records']) + 1:03d}")
    candidate.setdefault("created_at", datetime.now(timezone.utc).isoformat())
    if candidate["disposition"] in {"APPROVED", "IMPLEMENTED"}:
        authority["semantic_revision"] += 1
    candidate["semantic_revision"] = authority["semantic_revision"]
    candidate["catalog_revision"] = authority["catalog_revision"]
    authority["records"].append(candidate)
    _write(authority_path, authority)
    return candidate


def advance_catalog(authority_path: Path, *, source_project: str, source_cycle: str,
                    authority_note: str, evidence_pointers: list[str]) -> int:
    """Record an implemented catalog revision without declaring a semantic decision."""
    authority = _load(authority_path)
    authority["catalog_revision"] += 1
    authority.setdefault("catalog_history", []).append({
        "catalog_revision": authority["catalog_revision"],
        "source_project": source_project,
        "source_cycle": source_cycle,
        "engineering_director_authority": authority_note,
        "evidence_pointers": evidence_pointers,
    })
    _write(authority_path, authority)
    return authority["catalog_revision"]


def delta(authority_path: Path, project: str, known_catalog: int, known_semantic: int) -> dict[str, Any]:
    authority = _load(authority_path)
    if project not in authority["registered_projects"]:
        raise SemanticError(f"unregistered project: {project}")
    relevant = _revisioned_records(authority, project, known_semantic)
    return {"project": project, "catalog_revision": authority["catalog_revision"], "semantic_revision": authority["semantic_revision"],
            "catalog_changed": authority["catalog_revision"] > known_catalog,
            "records": relevant, "consumer_adoption": authority["consumer_adoptions"].get(project, [])}


def package_delta(authority_path: Path, cursors_path: Path, project: str) -> str:
    initialize(authority_path, cursors_path)
    cursor = _load(cursors_path)["projects"][project]
    item = delta(authority_path, project, cursor["catalog_revision_acknowledged"], cursor["semantic_revision_acknowledged"])
    if not item["catalog_changed"] and not item["records"]:
        return ""
    lines = ["## Authoritative semantic/catalog delta", f"SEMANTIC REVISION {cursor['semantic_revision_acknowledged']} -> {item['semantic_revision']}", f"CATALOG REVISION {cursor['catalog_revision_acknowledged']} -> {item['catalog_revision']}"]
    for record in item["records"]:
        lines += [f"### {record['disposition']} — {record['concept']}", record["direction"], f"Canonical state: {record['canonical_state']}", f"Project impact: {record.get('project_impact', {}).get(project, 'INFORMATIONAL')}"]
    lines += ["Consumer adoption remains explicit and unchanged:", json.dumps(item["consumer_adoption"], sort_keys=True)]
    return "\n\n".join(lines) + "\n"


def acknowledge(authority_path: Path, cursors_path: Path, project: str, catalog_revision: int, semantic_revision: int) -> None:
    authority, cursors = _load(authority_path), _load(cursors_path)
    if catalog_revision > authority["catalog_revision"] or semantic_revision > authority["semantic_revision"]:
        raise SemanticError("acknowledgment exceeds current authority")
    cursor = cursors["projects"][project]
    cursor["catalog_revision_acknowledged"] = max(cursor["catalog_revision_acknowledged"], catalog_revision)
    cursor["semantic_revision_acknowledged"] = max(cursor["semantic_revision_acknowledged"], semantic_revision)
    cursor["pending_relevant_deltas"] = []
    _write(cursors_path, cursors)


def delivery_metadata(authority_path: Path, cursors_path: Path, projects: list[str]) -> dict[str, Any]:
    """Return recipient-specific metadata for a raw ChatGPT generation.

    This is intentionally side-effect free. A verified raw-generation ACK is
    the only event permitted to advance a semantic cursor.
    """
    initialize(authority_path, cursors_path)
    authority, cursors = _load(authority_path), _load(cursors_path)
    recipients: dict[str, Any] = {}
    for project in projects:
        cursor = cursors["projects"][project]
        item = delta(authority_path, project, cursor["catalog_revision_acknowledged"], cursor["semantic_revision_acknowledged"])
        recipients[project] = {
            "catalog_revision": item["catalog_revision"],
            "semantic_revision": item["semantic_revision"],
            "records": [record["id"] for record in item["records"]],
            "relevant": bool(item["catalog_changed"] or item["records"]),
        }
    return {"catalog_revision": authority["catalog_revision"], "semantic_revision": authority["semantic_revision"], "recipients": recipients}
