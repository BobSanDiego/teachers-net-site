# Durable Views User Manual

## What a View does

A Durable View answers one simple question: **which approved terms should this
audience see, and in what order?**

Core Terms remains the source of what terms exist. Durable Views controls the
audience-facing selection, ordering, grouping, labels, and visibility. Job
Center owns job records and job assignments; it does not own the View.

## Who can manage Views

Only WordPress administrators with the `manage_options` capability can create,
publish, retire, or restore a View.

## Open the View manager

1. Sign in to WordPress as an administrator.
2. Open **Core Terms** in the WordPress admin menu.
3. Choose **Durable Views**.

The page shows the current Views, their status, their current published
version, and available lifecycle actions.

## Create a Job Listing View

Before creating one, decide what one classification dimension the View serves.
Good first examples are:

- `Job Listing — Subject Area`
- `Job Listing — Grade Level`
- `Job Listing — Work Location`

Avoid combining unrelated dimensions into one View. A View should be easy for
the consuming product to understand and safe to replace independently.

Then:

1. In **Durable Views**, find **Create View**.
2. Enter a clear name, such as `Job Listing — Subject Area`.
3. Add a description explaining where the View is used and what it contains.
4. Select **Create draft**.

The system creates a stable View identity and a new draft version. A draft is
not visible to consumers until it is published.

## Choosing content

View entries must refer to canonical Core Terms UUIDs. Do not type or invent a
slug, label, numeric ID, or replacement taxonomy as the View reference.

For each entry, decide:

- **Include** — show the selected canonical term.
- **Exclude** — suppress the selected term from the resolved model.
- **Include descendants** — include the selected term and its current canonical
  descendants.
- **Display label** — optionally provide an audience-facing label without
  changing the Core Terms name.
- **Display order** — determine the presentation order.
- **Hidden** — retain the entry as configured data while omitting it from the
  visible result.

For a Job Listing View, keep product behavior out of the View. Required versus
optional fields, single versus multiple selection, job authorization, search,
and job assignment rules remain Jobs responsibilities.

## Validate and publish

Before publishing:

1. Confirm every entry points to an existing active Core Terms UUID.
2. Check ordering and labels from the intended audience’s perspective.
3. Confirm exclusions are intentional.
4. Confirm the View contains only one coherent classification dimension.
5. Use **Validate / publish draft**.

Invalid references prevent publication. A published version is immutable. To
make a change, create a new draft version; do not edit the published snapshot.

## Job Center binding

After a View is published, a Job Center administrator can bind a form field to
one published View and version. Jobs stores only that binding. The resolved
presentation comes from the platform service, so Jobs does not copy or rebuild
the View.

The existing Jobs option path remains available during migration. If a binding
is removed or becomes unavailable, Jobs can return to its existing configured
option behavior.

## Retire and restore

- **Retire** stops the View from resolving for consumers while preserving the
  published version as a recovery target.
- **Restore** reactivates the selected published version.

Use retirement when a View should temporarily stop serving consumers. Use a new
draft version for content changes.

## Current interface limitation

The current admin page exposes View creation and lifecycle controls, but it does
not yet provide a complete visual editor for browsing Core Terms, adding and
ordering entries, or creating groups. The underlying protected authoring and
resolution services exist, but the full user-facing composition workflow is the
next usability improvement.

Until that editor is delivered, do not treat the current page as a complete
self-service Job Listing View builder. The safe workflow is to create the View
draft, use the approved platform authoring path to compose its entries, validate,
and publish only after the resolved model has been reviewed.

## Quick glossary

- **View** — stable audience-facing presentation identity.
- **Version** — immutable published snapshot or editable draft of a View.
- **Entry** — one canonical Core Terms reference plus presentation settings.
- **Group** — an ordered presentation section for entries.
- **Draft** — editable, not consumer-visible.
- **Published** — validated and consumer-available.
- **Retired** — temporarily unavailable while preserving recovery state.
