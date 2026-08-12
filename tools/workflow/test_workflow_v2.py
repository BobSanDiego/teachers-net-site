#!/usr/bin/env python3
"""Regression coverage for the canonical Teachers.Net Workflow V2 owner."""
from __future__ import annotations

import subprocess
import sys
import unittest
from pathlib import Path

from workflow_v2 import (
    AcceptanceLedger,
    AcceptanceSeam,
    WorkflowV2Error,
    bootstrap,
    load_project_record,
    load_manifest,
    reasoning_boost_notice,
    reasoning_reminder,
    report_tier,
    resolve_report_owner,
    validate_ticket_payload,
    workflow_cost_signal,
    shared_authority_marker,
)


def ticket(ticket_id: str = "TEST-V2-001", **overrides: str) -> str:
    values = {
        "mode": "FAST",
        "owner": "jobcenter",
        "outcome": "Prove one bounded invariant.",
        "stop": "Stop after the invariant is proven.",
        "end_id": ticket_id,
    }
    values.update(overrides)
    return f"""TICKET READY FOR CODEX
{ticket_id} — Compact fixture

MODE: {values['mode']}
OWNER: {values['owner']}

OUTCOME

{values['outcome']}

STOP BOUNDARY

{values['stop']}

END TICKET — {values['end_id']}
"""


class WorkflowV2Tests(unittest.TestCase):
    def test_manifest_is_single_v2_owner(self) -> None:
        manifest = load_manifest()
        self.assertEqual(manifest["workflow_version"], "V2")
        self.assertEqual(manifest["owners"]["ticket_validation"], "tools/workflow/workflow_v2.py")
        self.assertEqual(manifest["owners"]["bootstrap_entry"], "tools/workflow/workflow.py")
        self.assertEqual(manifest["owners"]["portable_handoff"], "tools/codex_archive/prepare_chatgpt_handoff.py")
        self.assertEqual(manifest["owners"]["report_hopper_finalization"], "tools/hopper/clean_cycle.py")
        self.assertEqual(manifest["portable_handoff"]["prepare_command"], "PREPARE HANDOFF")
        self.assertEqual(manifest["portable_handoff"]["startup_command"], "LOAD STARTUP")

    def test_valid_compact_ticket(self) -> None:
        result = validate_ticket_payload(ticket())
        self.assertTrue(result["valid"])
        self.assertEqual(result["ticket_id"], "TEST-V2-001")
        self.assertEqual(result["mode"], "FAST")
        self.assertEqual(result["warnings"], [])

    def test_ticket_label_with_inline_title_is_valid(self) -> None:
        payload = ticket().replace(
            "TEST-V2-001 — Compact fixture",
            "Ticket: TEST-V2-001 — Compact fixture",
        )
        self.assertEqual(validate_ticket_payload(payload)["ticket_id"], "TEST-V2-001")

    def test_conditional_runtime_and_input_fields_fail_closed(self) -> None:
        runtime = ticket().replace("OWNER: jobcenter", "OWNER: jobcenter\nRUNTIME REQUIRED: YES")
        with self.assertRaisesRegex(WorkflowV2Error, "CANONICAL URL"):
            validate_ticket_payload(runtime)
        inputs = ticket().replace("OWNER: jobcenter", "OWNER: jobcenter\nINPUT REQUIRED: YES")
        with self.assertRaisesRegex(WorkflowV2Error, "REQUIRED INPUTS"):
            validate_ticket_payload(inputs)

    def test_missing_and_mismatched_terminators_fail(self) -> None:
        with self.assertRaisesRegex(WorkflowV2Error, "truncated or missing terminator"):
            validate_ticket_payload(ticket().replace("END TICKET — TEST-V2-001", ""))
        with self.assertRaisesRegex(WorkflowV2Error, "terminator mismatch"):
            validate_ticket_payload(ticket(end_id="TEST-V2-OTHER"))

    def test_missing_owner_and_stop_fail(self) -> None:
        with self.assertRaisesRegex(WorkflowV2Error, "objective owner missing"):
            validate_ticket_payload(ticket(owner="").replace("OWNER: \n", ""))
        with self.assertRaisesRegex(WorkflowV2Error, "STOP BOUNDARY"):
            validate_ticket_payload(ticket().replace("STOP BOUNDARY\n\nStop after the invariant is proven.\n", ""))

    def test_oversized_complete_ticket_warns_but_executes(self) -> None:
        result = validate_ticket_payload(ticket(outcome="x" * 15_100))
        self.assertTrue(result["valid"])
        self.assertTrue(result["warnings"])

    def test_registered_projects_resolve_central_v2_without_leakage(self) -> None:
        expected = {
            "jobcenter": "Job Center",
            "views": "Teachers.Net Durable Views",
            "community": "Teachers.Net Community",
            "profile": "Teachers.Net Profile",
        }
        for project, display in expected.items():
            with self.subTest(project=project):
                result = bootstrap(project)
                self.assertEqual(result["status"], "BOOTSTRAP COMPLETE")
                self.assertEqual(result["workflow"], "V2")
                self.assertEqual(result["project"], project)
                self.assertEqual(result["display_name"], display)
                self.assertIn(f"tmp/hopper/{project}", result["report_hopper"])
                self.assertFalse(result["product_implementation_authorized"])

        _, community = load_project_record("community")
        community_guidance = {item["path"] for item in community["guidance_sources"]}
        self.assertIn("docs/community-3.0/project-cursor.md", community_guidance)
        self.assertNotIn("docs/core-terms/project-cursor.md", community_guidance)

    def test_new_project_bootstrap_enters_bounded_onboarding(self) -> None:
        result = bootstrap("future-project-fixture")
        self.assertEqual(result["status"], "NEW_PROJECT_ONBOARDING")
        self.assertEqual(result["bootstrap_authorization"], "BOOTSTRAP")
        self.assertFalse(result["product_implementation_authorized"])

    def test_bootstrap_command_entry_has_no_cross_project_default(self) -> None:
        entry = Path(__file__).with_name("workflow.py")
        completed = subprocess.run(
            [sys.executable, str(entry), "BOOTSTRAP", "--project", "jobcenter"],
            check=True,
            capture_output=True,
            text=True,
        )
        self.assertIn("BOOTSTRAP COMPLETE", completed.stdout)
        self.assertIn("Project ID: jobcenter", completed.stdout)
        self.assertIn("Handoff command: PREPARE HANDOFF", completed.stdout)
        self.assertIn("Fresh ChatGPT command: LOAD STARTUP", completed.stdout)
        ambiguous = subprocess.run(
            [sys.executable, str(entry), "BOOTSTRAP"],
            check=False,
            capture_output=True,
            text=True,
        )
        self.assertNotEqual(ambiguous.returncode, 0)
        self.assertIn("no cross-project default", ambiguous.stderr)

    def test_objective_owner_is_not_replaced_by_profile_fixture(self) -> None:
        self.assertEqual(resolve_report_owner("shared-workflow", "profile"), "shared-workflow")
        with self.assertRaises(WorkflowV2Error):
            resolve_report_owner("", "profile")

    def test_reasoning_signal_and_reminder_contract(self) -> None:
        self.assertEqual(reasoning_boost_notice("MEDIUM"), "FOR NEXT TICKET BOOST AI TO * MEDIUM *")
        self.assertEqual(reasoning_boost_notice("MAXIMUM"), "FOR NEXT TICKET BOOST AI TO *** MAXIMUM ***")
        self.assertEqual(
            reasoning_reminder("MAXIMUM", "NORMAL"),
            "REMINDER: AI IS NOW *** MAXIMUM *** / RECOMMEND SET TO NORMAL",
        )
        self.assertEqual(
            reasoning_reminder("MAXIMUM", "MEDIUM"),
            "REMINDER: AI IS NOW *** MAXIMUM *** / RECOMMEND SET TO * MEDIUM *",
        )
        self.assertEqual(
            reasoning_reminder("MEDIUM", "MEDIUM"),
            "REMINDER: AI IS NOW * MEDIUM * / RECOMMEND KEEP SETTING FOR ONE MORE CYCLE",
        )
        self.assertEqual(
            reasoning_reminder("MEDIUM", "NORMAL"),
            "REMINDER: AI IS NOW * MEDIUM * / RECOMMEND SET TO NORMAL",
        )
        self.assertEqual(
            reasoning_reminder("MAXIMUM", "MAXIMUM"),
            "REMINDER: AI IS NOW *** MAXIMUM *** / RECOMMEND KEEP SETTING FOR ONE MORE CYCLE",
        )

    def test_acceptance_ledger_carries_only_unchanged_proven_seams(self) -> None:
        ledger = AcceptanceLedger("TEST-V2-001", "CONVERGENCE")
        ledger.add(AcceptanceSeam(
            seam="POST/session",
            evidence_class="STATE/DATA",
            owner="handler.py",
            owner_identity="blob-a",
            dependency_identity="schema-a",
            status="PROVEN",
        ))
        self.assertFalse(ledger.should_rerun("POST/session", owner_identity="blob-a", dependency_identity="schema-a"))
        self.assertTrue(ledger.should_rerun("POST/session", owner_identity="blob-b", dependency_identity="schema-a"))
        self.assertEqual(ledger.as_dict()["workflow_version"], "V2")

    def test_report_tiers_never_copy_committed_source_by_default(self) -> None:
        for mode in ("FAST", "CONVERGENCE"):
            with self.subTest(mode=mode):
                self.assertFalse(report_tier(mode)["copy_committed_source_by_default"])

    def test_shared_authority_marker_is_deterministic(self) -> None:
        self.assertEqual(shared_authority_marker(), shared_authority_marker())

    def test_workflow_cost_guardrail(self) -> None:
        self.assertTrue(workflow_cost_signal([
            {"workflow_or_tooling": False},
            {"workflow_or_tooling": True},
            {"workflow_or_tooling": True},
        ])["review_required"])
        self.assertFalse(workflow_cost_signal([
            {"workflow_or_tooling": False},
            {"workflow_or_tooling": True, "active_quantified_blocker": True},
        ])["review_required"])


if __name__ == "__main__":
    unittest.main()
