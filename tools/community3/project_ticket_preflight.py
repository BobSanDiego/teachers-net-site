#!/usr/bin/env python3
"""Fail-closed project/branch preflight for Teachers.Net ticket work."""
from __future__ import annotations

import argparse
import subprocess
import sys


def branch_ok(project: str, branch: str) -> bool:
    if project == "community":
        return branch.startswith("COMMUNITY3-")
    if project == "jobcenter":
        return branch.startswith("JOB-CENTER-")
    return False


def ticket_ok(project: str, ticket: str) -> bool:
    if project == "community":
        return ticket.startswith("C3-")
    if project == "jobcenter":
        return ticket.startswith(("JC", "CT", "EMP"))
    return False


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project", choices=("community", "jobcenter"), required=True)
    parser.add_argument("--ticket", required=True)
    parser.add_argument("--branch", default=None)
    parser.add_argument("--hopper", required=True)
    parser.add_argument("--allow-integration", action="store_true")
    args = parser.parse_args()
    branch = args.branch or subprocess.check_output(["git", "branch", "--show-current"], text=True).strip()
    expected_hopper = "tnet-3.0" if args.project == "community" else "jobcenter"
    if args.allow_integration:
        print("preflight rejected: integration exception requires a separately authorized integration ticket")
        return 1
    failures = []
    if not ticket_ok(args.project, args.ticket): failures.append(f"ticket {args.ticket!r} is not a {args.project} ticket")
    if not branch_ok(args.project, branch): failures.append(f"branch {branch!r} is not owned by {args.project}")
    if args.hopper != expected_hopper: failures.append(f"hopper {args.hopper!r} does not match expected {expected_hopper!r}")
    dirty_paths = []
    for line in subprocess.check_output(["git", "status", "--porcelain"], text=True).splitlines():
        path = line[3:] if len(line) > 3 else line
        if not path.startswith("tmp/hopper/tnet-3.0/"):
            dirty_paths.append(path)
    if dirty_paths: failures.append("working tree has unprotected dirty paths: " + ", ".join(dirty_paths[:8]))
    if failures:
        print("preflight rejected: " + "; ".join(failures))
        return 1
    print(f"preflight passed: project={args.project} ticket={args.ticket} branch={branch} hopper={args.hopper}")
    return 0


if __name__ == "__main__": sys.exit(main())
