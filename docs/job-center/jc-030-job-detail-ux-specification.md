# JC-030 — Job Detail UX Specification

**Status:** Approved UX specification; JC-030 visual status remains Placeholder
**Scope:** Desktop interaction and information-structure authority only

This specification translates the approved JC-030 product definition into a
governed UX model. The product definition remains authoritative for product
truth. This document creates no visual, responsive, technical, or implementation
authority.

## 1. Experience Objective

Convert an interested teacher into an applicant by presenting complete job
information with maximum clarity and trust while maximizing application
conversion.

## 2. Entry Context

Supported entry paths are:

- JC-011 Search Results through a JC-020 Canonical Listing;
- JC-040 Saved Jobs;
- another governed public Job Center destination; and
- direct public entry to a valid Job Detail destination.

When prior results or discovery context exists, Job Detail preserves enough
context for the user to return to that same governed experience without
reconstructing search intent. Entry from Saved Jobs preserves the relationship
to the saved collection. Direct entry does not imply a prior query, saved
collection, or artificial return destination.

This continuity requirement does not prescribe browser-history behavior, URL
design, or implementation.

## 3. Primary User Task

The canonical task sequence enables the job seeker to:

1. confirm the opportunity and its current availability;
2. understand the role and canonical employer identity;
3. evaluate location or work arrangement, employment terms, qualifications,
   classifications, and compensation when supplied;
4. understand the application method and destination expectations; and
5. apply, save, return, or leave with accurate expectations.

The user may leave after any step. The experience must not imply that viewing,
saving, or revealing instructions constitutes an application.

## 4. Information Hierarchy

Hierarchy expresses decision priority, not layout, order, grouping, or visual
treatment.

### Primary decision information

- job title;
- canonical employer identity;
- location or work arrangement;
- current public availability; and
- valid application path and destination expectation.

This information enables the user to identify the opportunity, determine
whether it is actionable, and understand how application conversion occurs.

### Core evaluation information

- summary and full description;
- responsibilities when supplied;
- qualifications or requirements when supplied;
- grade, subject, and other supported classifications;
- employment type and work arrangement when supplied; and
- salary or compensation when supplied.

This information enables an informed application decision. Optional facts do
not become required merely because they belong to this priority level.

### Trust and context information

- freshness, expiration, and current availability facts;
- truthful application expectations;
- available public employer information; and
- discreet source, correction, removal, or employer-claim context where
  governed.

### Secondary navigation and engagement information

- save or remove from Saved Jobs;
- return to prior results or discovery context when available;
- continue to JC-040 Saved Jobs; and
- follow a governed public employer destination when available.

## 5. Primary Conversion Action

The UX provides one understandable primary conversion path when the job is
publicly actionable.

- A verified external application URL is available without authentication and
  communicates before departure that the user is proceeding to an external
  destination and that Teachers.Net does not receive the application.
- Protected email instructions require authentication and explicit reveal
  before protected information becomes available.
- Protected written instructions require authentication and explicit reveal
  before protected information becomes available.
- A non-actionable job has no live application action. The experience explains
  the governed availability condition and does not imply that application is
  still possible.

The conversion path communicates its application method and destination
expectation before the user proceeds. Revealing protected instructions is an
engagement event, not proof of application. This specification does not govern
control copy, styling, placement, modal use, or destination implementation.

## 6. Authentication Transitions

WordPress owns login, registration, password recovery, and authentication. JC-030
owns continuity of the intended Job Detail task.

For Save, protected email reveal, and protected written-instructions reveal:

- unauthenticated users receive a truthful authentication requirement before
  the protected action completes;
- the intended JC-030 destination is preserved;
- the intended supported action is preserved where doing so is safe and
  unambiguous;
- after authentication, the user returns to the relevant job and can continue
  the intended action without entering a separate applicant workflow; and
- cancellation or authentication failure does not falsely change saved,
  revealed, interested, or application state.

## 7. Save State

- **Unauthenticated:** the job is not represented as saved for the visitor.
  Attempting to save requires WordPress authentication with Job Detail return
  continuity.
- **Authenticated and not saved:** saving adds the job to the user's JC-040
  collection through the existing Jobs engagement model.
- **Authenticated and saved:** removing the saved state removes the job from the
  user's JC-040 collection without implying a lifecycle or application change.

The saved state must agree across Job Detail, JC-020 listings, and JC-040 Saved
Jobs. Saving expresses interest only. Saving is not applying, does not send an
application, and does not establish candidate status.

## 8. Availability States

- **Publicly actionable:** current availability and a valid application path
  agree with Finder eligibility and canonical lifecycle truth.
- **Closed with public detail retained:** the closed condition is
  understandable, and no live application action remains.
- **Expired with public detail retained:** expiration and loss of application
  eligibility are understandable, and no live application action remains.
- **Otherwise non-actionable historical detail:** the relevant supported
  condition is understandable, and the experience does not imply current
  application eligibility.

Status language must describe user-facing availability truth rather than expose
an internal storage term. Historical detail does not re-enter current Finder,
Saved Jobs, Alerts, structured data, or application eligibility contrary to the
canonical visibility rule. No replacement-job recommendation is required or
defined.

## 9. Optional and Missing Information

For optional salary, responsibilities, requirements, employer public details,
and optional location facts:

- absence is not represented as a known value;
- an empty information area does not imply completeness;
- unsupported facts are not inferred from related data;
- the UX does not invent fallback facts or guarantees; and
- missing optional data does not block the primary task unless the canonical
  publication contract makes that information necessary for integrity.

The experience remains coherent when one or more optional facts are absent.
This requirement does not prescribe placeholder text or visual treatment.

## 10. Location and Work Arrangement Conditions

Job Detail communicates the supported canonical facts for on-site, hybrid,
remote, multiple-location, and confidential-location jobs. It may also reflect
that a job lacks distance eligibility without implying that the job is invalid
or unavailable when it otherwise satisfies public eligibility.

Visible location and work-arrangement facts agree with the Finder and the
canonical job record. Job Detail does not resolve search origins, calculate or
redefine distance, change cross-state search behavior, or infer a precise
location from confidential or incomplete information.

## 11. Employer and Source Trust

The UX presents one canonical employer identity and the public employer
information that is actually available. Employer-posted and imported jobs use
the same public Job Detail model.

Discreet source, correction, removal, or employer-claim context is available
only where required by existing governance. It must not imply:

- verified-employer status;
- employer or Teachers.Net endorsement;
- recruiter authority;
- that a source relationship is employer authority; or
- that Teachers.Net receives an application when it does not.

No new verification, endorsement, claim, or trust system is created by this
specification.

## 12. Navigation Continuity

Supported outcomes are:

- return to prior results or discovery context when that context exists;
- continue to JC-040 Saved Jobs;
- follow a governed public employer destination when available; and
- proceed through the valid application destination or instructions.

Direct entry has no fabricated prior-results action. Navigation continuity does
not redefine the approved Job Center shell, global navigation, Search &
Discovery behavior, browser history, or route behavior.

## 13. Interaction States

| State | Trigger or condition | User need | Required UX response | Prohibited implication |
|---|---|---|---|---|
| Actionable external apply | Publicly actionable job with verified external URL | Know where application occurs | Communicate external destination and allow truthful departure | Teachers.Net receives or relays the application |
| Actionable protected email | Publicly actionable job with protected email method | Access usable email instructions safely | Require authentication and explicit reveal, then provide the governed method | Reveal or email initiation proves application |
| Actionable protected written instructions | Publicly actionable job with protected written method | Understand and use the instructions | Require authentication and explicit reveal, then provide the governed instructions | Reveal proves application |
| Unauthenticated save | Visitor is not authenticated | Save the job without losing intent | Explain authentication requirement and preserve safe return/action context | Job is already saved or user is an applicant |
| Authenticated unsaved | Authenticated user has not saved the job | Add it to Saved Jobs | Permit save through the Jobs engagement model and maintain cross-surface agreement | Save sends an application |
| Authenticated saved | Authenticated user has saved the job | Confirm or remove saved state | Represent the governed saved state and permit removal | Save is employer contact or candidate status |
| Closed/non-actionable | Closed detail remains public | Understand why application is unavailable | Communicate closed availability and omit live application action | Job remains open |
| Expired/non-actionable | Expired detail remains public | Understand why application is unavailable | Communicate expired availability and omit live application action | Expiration is a live opportunity |
| Optional content present | An optional canonical fact is supplied | Use the fact in evaluation | Present it as supported job information | Optional fact is mandatory or independently verified |
| Optional content absent | An optional canonical fact is absent | Evaluate without false data | Preserve a coherent task without inventing the fact | Unknown means zero, none, or another known value |
| Prior results context available | Entry retains governed results/discovery context | Return without rebuilding intent | Preserve a return outcome to that context | Direct entry or a different query was the source |
| Direct entry/no prior context | Valid public detail opened directly | Navigate without a fabricated history | Provide only supported onward destinations | A prior results state exists |

These states are inputs for later visual governance, not component or artwork
specifications.

## 14. Accessibility Requirements

- Heading semantics communicate the job and major information relationships in
  a meaningful, navigable structure.
- Every available action is keyboard accessible and has a programmatically
  determinable purpose and state.
- Current availability and saved state are programmatically determinable and
  are not communicated by color alone.
- Authentication requirements are communicated before an authenticated-only
  action proceeds.
- External-destination expectations are communicated before departure.
- Protected instructions are disclosed through an accessible interaction whose
  expanded state and revealed content relationship are determinable.
- Focus remains usable after authentication return, protected-content reveal,
  save-state change, or unavailable-action explanation.
- Returning from JC-030 preserves the user's relevant context where available,
  without introducing a keyboard or screen-reader dead end.
- Missing optional content does not create empty semantic regions or misleading
  labels.

These are semantic and interaction outcomes, not markup or visual
implementation prescriptions.

## 15. Relationship to Adjacent Surfaces

| Surface | Owns | JC-030 continuity responsibility |
|---|---|---|
| JC-011 Search Results | Query context, filters, sort, count, and pagination | Preserve return to available governed results context without redefining it |
| JC-020 Canonical Listing | Comparable job summary and entry to detail | Keep shared job facts, availability, and saved state consistent |
| JC-040 Saved Jobs | Authenticated saved-job collection and removal | Reflect the same saved state and support return between collection and detail |
| JC-055 Employer Preview | Employer-facing preview of the public presentation model | Use JC-030 information truth without implying publication or granting public actions |
| WordPress authentication | Login, registration, recovery, and authenticated identity | Preserve intended job and safe supported action through the transition |
| External application destination | Application receipt and external process | Communicate departure and avoid implying Teachers.Net processing |

No adjacent surface gains new scope from this specification.

## 16. Explicit UX Exclusions

This specification does not govern:

- visual styling;
- exact layout;
- typography;
- spacing;
- color;
- imagery;
- component selection or placement;
- responsive adaptation or breakpoint behavior;
- technical architecture;
- database or schema behavior;
- route behavior;
- application processing;
- analytics instrumentation; or
- speculative future features, including internal applications, ATS, resumes,
  candidate tracking, messaging, interviews, offers, or hires.

Completing this specification establishes approved UX authority only. JC-030
remains Placeholder in the Visual Manifest until a separate governed approval
establishes visual authority. It creates no responsive or implementation
authority.
