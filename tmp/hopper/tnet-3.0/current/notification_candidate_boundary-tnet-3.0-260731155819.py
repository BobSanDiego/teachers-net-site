"""Test-only Community 3.0 candidate/audit boundary."""
from __future__ import annotations
from typing import Any

ALLOWED_DECISIONS = {"eligible", "blocked", "ineligible"}

def build_candidate(evaluator_output: dict[str, Any]) -> dict[str, Any]:
    decision = str(evaluator_output["decision"])
    if decision not in ALLOWED_DECISIONS:
        raise ValueError(f"unsupported evaluator decision: {decision}")
    event = evaluator_output["event"]
    mapping = evaluator_output["mapping"]
    reasons = tuple(sorted(str(reason) for reason in evaluator_output.get("reasons", ())))
    return {"candidate_version":"1", "candidate_id":f"cand:{event['event_id']}:{evaluator_output['recipient_id']}", "event_id":str(event["event_id"]), "recipient_id":str(evaluator_output["recipient_id"]), "decision":decision, "reason_codes":reasons, "path_id":int(mapping["path_id"]), "group_id":int(mapping["group_id"]), "channels":{"bell":"deferred","email":"deferred","digest":"deferred","delivery":"deferred"}, "persistent":False}

def build_audit_record(evaluator_output: dict[str, Any], candidate: dict[str, Any]) -> dict[str, Any]:
    event = evaluator_output["event"]
    return {"audit_version":"1", "record_type":"candidate_decision", "event_id":candidate["event_id"], "candidate_id":candidate["candidate_id"], "recipient_id":candidate["recipient_id"], "decision":candidate["decision"], "reason_codes":list(candidate["reason_codes"]), "path_id":candidate["path_id"], "group_id":candidate["group_id"], "visibility":str(event.get("visibility", "unknown")), "redacted":True, "content":None, "side_effects":{"database":False,"schema":False,"queue":False,"bell":False,"email":False,"digest":False}}
