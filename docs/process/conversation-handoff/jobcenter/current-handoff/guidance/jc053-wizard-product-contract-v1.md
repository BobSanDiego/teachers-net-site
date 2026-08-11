# JC053 Wizard Product Contract v1

**Status:** Canonical JC053 V1 wizard product/field authority.

**Purpose:** Own Step 1-5 field ownership, requiredness, defaults, conditional
behavior, progressive disclosure, and step-transition gates for the V1 Job
Posting Wizard.

This contract exists because later synthesized summaries drifted from accepted
ticket/workbench evidence. Future field-admission, step-ownership,
requiredness, default, validation-gate, or disclosure changes must update this
contract and cite the authorizing ticket or Engineering Director decision.

## Authority and anti-drift rule

Documentation reconciliation may summarize accepted decisions, but it must not
change field admission, step ownership, requiredness, defaults, validation
gates, or disclosure behavior without an explicit product decision. Any commit
or report that changes one of those material properties must identify the
authorizing ticket, accepted human decision, or accepted implementation evidence.

Migration of an older authority document does not automatically make unsupported
statements newly authoritative. Current synthesized documents do not override
accepted ticket/human evidence merely by being newer. Contradictions must be
reported and resolved through provenance rather than silently normalized.

## Evidence hierarchy

For disputed JC053 wizard product decisions, use:

1. Explicit Engineering Director product decisions.
2. Explicit human acceptance decisions.
3. Accepted ticket completion reports tied to the decision.
4. Accepted implementation/workbench state tied to those tickets.
5. Approved visual/design evidence tied to those tickets.
6. Earlier architecture/field contracts whose provenance is supported.
7. Current synthesized governance documents.
8. Continuity summaries such as Cursor, Handoff, and Roadmap.

Repository implementation is evidence of what existed, not automatic product
acceptance.

## Step 1 — School / Jobsite

Step 1 establishes the required Primary School / Jobsite resource for the job.
The Primary Resource is the organizational anchor and remains required even when
the later Work Location choice changes how the job's work site is presented.

Confirmed V1 authority:

- Employers choose an existing authorized School / Jobsite or add a new one.
- Zero-resource entry routes directly into Add School / Jobsite.
- Existing-resource presence never disables the Add New School / Jobsite path.
- One authorized resource may be automatically selected/committed.
- Two or more authorized resources use chooser/confirmation behavior.
- Step 2 is gated on a valid selected Primary Resource.
- `display_name` is the compact identity for constrained UI surfaces; `full_name`
  remains the canonical resource identity.
- Defensive resource-identity overflow presentation is accepted before final
  Step 1 certification; final Display Name authoring limits remain separate.
- Step 1 image behavior includes staging, preview, Replace, Remove, fallback,
  and persistence through the accepted media flow; media editing in Manage
  Schools / Jobsites remains separately bounded.
- Return/hydration preserves authorized resources and selected resource state.

Open boundaries:

- Destructive/archive School / Jobsite management is outside the Step 1 wizard.
- Manage Schools / Jobsites is governed by JC052 authority.

## Step 2 — Job Basics

Step 2 owns Position, Employment, Starting Date, and Compensation. Benefits is
not a Step 2 field.

### Position

- Job Title — required.
- Grade Level(s) — required where applicable.
- Subject Area(s) — recommended/available where applicable.

Vocabulary and hierarchy for Grade Level(s) and Subject Area(s) use the
configured Jobs/Core Terms view boundary rather than local hardcoded taxonomy.

### Employment

- Employment Type — V1 field; exact vocabulary remains open except where
  already accepted by implementation.
- Work Location — V1 field.
- Work Location default: `Use School / Jobsite Location` when Step 1 has
  established the required Primary Resource.
- Work Location has no blank placeholder in the ordinary V1 authoring path.
- Changing Work Location does not remove or weaken the required Primary
  Resource.
- Alternate location, Remote, Hybrid, and Multiple Locations are conditional
  Work Location behaviors. Detailed Remote/Hybrid/Multiple semantics remain
  open except where accepted evidence already establishes behavior.

### Starting Date

Starting Date is a V1 Step 2 field.

Start Timing options:

- Immediately
- Specific Date
- Flexible

Default:

- Immediately

No blank placeholder is shown before the default.

Conditional behavior:

- Immediately: no specific Start Date shown or required.
- Specific Date: reveal Start Date; Start Date is required to complete the
  Starting Date control.
- Flexible: no specific Start Date shown or required.

Start Timing and Start Date are not V1 deferrals.

### Compensation

- Salary Visibility / salary display mode — V1 field.
- Salary Visibility default: `Show Salary`.
- Salary Visibility has no blank placeholder in the ordinary V1 authoring path.
- Salary minimum and salary maximum are conditional compensation fields.
- Detailed salary vocabulary, formatting, interval, validation, currency,
  hourly/annual, negotiable, volunteer, and undisclosed semantics remain open
  except where accepted evidence already establishes behavior.

## Step 3 — Job Description

Step 3 is paste-first, disclosure-driven authoring. Job Description is the only
immediate required Step 3 narrative field for ordinary authoring readiness.

### Immediate required field

- Job Description — required rich-text field.

### Recommended summary field and gate

- Short Summary follows Job Description and is recommended in the authoring UI.
- Short Summary does not block ordinary Step 3 authoring readiness.
- If the user continues without a sufficient Short Summary, the deterministic
  summary review/gate opens before Step 4.
- The summary review/gate writes or confirms the actual Short Summary value
  before Step 4.
- Short Summary supports promoted listings, search presentation, featured
  listings, discovery, and external sharing.

### Optional Fields boundary

The Optional Fields area includes:

- Requirements / Qualifications — recommended for matching.
- Responsibilities.
- Preferred Qualifications.
- About Our School.
- Benefits.

Requirements / Qualifications is optional/recommended, not required, and does
not block ordinary Step 3 readiness.

### Benefits

Benefits belongs exclusively to Step 3 Optional Fields for V1.

Accepted Benefits interaction:

- Compact inline selector.
- Category headings.
- Clickable benefit names.
- Selected-state highlighting.
- Always-visible selected summary.
- Empty-state teaching text: `Benefits offered: Click any benefit to add or
  remove it.`
- The empty-state instruction disappears after selection.
- Additional Benefits uses progressive disclosure: helper text, textarea, and
  character counter appear only after the control is selected.

Step 2 must contain no Benefits field or Benefits selector.

### Listing Preview and authoring behavior

- Rich paste is the primary authoring workflow.
- Pasted formatting is preserved automatically within accepted normalization.
- Only Job Description exposes the visible formatting toolbar.
- Optional rich-text sections remain rich-paste capable without persistent
  toolbars.
- Listing Preview is incremental and suppresses empty headings, sections, and
  containers.
- Step 5 remains the canonical full preview surface.

Open boundaries:

- Detailed Listing Image override behavior remains open except where already
  accepted.
- Advanced Display Options details remain open except where already accepted.

## Step 4 — Application Process

Confirmed V1 field families:

- Application Method: External URL, Email, Instructions.
- Conditional fields: Application URL, Application Email, Application
  Instructions.
- Contact: Use School / Jobsite default contact, Override contact, Contact Name,
  Contact Email, Contact Phone, Hide contact details publicly.
- Deadline: Specific date, Open until filled, No stated deadline, Application
  Deadline, Close on Application Deadline.

Open boundaries:

- Final Contact/default-contact behavior remains unresolved except where already
  accepted.
- Final Application Deadline semantics remain unresolved.
- Close-on-deadline lifecycle behavior remains unresolved.
- Any materials or other Step 4 controls not explicitly accepted remain open.

## Step 5 — Review & Publish

Confirmed V1 field families:

- Review composition and full preview.
- Certification / final confirmation.
- Final action label depends on trust/moderation state: `Publish` or `Submit
  for Review`.
- Immediate publication/on-approval flow.
- Schedule publication and conditional Publication Date are represented as
  candidate/current field-contract items, but final scheduled-publication V1
  status remains unresolved unless separately accepted.

Open boundaries:

- Publication duration, expiration, renewal, and end-publication lifecycle remain
  unresolved.
- Scheduled publication V1 status remains unresolved.
- Moderation-sensitive final action behavior is governed by the employer
  trust/capability authority and must not be reinvented in wizard summaries.

## Cross-step controls and state

- Previous and Next are shared bottom-navigation controls.
- The top stepper is progress display, not primary navigation.
- Save Draft / resume / hydration are production-owned state behaviors.
- Cancel returns to the employer-owned destination defined by production
  context.
- Validation timing is step-specific and must follow the gates in this contract;
  fields marked recommended/optional must not block navigation unless a separate
  accepted gate says so.
- Progressive disclosure must not become silent field removal or invented
  requiredness.

Current human Step 2 finding, not yet repaired:

- In the authenticated production Step 2 under Engineering Director review,
  visible required fields can be populated, but the bottom Next control does not
  advance to Step 3. This is an application issue for the resumed
  JC053-STEP2-INTEGRATION and is not accepted behavior.

## Explicit V1 deferrals

The following are excluded from the V1 wizard unless separately reopened:

- Internal Job ID / Requisition Number.
- Department.
- Job Role / Category.
- Specialties / Program Areas.
- Position count / openings.
- Experience level.
- Credential or certification expectation.
- Number of contract months.
- Schedule notes.

Starting Date / Start Timing and Start Date are not V1 deferrals.

## Provenance matrix

| STEP | FIELD / BEHAVIOR | CURRENT AUTHORITY | EARLIEST RELEVANT EVIDENCE | LATEST ACCEPTED PRODUCT EVIDENCE | IMPLEMENTED / WORKBENCH EVIDENCE | CHANGE / DRIFT COMMIT | AUTHORIZING TICKET / DECISION | FINAL CLASSIFICATION | FINAL V1 AUTHORITY | NOTES / REMAINING OPEN QUESTION |
|---|---|---|---|---|---|---|---|---|---|---|
| 1 | Primary Resource requirement | Current data/Step 1 docs require one Primary Resource | DATA001/DATA008 architecture chain | Step 1 final functional acceptance and JC052 management prerequisite | Production Step 1 selected-resource flow | none known | DATA001/Step 1 accepted tickets | CONFIRMED CURRENT | Required organizational anchor | Work Location does not remove it |
| 1 | Existing-resource selection | Current Step 1 authority | Step 1 state consolidation | Step 1 final acceptance | Production Step 1 chooser/summary | none known | JC053 Step 1 state authority tickets | CONFIRMED CURRENT | Select/confirm existing authorized resource | 0/1/2+ state behavior preserved |
| 1 | Add School / Jobsite | Current Step 1 authority | Step 1 Add School flow tickets | Add another/create-return corrections | Production Step 1 add subflow | none known | JC053 Step 1 Add School tickets | CONFIRMED CURRENT | Add path always available from chooser state | International flow remains conditional |
| 1 | Employer ownership/relationship | Data and employer authority docs | DATA001-REV1 | JC052 Manage Schools cert | Production relationships/resources | none known | DATA001/JC052 | CONFIRMED CURRENT | Employer relationship grants authorized use | Destructive management separate |
| 1 | display_name vs full_name | Design system and identity decision | DOC resource identity decision | Resource identity overflow defense | Production selected resource card | none known | JC053 identity tickets | CONFIRMED CURRENT | display_name compact; full_name canonical | Final Display Name authoring limit open |
| 1 | Image behavior | Image contract / Step 1 closure | Image contract | Image browser QA closure | Production media staging/persistence | none known | JC053 image ticket chain | CONFIRMED CURRENT | Stage, preview, replace, remove, persist | Manage media editing separate |
| 1 | Return/hydration | Step 1 state docs | State consolidation | Final functional acceptance | Production hydration | none known | JC053 state authority tickets | CONFIRMED CURRENT | Preserve authorized resource/selection | none |
| 1 | Step 1 validation / Next | Step 1 docs | Primary Resource gate | Final functional acceptance | Production Next gating | none known | DATA008/Step 1 final | CONFIRMED CURRENT | Next requires valid selected resource | none |
| 2 | Job Title | Field contract | Early Step 2 workbench | MIGAUDIT001 | Workbench Step 2 | none known | JC053 Step 2 / MIGAUDIT001 | CONFIRMED CURRENT | Required Step 2 field | none |
| 2 | Grade Level(s) | Field contract | Step2 taxonomy tickets | STEP2-TAXONOMY010 and migration audit | Workbench taxonomy selector | none known | STEP2-TAXONOMY chain | CONFIRMED CURRENT | Required where applicable | Uses configured view/terms |
| 2 | Subject Area(s) | Field contract | Step2 taxonomy tickets | STEP2-TAXONOMY010 and migration audit | Workbench taxonomy selector | none known | STEP2-TAXONOMY chain | CONFIRMED CURRENT | V1 Step 2 field | Uses configured view/terms |
| 2 | Employment Type | Field contract | Workbench Step 2 | MIGAUDIT001 | Workbench Step 2 | none known | MIGAUDIT001 | CONFIRMED CURRENT | V1 Step 2 field | exact vocabulary open |
| 2 | Work Location | Field contract | Workbench Step 2 | Drift audit 260810133514 | Workbench select | under-documented by 260730 artifact / 1969e45 migration | audit + Engineering Director correction | CONFIRMED DRIFT — CORRECT | V1 Step 2 field; default Use School / Jobsite Location; no blank ordinary placeholder | Remote/Hybrid/Multiple details open |
| 2 | Primary Resource / Work Location relationship | Field contract now records | DATA001 and Step 2 workbench | Drift audit 260810133514 | Workbench summary/alternate flows | under-documented in migrated contract | audit + Engineering Director correction | CONFIRMED DRIFT — CORRECT | Primary Resource remains anchor regardless of Work Location | none |
| 2 | Alternate location | Field contract conditional | Workbench Step 2 | MIGAUDIT001 | Workbench alternate location fields | none known | MIGAUDIT001 | CONFIRMED CURRENT | Conditional Work Location behavior | details open |
| 2 | Remote / Hybrid / Multiple | Field contract conditional/open | Workbench Step 2 | MIGAUDIT001 | Workbench conditional flows | none known | MIGAUDIT001 | UNRESOLVED — PRESERVE OPEN | Preserve current conditional field family | final semantics open |
| 2 | Starting Date / Start Timing | Field contract now records | Workbench Step 2 | Drift audit 260810133514 and ED correction | Workbench `Immediately`/`Specific Date`/`Flexible` | 260730 artifact/1969e45 deferred incorrectly; corrected 30fffae | audit + Engineering Director correction | CONFIRMED DRIFT — CORRECT | Step 2 V1; default Immediately; no blank | none |
| 2 | Start Date conditional | Field contract now records | STEP2-POLISH013 | Drift audit 260810133514 | Workbench reveals date for Specific Date | 260730 artifact/1969e45 deferred incorrectly; corrected 30fffae | STEP2-POLISH013/audit | CONFIRMED DRIFT — CORRECT | Specific Date reveals/requires Start Date | none |
| 2 | Salary Visibility | Field contract now records | Workbench Step 2 | Drift audit 260810133514 | Workbench default Show Salary | under-documented by 260730 artifact / 1969e45 migration | audit + Engineering Director correction | CONFIRMED DRIFT — CORRECT | Default Show Salary; no blank ordinary placeholder | detailed salary semantics open |
| 2 | Salary min/max | Field contract | STEP2-COMP001 | MIGAUDIT001 | Workbench compensation form | none known | STEP2-COMP001 | CONFIRMED CURRENT | Conditional compensation fields | validation details open |
| 2 | Benefits historical ownership | Field contract now Step 3 only | Step 3 Benefits ticket chain | fcb18d3 | Workbench Step 3 selector | 260730 artifact/1969e45 assigned Step 2; corrected fcb18d3 | fcb18d3 / Step 3 Benefits tickets | AUTHORIZED LATER CHANGE | Benefits Step 3 only | none |
| 2 | Deferred fields appearing in Step 2 evidence | Deferrals list excludes Starting Date | 260730 field contract | Drift audit 260810133514 | Workbench supports Starting Date only among disputed deferrals | 260730 artifact drift | audit + ED correction | CONFIRMED DRIFT — CORRECT | Deferrals list excludes only non-admitted fields | Reopen only by ticket |
| 3 | Job Description | Field contract/design system | STEP3-SUMMARY001 | Step 3 workflow/audit | Workbench required editor | none known | STEP3-SUMMARY001 | CONFIRMED CURRENT | Only immediate required Step 3 narrative field | none |
| 3 | Requirements / Qualifications | Design system/30fffae incorrectly required | STEP3-SUMMARY001 | ED correction and STEP3-SUMMARY001 | Workbench Optional Fields details | 30fffae strengthened requiredness; design-system summary already drifted | ED correction / STEP3-SUMMARY001 | CONFIRMED DRIFT — CORRECT | Optional / recommended for matching; does not block readiness | none |
| 3 | Short Summary | Field contract/30fffae flattened as required | STEP3-SUMMARY001 | STEP3 summary gate tickets | Workbench summary review modal | 30fffae over-flattened requiredness | ED correction / STEP3-SUMMARY001 | CONFIRMED DRIFT — CORRECT | Recommended authoring field; summary gate guarantees before Step 4 | not ordinary immediate blocker |
| 3 | Optional Fields boundary | Current docs partly drifted | STEP3-SUMMARY001 | Step 3 workflow tickets | Workbench Optional Fields boundary | design-system/roadmap summaries drifted | STEP3-SUMMARY001 | CONFIRMED DRIFT — CORRECT | Requirements, Responsibilities, Preferred Qualifications, About Our School, Benefits | none |
| 3 | Responsibilities | Current Step 3 optional | Step 3 workflow | Step 3 workflow | Workbench optional field | none known | Step 3 workflow | CONFIRMED CURRENT | Optional Field | none |
| 3 | Preferred Qualifications | Current Step 3 optional | Step 3 workflow | Step 3 workflow | implied optional field authority | none known | Step 3 workflow | CONFIRMED CURRENT | Optional Field | none |
| 3 | About Our School | Current Step 3 optional | Step 3 workflow | Step 3 workflow | Workbench optional field | none known | Step 3 workflow | CONFIRMED CURRENT | Optional Field | none |
| 3 | Benefits / Additional Benefits | Current Step 3 optional | Step 3 benefits chain | fcb18d3 and benefits tickets | Workbench compact selector | Step 2 drift corrected fcb18d3 | Step 3 Benefits tickets | CONFIRMED CURRENT | Optional compact selector; Additional Benefits progressive | exact vocabulary open |
| 3 | Listing Image | Current field contract | 260730 contract / Step 3 design | Audit found unresolved details | Workbench evidence partial | none known | field contract | UNRESOLVED — PRESERVE OPEN | Field family admitted; detailed override behavior open | do not invent |
| 3 | Advanced Display Options | Current field contract | 260730 contract | Audit found unresolved details | not fully audited accepted behavior | none known | field contract | UNRESOLVED — PRESERVE OPEN | Optional expandable family | details open |
| 3 | Paste/formatting/toolbar | Design system | Step 3 paste tickets | Step 3 workflow | Workbench Job Description toolbar only | none known | Step 3 paste tickets | CONFIRMED CURRENT | Paste-first; Job Description toolbar only | none |
| 3 | Incremental preview / suppression | Design system | Step 3 tickets | Step 3 workflow | Workbench preview suppresses empty sections | none known | Step 3 tickets | CONFIRMED CURRENT | Render populated content only | Step 5 full preview remains canonical |
| 3 | Step 3 to Step 4 gate | Product contract now records | STEP3-SUMMARY001 | Summary gate tickets | Workbench summary modal | 30fffae flattened summary requiredness | STEP3-SUMMARY001 | CONFIRMED DRIFT — CORRECT | Job Description readiness; summary gate before Step 4 | none |
| 4 | Application Method | Field contract | 260730 contract | MIGAUDIT001 | production apply method/instructions exist | none known | MIGAUDIT001 | CONFIRMED CURRENT | External URL / Email / Instructions | exact validation by integration |
| 4 | Conditional application fields | Field contract | 260730 contract | MIGAUDIT001 | production apply fields | none known | MIGAUDIT001 | CONFIRMED CURRENT | URL/email/instructions conditional | details by integration |
| 4 | Contact/default contact | Field contract | 260730 contract | MIGAUDIT001 partial/unclear | production partial | none known | none complete | UNRESOLVED — PRESERVE OPEN | field family admitted | final behavior open |
| 4 | Deadline/Open until filled/No stated deadline | Field contract | 260730 contract | MIGAUDIT001 partial/unclear | workbench/production partial | none known | none complete | UNRESOLVED — PRESERVE OPEN | field family admitted | final semantics open |
| 4 | Close on Application Deadline | Field contract | 260730 contract | audit unresolved | partial | none known | none complete | UNRESOLVED — PRESERVE OPEN | conditional field family | lifecycle semantics open |
| 4 | Materials/other controls | Not admitted unless accepted | ticket requires audit | no accepted evidence found in bounded search | none current | none known | none | UNRESOLVED — PRESERVE OPEN | Do not add without ticket | open |
| 5 | Review composition/full preview | Field contract | 260730 contract | MIGAUDIT001 | production review/preview exists | none known | MIGAUDIT001 | CONFIRMED CURRENT | Review, preview, validation, lifecycle controls | details by integration |
| 5 | Certification/final confirmation | Field contract | 260730 contract | MIGAUDIT001 | production final submit exists | none known | field contract/MIGAUDIT001 | CONFIRMED CURRENT | Confirmation before final submission | DB field not presumed |
| 5 | Publish vs Submit for Review | Field contract + employer authority | 260730 contract | JC056 trust/capability chain | production final status path | none known | JC056 authority | CONFIRMED CURRENT | Label depends on trust/moderation | none |
| 5 | Immediate publication | Field contract | 260730 contract | MIGAUDIT001 partial | production final submit exists | none known | partial | CONFIRMED CURRENT | publish/on approval field family | details by trust |
| 5 | Scheduled publication / Publication Date | Field contract but status open | 260730 contract | audit unresolved | partial/candidate | none known | none complete | UNRESOLVED — PRESERVE OPEN | represented as conditional candidate/current family | V1 status open |
| 5 | Expiration / renewal lifecycle | Field contract unresolved | 260730 contract | audit unresolved | production partial | none known | none complete | UNRESOLVED — PRESERVE OPEN | unresolved lifecycle decision | do not invent |
| all | Previous / Next | Design system | wizard workbench | Step 1/Step 2/Step 3 tickets | shared bottom nav | none known | JC053 wizard design | CONFIRMED CURRENT | Shared bottom controls; stepper not nav | current Step 2 Next failure is app defect |
| all | Save Draft / resume / hydration | Production authority | MIGAUDIT001 | Step 1 state/production seam | production state partial | none known | MIGAUDIT001/Step 1 | CONFIRMED CURRENT | Production-owned state | details by integration |
| all | Cancel | Production authority | workbench/production | Step 1 production seam | production cancel URL | none known | Step 1 production integration | CONFIRMED CURRENT | Return to employer-owned destination | none |
| all | Defaults/placeholders | Product contract | Step 2 workbench | drift audit / ED correction | Workbench defaults | 260730/1969e45 under-documented | audit/ED correction | CONFIRMED DRIFT — CORRECT | Defaults documented per field | do not invent others |
| all | Responsive/shared-shell primitives | Design system | JC053 responsive tickets | Step 1 final/consolidation | production Step 1 CSS | none known here | JC053 visual/convergence tickets | CONFIRMED CURRENT | UI primitive authority remains design system | product contract owns field behavior |
