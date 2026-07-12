# JC-030 — Job Detail Product Definition

**Status:** Approved product definition; JC-030 visual status remains Placeholder
**Scope:** Product truth only

## Purpose

Convert an interested teacher into an applicant.

## Primary Goal

Present complete job information with maximum clarity and trust while
maximizing application conversion.

## Product Role

Job Detail sits after discovery and search and before the user leaves for an
external application path or returns through Saved Jobs. It satisfies the need
to understand one opportunity well enough to make an informed application
decision.

Job Detail is responsible for:

- presenting the canonical public facts for one job;
- communicating whether the job is currently actionable;
- providing a truthful, usable application path;
- supporting governed save and engagement behavior; and
- keeping visible job, employer, lifecycle, and application facts consistent
  with the Finder and other public systems.

Adjacent systems retain their own responsibilities:

- the Job Finder owns discovery, query, filters, sort, result count, and
  pagination;
- WordPress owns registration, authentication, password recovery, and login
  return;
- Saved Jobs owns the saved-job collection;
- Alerts owns recurring discovery criteria and delivery;
- the employer or external destination owns the application unless Teachers.Net
  explicitly supports otherwise; and
- employer, moderator, and administration surfaces own management and lifecycle
  operations.

## Primary User

The primary user is a teacher or job seeker evaluating a public job. Public,
actionable job information is available to authenticated and unauthenticated
visitors under the canonical visibility rule.

Authentication is required for saving a job and for revealing protected email
or written application instructions. A login transition preserves the intended
return to the job and action. No separate applicant class is introduced.

## User Objectives

Job Detail must enable a job seeker to:

- confirm the role, employer, location or work arrangement, and current
  availability;
- understand the work, qualifications, classifications, and employment terms;
- evaluate compensation when supplied;
- understand how and where the application will occur;
- apply through the valid destination or instructions when the job is
  actionable;
- save or remove the job from Saved Jobs when authenticated; and
- understand why an application action is unavailable when the job is no
  longer actionable.

## Required Information

The canonical job information needed to satisfy the product purpose is:

- job title;
- canonical employer identity and available public employer information;
- city, state, country, location mode, and other relevant location facts;
- current public availability and applicable freshness or expiration facts;
- job summary and complete description;
- responsibilities and requirements when supplied;
- grade, subject, and other supported Core Terms classifications;
- employment type and work arrangement when supplied;
- salary or compensation information when supplied;
- a valid application method, destination, or protected instructions;
- application expectations necessary to explain whether Teachers.Net or an
  external party receives the application; and
- discreet source, correction, removal, or employer-claim information where
  required by the canonical contract.

Optional facts remain optional. Their absence must not be represented as a
known value. Internal provenance, import, moderation, and source-control facts
do not create a different public job class.

## Supported Actions

### Primary conversion action

Proceed through the job's valid application path. A verified external URL routes
to the actual destination. Protected email or written instructions require an
authenticated user and explicit reveal. Teachers.Net does not imply receipt or
relay of an application when it does not provide that service.

### Secondary user actions

- Save the job or remove it from Saved Jobs. This is authenticated-only and is
  an engagement signal, not an application.
- Access governed correction, removal, or employer-claim paths when applicable.

### Navigation actions

- Return to the prior discovery or results context when available.
- Continue to Saved Jobs through the governed destination.
- Follow a governed public employer destination when one exists.

Authentication transitions belong to WordPress and must preserve the intended
Job Detail destination and supported action. This definition does not prescribe
controls or interaction treatment for any action.

## Trust Requirements

Job Detail establishes confidence only from supported product facts and
conditions:

- the public job is one canonical Jobs record associated with one canonical
  employer record;
- employer-posted and imported jobs follow the same public identity,
  eligibility, and application rules;
- source origin does not imply employer endorsement, recruiter authority, or a
  special public designation;
- the application destination is present, valid, and truthful before public
  publication;
- external, email, and written-instruction methods accurately state where the
  application goes and what Teachers.Net does not process;
- visible availability agrees with Finder eligibility, Saved Jobs, Alerts,
  application actions, and structured data;
- closed, expired, suppressed, or otherwise non-actionable jobs do not present
  a live application path; and
- freshness, expiration, correction, removal, and claim information is accurate
  where the canonical contract requires it.

This definition creates no verified-employer badge, endorsement system, or new
trust workflow.

## States and Conditions

Later UX governance must account for these supported product conditions:

- authenticated or unauthenticated visitor;
- publicly actionable job;
- closed, expired, or otherwise non-actionable historical job when a public
  detail remains available;
- verified external application URL;
- protected email application instructions;
- protected written application instructions;
- saved or not saved for an authenticated user;
- on-site, hybrid, remote, multiple-location, confidential-location, or missing
  distance eligibility where supported by canonical job facts; and
- available or absent optional information such as salary, requirements, or
  public employer details.

Records that fail publication integrity are held from public publication; they
are not a normal public Job Detail state. Draft, review, moderation, and employer
management conditions belong to their governed operational surfaces.

## Dependencies

Approved upstream authority:

- Job Center Canonical V1 Contract;
- Job Center UX Atlas v1;
- Job Center Design System v1 principles, without adopting unresolved visual
  details;
- the approved Search & Discovery Interaction Suite v1, especially JC-011 as
  the primary results transition; and
- WordPress authentication plus existing Jobs-owned lifecycle, application,
  save, employer, and engagement rules.

Downstream work depending on this definition:

- JC-030 UX specification and visual governance;
- Job Detail accessibility and responsive governance;
- implementation audit and bounded implementation tickets;
- JC-040 Saved Jobs continuity;
- employer preview continuity under JC-055; and
- acceptance and QA for job availability and application truth.

## Success Criteria

JC-030 succeeds at the product level when:

- a job seeker can determine what the job is, who it is associated with, where
  or how it is performed, and whether it is currently actionable;
- complete available job facts support an informed application decision;
- every actionable job provides one truthful, usable application path;
- non-actionable jobs cannot mislead the user into attempting to apply;
- authentication gates only the actions governed as authenticated-only and
  preserves return intent;
- Save behavior remains consistent with the Jobs engagement model; and
- Job Detail facts and availability agree with Finder, Saved Jobs, Alerts,
  structured data, and lifecycle truth.

## Explicitly Out of Scope

This product definition does not govern:

- layout;
- visual hierarchy;
- typography;
- spacing;
- component selection or placement;
- responsive adaptation;
- technical architecture;
- implementation;
- analytics instrumentation; or
- speculative future features, including internal applications, ATS, resumes,
  candidate tracking, messaging, interviews, offers, or hires.

Completing this definition does not approve UX, visual, responsive, or
implementation authority. JC-030 remains Placeholder in the Visual Manifest
until a separate governed approval changes that status.
