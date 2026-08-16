# Teachers.Net Handoff Lifecycle

This is the canonical shared shutdown/startup procedure. Project facts come
from the active project record; this document owns the process.

## Routine portable ChatGPT startup handoff

1. Create or update the OpenAI ChatGPT share for the registered project.
2. Supply its share URL to a BOOTSTRAP-ready project Codex and issue exactly:

   `PREPARE HANDOFF`

3. The central workflow retrieves and archives the share, decodes one canonical
   visible user/assistant sequence using OpenAI message UUIDs, reconciles the
   registered project master, consumes terminal evidence, and validates one
   self-contained startup payload under the registered HANDOFFS root.
4. Move the payload into a fresh ChatGPT and issue exactly:

   `LOAD STARTUP`

5. ChatGPT follows `00-LOAD-STARTUP.md` and returns its prescribed concise
   `STARTUP LOADED` identity/freshness/objective status.

The routine payload contains visible logical components plus
`99-PACKAGE-MANIFEST.json`; no essential startup instruction depends on local
repository access. Physical ZIP versus visible-file delivery remains deferred.
The preparer may expose both the validated visible directory and an optional
ZIP transport candidate for empirical testing; neither changes logical roles.

## Full immutable recovery checkpoint

Every successful checkpoint is named `<Project-Name>-YYYYMMDD-HHMMSS` and is
published through a temporary build followed by validation and atomic rename.
Existing checkpoints are never overwritten.

## Payload and safety

The checkpoint contains the START-HERE, shared contract, authority ZIP,
complete ChatGPT master, compact Codex current handoff, project record, and
manifest. It excludes the full Codex fossil body, raw JSONL, credentials,
Report/Hopper logs, screenshots, and temporary diagnostics. No AI
summarization or semantic transcript filtering is used.

The canonical tracked publication owner is
`tools/codex_archive/project_handoff_builder.py`:

```text
python3 tools/codex_archive/project_handoff_builder.py \
  --project <registered-project-id> \
  --source <validated-project-handoff-source> \
  --archive-root /mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS/
```

`prepare_handoff.py` remains the backward-compatible immutable Job Center
checkpoint entry point. Routine self-contained ChatGPT startup preparation is
owned centrally by `prepare_chatgpt_handoff.py`; neither owner may be copied
into project-local builders. The shared builders own validation, manifest
enrichment, hashing, collision refusal, and publication.
The Windows-accessible operational projection is
`/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW/`; projected files
identify their canonical tracked source.

The share-based path is implemented centrally by
tools/codex_archive/openai_share_archive.py and
tools/codex_archive/openai_share_index.py. It stores raw provenance and a
faithful canonical session under
tmp/hopper/shared-workflow/openai-share-archive/<project>/, plus generated
session/ticket ledgers and a modest static index. If the share is unavailable,
the existing file-driven transcript path remains the governed fallback; it
must still pass identity, freshness, and reconciliation validation.
