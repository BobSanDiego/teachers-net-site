"""Fail-closed semantic validation for a Community hopper cycle."""
from __future__ import annotations
import argparse, json, re
from pathlib import Path

def validate_paths(cursor_path: Path, handoff_path: Path, cycle_path: Path, manifest_path: Path) -> None:
    cursor=cursor_path.read_text(); handoff=handoff_path.read_text(); cycle=json.loads(cycle_path.read_text()); manifest=manifest_path.read_text()
    ticket=cycle["ticket"]
    artifact_names={item.get("hopper_filename") for item in cycle.get("artifacts", []) if isinstance(item, dict)}
    cursor_phase=re.search(r"^Bounded implementation preparation — (.+?) complete;", cursor, re.MULTILINE)
    handoff_phase=re.search(r"^Bounded implementation preparation — (.+?) complete;", handoff, re.MULTILINE)
    next_block=cursor.split("## Next Authorized Ticket",1)[-1].split("## Next Decision",1)[0]
    checks=[
        (ticket in cursor and re.search(rf"{re.escape(ticket)} is complete", cursor) is not None, "cursor names completed ticket"),
        (ticket in handoff and re.search(rf"{re.escape(ticket)} is complete", handoff) is not None, "handoff names completed ticket"),
        (cursor_phase is not None and handoff_phase is not None and cursor_phase.group(1) == handoff_phase.group(1), "cursor and handoff phases agree"),
        (cycle["status"] == "complete", "cycle status is complete"),
        (cycle["project"] == "tnet-3.0", "project slug valid"),
        (manifest and f"ticket={ticket}" in manifest and f"cycle_id={cycle['cycle_id']}" in manifest, "manifest agrees with cycle identity"),
        (f"commit={cycle.get('commit')}" in manifest and f"push={cycle.get('push')}" in manifest, "manifest agrees with cycle finalization"),
        ("output.txt" not in artifact_names, "protected output excluded"),
        (not re.search(rf"(?:next|proposed|authorized).*{re.escape(ticket)}", next_block, re.IGNORECASE), "next ticket does not point backward"),
        ("C3-IMP002 is complete" not in cursor and "C3-IMP002 is complete" not in handoff, "unverified IMP002 not falsely completed"),
        ("C3-NOT005 is complete" not in cursor and "C3-NOT005 is complete" not in handoff, "unverified NOT005 not falsely completed"),
    ]
    failed=[label for ok,label in checks if not ok]
    if failed: raise SystemExit("continuity validation failed: " + "; ".join(failed))

def main() -> int:
    parser=argparse.ArgumentParser(); parser.add_argument("--cursor",required=True); parser.add_argument("--handoff",required=True); parser.add_argument("--cycle",required=True); parser.add_argument("--manifest",required=True); args=parser.parse_args()
    validate_paths(Path(args.cursor), Path(args.handoff), Path(args.cycle), Path(args.manifest))
    print("continuity validation passed")
    return 0
if __name__ == "__main__": raise SystemExit(main())
