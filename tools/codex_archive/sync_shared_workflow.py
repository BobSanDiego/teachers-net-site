#!/usr/bin/env python3
"""Project canonical shared handoff authorities into the Windows library."""
from pathlib import Path
import shutil

ROOT = Path(__file__).resolve().parents[2]
TARGET = Path("/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW")
SOURCES = {
    "START-CODEX.md": "docs/process/conversation-handoff/shared/START-CODEX.md",
    "PROJECT-BOOTSTRAP-SPEC.md": "docs/process/conversation-handoff/shared/PROJECT-BOOTSTRAP-SPEC.md",
    "CHATGPT-ENGINEERING-OPERATING-CONTRACT.txt": "docs/process/conversation-handoff/shared/chatgpt-engineering-operating-contract.md",
    "HANDOFF-LIFECYCLE.md": "docs/process/conversation-handoff/shared/HANDOFF-LIFECYCLE.md",
    "PROJECT-RECORD-SPEC.md": "docs/process/conversation-handoff/shared/PROJECT-RECORD-SPEC.md",
    "TRANSCRIPT-ARCHIVE-SPEC.md": "docs/process/conversation-handoff/shared/TRANSCRIPT-ARCHIVE-SPEC.md",
    "REPORT-HOPPER-SPEC.md": "docs/process/conversation-handoff/shared/REPORT-HOPPER-SPEC.md",
}

def main() -> int:
    TARGET.mkdir(parents=True, exist_ok=True)
    for name, source in SOURCES.items():
        src = ROOT / source
        text = f"CANONICAL SOURCE: {source}\n\n" + src.read_text(encoding="utf-8")
        (TARGET / name).write_text(text, encoding="utf-8")
    print(f"projected {len(SOURCES)} canonical authorities to {TARGET}")
    return 0

if __name__ == "__main__":
    raise SystemExit(main())
