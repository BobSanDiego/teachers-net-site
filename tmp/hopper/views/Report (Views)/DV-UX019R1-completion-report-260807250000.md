# DV-UX019R1 — Completion Report

## Status

DV-UX019R1 COMPLETE. Human acceptance for DV-UX019 may resume. DV-UX020 was
not started.

## Corrections

- Added the shared focus contract: mouse activation clears the heavy WordPress
  focus box; keyboard `:focus-visible` retains a restrained blue outline.
- Corrected the Library selection spacer so it is a real grid slot rather than
  an absolutely positioned element. Library and Current View now use the same
  disclosure, selection, and label columns at every tested depth.
- Added direct-branch disclosure synchronization. Opening a node reveals only
  its immediate children; descendants remain collapsed until opened directly.
  Indicators are reset with hidden descendants and global Expand All/Collapse
  All remains synchronized.
- Preserved shuttle, represented/ancestor state, bulk-selection, Current View
  removal, contextual controls, repository, resolver, schema, and Jobs seams.

## Browser verification

Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17

Authenticated cache-bypassed browser verification passed. Neutral computed
styles matched: transparent background, `1px solid rgb(240, 240, 241)` row
separator, 28px row height, `1px 0px` padding, 7px gap, and the same system
font. Representative structural positions matched at depth 0/1/2:
disclosure 27/45/63px, selection 52/70/88px, label 77/95/113px.

The Library and Current View top-level branches opened independently. Direct
children appeared first; descendant branches remained collapsed until their
own disclosure was activated. Expand All set every disclosure to expanded;
Collapse All hid descendants and set every disclosure to collapsed. The
console reported no warnings or errors. Focus verification showed keyboard
focus-visible outline `rgb(34, 113, 177) solid 1px` and no box shadow.

Chrome MCP captured a screenshot but exposed only the Windows path
`C:\\home\\bobreap\\projects\\teachers-net-site\\tmp\\DV-UX019R1-parity.png`;
it was not available as a WSL-local file, so it is not claimed in the hopper.

## Git

- Profilaxes branch: `agent/durable-views-dv003-persistence`
- Commit: `548a604` (`Correct shared Views disclosure presentation`)
- Push: successful
- Root documentation commit: pending in this cycle

