# Community 3.0 V1 Release Convergence Ledger

Status: planning and reconciliation authority, dated 2026-09-10. This ledger
supersedes earlier scheduling shorthand where it conflicts with current
implementation evidence. It authorizes no schema, runtime, route, legacy-data,
Sandy, or production change.

## Release conclusion

Community 3.0 is a credible local, text-first discussion and migration pilot.
It is **not a production-ready replacement for legacy Chatboards**. Canonical
Community membership, consent-safe preference migration, production media
operations, user safety/moderation, URL/SEO evidence, operational cutover and
rollback, and full native acceptance remain release gates.

The registered source is `/home/bobreap/projects/teachers-net-community3`,
branch `COMMUNITY3-ui-working`, now at pushed commit
`c6feb48a7501eef0c8e643d5d8339925febd0734`. The intended integrated local host
is `teachers-net-live`. Its C3 authority header still reports `status=mismatch`:
the read-only mount points at the governed source path, but the source worktree
contains one uncommitted plugin change and one ignored runtime-required plugin
file absent from the pushed commit. Browser observation there is provisional
until this source-integrity decision is resolved.

## V1 capability ledger

| Capability | Disposition | Evidence / release condition |
|---|---|---|
| Opaque Community identity, slug, legacy path/group mapping | PROVEN locally | Registry persists opaque `community_id`, slug, lifecycle, visibility and explicit `path_id=241` / `group_id=227` mapping. Numeric equality is prohibited. |
| Core Terms and Rail Parent presentation | PROVEN locally | C3 consumes the Views/Core Terms public published-List contract. It is presentation, not membership. |
| Community profile: description/about, artwork, owner/moderator identity, editable visibility | MISSING | AI description and About copy are literals; registry has no description/artwork/owner fields or product editor. |
| Community membership and join/leave | MISSING / RELEASE_GATE | `tnet_memberships` is a compatibility read only. C3 lacks membership persistence, mutation, migration linkage and inactive/leave state. |
| Topic/reply writer, thread identity, direct/deep reply lineage | IMPLEMENTED_NOT_ACCEPTED | Local publisher/repository and routes exist; release needs reconciled runtime provenance and authenticated native journeys. |
| Historical AI pilot: 3 public, 5 status-9 excluded | PROVEN local pilot only | 8/8 local reconciliation, idempotency and rollback evidence. Not corpus migration, public routing or production cutover. |
| Landing/feed/card/Quick View/composer | IMPLEMENTED_NOT_ACCEPTED | Local surfaces exist; pilot literals must not be claimed as product state and the current host mismatch blocks release-quality acceptance. |
| Feed definitions | PARTIAL | Deterministic latest exists. Popular/Unanswered are presentation literals, not queries. |
| Canonical page and modal navigation | IMPLEMENTED_NOT_ACCEPTED | Local page-versus-modal contract exists; public URL/SEO and release acceptance remain gates. |
| Community/thread follow and interest | MISSING | No C3 relationship persistence, controls, eligibility, or migration. |
| Bell, reply, reaction, mention, group notifications | PARTIAL | Shared provider is real; C3 lacks complete producers, recipient/visibility resolution, thread interest, aggregation and mention support. |
| Notification preferences and delivery | RELEASE_GATE | Legacy flags are evidence only. No automatic migration may create email or push consent. |
| Save/hide/mute/block | MISSING / DEFERRED | Per-reader and safety relationships are absent; scope and moderation interaction require a product decision. |
| Report/moderation queue, scoped roles, sanctions | PARTIAL / RELEASE_GATE | Publisher lifecycle/audit exists. Report intake, queue, roles, user sanctions, reporter privacy, abuse controls and appeals are absent. |
| Direct uploads/attachments/accessibility | PARTIAL / RELEASE_GATE | Local prototype exists; durable storage, scans, quotas, derivatives, retention and operations are not production-shaped. |
| OG/link preview acquisition | PARTIAL / RELEASE_GATE | Local mock/deterministic seams exist. Safe fetch/cache/SSRF, moderation and failure policy need production proof. Raw-link fallback is essential. |
| Search/indexing | MISSING | Current form is generic site search; no visibility-aware C3 index/document/event owner exists. |
| Responsive, keyboard, screen-reader, error-state QA | IMPLEMENTED_NOT_ACCEPTED | Local visual work exists; must rerun from a byte-identified integrated runtime using required guest/authenticated journeys. |
| Ads, analytics, monetization | DEFERRED | No release claim; separate consent and product authority required. |
| Root `/community` discovery product | MISSING / DIRECTOR_DECISION | AI landing is a local pilot route; root directory/default discovery/empty states are unsettled. |
| Full corpus migration, duplicate disposition, historical media | PARTIAL / RELEASE_GATE | Foundation contracts, duplicate characterization and AI pilot exist. Corpus census, media reconciliation and restart evidence are required. |
| Legacy aliases, redirects, canonical SEO | RELEASE_GATE | One-hop aliases are contracted but public activation needs empirical production URL sampling. |
| Authentication, authorization, privacy, performance | PARTIAL / RELEASE_GATE | WordPress identity/local capability checks exist; release needs a role matrix, visibility enforcement, abuse/rate limits, security and load review. |
| Deployment, rollback, monitoring, legacy-writer retirement | MISSING / RELEASE_GATE | No production action is authorized. Cutover needs staged scope, observability, reconciliation, rollback window and immutable archive. |

## Delta from the 2026-09-08 readiness audit

The Sep. 8 audit correctly identified C3 as a narrow text-first foundation.
Since then, useful bounded local evidence has accumulated: Core Terms/Views
rail consumption, main-host Shared Shell composition, historical AI import and
reconciliation, native C3 writer/reply/deep-target seams, local composer/media
staging and C3 shell work. None is production evidence.

The Sep. 10 Hero audit makes two release blockers explicit:

1. Community identity has no persisted description, artwork, membership,
   owner/moderator, follow/watch or editable profile state; visible description
   and some navigation/filter text are literals.
2. `teachers-net-live` reports a C3 plugin-tree authority mismatch. Rendering a
   page does not make it valid release-quality native evidence.

No later evidence changes the Sep. 8 findings on moderation queue/sanctions,
relationship state, delivery policy, production media, global search, public
URLs or cutover operations.

## Membership migration: mandatory and separate

Legacy group membership is not board identity and is not notification consent.
For each in-scope membership, migration must retain immutable source identity
and create/reconcile one canonical Community-membership record only when both
the legacy group-to-Community mapping and user identity mapping are explicit.

Required evidence: source namespace and membership ID; legacy `group_id`;
mapped opaque `community_id`; resolved user ID or explicit unresolved-identity
disposition; active/inactive/left/unknown state; source timestamps where
available; snapshot/checksum; rule version; migration batch/run identity;
target identity; and reconciliation status. The importer must be idempotent,
restartable, auditable and batch-rollbackable. Preserve historical inactive and
leave evidence instead of treating absence as active membership.

Membership must never silently create a Community follow, thread follow,
inferred interest, notification preference or off-site consent. Those are
separate relationships and default to no new interest or delivery.

## Notification-preference migration decision matrix

| Legacy evidence | Current truth | C3 disposition | Release condition |
|---|---|---|---|
| `tnet_memberships.email_posts` | Observed group preference field | Preserve immutable evidence; **no email enrollment** | Semantics, consent basis, scope and confirmation are approved. |
| `tnet_memberships.email_responses` | Observed reply-related field | Preserve evidence; do not infer thread follow or email consent | Direct-reply/thread policy, suppression and confirmation are defined/tested. |
| Legacy group frequency values | Value domain not fully reconciled | Census/map only | Explicit mapping to a C3 channel/frequency or deterministic `NO_EMAIL`. |
| WordPress/BuddyPress settings | Possible adjacent preference sources | Read-only inventory/provenance only | Owner/scope are verified; no cross-product overwrite. |
| Dormant custom mail code/records | Possible delivery behavior, not consent | Preserve audit evidence | Operator review marks it authoritative or retired. |
| Unsubscribe/bounce/complaint/suppression | Required safety evidence; corpus not fully inventoried | Preserve and apply as hard suppression if delivery becomes eligible | Auditable precedence, global pause, kill switch and recovery proof. |
| Past delivery history | Evidence of historical delivery, not permission | Audit-only | Never used as re-consent or automatic enrollment. |

The safe default for unresolved rows is `NO_EMAIL`. A later confirmation path
may offer a deliberate C3 choice, but must not be represented as continuation
of consent without Director-approved policy and evidence.

## Media production readiness matrix

| Dimension | Current state | V1 release gate |
|---|---|---|
| Identity/persistence/provenance | Local attachment and historical static-artifact references | Immutable object/version/provenance identity and ledger reconciliation. |
| Object storage/CDN | No approved production owner | Director/operator selection and rollback-compatible access model. |
| Upload authorization/MIME/size | Local bounded prototype | Authz, allowlist, quotas, rate limits and failure handling. |
| Malware scanning/abuse | Not proven | Scan, quarantine, review and observability policy. |
| EXIF/privacy | Not proven | Metadata stripping/preservation policy and tests. |
| Derivatives/compression | Not proven | Responsive variants, bounded processing, retry and fallback. |
| Accessibility | Local alt fields | Required alt behavior, display fallback and audit path. |
| Rights/moderation | Contract-shaped fields only | Rights, report/review/removal and lawful retention behavior. |
| Retention/deletion/backup | Not proven | Object lifecycle, tombstone/delete, backup and restore rehearsal. |
| Historical media | AI provenance references | Corpus census, availability sampling, deterministic fallback and alias policy. |
| OG previews | Local mock/legacy metadata read seam | Safe fetch/cache/SSRF, failure fallback, moderation and no feed-time remote fetch. |

No storage provider is selected by this ledger.

## URL/SEO release gate

Before cutover, take a read-only empirical production sample of every active
board route family, representative topic URLs, deep-reply targets,
historical/static aliases, malformed/duplicate records and externally linked
legacy patterns. Each source URL needs exactly one disposition:
`ONE_HOP_REDIRECT_TO_CANONICAL`, `ARCHIVE_READ`, `GONE`, or
`DIRECTOR_DECISION_REQUIRED`. Capture source/target status, canonical/robots,
hop count, target identity and exception reason. This audit activates no public
alias or redirect.

Public C3 routes are indexable only after target existence, visibility,
canonical tag, legacy alias, sitemap/search, access control and rollback
behavior are rehearsed. Current local/noindex AI routes are not SEO evidence.

## Dependency sequence to safe replacement

1. Restore/verify byte-identified integrated runtime and establish a release
   test matrix.
2. Build canonical Community profile, membership, role and moderation
   foundations, with migration-safe provenance and report/queue/sanction work.
3. Add Community/thread relationships and notification candidates with
   consent-safe preference/suppression migration; bell first, no implied email.
4. Establish production media/link-preview operations and safety/accessibility.
5. Rehearse corpus migration, memberships, media, aliases and reconciliation in
   bounded batches with deterministic duplicate disposition and rollback.
6. Complete public URL/search/performance/security/accessibility and native
   user/moderator/operator acceptance; then seek staged production cutover,
   observe/reconcile, and retire legacy writer after the approved rollback window.

## Exact next coherent objectives

1. **COMMUNITY3-V1-RUNTIME-AND-RELEASE-BASELINE001** — reconcile governed
   `teachers-net-live` byte identity and establish native release acceptance.
2. **COMMUNITY3-V1-MEMBERSHIP-AND-MODERATION-FOUNDATION001** — canonical
   membership/profile/role/migration foundation plus credible report/queue/
   sanction capabilities; preserve legacy state without importing consent.
3. **COMMUNITY3-V1-RELATIONSHIP-NOTIFICATION-PREFERENCE001** — Community and
   thread relationships, bounded C3 bell candidates, and the legacy
   preference/suppression census and no-email/confirmation matrix.
4. **COMMUNITY3-V1-MEDIA-OPERATIONS-FOUNDATION001** — approved production media
   and link-preview operations, safety, accessibility and historical media.
5. **COMMUNITY3-V1-FULL-MIGRATION-AND-URL-REHEARSAL001** — all-board dry run,
   reconciliation and empirical legacy URL disposition sampling; no cutover.
6. **COMMUNITY3-V1-RELEASE-CANDIDATE-AND-CUTOVER-GATE001** — native,
   operational, security/performance/accessibility and rollback proof before a
   separate production decision.

## Director decisions required

- V1 membership/join/leave scope and legacy state mapping.
- Community profile/admin contract: editable description, artwork, visibility,
  moderator/owner identity and root `/community` discovery.
- Bell-only versus email/digest launch promise; legacy preference semantics,
  consent, confirmation and re-engagement policy.
- Moderation role matrix, report reasons, sanctions, appeals and reporter privacy.
- Production media/storage/scan/CDN and historical asset retention choice.
- Public legacy URL exception policy, staged cutover order, rollback window and
  legacy-writer retirement criterion.

## Evidence sources

- `COMMUNITY3-V1-CAPABILITY-MODERATION-READINESS001`, cycle `260908134731`.
- `COMMUNITY3-HERO-INFORMATION-AUDIT001`, cycle `260910131500`.
- Convergence addendum, V1 authority consolidation, identity, migration, URL,
  notification and media-provenance contracts.
- Current C3 source at `be6f258` and read-only integrated runtime evidence in
  the Hero audit.
