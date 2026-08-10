#!/usr/bin/env python3
"""Lifecycle coverage for the project hopper helper."""
from __future__ import annotations

import json
import sys
import tempfile
import unittest
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

import clean_cycle


class CleanCycleLifecycleTest(unittest.TestCase):
    def setUp(self) -> None:
        self.tmp = tempfile.TemporaryDirectory()
        self.root = Path(self.tmp.name)
        self.original_root = clean_cycle.ROOT
        self.original_hopper = clean_cycle.HOPPER
        clean_cycle.ROOT = self.root
        clean_cycle.HOPPER = self.root / "tmp" / "hopper"
        self.sources = self.root / "sources"
        self.sources.mkdir()

    def tearDown(self) -> None:
        clean_cycle.ROOT = self.original_root
        clean_cycle.HOPPER = self.original_hopper
        self.tmp.cleanup()

    def source_file(self, name: str, text: str) -> Path:
        path = self.sources / name
        path.write_text(text)
        return path

    def begin_collect_finalize_validate(
        self,
        cycle: str,
        status: str,
        commit: str | None,
        push: str | None,
        git_disposition: str | None,
        excluded_artifacts: list[dict] | None = None,
    ) -> dict:
        clean_cycle.begin("jobcenter", cycle)
        ticket = clean_cycle.collect(
            "jobcenter", cycle, self.source_file(f"ticket-{cycle}.txt", "ticket"), "created"
        )
        evidence = clean_cycle.collect(
            "jobcenter", cycle, self.source_file(f"evidence-{cycle}.txt", "evidence"), "created"
        )
        report_source = self.source_file(f"report-{cycle}.txt", "status first report")
        clean_cycle.write_records(
            "jobcenter",
            f"TEST-{cycle}",
            cycle,
            "main",
            status,
            commit,
            push,
            [ticket, evidence],
            git_disposition=git_disposition,
            excluded_artifacts=excluded_artifacts,
            report_source=report_source,
        )
        clean_cycle.validate("jobcenter", cycle)
        record = clean_cycle.HOPPER / "jobcenter" / "Hopper (Job Center)" / f"cycle-jobcenter-{cycle}.json"
        return json.loads(record.read_text())

    def test_normal_committed_pushed_cycle_validates(self) -> None:
        payload = self.begin_collect_finalize_validate(
            "260101010101", "complete", "abc1234", "pushed", "COMMITTED_PUSHED"
        )
        self.assertEqual(payload["git_disposition"], "COMMITTED_PUSHED")
        self.assertEqual(payload["commit"], "abc1234")
        report_dir = clean_cycle.HOPPER / "jobcenter" / "Report (Job Center)"
        self.assertTrue((report_dir / "output-jobcenter-260101010101.txt").is_file())

    def test_completed_no_commit_cycle_validates_without_fake_commit(self) -> None:
        payload = self.begin_collect_finalize_validate(
            "260101010102", "complete", None, None, "NOT_APPLICABLE"
        )
        self.assertEqual(payload["git_disposition"], "NOT_APPLICABLE")
        self.assertIsNone(payload["commit"])
        self.assertIsNone(payload["push"])
        manifest = (
            clean_cycle.HOPPER
            / "jobcenter"
            / "Hopper (Job Center)"
            / "MANIFEST-jobcenter-260101010102.txt"
        ).read_text()
        self.assertIn("commit=null", manifest)
        self.assertIn("push=null", manifest)

    def test_local_only_evidence_can_be_recorded_without_transport(self) -> None:
        local_only = self.source_file("local-sensitive.txt", "do not package")
        payload = self.begin_collect_finalize_validate(
            "260101010103",
            "complete",
            None,
            None,
            "NOT_APPLICABLE",
            excluded_artifacts=[
                {
                    "path": str(local_only),
                    "disposition": "SENSITIVE / DO NOT PACKAGE",
                    "reason": "fixture proves excluded evidence recording",
                }
            ],
        )
        hopper = clean_cycle.HOPPER / "jobcenter" / "Hopper (Job Center)"
        self.assertFalse((hopper / "local-sensitive-jobcenter-260101010103.txt").exists())
        self.assertEqual(payload["excluded_artifacts"][0]["disposition"], "SENSITIVE / DO NOT PACKAGE")

    def test_blocked_cycle_validates_as_blocked_not_successful_no_commit(self) -> None:
        payload = self.begin_collect_finalize_validate(
            "260101010104", "blocked", None, None, "BLOCKED"
        )
        self.assertEqual(payload["status"], "blocked")
        self.assertEqual(payload["git_disposition"], "BLOCKED")

    def test_collect_records_external_source_path_without_crashing(self) -> None:
        cycle = "260101010105"
        clean_cycle.begin("jobcenter", cycle)
        with tempfile.TemporaryDirectory() as external_tmp:
            external_ticket = Path(external_tmp) / "pasted-text.txt"
            external_ticket.write_text("external ticket")
            artifact = clean_cycle.collect("jobcenter", cycle, external_ticket, "source-ticket")
            self.assertEqual(artifact["original_path"], str(external_ticket))
            copied = (
                clean_cycle.HOPPER
                / "jobcenter"
                / "Hopper (Job Center)"
                / f"pasted-text-jobcenter-{cycle}.txt"
            )
            self.assertTrue(copied.is_file())


if __name__ == "__main__":
    unittest.main()
