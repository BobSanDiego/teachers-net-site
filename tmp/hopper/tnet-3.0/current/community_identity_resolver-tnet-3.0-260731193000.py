"""Process-local canonical Community identity resolver; no persistence or I/O."""
from __future__ import annotations
from copy import deepcopy
from dataclasses import dataclass, field
from typing import Any

STATUSES = {"resolved", "missing", "ambiguous", "duplicate", "inactive", "orphaned"}

@dataclass(frozen=True)
class Community:
    community_id: str
    lifecycle: str = "active"
    visibility: str = "public"
    legacy_paths: tuple[dict[str, Any], ...] = ()
    legacy_groups: tuple[dict[str, Any], ...] = ()
    publisher_context: dict[str, Any] = field(default_factory=dict)
    group_context: dict[str, Any] = field(default_factory=dict)
    evidence_ref: str = "synthetic-fixture"

class CommunityIdentityResolver:
    def __init__(self) -> None:
        self._communities: dict[str, Community] = {}
        self._sources: dict[tuple[str, Any], list[tuple[str, str, str]]] = {}
        self._orphaned: dict[tuple[str, Any], str] = {}

    def register(self, community: Community) -> None:
        if not community.community_id or community.community_id in self._communities:
            raise ValueError("COMMUNITY_REGISTRATION_CONFLICT")
        self._communities[community.community_id] = deepcopy(community)
        for ref in community.legacy_paths:
            self._add_source("path_id", ref.get("path_id"), community.community_id, ref.get("evidence_ref", community.evidence_ref), ref.get("status", "active"))
            self._add_source("local_path", ref.get("local_path"), community.community_id, ref.get("evidence_ref", community.evidence_ref), ref.get("status", "active"))
        for ref in community.legacy_groups:
            self._add_source("group_id", ref.get("group_id"), community.community_id, ref.get("evidence_ref", community.evidence_ref), ref.get("status", "active"))

    def add_orphaned_reference(self, kind: str, value: Any, evidence_ref: str = "synthetic-orphan") -> None:
        self._orphaned[(kind, value)] = evidence_ref

    def _add_source(self, kind: str, value: Any, community_id: str, evidence: str, status: str) -> None:
        if value in (None, ""): raise ValueError("LEGACY_REFERENCE_INVALID")
        self._sources.setdefault((kind, value), []).append((community_id, evidence, status))

    def _result(self, status: str, reason: str, source: dict[str, Any], entries: list[tuple[str, str, str]] = None) -> dict[str, Any]:
        result = {"status": status, "community_id": None, "source_reference": deepcopy(source), "evidence_ref": None, "permitted_context": {}, "reason_code": reason, "no_guess": True}
        if entries and status == "resolved":
            community = self._communities[entries[0][0]]
            result.update({"community_id": community.community_id, "evidence_ref": entries[0][1], "permitted_context": {"lifecycle": community.lifecycle, "visibility": community.visibility}})
        elif entries:
            result["evidence_ref"] = entries[0][1]
        return result

    def _resolve(self, kind: str, value: Any, reason_missing: str) -> dict[str, Any]:
        source = {kind: value}
        if value in (None, ""): return self._result("missing", reason_missing, source)
        if (kind, value) in self._orphaned: return self._result("orphaned", "LEGACY_REFERENCE_ORPHANED", source, [("", self._orphaned[(kind, value)], "orphaned")])
        entries = self._sources.get((kind, value), [])
        unique = {entry[0] for entry in entries}
        if not entries: return self._result("missing", reason_missing, source)
        if len(entries) > 1 and len(unique) == 1: return self._result("duplicate", "LEGACY_MAPPING_DUPLICATE", source, entries)
        if len(unique) > 1: return self._result("ambiguous", "LEGACY_MAPPING_AMBIGUOUS", source, entries)
        community = self._communities[entries[0][0]]
        if community.lifecycle != "active": return self._result("inactive", "COMMUNITY_INACTIVE", source, entries)
        return self._result("resolved", "COMMUNITY_RESOLVED", source, entries)

    def resolve_community_by_legacy_path(self, path_id: Any = None, local_path: str | None = None) -> dict[str, Any]:
        if path_id is not None: return self._resolve("path_id", path_id, "LEGACY_PATH_MISSING")
        return self._resolve("local_path", local_path, "LEGACY_PATH_MISSING")
    def resolve_community_by_legacy_group(self, group_id: Any) -> dict[str, Any]:
        return self._resolve("group_id", group_id, "LEGACY_GROUP_MISSING")
    def get_legacy_references(self, community_id: str) -> dict[str, Any]:
        community = self._communities.get(community_id)
        return deepcopy({"community_id": community_id, "legacy_paths": community.legacy_paths, "legacy_groups": community.legacy_groups}) if community else {"community_id": community_id, "status": "missing", "reason_code": "COMMUNITY_MISSING"}
    def get_group_context(self, community_id: str) -> dict[str, Any]:
        return deepcopy(self._communities[community_id].group_context) if community_id in self._communities else {}
    def get_publisher_context(self, community_id: str) -> dict[str, Any]:
        return deepcopy(self._communities[community_id].publisher_context) if community_id in self._communities else {}
