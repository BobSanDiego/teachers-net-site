#!/usr/bin/env python3
"""Shared bounded bootstrap authorization and lifecycle-readiness checks."""
from __future__ import annotations

import json
import re
from pathlib import Path

BOOTSTRAP_COMMAND = re.compile(r"^bootstrap this project as directed\s*$", re.I)


def is_bounded_bootstrap_authorization(instruction: str, project_name: str) -> bool:
    return bool(project_name.strip() and BOOTSTRAP_COMMAND.match(instruction.strip()))


def assert_lifecycle_ready(record: dict, *, report_dir: Path, hopper_dir: Path, checkpoint: Path) -> None:
    if not record.get("project_id") or not record.get("display_name"):
        raise ValueError("lifecycle readiness requires a valid project record")
    if not report_dir.is_dir() or not hopper_dir.is_dir():
        raise ValueError("lifecycle readiness requires Report and Hopper directories")
    if not checkpoint.is_dir() or not (checkpoint / "handoff-manifest.json").is_file():
        raise ValueError("lifecycle readiness requires a validated immutable checkpoint")
    cycles = sorted(hopper_dir.glob("cycle-*.json"))
    if not cycles:
        raise ValueError("lifecycle readiness requires a Hopper cycle record")
    payload = json.loads(cycles[-1].read_text(encoding="utf-8"))
    if payload.get("project") != record["project_id"] or payload.get("status") != "complete":
        raise ValueError("latest lifecycle cycle is not a complete matching project cycle")
    for name in (payload.get("report_file"), payload.get("manifest_file"), payload.get("cycle_record_file")):
        if not name or not (report_dir / name).is_file() or not (hopper_dir / name).is_file():
            raise ValueError(f"required lifecycle artifact missing: {name}")
