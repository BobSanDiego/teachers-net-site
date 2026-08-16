#!/usr/bin/env python3
"""Focused acceptance tests for Workflow V2 portable ChatGPT handoff."""
from __future__ import annotations

import hashlib
import json
import sys
import tempfile
import unittest
import zipfile
from datetime import datetime, timezone
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from prepare_chatgpt_handoff import HandoffError, prepare


PROJECTS = {
    "jobcenter": ("Job Center", r"Job Center", "Job-Center"),
    "views": ("Teachers.Net Durable Views", r"Views", "Views"),
    "community": ("Teachers.Net Community", r"Community", "Community"),
    "profile": ("Teachers.Net Profile", r"Profile", "Profile"),
}


def transcript(title: str, messages: list[tuple[str, str]], exported: str = "8/11/2026, 1:00:00 PM") -> str:
    body = [f"# Teachers.Net (TNET) - {title}", "", f"**Exported:** {exported}  ", f"**Messages:** {len(messages)}  ", "", "---"]
    for role, value in messages:
        marker = "🙍🏻‍♂️ You" if role == "user" else "🤖 ChatGPT"
        body.extend(["", f"**{marker}:**", "", value, "", "---"])
    return "\n".join(body) + "\n"


def sha(path: Path) -> str:
    return hashlib.sha256(path.read_bytes()).hexdigest()


class PortableHandoffV2Tests(unittest.TestCase):
    def setUp(self) -> None:
        self.temp = tempfile.TemporaryDirectory()
        self.root = Path(self.temp.name) / "repo"
        self.root.mkdir()
        self.records = self.root / "docs/process/conversation-handoff/projects"
        self.records.mkdir(parents=True)
        authority = self.root / "docs/shared-authority.md"
        authority.parent.mkdir(parents=True, exist_ok=True)
        authority.write_text("# Workflow V2 authority\n", encoding="utf-8")
        for project, (name, pattern, prefix) in PROJECTS.items():
            base = self.root / f"docs/process/conversation-handoff/{project}"
            base.mkdir(parents=True)
            (base / "chatgpt.md").write_text(f"# {name} historical portable master\n", encoding="utf-8")
            (base / "codex.md").write_text(f"# {name} Codex baseline\n", encoding="utf-8")
            report = self.root / f"tmp/hopper/{project}/Report ({name.split()[-1] if project != 'jobcenter' else 'Job Center'})"
            report.mkdir(parents=True)
            (report / "latest-report.txt").write_text(f"OUTCOME\n{project} current state\n", encoding="utf-8")
            (report / f"cycle-{project}-260811200000.json").write_text(
                json.dumps({"ticket": f"{project.upper()}-CURRENT", "status": "complete", "cycle_id": "260811200000"}),
                encoding="utf-8",
            )
            (report.parent / "workflow-ledger.json").write_text(
                json.dumps({"tickets": [{"ticket": "STALE-LEDGER", "status": "completed"}]}),
                encoding="utf-8",
            )
            record = {
                "project_id": project,
                "display_name": name,
                "state": "REGISTERED / LIFECYCLE READY",
                "report_label": "Job Center" if project == "jobcenter" else name.split()[-1],
                "root_repository": str(self.root),
                "report_hopper": f"tmp/hopper/{project}",
                "conversation": {"master": f"docs/process/conversation-handoff/{project}/chatgpt.md"},
                "codex": {"portable_record": f"docs/process/conversation-handoff/{project}/codex.md"},
                "handoff": {"checkpoint_prefix": prefix, "handoffs": str(Path(self.temp.name) / "handoffs")},
                "handoff_v2": {
                    "chatgpt_title_patterns": [pattern],
                    "conversation_manifest": f"docs/process/conversation-handoff/{project}/manifest-v2.json",
                },
                "guidance_sources": [{"path": "docs/shared-authority.md", "role": "canonical Workflow V2"}],
            }
            (self.records / f"{project}.json").write_text(json.dumps(record), encoding="utf-8")

    def tearDown(self) -> None:
        self.temp.cleanup()

    def _run(self, project: str, source: Path, *, second: int, **kwargs):
        return prepare(
            root=self.root,
            project_record=self.records / f"{project}.json",
            transcript=source,
            output_root=Path(self.temp.name) / f"packages-{second}",
            now=datetime(2026, 8, 11, 20, 0, second, tzinfo=timezone.utc),
            **kwargs,
        )

    def test_registered_projects_share_one_command_owner(self) -> None:
        for index, (project, (_, _, _)) in enumerate(PROJECTS.items()):
            title = {"jobcenter": "Job Center (8/11/26)", "views": "Views (8/10/26)", "community": "Community (8/3/26)", "profile": "Profile (8/10/26)"}[project]
            source = Path(self.temp.name) / f"{project}.md"
            source.write_text(transcript(title, [("user", f"current {project}"), ("assistant", "ready")]), encoding="utf-8")
            result = self._run(project, source, second=index)
            package = Path(result["package_directory"])
            self.assertEqual(result["status"], "HANDOFF READY")
            self.assertTrue((package / "00-LOAD-STARTUP.md").is_file())
            self.assertTrue(Path(result["package_zip_candidate"]).is_file())
            drop = Path(result["operator_drop"]["directory"])
            self.assertEqual(sorted(path.name for path in drop.iterdir()), sorted(["STARTUP-TICKET.txt", Path(result["package_zip_candidate"]).name]))
            self.assertEqual(sha(drop / Path(result["package_zip_candidate"]).name), result["operator_drop"]["zip_sha256"])
            ticket = (drop / "STARTUP-TICKET.txt").read_text()
            self.assertIn(Path(result["package_zip_candidate"]).name, ticket)
            self.assertIn(result["operator_drop"]["zip_sha256"], ticket)
            with zipfile.ZipFile(drop / Path(result["package_zip_candidate"]).name) as archive:
                self.assertIn("99-PACKAGE-MANIFEST.json", archive.namelist())
            self.assertEqual(json.loads((package / "99-PACKAGE-MANIFEST.json").read_text())["project"]["id"], project)
            terminal = json.loads((package / "03-terminal/terminal-state.json").read_text())
            self.assertEqual(terminal["objective"]["ticket"], f"{project.upper()}-CURRENT")
            self.assertEqual(terminal["objective"]["source"], "CURRENT VALIDATED REPORT/HOPPER CYCLE")

    def test_project_mismatch_fails_before_master_mutation(self) -> None:
        source = Path(self.temp.name) / "views.md"
        source.write_text(transcript("Views (8/10/26)", [("user", "wrong project")]), encoding="utf-8")
        master = self.root / "docs/process/conversation-handoff/jobcenter/chatgpt.md"
        before = sha(master)
        with self.assertRaisesRegex(HandoffError, "identity mismatch"):
            self._run("jobcenter", source, second=10)
        self.assertEqual(before, sha(master))
        self.assertFalse((self.root / "docs/process/conversation-handoff/jobcenter/manifest-v2.json").exists())

    def test_unchanged_is_idempotent_and_later_open_snapshot_is_incremental(self) -> None:
        source = Path(self.temp.name) / "jobcenter.md"
        initial = [("user", "first stable message with enough unique content for portable history"), ("assistant", "first response")]
        source.write_text(transcript("Job Center (8/11/26)", initial), encoding="utf-8")
        first = self._run("jobcenter", source, second=20)
        second = self._run("jobcenter", source, second=21)
        self.assertEqual(first["chatgpt"]["messages_added"], 2)
        self.assertTrue(second["chatgpt"]["source_unchanged"])
        self.assertEqual(second["chatgpt"]["messages_added"], 0)
        source.write_text(transcript("Job Center (8/11/26)", initial + [("user", "later open-session message")], "8/11/2026, 1:10:00 PM"), encoding="utf-8")
        third = self._run("jobcenter", source, second=22)
        self.assertEqual(third["chatgpt"]["messages_added"], 1)
        master = (self.root / "docs/process/conversation-handoff/jobcenter/chatgpt.md").read_text()
        self.assertEqual(master.count("later open-session message"), 1)

    def test_changed_historical_message_fails_closed(self) -> None:
        source = Path(self.temp.name) / "profile.md"
        source.write_text(transcript("Profile (8/10/26)", [("user", "original")]), encoding="utf-8")
        self._run("profile", source, second=30)
        source.write_text(transcript("Profile (8/10/26)", [("user", "changed in place")]), encoding="utf-8")
        with self.assertRaisesRegex(HandoffError, "historical ChatGPT source"):
            self._run("profile", source, second=31)

    def test_regressed_or_truncated_snapshot_fails_closed(self) -> None:
        source = Path(self.temp.name) / "views.md"
        source.write_text(transcript("Views (8/10/26)", [("user", "one"), ("assistant", "two")]), encoding="utf-8")
        self._run("views", source, second=35)
        source.write_text(transcript("Views (8/10/26)", [("user", "one")]), encoding="utf-8")
        with self.assertRaisesRegex(HandoffError, "regresses"):
            self._run("views", source, second=36)
        malformed = transcript("Views (8/12/26)", [("user", "one")]).replace("**Messages:** 1", "**Messages:** 2")
        source.write_text(malformed, encoding="utf-8")
        with self.assertRaisesRegex(HandoffError, "message count mismatch"):
            self._run("views", source, second=37)

    def test_payload_is_self_contained_and_house_context_is_explicit(self) -> None:
        source = Path(self.temp.name) / "views.md"
        source.write_text(transcript("Views (8/10/26)", [("user", "state")]), encoding="utf-8")
        result = self._run("views", source, second=40, include_house_context=True)
        package = Path(result["package_directory"])
        startup = (package / "00-LOAD-STARTUP.md").read_text()
        self.assertNotIn("/home/", startup)
        self.assertNotIn("C:\\", startup)
        classification = json.loads((package / "08-context/classification.json").read_text())
        self.assertEqual(classification["classification"], "CONTEXTUAL EVIDENCE / NOT TARGET AUTHORITY")

    def test_direct_codex_source_is_safely_rendered(self) -> None:
        source = Path(self.temp.name) / "community.md"
        source.write_text(transcript("Community (8/3/26)", [("user", "state")]), encoding="utf-8")
        codex = Path(self.temp.name) / "codex.jsonl"
        records = [
            {"type": "session_meta", "payload": {"id": "019ftest0-aaaa-bbbb-cccc-ddddeeeeeeee", "cwd": str(self.root), "title": "Community"}},
            {"type": "response_item", "payload": {"type": "message", "role": "user", "content": [{"type": "input_text", "text": "visible Codex state"}]}, "timestamp": "2026-08-11T20:00:00Z"},
            {"type": "response_item", "payload": {"type": "message", "role": "assistant", "content": [{"type": "output_text", "text": "visible result"}]}, "timestamp": "2026-08-11T20:00:01Z"},
        ]
        codex.write_text("\n".join(json.dumps(item) for item in records) + "\n", encoding="utf-8")
        result = self._run("community", source, second=50, codex_source=codex)
        self.assertTrue(result["codex"]["updated"])
        self.assertIn("visible Codex state", (Path(result["package_directory"]) / "06-codex-portable-master.md").read_text())
        preserved = self._run("community", source, second=51)
        self.assertEqual(
            preserved["codex"]["status"],
            "LATEST INCORPORATED CODEX SNAPSHOT / NEWER ACTIVE STATE UNPROVEN",
        )

    def test_shared_workflow_does_not_create_an_independent_chatgpt_project(self) -> None:
        source = Path(self.temp.name) / "shared.md"
        source.write_text(transcript("Job Center (8/11/26)", [("user", "house state")]), encoding="utf-8")
        record = {
            "project_id": "shared-workflow",
            "display_name": "Teachers.Net Shared Workflow",
            "report_label": "Shared Workflow",
        }
        path = self.records / "shared-workflow.json"
        path.write_text(json.dumps(record), encoding="utf-8")
        with self.assertRaisesRegex(HandoffError, "no independent ChatGPT project"):
            prepare(
                root=self.root,
                project_record=path,
                transcript=source,
                output_root=Path(self.temp.name) / "packages-shared",
            )

    def test_non_jobcenter_project_inherits_shared_successor_contract(self) -> None:
        repo = Path(__file__).resolve().parents[2]
        record = json.loads((repo / "docs/process/conversation-handoff/projects/views.json").read_text())
        guidance = "\n".join(
            (repo / entry["path"]).read_text(encoding="utf-8")
            for entry in record["guidance_sources"]
            if entry["path"].startswith("docs/process/conversation-handoff/shared/")
        )
        self.assertIn("PREPARE HANDOFF", guidance)
        self.assertIn("OpenAI ChatGPT share URL", guidance)
        self.assertIn("STARTUP-TICKET.txt", guidance)
        self.assertIn("validated ZIP", guidance)
        self.assertIn("HANDOFFS", guidance)
        self.assertIn("local", guidance.lower())


if __name__ == "__main__":
    unittest.main()
