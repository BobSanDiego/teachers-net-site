#!/usr/bin/env python3
"""Build the Job Center one-drop handoff directory."""
from __future__ import annotations

import hashlib
import json
import shutil
import subprocess
import zipfile
from datetime import datetime, timezone
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
GUIDANCE = [
    ("docs/process/conversation-handoff/shared/START-CODEX.md", "shared Codex bootstrap front door"),
    ("docs/process/conversation-handoff/shared/chatgpt-engineering-operating-contract.md", "shared ChatGPT engineering operating contract"),
    ("docs/process/conversation-handoff/shared/HANDOFF-LIFECYCLE.md", "shared handoff lifecycle"),
    ("docs/process/conversation-handoff/shared/PROJECT-RECORD-SPEC.md", "shared project record specification"),
    ("docs/process/conversation-handoff/shared/TRANSCRIPT-ARCHIVE-SPEC.md", "shared transcript archive specification"),
    ("docs/process/conversation-handoff/shared/REPORT-HOPPER-SPEC.md", "durable handoff Report/Hopper specification"),
    ("docs/process/conversation-handoff/projects/jobcenter.json", "Job Center project sub-record"),
    ("docs/process/chatgpt-codex-workflow-protocol-v1.md", "shared workflow authority"),
    ("docs/documentation-governance.md", "documentation governance"),
    ("docs/decision-log.md", "shared decisions"),
    ("docs/design-system-v1.md", "shared design authority"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/development-constitution.md", "Jobs engineering constitution"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/jc053-authority-manifest-v1.json", "JC053 authority manifest"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/project-cursor.md", "current Job Center state"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/engineering-handoff.md", "current engineering handoff"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/jobs-roadmap.md", "Job Center roadmap"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/jc053-wizard-product-contract-v1.md", "JC053 field/default/gate authority"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/jc053-wizard-design-system-v1.md", "JC053 wizard visual authority"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/job-center-design-system-v1.md", "Job Center design authority"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/job-center-responsive-decisions-v1.md", "responsive decisions"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/employer-ux-v1.md", "Employer UX authority"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/job-posting-wizard-field-contract-v1.md", "wizard field contract"),
    ("wordpress/wp-content/plugins/tnet-jobs/docs/job-center/design/manifest.md", "visual manifest"),
]


def sha(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as f:
        for block in iter(lambda: f.read(1024 * 1024), b""):
            h.update(block)
    return h.hexdigest()


def main() -> int:
    out = ROOT / "docs/process/conversation-handoff/jobcenter/current-handoff"
    if out.exists():
        shutil.rmtree(out)
    out.mkdir(parents=True)
    shutil.copy2(ROOT / "docs/process/conversation-handoff/jobcenter/chatgpt-complete-current-record.md", out / "03-chatgpt-complete-current-record.md")
    shutil.copy2(ROOT / "docs/process/conversation-handoff/jobcenter/codex-complete-current-record.md", out / "04-codex-current-handoff-record.md")
    shutil.copy2(ROOT / "docs/process/conversation-handoff/jobcenter/conversation-master-manifest.json", out / "conversation-master-manifest.json")
    generated = datetime.now(timezone.utc).replace(microsecond=0).isoformat()
    guide_dir = out / "guidance"
    guide_dir.mkdir()
    entries = []
    for source, role in GUIDANCE:
        src = ROOT / source
        if not src.is_file():
            raise RuntimeError(f"missing guidance source: {source}")
        dest = guide_dir / Path(source).name
        shutil.copy2(src, dest)
        entries.append({"filename": dest.name, "original_path": source, "authority_role": role, "sha256": sha(dest), "bytes": dest.stat().st_size, "status": "CURRENT_SOURCE"})
    index = "# GUIDANCE INDEX\n\nConversation transcripts are intentionally excluded from this ZIP; they are supplied separately as the two self-contained conversation masters.\n\n" + "\n".join(json.dumps(e, sort_keys=True) for e in entries) + "\n"
    (guide_dir / "00-GUIDANCE-INDEX.txt").write_text(index, encoding="utf-8")
    zip_path = out / "02-authoritative-guidance.zip"
    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as z:
        for member in sorted(guide_dir.iterdir()):
            z.write(member, member.name)
    start = out / "00-START-HERE.txt"
    shutil.copy2(ROOT / "docs/process/conversation-handoff/shared/chatgpt-engineering-operating-contract.md", out / "01-CHATGPT-ENGINEERING-OPERATING-CONTRACT.txt")
    start.write_text(f"""JOB CENTER ONE-DROP HANDOFF\nGenerated: {generated}\n\nYou are receiving one portable Job Center handoff directory. It contains:\n1. 01-CHATGPT-ENGINEERING-OPERATING-CONTRACT.txt — shared engineering operating authority.\n2. 02-authoritative-guidance.zip — current project authority.\n3. 03-chatgpt-complete-current-record.md — conversation evidence.\n4. 04-codex-current-handoff-record.md — compact current Codex evidence.\n5. handoff-manifest.json — provenance and integrity manifest.\n\nREAD AND ADOPT THE SHARED CHATGPT ENGINEERING OPERATING CONTRACT FIRST.\n\nCONVERSATION EVIDENCE IS NOT AUTOMATIC PROJECT AUTHORITY. Transcripts may contain brainstorming, rejected or superseded decisions, mistakes, and stale implementation state. Resolve current truth through the authority package.\n\nAuthority order:\n1. shared operating contract;\n2. authority manifest/canonical project authority;\n3. product and behavioral contracts;\n4. governance and workflow authority;\n5. Project Cursor and Engineering Handoff;\n6. roadmap/plan;\n7. accepted implementation and verification evidence;\n8. conversation records only for unresolved history/context.\n\nStartup procedure:\n1. read 01-CHATGPT-ENGINEERING-OPERATING-CONTRACT.txt;\n2. inspect handoff-manifest.json and the Job Center project record;\n3. inspect 02-authoritative-guidance.zip and its index;\n4. resolve current authority and operational state;\n5. consult conversation records only as evidence where needed. Do not read all transcript history before current authority.\n\nRespond concisely with: project identified; current phase/workstream; last accepted completed objective; current gate/blocker; preferred next bounded objective; material authority contradiction; and whether this package is sufficient to continue without requesting more context. Then stop for Engineering Director instruction.\n""", encoding="utf-8")
    components = []
    for p in sorted(out.iterdir()):
        if p.is_file() and p.name != "handoff-manifest.json":
            components.append({"filename": p.name, "bytes": p.stat().st_size, "sha256": sha(p), "classification": "REPORT_REQUIRED", "purpose": "one-drop handoff terminal component", "external_dependency": False, "publication_status": "SAFE_HANDOFF_ARTIFACT"})
    branch = subprocess.check_output(["git", "branch", "--show-current"], cwd=ROOT, text=True).strip()
    head = subprocess.check_output(["git", "rev-parse", "HEAD"], cwd=ROOT, text=True).strip()
    manifest = {"schema_version": "1.0", "project": "jobcenter", "project_record": "docs/process/conversation-handoff/projects/jobcenter.json", "generated_at": generated, "source_branch": branch, "root_repository_head": head, "chatgpt_conversation_id": "6a79d7ea-da44-83e8-91eb-460b99ab593b", "codex_active_session_id": "019fbfe6-e2d0-73e3-98a1-d10b384cdf7d", "authority_warning": "Conversation records are evidence; current project authority is resolved from the shared contract, project record, guidance ZIP, and repository authority hierarchy.", "components": components, "guidance_member_count": len(entries) + 1, "guidance_index_sha256": sha(guide_dir / "00-GUIDANCE-INDEX.txt"), "known_limitations": ["Live ChatGPT continuation is bounded and explicitly disclosed in the self-contained master."], "raw_codex_jsonl_included": False, "full_codex_fossil_packaged": False}
    (out / "handoff-manifest.json").write_text(json.dumps(manifest, indent=2, sort_keys=True) + "\n", encoding="utf-8")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
