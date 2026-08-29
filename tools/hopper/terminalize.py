#!/usr/bin/env python3
"""Fail-closed Workflow V2 terminalization entrypoint."""
from __future__ import annotations
import argparse
from pathlib import Path
import clean_cycle

def main() -> int:
    p = argparse.ArgumentParser(description="Finalize and validate one V2 cycle.")
    p.add_argument('--project', required=True); p.add_argument('--cycle', required=True)
    p.add_argument('--ticket', required=True); p.add_argument('--ticket-source', required=True)
    p.add_argument('--report-source', required=True); p.add_argument('--mode', required=True)
    p.add_argument('--objective-owner', required=True); p.add_argument('--evidence', required=True)
    p.add_argument('--evidence-class', default='FUNCTIONAL'); p.add_argument('--git-disposition', default='NOT_APPLICABLE')
    p.add_argument('--commit'); p.add_argument('--push'); p.add_argument('--artifact-json', action='append', default=[])
    a=p.parse_args(); ticket=Path(a.ticket_source); report=Path(a.report_source)
    preflight=clean_cycle.validate_ticket_payload(ticket.read_text(encoding='utf-8'))
    preflight.update({'source_path':str(ticket.resolve()),'source_bytes':ticket.stat().st_size,'source_sha256':clean_cycle.sha256(ticket),'title':a.ticket})
    artifacts=[clean_cycle.collect(a.project,a.cycle,ticket,'REPORT_REQUIRED'), clean_cycle.collect(a.project,a.cycle,report,'REPORT_REQUIRED')]
    clean_cycle.write_records(a.project,a.ticket,a.cycle,'unknown','complete',a.commit,a.push,artifacts,a.evidence,a.git_disposition,report_source=report,mode=a.mode,evidence_class=a.evidence_class,objective_owner=a.objective_owner,ticket_preflight=preflight)
    clean_cycle.validate(a.project,a.cycle)
    print(f'terminalized and validated {a.project}/{a.cycle}')
    return 0
if __name__ == '__main__': raise SystemExit(main())
