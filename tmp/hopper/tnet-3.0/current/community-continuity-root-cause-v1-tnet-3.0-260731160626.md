# Community Continuity and Hopper Flush Root-Cause Analysis v1

## Root cause

The verified failure was an ordering and validation gap. A ticket could update
or commit implementation files while Project Cursor and Engineering Handoff
still contained forward-looking text from an earlier cycle. Hopper collection
then copied those source documents. `tools/hopper/clean_cycle.py` finalized
commit/push/file metadata, but before this correction it did not semantically
compare the completed ticket with the continuity prose, next-ticket block, or
contract-status statements.

## Reproduction

1. Leave a cursor containing `C3-IMP002` as proposed.
2. Complete a later bounded ticket and copy the cursor/handoff with `collect`.
3. Finalize the cycle with `refresh` and run the existing `validate` command.
4. Observe that file hashes, commit, push, manifest, and JSON can all pass
   while the copied prose still points backward.

Repository evidence is the C3-IMP001, C3-IMP003, C3-IMP004, and C3-IMP005
commit sequence, plus the stale cursor/handoff text found before OPS-CONT001.
No evidence showed production data or generated artifacts causing the drift.

## Affected workflow and files

The affected path was ticket completion -> continuity update -> commit ->
`clean_cycle.py collect` -> hand-authored or refreshed manifest/JSON -> report
-> archive. Affected process files were `tools/hopper/clean_cycle.py`, the
continuity documents, and the hopper current/archive payloads.

## Minimal correction

`tools/hopper/validate_community_continuity.py` now compares the cycle ticket,
cursor, handoff, manifest, cycle status, commit/push metadata, phase agreement,
backward next-ticket references, and protected-output exclusion. It fails closed
on contradictions. The correction does not rewrite project history and does
not claim C3-IMP002 or C3-NOT005 completion.

## Validation

```text
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
python3 -m unittest discover -s tools/hopper -p 'test_*.py'
python3 tools/hopper/validate_community_continuity.py --cursor ... --handoff ... --cycle ... --manifest ...
```

The regression test proves a stale cursor is rejected and a corrected state
passes. The guard is run only after the final cycle JSON and manifest exist.

## Remaining limitation

The validator checks governed markers and known contradiction patterns; it does
not understand arbitrary natural language. Engineering review remains required
for roadmap meaning and authorization.
