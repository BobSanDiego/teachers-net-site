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
        shared = self.root / "docs" / "process" / "conversation-handoff" / "shared"
        projects = self.root / "docs" / "process" / "conversation-handoff" / "projects"
        shared.mkdir(parents=True)
        projects.mkdir(parents=True)
        (shared / "workflow-v2.json").write_text(json.dumps({
            "workflow_version": "V2",
            "workflow_id": "teachers-net-engineering-workflow",
        }))
        project_records = {
            "jobcenter": "Job Center",
            "shared-workflow": "Shared Workflow",
            "profile": "Profile",
            "community": "Community",
        }
        for project, label in project_records.items():
            (projects / f"{project}.json").write_text(json.dumps({
                "project_id": project,
                "display_name": label,
                "state": "REGISTERED / LIFECYCLE READY",
                "report_label": label,
                "root_repository": str(self.root),
                "report_hopper": f"tmp/hopper/{project}",
            }))
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

    def ticket_file(self, ticket_id: str, *, mode: str = "STANDARD", owner: str = "jobcenter") -> tuple[Path, dict]:
        path = self.source_file(
            f"ticket-{ticket_id}.txt",
            f"""TICKET READY FOR CODEX
{ticket_id} — Test fixture

MODE: {mode}
OWNER: {owner}

OUTCOME

Prove the clean-cycle fixture.

STOP BOUNDARY

Stop after validation.

END TICKET — {ticket_id}
""",
        )
        preflight = clean_cycle.validate_ticket_payload(path.read_text())
        preflight.update({
            "source_path": str(path.resolve()),
            "source_bytes": path.stat().st_size,
            "source_sha256": clean_cycle.sha256(path),
            "title": f"{ticket_id} — Test fixture",
        })
        return path, preflight

    def begin_collect_finalize_validate(
        self,
        cycle: str,
        status: str,
        commit: str | None,
        push: str | None,
        git_disposition: str | None,
        excluded_artifacts: list[dict] | None = None,
    ) -> dict:
        ticket_id = f"TEST-{cycle}"
        ticket_source, preflight = self.ticket_file(ticket_id)
        clean_cycle.begin("jobcenter", cycle, ticket_source)
        ticket = clean_cycle.collect(
            "jobcenter", cycle, ticket_source, "created"
        )
        evidence = clean_cycle.collect(
            "jobcenter", cycle, self.source_file(f"evidence-{cycle}.txt", "evidence"), "created"
        )
        report_source = self.source_file(f"report-{cycle}.txt", "status first report")
        clean_cycle.write_records(
            "jobcenter",
            ticket_id,
            cycle,
            "main",
            status,
            commit,
            push,
            [ticket, evidence],
            git_disposition=git_disposition,
            excluded_artifacts=excluded_artifacts,
            report_source=report_source,
            ticket_preflight=preflight,
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

    def test_mismatched_ticket_identity_cannot_finalize(self) -> None:
        ticket_source, preflight = self.ticket_file("TEST-IDENTITY-A")
        clean_cycle.begin("jobcenter", "260101010199", ticket_source)
        source_ticket = clean_cycle.collect("jobcenter", "260101010199", ticket_source, "source")
        with self.assertRaisesRegex(RuntimeError, "preflight identity"):
            clean_cycle.write_records(
                "jobcenter", "TEST-IDENTITY-B", "260101010199", "main", "complete",
                None, None, [source_ticket], git_disposition="NOT_APPLICABLE",
                report_source=self.source_file("identity-report.txt", "status"),
                ticket_preflight=preflight,
            )

    def test_report_publication_uses_registered_route_not_docs_reports(self) -> None:
        ticket_source, preflight = self.ticket_file("TEST-COMMUNITY-REPORT-ROUTE", owner="community")
        clean_cycle.begin("community", "260101010199", ticket_source)
        ticket = clean_cycle.collect("community", "260101010199", ticket_source, "created")
        report_source = self.source_file("report-community-route.txt", "registered report only")
        clean_cycle.write_records(
            "community",
            "TEST-COMMUNITY-REPORT-ROUTE",
            "260101010199",
            "main",
            "complete",
            None,
            None,
            [ticket],
            git_disposition="NOT_APPLICABLE",
            report_source=report_source,
            ticket_preflight=preflight,
        )
        clean_cycle.validate("community", "260101010199")
        report_dir = clean_cycle.HOPPER / "community" / "Report (Community)"
        self.assertTrue((report_dir / "output-community-260101010199.txt").is_file())
        self.assertFalse((self.root / "docs" / "reports").exists())

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
        ticket_source, _ = self.ticket_file("TEST-EXTERNAL-SOURCE")
        clean_cycle.begin("jobcenter", cycle, ticket_source)
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

    def test_report_required_artifact_is_copied_and_validated_in_both_sets(self) -> None:
        cycle = "260101010106"
        ticket_source, preflight = self.ticket_file("TEST-REPORT-REQUIRED")
        clean_cycle.begin("jobcenter", cycle, ticket_source)
        primary = clean_cycle.collect(
            "jobcenter", cycle, self.source_file("primary.md", "terminal"),
            "created", "REPORT_REQUIRED"
        )
        supporting = clean_cycle.collect(
            "jobcenter", cycle, self.source_file("support.log", "evidence"),
            "created", "HOPPER_SUPPORTING"
        )
        ticket = clean_cycle.collect(
            "jobcenter", cycle, ticket_source,
            "source", "HOPPER_SUPPORTING"
        )
        clean_cycle.write_records(
            "jobcenter", "TEST-REPORT-REQUIRED", cycle, "main", "complete",
            None, None, [primary, supporting, ticket], git_disposition="NOT_APPLICABLE",
            report_source=self.source_file("report.txt", "status"),
            ticket_preflight=preflight,
        )
        clean_cycle.validate("jobcenter", cycle)
        report = clean_cycle.HOPPER / "jobcenter" / "Report (Job Center)"
        hopper = clean_cycle.HOPPER / "jobcenter" / "Hopper (Job Center)"
        self.assertTrue((report / primary["hopper_filename"]).is_file())
        self.assertTrue((hopper / primary["hopper_filename"]).is_file())
        self.assertFalse((report / supporting["hopper_filename"]).exists())

    def test_missing_report_required_artifact_fails_validation(self) -> None:
        cycle = "260101010107"
        ticket_source, preflight = self.ticket_file("TEST-REPORT-REQUIRED-MISSING")
        clean_cycle.begin("jobcenter", cycle, ticket_source)
        primary = clean_cycle.collect(
            "jobcenter", cycle, self.source_file("primary.md", "terminal"),
            "created", "REPORT_REQUIRED"
        )
        ticket = clean_cycle.collect(
            "jobcenter", cycle, ticket_source,
            "source", "HOPPER_SUPPORTING"
        )
        report = clean_cycle.HOPPER / "jobcenter" / "Report (Job Center)"
        (report / primary["hopper_filename"]).unlink()
        clean_cycle.write_records(
            "jobcenter", "TEST-REPORT-REQUIRED-MISSING", cycle, "main", "complete",
            None, None, [primary, ticket], git_disposition="NOT_APPLICABLE",
            report_source=self.source_file("report.txt", "status"),
            ticket_preflight=preflight,
        )
        with self.assertRaisesRegex(RuntimeError, "REPORT_REQUIRED"):
            clean_cycle.validate("jobcenter", cycle)

    def test_invalid_ticket_preflight_does_not_initialize_cycle(self) -> None:
        ticket = self.source_file("invalid-ticket.txt", "TICKET READY FOR CODEX\nTEST — truncated")
        with self.assertRaisesRegex(RuntimeError, "ticket preflight failed"):
            clean_cycle.begin("jobcenter", "260101010108", ticket)
        self.assertFalse((clean_cycle.HOPPER / "jobcenter" / "archive" / "260101010108").exists())

    def test_fast_and_convergence_do_not_copy_committed_sources_automatically(self) -> None:
        for index, mode in enumerate(("FAST", "CONVERGENCE"), start=9):
            cycle = f"2601010101{index:02d}"
            ticket_id = f"TEST-{mode}"
            ticket_source, preflight = self.ticket_file(ticket_id, mode=mode)
            clean_cycle.begin("jobcenter", cycle, ticket_source)
            source_ticket = clean_cycle.collect(
                "jobcenter", cycle, ticket_source, "source"
            )
            clean_cycle.write_records(
                "jobcenter", ticket_id, cycle, "main", "complete",
                None, None, [source_ticket], git_disposition="NOT_APPLICABLE",
                report_source=self.source_file(f"report-{mode}.txt", "status"),
                mode=mode,
                ticket_preflight=preflight,
            )
            payload = json.loads((
                clean_cycle.HOPPER / "jobcenter" / "Hopper (Job Center)" /
                f"cycle-jobcenter-{cycle}.json"
            ).read_text())
            self.assertFalse(payload["report_tier"]["copy_committed_source_by_default"])
            self.assertEqual(len(payload["artifacts"]), 1)

    def test_acceptance_fixture_does_not_own_shared_workflow_cycle(self) -> None:
        cycle = "260101010111"
        ticket_source, preflight = self.ticket_file(
            "TEST-OWNER-FIXTURE", mode="CONVERGENCE", owner="shared-workflow"
        )
        clean_cycle.begin("shared-workflow", cycle, ticket_source)
        source_ticket = clean_cycle.collect(
            "shared-workflow", cycle, ticket_source, "source"
        )
        clean_cycle.write_records(
            "shared-workflow", "TEST-OWNER-FIXTURE", cycle, "main", "complete",
            None, None, [source_ticket], git_disposition="NOT_APPLICABLE",
            report_source=self.source_file("report-owner-fixture.txt", "status"),
            mode="CONVERGENCE", objective_owner="shared-workflow",
            acceptance_fixtures=["profile"],
            ticket_preflight=preflight,
        )
        shared = clean_cycle.HOPPER / "shared-workflow" / "Hopper (Shared Workflow)"
        profile = clean_cycle.HOPPER / "profile" / "Hopper (Profile)"
        payload = json.loads((shared / f"cycle-shared-workflow-{cycle}.json").read_text())
        self.assertEqual(payload["objective_owner"], "shared-workflow")
        self.assertEqual(payload["acceptance_fixtures"], ["profile"])
        self.assertFalse(profile.exists())

    def test_stub_generation_boundary_and_accumulation(self) -> None:
        from tools.workflow import workflow_v2

        report = clean_cycle.HOPPER / "jobcenter" / "Report (Job Center)"
        report.mkdir(parents=True, exist_ok=True)
        (report / "cycle-jobcenter-old.json").write_text("executed")
        stub = workflow_v2.write_unexecuted_stub(
            "jobcenter", ticket_id="TEST-STUB-001", title="first", source_hash="hash-1",
            classification="NOT_EXECUTED / BLOCKED", response="first terminal response",
            objective_owner="jobcenter", root=self.root,
        )
        self.assertTrue(stub.is_file())
        self.assertEqual([item.name for item in report.iterdir()], ["UNEXECUTED-STUB.txt"])
        generations = list((clean_cycle.HOPPER / "jobcenter" / "archive" / "report-generations").rglob("cycle-jobcenter-old.json"))
        self.assertEqual(len(generations), 1)
        workflow_v2.write_unexecuted_stub(
            "jobcenter", ticket_id="TEST-STUB-002", title="second", source_hash="hash-2",
            classification="NOT_EXECUTED / BLOCKED", response="second terminal response",
            objective_owner="jobcenter", root=self.root,
        )
        self.assertEqual(len(list((clean_cycle.HOPPER / "jobcenter" / "archive" / "report-generations").iterdir())), 1)
        self.assertIn("second terminal response", stub.read_text())

    def test_genuine_cycle_retires_accumulated_stub_once(self) -> None:
        from tools.workflow import workflow_v2

        report = clean_cycle.HOPPER / "jobcenter" / "Report (Job Center)"
        report.mkdir(parents=True, exist_ok=True)
        stub = workflow_v2.write_unexecuted_stub(
            "jobcenter", ticket_id="TEST-STUB-003", title="stub", source_hash="hash-3",
            classification="NOT_EXECUTED / BLOCKED", response="terminal response",
            objective_owner="jobcenter", root=self.root,
        )
        retired = workflow_v2.retire_unexecuted_stub("jobcenter", "260101010199", self.root)
        self.assertEqual(retired.name, "UNEXECUTED-STUB-260101010199.txt")
        self.assertFalse(stub.exists())
        self.assertEqual(list(report.iterdir()), [])
        self.assertEqual(len(list((clean_cycle.HOPPER / "jobcenter" / "archive" / "unexecuted-stubs").iterdir())), 1)

    def test_execution_project_owns_report_when_objective_owner_differs(self) -> None:
        cycle = "260101010112"
        ticket_source, preflight = self.ticket_file(
            "TEST-SHARED-OBJECTIVE", mode="STANDARD", owner="shared-workflow"
        )
        clean_cycle.begin("jobcenter", cycle, ticket_source)
        source_ticket = clean_cycle.collect("jobcenter", cycle, ticket_source, "source")
        clean_cycle.write_records(
            "jobcenter", "TEST-SHARED-OBJECTIVE", cycle, "main", "complete",
            None, None, [source_ticket], git_disposition="NOT_APPLICABLE",
            report_source=self.source_file("report-agent-route.txt", "status"),
            objective_owner="shared-workflow", ticket_preflight=preflight,
        )
        clean_cycle.validate("jobcenter", cycle)
        payload = json.loads((clean_cycle.HOPPER / "jobcenter" / "Hopper (Job Center)" /
                              f"cycle-jobcenter-{cycle}.json").read_text())
        self.assertEqual(payload["project"], "jobcenter")
        self.assertEqual(payload["execution_project"], "jobcenter")
        self.assertEqual(payload["objective_owner"], "shared-workflow")


if __name__ == "__main__":
    unittest.main()
