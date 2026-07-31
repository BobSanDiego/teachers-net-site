"""Community-owned, test-only public entry point for notification dry runs."""
from __future__ import annotations
from copy import deepcopy
from typing import Any
from notification_dry_run_pipeline import DryRunNotificationPipeline

REQUIRED = {"event_id", "recipient_id", "path_id", "group_id", "event_family", "decision", "reason_codes"}

class NotificationApplicationService:
    def __init__(self) -> None:
        self._pipeline = DryRunNotificationPipeline()

    @staticmethod
    def _validate(event: dict[str, Any]) -> None:
        if not isinstance(event, dict) or not REQUIRED <= event.keys():
            raise ValueError("malformed Community event")
        if event["event_family"] != "group_post":
            raise ValueError("unsupported Community event family")
        if not str(event["event_id"]) or not str(event["recipient_id"]):
            raise ValueError("event identity is required")
        if int(event["path_id"]) == int(event["group_id"]):
            raise ValueError("path_id and group_id must remain distinct")
        if not isinstance(event["reason_codes"], (tuple, list)):
            raise ValueError("reason_codes must be ordered")

    def notify(self, event: dict[str, Any]) -> dict[str, Any]:
        self._validate(event)
        return deepcopy(self._pipeline.run(deepcopy(event)))
