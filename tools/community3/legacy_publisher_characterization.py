"""Pure, synthetic characterization model for observed legacy publisher behavior.

This is not a Perl port and performs no I/O, publication, database, or network work.
"""
from __future__ import annotations
from datetime import datetime
from typing import Any

UNKNOWN = "UNKNOWN — EVIDENCE REQUIRED"

def characterize(fixture: dict[str, Any]) -> dict[str, Any]:
    required = ("board", "title", "body", "local_path", "path_id", "group_id")
    missing = [key for key in required if fixture.get(key) in (None, "")]
    if missing:
        return {"outcome": "rejected", "reason_code": "required_field_missing", "missing": missing}
    if fixture.get("spam") or fixture.get("profanity"):
        return {"outcome": "rejected", "reason_code": "abuse_gate_rejected"}
    if fixture.get("mapping_evidence") is not True:
        return {"outcome": UNKNOWN, "reason_code": "canonical_group_mapping_unverified"}
    if fixture.get("duplicate"):
        return {"outcome": "idempotency_classification", "reason_code": "duplicate_behavior_unverified"}
    if fixture.get("partial_write"):
        return {"outcome": "inconsistent_state", "reason_code": "file_database_divergence"}
    timestamp = fixture.get("timestamp", "2014-01-14T12:34:56")
    dt = datetime.fromisoformat(timestamp)
    post_type = "reply" if fixture.get("parent_id") else "post"
    post_name = dt.strftime("%m.%d.%Y.%H.%M.%S.html")
    url = f"{fixture['board'].rstrip('/')}/topic{fixture.get('topic', 1)}/{post_name}"
    return {
        "outcome": "accepted", "post_type": post_type,
        "parent_id": fixture.get("parent_id"), "thread_id": fixture.get("thread_id") or fixture.get("topic", 1),
        "url_pattern": url, "timestamp_format": "%m.%d.%Y.%H.%M.%S.html",
        "chat_posts_fields": ["post_id", "post_url", "post_type", "post_datetime", "chatboard_url", "post_title", "post_author", "wordpress_id", "status"],
        "local_path": fixture["local_path"], "path_id": fixture["path_id"], "group_id": fixture["group_id"],
        "mapping_required": True,
        "archive_reference": {"url": url, "immutable": True},
    }

def unsupported_behavior(name: str) -> dict[str, str]:
    return {"behavior": name, "outcome": UNKNOWN}
