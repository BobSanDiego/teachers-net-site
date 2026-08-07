# DV-UX009C — Restore Core Terms Tree Expansion

## Outcome

DV-UX009C is complete. Human DV-UX009 acceptance testing may resume.

## Root cause

The Library workbench script unconditionally called `addEventListener()` on
`[data-cfm-views-select-visible]`, a control not rendered in the current V1
markup. The exception stopped event initialization before the delegated
expand/collapse handler was usable.

## Fix

The optional selection-control lookup is now guarded in
`wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`.
No schema, repository, resolver, Jobs, autosave, removal, preview, publish, or
manager behavior changed.

## Browser verification

- Canonical URL: `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17`
- Authenticated user: `jobman`
- Grade Level: expand/collapse/re-expand passed; six children appeared.
- Location: expand/collapse/re-expand passed; three children appeared.
- Subject Area: expand/collapse/re-expand passed; sixteen children appeared.
- No checkbox selections changed; nested indentation remained intact.
- Console: no messages found after interaction.
- Screenshot: collected from the alternate Windows mount into the Views hopper.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Commit: `852a515`
- Push: successful
- Profilaxes Git status: clean
- Milestone tag: none

DV-UX010 was not started.
