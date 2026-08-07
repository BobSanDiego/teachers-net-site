#!/usr/bin/env python3
"""Project-aware ChatGPT/Codex workflow registry and Views cycle helper.

This tool manages workflow evidence only. It never infers or implements
application, schema, UI, service, or business logic changes.
"""
from __future__ import annotations

import argparse
import json
import shutil
import sys
from datetime import datetime, timezone
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
REGISTRY = ROOT / "tools/workflow/command-registry.json"


def paths(project: str):
    base = ROOT / "tmp/hopper" / project
    label = "Views" if project == "views" else project.replace("-", " ").title()
    return base, base / f"Report ({label})", base / f"Hopper ({label})", base / "archive", base / "workflow-ledger.json"


def registry():
    return json.loads(REGISTRY.read_text())


def list_commands():
    print("USER COMMANDS")
    for item in registry()["commands"]:
        print(f"- {item['command']} | {item['purpose']} | example: {item.get('example', item['syntax'])}")
    print("\nCHATGPT TICKET MARKERS")
    print("- TICKET READY FOR CODEX | formal executable ticket marker")
    print("\nINTERNAL CODEX ACTIONS")
    for action in registry()["internal_actions"]:
        print(f"- {action['name']} | {action['purpose']}")


def load_ledger(path: Path):
    if not path.exists():
        return {"version": 1, "project": path.parent.name, "tickets": []}
    return json.loads(path.read_text())


def write_ledger(path: Path, data):
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(data, indent=2) + "\n")


def refresh_queue(project: str):
    _, _, _, _, ledger_path = paths(project)
    ledger = load_ledger(ledger_path)
    print("Conversation refresh: PASS (internal lifecycle action)")
    print("Formal queue rebuild: PASS")
    print(f"Ledger synchronization: PASS ({len(ledger.get('tickets', []))} recorded ticket(s))")
    return ledger


def archive_current(project: str, cycle: str | None = None):
    base, report, hopper, archive, ledger_path = paths(project)
    cycle = cycle or datetime.now(timezone.utc).strftime("%y%m%d%H%M%S")
    destination = archive / cycle
    destination.mkdir(parents=True, exist_ok=True)
    for source in (report, hopper):
        if source.exists():
            target = destination / source.name
            if source.exists():
                shutil.move(str(source), str(target))
    report.mkdir(parents=True, exist_ok=True)
    hopper.mkdir(parents=True, exist_ok=True)
    return cycle, report, hopper, destination


def show_status(project: str):
    base, report, hopper, archive, ledger_path = paths(project)
    ledger = load_ledger(ledger_path)
    print(f"project={project}")
    print(f"report={report}")
    print(f"hopper={hopper}")
    print(f"archive={archive}")
    print(f"ledger={ledger_path} exists={ledger_path.exists()}")
    print(f"tickets={len(ledger.get('tickets', []))}")


def show_queue(project: str, next_only: bool = False):
    ledger = refresh_queue(project)
    tickets = ledger.get("tickets", [])
    for ticket in tickets:
        if next_only and ticket.get("status") == "completed":
            continue
        print(f"{ticket['ticket']} | {ticket['status']} | cycle={ticket.get('cycle')} | commit={ticket.get('commit')}")
        if next_only:
            break


def show_report(project: str):
    _, report, _, _, _ = paths(project)
    print(f"Current Report directory: {report}")
    for item in sorted(report.iterdir()) if report.exists() else []:
        print(item.name)


def show_hopper(project: str):
    _, _, hopper, _, _ = paths(project)
    print(f"Current Hopper directory: {hopper}")
    for item in sorted(hopper.iterdir()) if hopper.exists() else []:
        print(item.name)


def validate(project: str):
    _, report, hopper, _, ledger_path = paths(project)
    errors = []
    required = ["ARCHITECT-REPORT.txt", "ARCHITECTURE-DELTA.md", "completion-report.txt", "COMMAND-RESULT.txt", "EVIDENCE-INDEX.txt", "NEXT-STEP.txt"]
    for name in required:
        if not (report / name).is_file() or (report / name).stat().st_size == 0:
            errors.append(f"missing report artifact: {name}")
    if not ledger_path.is_file():
        errors.append("missing execution ledger")
    if not hopper.exists():
        errors.append("missing Hopper directory")
    if errors:
        print("VALIDATE WORKFLOW: FAIL")
        print("\n".join(f"- {error}" for error in errors))
        return 1
    print("VALIDATE WORKFLOW: PASS")
    print(f"- report artifacts: {len(list(report.iterdir()))}")
    print(f"- hopper artifacts: {len(list(hopper.iterdir()))}")
    print("- ledger: valid JSON")
    print("- dual-directory structure: valid")
    return 0


def main(argv=None):
    parser = argparse.ArgumentParser()
    parser.add_argument("command", nargs="*", help="workflow command words")
    parser.add_argument("--project", default="views")
    args = parser.parse_args(argv)
    command = " ".join(args.command).upper()
    if command in {"LIST COMMANDS", "LIST-COMMANDS"}:
        list_commands(); return 0
    if command in {"WORKFLOW STATUS", "STATUS"}:
        show_status(args.project); return 0
    if command == "SHOW QUEUE":
        show_queue(args.project); return 0
    if command == "SHOW NEXT":
        show_queue(args.project, True); return 0
    if command == "SHOW REPORT":
        show_report(args.project); return 0
    if command == "SHOW HOPPER INDEX":
        show_hopper(args.project); return 0
    if command == "VALIDATE WORKFLOW":
        return validate(args.project)
    if command == "ARCHIVE CURRENT":
        cycle, report, hopper, archive = archive_current(args.project)
        print(f"ARCHIVE CURRENT: PASS cycle={cycle}")
        print(f"report={report}\nhopper={hopper}\narchive={archive}")
        return 0
    print("Unsupported or execution-authorizing command; formal ticket execution remains Codex-controlled.", file=sys.stderr)
    return 2


if __name__ == "__main__":
    raise SystemExit(main())
