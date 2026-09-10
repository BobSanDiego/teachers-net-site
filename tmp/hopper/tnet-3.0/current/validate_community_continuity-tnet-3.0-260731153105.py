"""Fail-closed semantic validation for a Community hopper cycle."""
from __future__ import annotations
import argparse, json
from pathlib import Path

def main() -> int:
    parser=argparse.ArgumentParser(); parser.add_argument("--cursor",required=True); parser.add_argument("--handoff",required=True); parser.add_argument("--cycle",required=True); args=parser.parse_args()
    cursor=Path(args.cursor).read_text(); handoff=Path(args.handoff).read_text(); cycle=json.loads(Path(args.cycle).read_text())
    ticket=cycle["ticket"]
    artifact_names={item.get("hopper_filename") for item in cycle.get("artifacts", []) if isinstance(item, dict)}
    checks=[(ticket in cursor, "cursor names completed ticket"),(ticket in handoff, "handoff names completed ticket"),(cycle["status"] in {"complete","payload-recreated"}, "cycle status valid"),(cycle["project"] == "tnet-3.0", "project slug valid"),("output.txt" not in artifact_names, "protected output excluded"),("C3-IMP002 is complete" not in cursor and "C3-IMP002 is complete" not in handoff, "unverified IMP002 not falsely completed"),("C3-NOT005 is complete" not in cursor and "C3-NOT005 is complete" not in handoff, "unverified NOT005 not falsely completed")]
    failed=[label for ok,label in checks if not ok]
    if failed: raise SystemExit("continuity validation failed: " + "; ".join(failed))
    print("continuity validation passed")
    return 0
if __name__ == "__main__": raise SystemExit(main())
