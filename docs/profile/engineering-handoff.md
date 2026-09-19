# Teachers.Net Profile Engineering Handoff

## 1. Current Phase

Active Development — Screens 3 Photo/Avatar, 4 Public Identity, and 5
voluntary Location Context are COMPLETE/FROZEN after Engineering Director
HUMAN_QA PASS. The Profile resolver remains the canonical representation owner;
the 268-entry frozen portrait bank is read-only chooser content, not
member-demographic data.

## 2. Current Ticket

`PROFILE-ONBOARDING-LOCATION-SCREEN5-CLOSEOUT001` terminalized the bounded
optional location step. `/account/location/` accepts an explicit U.S.
State/District of Columbia or non-U.S. country, persists only canonical country
and region codes through `TNet_Identity_Service`, clears inactive unsaved
values on a mode change, and lets Skip continue without fabricating a default.
Screen 4 routes to this step while it is pending; valid Continue and Skip go to
`/jobs/`. There is no city, ZIP, street, precise, or inferred location
collection. Native DDEV evidence covers U.S., international, switch/reset,
Skip, route chaining, intermediate, and 390px containment. Screen 5 is
COMPLETE/FROZEN. See `docs/profile/location-screen5-contract.md`.

## 3. Last Completed Milestones

## Historical Curation002 checkpoint

`PROFILE-AVATAR-PORTRAIT-BANK003-CURATION002` imported the supplied
Director export's 230 `final_presentation_retrieval_bucket` assignments
exactly. That classification is authoritative. The portable Curation002
package expands the review population to 310 with 80 new E++ candidates,
exactly 10 per Generation x Women/Men retrieval cell. The new batch targets
fair/light blond/light-haired self-representation and includes ordinary
bald/thinning/shaved breadth across Gen X and Boomer+ Men. Its Director review
later completed through FINALIZE001; retained content is now represented by the
frozen 268-entry bank and removed content is historical only.

The root-flat portable archive is `portrait-bank003-curation002-portable.zip`.
`review.html` contains the complete grouped reversible remove sorter; it
persists decisions locally, has Removed tray/Undo/Restore all, and exports a
complete 310-row ingestible decision set. `batch6-visual-review.html` contains
source, 64/48/32 circles and a 48px feed view. The transported archive was
independently extracted and served: every manifest file verified, 310 review
masters and 400 Batch-6 rendered instances loaded with zero broken images, and
native 390px had client width equal to scroll width. The next authority is
Director review and the returned `RETAIN | REMOVE` export — not a re-audit of
Director's classification or runtime activation.

Profile is the canonical first-party avatar representation owner. It owns the
selected attachment, resolver, WordPress `get_avatar()` integration, and
conditional legacy BuddyPress compatibility. The rejected component-set-v1-r4
adds no resolver precedence. The recommended future model is a commissioned,
curated collection of complete portraits with only safe background variation;
that recommendation is not production authorization.

## 4. Next boundary

The next product boundary is intent-aware member context / onboarding fork.
Director product design must determine how ordinary educator/member,
employer/recruiter, and other/general intent is distinguished and used, so
employer/recruiter users are not asked irrelevant teacher-specific questions.
Potential role, grade, and subject enrichment remains UNDEFINED. Original entry
intent may require eventual restoration where existing architecture supports
it, but no mechanism is authorized here.

## 4. Next Five Planned Tickets

1. Authorize and define the intent-aware member-context/onboarding-fork
   objective before implementation.
2. Preserve the frozen 268-entry bank and 42-row removal archive; do not
   acquire artwork, restore removed portraits, or change the resolver without
   a new Director decision.

## 5. Current Blockers

- Screens 3, 4, and 5 are COMPLETE/FROZEN.
- No current Profile product blocker is known; the next boundary requires
  Director product authority.

## 6. Recently Adopted Governance Documents

- Shared START-CODEX and PROJECT-BOOTSTRAP-SPEC.
- Profile project record: `docs/process/conversation-handoff/projects/profile.json`.

## 7. Recently Approved Product Decisions

`docs/profile/avatar-architecture.md` is the canonical selected-avatar and
legacy-compatibility contract. Profile selections win; consumers render a
Profile result and do not choose a competing source.
`docs/profile/avatar-component-set-v1-provenance.md` records the first-party
component-source and rights boundary, but its art direction is rejected for
production. The pilot uses finished complete portraits for visual curation only;
their production rights and runtime use remain unapproved.

## 8. Recently Approved Visual References

The active frozen artwork fixture is the root-flat
`portrait-bank-v1-final.zip`, published in the current Profile Report/Hopper
payload. It contains only the 268 retained masters, the canonical frozen
manifest, final coverage ledger, and historical 42-row removal archive. The
prior `portrait-bank003-curation002-portable.zip` remains historical review
evidence for the 310-person decision surface. Neither fixture is a product
route or changes the resolver.

## 9. Active Design Authority

`docs/profile/avatar-architecture.md`,
`docs/profile/avatar-component-set-v1-provenance.md`, and finalized
`PROFILE-AVATAR-ART-STRATEGY001` evidence.

## 10. Immediate Engineering Priorities

1. Authorize and define the post-avatar public-identity/profile objective before
   implementation.
2. Keep artwork acquisition, generated-avatar generation, and resolver changes
   deferred; the retained bank is frozen.

## Canonical Review URL (PROCESS-GOV001)

- Canonical Engineering Director review URL: Not established.
- Verified against canonical URL: YES — Screen 3 native evidence and Director
  HUMAN_QA PASS are recorded; no new browser run was required for closeout.

## Google Drive Synchronization State (PROCESS-GOV002)

- Drive sync primary-code transitions: 0 / 10
- Last successful Drive sync: Unknown — baseline not yet recorded.
- Next sync trigger: PREPARE HANDOFF, explicit request, or milestone transition.

## Screen 3 final acceptance — cycles 260917133730 through 260918234713

`PROFILE-ONBOARDING-AVATAR-SCREEN3-001` is implemented at the existing
Profile avatar owner and is HUMAN_QA_PASS / COMPLETE_FROZEN for product
acceptance. The frozen 268-entry
`portrait-bank-v1` manifest is consumed read-only by the onboarding chooser;
selected opaque portrait IDs and first-party uploaded photos resolve through
the same Profile resolver. Native DDEV evidence covered verified signup to
Screen 3, progressive Generation to Women/Men filtering, portrait persistence
after reload/Profile navigation, real photo preview/upload persistence and
replacement, stable skip assignment for two disposable users with distinct
portraits, and 390px no-overflow containment. Disposable QA users and the
temporary uploaded file were removed after verification.

The browser connector was unavailable in those cycles, so native evidence used
the already-approved direct Chrome DevTools binding. No bank artwork, Director
classification, or production state changed during that historical closeout.
Source-control recovery establishes this repository as the canonical Identity
owner; see `docs/profile/tnet-identity-source-ownership.md`. The later Screen
4 and Screen 5 objectives are now COMPLETE/FROZEN; do not treat this historical
checkpoint as current authority.
