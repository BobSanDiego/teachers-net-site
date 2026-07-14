# Job Center UX Atlas v1

The UX Atlas is the stable product map for governed Job Center screens and
components. It explains purpose, connection, and governance status without
specifying design or implementation. The Visual Manifest remains authoritative
for artifact status and filenames.

## Product Journey

```text
Discovery → Search → Job Detail → Apply
                         │
                         └→ Saved Jobs → Alerts

Employer Identity → Dashboard → My Jobs → Post Job → Review → Preview → Publish
```

Approved search/discovery transitions:

```text
JC-010 → JC-014 → JC-011
JC-010 → JC-015 → JC-011
```

## Approved Authority Index

| ID | Screen / Component | Desktop | Tablet | Mobile |
|---|---|---|---|---|
| JC-010 | Job Finder State 1 | `jc-010-job-finder-state-1-desktop-v1.1.png` | `jc-010-job-finder-state-1-tablet-v1.0.png` | `jc-010-job-finder-state-1-mobile-v1.0.png` |
| JC-011 | Job Finder State 2 | `jc-011-job-finder-state-2-desktop-v1.0.png` | `jc-011-job-finder-state-2-tablet-v1.0.png` | `jc-011-job-finder-state-2-mobile-v1.0.png` |
| JC-014 | Location Selection Modal | `jc-014-location-selection-modal-desktop-v1.0.png` | `jc-014-location-selection-modal-tablet-v1.0.png` | `jc-014-location-selection-modal-mobile-v1.0.png` |
| JC-015 | Browse Reveal | `jc-015-browse-reveal-desktop-v1.0.png` | `jc-015-browse-reveal-tablet-v1.0.png` | — |
| JC-030 | Job Detail | `jc-030-job-detail-desktop-v1.0.png` | — | — |
| JC-003 | Mobile Navigation Drawer (Logged Out) | — | — | `jc-003-mobile-navigation-drawer-logged-out-v1.0.png` |
| JC-004 | Mobile Navigation Drawer (Logged In) | — | — | `jc-004-mobile-navigation-drawer-logged-in-v1.0.png` |

Superseded artifact-lineage identifiers JC-090–JC-093 are not product surfaces
and are intentionally excluded. Their history remains in the Visual Manifest.

---

### Identifier
JC-001
### Screen Name
Approved Page Shell
### Purpose
Provide the shared Job Center frame.
### Primary User Goal
Orient and move through Job Center pages consistently.
### Primary User
All users
### Status
Draft
### Authority
Written shell rules in Job Center Design System v1; no approved artifact.
### Preceded By
Teachers.Net navigation
### Leads To
All Job Center destinations
### Dependencies
Teachers.Net design system and navigation
### Current State
Draft
### Next Expected Work
Govern an exact shell reference if separate approval is needed.
### Notes
Approved page states do not independently approve the shell component.

---

### Identifier
JC-002
### Screen Name
Job Center Landing
### Purpose
Introduce Job Center discovery choices.
### Primary User Goal
Begin finding a job or employer task.
### Primary User
Visitors and returning users
### Status
Draft
### Authority
Landing artifacts are candidates only.
### Preceded By
Teachers.Net navigation
### Leads To
JC-010 or employer entry
### Dependencies
JC-001, JC-080
### Current State
Draft
### Next Expected Work
Govern the landing reference and its entry paths.
### Notes
Filename recency does not establish approval.

---

### Identifier
JC-003
### Screen Name
Mobile Navigation Drawer (Logged Out)
### Purpose
Provide the approved logged-out mobile navigation drawer component.
### Primary User Goal
Navigate Job Center destinations or begin account acquisition.
### Primary User
Logged-out mobile user
### Status
Approved
### Authority
`docs/job-center/design/approved/jc-003-mobile-navigation-drawer-logged-out-v1.0.png`
### Current State
Approved mobile component reference, v1.0.
### Next Expected Work
Use only for an explicitly approved Patch Mode delta or bounded implementation.
### Notes
Component authority only; it does not independently approve an underlying page.

---

### Identifier
JC-004
### Screen Name
Mobile Navigation Drawer (Logged In)
### Purpose
Provide the approved logged-in mobile navigation drawer component.
### Primary User Goal
Navigate Job Center destinations and account actions.
### Primary User
Logged-in mobile user
### Status
Approved
### Authority
`docs/job-center/design/approved/jc-004-mobile-navigation-drawer-logged-in-v1.0.png`
### Current State
Approved mobile component reference, v1.0.
### Next Expected Work
Use only for an explicitly approved Patch Mode delta or bounded implementation.
### Notes
Component authority only; it does not independently approve an underlying page.

---

### Identifier
JC-010
### Screen Name
Job Finder State 1
### Purpose
Provide first-touch job discovery before search intent is expressed.
### Primary User Goal
Search, choose location, browse, or inspect recent jobs.
### Primary User
Logged-out job seeker
### Status
Approved
### Authority
Desktop: `docs/job-center/design/approved/jc-010-job-finder-state-1-desktop-v1.1.png`.
Tablet: `docs/job-center/design/approved/jc-010-job-finder-state-1-tablet-v1.0.png`.
Mobile: `docs/job-center/design/approved/jc-010-job-finder-state-1-mobile-v1.0.png`.
### Preceded By
JC-002 or direct `/jobs/` entry
### Leads To
JC-014, JC-015, or JC-011
### Dependencies
JC-020–JC-024 within the approved screen scope
### Current State
Approved desktop v1.1, portrait-tablet v1.0, and mobile v1.0 logged-out
first-touch references. Tablet and mobile are Patch Mode presentation authority.
### Next Expected Work
Use the exact authority for its approved viewport and state; implementation
requires a separate bounded ticket.
### Notes
Search Jobs remains primary; Browse and Refine are progressive disclosure.
Desktop remains product/content authority. DOC008 approves only the bounded
portrait-tablet presentation and does not extend responsive authority to other
screens.

---

### Identifier
JC-011
### Screen Name
Job Finder State 2
### Purpose
Present search results with active query context.
### Primary User Goal
Evaluate and refine relevant jobs.
### Primary User
Job seeker
### Status
Approved
### Authority
Desktop: `docs/job-center/design/approved/jc-011-job-finder-state-2-desktop-v1.0.png`.
Tablet: `docs/job-center/design/approved/jc-011-job-finder-state-2-tablet-v1.0.png`.
Mobile: `docs/job-center/design/approved/jc-011-job-finder-state-2-mobile-v1.0.png`.
### Preceded By
JC-010, JC-014, JC-015, or JC-013
### Leads To
JC-030, JC-040, or another results page
### Dependencies
JC-020–JC-024
### Current State
Approved desktop v1.0 search-results reference, portrait-tablet v1.0
responsive authority, and mobile v1.0 responsive authority. Tablet and mobile
are Patch Mode presentation authority.
### Next Expected Work
Use the exact authority for its approved viewport and state; implementation
requires a separate bounded ticket.
### Notes
Desktop remains product/content authority. DOC011 approves only the bounded
portrait-tablet presentation. RESP-DEC002 governs the JC-011 Mobile-only
support-content exception. DOC012 approves only the bounded mobile presentation
and does not approve implementation or other screens. The JC-011 Mobile
authority is the original 360 × 975 Engineering Director-edited raster; its
native-resolution limitation does not authorize derivative replacement.

---

### Identifier
JC-012
### Screen Name
Job Finder State 3
### Purpose
Represent a later Finder interaction state once defined.
### Primary User Goal
Continue a governed search interaction.
### Primary User
Job seeker
### Status
Placeholder
### Authority
No approved visual authority exists for this state.
### Preceded By
JC-011
### Leads To
JC-011 or JC-030
### Dependencies
State definition and JC-021
### Current State
Placeholder; boundary unresolved.
### Next Expected Work
Govern the product meaning before visual work.
### Notes
No functionality is implied by this placeholder.

---

### Identifier
JC-013
### Screen Name
Expert Search
### Purpose
Expose advanced search capability when basic discovery is insufficient.
### Primary User Goal
Build a more precise job query.
### Primary User
Job seeker with specific criteria
### Status
Placeholder
### Authority
No approved implementation authority yet exists.
### Preceded By
JC-010 or JC-011
### Leads To
JC-011
### Dependencies
JC-021 and supported search contract
### Current State
Placeholder; panel explorations are Draft evidence only.
### Next Expected Work
Govern scope and relationship to progressive disclosure.
### Notes
It is not a separate results product.

---

### Identifier
JC-014
### Screen Name
Location Selection Modal
### Purpose
Capture a valid search origin and practical radius.
### Primary User Goal
Set where to search for jobs.
### Primary User
Job seeker
### Status
Approved
### Authority
Desktop: `docs/job-center/design/approved/jc-014-location-selection-modal-desktop-v1.0.png`.
Tablet: `docs/job-center/design/approved/jc-014-location-selection-modal-tablet-v1.0.png`.
Mobile: `docs/job-center/design/approved/jc-014-location-selection-modal-mobile-v1.0.png`.
### Preceded By
JC-010
### Leads To
JC-011 or back to JC-010
### Dependencies
JC-010 and supported location behavior
### Current State
Approved desktop logged-out interaction state, portrait-tablet v1.0 responsive
authority, and mobile v1.0 responsive authority. Tablet and mobile are Patch
Mode presentation authority.
### Next Expected Work
Use the exact authority for its approved viewport and state; implementation
requires a separate bounded ticket.
### Notes
Desktop remains product/content authority. DOC013 approves only the bounded
portrait-tablet presentation. DOC014 approves only the bounded mobile localized
overlay presentation and does not approve implementation or other screens.

---

### Identifier
JC-015
### Screen Name
Browse Reveal
### Purpose
Reveal grade and subject exploration inline.
### Primary User Goal
Explore jobs without entering a keyword.
### Primary User
Job seeker
### Status
Approved
### Authority
Desktop: `docs/job-center/design/approved/jc-015-browse-reveal-desktop-v1.0.png`.

Tablet: `docs/job-center/design/approved/jc-015-browse-reveal-tablet-v1.0.png`.
### Preceded By
JC-010
### Leads To
JC-011 or back to JC-010
### Dependencies
JC-010 and governed classification
### Current State
Approved desktop browse interaction state and portrait-tablet responsive
authority. Tablet is Patch Mode.
### Next Expected Work
Use as authority for bounded implementation or QA. Future JC-015 Tablet visual
work requires a separately approved Patch Mode delta.
### Notes
Inherits JC-010 and changes only the Browse disclosure. DOC015 approves only the
bounded portrait-tablet presentation and does not approve JC-015 Mobile or
implementation.

---

### Identifier
JC-020
### Screen Name
Canonical Listing
### Purpose
Summarize one job as a professional directory entry.
### Primary User Goal
Judge relevance quickly and open the job.
### Primary User
Job seeker
### Status
Draft
### Authority
Written composition rules govern; no separately approved listing artifact.
### Preceded By
Results assembly
### Leads To
JC-030 or JC-040
### Dependencies
Canonical job model and Job Center Design System v1
### Current State
Draft visual authority within approved full-page contexts only.
### Next Expected Work
Decide whether separate component approval is required.
### Notes
Narrative and compact secondary zones remain one directory entry.

---

### Identifier
JC-021
### Screen Name
Search Panels
### Purpose
Control basic, refined, and advanced search intent.
### Primary User Goal
Change query criteria without leaving the Finder.
### Primary User
Job seeker
### Status
Draft
### Authority
Panel artifacts exist without approval evidence.
### Preceded By
JC-010 or JC-011
### Leads To
JC-011
### Dependencies
Search contract and progressive-disclosure rules
### Current State
Draft
### Next Expected Work
Govern any panel state not covered by approved Finder references.
### Notes
Search and Browse share one results model.

---

### Identifier
JC-022
### Screen Name
Pagination / Result Count
### Purpose
Communicate inventory range and support results traversal.
### Primary User Goal
Understand result volume and move between pages.
### Primary User
Job seeker
### Status
Draft
### Authority
Written rules and candidate full-page evidence; no component approval.
### Preceded By
JC-011 results
### Leads To
Another JC-011 results page
### Dependencies
Result query and count accuracy
### Current State
Draft
### Next Expected Work
Validate against approved Finder implementation work.
### Notes
Count and pagination must describe the same result set.

---

### Identifier
JC-023
### Screen Name
Right Rail
### Purpose
Provide secondary conversion, utility, and advertising content.
### Primary User Goal
Access useful secondary actions without losing results context.
### Primary User
Job seeker
### Status
Draft
### Authority
Candidate results-page evidence; no separately approved rail artifact.
### Preceded By
JC-010 or JC-011 assembly
### Leads To
Secondary Job Center destinations
### Dependencies
JC-024 and page-state context
### Current State
Draft
### Next Expected Work
Govern rail composition only if separate authority is required.
### Notes
The rail remains subordinate to the primary task.

---

### Identifier
JC-024
### Screen Name
Advertising
### Purpose
Reserve intentional advertising placements in the layout.
### Primary User Goal
Continue the page task without layout disruption.
### Primary User
All users
### Status
Placeholder
### Authority
No approved implementation authority yet exists; written dimensions govern.
### Preceded By
Page composition
### Leads To
Advertiser destination when populated
### Dependencies
300 × 250 rail and 728 × 90 leaderboard reservations
### Current State
Placeholder for separately governed placement authority.
### Next Expected Work
Approve only if a standalone placement reference is needed.
### Notes
Sample creative is illustrative, not approved.

---

### Identifier
JC-030
### Screen Name
Job Detail
### Purpose
Convert an interested teacher into an applicant.
### Primary User Goal
Present complete job information with maximum clarity and trust while maximizing application conversion.
### Primary User
Job seeker
### Status
Approved
### Authority
`docs/job-center/design/approved/jc-030-job-detail-desktop-v1.0.png`
### Preceded By
JC-011, JC-020, or JC-040
### Leads To
External Apply or JC-040
### Dependencies
Canonical job contract, JC-030 product definition and UX specification, and application destination integrity
### Current State
Approved desktop visual authority; responsive and implementation authority
remain unresolved.
### Next Expected Work
Audit the current implementation against the complete approved authority set.
### Notes
The canonical raster is
`docs/job-center/design/approved/jc-030-job-detail-desktop-v1.0.png`. Editable
source is unavailable; this does not authorize visual reinterpretation.

---

### Identifier
JC-040
### Screen Name
Saved Jobs
### Purpose
Keep selected jobs available for later consideration.
### Primary User Goal
Review and manage saved opportunities.
### Primary User
Authenticated job seeker
### Status
Placeholder
### Authority
No approved implementation authority yet exists.
### Preceded By
JC-011 or JC-030
### Leads To
JC-030 or JC-041
### Dependencies
Login transition and Jobs engagement model
### Current State
Placeholder
### Next Expected Work
Govern empty, populated, removal, and signed-out states.
### Notes
Saved is an engagement signal, not an application.

---

### Identifier
JC-041
### Screen Name
Alerts
### Purpose
Let job seekers manage recurring job-discovery criteria.
### Primary User Goal
Receive relevant future job matches.
### Primary User
Authenticated job seeker
### Status
Placeholder
### Authority
No approved implementation authority yet exists.
### Preceded By
JC-011 or JC-040
### Leads To
JC-011 or JC-030 from a matched alert
### Dependencies
Supported search criteria and alert ownership
### Current State
Placeholder
### Next Expected Work
Govern creation, management, and state vocabulary.
### Notes
Alerts are not a general notification center.

---

### Identifier
JC-050
### Screen Name
Employer Dashboard
### Purpose
Summarize employer activity and priorities.
### Primary User Goal
Understand current employer status and next actions.
### Primary User
Authorized employer user
### Status
Placeholder
### Authority
Employer UX V1 governs purpose; no approved implementation authority exists.
### Preceded By
JC-053 or employer navigation
### Leads To
JC-051, JC-052, or JC-056
### Dependencies
Employer identity, authorization, and lifecycle state
### Current State
Placeholder
### Next Expected Work
Govern the employer visual workstream.
### Notes
Dashboard summarizes; My Jobs manages.

---

### Identifier
JC-051
### Screen Name
Employer My Jobs
### Purpose
Manage the employer's canonical job inventory.
### Primary User Goal
Find a job and take the correct lifecycle action.
### Primary User
Authorized employer user
### Status
Placeholder
### Authority
Employer UX V1 governs purpose; no approved implementation authority exists.
### Preceded By
JC-050
### Leads To
JC-052, JC-055, or lifecycle actions
### Dependencies
Employer scope and canonical status vocabulary
### Current State
Placeholder
### Next Expected Work
Govern inventory, status, action, and lifecycle visibility.
### Notes
My Jobs manages and does not duplicate the Dashboard.

---

### Identifier
JC-052
### Screen Name
Employer Wizard
### Purpose
Create or edit one canonical job.
### Primary User Goal
Provide complete, valid job information efficiently.
### Primary User
Authorized employer user
### Status
Placeholder
### Authority
Employer UX V1 governs interaction principles; no approved implementation authority exists.
### Preceded By
JC-050 or JC-051
### Leads To
JC-054
### Dependencies
Canonical job contract and employer authorization
### Current State
Placeholder
### Next Expected Work
Govern shared Create/Edit interaction patterns.
### Notes
Exact steps and layouts remain unresolved.

---

### Identifier
JC-053
### Screen Name
Employer Claim
### Purpose
Connect a user to an existing or requested employer identity.
### Primary User Goal
Establish verified authority to act for an employer.
### Primary User
Prospective employer user
### Status
Placeholder
### Authority
Employer UX V1 governs lifecycle placement; no approved implementation authority exists.
### Preceded By
Login or registration
### Leads To
JC-050
### Dependencies
WordPress identity and employer authority verification
### Current State
Placeholder
### Next Expected Work
Govern claim, request, pending, denial, and switching states.
### Notes
Claim Existing Employer and Request New Employer are distinct routes.

---

### Identifier
JC-054
### Screen Name
Employer Review
### Purpose
Validate job data and submission readiness.
### Primary User Goal
Correct issues before submission.
### Primary User
Authorized employer user
### Status
Placeholder
### Authority
Employer UX V1 defines Review; no approved implementation authority exists.
### Preceded By
JC-052
### Leads To
JC-055 or submission
### Dependencies
Validation and canonical job contract
### Current State
Placeholder
### Next Expected Work
Govern review behavior and messaging.
### Notes
Review is not Preview.

---

### Identifier
JC-055
### Screen Name
Employer Preview
### Purpose
Show the job through the public presentation model before publication.
### Primary User Goal
Confirm how the job will appear to job seekers.
### Primary User
Authorized employer user
### Status
Placeholder
### Authority
Employer UX V1 defines Preview; no approved implementation authority exists.
### Preceded By
JC-054 or JC-051
### Leads To
Publish/Submit or back to JC-052
### Dependencies
JC-030 presentation model and canonical job data
### Current State
Placeholder
### Next Expected Work
Govern preview purpose and state transitions.
### Notes
Preview does not imply publication.

---

### Identifier
JC-056
### Screen Name
Employer Metrics
### Purpose
Report job engagement consistently.
### Primary User Goal
Understand how published jobs are performing.
### Primary User
Authorized employer user
### Status
Placeholder
### Authority
Employer UX V1 governs the metrics model; no approved implementation authority exists.
### Preceded By
JC-050 or JC-051
### Leads To
JC-051 or JC-030
### Dependencies
Views, Saved, and Interested definitions and time context
### Current State
Placeholder
### Next Expected Work
Govern metric presentation and interpretation.
### Notes
Metrics must not imply unsupported candidate tracking.

---

### Identifier
JC-060
### Screen Name
Moderator
### Purpose
Support governed job review and lifecycle intervention.
### Primary User Goal
Resolve moderation work accurately.
### Primary User
Moderator
### Status
Placeholder
### Authority
No approved implementation authority yet exists.
### Preceded By
Moderator navigation or submitted job
### Leads To
Publication, return, closure, or moderation queue
### Dependencies
Canonical lifecycle, permissions, and moderation policy
### Current State
Placeholder
### Next Expected Work
Govern moderator scope and states.
### Notes
No new moderation capability is implied.

---

### Identifier
JC-061
### Screen Name
Administration
### Purpose
Provide authorized Job Center operational controls.
### Primary User Goal
Administer supported Job Center operations safely.
### Primary User
Administrator
### Status
Placeholder
### Authority
No approved implementation authority yet exists.
### Preceded By
Administration navigation
### Leads To
Supported administrative workflows
### Dependencies
WordPress capabilities and Jobs-owned services
### Current State
Placeholder
### Next Expected Work
Govern administrative boundaries before visual work.
### Notes
The Atlas does not define new administrative functions.

---

### Identifier
JC-070
### Screen Name
Responsive System
### Purpose
Adapt governed Job Center experiences across viewports.
### Primary User Goal
Complete the same task on an appropriate device layout.
### Primary User
All users
### Status
Draft
### Authority
Job Center Shared Responsive Decisions v1, plus the bounded Approved JC-010
mobile and portrait-tablet references. Other responsive screenshots are
candidates only.
### Preceded By
An approved desktop screen or component
### Leads To
The same product destination at another viewport
### Dependencies
Approved source state and responsive design rules
### Current State
Draft except for the bounded JC-010 Mobile v1.0 and JC-010 Tablet v1.0
presentation authorities.
### Next Expected Work
Govern remaining responsive adaptations after the bounded JC-010 approvals.
### Notes
Desktop approval does not imply responsive approval. DOC008 approves only
JC-010 portrait-tablet presentation and does not establish other screens,
breakpoints, or implementation.

---

### Identifier
JC-080
### Screen Name
Landing Hero
### Purpose
Provide the visual introduction to the Job Center landing experience.
### Primary User Goal
Recognize the Job Center and its purpose immediately.
### Primary User
Job Center visitor
### Status
Draft
### Authority
Current assets are implementation evidence, not approved visual authority.
### Preceded By
Teachers.Net navigation
### Leads To
JC-002 discovery controls
### Dependencies
JC-002 and shared hero rules
### Current State
Draft
### Next Expected Work
Govern the asset and crop with the landing reference.
### Notes
Repository presence does not confer approval.

## Upcoming UX Workstreams

1. JC-030 — Job Detail
2. Employer UX
3. Saved Jobs / Alerts
4. Responsive Adaptations
