# Community 3.0 Hopper Governance v1

The repository source documents are authoritative. A hopper is a flat,
timestamped handoff payload, never a source of truth. Update Project Cursor and
Engineering Handoff before committing ticket work. Commit and push source work
before finalizing the hopper metadata.

Required order:

1. Complete the bounded ticket and update continuity documents.
2. Run focused and applicable tests.
3. Commit and push source changes.
4. Begin a fresh project cycle, preserving protected `output.txt`.
5. Collect every ticket artifact and continuity file.
6. Create cycle JSON, manifest, and completion report.
7. Run semantic continuity validation with source cursor, handoff, manifest,
   and cycle JSON.
8. Archive the report duplicate and commit/push the payload.

`payload-recreated` is valid only when a payload is being rebuilt without new
ticket execution. It must not be labeled `complete`, and it must not update
continuity state. A completed cycle must name the completed ticket consistently
in source documents, manifest, and cycle JSON.

The protected engineer-owned `output.txt` is never moved, copied, edited,
archived, manifested, or reported as a ticket artifact. Active files remain flat
in `tmp/hopper/tnet-3.0/current/`; prior payloads remain under the project
archive.
