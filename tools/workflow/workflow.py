#!/usr/bin/env python3
"""Read-only workflow registry and cycle status helper.

Ticket execution remains ticket-specific; this utility does not infer or
implement application work.
"""
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
REGISTRY = ROOT / "tools/workflow/command-registry.json"

def registry():
    return json.loads(REGISTRY.read_text())

def list_commands():
    print("USER COMMANDS")
    for item in registry()["commands"]:
        print(f"- {item['command']} | {item['syntax']} | {item['purpose']} | stop: {item['stop']} | example: {item.get('example', item['syntax'])}")
    print("\nCHATGPT TICKET MARKERS")
    print("- TICKET READY FOR CODEX | formal executable ticket marker")
    print("\nINTERNAL CODEX ACTIONS")
    print("- Conversation refresh | automatic before EXECUTE NEXT, EXECUTE, EXECUTE ALL PENDING, SHOW QUEUE, and SHOW NEXT")

def status(project="jobcenter"):
    base = ROOT / "tmp/hopper" / project
    print(f"project={project}")
    print(f"report={base / 'Report (Job Center)'}")
    print(f"hopper={base / 'Hopper (Job Center)'}")
    print(f"archive={base / 'archive'}")
    print(f"registry={REGISTRY}")
    ledger = base / "workflow-ledger.json"
    print(f"ledger={ledger} exists={ledger.exists()}")

def show_report(project="jobcenter"):
    report = ROOT / "tmp/hopper" / project / "Report (Job Center)"
    print("Current Report directory:")
    for item in sorted(report.iterdir()):
        print(item.name)

def queue(project="jobcenter"):
    print("Conversation refreshed.")
    print("Queue rebuilt.")
    data = json.loads((ROOT / "tmp/hopper" / project / "workflow-ledger.json").read_text())
    for ticket in data.get("tickets", []):
        print(f"{ticket['ticket']} | {ticket['status']} | cycle={ticket.get('cycle')}")

def main(argv):
    command = " ".join(argv[1:]).upper()
    if command in {"LIST COMMANDS", "LIST-COMMANDS"}:
        list_commands(); return 0
    if command in {"WORKFLOW STATUS", "STATUS"}:
        status(); return 0
    if command in {"SHOW REPORT", "SHOW-REPORT"}:
        show_report(); return 0
    if command in {"SHOW QUEUE", "SHOW NEXT"}:
        queue(); return 0
    print("Supported inspection commands: LIST COMMANDS, WORKFLOW STATUS, SHOW REPORT, SHOW QUEUE, SHOW NEXT", file=sys.stderr)
    return 2

if __name__ == "__main__":
    raise SystemExit(main(sys.argv))
