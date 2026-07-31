# Employer Workspace Flow Authority v1

**Ticket:** JC052-DESIGN001  
**Status:** UX authority for design convergence; implementation not authorized

## Purpose and boundaries

This document defines the Employer Workspace navigation flow and the
relationship among employer identity, organizations, Schools / Job Sites, job
work locations, and public search. It is subordinate to the JC-051A desktop
authority for the existing My Jobs shell and to the Job Finder Search Contract
for public search behavior.

It does not authorize PHP, CSS, JavaScript, schema, migration, responsive
redesign, browser QA, JC053, or JC057 work.

## Workspace navigation

The authenticated Employer Workspace uses one shared shell and one active
navigation selection at a time:

1. **My Jobs** — aggregate inventory across the current employer scope.
2. **My Schools / Job Sites** — management index of authorized Schools / Job
   Sites.
3. **Add School / Job Site** — create one new School / Job Site in the current
   employer context.
4. **Manage Schools / Job Sites** — management index and edit entry point for
   existing Schools / Job Sites.

All My Jobs and a selected School / Job Site are mutually exclusive views. The
selected employer context and authorization are preserved across the flow;
unauthorized memberships or records are never presented as active choices.

## Screen transitions and return behavior

### My Jobs → School / Job Site management

From My Jobs, selecting **My Schools / Job Sites** opens the management index.
Selecting a School / Job Site opens its management/detail state. Selecting
**Manage Schools / Job Sites** opens the same management index with management
actions available. The index must show the current employer context and the
authorized School / Job Site inventory.

### Management index → Add

Selecting **Add School / Job Site** opens the create flow in the current
employer context. The create flow must identify the record being created as a
School / Job Site and must not silently create a second employer identity.

### Management index → Edit

Selecting an existing School / Job Site and then Edit opens the edit flow for
that record. Edit preserves the record identity and current employer
relationship. It must not silently convert an organization location into a job
listing location or vice versa.

### Cancel and Back

- **Cancel** abandons uncommitted changes and returns to the immediately prior
  management context.
- **Back** returns to the prior stable screen without mutation: Add returns to
  the management index; Edit returns to the record’s management/detail state.
- Browser Back and forward navigation must preserve the selected employer and
  selected School / Job Site context where the route supports it.
- Leaving a form with unsaved changes requires the existing confirmation
  treatment before discarding work.

### Successful save

After a successful Add or Edit, the flow returns to the management context and
shows the saved School / Job Site. The saved record may be selected for the
next action, but the flow must not imply that a job listing was created.

The exact post-save focus, toast wording, and whether a newly created
School / Job Site immediately opens its detail state remain presentation
decisions for Engineering Director review.

### Management → My Jobs

Selecting **My Jobs** returns to the aggregate inventory. Selecting a specific
School / Job Site from the management index may open its single-scope inventory
with a visible **Back to All My Jobs** path. A single-scope view must not also
highlight All My Jobs.

## Progressive disclosure

The base management flow should collect only the information needed to create a
truthful, usable School / Job Site. Advanced or lower-frequency information is
collapsed until requested. Progressive disclosure must not hide a required
permission, location type, or save consequence.

For location and imagery, the base path should expose the primary choice and
the minimum required fields; advanced sections may contain Additional
Information, Jobsite Image, optional contact details, and other enrichment.
Validation remains adjacent to the relevant section and preserves entered work.

## Location model

Use the approved adaptive location model:

- **Physical U.S.** — collect ZIP first; automatic lookup supplies City and
  State. No explicit lookup button is required.
- **International** — collect the country and the location fields appropriate
  to that country; do not force U.S.-specific ZIP/State assumptions.
- **Multiple Locations** — represent more than one valid location without
  pretending that one location is the only work location.
- **Remote** — use the approved Work Location concept for a job with no public
  physical work location. “Virtual / No Public Physical Location” is not a
  separate approved public state in this authority.

The precise international field set, multiple-location editing pattern, and
remote eligibility scope remain future audit subjects. Remote availability is
not assumed to mean nationwide availability.

## Separate location concepts

Keep these concepts distinct:

- **Organization Location** — where an employer organization is represented or
  administered; it belongs to employer/org management.
- **Job Work Location** — where a particular listing is performed or whether it
  is Remote, Hybrid, or otherwise location-qualified; it belongs to the job
  authoring contract.
- **Search behavior** — a public user's Work Location filter and, separately,
  typed geographic origin/radius and distance sorting. Search must follow
  `docs/job-center/job-finder-search-contract-v1.md`; it must not reuse an
  organization address as a user's search origin without an explicit product
  rule.

## Imagery behavior

School / Job Site imagery follows three truthful states:

1. An uploaded image is displayed when supplied and valid.
2. When no image is supplied, Teachers.Net supplies the approved default image.
3. Omission is allowed; the default is the rendered result rather than a
   required employer upload.

Image recommendations may encourage useful imagery but must not block creation
or publication solely because an image is absent. Image replacement, removal,
cropping, and permission rules remain subject to the later capability/schema
audit.

## Unresolved decisions requiring approval

- Exact management-index versus record-detail screen composition.
- Whether Add success immediately opens the new record or returns only to the
  index.
- Whether Cancel and Browser Back preserve an in-progress draft or require an
  explicit discard confirmation in every form state.
- Exact international location fields and validation rules.
- Multiple Locations data-entry, ordering, display, and removal behavior.
- Remote eligibility fields and state-restricted remote presentation.
- Organization default location/contact inheritance into a job and the exact
  override affordance.
- Image upload limits, crop behavior, removal permissions, and default-image
  selection.
- Whether a School / Job Site may be shared across authorized organizations and
  how that relationship is presented.

These questions are bounded inputs to JC057 and later implementation planning;
they do not block the current authority from defining the flow boundaries above.

## Authority relationships

- JC-051A governs the current All My Jobs desktop shell.
- `docs/job-center/employer-ux-v1.md` governs the Employer Workspace product
  model and capability language.
- `docs/job-center/employer-authoring-authority-contract-v1.md` governs the
  shared Create/Edit job-authoring flow and remains separate from this
  School / Job Site management flow.
- `docs/job-center/job-finder-search-contract-v1.md` governs public Work
  Location, remote inclusion, and distance-sort behavior.
- JC053 will define the re-converged Job Posting Wizard; it must consume this
  flow authority rather than redefine employer context or location ownership.
