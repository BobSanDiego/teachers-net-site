# Community 3.0 Browser-First QA and Runtime Authority v1

Status: active local engineering guidance

## Screenshot Evidence Stop Condition

If a ticket requires Engineering Director screenshots or visual evidence and
Codex cannot access and inspect those files, Codex must stop before
implementation or acceptance. It must attempt one bounded access correction,
report the unresolved deficit if that fails, and request the minimum next
action. It may not substitute its own screenshots, DOM output, source
inspection, or automated assertions for unavailable Engineering Director
evidence.

When screenshots are referenced, first use the supported conversation/MCP
attachment mechanism, then reconcile Windows and WSL paths, then check the
project and active hopper copies. Record the exact accessible identifier/path
and inspect the image. If Engineering Director evidence contradicts Codex
evidence, reconcile route, commit, runtime badge, viewport, authentication,
cache, DOM state, and timestamps before any PASS claim.

## Scope

This document governs browser-facing Community 3.0 recovery tickets. It is
project-specific guidance; it does not authorize production changes, schema
migrations, mail delivery, or broad UX redesign.

## Three truths must align

Community browser work is complete only when all three forms of truth agree:

1. **Repository truth** — the intended source and documents exist in the
   correct authority worktree.
2. **Runtime truth** — the browser is serving the expected host, DDEV project,
   mounted plugin tree, worktree, branch, commit, plugin hash, route, and
   controller.
3. **Product truth** — the authenticated user can see and perform the intended
   browser-visible behavior.

The canonical local browser authority is:

- URL: `https://teachers-net-community3.ddev.site`
- Worktree: `/home/bobreap/projects/teachers-net-community3`
- Branch: `COMMUNITY3-ui-working`
- Community route: `/community/new/`

The runtime badge is permanent local QA infrastructure. It must remain
local/DDEV-only, be generated from serving-runtime facts, and fail closed when
authority data does not match the current runtime.

## Required preflight and acceptance

Before browser-facing implementation or acceptance, run:

```bash
bash tools/qa/runtime_authority_preflight.sh
```

The preflight starts DDEV, resolves the current branch and commit, calculates
the mounted plugin-tree identity, writes the generated authority record, and
checks that the rendered response contains `status=ok` and the current commit.
If it fails or the browser badge disagrees, stop and diagnose runtime
authority. Do not continue with speculative UI fixes.

Acceptance requires authenticated browser evidence. Source inspection, PHP
lint, HTTP status, DDEV status, commit identity, and completion reports are
supporting evidence only; they do not substitute for rendered product truth.

## Evidence modes

Use the smallest mode that matches the ticket:

- **Normal:** affected page only, one relevant 1440px AFTER screenshot, and a
  prior accepted AFTER screenshot as BEFORE when the page and authority remain
  comparable.
- **Responsive:** only when responsive behavior changes; capture the necessary
  transition widths, normally 390, 768, and 1440, adding 1024 only when the
  transition requires it.
- **Diagnostic:** only for runtime, cache, DOM, event-path, CSS-cascade, or
  rendering mismatch investigations; computed styles, DOM/event evidence, and
  broader capture sets are then justified.

Every report must record the review URL, runtime status, commit, evidence mode,
and canonical screenshot paths. Relevant screenshots belong in the active
handoff when the ticket requires them; redundant screenshot matrices do not.

## Composer authority

The Community composer is a lightweight, social, paste-first authoring surface:

- body-first authoring with simple visible controls;
- `Add Photo`/camera action plus paste and drag/drop;
- preview and remove/replace behavior;
- automatic representative-link selection and preview behavior;
- automatic baseline accessibility metadata without a required visible alt
  field;
- no heavy rich-text editor unless separately authorized.

Images should read as story content rather than technical attachments. Reply
media parity remains a future browser-verification target; historical claims
are not acceptance evidence.

## Escalation and stop rules

One ticket should contain one narrow browser-visible objective. If two bounded
implementation passes fail to produce the intended rendered result, stop and
diagnose the live DOM, computed CSS, event registration, render path, cache,
duplicate source, output rewrite, route, and controller authority. A runtime
mismatch always stops execution pending correction or user acknowledgment.

## Hopper lifecycle

The authoritative Community handoff hopper is the site repository path:

`/home/bobreap/projects/teachers-net-site/tmp/hopper/tnet-3.0/current`

Windows Explorer path:

`\\wsl$\\Ubuntu-24.04\\home\\bobreap\\projects\\teachers-net-site\\tmp\\hopper\\tnet-3.0\\current`

Every ticket must archive all existing `current/` contents first, verify the
directory is clean, execute the bounded work, repopulate only current-ticket
artifacts, and validate the cycle. Screenshots must be included only when
relevant to the active handoff; otherwise record their canonical paths in the
report. Never treat path acknowledgment as ritual completion.

The current handoff must distinguish modified source, report, manifest,
governance records, and evidence. Historical material belongs in the archive,
not in `current/`.

## Historical UX status

UX005–UX009 remain informational source-history references, not completed
product milestones, until each intended browser outcome is independently
re-proven. `COMMUNITY-RESTART001` and `COMMUNITY-RESTART003` are the current
browser-verified recovery milestones recorded in the Project Cursor and
Engineering Handoff.
