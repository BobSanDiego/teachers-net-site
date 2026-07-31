# Community 3.0 Suppression and Abuse-Control Contract v1

Status: documentation and read-only evidence only. This contract establishes policy authority; it does not implement suppression, send mail, mutate queues, or activate controls.

## 1. Purpose and authority

This contract defines suppression, complaints, bounces, abuse controls, moderation interaction, operational kill switches, audit, reversibility, expiry, and safe recovery for Community 3.0 communications and participation. It is subordinate to the Community 3.0 Master Plan for product direction and complementary to the Subscriber Policy Contract for consent, preferences, pause, unsubscribe, frequency, and eligibility.

It does not define schema, provider settings, queue mechanics, numeric abuse thresholds, legal advice, production operations, or product-specific facts. Domain events provide evidence; moderation governs content and participation; Communications governs eligibility and delivery; products retain their own facts and workflows. Suppression is not a preference. Membership is not interest, consent, eligibility, or delivery. `path_id` and `group_id` remain distinct.

## 2. Suppression model

Suppression is a first-class policy state that blocks or narrows an action. Every suppression has:

- type, scope, authority, source, and evidence;
- effective time and expiry or review condition;
- reversibility and current status;
- reason, actor, and affected member/address/group/product/channel/category;
- append-oriented audit history and any appeal or incident reference.

The safe default for an unresolved optional-communication conflict is no optional delivery. Suppression does not erase preferences, membership, interests, content, or history. It must be evaluated again when scope, status, event, channel, or operational state changes.

## 3. Suppression types

| Type | Trigger and scope | Authority, reversal, and evidence | Precedence/recovery |
|---|---|---|---|
| User-requested suppression | Member blocks a defined product/category/channel or address. | Member creates; member restores unless a higher suppression applies. Request and scope required. | Above ordinary preference; explicit restoration only. |
| Global unsubscribe | Member opts out of an optional communication class. | Member creates; explicit re-subscribe required. | Blocks that class across defined products/channels; no silent re-enrollment. |
| Hard bounce | Provider indicates address is invalid or permanently undeliverable. | Mail authority records provider event; review needs remediation evidence. | Blocks affected email address/channel; bell may remain eligible. |
| Soft-bounce threshold state | Repeated or sustained temporary failures reach a later-approved threshold. | Provider/operations records evidence; threshold is implementation-deferred. | Blocks only after approved rule; review after remediation. |
| Complaint | Provider/member reports unwanted or abusive mail. | Trust/mail authority records complaint; re-enable requires reviewed explicit decision. | Promotional scope is blocked immediately; no automatic restoration. |
| Administrative suppression | Authorized operator blocks a defined scope for a stated reason. | Authorized operator creates/reverses with audit and review condition. | Higher than preference within scope; reversible by authority. |
| Abuse or safety suppression | Spam, harassment, flooding, coordinated abuse, or safety incident. | Moderator/trust authority; appeal or review required. | May block participation, notices, or delivery only within justified scope. |
| Legal/privacy suppression | Required by approved privacy/legal process. | Authorized privacy/legal authority only. | Highest applicable scope; reversal requires the same authority. |
| Temporary operational suppression | Incident, provider fault, or maintenance requires a temporary block. | Operations activates with incident reference and expiry/review. | Blocks named path; recovery requires verification. |
| Category/channel suppression | A category or channel is blocked independently. | Member or authorized policy/operations actor, by scope. | Does not leak to unrelated categories/products. |
| Product-specific suppression | A product's communication or participation is blocked. | Product authority within contract; cross-product scope requires explicit basis. | Does not suppress unrelated products silently. |
| Global kill switch | Emergency stop for a named transport, event, product, or all optional delivery. | Authorized operations/trust authority; incident evidence required. | Overrides eligible candidates in scope; re-enable is staged and verified. |

Each type records trigger, scope, actor, source, reason, evidence, status, effective time, expiry/review, reversal, and audit. A higher suppression is never cleared by restoring a lower preference.

## 4. Complaint handling

Provider complaint evidence must identify the provider event, affected address or member mapping, category/transport if available, event time, and evidence quality. The affected promotional scope is blocked immediately when the complaint is credible. Transactional or security treatment is evaluated separately and may remain eligible only when necessary, lawful, and not itself the subject of the complaint.

Review authority determines whether the complaint is duplicate, ambiguous, misattributed, or valid. Duplicate events must be coalesced without losing original provider references. A member re-enable requires an explicit, scoped action plus reviewed evidence; login, membership, preference restoration, or a new campaign cannot restore delivery automatically. Audit and retention must support the complaint, decision, appeal, and any recovery without inventing a numeric period in this contract.

## 5. Bounce handling

| Failure | Policy treatment |
|---|---|
| Hard bounce/invalid address | Create address/channel suppression from provider evidence; retain bell/account-feed eligibility where otherwise allowed. |
| Soft bounce | Record failure; do not invent a threshold or immediately convert every transient event into permanent suppression. |
| Repeated soft bounce | Apply only a later-approved threshold/state; require review and remediation evidence before re-enable. |
| Transient provider failure | Record operational failure; retry behavior is implementation-deferred and must not bypass suppression. |
| Mailbox full | Treat as temporary evidence unless provider establishes permanence; review repeated failures under approved policy. |
| Domain-level failure | Scope cautiously to affected domain/channel; do not suppress unrelated products or addresses without evidence. |
| Delivery deferral | Record provider response and re-evaluate candidate; no silent success assumption. |

Retry limits are implementation-deferred because no existing authority establishes numeric values. Remediation evidence may include corrected address, verified domain/provider state, or reviewed member action. Re-enable is staged, scoped, audited, and prohibited from silently restoring unrelated categories.

## 6. Abuse controls

| Abuse area | Detection signal | Temporary/durable control and review |
|---|---|---|
| Posting rate | Burst volume, repeated failures, or unusual cadence | Temporary rate restriction; durable restriction only after review; preserve posts/evidence. |
| Reply rate | High-volume replies across threads | Coalesce or defer notices; moderator review for durable action. |
| Mention abuse | Repeated unsolicited mentions or targeting | Temporary mention limit; scoped account/group action after review. |
| Reaction abuse | Automated or repetitive reactions | Limit or remove reactions; retain evidence and support correction. |
| Notification flooding | Excessive eligible events or recipient reports | Dedupe, coalesce, mute, or event-type kill switch; never create consent. |
| Group join/leave abuse | Repeated cycling or coordinated membership actions | Temporary membership action limit; moderator review; do not confuse with suppression of unrelated products. |
| Repetitive content/spam patterns | Duplicate text, links, accounts, or destinations | Hide as spam while retaining evidence; durable restriction requires review. |
| Bot-like behavior | Automation signals, impossible cadence, client/IP/device patterns where appropriate | Challenge, throttle, or temporary restriction; avoid treating a signal as conclusive. |
| Coordinated abuse | Shared targets, timing, content, or account relationships | Incident-scoped controls, moderator/trust review, and appeal path. |
| Account-level restriction | Accumulated credible abuse evidence | Named scope, reason, expiry/review, and durable audit; no silent cross-product ban. |
| IP/device signals | Correlated operational signals, only where legally and operationally appropriate | Supporting evidence, not sole identity proof; access, retention, and appeal controls required. |

Automated actions are reversible and bounded unless an authorized policy says otherwise. Durable restrictions require moderator/trust authority, evidence, reason, scope, and appeal/review. False-positive correction must be available. No threshold is numerically specified here.

## 7. Moderation interaction

Moderation, promotion, notification eligibility, and delivery are separate decisions. A post may be immediately published, hidden as spam while evidence is retained, removed, or left visible while a user is restricted. Moderator notes, action reason, actor, scope, and appeal state belong in audit history.

Promotion/editorial action does not create notification eligibility. A moderated or promoted post is evaluated separately for visibility and event eligibility. A user disablement may prevent new participation and optional notices while preserving evidence and necessary account/security handling. Appeals may reverse moderation or suppression only through the responsible authority and do not erase history.

## 8. Kill switches

| Control | Activation scope | Recovery requirement |
|---|---|---|
| All optional email | All non-required email | Confirm incident resolved; re-evaluate candidates. |
| Promotional email | Newsletters, campaigns, promotional announcements | Verify suppression and unsubscribe state; staged re-enable. |
| Digest email | Digest generation/transport | Re-evaluate queued candidates; discard or regenerate only by approved rule. |
| Transactional email | Named transactional class or all transactional email | Security/operations review; required notices need explicit treatment. |
| Bell generation | In-product notification creation | Confirm event and read-state integrity before re-enable. |
| Specific event types | Replies, reactions, mentions, group activity, or named event | Test event eligibility and dedupe in scope. |
| Specific product subscribers | Community, Job Center, Lesson Bank, CE, Marketplace, or future product | Product owner and operations verify isolation. |
| Specific groups | Named group scope | Confirm `group_id` mapping and no path identity substitution. |
| Specific provider transport | Email provider or named transport | Provider/operations verification before staged recovery. |
| All Community notifications | Community bell and communication paths | Community authority plus operations review; re-evaluate all candidates. |

Every activation records authority, scope, effective time, reason, incident, and audit. Queued candidates are re-evaluated after a suppression or kill-switch change; they are discarded only under an explicit retention/replay decision. Re-enable requires checks, staged scope, monitoring, and named authority. This ticket does not activate any switch.

## 9. Precedence and resolution

| Order | Rule | Result |
|---:|---|---|
| 1 | Legal/security-required notice determination | Permit only necessary notice within justified scope. |
| 2 | Legal/privacy suppression | Block applicable action. |
| 3 | Complaint suppression | Block affected promotional scope. |
| 4 | Hard-bounce suppression | Block affected address/channel. |
| 5 | Abuse/safety suppression | Block named participation, event, or delivery scope. |
| 6 | Administrative suppression | Block named scope. |
| 7 | Global unsubscribe | Block opted-out optional class. |
| 8 | Global pause | Block optional communications while active. |
| 9 | Category/channel preference | Resolve member-selected state. |
| 10 | Group override | Narrow permitted group behavior only. |
| 11 | Event eligibility | Confirm event, visibility, recipient, and product policy. |
| 12 | Dedupe/throttle | Remove duplicate or excessive candidates. |
| 13 | Kill switch | Block if the named operational control is active. |
| 14 | Final delivery decision | Record allow, block, defer, bell-only, or no eligible channel and reason. |

The kill switch is operationally checked immediately before the final action and cannot reverse higher policy; it can only block. A hard bounce may block email while bell remains eligible. A group suppression does not suppress Job Center mail. A security notice does not authorize promotional content.

## 10. Reversibility, expiry, and recovery

User-requested, category, temporary operational, and some administrative states are reversible with explicit action and history. Legal, complaint, safety, and hard-bounce states require responsible-authority review and evidence. Expiry is never assumed: a state either has a recorded expiry or a review condition.

Recovery is staged: confirm cause/remediation, verify scope, re-evaluate queued candidates, enable a narrow path, inspect outcomes, and only then consider broader recovery. Restoration never silently recreates consent. Rollback means reactivating the prior safe block state while preserving all events and incident references. Recovery verification must be recorded before normal operation resumes.

## 11. Audit and evidence

Durable audit must record trigger, source, evidence, actor, authority, scope, affected member/address/group/product/channel/category, effective time, expiry/review time, current status, reversal/restoration, reason, appeal, operational incident reference, and provider event reference. Records are append-oriented and access-controlled. Retention must support member correction, abuse review, provider reconciliation, appeals, and required disclosure; no numeric period is invented here.

## 12. Legacy and production evidence census

| Evidence area | Classification | Current contract conclusion |
|---|---|---|
| `tnet_memberships.email_posts` | Partial/legacy | Preference evidence, not suppression authority or current consent. |
| Group frequency controls | Partial/legacy | Group-scoped evidence; must preserve explicit `local_path -> group_id` mapping. |
| BuddyPress preferences/usermeta/options/notifications | Legacy/unknown | Separate framework; no safe Community authority established. |
| Bounce/complaint handling | Unknown | Production/provider evidence required; no repository authority found. |
| Suppression lists/provider feedback loops | Unknown | Require read-only production/provider audit. |
| Postfix logs/queue behavior | Unknown | Prior bounded work did not mutate or establish delivery authority. |
| WordPress mail failures | Partial platform behavior | Core `wp_mail()` paths exist; Community delivery semantics are not established. |
| Moderator block/ban behavior | Partial/legacy | Product/moderation evidence requires separate census. |
| Spam/rate controls | Partial/legacy/unknown | No Community implementation authority established. |
| Dormant newsletter/campaign systems | Unknown/legacy | Require separate consent/audience/provider audit. |
| Emergency disable switches | Unknown | No switch is activated; operator authority must be defined. |

This census was read-only. No queues, options, users, memberships, schemas, providers, cron, or production records were changed.

## 13. Product and channel matrix

| Product | Product scope | Bell/feed | Transactional email | Digest/promotional email | Administrative notices |
|---|---|---|---|---|---|
| Community | Groups, posts, moderation, participation | Community policy | Scoped Community events | Explicit policy only | Moderator/required scope |
| Job Center | Jobs, employers, applications | Job Center policy | Job Center authority | Separate consent | Product authority |
| Lesson Bank | Lessons and resources | Product policy | Product authority | Separate consent | Product authority |
| CE | Courses and credentials | Product policy | Product authority | Separate consent | Product authority |
| Marketplace | Listings and transactions | Product policy | Product authority | Separate consent | Safety/transaction scope |
| Future products | Explicit product contract | Product policy | Product authority | Separate consent | Product authority |

Controls are scoped by product and channel. Cross-product suppression leakage is prohibited unless an explicit legal, safety, or operational scope requires it. Cross-product consent inference is prohibited. A product may consume a shared relationship or Portable View without inheriting another product's communication preference.

## 14. Decision examples

- A hard bounce blocks email for the affected address/channel but preserves bell eligibility where permitted.
- A complaint blocks promotional email; re-enable requires reviewed evidence and explicit action.
- A moderator disables a spammer while retaining posts, evidence, notes, and appeal history.
- An optional-email kill switch activates during an incident; candidates are blocked and later re-evaluated.
- Abusive mentions trigger a temporary mention restriction, not a universal cross-product ban.
- Repeated soft bounces remain evidence until an approved threshold and review rule exists.
- A user corrects an invalid address; the hard-bounce state remains until responsible review verifies remediation.
- A group-specific abuse control does not silently suppress unrelated Job Center or Marketplace communications.
- A required security notice remains eligible where legally permitted and necessary.
- Queued notifications are re-evaluated after suppression changes; they are not assumed safe because they were previously queued.

## 15. Open decisions and evidence gaps

| Item | Classification |
|---|---|
| Provider complaint and bounce semantics and feedback-loop mapping | Production evidence required / external research required |
| Suppression-list and one-click unsubscribe expectations | External research required |
| Retention and access periods for abuse, provider, and appeal evidence | Engineering Director decision / external research required |
| Numeric abuse-rate thresholds and soft-bounce thresholds | Engineering Director decision / implementation detail deferred |
| Appeal authority and cross-product trust escalation | Engineering Director decision |
| Kill-switch operators and separation of duties | Engineering Director decision |
| Queue replay, discard, and recovery behavior | Engineering Director decision / implementation detail deferred |
| Product-versus-global suppression scope | Engineering Director decision |
| Production Postfix/provider and dormant campaign census | Production evidence required |

No broad web research was performed. C3-NOT001 may be considered only after Engineering Director review of this contract; it is not authorized by this ticket.

## 16. Acceptance criteria for later implementation

Later implementation must demonstrate deterministic precedence, explainable final decisions, append-oriented audit, no silent restoration, scoped suppression, safe bell-only outcomes, provider outcome handling, kill-switch verification, reversible staged recovery, cross-product isolation, false-positive correction, preserved evidence, and rollback/stop conditions. It must also demonstrate the membership/interest/consent/eligibility/delivery distinctions, preserve `path_id` versus `group_id`, and prove that transactional and promotional communications remain separate.

Implementation acceptance requires isolated tests for complaints, hard/soft bounces, abuse controls, moderation interaction, queue re-evaluation, kill switches, recovery, appeals, and legacy conflicts. It must not require production writes or unsolicited mail as a prerequisite for policy verification.
