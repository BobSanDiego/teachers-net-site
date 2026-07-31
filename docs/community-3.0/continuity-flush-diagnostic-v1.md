# Community 3.0 Continuity Flush Diagnostic v1

## Observed contradictions

Before this correction, the Project Cursor and Engineering Handoff stated that
C3-IMP002 was proposed after C3-IMP003 had completed. The Handoff also retained
C3-NOT005 as a possible next contract even though no C3-NOT005 contract or
implementation commit is present. The C3-IMP001 sequence likewise described
C3-IMP002 as proposed rather than recording that the repository proceeded to
C3-IMP003 without a committed C3-IMP002 implementation.

## Evidence and verified root cause

`git log --all --oneline` for the Community continuity documents shows commits
for C3-IMP001 (`cd37b12`) and C3-IMP003 (`5228bd3`), with no C3-IMP002 or
C3-NOT005 implementation/contract commit. The source cursor and handoff at the
start of this ticket contained the stale forward-looking statements. The
current hopper manifest copied those source files and identified only the
previous ticket cycle; it had no semantic check comparing ticket metadata with
continuity prose.

The demonstrated cause is therefore twofold: continuity files were not
reconciled before later export, and the hopper rebuild procedure copied files
without semantic validation. No evidence supports a generated-artifact or
production-data cause.

## Correction

The cursor and handoff now state C3-IMP003 as complete, explicitly state that
C3-IMP002 and C3-NOT005 are not verified as complete, and identify the next
decision as Engineering Director review of a separately bounded persistence or
channel slice. The implementation sequence records C3-IMP002 as proposed and
not executed; C3-IMP003 is the verified candidate/audit boundary.

`tools/hopper/validate_community_continuity.py` is a fail-closed guard. It
checks project/cycle status, completed-ticket presence in both continuity
documents, protected-output exclusion, and prevents false completion claims
for unverified tickets. It distinguishes `complete` from `payload-recreated`.

## Limitations

The guard validates required state markers and explicit contradictions; it
does not understand every natural-language sentence or replace Engineering
Director review. It must be run against the final source continuity files and
cycle JSON before a hopper payload is handed off.

No project history was rewritten. The correction records the sequence supported
by committed evidence and does not claim C3-IMP002 or C3-NOT005 completion.
