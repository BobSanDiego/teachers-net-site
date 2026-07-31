# Community Publisher First Slice v1

The exact next ticket is **C3-CORE007 — Test-Only WordPress-Native Publisher
Domain Core**. It should implement pure domain objects, validation result
types, threading rules, and lifecycle transitions using synthetic data only.
It must not create schema, write WordPress tables, publish forms, integrate the
legacy publisher, attach notifications, or migrate content.

This is the smallest safe slice because it validates the domain contract before
runtime SQL. Remaining blockers are approved mapping evidence, anonymous/privacy
policy decisions, complete moderator/edit/delete evidence, and authorization
for persistence.
