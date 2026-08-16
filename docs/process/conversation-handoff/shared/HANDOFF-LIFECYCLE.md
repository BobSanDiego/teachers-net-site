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
4. It then creates a directly openable successor drop containing exactly two
   files: `STARTUP-TICKET.txt` and the validated startup package ZIP. The ZIP
   remains the authoritative portable package; the drop is transport only.
5. Move those two files into a fresh ChatGPT and issue exactly:

   `LOAD STARTUP`

The terminal response must include the exact absolute successor-drop directory
as a clickable Markdown link, for example:

`[Open handoff directory](/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS/<drop>/)`

Plain text paths may be included additionally, but never replace the clickable
directory link.

5. ChatGPT follows `00-LOAD-STARTUP.md` and returns its prescribed concise
   `STARTUP LOADED` identity/freshness/objective status.

The successor must complete package verification before any startup status:
locate the adjacent ZIP, verify its SHA-256 against `STARTUP-TICKET.txt`,
extract it, read `99-PACKAGE-MANIFEST.json`, verify every required member is
present/non-empty and matches its manifest hash, then read and execute
`00-LOAD-STARTUP.md`. It must not infer or predict `STARTUP LOADED` or `READY`
from the ticket. Any failed seam produces `STARTUP BLOCKED` with the exact
failure. A successful response must report package-derived evidence including
ZIP hash, validated-member count, project identity, Workflow version,
conversation boundary, objective/state, source warnings, and semantic-authority
status.

The routine payload contains visible logical components plus
`99-PACKAGE-MANIFEST.json`; no essential startup instruction depends on local
repository access. The immutable package remains beneath the canonical
`/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS/` root. The two-file drop is
an operator transport surface and must not contain manifests, masters, reports,
raw transcripts, or authority files beside the ZIP. Reports remain in the
local Report/Hopper of the project that executed the operation.

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

The canonical two-file successor-drop owner is
`tools/codex_archive/prepare_chatgpt_handoff.py`; registered projects consume
it through the shared workflow and do not maintain project-local variants.
