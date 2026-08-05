# Durable Views Administrator Manual

**Current implementation baseline — updated for DV-023**

This manual describes the Durable Views administration surfaces that exist in
the current Teachers.Net local implementation. It is not a specification for
the future Views Authoring UX. Where a capability is service-level, incomplete,
or absent from the browser, that limitation is stated plainly.

## 1. What Durable Views are

A Durable View is a reusable presentation definition: it determines which
canonical Core Terms are shown, their labels, groups, and order.

- **Core Terms** owns the terms that exist.
- **Durable Views** selects and presents those terms.
- **Jobs** owns job records, employer authorization, form behavior, and the
  binding from a Jobs field to a published View version.
- **WordPress** authenticates the administrator or employer user.

A View is not a permission set, taxonomy replacement, search filter, or job
authorization rule.

## 2. Before you begin

You need a WordPress administrator account with `manage_options`. The current
local administration URLs are:

- Durable Views: `https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views`
- Jobs category mappings: `https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-job-categories&tab=map`
- Jobs form preview: `https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-job-categories&tab=preview`
- Employer form: `https://teachers-net.ddev.site/jobs/employer/new/`

The URLs are local DDEV URLs. Production deployment is outside this manual.

## 3. Current browser capabilities

The current browser administration surface supports:

- creating a View and its initial draft;
- adding flat groups to a draft;
- selecting canonical Core Terms from the installed framework/term list;
- including or excluding a term;
- including descendants;
- overriding a display label;
- assigning display order;
- removing draft entries;
- previewing the resolved draft;
- viewing validation feedback;
- publishing a valid draft;
- retiring and restoring a published View;
- binding a Jobs field to a published View/version;
- removing that binding.

The published version is immutable. Changes require a new draft version.

## 4. Create a View

1. Open the Durable Views URL.
2. Find **Create View**.
3. Enter a clear name, for example `Job Listing — Grade Level`.
4. Add a description explaining the consumer and classification dimension.
5. Submit the form to create the View and its draft version.
6. Open the draft composition area for the new version.

Use one coherent dimension per View. Do not combine Grade Level, Subject Area,
and Location into one View unless a later product decision explicitly requires
that composition.

## 5. Compose a draft

### Add a group

In the draft composition area, enter:

- a key using letters, numbers, hyphens, or underscores;
- a human-readable label;
- an optional description;
- a display order.

Choose **Add group**. Groups are currently flat and ordered. Entries may be
assigned to a group when they are added.

### Add a term

Use the term selector in the draft composition area:

1. Select the Core Terms framework and term.
2. Choose a group or **Ungrouped**.
3. Choose **Include** or **Exclude**.
4. Optionally enter a display label.
5. Enter a display order.
6. Select **Include descendant terms** when the selected term should expand to
   its current active descendants.
7. Choose **Save term to draft**.

The saved reference is the canonical term UUID. Administrators do not need to
copy or invent UUIDs; the selector supplies the canonical value.

### Review and remove entries

The draft entry table shows framework, UUID, group, inclusion, display label,
order, and descendant behavior. Use **Remove** to delete a draft entry. This
does not alter Core Terms and does not alter a published version.

## 6. Preview and validate

Use **Preview draft** to inspect the resolved presentation before publication.
Preview shows the included entries in resolved order. An empty result means no
included entry currently resolves.

The draft page also shows validation state and entry count. Typical outcomes:

- **Valid** — the draft can be published if the remaining publication checks
  pass.
- **Warning** — review the draft carefully before publishing.
- **Invalid** — publication is blocked until the reported problem is fixed.

Common causes of invalid or unexpected results include:

- a term UUID no longer resolves to an active Core Term;
- duplicate or conflicting scopes;
- an exclusion removing an included descendant;
- an unintended display order or group assignment.

## 7. Publish, retire, and restore

### Publish

1. Review the draft entries and preview.
2. Resolve all invalid validation feedback.
3. Choose **Validate / publish draft**.
4. Confirm that the View shows a published version.

Publishing creates an immutable snapshot. Do not edit published rows in place.

### Retire

On a published View, choose **Retire** when it should stop resolving for
consumers while retaining its published data for recovery.

### Restore

On a retired View with a current published version, choose **Restore**. The
published version becomes available again to valid consumers.

## 8. Clone capability

The clone operation exists in the platform service and copies groups and
entries into a new draft. It is not exposed as a complete administrator button
in the current Durable Views page. Do not tell administrators to expect a
browser **Clone** action; use the authoring service only when an approved
engineering procedure calls for it.

## 9. Bind a View to Job Center

Jobs stores only the binding. Durable Views remains responsible for composition,
validation, lifecycle, and resolution.

1. Open the Jobs category mapping URL.
2. Locate the target field, such as `grade_level`.
3. Choose **Edit** for that field.
4. In **Durable View binding**, select a published View/version.
5. Choose **Bind published Durable View**.
6. Confirm the page reports the bound View and version.

Only published versions are offered. Drafts, retired versions, mismatched
Views, and unresolved versions are rejected by the Jobs service.

## 10. Verify, unbind, and restore a Jobs binding

### Verify on the employer form

The employer form requires an authenticated user with an active employer
membership. Authorization is not bypassed for WordPress administrators.

1. Open the employer form URL while signed in as an authorized employer user.
2. Complete the required Basic Info step.
3. Continue to Qualifications.
4. Confirm that the target field displays the bound View's resolved options.

In DV-023, `View 10 / Version 12` resolved the single bound `Grade Level`
option in the authenticated form.

### Remove the binding

1. Return to the Jobs mapping field editor.
2. Choose **Remove Durable View binding**.
3. Confirm the success message, **Mapping updated**.
4. Reload the employer form and repeat the path to Qualifications.

### Confirm legacy fallback

When no valid binding is present, Jobs uses its existing configured option
path. In DV-023, the unbound form displayed the legacy Grade Level children:
`Early Childhood`, `Elementary`, `Middle School`, `High School`, `Adult
Education`, and `Higher Education`.

### Restore the binding

Use the same field editor, select the published View/version again, and choose
**Bind published Durable View**. Confirm the bound View/version is shown, then
reload the employer form and verify the Durable View options again.

## 11. Known limitations and planned enhancements

These are current limitations, not hidden capabilities:

- There is no drag-and-drop ordering interface.
- There is no bulk term operation or advanced term search.
- Group nesting is not implemented. The current group model is flat; nested
  presentation groups are a future enhancement.
- Clone is service-level, not a complete browser action.
- Validation feedback is visible but not yet a full guided diagnostics panel.
- The UI does not provide a complete authoring history or audit viewer.
- Descendant expansion follows the active Core Terms hierarchy; it is not a
  frozen taxonomy snapshot.
- Jobs binding is to a specific published View/version, not an automatically
  advancing “current version” policy.
- The employer form requires active employer membership; administrator status
  alone does not grant employer access.

The forthcoming Views Authoring UX workstream may address these gaps. Until
then, do not document or rely on them as current product behavior.

## 12. Troubleshooting

**The View does not appear in Jobs binding options.**

Confirm that the View is published and not retired. Drafts and unavailable
versions are intentionally excluded.

**The employer form says “No employer access found.”**

The signed-in account has no active employer membership. Ask a Jobs
administrator to use the established membership workflow. Do not bypass the
authorization check.

**The employer form shows old options.**

Check whether the field is unbound. The legacy options are the expected
fallback when no valid Durable View binding exists.

**The View publishes with unexpected results.**

Open the draft preview, inspect inclusion/exclusion, descendant expansion,
group assignment, labels, and order, then correct the draft and publish a new
version.

**A retired View does not resolve.**

That is expected. Restore the published version before testing its consumer.

## 13. Best practices

- Give each View one clear consumer and classification dimension.
- Use canonical Core Terms through the selector; never copy taxonomy into Jobs.
- Prefer descriptive display labels only when the audience needs different
  wording.
- Review descendant expansion whenever Core Terms changes.
- Preview and validate every draft before publishing.
- Treat published versions as immutable release artifacts.
- Test both the bound path and the unbound fallback before a consumer rollout.
- Keep authorization and job behavior in Jobs, not in the View.

## 14. Frequently asked questions

**Is a View the same thing as a Core Terms group?** No. Core Terms describes
what exists; a View describes what a consumer presents.

**Can I edit a published View?** No. Create or edit a draft and publish a new
version.

**Can a View grant employer access?** No. Jobs employer membership controls
authorization.

**What happens if I remove a binding?** Jobs returns to its existing configured
option path, preserving a rollback path.

**Can I nest groups?** Not in the current implementation. Groups are flat.

**Can I use a View for permissions or search?** No. Those remain separate
responsibilities.

## 15. Glossary

- **Core Term** — canonical term owned by Core Terms.
- **View** — stable presentation identity.
- **Version** — draft or immutable published snapshot of a View.
- **Draft** — editable version not available to consumers.
- **Published** — validated version available to valid consumers.
- **Retired** — published View intentionally unavailable to consumers.
- **Restore** — reactivation of the retained published version.
- **Entry** — one Core Terms reference and presentation settings.
- **Group** — flat ordered presentation section for entries.
- **Include** — include the selected term in resolution.
- **Exclude** — suppress the selected term or descendant scope.
- **Include descendants** — expand a selected term through active canonical
  descendants.
- **Display label** — optional consumer-facing label override.
- **Binding** — Jobs-owned association between a form field and one published
  View/version.
- **Legacy fallback** — existing Jobs-owned option behavior used when a valid
  Durable View binding is absent or cannot resolve.
