"""Disabled-by-default, test-owned shadow seam for Community post publication."""
from __future__ import annotations
from copy import deepcopy
from typing import Any, Callable
from group_post_event_adapter import GroupPostEventAdapter

class GroupPostPublicationShadowHook:
    def __init__(self, *, test_mode: bool = False, enabled: bool = False, recorder: Callable[[dict[str, Any]], None] | None = None) -> None:
        self._enabled = bool(test_mode and enabled)
        self._recorder = recorder

    def observe(self, source: dict[str, Any], recipient: dict[str, Any], policy: dict[str, Any]) -> dict[str, Any] | None:
        if not self._enabled:
            return None
        try:
            if source.get("publication_state") != "published":
                return None
            event = GroupPostEventAdapter.adapt(source, recipient, policy)
            if self._recorder is None:
                return None
            report = self._recorder(deepcopy(event))
            return deepcopy(report) if isinstance(report, dict) else None
        except Exception as error:  # shadow path must never affect publication
            if self._recorder is not None:
                try:
                    self._recorder({"shadow_error":type(error).__name__})
                except Exception:
                    pass
            return None
