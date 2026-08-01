#!/usr/bin/env python3
"""Reject mixed Community/Job Center hopper payloads."""
from __future__ import annotations

import argparse
import json
from pathlib import Path


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("cycle", type=Path)
    args = parser.parse_args()
    cycle = json.loads(args.cycle.read_text())
    failures = []
    if cycle.get("project") != "tnet-3.0": failures.append("project must be tnet-3.0")
    if not str(cycle.get("ticket", "")).startswith("C3-"): failures.append("ticket must use the C3- Community family")
    if not str(cycle.get("branch", "")).startswith("COMMUNITY3-"): failures.append("branch must be Community-owned")
    forbidden = ("job-center", "jobcenter", "tnet-jobs", "docs/job-center", "tmp/jc", "JC053")
    for artifact in cycle.get("artifacts", []):
        value = " ".join(str(artifact.get(key, "")) for key in ("hopper_filename", "original_path", "purpose"))
        if any(token.lower() in value.lower() for token in forbidden): failures.append(f"foreign artifact rejected: {value}")
    if failures:
        print("hopper payload rejected: " + "; ".join(failures))
        return 1
    print("Community hopper payload passed")
    return 0


if __name__ == "__main__": raise SystemExit(main())
