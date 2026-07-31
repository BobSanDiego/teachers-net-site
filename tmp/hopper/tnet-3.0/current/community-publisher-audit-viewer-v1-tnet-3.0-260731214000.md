# Community Publisher Audit Viewer v1

The sandbox shows append-only audit history and publication-event history for
the selected post. Hide, retract, restore, and soft-delete actions are routed
through the PHP lifecycle transition and repository audit path. Publication
events remain local outbox rows; they are not dispatched. All output is escaped
and limited to synthetic local records.
