# Teachers.Net Handoff Lifecycle

This is the canonical shared shutdown/startup procedure. Project facts come
from the active project record; this document owns the process.

## Shutdown

1. Export the closing ChatGPT transcript.
2. Supply it to Codex and issue:

   `PREPARE HANDOFF — incorporate the attached closing ChatGPT transcript and execute the complete handoff lifecycle for this project.`

3. The workflow resolves the project record, validates and hashes the closing
   transcript, refreshes the ChatGPT master and Codex portable record, builds
   guidance and START-HERE, validates every member, and publishes a new
   immutable timestamped checkpoint under
   `/mnt/c/Main/Active/Projects/Teachers.Net/HANDOFFS/`.
4. The workflow returns a durable handoff receipt. Report/Hopper contains the
   receipt and supporting evidence, not duplicate multi-megabyte payloads.

Every successful checkpoint is named `<Project-Name>-YYYYMMDD-HHMMSS` and is
published through a temporary build followed by validation and atomic rename.
Existing checkpoints are never overwritten.

## Startup

Upload the contents of the latest intended checkpoint to a fresh ChatGPT
conversation and say `Execute the attached handoff.` The successor reads
`00-START-HERE.txt`, adopts the shared Operating Contract, resolves the
project record and authority package, and consults transcripts only as needed.
It returns the prescribed concise startup-state report before engineering
resumes.

## Payload and safety

The checkpoint contains the START-HERE, shared contract, authority ZIP,
complete ChatGPT master, compact Codex current handoff, project record, and
manifest. It excludes the full Codex fossil body, raw JSONL, credentials,
Report/Hopper logs, screenshots, and temporary diagnostics. No AI
summarization or semantic transcript filtering is used.

The canonical tracked process is `tools/codex_archive/prepare_handoff.py`.
The Windows-accessible operational projection is
`/mnt/c/Main/Active/Projects/Teachers.Net/SHARED-WORKFLOW/`; projected files
identify their canonical tracked source.
