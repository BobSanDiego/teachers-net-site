#!/usr/bin/env python3
"""Deterministic Community formal-ticket workflow registry."""
from __future__ import annotations

import argparse
import json
import re
import shutil
from datetime import datetime, timezone
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
WORKFLOW = ROOT / "tmp" / "workflow" / "community"
TICKETS = WORKFLOW / "tickets"
REPORT = WORKFLOW / "report"
HOPPER = WORKFLOW / "hopper"
LEDGER = WORKFLOW / "execution-ledger.json"
REFRESH = WORKFLOW / "conversation-refresh.json"
COMMANDS = ["EXECUTE NEXT", "EXECUTE <ticket>", "EXECUTE ALL PENDING", "SHOW QUEUE", "SHOW NEXT", "SHOW REPORT", "SHOW HOPPER INDEX", "RETRY BLOCKED <ticket>", "ARCHIVE CURRENT", "WORKFLOW STATUS", "VALIDATE WORKFLOW", "LIST COMMANDS"]

def now() -> str: return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
def read_json(p: Path, default): return json.loads(p.read_text()) if p.exists() else default
def formal(p: Path) -> dict | None:
    text = p.read_text(errors="replace")
    if not re.search(r"(?m)^TICKET READY FOR CODEX\s*$", text): return None
    m = re.search(r"(?m)^Ticket:\s*(\S+)", text)
    return {"id": m.group(1) if m else p.stem, "path": str(p.relative_to(ROOT)), "text": text}
def queue() -> list[dict]: return [x for p in sorted(TICKETS.glob("*")) if p.is_file() and (x := formal(p))]
def ledger() -> dict: return read_json(LEDGER, {"version": 1, "tickets": []})
def archive() -> str:
    cycle = now(); dest = WORKFLOW / "archive" / cycle
    for base in (REPORT, HOPPER):
        current = base / "current"; current.mkdir(parents=True, exist_ok=True)
        target = dest / base.name; target.mkdir(parents=True, exist_ok=True)
        for item in current.iterdir(): shutil.move(str(item), str(target / item.name))
    (REPORT / "current").mkdir(parents=True, exist_ok=True); (HOPPER / "current").mkdir(parents=True, exist_ok=True)
    return cycle
def refresh() -> None:
    WORKFLOW.mkdir(parents=True, exist_ok=True); REFRESH.write_text(json.dumps({"refreshed_at": datetime.now(timezone.utc).isoformat(), "mode": "internal conversation lifecycle", "queue_rebuilt": True}, indent=2) + "\n")
def main() -> int:
    parser = argparse.ArgumentParser(add_help=True); parser.add_argument("command", nargs="+", help="workflow command words"); args = parser.parse_args(); command=" ".join(args.command).upper()
    if command == "LIST COMMANDS": print("\n".join(COMMANDS)); return 0
    if command.startswith("EXECUTE ") and command not in {"EXECUTE NEXT", "EXECUTE ALL PENDING"}:
        refresh(); ticket=command.removeprefix("EXECUTE "); match=next((x for x in queue() if x["id"]==ticket),None); print(json.dumps(match, indent=2)); return 0 if match else 1
    if command.startswith("RETRY BLOCKED "):
        refresh(); ticket=command.removeprefix("RETRY BLOCKED "); match=next((x for x in queue() if x["id"]==ticket),None); print(json.dumps(match, indent=2)); return 0 if match else 1
    if command in {"SHOW QUEUE", "SHOW NEXT", "EXECUTE NEXT", "EXECUTE ALL PENDING"}:
        refresh(); q=queue(); done={x["ticket_id"] for x in ledger()["tickets"] if x.get("status") in {"complete","blocked"}}; pending=[x for x in q if x["id"] not in done]
        if command == "SHOW NEXT" or command == "EXECUTE NEXT": print(json.dumps(pending[0] if pending else None, indent=2)); return 0
        print(json.dumps(pending, indent=2)); return 0
    if command == "ARCHIVE CURRENT": print(archive()); return 0
    if command == "WORKFLOW STATUS": print(json.dumps({"queue":len(queue()),"ledger":len(ledger()["tickets"]),"report_current":str(REPORT/"current"),"hopper_current":str(HOPPER/"current")}, indent=2)); return 0
    if command == "VALIDATE WORKFLOW":
        ok=all(p.exists() for p in (LEDGER, REFRESH, REPORT/"current", HOPPER/"current")); print(json.dumps({"valid":ok,"ledger":str(LEDGER),"refresh":str(REFRESH)}, indent=2)); return 0 if ok else 1
    if command in {"SHOW REPORT", "SHOW HOPPER INDEX"}:
        base=REPORT/"current" if command == "SHOW REPORT" else HOPPER/"current"; print("\n".join(str(p.relative_to(ROOT)) for p in sorted(base.glob("*")))); return 0
    parser.error("unsupported workflow command"); return 2
if __name__ == "__main__": raise SystemExit(main())
