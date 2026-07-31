"""Process-local, test-only store for validated Community candidates."""
from __future__ import annotations
from copy import deepcopy
from typing import Any

REQUIRED = {"candidate_id","event_id","recipient_id","decision","reason_codes","path_id","group_id","channels","persistent"}
DECISIONS = {"eligible","blocked","ineligible"}

class InMemoryCandidateStore:
    def __init__(self) -> None:
        self._items: dict[str, dict[str, Any]] = {}
        self._order: list[str] = []

    @staticmethod
    def _validate(candidate: dict[str, Any]) -> None:
        if not isinstance(candidate, dict) or not REQUIRED <= candidate.keys():
            raise ValueError("malformed candidate")
        if candidate["decision"] not in DECISIONS or candidate["persistent"] is not False:
            raise ValueError("candidate is not a valid test candidate")
        if not isinstance(candidate["reason_codes"], (tuple, list)):
            raise ValueError("reason_codes must be ordered")
        if not isinstance(candidate["channels"], dict) or set(candidate["channels"]) != {"bell","email","digest","delivery"}:
            raise ValueError("candidate channels are incomplete")
        if not all(candidate["channels"][name] == "deferred" for name in candidate["channels"]):
            raise ValueError("test candidate channels must be deferred")
        if candidate["path_id"] == candidate["group_id"]:
            raise ValueError("path_id and group_id must remain distinct")

    def add(self, candidate: dict[str, Any]) -> None:
        self._validate(candidate)
        key = str(candidate["candidate_id"])
        if key in self._items:
            raise ValueError(f"duplicate candidate_id: {key}")
        self._items[key] = deepcopy(candidate)
        self._order.append(key)

    def get(self, candidate_id: str) -> dict[str, Any] | None:
        return deepcopy(self._items.get(str(candidate_id)))

    def contains(self, candidate_id: str) -> bool:
        return str(candidate_id) in self._items

    def list_for_recipient(self, recipient_id: str) -> list[dict[str, Any]]:
        return [deepcopy(self._items[key]) for key in self._order if self._items[key]["recipient_id"] == str(recipient_id)]

    def list_for_event(self, event_id: str) -> list[dict[str, Any]]:
        return [deepcopy(self._items[key]) for key in self._order if self._items[key]["event_id"] == str(event_id)]

    def count(self) -> int:
        return len(self._order)

    def clear(self) -> None:
        self._items.clear()
        self._order.clear()
