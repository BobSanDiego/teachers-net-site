# Teachers.Net Profile Engineering Handoff

## Current handoff — PROFILE-V1-PUBLIC-COLUMN-PARITY042

`PROFILE-V1-PUBLIC-COLUMN-PARITY042` is implemented on the isolated Profile branch. The public renderer now places the unchanged hero plus conditional About, Teaching Profile, and Groups cards in one shared width-owning column. Native direct DDEV Chrome/CDP measurements prove exact left/right/width parity at 1440 (x=334, w=662), 1024 (x=288, w=662), 768 (x=254, w=474), and 390 (x=24, w=342), with no horizontal overflow. Public owner-preview uses the same wrapper. At 1440 the right rail remains x=1044, w=300, y=14, h=972. `/profile/` and `/profile/edit/` were checked at 1440/390: their hero retains `max-width:662px`, and no content-column wrapper is rendered. The test member has no public About/Teaching Profile facts, so those conditional cards are not currently present; source markup confirms both are within the same wrapper as the rendered Groups card. Source and DDEV runtime PHP/CSS are byte-identical; PHP syntax check passes. Director HUMAN_QA remains pending; no production change. Workflow V2 cycle `260929200959` owns the exact geometry, responsive results, parity hashes, and acceptance ledger.

The preceding `PROFILE-V1-CARD-PARITY041` acceptance remains carried forward, including the Director-PASS self-view baseline, owner/public projection distinctions, and disclosure interaction. This ticket did not mutate fixtures or data.

Next: Director HUMAN_QA review of the public body-column parity. Preserve all 041 accepted seams and the separate production username-collision gate.

## 1. Carry-forward history

`PROFILE-V1-SELF-CARD-SPACING040B` is the active Director review boundary in Workflow V2 cycle `260929164636`, on isolated branch `codex/profile-v1-basics-facts`. Only compact `/profile/` self-view spacing and camera-control CSS changed. Native geometry with all four private QA lanes proves identity→Location +7px; Location→Grades, Grades→Subjects, Subjects→Roles each −9px; Roles→date remains 7px; card bottom padding −9px at both 1440 and 390. The camera is 4px smaller at each responsive size; its circle/glyph are moved 2px left/up relative to the post-resize anchor and remain centered at the same point on the avatar edge. No overflow; 390 console is clean. Desktop screenshot is packaged; the mobile screenshot RPC hung, so the 390 proof is computed DOM/geometry rather than an image. The reusable local user-353 QA fixture was populated through the existing Profile editor for decisive lane measurements, then its Profile facts and location were restored to the inspected empty/absent starting state. Location visibility was never changed. Public Profile, edit-on, identity typography, ellipsis, lower content, and other owners remain unchanged. Engineering verification is complete; Director HUMAN_QA remains pending.

`PROFILE-V1-SELF-CARD-COMPACT040A` remains the immediately preceding review boundary in cycle `260929160011`, commit `de7631124689b5db076aac796a4a7e881cb3638a`; 040B preserves its accepted identity line and typography.

`PROFILE-V1-SELF-CARD-COMPACT040` is the immediately preceding compact owner-card geometry/interaction baseline (cycle `260929143705`, commit `5a1e4a7`); 040A changes only the identity presentation specified above.

`PROFILE-V1-SELF-CARD-PARITY039` remains the immediately preceding accepted owner-card baseline (cycle `260929125522`). It remains authoritative for edit-on and public presentation, the shared hero renderer, stored-fact owner projection, lane/disclosure behavior, and all below-card sections; 040 changes only `/profile/` self-view presentation.

`PROFILE-V1-PUBLIC-CARD038` is the active Director review boundary in Workflow V2 cycle `260929122143`. The four pipe dividers shift 3px toward their labels and gain 1px of right-side breathing room; the row/icon/label columns stay fixed, while values begin 2px earlier from the net spacing adjustment. The accepted centered divider height remains 4px shorter than each label (desktop 21/17px; mobile 20/16px single-line and 34.781/30.781px multiline). The dated row moves down only 2px, making the Roles-to-date gap 7px; its +2px calendar inset is preserved, its icon center matches the four facet icons, and its text begins at the common label axis (desktop x=558.5px; 390px x=62px). Document width is 1425px at 1440px and exactly 390px at mobile. Direct DDEV Chrome/CDP evidence includes desktop viewport and card crop; the Roles disclosure opened/closed and console has no messages. Source, DDEV mirror, container CSS, and cache-bypassed served CSS match SHA-256 `cf5e9728cf323a8020a17181fbd5a2afb89e7c2c4c03125728b6e83a06443f1f`. Engineering verification passes; Director HUMAN_QA remains pending.

`PROFILE-V1-PUBLIC-CARD038F` is the immediately preceding Director review boundary, preserved in cycle `260929115345`. It restored the 038D horizontal label/value grid, retained the centered shorter pipes and +2px calendar inset, and reduced the four upper vertical intervals by 6px while keeping Roles-to-date at 5px.

`PROFILE-V1-PUBLIC-CARD038E` was an earlier styling experiment. Its shorter centered pipe and +2px date-icon inset carry forward; its horizontal-gap reduction was superseded by 038F, which restored 038D horizontal geometry.

`PROFILE-V1-PUBLIC-CARD038D` is the prior Director review boundary. CSS restores the dated calendar to 17px, inherited muted `#365a8a`, and 1.8px stroke; the four facet icons are visually lighter; their label weight is 650. Desktop row/calendar icons align at x=530.5px against the identity x=529.5px, with the first lane 18px below username (7px tighter). The 390px full-width lane stack remains aligned internally and moves 7px upward without overflow; identity remains in its existing beside-avatar position. Dated text, values, route/link markup, disclosures, order, and other surfaces are unchanged. Direct Chrome/CDP at 1440/390 confirms geometry, Roles disclosure open/close, zero overflow, and no console errors. Source, DDEV bind-mounted runtime, and cache-bypassed served CSS match SHA-256 `0c081f38fe6f3e277875bd13eb6995969407948187a50bfd67e46322e1ec4efa`. Cycle `260929100946` owns the native captures and acceptance ledger. State: `PUBLIC_PROFILE_CARD_ALIGNMENT_CONVERGED_HUMAN_QA_PENDING`; Director HUMAN_QA remains pending.

`PROFILE-V1-PUBLIC-CARD038C` is the previous Director review boundary. The five top-card information lines share one icon/value grid; date icon aligns to the shared icon column at 21px desktop / 19px mobile. Summary/section gaps are 5px, facet values use weight 500 (labels 750), and date text is regular weight. CSS-only; data, links, ordering, separators, `more` alignment/disclosure behavior, and the unshaded dated line are unchanged. DDEV Chrome/CDP evidence at 1440 and 390 confirms exact shared x-coordinates, no overflow, working Roles disclosure, and clean console. Source/runtime/served CSS matched SHA-256 `46b6f5b7be1fcf8ecbd7e2287e25960a4b771a5e91fe7a4ce980ea2260c5d791`. Cycle `260929094303` owns evidence. State: `PUBLIC_PROFILE_CARD_MICRO_CONVERGED_HUMAN_QA_PENDING`; Director HUMAN_QA remains pending.

`PROFILE-V1-PUBLIC-CARD038B` is the prior Director review boundary. The public Profile top card places canonical public Location first in the shared lane grid, omits it when private/absent, and combines Teaching Since then Member Since in one quiet credential line without shading. Outer lane strokes are transparent; value typography remains at the accepted 038A styling and the value/separator baselines are aligned. DDEV native evidence covers 1440px and 390px, sparse/private location, responsive disclosure popovers, zero horizontal overflow, and no console errors. PHP/CSS source and runtime hashes match. Cycle `260929091147` owns the captures and evidence. State: `PUBLIC_PROFILE_CARD_FINAL_LAYOUT_HUMAN_QA_PENDING`; Director HUMAN_QA remains pending.

`PROFILE-V1-PUBLIC-CARD038A` applies only the Director's final collapsed facet-row style corrections to the accepted 038 baseline: `#0045dd` value color, `600` value weight, one-step smaller value type, and a 7px narrower label/divider column at desktop/mobile. The same normal DDEV `/profile/jobman/` route supplied native desktop/390px captures and computed-style/no-overflow evidence in Workflow V2 cycle `260929001820`; the Profile CSS source and runtime mirror match. No fixture facts, public routing, popover, or data owner changed. State: `PUBLIC_PROFILE_CARD_01C_FINAL_STYLING_APPLIED_HUMAN_QA_PENDING`; next action is Director visual review.

The initial `PROFILE-V1-PUBLIC-CARD038` convergence established the `profile-card-01c.png` visual baseline (SHA-256 `c6334c279d96a5741dba31635125aa65c97b977ea960860b314711aad9e89bc1`) and superseded 037's heavier facet-row treatment. The public Profile owner renders compact facet rows with dot-separated values and far-right overflow controls; the data/privacy behavior, anchored popovers, and QA fixture restoration remain as recorded in cycle `260928232714`. Grade/Subject/Role Community destinations remain unresolved; QA values are non-links rather than fabricated routes. The original state was `PUBLIC_PROFILE_CARD_01C_CONVERGED_HUMAN_QA_PENDING`; its review boundary has since advanced through 038F to the active 038 correction above.

`PROFILE-V1-PUBLIC-CARD037` is the prior card baseline, superseded visually by 038. It maps the exact `profile-card-01b.png` reference (SHA-256 `a69054304785aad0ac0c9d90d9c1414a151a442ea13c89a6794dbbe5c6c81c90`) onto the existing public Profile card without changing the Shared Shell. Pale inset Grades/Subjects/Roles rows with code-native glyphs and a separate Teaching-since badge provide the former compact brag hierarchy; the 036/037 governed values, ordering, and anchored popovers remain the functional baseline. Desktop and 390px normal-DDEV evidence, location-present `/profile/jobman/` readback, no-overflow checks, and restored temporary user 353 are in Workflow V2 cycle `260928214317`. Its historical state was `PUBLIC_PROFILE_CARD_CONVERGED_HUMAN_QA_PENDING`; no Director visual PASS or production activation was inferred.

`PROFILE-V1-PUBLIC-POPOVER-CONVERGENCE037` is the prior popover baseline. It changes only the 036 hero summary separators, quiet `more` triggers, and anchored disclosure presentation; all 036 governed data projection, privacy, owner-preview, Teaching Profile, About, Groups, join rail, and Shared Shell seams are carried forward unchanged. The exact reference is `profile-public-view-01f.png`, SHA-256 `42e1febf363c96faa73a08145bd9ad65bd877576a0538dcfc1d9df5cfd658c44`. Normal DDEV native 1440/1024/768/390, keyboard/ARIA, viewport containment, no hero reflow, privacy-off readback, restored local fixture, and source/runtime parity are recorded in Workflow V2 cycle `260928192329`. State: `PUBLIC_PROFILE_POPOVER_CONVERGED_HUMAN_QA_PENDING`; no Director PASS is inferred. The 034 owner self-edit gate and production username-collision audit remain separate.

`PROFILE-V1-PUBLIC-VIEW-CONVERGENCE036` is the prior implementation baseline.
The 035 capsules are superseded by four text-led professional summary rows,
with governed Grade parents/compact children, governed Subject short labels,
Director-prioritized Roles, plain Teaching Since, width-aware whole-term
packing, and one keyboard-accessible disclosure at a time. The full Teaching
Profile remains the authoritative record. Anonymous right-rail join copy links
only to the existing signup/login routes and does not claim messaging/follow
capabilities. Normal DDEV 1440/1024/768/390 native evidence, focused PHP/JS
regressions, restored user-353 fixture, and source/runtime hashes are recorded
in Workflow V2 cycle `260928183519`. This is
`PUBLIC_PROFILE_FINAL_DESIGN_CONVERGED_HUMAN_QA_PENDING`, not Director PASS.
No production activation or username-collision audit occurred. The next action
is Director review of the final public Profile presentation; preserve 034
owner self-edit's independent pending gate.

`PROFILE-V1-PUBLIC-HERO-CONVERGENCE035` is the superseded capsule baseline on isolated Profile
branch `codex/profile-v1-basics-facts` and is
`PUBLIC_PROFILE_HERO_CONVERGED_HUMAN_QA_PENDING`. The accepted public Profile
composition below the hero is unchanged. The hero now summarizes represented
governed Grade parents, alphabetically sorted Subjects, Director-prioritized
selected roles, and optional Teaching Since; only one of the first three can
expand at a time. Owner-only `?view_as_public=1` renders the exact public
projection with a separate return notice and noindex/nofollow, while ordinary
visitors receive no owner UI. The public breadcrumb begins Teachers > Members.
Normal DDEV evidence covers 1440/1024/768/390, keyboard/ARIA disclosure,
public/private visibility, and complete restoration of local user 353. Cycle
`260928161611` carries the bounded Report/Hopper package. Director HUMAN_QA
is pending; no production activation or collision audit was performed.

`PROFILE-V1-SELF-EDIT-CONVERGENCE034` is implemented on the isolated Profile
branch and available in normal DDEV at `/profile/` and `/profile/edit/` for
Engineering Director HUMAN_QA. The bounded corrections are white labels on
the two primary blue owner-route links, one Teaching-modal scroll region,
onboarding-owned finite nine-role checkboxes, optional typed Teaching Since,
direct editable Location controls, benefit-oriented copy for the existing two
visibility flags, and a five-second saved notice. Native QA covered all four
required widths and public/private projection. The test member was restored
to its prior empty Profile state. This is
`PROFILE_SELF_EDIT_CONVERGED_HUMAN_QA_PENDING` in cycle `260928144227`; do not
record Director PASS before review. The 032 public Profile HUMAN_QA and
production username-collision gates remain separate.

`PROFILE-V1-SELF-EDIT-MODE033` is implemented and awaiting Engineering Director
HUMAN_QA. The isolated Profile branch owns the source, and normal DDEV uses a
file-scoped projection for QA. `/profile/` shows all owner facts, including
private facts, with one Edit Profile action, View as Public, and the avatar camera. `/profile/edit/`
uses that same composition with four modal section editors and View as Public /
Done Editing actions. Canonical data/selection controls and the existing
public projection remain the writers/readers; the avatar editor is reused.
Browser verification covered section saves, aggregate visibility, dirty-close
protection, responsive 1440/1024/768/390 layouts, and source/runtime parity.
The local QA account was returned to its original empty Profile state after
temporary verification. Groups has no governed management destination, so
there is no Manage Groups link. Cycle `260928125357` contains the native
captures and acceptance ledger. Do not declare Director PASS before review.

`PROFILE-V1-PUBLIC-VIEW032` is implemented on isolated branch
`codex/profile-v1-basics-facts` and remains HUMAN_QA_PENDING. Follow-up cycle
`260928032005` made the implementation available in the normal
`teachers-net` DDEV environment through its existing bind-mounted source owner.
The six affected Profile/Identity files are byte-identical between the
canonical Profile worktree and live container. Normal-DDEV Chrome/CDP evidence
proves `/profile/jobman/`, anonymous access, authenticated non-owner access,
owner public projection, `/profile/` self-view, fixed-route precedence,
unknown-user 404, mixed-case canonicalization, loaded assets, and no console
errors or horizontal overflow at 390px. Existing data show only sparse public
profiles; none has `profile_details_public=1`, so a populated public projection
is not available without a separate fixture decision. No account or Profile
facts were changed. The current stack has no authoritative public-membership
provider or Groups discovery destination, so Groups remains the approved empty
state and no membership/link is invented. `/members/<username>/` remains
omitted because the generic WordPress catch-all makes that alias ambiguous.
No production activation occurred; Director visual review and the read-only
production username collision audit remain required before release activation.

The 032B bio audit found a 500-character UI attribute/server limit in normal
text, enforced by the same Basics renderer for guided onboarding and
`/profile/edit/`. PHP uses `mb_strlen` and accepts 500 code points, rejects
501, normalizes CRLF to LF, allows punctuation and plain URLs, and rejects HTML
tags. The JS counter uses `Array.from` code-point length, but HTML `maxlength`
uses UTF-16 code units; an all-astral string therefore reaches 250 code points
in the browser while the server would accept 500. No limit was changed. The
stored value is `_tnet_profile_bio` in `wp_usermeta.meta_value` (`LONGTEXT`;
4,294,967,295-byte column capacity; DDEV `max_allowed_packet` is 256 MiB),
well above the application ceiling of 2,000 UTF-8 bytes. Public output is
escaped plain text with `<br>` newline rendering; URLs are not linkified. A
transient 500-character desktop render measured five lines and a 179px total
About card at 710px width. These findings are recorded in cycle
`260928032005`; do not change the bio limit without Director authority.

`PROFILE-LAUNCH-ROUTER-VISUAL-CONVERGENCE004` is COMPLETE/FROZEN after
Engineering Director HUMAN_QA PASS.
`/account/launch/` remains a normal authenticated Shared Shell surface with a
durable one-time state and its existing destination registry. Its accepted
review state is featured Profile completion, equal Community/Lessons choices,
quiet Home escape, no Jobs/Search/right rail, and a naturally visible footer
on short desktop viewports. Explicit persisted U.S. state values now resolve
through an opt-in direct Shared Shell family link to validated canonical
production state routes; the personalized row is not marked active merely from
member location. The launch surface suppresses standalone Help, releases the
empty right slot, and centers the composition in the usable region after the
left rail. Unvalidated international and unsupported location cases retain
generic States. Profile and Lessons remain release dependencies without
invented member-facing routes. The accepted 656px desktop composition is
centered on the canonical 1280px shell canvas. Cycle `260921162000` applies a
Shared Shell desktop-only correction so top-level family headings use the same
40px row and 2px inter-row cadence as primary navigation, while nested active
children retain their subordinate treatment. Fresh launch and Community DOM
evidence at desktop and launch containment at 390px are accepted carryforward.
Profile and Lessons remain deployment dependencies; Jobs remains modeled but
disabled/hidden. The next substantive Profile objective is
`PROFILE V1 PUBLIC DISPLAY / EDIT`. The contract is
`docs/profile/onboarding-journey-contract.md`.

Active Development — Screens 3 Photo/Avatar, 4 Public Identity, and 5
voluntary Location Context retain their accepted source and persistence seams.
`PROFILE-ONBOARDING-JOURNEY-VISUAL-FINAL001` is HUMAN_QA_PENDING: it retains
the combined `/account/identity/` owner and Screen 6 final checkpoint while
making the final visual corrections only. Create Account is disabled until the
existing browser-visible email/username/password rules pass; signup authority
remains server-side. Screens 1 and 2 use full-column CTAs, while the combined
identity/location form uses a compact desktop control column and fluid compact
layout. Native 1425px and 390px evidence is ready for Director review.
The Profile resolver remains the canonical representation owner; the 268-entry
frozen portrait bank is read-only chooser content, not member-demographic data.

## 2. Current Ticket

`PROFILE-V1-PUBLIC-COLUMN-PARITY042` is the current Director review boundary. See the current handoff above and Workflow V2 cycle `260929200959` for the rendered geometry and scoped change.

`PROFILE-V1-PUBLIC-CARD038B` is the current handoff boundary for the public Profile card's four summary lanes and dated credential line. Workflow V2 cycle `260929091147` contains its source diff, direct-DDEV screenshots, state/projection checks, responsive popover evidence, containment/console results, and source/runtime hashes. No Profile data or fixture state was mutated. Await Engineering Director HUMAN_QA.

`PROFILE-V1-PUBLIC-CARD037` is the current handoff boundary for public top-card presentation. Cycle `260928214317` owns its scoped source, native captures, restored QA fixture, and acceptance ledger. Director HUMAN_QA remains pending.

`PROFILE-V1-PUBLIC-POPOVER-CONVERGENCE037` is the prior popover baseline, refining only 036 hero disclosure presentation. Cycle `260928192329` owns the scoped source, native captures, restored QA fixture, and acceptance ledger.

`PROFILE-V1-PUBLIC-VIEW-CONVERGENCE036` is the prior implementation boundary.
Director review uses the normal DDEV public route
`https://teachers-net.ddev.site/profile/<username>/`, including owner-only
`?view_as_public=1` when authenticated as that member. Cycle `260928183519`
owns the scoped 036 diff, native captures, source/runtime hashes, restored
fixture and acceptance ledger. The separate 034 owner self-edit gate remains
HUMAN_QA_PENDING at `/profile/` and `/profile/edit/`. Engineering acceptance
does not imply Director visual/interaction PASS.

`PROFILE-V1-SELF-EDIT-MODE033` is the prior implementation baseline:
`PROFILE_SELF_EDIT_MODE_IMPLEMENTED_HUMAN_QA_PENDING`. Director can review
`https://teachers-net.ddev.site/profile/`,
`https://teachers-net.ddev.site/profile/edit/`, and the unchanged public
`https://teachers-net.ddev.site/profile/<username>/` projection. Production
activation still requires the previously identified username-collision audit.
No account settings, Group membership mutation, granular privacy, or Shared
Shell redesign was added.

`PROFILE-V1-PUBLIC-VIEW032B-DDEV-QA` is the prior public-route QA baseline. The public
route is available for interactive Director review in normal DDEV at
`https://teachers-net.ddev.site/profile/jobman/`; useful existing sparse
profiles include `/profile/test/` and `/profile/tommytoons/`. No normal-DDEV
account currently exposes populated teaching details publicly. This cycle is
`PUBLIC_PROFILE_MAIN_DDEV_QA_READY_BIO_CONTRACT_AUDITED`, not Director PASS.
The canonical Profile source remains the isolated branch; the Community-root
DDEV tree received only the narrow runtime projection needed for QA, with
pre-projection copies preserved outside the repository. No commit was made to
the shared Community checkout, and no production activation or member-data
mutation occurred. See the cycle report for exact hashes, route evidence, and
the Unicode bio-limit discrepancy.

The source implementation from 032 added `class-tnet-profile-public.php`
route/projection/rendering, `public/css/tnet-profile-public.css`, Shared Shell
configuration/enqueue helpers, reuse of the existing Complete ad-slot owner,
and focused route/projection regressions. It deliberately does not implement
Groups membership or the secondary `/members/` alias without authoritative
ownership.

`PROFILE-V1-USERNAME-NAMESPACE031` is implemented on the canonical isolated
Profile branch `codex/profile-v1-basics-facts` at
`/home/bobreap/projects/teachers-net-profile-recovery029`. It adds the narrow
`profile_routes` reserved-username category and an Identity-owned explicit,
administrator-gated official provisioning method; ordinary account creation
cannot select that override. Focused deterministic regression coverage passed
without database accounts or email. `admin` remains unchanged and no public
username route is activated. The next public-route implementation may proceed
in source; production activation remains gated on a read-only production
username collision audit. Cycle `260927183656` contains final evidence and
Git provenance. Community work/history is unchanged.

The next Permanent Profile contract is decided but not implemented in this
recovery: authenticated self entry at `/profile/`, public/other-member route at
`/profile/<username>/` keyed by permanent `user_login`, anonymous viewing,
owner-only editing, existing `location_public` and aggregate
`profile_details` visibility, no email, and no authored/Community/lesson/Jobs
relationships in Profile V1.

`PROFILE-V1-ROUTES-LIVE-PREVIEW026` is implemented on isolated Profile branch
`codex/profile-v1-basics-facts` and remains HUMAN_QA_PENDING. Full implementation
baseline: cycle `260925124915`; latest bounded refinement/evidence cycle:
`260925135240` (`PROFILE-V1-ROUTES-LIVE-PREVIEW026-REFINEMENT`). Canonical
routes are `/profile/`, `/profile/edit/`,
`/profile/edit/avatar/`, `/profile/enrichment/basics/`,
`/profile/enrichment/roles/`, and `/profile/enrichment/complete/`. The public
route is a temporary fact-only view for route ownership, not the final public
Profile design; the permanent editor and avatar-editor design remain outside
scope. Camera controls open the existing avatar editor as a modal, and the
standalone avatar route shares that same upload/remove owner.

The three enrichment states use one shared Profile card/status contract.
Basics and Roles reflect pending client-side values in the live card and
status without persistence; both clear immediately when the last value is
removed. A subject-last-chip regression was corrected at the existing picker
owner. Complete remains fact-only, with the accepted three destination cards.
Basics uses two Grade columns, collapses disclosure branches on load/reload,
and places Skip left / Save right. Roles places Done left / Add right; both
actions retain their accepted save semantics. Native desktop captures, 390px
containment, live change/clear checks, route/redirect checks, PHP/JS syntax, and
scoped diff validation are recorded in cycle `260925124915`; refreshed
refinement evidence is in `260925135240`. User 353 was restored and read back
as having no Profile facts, bio, or location. No commit/push occurred. The
Profile/Identity executables and Shared Shell PHP match source to DDEV. The
active DDEV Shared Shell Community stylesheet has 56 additional
Community-owned intermediate/mobile layout lines beyond the isolated Profile
worktree copy; this pre-existing Community difference was not altered, and no
full-file parity claim is made for it. Profile routes were verified on the
active DDEV composite.

Cycle `260924212200` for `PROFILE-V1-GUIDED-JOURNEY-CONVERGENCE025` remains
accepted carryforward for save ordering, the actual-fact completed state,
three next destinations, and the interim personalized rail. The rail remains
presentation from Profile facts, not membership or route authority; no
Community URL was fabricated in 026.

Director-authorized Chatboards children are interim presentation from actual
Profile Grade/Subject/location facts. Grade/Subject children lacking canonical
Community destinations are deliberate non-links, with no invented slug, `#`,
or JavaScript URL. A validated state child may retain its established route.
These missing Grade/Subject URLs do not block Profile V1 deployment; only
activation of the individual labels as links remains blocked on Community
route-owner authority. Projected labels do not create membership facts.

`PROFILE-V1-PAYOFF-ENRICHMENT023` was the Page-2 baseline after source reconciliation in
cycle `260924151739`. The isolated tracked Profile worktree is
`/tmp/profile-v1-basics-facts`, branch `codex/profile-v1-basics-facts`, starting
HEAD `8527f570df0cbf2bea01ce39a0cd74a6d438771c`. Its executable
`tnet-profile` source matches active DDEV byte for byte; READINESS022 role
writer, governed list resolver, and picker preservation were ported from
accepted DDEV state. The Shared Shell fixed rail rule also matches DDEV; all
Identity executable files matched before change. The 268 frozen portraits and
manifest match. Direct native Page 1 still renders with the accepted 024
placeholder/CTA treatment, and DDEV resolves Profile Educator Roles V1 as
View 65/version 90 with nine members. No Community-owned source or Git history
was changed. Page 2 now exists at authenticated `/profile/enrichment/`; guided
Page-1 save routes there. Cycle `260924151739` verified actual-fact payoff and
progress, nine governed roles, Screen-6-only suggestions (present/absent,
no implicit selection), explicit multi-role/year save/read, no-write Skip,
390/390 containment, and fixed desktop rail. The reusable test account was
restored to its original empty Profile state. Isolated source and DDEV
executable files remain byte-identical; the frozen bank is unchanged. Native
desktop and upper mobile captures are available. Full-page mobile capture
timed out at the CDP screenshot command, while direct-CDP DOM, console, and
responsive checks succeeded. Director HUMAN_QA remains PENDING; no commit/push
occurred. Do not rediscover this source boundary absent a concrete dependency
or hash change.

`PROFILE-ONBOARDING-MEMBER-CONTEXT-SCREEN6-001` adds `/account/context/` after
the combined public-identity/location checkpoint. It records optional independent self-reported roles
and hiring intent through Profile's normalized indexed relation; a member may
be a teacher, administrator, and hiring simultaneously. Identity owns only the
onboarding marker/route. Continue synchronizes explicit selections, Skip stores
no classifications, and neither path alters canonical Screen 5 location.
Native DDEV evidence covers desktop, 390px, multi-select persistence,
deselection, Skip, route completion, and indexed reverse lookup. See
`docs/profile/member-context-screen6-contract.md`.

The earlier `PROFILE-V1-CORE-TERMS-READINESS001` state is superseded by
accepted READINESS017 and the additive local Educator Roles READINESS022
foundation. Current role choice authority is documented in
`docs/core-terms/profile-educator-roles-v1.md`.

`PROFILE-ONBOARDING-JOURNEY-CONVERGENCE001` is superseded by the active
consolidation ticket. Focused Identity Shared Shell owns the completed chrome correction: it
suppresses WordPress's logged-in toolbar before render, omits the otherwise
purposeless focused-rail divider, and reduces focused rail height by the
layout's 70px vertical padding so a viewport-fitting screen no longer gains a
phantom scrollbar. Normal Community preserves its y=14 rail / y=80 first-link
geometry. The Screen 6 visual cleanup restores the Teacher mortarboard,
retains the distinct Tutor person-and-board icon, and removes the decorative
people mark and informational panel. The resolver's uncommitted-user fallback
is now WordPress's ordinary ``mystery`` head-and-shoulders image; committed
Profile photos and portraits are unchanged. No Screen 6 role/intent,
persistence, lifecycle, routing, or Skip behavior changed.

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

The consolidated ordinary-member journey is the current HUMAN_QA gate. Later use of its facts
for an intent-aware educator/employer/general onboarding fork remains a
separate product decision; do not define or implement it without a new ticket.
Preserve the frozen bank and do not alter the Profile resolver without new
authority.

Director has additionally identified future design-review inputs only: explicit
member interests, optional later public-profile enrichment, a possible
recruiter/job-posting branch, and a full-journey fatigue/redundancy review.
Those inputs do not authorize another numbered screen, schema, or route.

## Profile V1 member facts — 2026-09-20

`PROFILE-V1-MEMBER-FACT-CONTRACT001` is complete at the data-contract layer.
Profile retains the canonical fact write/query seam and Core Terms resolves
live term UUIDs. It supports independent professional identity, teaching grade,
teaching subject, and explicit interest facts without a second taxonomy or a
write to `wp_cfm_user_terms`. Legacy Screen 6 role/intent values and `hiring`
remain compatible and are not destructively migrated. Profile-v1 UI, verified
credentials, Core Terms alias intake, Community membership events, activation
prompting, and public rendering remain separate future authority. See
`docs/profile/member-fact-contract-v1.md`.

## 4. Next Five Planned Tickets

1. Obtain Director HUMAN_QA for Screen 6 Member Context before any later
   intent-aware onboarding fork work.
2. Preserve the frozen 268-entry bank and 42-row removal archive; do not
   acquire artwork, restore removed portraits, or change the resolver without
   a new Director decision.

## 5. Current Blockers

- Screens 3, 4, and 5 are COMPLETE/FROZEN.
- Screen 6 Member Context is HUMAN_QA_PENDING; no product blocker is known.

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
classification, or production state changed during closeout. Source-control
recovery now establishes this repository as the canonical Identity owner; see
`docs/profile/tnet-identity-source-ownership.md`. Screen 4 is now separately
authorized and HUMAN_QA_PENDING; do not begin Screen 5, member-avatar
activation, or further bank work without new authority.

## READINESS017 semantic/composition continuation — 2026-09-24

The Director resolved the Jobs Grade and JobLister boundaries. Local DDEV now
has Preschool, Beginning Learners (preserved prior UUID), Early Literacy,
Profile-owned governed Grade/Subject Lists, corrected Jobs V1 Grade binding and
field-option lifecycle, and unchanged unbound JobLister View/draft. The guided
Profile page was natively checked at desktop and 390px, and a disposable member
proved exact Grade/Subject UUID persistence without parent inference; that
fixture was removed. The Director has now granted READINESS017 HUMAN_QA PASS.
No production-data absence is
inferred from DDEV. Detailed inventory, ownership and target-environment gate:
`docs/core-terms/profile-readiness017-composition.md` and the current Profile
Report/Hopper. Preserve RAIL013 and Community route separation.

## Grade tree convergence and Shared Shell rail — 019 (2026-09-24)

READINESS017 is accepted and frozen locally. The current Profile objective
owns a compact Grade tree with independent parent selection and disclosure,
child-only Grade facts, and the unchanged reusable read-only Grade label
projection. The 018 boxed one-open accordion is superseded. Direct Chrome/CDP
checks prove normal, muted-derived and full parent states, multi-open branches,
direct Adult/Higher leaves, a disposable exact-child save/read, and 390px
containment. Jobs source confirms its muted checked-parent grammar; this QA
account cannot enter the Jobs wizard because it has no employer access, so no
native Jobs-side screenshot is claimed. The separate Shared Shell fixed-rail
owner is unchanged; native top/middle/footer/bottom measurements show an
invariant 14px rail top and stable main x=230. Grade-tree presentation and rail
behavior are Director HUMAN_QA PASS / PROVEN by LOCK020. No Core Terms/List, public Profile,
personalized-route, or production-target migration work is authorized here.

## Guided Grade tree visual lock — 020 (2026-09-24)

The final top-level alignment refinement is implemented in Profile markup/CSS
only. Desktop Chrome/CDP measured all four grouped-parent checkboxes and both
direct-leaf checkboxes at x=332px, with all six labels at x=370px; grouped
parents and direct leaves use font weight 600. Child labels remain at weight
300. The leaf disclosure gutter is empty and no fake caret is rendered. At
390px the same columns align, document client and scroll widths are both
390px, and there is no horizontal overflow. Partial parents retain the muted
checked treatment (#8a929d); the JS owner and parent interaction were not
changed. Engineering Director HUMAN_QA PASS is recorded; the Grade-tree visual
composition and behavior are PROVEN.

## Profile Educator Roles V1 — READINESS022 (2026-09-24)

Local DDEV implementation is engineering-verified. Core Terms adds School
Counselor (`8feaa1bd-3aa6-4974-9875-8bb66cb60e5d`) under Professional
Communities & Roles without altering Counselors or Subject Area Counseling.
Profile's published, ordered `Profile Educator Roles V1` List is View 65/version
90 and contains exactly the nine Director-approved role UUIDs. The dedicated
`professional_identity` binding validates the Profile List identity and live
members. Profile remains the fact writer; composed choices are self-reported
and `profile_details`-scoped, and out-of-composition historical assertions are
preserved. Legacy Screen 6 suggestions are deterministic, explicit,
non-selected, and limited to approved direct mappings. No production migration
or census, enrichment UI, Jobs, or Community work was performed. Detailed
identifiers and acceptance evidence are in
`docs/core-terms/profile-educator-roles-v1.md` and this cycle's Profile
Report/Hopper. No commit or push was made.

## Current responsive launch boundary — 027 (2026-09-25)

The active Profile objective is
`PROFILE-V1-ENRICHMENT-LAUNCH027-RESPONSIVE-CONVERGENCE`, cycle
`260925235128`, HUMAN_QA_PENDING. The responsive launch implementation is
complete and natively checked through the approved direct Windows Chrome/CDP
route. Preserve the accepted desktop three-card/right-ad composition and the
breakpoint behavior recorded in the Project Cursor. The shared compact mobile
menu uses the same Profile community projection as the desktop rail, with no
fabricated child URLs.

The prior builder interruption is historical cycle `260925221311`; it did not
authorize reopening the objective. No commit or push has been made and no
unrelated dirty work may be staged. The current Report/Hopper package contains
the final native screenshots, edge measurements, source/runtime hashes,
acceptance ledger and Workflow V2 ticket identity.

## Compact Shared Shell header continuation — 027 (2026-09-26)

Cycle `260926004027` completed the bounded compact-header owner correction and
remains HUMAN_QA_PENDING. The inherited 154px compact account-area basis was
reset to natural width, eliminating the artificial avatar-to-hamburger gap.
Profile menu children now share the approved 15px/600 label treatment with
top-level menu labels; child hierarchy remains indentation and no child icon.
Direct Windows Chrome/CDP measured no overflow and no control overlap through
the 360px layout width. The 350px probe is the first collision with the
unchanged full logo plus four visible controls. No existing Shared Shell
account-in-hamburger pattern was available, so ultra-narrow redesign is a
deferred owner boundary rather than an invented fallback. The temporary
disposable QA grade/subject facts were cleaned via the canonical service.
No commit or push was made, and unrelated Community/Profile dirty work must
remain preserved.

## Notification badge transfer continuation — 027 (2026-09-26)

Cycle `260926115156` implements the Director-authorized ultra-narrow
notification proxy transfer and remains HUMAN_QA_PENDING. The existing
Shared Shell notification owner still computes the unread count and owns its
badge/read-state semantics. At `max-width: 374px`, the single existing badge
node moves to the hamburger while the menu is closed, moves to the
Notifications bell row while the menu is open, and returns unchanged when the
menu closes. The badge overlays the control and does not consume layout width;
zero count hides it on both controls. Menu toggling leaves the fixture's
unread/read states unchanged. Native direct Chrome/CDP checks used the
existing 3-unread synthetic fixture at 360px and the authenticated Profile
runtime at 1280px and 390px; all measured client/scroll widths matched. No
commit or push occurred. The only remaining gate is Director HUMAN_QA for the
Shared Shell transfer presentation.

## Compact Notifications row alignment continuation — 027 (2026-09-26)

Cycle `260926124139` corrected the ultra-narrow Notifications row to the
established compact navigation grid: 20px icon column, 12px gap, 9px 10px
padding, 44px minimum height, and matching label column. Native direct
Chrome/CDP evidence at the Director's 339px state measures the bell at x=44
and the Notifications label at x=76, matching Home/Lesson Plans/Jobs. The
current zero-count fixture correctly renders no badge; prior positive-count
transfer/read-state evidence remains PROVEN and was not rerun. Report/Hopper
validation passed for cycle `260926124139`; no commit or push occurred.

## Celebration mark continuation — 027 (2026-09-26)

Cycle `260926133957` added the exact Director-supplied party-streamer asset to
the Profile enrichment asset owner. It renders immediately beside `You’re all
set!`, with empty alt and `aria-hidden=true`; desktop is 40x26.7px, compact
390px is 32x21.3px, and the narrowest visible state is 361px. At 354px it is
hidden to avoid isolated wrapping. DDEV source/runtime hashes match and no
console errors were found. Desktop and 361px screenshots are packaged; the
390px screenshot transport timed out twice, with DOM/geometry evidence retained.
No commit or push occurred; HUMAN_QA remains pending.

## Celebration asset lock continuation — 027 (2026-09-26)

Cycle `260926142722` replaced the prior active celebration file with the
Director's newly supplied 945x907 RGBA asset at the same governed Profile path.
SHA-256: `73385bdb819c7a256e28b9614ca816153ef1d7200ed281bd13685519b17e687e`.
The existing renderer/CSS owner now presents it at 40px desktop height and
32px compact height with an 8px heading gap; source/runtime parity and PHP
lint passed. Native direct Chrome/CDP geometry passes at 1280, 390, 361 and
354px; screenshots are captured at desktop and 361px, while the 390px capture
timed out. The mark hides at 354px rather than wrapping. No commit or push;
HUMAN_QA remains pending.

## Streamer 3 swap continuation — 027 (2026-09-26)

Cycle `260926152058` replaced the active celebration binary with the supplied
transparent `streamer3.png` at the existing governed path. SHA-256 is
`57379c00f7659a72cfb53cab35497accb86366a1b2cacdd1eb125abcf8ca87c1`.
Direct Chrome/CDP evidence measures 40.64x40px desktop and 32.5x32px at 390px,
with the accepted 8px heading gap and no overflow. The 354px hide behavior is
unchanged. Desktop screenshot capture succeeded; the 390px screenshot timed
out while DOM/geometry evidence passed. PHP/CSS owners and all unrelated
behavior remain unchanged; no commit or push occurred. HUMAN_QA remains pending.

## Enrichment Launch 027 closeout — 2026-09-26

Engineering Director HUMAN_QA PASS is recorded for the final
`/profile/enrichment/complete/` surface, including desktop/responsive layout,
destination cards/assets, ad slot, compact navigation, notification transfer,
menu alignment, and streamer-3 treatment. Basics, Roles, and the shared
Profile card remain PROVEN. `PROFILE-V1-ENRICHMENT-LAUNCH027` is
`COMPLETE / DIRECTOR HUMAN_QA PASS`; final evidence and ledger are preserved in
cycles `260926152058` and `260926160823`. No product changes, commit, or push
occurred during closeout. Next objective: **Permanent Profile View/Edit
readiness**.

## Permanent Profile surfaces readiness — 028 (2026-09-26)

Diagnostic cycle `260926161138` confirms the current route/owner baseline
without implementation changes: `/profile/` is an authenticated self-only
temporary fact view; `/profile/edit/` is the current-member editor; and
`/profile/edit/avatar/` plus the modal share the canonical Profile avatar
owner. Permanent View/Edit implementation remains NOT READY until Director
authority resolves the other-member route/identity key, viewer classes, final
public visibility matrix, and permanent presentation contract. See
`tmp/hopper/profile/PROFILE-V1-PERMANENT-SURFACES028-readiness-report-260926161138.md`.

028 source correction: `/tmp/profile-v1-basics-facts` is missing while its
branch ref survives at `8527f570df0cbf2bea01ce39a0cd74a6d438771c`. That tree
does not contain the current Public/Enrichment route owners or related assets
present in DDEV. The prior complete-source/parity assertion is contradicted;
source/runtime parity and the complete publishable Profile baseline are
UNPROVEN. Resolve this before implementation. The current Profile runtime
files are ignored in the active Community checkout and were not copied or
staged by readiness work.

The complete corrected diagnostic package is corrective continuation cycle
`260926164236` at
`tmp/hopper/profile/PROFILE-V1-PERMANENT-SURFACES028-readiness-report-260926164236.md`;
it supersedes the incomplete first-cycle report.

## Profile V1 source recovery — 029 (2026-09-26)

Cycle `260926095358` resolves the source blocker from 028 without changing
product behavior. The recovered isolated source owns the complete current
Profile plugin: 268 frozen portraits, one frozen manifest, ten PHP/JS/CSS
owners, and four enrichment launch assets. Accepted 023-027 evidence supplies
the provenance for each runtime-recovered file, including the final streamer-3
asset. Full source/runtime inventory and SHA-256 comparison are exact except
for the explicitly excluded diagnostic fixture.

Authenticated direct Chrome/CDP regression confirms Basics, Roles, Complete,
temporary self Profile, permanent-editor baseline, and standalone avatar owner
still render with no horizontal overflow or console errors. No facts were
written. Shared Shell and Identity dependencies were exercised unchanged. The
terminal Workflow V2 package records the immutable Git identity, provenance
matrix, parity matrix, final diff, and acceptance ledger. Permanent Profile
View/Edit implementation is the next separate product objective.
