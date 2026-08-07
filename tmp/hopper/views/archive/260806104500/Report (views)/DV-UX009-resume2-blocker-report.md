# DV-UX009 Resume (Authorized Renderer/Controller Refactor) — Blocker Report

Previous cycle completed: DV-ARCH003 identified and authorized removal of the
renderer/controller form-boundary blocker.

Status: DV-UX009 remains blocked by a newly evidenced persistence/lifecycle
constraint outside DV-ARCH003.

## New blocker

The current Views persistence contract has no saved-draft snapshot boundary.
Entry and group edits write directly to the active draft records through
`CFM_Views_Repository::save_entry`, `delete_entry`, and ordering methods. There
is no persisted “last saved draft” snapshot from which Revert to Saved Draft
can restore state.

The repository/controller also has no draft-deletion operation. Existing
methods include draft creation, entry/group mutation, publication, retirement,
restore, validation, and preview, but no safe Delete Draft lifecycle seam.

Implementing these behaviors now would require inventing semantics or adding a
schema/persistence contract not authorized by the renderer refactor ticket.

## Evidence inspected

- `wordpress/wp-content/plugins/profilaxes/admin/class-cfm-views-admin.php`
  (`handle_actions`, `render_draft_editor`, and all active form boundaries).
- `wordpress/wp-content/plugins/profilaxes/includes/class-cfm-views-repository.php`
  (`save_entry`, `delete_entry`, `add_selected_entries`, draft creation,
  publication, validation, and preview).
- Current Profilaxes commit: `6a600ff`.
- DV-SPEC001 finalized by DV-SPEC002.
- DV-ARCH003 blocking diagnostic.

## Renderer status

The authorized renderer refactor was not completed in this pass. No code was
changed or committed because the required lifecycle semantics cannot be
completed safely with the current persistence contract.

## Required next decision

Authorize a narrowly scoped persistence/lifecycle contract ticket defining:

- what Save Draft commits;
- where the saved draft snapshot lives;
- how Revert restores it;
- how Delete Draft removes only the draft;
- how one active draft is enforced;
- how published versions remain immutable.

This is a new blocker outside DV-ARCH003. DV-UX009 remains open. DV-UX010
must not begin.
