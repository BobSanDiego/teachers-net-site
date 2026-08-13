#!/usr/bin/env python3
"""Focused regression tests for shared semantic authority and delivery state."""
from __future__ import annotations

import json
import shutil
import tempfile
from pathlib import Path
import sys

ROOT = Path(__file__).resolve().parents[2]
sys.path.insert(0, str(ROOT))
from tools.semantic_sync import sync

SOURCE = ROOT / "docs/process/conversation-handoff/shared/semantic-authority.json"


def candidate(**overrides):
    record = {
        "concept": "Example semantic direction",
        "disposition": "APPROVED",
        "canonical_state": "No catalog mutation.",
        "direction": "Example only.",
        "source_project": "views",
        "source_session": "test",
        "source_cycle": "test",
        "engineering_director_authority": "test authority",
        "affected_frameworks": ["Core Terms"],
        "affected_projects": ["views"],
        "evidence_pointers": ["test"],
        "supersedes": [],
        "base_catalog_revision": 1,
        "base_semantic_revision": 1,
    }
    record.update(overrides)
    return record


def expect_error(fn, message):
    try:
        fn()
    except sync.SemanticError as exc:
        assert message in str(exc), exc
    else:
        raise AssertionError(f"expected {message}")


def main() -> int:
    with tempfile.TemporaryDirectory() as tmp:
        root = Path(tmp)
        authority, cursors = root / "authority.json", root / "cursors.json"
        shutil.copy2(SOURCE, authority)
        sync.initialize(authority, cursors)

        # Catalog and semantic revisions are independent.
        assert sync.advance_catalog(authority, source_project="views", source_cycle="test", authority_note="test", evidence_pointers=[]) == 2
        current = json.loads(authority.read_text())
        assert current["semantic_revision"] == 1
        approved = candidate(base_catalog_revision=2, base_semantic_revision=1)
        sync.harvest(authority, approved)
        current = json.loads(authority.read_text())
        assert current["catalog_revision"] == 2 and current["semantic_revision"] == 2

        # Packaging is not acknowledgement; only the target recipient advances.
        metadata = sync.delivery_metadata(authority, cursors, ["views", "profile"])
        assert metadata["recipients"]["views"]["relevant"]
        before = json.loads(cursors.read_text())
        assert before["projects"]["views"]["semantic_revision_acknowledged"] == 0
        sync.acknowledge(authority, cursors, "views", 2, 2)
        after = json.loads(cursors.read_text())
        assert after["projects"]["views"]["semantic_revision_acknowledged"] == 2
        assert after["projects"]["profile"]["semantic_revision_acknowledged"] == 0

        # A stale source cannot overwrite accepted authority.
        expect_error(lambda: sync.harvest(authority, candidate()), "STALE SEMANTIC HARVEST")
        conflicting = candidate(base_catalog_revision=2, base_semantic_revision=2, direction="Contradictory direction")
        expect_error(lambda: sync.harvest(authority, conflicting), "SEMANTIC DECISION REQUIRED")

        # Deferred, rejected, and superseded records remain historical authority.
        deferred = candidate(concept="Deferred placement", disposition="DEFERRED", base_catalog_revision=2, base_semantic_revision=2)
        sync.harvest(authority, deferred)
        rejected = candidate(concept="Rejected placement", disposition="REJECTED", base_catalog_revision=2, base_semantic_revision=2)
        sync.harvest(authority, rejected)
        superseded = candidate(concept="Old placement", disposition="SUPERSEDED", base_catalog_revision=2, base_semantic_revision=2, supersedes=["semantic-001"])
        sync.harvest(authority, superseded)
        concepts = {r["concept"]: r["disposition"] for r in json.loads(authority.read_text())["records"]}
        assert concepts["Deferred placement"] == "DEFERRED" and concepts["Rejected placement"] == "REJECTED" and concepts["Old placement"] == "SUPERSEDED"

        # Consumer adoption is not changed by semantic delivery, and irrelevant
        # projects do not receive a detailed semantic payload.
        assert json.loads(cursors.read_text())["projects"]["jobcenter"]["consumer_adoptions"] == [
            {"consumer": "Subject Area", "view": "Jobs Subjects", "version": 2},
            {"consumer": "Grade Level", "view": "Jobs Grade Levels", "version": 1},
        ]
        assert sync.delta(authority, "community", 2, 2)["records"] == []
    print("semantic sync tests: PASS")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
