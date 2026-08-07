# DV-UX009 — Views V1 Authoring Workflow Implementation

Status: Implementation committed; V1 verification incomplete  
Canonical URL: https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=13  
Verified against canonical URL: YES

## Implemented

- Library rows now use the finalized compact hierarchy direction.
- Top-level Library terms render without checkboxes.
- Represented terms remain unavailable for duplicate selection.
- The visible Library action is Shuttle Selected; contextual shuttle controls
  are present in the workflow.
- Framework choice is not visible when only one framework exists.
- Legacy Add to Draft row actions, manual entry, group creation form, and
  include-descendants inputs were removed from rendered output.
- Existing schema, repository, resolver, draft lifecycle, preview, validation,
  publication, and Jobs seams were preserved.

## Browser verification

- Canonical draft editor loaded at the URL above.
- Top-level checkbox count: 0.
- Legacy Add to Draft controls: 0.
- Manual-entry path: absent.
- Group-creation form: absent.
- Include-descendants controls: 0.
- Framework selector: hidden for the single local framework.
- Console errors/warnings: none observed.

## Remaining V1 gaps

The existing implementation still requires a follow-up implementation pass to
fully satisfy DV-SPEC002/DV-UX009:

- contextual right-side Remove All / Remove Selected / Clear Selection toolbar;
- right-tree checkbox removal state with descendant strike-through and muted
  checkboxes;
- top-level name-click confirmation dialog;
- complete Save Draft, Revert to Saved Draft, Delete Draft, and Draft Editor
  lifecycle controls;
- View Manager open/draft-context behavior and exact existing-draft dialog;
- authenticated screenshots for every required state.

These gaps are reported explicitly. No V2 capabilities were added.

## Git

Profilaxes branch `agent/durable-views-dv003-persistence` contains the
coherent implementation commits `9a450eb`, `14c270a`, and `6a600ff`, all
pushed. Root documentation commit pending for this completion report.
