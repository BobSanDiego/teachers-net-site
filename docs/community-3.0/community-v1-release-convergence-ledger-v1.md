# Community 3.0 V1 Release Convergence Ledger

Status: planning and reconciliation authority, dated 2026-09-10. This ledger
supersedes earlier scheduling shorthand where it conflicts with current
implementation evidence. It authorizes no schema, runtime, route, legacy-data,
Sandy, or production change.

## 2026-09-12 native media runtime proof amendment

`C3-V1-MEDIA-RUNTIME-PROOF001` / cycle `260912013811` is
`COMPLETE / PROVEN_NATIVE` for the existing AWS runtime seam. Native evidence
proves S3 quarantine notification, SQS delivery, enabled Lambda mapping,
arm64 container execution, four-output JPEG/WebP release, post-write
verification, metadata stripping, source deletion ordering, bounded logs,
queue idle state, invalid-input retention, three receives and DLQ transition.
The exact anchors and evidence are recorded in
`community-v1-media-runtime-proof-v1.md`.

This does not close the broader media-operations release gate. Browser upload
authorization, media registry integration, CloudFront delivery, application
rendering, historical media and production cutover remain open.

## 2026-09-11 native relationship-notification acceptance amendment

`COMMUNITY3-V1-RELATIONSHIP-NOTIFICATION-PREFERENCE001-CONT1` is
`COMPLETE / PROVEN_NATIVE` for the authenticated Shared Shell bell and C3
reply-notification destination/read seam on the canonical local fixture. The
provider lookup now invokes event-level destination/authorization callbacks,
and Shared Shell preserves valid resolver URL strings as destination links.
Native proof includes one unread User A item, the full
`#reply-post:post:256447c50a99dba0` href and meaningful `community.thread`
label, canonical exact-reply click-through, visible User B reply, persistent
read state after reload, zero User B self-notifications, and no email delivery.
Existing relationship, preference, suppression, duplicate, and self-event
seams remain carried forward. This is local acceptance evidence only; it does
not change the release conclusion or authorize production/Sandy work.

## Release conclusion

Community 3.0 is a credible local, text-first discussion and migration pilot.
It is **not a production-ready replacement for legacy Chatboards**. Canonical
Community membership, consent-safe preference migration, production media
operations, user safety/moderation, URL/SEO evidence, operational cutover and
rollback, and full native acceptance remain release gates.

The registered source is `/home/bobreap/projects/teachers-net-community3`,
branch `COMMUNITY3-ui-working`, at the current pushed HEAD recorded in the
generated runtime authority record. The integrated local host is
`teachers-net-live`; its generated authority record identifies the same commit
and mounted plugin tree hash
`3adbc56908450eabaf11ee15a06db2f04101c834be35c3eddd44c8f8118616cd`, and the
runtime header reports `status=ok`. The previously ignored maintained
subject-reference class and accepted rail change are now committed; no
runtime-required Community product source remains ignored or untracked.

## V1 capability ledger

| Capability | Disposition | Evidence / release condition |
|---|---|---|
| Opaque Community identity, slug, legacy path/group mapping | PROVEN locally | Registry persists opaque `community_id`, slug, lifecycle, visibility and explicit `path_id=241` / `group_id=227` mapping. Numeric equality is prohibited. |
| Core Terms and Rail Parent presentation | PROVEN locally | C3 consumes the Views/Core Terms public published-List contract. It is presentation, not membership. |
| Community profile: description/about, artwork, owner/moderator identity, editable visibility | MISSING | AI description and About copy are literals; registry has no description/artwork/owner fields or product editor. |
| Community membership and join/leave | LOCAL FOUNDATION / RELEASE_GATE | C3 owns opaque membership persistence, idempotent active/left state, audit, explicit legacy mapping, unresolved dispositions, and rollback. Native role/product acceptance remains a release gate. |
| Topic/reply writer, thread identity, direct/deep reply lineage | IMPLEMENTED_NOT_ACCEPTED | Local publisher/repository and routes exist; release needs reconciled runtime provenance and authenticated native journeys. |
| Historical AI pilot: 3 public, 5 status-9 excluded | PROVEN local pilot only | 8/8 local reconciliation, idempotency and rollback evidence. Not corpus migration, public routing or production cutover. |
| Landing/feed/card/Quick View/composer | IMPLEMENTED_NOT_ACCEPTED | Local surfaces exist; pilot literals must not be claimed as product state and the current host mismatch blocks release-quality acceptance. |
| Feed definitions | PARTIAL | Deterministic latest exists. Popular/Unanswered are presentation literals, not queries. |
| Canonical page and modal navigation | IMPLEMENTED_NOT_ACCEPTED | Local page-versus-modal contract exists; public URL/SEO and release acceptance remain gates. |
| Community/thread follow and interest | LOCAL FOUNDATION / RELEASE_GATE | C3 now owns opaque, idempotent Community/thread follow and separately typed inferred participation with audit evidence; native relationship controls and migration remain release gates. |
| Bell, reply, reaction, mention, group notifications | LOCAL FOUNDATION / PARTIAL | C3 post-commit adapter produces bounded reply and followed-Community activity bell events through the shared provider with recipient, target, self-event and dedupe policy; authenticated reply destination/read acceptance is PROVEN_NATIVE; reactions, mentions, aggregation and full visibility acceptance remain open. |
| Notification preferences and delivery | LOCAL FOUNDATION / RELEASE_GATE | Explicit bell/email frequency state, suppression precedence, and bounded legacy-evidence reconciliation are implemented; email is evaluated but not delivered and no consent is inferred. |
| Save/hide/mute/block | MISSING / DEFERRED | Per-reader and safety relationships are absent; scope and moderation interaction require a product decision. |
| Report/moderation queue, scoped roles, sanctions | LOCAL FOUNDATION / RELEASE_GATE | C3 owns bounded report intake, private reporter identity, admin queue, report audit, and publisher-owned reversible content actions. Role matrix, sanctions, appeals, rate controls and native acceptance remain release gates. |
| Direct uploads/attachments/accessibility | PARTIAL / RELEASE_GATE | Native AWS quarantine-to-ready processing, four derivatives, metadata boundary, retry and DLQ behavior are PROVEN_NATIVE; browser upload authorization, registry integration, accessibility presentation, quotas, historical media and production operations remain open. |
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

## Relationship and notification-preference foundation

`COMMUNITY3-V1-RELATIONSHIP-NOTIFICATION-PREFERENCE001` adds the local target
foundation without changing the shared provider contract or sending mail.
Community follow, thread follow, and inferred thread participation are opaque,
typed, idempotent, separately auditable relationships. Replies may notify the
direct parent author and active thread/Community followers; new topics may
notify active Community followers. Self-events and duplicate event/recipient
pairs are suppressed, and canonical destinations are revalidated through the
Community thread route.

Explicit `bell` and `email` preferences support `immediate`, `daily`, `weekly`,
and `never`. Bell eligibility is independent of email. Email requires explicit
preference, applies unsubscribe/hard-bounce/complaint suppression, and remains
`NO_EMAIL` when absent; this objective records an eligible/no-delivery result
only. Bounded legacy evidence is retained and classified as deterministic,
confirmation-required, `NO_EMAIL`, or suppressed. The deterministic local
proof is `tools/community3/test_relationship_notification_preference.php`.
Native authenticated bell/account inspection is PROVEN_NATIVE for the retained
reply fixture; broader notification families, visibility cases, and delivery
remain release gates when their browser journeys are available.

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
3. **COMMUNITY3-V1-MEDIA-OPERATIONS-FOUNDATION001** — approved production media
   and link-preview operations, safety, accessibility and historical media.
4. **COMMUNITY3-V1-FULL-MIGRATION-AND-URL-REHEARSAL001** — all-board dry run,
   reconciliation and empirical legacy URL disposition sampling; no cutover.
5. **COMMUNITY3-V1-RELEASE-CANDIDATE-AND-CUTOVER-GATE001** — native,
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

## C3 media operations foundation — 2026-09-12

`C3-V1-MEDIA-OPERATIONS-FOUNDATION001` carries the native runtime-proof
seams from cycle `260912013811` as `PROVEN_NATIVE` and records the durable
operations authority in `community-v1-media-operations-authority-v1.md`.
The canonical future IaC home is
`infrastructure/aws/community-media/`; existing manually bootstrapped anchors
must be imported/reconciled and never recreated merely to obtain state.

The registry contract now requires immutable asset/source identity, source and
variant bytes/dimensions/formats, processor identity and digest, processing
timestamps/duration, and lifecycle/reconciliation evidence. It records
economic retained-byte facts but adds no behavioral telemetry.

Cost controls remain fail-closed: seven-day required Lambda logs, bounded
reserved concurrency, S3 lifecycle/versioning and SSE-S3 are retained; no
optional paid telemetry, edge compute, WAF, NAT, replication, CMK, or verbose
logging is enabled. Billing controls remain Engineering Director-owned and
must be verified separately.

CloudFront is `DIRECTOR_DECISION_REQUIRED`: the approved operator cannot list
distributions, and no distribution/OAC/hostname/DNS state is claimed. The
exact decision is whether initial delivery uses the default CloudFront
hostname or a Director-approved custom media hostname/DNS owner, together with
the narrowly scoped CloudFront access path.

The next application integration objective remains gated until that delivery
decision and IaC import/reconciliation authority are available. No proven
native processing seam was reopened.

## Evidence sources

- `COMMUNITY3-V1-CAPABILITY-MODERATION-READINESS001`, cycle `260908134731`.
- `COMMUNITY3-HERO-INFORMATION-AUDIT001`, cycle `260910131500`.
- Convergence addendum, V1 authority consolidation, identity, migration, URL,
  notification and media-provenance contracts.
- Current C3 source at `be6f258` and read-only integrated runtime evidence in
  the Hero audit.
