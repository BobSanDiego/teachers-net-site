# Teachers.Net Profile Project Cursor

Project state: Active Development

## Identity

- Project: Teachers.Net Profile
- Project record: `docs/process/conversation-handoff/projects/profile.json`
- Repository: `/home/bobreap/projects/teachers-net-site`

## Current boundary

`PROFILE-ONBOARDING-AVATAR-SCREEN3-001` is product-accepted and FROZEN after
Engineering Director HUMAN_QA PASS. Its photo-first onboarding, frozen-bank
chooser, crop/adjust/change/remove, skip and random-assignment paths,
filtering, explicit selection gate, selected confirmation state, live shell
preview, persistence/resolver behavior, and responsive containment remain
PROVEN. The frozen 268-entry `portrait-bank-v1` is consumed read-only by the
Screen 3 chooser; retrieval labels remain artwork metadata rather than member
demographics.

The bounded first-party avatar capability is implemented with Profile as the
canonical resolver/representation owner. Engineering Director HUMAN_QA rejected
the `component-set-v1-r4` fully procedural artwork for production on visual
quality/coherence grounds. It remains an unactivated diagnostic control only;
it does not participate in the live resolver. The Director has authorized the
bounded, development-only `PROFILE-AVATAR-ART-CURATION-PILOT001`, now passed
by Director HUMAN_QA for E++ visual language and circle-safe composition. The
separate `portrait-bank-v1` Batch 1 contains 48 opaque 1254px square,
circle-safe E++ production candidates: six in every Generation × Women/Men
retrieval cell. Director HUMAN_QA visually accepted all 48; their current
state is `DIRECTOR_VISUAL_APPROVED` and `PRODUCTION_USE_ELIGIBLE` subject to
the documented non-exclusive/non-uniqueness/non-infringement limitations.
`PROFILE-AVATAR-PORTRAIT-BANK002` added 80 Batch 2 candidates, ten in each of
the same eight retrieval cells; all 80 are Director visually approved and
`PRODUCTION_USE_ELIGIBLE`. Director HUMAN_QA subsequently approved all 72
Batch 3 candidates, making the existing 200-person bank approved and eligible.
The current `PROFILE-AVATAR-PORTRAIT-BANK003-HUMAN-CURATION001` preserves the
existing 216 masters without changing their artwork or durable retrieval
metadata, then adds 14 unreviewed Batch 5 coverage candidates: eight
fair/light-complexion blond/light-blond choices across every Generation ×
Women/Men retrieval cell and six bald/shaved Men choices (four fair/light,
including Gen X and Boomer+, plus two broader appearance treatments). The
Director-controlled sorter now contains 230 portraits in exactly Women/Men
pools. Its prior 42 Codex retrieval corrections are only provisional starting
assignments until the Director returns the exported final assignments. The
root-flat `tnet-portrait-director-sorter.zip` contains no enclosing directory
and a 42-character maximum internal path; it was independently
extract-and-serve verified. That historical review population is superseded by
the final retained bank consumed by Screen 3. The
E++ 24, E+ 32, and original 64-candidate banks remain history/reference only
and are not automatically production assets.
Generated candidate sources and historical review artifacts are not additional
production assets and do not assert exclusive rights. Broader Profile schema,
later onboarding, and production-art expansion remain outside this boundary.
The durable contract is `docs/profile/avatar-architecture.md`.

`PROFILE-AVATAR-PORTRAIT-BANK003-FINALIZE001` has now ingested the Director's
310-row retain/remove export exactly. The frozen `portrait-bank-v1` contains
268 retained masters: 44 Batch 1, 47 Batch 2, 68 Batch 3, 16 Batch 4, 14
Batch 5, and 79 Batch 6. Forty-two Director-removed portraits are excluded
from the final asset/chooser inventory and preserved only in the historical
removal archive with ID, batch, classification, hash, and provenance. Every
retained entry is `DIRECTOR_VISUAL_APPROVED` and
`PRODUCTION_USE_ELIGIBLE`; the 1254px square and centered circle-safe master
contract is unchanged. Portrait-bank artwork/content selection is FROZEN.

## Evidence and authority

## Portrait-bank Curation002 checkpoint — 2026-09-17

`PROFILE-AVATAR-PORTRAIT-BANK003-CURATION002` has ingested the Director's
complete 230-row classification export exactly. Its
`final_presentation_retrieval_bucket` is now the authoritative Women/Men
artwork retrieval assignment; Codex made no reclassification. The portable
Curation002 lab adds 80 new, unreviewed E++ candidates — exactly ten in every
Generation x Women/Men retrieval cell — for a 310-portrait review population.
The additions materially expand fair/light and blond/light-haired appearance
choices, with ordinary bald/thinning/shaved choices added across Gen X and
Boomer+ Men. They remain `HUMAN_QA_PENDING`, as do the pre-existing Batch 4
and Batch 5 candidates; no candidate is connected to the Profile runtime.

The current portable root-flat artifact is
`portrait-bank003-curation002-portable.zip`. Its `review.html` is the
Director's complete reversible duplicate/redundancy sorter: click to mark
`REMOVE`, use the Removed tray/Undo/Restore all, and export the complete
310-row `RETAIN | REMOVE` decision set. It is an authoritative Director
decision input, not a request for Codex interpretation or a physical deletion
mechanism. The companion `batch6-visual-review.html` provides source-square,
64/48/32 circular, and 48px feed-scale review. The package was independently
extracted and served; 310/310 review images and 400/400 Batch-6 rendered
instances resolved, with desktop and native 390px containment passing.

The supplied 2026-08-11 ChatGPT transcript is preserved as conversation
evidence under `docs/process/conversation-handoff/profile/chatgpt-sources/`.
It is not automatic product or architecture authority. The resolver
convergence is implemented only within
`PROFILE-AVATAR-RESOLVER-CONVERGENCE001`. A disposable local user completed
the canonical Profile selection flow, then rendered the same resolved first-
party avatar natively in Community and Jobs before complete cleanup. Engineering
Director HUMAN_QA remains the resolver acceptance gate. The later
`PROFILE-AVATAR-COMPONENT-SET001` candidate has independent native review
evidence and awaits Director art acceptance; it does not change resolver state.

## Known preliminary direction

The conversation discusses a durable member Profile, onboarding as a workflow,
privacy and communication intent, and future relationship policy. These remain
candidate direction pending explicit product and architecture decisions.

## Immediate next boundary

Screen 3 remains COMPLETE/FROZEN. Screen 4 Public Identity is
COMPLETE/FROZEN following Engineering Director HUMAN_QA PASS under
`PROFILE-ONBOARDING-PUBLIC-IDENTITY-SCREEN4-CLOSEOUT001`. `/account/identity/`
displays the Profile-resolved avatar
and permanent username, defaults the required Display Name from username
without persisting on GET, validates/persists it server-side on submit, and
continues to the next pending onboarding step. The shared focused-shell layer owner now puts journey
content above the overlapping rail at the 901px+ state without a route-local
z-index patch. The same existing Profile resolver supplies the shell avatar
(portrait selection or its normal fallback). `TNet_Identity_Policy` is the
single server authority for username reservation and Display Name validation;
duplicate Display Names are intentionally permitted. No availability lookup or
additional profile field belongs to this step.

The complete first-party `tnet-identity` and `tnet-shared-shell` source
boundaries are published through the isolated Profile branch
`codex/profile-screen4-closeout`, derived from the upstream branch before the
unrelated local Community history. Git provenance terminalization is COMPLETE;
the terminal Workflow V2 report records the immutable commit identity. Active
Community history remains untouched.

Screen 5 voluntary Location Context is COMPLETE/FROZEN following Engineering
Director HUMAN_QA PASS under
`PROFILE-ONBOARDING-LOCATION-SCREEN5-CLOSEOUT001`. `/account/location/`
accepts only an explicit U.S. State/District of Columbia or non-U.S. country,
stores canonical country and (for the U.S.) region codes, and has no
city/ZIP/street/precise or inferred-location path. Skip stores nothing and
continues to `/jobs/`. The durable scope/ownership contract is
`docs/profile/location-screen5-contract.md`.

The immediate next product boundary is intent-aware member context / onboarding
fork. It must establish how known or selected intent serves ordinary
educator/member, employer/recruiter, and other/general members so an employer
or recruiter is not asked irrelevant teacher-specific questions. Potential
educator role, grade, and subject enrichment remains UNDEFINED. Original entry
intent may need eventual restoration where existing architecture supports it,
but no mechanism is defined or implemented by this closeout. Do not regenerate
or recurate the bank, persist retrieval filters as demographics, or alter the
Profile resolver without new authority.

## Source-control recovery — 2026-09-19

The Director's HUMAN_QA PASS remains recorded above. The recovery audit
established `/home/bobreap/projects/teachers-net-site` as the canonical Git
owner of the complete eight-file `tnet-identity` plugin source. The former
ignore state came only from the broad historical `wordpress/` runtime rule;
there is no alternate repository, generated/runtime payload, or secret-bearing
configuration in the plugin boundary. The root ignore now re-includes only this
complete first-party plugin. See
`docs/profile/tnet-identity-source-ownership.md`.

The canonical source commit is local. Its remote push is intentionally pending:
the active branch already contains an unrelated unpushed Community commit, and
a normal branch push would publish both. This is an external-publication scope
boundary, not a source-owner or product-acceptance blocker.

## Persistent local QA fixture

The local DDEV environment retains a verified QA account for reusable
signup/Profile/avatar review: username `test`, email `test@tnet.test`, WordPress
user ID 353. This fixture is LOCAL-DDEV-ONLY and must never be created or used
in production or another shared environment. Do not delete it during routine
QA cleanup; use disposable unique accounts when a test requires fresh-account
or uniqueness behavior. Credentials remain Director-controlled and are not
stored in this document.
