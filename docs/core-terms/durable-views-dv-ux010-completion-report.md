# DV-UX010 — Adopt Meta-Groups Tree Structure in Core Terms Library

## Outcome

DV-UX010 implementation is complete for the left Core Terms Library. The
Current View tree, persistence, resolver, Jobs integration, and V1 lifecycle
were not redesigned.

## Reference inspected

The existing Core Terms assignment/Meta-Groups tree in
`wordpress/wp-content/plugins/profilaxes/includes/class-cfm.php` was inspected.
Its pattern groups children by `parent_uuid`, recursively renders nodes, uses
compact flex rows with an expander/checkbox/label, indents each generation, and
places children directly beneath the parent.

Views adopts that structural pattern through an isolated renderer so Views'
pending shuttle, represented-term, muted/inherited, and selection semantics
remain authoritative.

## Exact change

Changed:

- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`

The flat Library loop now builds `parent_uuid` buckets and recursively renders
`.cfm-views-term-node` / `.cfm-views-term-children` structures. Compact Library
CSS aligns expanders, checkboxes, and labels with tight generation indentation.

## Browser verification

- Canonical URL: `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`
- Authenticated user: `jobman`
- Grade Level expansion revealed six children; selection count remained zero.
- Location and Subject Area controls remained independently rendered.
- Nested descendants remain indented beneath their parent.
- Console: no messages found.
- Reference screenshot: `meta-groups.png`.
- After screenshot: `DV-UX010-views-library-after.png`.

The Library now matches the Meta-Groups structural model closely enough for
engineer acceptance, subject to visual review of the hopper screenshots.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Commit: `49b2921`
- Push: successful
- Profilaxes Git status: clean
- Milestone tag: none

DV-UX010 stop boundary observed; Current View tree redesign was not started.
