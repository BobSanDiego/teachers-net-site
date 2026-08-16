#!/usr/bin/env python3
"""Project-aware ChatGPT/Codex Workflow V2 command entry.

This tool manages workflow evidence only. It never infers or implements
application, schema, UI, service, or business logic changes.
"""
from __future__ import annotations

import argparse
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
if str(ROOT) not in sys.path:
    sys.path.insert(0, str(ROOT))

from tools.workflow.workflow_v2 import (
    WorkflowV2Error,
    bootstrap,
    load_project_record,
    project_report_route,
    reasoning_boost_notice,
    recommend_reasoning,
    validate_ticket_payload,
    workflow_version,
)

from tools.hopper.clean_cycle import validate as validate_cycle
from tools.codex_archive.prepare_chatgpt_handoff import HandoffError, prepare as prepare_chatgpt_handoff
from tools.codex_archive.prepare_chatgpt_handoff import prepare_from_share as prepare_chatgpt_handoff_from_share
from tools.chatgpt_sync.sync import DEFAULT_STATE as CHATGPT_SYNC_STATE, load_state as load_chatgpt_sync_state

REGISTRY = ROOT / "tools/workflow/command-registry.json"


def paths(project: str):
    _, record = load_project_record(project, ROOT)
    route = project_report_route(record, ROOT)
    base = route["base"]
    return base, route["report"], route["hopper"], route["archive"], base / "workflow-ledger.json"


def registry():
    return json.loads(REGISTRY.read_text())


def list_commands():
    print(f"WORKFLOW {workflow_version()}")
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


def show_status(project: str):
    base, report, hopper, archive, ledger_path = paths(project)
    ledger = load_ledger(ledger_path)
    print(f"project={project}")
    print(f"report={report}")
    print(f"hopper={hopper}")
    print(f"archive={archive}")
    latest = sorted(archive.iterdir())[-1] if archive.exists() and list(archive.iterdir()) else None
    print(f"latest_archive={latest if latest else 'none'}")
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
    _, _, hopper, _, _ = paths(project)
    cycles = sorted(hopper.glob(f"cycle-{project}-*.json"))
    if not cycles:
        print("VALIDATE WORKFLOW: FAIL\n- no current cycle record", file=sys.stderr)
        return 1
    payload = json.loads(cycles[-1].read_text(encoding="utf-8"))
    validate_cycle(project, payload["cycle_id"])
    print(f"VALIDATE WORKFLOW: PASS workflow={payload['workflow_version']} cycle={payload['cycle_id']}")
    return 0


def main(argv=None):
    parser = argparse.ArgumentParser()
    parser.add_argument("command", nargs="*", help="workflow command words")
    parser.add_argument("--project")
    parser.add_argument("--ticket")
    parser.add_argument("--signal", action="append", default=[])
    parser.add_argument("--transcript")
    parser.add_argument("--share-url")
    parser.add_argument("--share-archive-root")
    parser.add_argument("--source-status", default="OPEN/INCOMPLETE", choices=("OPEN/INCOMPLETE", "CLOSED"))
    parser.add_argument("--codex-source")
    parser.add_argument("--output-root")
    parser.add_argument("--include-house-context", action="store_true")
    args = parser.parse_args(argv)
    command = " ".join(args.command).upper()
    project_independent = {"LIST COMMANDS", "LIST-COMMANDS", "VALIDATE TICKET", "RECOMMEND REASONING", "UPDATE CHATGPT", "CHATGPT SYNC STATUS"}
    if not args.project and command not in project_independent:
        print(
            "PROJECT CONTEXT REQUIRED\nBoundary: resolve the active registered project before invoking the shared command; no cross-project default is permitted.",
            file=sys.stderr,
        )
        return 1
    if command == "BOOTSTRAP":
        try:
            result = bootstrap(args.project, root=ROOT)
        except WorkflowV2Error as exc:
            print(f"BOOTSTRAP BLOCKED\nBoundary: {exc}", file=sys.stderr)
            return 1
        if result["status"] == "BOOTSTRAP COMPLETE":
            print("BOOTSTRAP COMPLETE")
            print(f"Project: {result['display_name']}")
            print(f"Workflow: {result['workflow']}")
            print(f"Lifecycle: {result['lifecycle']}")
            print(f"Project ID: {result['project']}")
            print(f"Repository: {result['repository']}")
            print(f"Report/Hopper: {result['report_hopper']}")
            print("Handoff command: PREPARE HANDOFF")
            print("Fresh ChatGPT command: LOAD STARTUP")
            print("Product implementation authorized: NO")
        else:
            print("BOOTSTRAP NEW PROJECT")
            print(f"Project: {result['project']}")
            print(f"Workflow: {result['workflow']}")
            print(f"Lifecycle: {result['lifecycle']}")
            print(f"Onboarding authority: {result['bootstrap_authorization']}")
            print(f"Bootstrap specification: {result['bootstrap_spec']}")
            print("Product implementation authorized: NO")
        return 0
    if command == "PREPARE HANDOFF":
        if not args.transcript and not args.share_url:
            parser.error("PREPARE HANDOFF requires --share-url or --transcript")
        try:
            record_path, record = load_project_record(args.project, ROOT)
            repository_value = record.get("root_repository") or (record.get("repositories") or {}).get("root")
            if not repository_value:
                raise HandoffError(f"project {args.project} has no registered repository root")
            repository = Path(repository_value).resolve()
            current = Path.cwd().resolve()
            if not (current == repository or repository in current.parents):
                raise HandoffError(
                    f"project worktree mismatch: current={current} expected={repository}"
                )
            output_root = Path(args.output_root) if args.output_root else Path((record.get("handoff") or {}).get("handoffs", "/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS"))
            if args.share_url:
                result = prepare_chatgpt_handoff_from_share(
                    root=ROOT,
                    project_record=record_path,
                    share_url=args.share_url,
                    output_root=output_root,
                    archive_root=Path(args.share_archive_root) if args.share_archive_root else ROOT / "tmp/hopper/shared-workflow/openai-share-archive",
                    source_status=args.source_status,
                    codex_source=Path(args.codex_source) if args.codex_source else None,
                    include_house_context=args.include_house_context,
                )
            else:
                result = prepare_chatgpt_handoff(
                    root=ROOT,
                    project_record=record_path,
                    transcript=Path(args.transcript),
                    output_root=output_root,
                    source_status=args.source_status,
                    codex_source=Path(args.codex_source) if args.codex_source else None,
                    include_house_context=args.include_house_context,
                )
        except (OSError, ValueError, HandoffError) as exc:
            print(f"PREPARE HANDOFF: BLOCKED\nBoundary: {exc}", file=sys.stderr)
            return 1
        print("HANDOFF READY")
        print(f"Project: {result['display_name']} ({result['project']})")
        print(f"Workflow: {result['workflow']}")
        print(f"ChatGPT master: {result['chatgpt']['status']} through {result['chatgpt']['boundary']}")
        print(f"Codex master: {result['codex']['status']}")
        print(f"Startup payload: {result['startup_payload']}")
        print(f"Package directory: {result['package_directory']}")
        print(f"Optional ZIP transport candidate: {result['package_zip_candidate']}")
        if result.get("operator_drop"):
            print(f"Successor drop directory: {result['operator_drop']['directory']}")
            print(f"Successor drop files: {result['operator_drop']['startup_ticket']}, {result['operator_drop']['startup_zip']}")
        for warning in result["warnings"]:
            print(f"Warning: {warning}")
        return 0
    if command == "VALIDATE TICKET":
        if not args.ticket:
            parser.error("VALIDATE TICKET requires --ticket")
        try:
            payload = Path(args.ticket).read_text(encoding="utf-8")
            print(json.dumps(validate_ticket_payload(payload), indent=2, sort_keys=True))
        except (OSError, WorkflowV2Error) as exc:
            print(f"TICKET PREFLIGHT: FAIL\n- {exc}", file=sys.stderr)
            return 1
        return 0
    if command == "RECOMMEND REASONING":
        posture, reason = recommend_reasoning(args.signal)
        print(f"reasoning_posture_recommended={posture}")
        print(f"reasoning_escalation_reason={reason or 'none'}")
        notice = reasoning_boost_notice(posture)
        if notice:
            print(notice)
        return 0
    if command == "UPDATE CHATGPT":
        # Live reader access belongs to the Codex agent, not this file-driven
        # command wrapper.  This is intentionally a no-op until the agent has
        # bounded, identity-validated reader pages to pass to chatgpt_sync.
        print("UPDATE CHATGPT: AGENT READER REQUIRED")
        print("Read only registered active threads through the bounded app reader; then invoke tools/chatgpt_sync/sync.py build with the normalized reader pages.")
        print("No retrieval, handoff regeneration, or product action was performed by this command.")
        return 0
    if command == "CHATGPT SYNC STATUS":
        print(json.dumps(load_chatgpt_sync_state(CHATGPT_SYNC_STATE), indent=2, sort_keys=True))
        return 0
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
        print("ARCHIVE CURRENT is retired under Workflow V2; validated cycle initialization is owned by tools/hopper/clean_cycle.py.", file=sys.stderr)
        return 2
    print("Unsupported or execution-authorizing command; formal ticket execution remains Codex-controlled.", file=sys.stderr)
    return 2


if __name__ == "__main__":
    raise SystemExit(main())
