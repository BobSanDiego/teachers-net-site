# JC-051A - Employer My Jobs Desktop Authority v1.0

Status: Approved
Approval date: 2026-07-23
Approval authority: Engineering Director, JC-051A
Scope: Authenticated Employer My Jobs desktop visual authority

## Canonical artifact

- Authority name: Employer My Jobs Desktop Authority v1.0
- Source artifact: jc-050-unicard-002.png
- Controlled artifact: docs/job-center/design/approved/jc-051a-employer-my-jobs-desktop-v1.0.png
- Dimensions: 1240 x 827 source raster
- SHA-256: e142787148a881502f2e44e643ef34b375daf8d4e3208e2b43dbcbb602249702

The controlled artifact is the sole current visual authority for the authenticated
Employer My Jobs desktop composition. It supersedes earlier JC-050 concepts,
recompositions, v1.0/v1.1 authorities, and replacement candidates for this
visual boundary. Historical artifacts remain preserved and are not deleted.

## Approved geometry and composition

- Canonical normalized application card: 1200px
- Employer rail: 250px, measured to the actual rail/workspace surface divider
- Main workspace: 950px
- Workspace padding begins after the rail divider
- Approved shell includes the header, Employer rail, My Jobs workspace, inventory,
  pagination, and integrated footer

## Approved visual decisions

- Header navigation: Job Center; My Jobs; Career Resources; Community; Teacher Resources
- Right-side header controls: notifications and My Account; no school identity treatment
- Workspace title: My Jobs with a subtle context caret
- Primary workspace action: Post a Job
- Rail: My Jobs, Post a Job, Schools / Jobsites, Account Settings, Archived Jobs,
  and the promotional hiring card
- Filters: All Jobs, Live, Drafts, In Review, Closed, Expired
- Lifecycle terminology: Published -> Live; Awaiting Review -> In Review
- Live uses restrained positive treatment; In Review is restrained amber text;
  Draft and Closed are plain neutral text; Expired is plain red text
- Expires shows only valid absolute dates or an em dash; no redundant relative-time
  or repeated status text
- Rows expose a valid visible primary action and aligned overflow control
- Continue receives light-blue attention treatment; secondary actions remain quieter
- The footer remains integrated with the application shell

## Implementation relationship

This raster governs visual composition only. Existing lifecycle semantics,
authorization, accessibility, routing, services, and data behavior remain
authoritative. The deterministic HTML workbench remains supporting evidence for
exact geometry, typography, long-content stress behavior, lifecycle fixtures,
accessibility proofing, and implementation mapping. Any discrepancy between the
authority image and supporting evidence must be reported before implementation.

## Superseded visual artifacts

The following remain historical evidence and are superseded for visual authority
purposes: prior JC-050 v1.0 and v1.1 authority copies, jc-050-approved-candidate-01a.png,
jc-050-final-01a.png, and intermediate Employer My Jobs recompositions.
