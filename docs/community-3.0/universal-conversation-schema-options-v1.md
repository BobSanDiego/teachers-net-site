# Universal Conversation Schema Options v1

| Option | Strength | Risk | Decision |
|---|---|---|---|
| A. Nullable subject fields on current topic/thread | Smallest migration and fast standalone continuity | Attachment integrity and ownership need careful repository validation | Viable near-term compatibility slice |
| B. First-class conversations table | Clear identity, feed and lifecycle joins | New migration/ownership surface before product need | Defer |
| C. Synthetic topic per subject | Reuses current rows | Confuses subject and conversation, harms moderation/import identity | Reject |
| D. Hybrid subject adapter plus later table | Preserves current engine, isolates cross-product identity | Requires disciplined value object and later migration | Select |

The selected hybrid means current topics remain authoritative for the sprint;
the repository first accepts a validated subject-reference object and nullable
attachment fields. A separate conversations table is not required now. If
multiple conversations per subject become necessary, a future table can be
introduced without redefining post lineage.

Required future indexes include thread/time, branch root/time, parent, subject
reference, and visibility/access lookup. Uniqueness must be explicit for the
chosen one-or-many policy; no uniqueness assumption is implemented here.
