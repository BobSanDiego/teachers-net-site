"""Test-only in-memory bell repository; no persistence or delivery."""
from __future__ import annotations
from copy import deepcopy
from typing import Any
from notification_candidate_store import InMemoryCandidateStore

class InMemoryBellRepository:
    def __init__(self) -> None:
        self._items: dict[str, dict[str, Any]] = {}
        self._order: list[str] = []

    def create_bell(self, candidate: dict[str, Any]) -> dict[str, Any]:
        InMemoryCandidateStore._validate(candidate)
        if candidate["decision"] != "eligible":
            raise ValueError("only eligible candidates can create a bell")
        bell_id = f"bell:{candidate['candidate_id']}"
        if bell_id in self._items:
            raise ValueError(f"duplicate bell_id: {bell_id}")
        bell = {"bell_id": bell_id, "candidate_id": candidate["candidate_id"], "event_id": candidate["event_id"], "recipient_id": candidate["recipient_id"], "path_id": candidate["path_id"], "group_id": candidate["group_id"], "state": "unread", "delivery": "deferred", "engagement": "unmeasured", "persistent": False}
        self._items[bell_id] = deepcopy(bell)
        self._order.append(bell_id)
        return deepcopy(bell)

    def get(self, bell_id: str) -> dict[str, Any] | None:
        return deepcopy(self._items.get(str(bell_id)))

    def list_unread(self, recipient: str) -> list[dict[str, Any]]:
        return [deepcopy(self._items[key]) for key in self._order if self._items[key]["recipient_id"] == str(recipient) and self._items[key]["state"] == "unread"]

    def mark_read(self, bell_id: str) -> dict[str, Any]:
        return self._set_state(bell_id, "read")

    def mark_unread(self, bell_id: str) -> dict[str, Any]:
        return self._set_state(bell_id, "unread")

    def archive(self, bell_id: str) -> dict[str, Any]:
        return self._set_state(bell_id, "archived")

    def _set_state(self, bell_id: str, state: str) -> dict[str, Any]:
        key = str(bell_id)
        if key not in self._items:
            raise KeyError(key)
        self._items[key]["state"] = state
        return deepcopy(self._items[key])

    def count_unread(self, recipient: str) -> int:
        return len(self.list_unread(recipient))

    def clear(self) -> None:
        self._items.clear()
        self._order.clear()
