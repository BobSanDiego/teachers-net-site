"""End-to-end, non-delivering Community notification dry-run pipeline."""
from __future__ import annotations
from copy import deepcopy
from typing import Any
from notification_candidate_boundary import build_candidate
from notification_candidate_store import InMemoryCandidateStore
from notification_bell_repository import InMemoryBellRepository

class DryRunNotificationPipeline:
    def __init__(self) -> None:
        self.candidates = InMemoryCandidateStore()
        self.bells = InMemoryBellRepository()
        self._reports: dict[str, dict[str, Any]] = {}

    @staticmethod
    def evaluate(event: dict[str, Any]) -> dict[str, Any]:
        decision = str(event.get("decision", "ineligible"))
        reasons = tuple(event.get("reason_codes", ()))
        return {"decision":decision,"reasons":reasons,"recipient_id":event["recipient_id"],"event":{"event_id":event["event_id"],"visibility":event.get("visibility","public")},"mapping":{"path_id":event["path_id"],"group_id":event["group_id"]}}

    def run(self, event: dict[str, Any]) -> dict[str, Any]:
        event_id = str(event["event_id"])
        if event_id in self._reports:
            return deepcopy(self._reports[event_id])
        evaluated = self.evaluate(event)
        candidate = None
        bell = None
        if evaluated["decision"] in {"eligible", "blocked", "ineligible"}:
            if evaluated["decision"] == "eligible":
                candidate = build_candidate(evaluated)
                self.candidates.add(candidate)
                if event.get("bell_enabled", True):
                    bell = self.bells.create_bell(candidate)
        else:
            raise ValueError("unsupported evaluator decision")
        report = {"event_id":event_id,"recipient_id":str(event["recipient_id"]),"path_id":int(event["path_id"]),"group_id":int(event["group_id"]),"eligibility_decision":evaluated["decision"],"reason_codes":list(evaluated["reasons"]),"candidate_id":candidate["candidate_id"] if candidate else None,"bell_id":bell["bell_id"] if bell else None,"bell_state":bell["state"] if bell else None,"channels":{"bell":"eligible" if bell else "not-created","email":"suppressed" if event.get("email_paused") else "deferred","digest":"deferred","delivery":"deferred"},"side_effects":{"database":False,"schema":False,"queue":False,"email":False,"digest":False,"production":False,"ui":False}}
        self._reports[event_id] = deepcopy(report)
        return deepcopy(report)
