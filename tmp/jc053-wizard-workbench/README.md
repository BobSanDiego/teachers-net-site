# JC053 Job Posting Wizard Workbench

Static, non-production design workbench for the JC053 Step 1 School / Jobsite
calibration view. It does not use WordPress, the Jobs plugin, a database, or
production services.

## Open

From the repository root, serve this directory with any local static server:

```bash
python3 -m http.server 8766 --bind 127.0.0.1 --directory tmp/jc053-wizard-workbench
```

Open `http://127.0.0.1:8766/#step-01-nav-back`. Use the Workbench Views select
or the Previous/Next links; do not manually construct view URLs. The stable
implemented view id is `step-01-nav-back`.

## Views

Registered view ids are:

`step-01-first-touch`, `step-01-school-selected`, `step-01-nav-back`,
`step-01-add-physical-us`, `step-01-add-international`,
`step-01-add-multiple-locations`, `step-01-add-additional-info`,
`step-02-job-basics`, `step-03-job-description`, `step-04-application-process`,
and `step-05-review-publish`.

Only `step-01-nav-back` is implemented. The remaining views are disabled
placeholders and must not be mistaken for authority or production routes.

## Adding future states

Reuse the persistent shell in `index.html`. Add a view id to the `views` array
in `mockup.js`, then add only the state-specific markup needed for that view.
Keep shell geometry and shared tokens in `mockup.css`; do not duplicate the
navbar, rail, footer, or stepper.

## Calibration and authority workflow

Central tokens are at the top of `mockup.css`, including the 1200px shell,
250px rail, 950px workspace, spacing, controls, and colors. Diagnostics are
hidden by default. Click **Show diagnostics** to display measured bounding boxes,
viewport, active view id, and horizontal overflow; hide them before any review
screenshot.

At 1440 × 1000, confirm `.application-card`, `.left-rail`, and
`.main-workspace` measure 1200px, 250px, and 950px respectively. This workbench
is supporting design evidence only and does not replace JC-051A or any approved
visual authority.
