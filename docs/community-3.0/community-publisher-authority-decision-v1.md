# Community 3.0 Publisher Authority Decision v1

## Decision

**2. PUBLISHER EXISTS BUT SOURCE IS NOT OWNED/AVAILABLE.** The actual legacy
publisher is `/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi` on
Sandy. Its production path is evidenced by `CreatePost`, `IncludeFiles`,
`UpdateCapfiles`, and `UpdatePostsDB`. It is outside the owned Git repository,
uses legacy Perl CGI/static-file/database behavior, and lacks the canonical
modern identity/mapping contract.

## Consequences

Notification attachment is blocked. The in-memory adapter, application service,
and disabled shadow hook remain test infrastructure only. Core Terms, Job
Center, generic WordPress hooks, and the theme must not be promoted to
Community publisher authority.

The next bounded ticket must establish source ownership/access and define a
read-only compatibility boundary around the legacy publisher before live hook
or migration work. It must not silently absorb legacy behavior into a modern
plugin.

No production mutation, post creation, user change, option change, schema
change, notification, or deployment action occurred.
