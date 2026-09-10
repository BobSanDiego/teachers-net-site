# Community Publisher Lifecycle v1

Normal posts follow post-first moderation: validate, persist, publish, and
notify the moderation/audit surface after commit. A specific community mode
may require pending moderation, but that is policy—not the default.

| State | Meaning | Allowed next states |
|---|---|---|
| draft | not submitted | validated, failed |
| validated | deterministic checks passed | published, pending |
| pending | explicit pre-moderation mode | published, hidden, spam, failed |
| published | visible according to policy | hidden, moderated, spam, retracted, deleted |
| hidden/moderated | restricted | published, spam, deleted, restored |
| spam | removed; evidence retained | restored, deleted |
| retracted | withdrawn; evidence retained | restored, deleted |
| deleted/tombstoned | not visible; audit remains | restored by authorized action |
| failed | no canonical publication | draft or terminal audit |

Every transition records actor, reason, visibility, notification effect, audit,
and reversibility. The authoritative event is emitted only after persistence
commits. Notification failure never rolls back publication. Admin actions
include accept/no-op, promote, spam/remove, and remove/disable-user-with-
evidence; UI is out of scope.
