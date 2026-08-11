# Teachers.Net (TNET) - Views (8/10/26)

**Exported:** 8/11/2026, 1:03:16 AM  
**Messages:** 16  

---

**🙍🏻‍♂️ You:**

teachers_net_tnet_views_8_8_26__2026-08-10.mdFilePls ingest this and take note of the ticket flow procedures - it is critical that you comply with the latest procedures, and the product development flow.

---

**🙍🏻‍♂️ You:**

teachers_net_tnet_tnet_3_0_7_29_26_2026-08-03(2).mdFileteachers_net_tnet_tnet_3_0_8_3_26_2026-08-04(2).mdFileteachers_net_tnet_job_center_8_6_26__2026-08-08(1).mdFileteachers_net_tnet_views_8_4_26__2026-08-08(1).mdFileteachers_net_tnet_job_center_7_23_26_2026-07-25(3).txtDocumentteachers_net_tnet_job_center_7_25_26_2026-07-27(2).txtDocumentteachers_net_tnet_teachers_net_community_3_0_desig_2026-07-29(2).mdFileteachers_net_tnet_teachers_net_community_3_0_desig_2026-07-30(3).txtDocumentteachers_net_tnet_job_center_7_28_26_2026-07-30(2).mdFilepls ingest the next few file dumps without comment - they provide context for the current Views project as well as related concurrent projects

---

**🙍🏻‍♂️ You:**

durable-views-dv003-persistence-strategy(1).mdFiledurable-views-dv013-job-center-certification.mdFiledurable-views-dv018-sprint-readiness-gap-audit.mdFiledurable-views-dv-fix001-completion-report.mdFiledurable-views-dv-fix002-completion-report.mdFiledurable-views-dv-ux019-completion-report.mdFiledurable-views-dv-ux022-completion-report.mdFiledurable-views-dv-ux022r1-completion-report.mdFiledurable-views-dv-uxaud002-authoring-model-specification.mdFiledurable-views-engineering-handoff(2).mdFiledurable-views-mvp-assessment(1).mdFiledurable-views-mvp-closeout.mdFiledurable-views-project-cursor(2).mdFiledurable-views-roadmap(2).mdFiledurable-views-user-manual(1).mdFiledurable-views-uxaud001-reuse-audit.mdFiledurable-views-v1-product-specification(1).mdFileviews-ticket-handoff-and-execution-procedure(1).mdFiledurable-views-dv002-schema-contract(1).mdFile

---

**🙍🏻‍♂️ You:**

02-Views-Browser-QA-Troubleshooting-and-Procedure-Revision-Report-2026-08-08(1).mdFile03-ChatGPT-Codex-Workflow-Optimization-Report-2026-08-08(1).mdFile04-ChatGPT-Engineering-Assistant-and-Codex-Cycle-Operating-Guide-2026-08-08(1).mdFile05-ChatGPT-Custom-Instructions-Optimization-Report-2026-08-08.mdFile01-Views-Browser-QA-Durable-Guidance-2026-08-08(1).mdFile

---

**🙍🏻‍♂️ You:**

cycle-views-260809012225.jsonFileMANIFEST-views-260809012225.txtDocumentoutput-260809012225.txtDocumentoutput-views-260809012225.txtDocumentQA-INFRA002-report-260809012225.mdFileQA-INFRA002-report-260809012225-views-260809012225.mdFile

---

**🙍🏻‍♂️ You:**

durable-views-engineering-handoff-views-260810140350.mdFiledurable-views-mvp-assessment-views-260810140350.mdFiledurable-views-mvp-closeout-views-260810140350.mdFiledurable-views-v1-product-specification-views-260810140350.mdFileupdated guidance documents

---

**🙍🏻‍♂️ You:**

we seem to be going backwards here, because all those improvements you just listed I remember before, and the current interface does not reflect the updates we implemented - the trees were supposed to be on parity with each other, the top panel items were supposed to have symmetry. Am I going crazy?

---

**🙍🏻‍♂️ You:**

output-260810160240-views-260810160240(1).txtDocumentdv-diag005-completion-report-260810160240-views-260810160240(1).mdFileso what happened?

---

**🙍🏻‍♂️ You:**

dv-diag005-cont1-completion-report-260810160928-views-260810160928.mdFileoutput-260810160928-views-260810160928.txtDocument

---

**🙍🏻‍♂️ You:**

The trees themselves look fine - it's that one side contains a box which pushes its content well down below where the left side start. That's what caught my eye - why is that box there?

---

**🙍🏻‍♂️ You:**

dv-fix004-ungrouped-completion-report-260810163148-views-260810163148.mdFileoutput-260810163148-views-260810163148.txtDocumenthere is the latest report I have loaded - did it complete the last ticket?

---

**🤖 ChatGPT:**

DV-FIX004 is accepted and closed. The next highest-leverage step is the governance reconciliation: establish the actual implemented manager state and repair the roadmap/handoff so we stop deriving tickets from stale historical “next” language.
TICKET READY FOR CODEX

Ticket:
DV-GOV005 — Reconcile Post-MVP Roadmap, Manager State, and Next Execution Boundary

Execution mode:
DIAGNOSTIC

Objective:
Reconcile the current Durable Views implementation, accepted completion evidence, and durable guidance so the project has one trustworthy post-MVP state and sequencing authority.

Determine exactly which View Manager, subscriber-visibility, dependency, and related post-MVP capabilities are already implemented and accepted; identify stale or contradictory “next ticket” language; update only the appropriate durable guidance; and establish the smallest correct next implementation boundary plus a reasoned next-five planning horizon.

This is primarily governance/state reconciliation. Do not implement new product capability.

Why now:
DV-FIX004 is engineering-complete and has received human visual acceptance.

Recent diagnostics exposed a sequencing problem:
- current durable guidance contains historical “next” statements that can be read as current;
- DV-DIAG005 reported that prior DV-MGR001 implementation and reports already exist in repository history;
- ChatGPT initially interpreted older roadmap language as authorization to issue manager work that may already have been completed;
- the MVP assessment is explicitly superseded planning authority;
- DV-ACCEPT002 closed the standalone MVP and established readiness for separately authorized consumer integration.

Before another implementation ticket, the project needs a single reconciled account of what is actually complete and what remains.

Authority:
Use the established hierarchy:

1. Authority Manifest, if present.
2. Approved platform/product contracts.
3. Durable governance.
4. Current Project Cursor and Engineering Handoff.
5. Current roadmap and V1 Product Specification.
6. Accepted implementation, Git history, completion reports, and runtime evidence.
7. Historical plans/audits only for chronology.
8. Companion chat only for unresolved context/latest ticket.

Required local documents:
- docs/core-terms/durable-views-project-cursor.md
- docs/core-terms/durable-views-engineering-handoff.md
- docs/core-terms/durable-views-roadmap.md
- docs/core-terms/durable-views-v1-product-specification.md
- docs/core-terms/durable-views-mvp-closeout.md
- docs/core-terms/views-ticket-handoff-and-execution-procedure.md

Required recent evidence:
- DV-ACCEPT002 standalone MVP closeout
- DV-DIAG005
- DV-DIAG005-CONT1
- DV-FIX004 completion report
- any existing DV-MGR001 report/artifacts discovered in repository authority
- later accepted manager/subscriber-related reports or commits discovered during the audit

Canonical root:
- /home/bobreap/projects/teachers-net-site

Profilaxes:
- /home/bobreap/projects/teachers-net-site/wordpress/wp-content/plugins/profilaxes

Canonical runtime:
- https://teachers-net.ddev.site/

Views manager:
- https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views

Current proven state:
- DV-ACCEPT002 accepted the standalone Durable Views MVP.
- DV-FIX004 is now engineering-complete and human visually accepted.
- JobLister draft version 46 has been restored to an ungrouped 23-entry canonical composition.
- Job Center remains pinned to View 10 / published version 12 unless later accepted evidence proves an explicitly authorized change.
- Publishing does not silently migrate subscribers.
- Core Terms remains live canonical UUID authority.
- Subscriber migration, generalized dependency graphs, semantic diffs, snapshots, record migration, and hard-delete automation remain deferred unless later explicit authority proves otherwise.
- The MVP assessment is historical/superseded planning authority.
- Current guidance contains at least one older “next manager objective” statement that may no longer describe current implementation state.
- DV-DIAG005 reported that a prior DV-MGR001 implementation exists and its reports are archived. This must be independently verified before sequencing further manager work.

Scope:

A. Establish implementation chronology

Inspect Git history, completion reports, Hopper/report artifacts where available, and current source.

Identify all work materially related to:
- View Manager organization;
- stable View/version grouping;
- draft/latest-published/history presentation;
- subscriber counts or subscriber identity;
- pinned-version visibility;
- dependency/impact visibility;
- subscriber migration affordances;
- version comparison/diff;
- manager lifecycle actions.

For each capability classify:
- IMPLEMENTED + ACCEPTED
- IMPLEMENTED + NOT ACCEPTED
- PARTIAL
- DOCUMENTED/PLANNED ONLY
- DEFERRED
- SUPERSEDED
- ABSENT

Do not infer implementation from a roadmap entry alone.

B. Resolve DV-MGR001 identity

Locate the reported prior DV-MGR001 implementation and determine:
- exact ticket title/objective;
- completion report;
- repository;
- branch;
- commit(s);
- push state;
- files/owners changed;
- verification performed;
- whether human visual acceptance occurred;
- whether current HEAD contains the implementation;
- whether later work superseded or altered it.

If “DV-MGR001” was used for a materially different objective than ChatGPT recently assumed, make that explicit.

C. Inspect current manager state

Perform source inspection and proportionate authenticated runtime inspection sufficient to determine what the current manager actually provides.

Do not redesign it.

Establish whether the manager currently:
- groups versions beneath stable Views;
- identifies the active draft;
- identifies latest published;
- exposes historical published versions;
- identifies subscriber-pinned versions;
- exposes subscriber counts;
- exposes subscriber identities/context;
- distinguishes latest from subscribed without duplicate conceptual rows;
- provides expandable/secondary history;
- exposes migration/rebinding actions.

Browser work, if needed, must use:
bash tools/qa/verify-views-browser-qa.sh

Preflight READY proves browser control only.

D. Reconcile durable documents

For each relevant statement in Cursor, Handoff, Roadmap, Product Specification, and closeout, classify it:
- CURRENT
- HISTORICAL
- STALE
- CONTRADICTORY
- DEFERRED

Pay particular attention to phrases such as:
- “next implementation boundary”
- “next manager objective”
- “next ticket”
- “next five planned tickets”
- historical DV-UX sequencing
- Community next-consumer language
- Job Center integration timing

Do not delete useful historical chronology.

Where a statement is stale because its described work has already been completed, rewrite or annotate it so it cannot reasonably be interpreted as current execution authority.

E. Establish current phase and next boundary

Produce one explicit current-state statement answering:

1. What phase is Durable Views in now?
2. What post-MVP capabilities are already complete?
3. What remains intentionally deferred?
4. What work can proceed independently while Job Center's wizard catches up?
5. What work must wait for Job Center?
6. What is the smallest coherent next implementation objective?

F. Establish next-five planning horizon

Produce a reasoned EXPECTED NEXT FIVE TICKETS list.

Rules:
- planning horizon only; only the first becomes eligible for later execution;
- do not include already-completed objectives;
- do not promote deferred capabilities without explaining why they are now appropriate;
- account for the parallel Job Center dependency;
- prefer useful independent Views work while Job Center catches up;
- stop the list where evidence no longer supports confident sequencing rather than inventing filler.

Autonomy:
Codex may inspect documentation, reports, Hopper/archive indexes, Git history, current source, and authenticated runtime.

Codex may update durable Views documentation where the audit proves statements stale or contradictory.

Codex may add a narrowly scoped governance note if needed to distinguish historical ticket ledgers from current execution authority.

Do not change application behavior.

Do not mutate Views, Core Terms, Jobs, subscriber bindings, or production data.

Acceptance:
This ticket passes only if the final report establishes:

1. The exact identity and disposition of the previously implemented DV-MGR001.
2. The actual current View Manager capability set.
3. Which manager/subscriber/dependency objectives remain genuinely unimplemented.
4. Which roadmap/handoff statements were stale or misleading.
5. Durable documents no longer present completed historical work as the current next ticket.
6. DV-FIX004 is recorded as complete with human visual acceptance.
7. The current Job Center subscriber state is accurately represented.
8. The current project phase is explicit.
9. One smallest coherent next implementation boundary is named.
10. A non-executable expected-next-five planning horizon is provided and reconciled with current evidence.

Verification:
Documentation/source work:
- inspect final diffs;
- verify cross-document consistency;
- verify referenced commits/reports exist;
- git diff --check.

Runtime:
Use authenticated manager inspection only to resolve capability uncertainty.
Do not perform broad UI acceptance or responsive testing.

Human visual acceptance:
NOT REQUIRED unless this ticket unexpectedly changes user-facing application code, which is outside scope and should instead trigger STOP.

Consolidation gate:
Before completion:
- remove superseded diagnostic notes that were created only temporarily;
- ensure one current-state statement exists across Cursor/Handoff/Roadmap rather than conflicting “next” instructions;
- preserve historical ticket chronology but clearly label it historical;
- confirm no application source changed;
- inspect final documentation diff;
- rerun consistency checks.

Exclusions:
Do not:
- implement new View Manager capability;
- redo completed manager work;
- execute subscriber migration;
- build dependency graphs;
- build semantic diffs;
- change the Job Center wizard;
- alter Job Center bindings;
- change Core Terms;
- change schema/resolver/lifecycle;
- change the Draft Editor;
- modify production;
- clean unrelated dirty work;
- rewrite Git history.

Git:
If canonical governance documents require correction, stage only those authorized documentation changes in the owning repository.

Commit and push documentation reconciliation if established project governance requires durable authority updates.

Do not create an application-code commit.

Report repository, branch, commit, upstream, push result, and unrelated remaining status.

Reporting:
Produce one status-first formal Views report and validated Report/Hopper cycle.

Report sections:
- terminal status;
- DV-FIX004 acceptance closure;
- implementation chronology;
- DV-MGR001 identity/disposition;
- current manager capability matrix;
- stale/contradictory guidance findings;
- documents corrected;
- current project phase;
- Job Center dependency boundary;
- next implementation recommendation;
- EXPECTED NEXT FIVE TICKETS;
- browser/runtime classification if used;
- Git/push;
- preserved unrelated work;
- remaining limitations.

Stop boundary:
Stop only for:
- genuinely contradictory authority that cannot be resolved by the established hierarchy;
- missing repository/report evidence necessary to determine whether a supposedly completed objective actually occurred;
- a product decision required to choose between materially different next directions;
- inaccessible required repository/worktree;
- or a human-only authentication prerequisite after bounded recovery.

Do not stop merely because historical documents disagree. Reconcile them using accepted implementation and the authority hierarchy.
This ticket should give us the clean roadmap/next-five baseline we thought we already had, but this time grounded in the actual repository and completed work rather than stale planning prose.

---

**🙍🏻‍♂️ You:**

dv-gov005-completion-report-260810195050-views-260810195050.mdFileoutput-260810195050-views-260810195050.txtDocumentok, where were we? is this report new?

---

**🤖 ChatGPT:**

🚩 NEW REPORT — yes. This is the completion report for DV-GOV005, the ticket we had just issued before the interruption.
Where we were
DV-FIX004 had been completed and you visually accepted it. We then issued DV-GOV005 because we had discovered that our roadmap contained stale sequencing language and that some work I thought was upcoming—particularly DV-MGR001—had actually already been implemented.
DV-GOV005 has now completed that reconciliation successfully. 
What it found
The important correction is substantial:
DV-MGR001 is already done. It wasn't future work. The manager was implemented across:


a405aed — organize manager


1bc770b — converge manager layout


be5b189 — safely manage empty Views


5f6d094 — group Views by priority


Those changes remain in current HEAD. 
And the current manager already provides most of what we were discussing as future work:


stable Views with versions beneath them;


Subscribed / Published / Draft organization;


active draft visibility;


latest published visibility;


historical versions behind See all versions;


subscriber-pinned version visibility;


subscriber count and identity/context — including Job Center — Grade Level;


safe handling of empty/unused Views. 


So our suspicion was correct: the roadmap had drifted behind the implementation, and my earlier interpretation of it caused us to start planning already-completed work.
Codex fixed the authority
It reconciled:


Project Cursor


Engineering Handoff


Roadmap


V1 Product Specification


Historical “next manager objective,” DV-UX009 transitions, and similar stale sequencing language are now explicitly historical rather than executable. Documentation commit e4d4438 was pushed successfully. 
Where the project actually is now
Stabilization — standalone MVP and manager organization certified; next consumer authorization pending.
Job Center is still deliberately pinned to View 10 / published version 12. Nothing migrated. 
And importantly, Codex refused to manufacture five tickets where the evidence didn't support them. The currently defensible planning horizon is:


DV-CONSUMER001 — Select and assess the next consumer seam


DV-CONSUMER002 — Implement that consumer adapter, conditional on what #1 finds


DV-DEP001 — Consumer-specific dependency/impact visibility, only if that consumer actually needs it


It deliberately left #4–5 undefined rather than promoting deferred features like semantic diffs, migration tooling, snapshots, or release nicknames without a product reason. 
The decision now belongs to us
The next ticket is no longer a technical cleanup. We need to decide which consumer Views should work on while Job Center catches up.
Community was historically identified as the next candidate, but earlier seam assessments found an external source/access prerequisite. 
So before I issue DV-CONSUMER001, we should choose whether to:
revisit Community now, choose another Teachers.Net consumer that is currently accessible, or deliberately hold Views until Job Center is ready.
My preference is revisit Community first, because it was already the planned second consumer and we have prior seam analysis to build from rather than starting another product from scratch.

---

**🙍🏻‍♂️ You:**

I think this might be a smart time to work on the profile and onboarder system. Let's discuss how that system would ideally live inside Teachers.Net. Is it a plugin? Two plugins?

---

**🤖 ChatGPT:**

I would design this as two conceptual systems, but probably begin with one platform plugin containing two clearly separated modules/services. Split them into separate plugins only if their ownership/deployment boundaries prove independently useful.
The key architectural distinction is:

Profile = durable state about a person.
Onboarder = a workflow that gathers, derives, validates, and updates that state.

Those are related, but they should never become the same object.
Where they fit in Teachers.Net
I would extend the platform model we've been converging on:
WordPress User
      │
      ▼
Teachers.Net Profile
 durable person/account data
      │
      ├── canonical Core Term references
      ├── preferences / interests
      ├── professional attributes
      ├── privacy / visibility choices
      └── completion / verification state
      │
      ▼
Audience / Product Services
      │
      ├── Community
      ├── Job Center
      ├── Lesson Bank
      ├── Directories
      └── other consumers
Alongside it:
Onboarder
   │
   ├── asks questions
   ├── presents choices
   ├── validates answers
   ├── resolves Core Terms
   ├── manages progress
   └── writes through Profile services
            │
            ▼
         Profile
That distinction gives us a major long-term advantage: the onboarding UI can change completely without changing what a Teachers.Net profile is.
My preferred implementation
Initially:
tnet-profile — one platform plugin
Inside it, keep explicit modules such as:
tnet-profile/
    includes/
        profile/
            repository
            service
            schema
            permissions
            visibility
        onboarding/
            workflow
            step definitions
            progress
            validation
            controller
        integrations/
            core-terms
            views
            wordpress-user
User-facing/admin routes could still be distinct:
/profile/
/profile/edit/

/welcome/
/onboarding/
I would not put the profile system into Profilaxes/Core Terms. Core Terms should remain taxonomy authority. Profiles should reference canonical UUIDs rather than become another Core Terms responsibility.
Similarly, I would not put it into Community, Jobs, or another consumer plugin. Profile is a platform capability that those products consume.

Why not two plugins immediately?
We certainly could create:


tnet-profiles


tnet-onboarding


Architecturally that's clean, but initially it buys relatively little and introduces additional seams:


activation/dependency order;


version compatibility;


cross-plugin service contracts;


duplicated bootstrap/capability work;


another repository/plugin lifecycle;


more Codex/test/deployment surface.


The onboarder fundamentally depends upon Profile. There probably isn't a meaningful scenario where Teachers.Net runs onboarding without Profiles.
So I'd start with one plugin, two hard module boundaries.
If onboarding later becomes a large workflow engine reused for employer onboarding, organization onboarding, moderator onboarding, contributor onboarding, etc., then we have evidence for extraction.
At that point:
tnet-profile
      ▲
      │ writes through API
      │
tnet-onboarding
becomes sensible.
We should architect today's module so extraction would be boring rather than revolutionary.
What belongs in Profile
This is where I think we need to be disciplined. A profile should contain durable facts/preferences pertaining to the user, not everything every Teachers.Net product ever learns about them.
For example:
Belongs in ProfileDoes not belong in ProfileDisplay nameJob applicationsAvatarPosted jobsBioCommunity postsLocation / regionLesson recordsEducator roleNotificationsGrade-level interestsSearch historySubject interestsProduct-specific workflow stateProfessional experienceJob Center employer membershipProfile visibilityCommunity moderation stateCommunication preferences where genuinely platform-wideEvery consumer's settings
Products can reference the profile rather than shovel their data into it.
That avoids turning tnet-profile into the platform's junk drawer.
Core Terms becomes particularly valuable here
This is where the work we've already done starts paying dividends.
Suppose onboarding asks:
What grade levels do you work with?
Instead of storing:
"Elementary School"
we should store the canonical Core Term UUID.
Likewise:


Subject Area


Grade Level


Location where appropriate


educator/professional classifications if modeled canonically


The profile then stays durable even when labels change.
And Views can potentially determine which terms a particular onboarding experience offers.
That gives us:
Core Terms
   │
   ▼
View: Teacher Onboarding — Subjects
   │
   ▼
Onboarder asks user
   │
   ▼
Profile stores canonical UUID selections
That is a very elegant reuse of the platform we're building.
Crucially, the Onboarder does not reconstruct taxonomy and doesn't own its own list of subjects.
Onboarding should be configuration-driven
I would avoid coding onboarding as:
PHPif ($step === 1) { ... }
if ($step === 2) { ... }
Instead, define a workflow:
Teacher onboarding
  1. Identity
  2. Professional role
  3. Grade levels
  4. Subjects
  5. Location
  6. Community interests
  7. Profile visibility
  8. Finish
Each step has something like:
id
title
field/service target
input type
required/optional
validation
View reference where applicable
condition
completion rule
Then the workflow engine handles:
render → validate → persist → advance → resume
This becomes important because onboarding will probably diverge by person type.
A classroom teacher may get:
Role → Grade → Subject → Community
An administrator:
Role → School → Leadership areas → Community
A job seeker might get a later optional branch.
We should be able to alter these flows without schema surgery.
One profile, progressive onboarding
I also would not treat onboarding as something a user completes exactly once and can never encounter again.
Think of three concepts:
Profile completeness
What useful profile information exists?
Onboarding progress
Where did this user stop in this workflow/version?
Prompts/opportunities
What useful information could we ask later?
Someone could register with only:
email
username
password
Then we progressively enrich:
Welcome, Bob
What best describes you?

Teacher
Administrator
Counselor
Other
They can leave after step 2 and return next week.
The site must remain functional; onboarding shouldn't become a hostage situation.
Version the workflow
This is subtle but important.
If we change onboarding six months from now, don't pretend somebody who completed the old flow completed the new one.
Store something like:
workflow_key: teacher_initial
workflow_version: 3
status: in_progress | completed | skipped
current_step: subject_interests
started_at
completed_at
But the answers themselves live in Profile.
So deleting onboarding history doesn't delete someone's profession or interests.
That's the separation we want.
Privacy needs first-class architecture
Profiles create a different risk profile from Views/Core Terms because we're now storing information about people.
Fields should eventually have explicit visibility semantics such as:
private
members
public
Perhaps some fields aren't eligible for public exposure at all.
Consumers should request profile presentation through a service rather than simply reading raw usermeta and deciding independently what can be shown.
Eventually:
TNet_Profile_Service::get_public_profile($user_id)
TNet_Profile_Service::get_member_profile($user_id, $viewer_id)
would be much safer than:
PHPget_user_meta(...)
scattered through Community, Jobs, and Directories.
Avoid making wp_usermeta the architecture
We can use WordPress's user identity and possibly usermeta for very small compatibility values, but I would want an explicit Profile repository/schema fairly early.
Once we have multivalued Core Term references, field visibility, provenance, completion state, onboarding versions, etc., generic usermeta gets ugly quickly.
Something conceptually like:
tnet_profiles
tnet_profile_fields / typed columns
tnet_profile_term_refs
tnet_onboarding_progress
The exact schema should come after an audit, not from this conversation.
And wp_users.ID can remain the authentication/account foreign key. We do not need another identity system.
Registration is not onboarding
Another important separation:
Account creation
      ↓
minimum WordPress identity exists
      ↓
Onboarding begins
      ↓
Profile becomes richer
Registration must remain small and reliable.
That gives us options later:


Google/Apple login;


magic links;


invitations;


imported legacy users;


community-only signup;


employer signup.


All of them can arrive at the same onboarding service.
Where I think this gets especially powerful
Eventually the Profile becomes a platform substrate:
             ┌── Community
             ├── Job Center
Profile ─────┼── Directories
             ├── Lesson Bank
             ├── Messaging
             └── Recommendations
But each consumer gets only the appropriate presentation/permissions, not raw ownership.
For example, Community could show:

Bob
High School Teacher
Science • Technology
San Diego, CA

Job Center might use some of the same canonical interests to prefill search preferences.
Directories might use verified professional attributes.
That's reuse without duplication.
One architectural caution
I would resist immediately making the onboarder a generic universal form-builder. That's an easy architecture trap.
First build:

Teachers.Net member onboarding

Then prove the primitives.
If later employer onboarding needs the same engine and 80% of it genuinely transfers, extract/generalize it.
Otherwise we'll spend three weeks building a workflow framework before a single teacher completes a profile.

Recommended product boundary
I think the clean architecture is:
tnet-profile


owns durable member profile


owns privacy/visibility policy


stores canonical Core Term references


exposes platform profile services


contains the first member-onboarding module


owns onboarding progress, but not the semantic answers separately from Profile


Core Terms


owns canonical meaning


Views


may supply curated selectable term sets to onboarding


WordPress


owns authentication/account identity


Community / Jobs / Lesson Bank / Directories


consume Profile; they don't own the canonical member profile


And I would build this as one plugin initially, with a deliberate architectural seam between Profile and Onboarding so it can become two plugins later without redesign.
Before implementation, the correct first ticket would be an architecture/repository seam audit: inspect existing user/profile/community/account code and determine what Teachers.Net already has that we must preserve rather than designing over it. That would tell us whether tnet-profile is actually greenfield or whether there's legacy profile authority we need to wrap/migrate.

---

