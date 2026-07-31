# Community 3.0 Subscriber Policy Contract v1

Status: accepted policy authority for bounded preparation; documentation and read-only evidence only. This contract does not enable delivery.

## 1. Purpose and authority

This contract defines how Community 3.0 distinguishes a member's identity, participation, interests, communication preferences, eligibility, suppression, and delivery history. It governs policy resolution for bell state, reply notices, group activity, transactional notices, digests, announcements, newsletters, and campaigns across supported channels in principle.

It does not define database schema, mail-provider configuration, queue implementation, production migration, UI implementation, legal advice, or product-specific facts. It does not authorize sending mail or activating any delivery path.

The [Community 3.0 Master Plan](community-3.0-master-plan-v1.md) is the product authority. The Communications Platform is the policy and lifecycle capability described by that plan. Domain events provide evidence for eligibility; groups own participation; Core Terms owns canonical semantic identity; Portable Views own reusable presentation; subscriber products own their facts and workflows. A semantic subscriber is not automatically a communications subscriber.

## 2. Subscriber and preference concepts

| Concept | Contract definition | Must not be treated as |
|---|---|---|
| Authenticated member | A person recognized by the WordPress identity boundary. | Consent, membership, or delivery eligibility. |
| Product subscriber | A product-specific relationship in which a member can receive or view that product's governed output. | A universal subscription across Teachers.Net. |
| Group member | A membership relationship to a teacher group identified by canonical `group_id`; chatboard `path_id` remains distinct. | An explicit interest or consent. |
| Explicit interest | A member-selected professional or content interest with source and effective state. | Inferred interest, membership, or consent. |
| Inferred interest | A labeled inference from behavior, membership, content, or a governed model with confidence and expiry. | An explicit choice or permission to send. |
| Communication category | A named purpose such as reply, digest, security, or campaign. | A product, channel, or semantic term. |
| Channel | A transport or presentation surface such as bell, email, or account feed. | A category or consent itself. |
| Frequency | The permitted cadence: immediate, daily, weekly, or never. | Eligibility or a guarantee of delivery. |
| Consent | A scoped, recorded permission or required-action basis for a communication category/channel. | Relevance, membership, or delivery success. |
| Eligibility | The result of category, consent, event, suppression, policy, and operational checks. | Consent or delivery. |
| Suppression | A higher-priority condition preventing communication for a defined scope. | A preference that can be silently overwritten. |
| Delivery | An attempted or completed transport action after eligibility resolves. | A promise that a member saw or engaged with a message. |
| Preference history | Durable evidence of changes, actor, source, scope, effective time, reason/context, and reversal. | The current preference alone. |

The invariant chain is: membership is not interest; interest is not consent; consent is not eligibility; eligibility is not delivery. `path_id` and `group_id` remain distinct identities.

## 3. Communication categories

The following are policy categories only. Defaults mean the contract's safe starting state, not an enabled sender.

| Category | Classification | Consent basis | Channels in principle | Default/frequency | Group override/global pause/unsubscribe | Suppression and audit |
|---|---|---|---|---|---|---|
| Replies | Operational/transactional | Implied by the member's relevant participation, subject to policy | Bell, account feed, optional email | Bell immediate; email optional | Group scope may apply; pause applies to optional email; class unsubscribe does not erase required account context | Suppression, event ID, recipient, and outcome audited |
| Reactions | Operational | Optional and explicit where delivered | Bell, account feed, optional digest | Never for email by default; bell may be immediate | Group override may scope; pause applies to optional channels | Dedupe and audit required |
| Mentions | Operational | Implied by the targeted action for the relevant notice | Bell, account feed, optional email | Immediate where eligible | Group scope may apply; pause applies to optional email | Abuse and suppression controls required |
| Group activity | Operational/digest | Explicit category preference; membership alone is insufficient for email | Bell, account feed, email digest | Never until selected; daily/weekly if selected | Group override applies; global pause applies; unsubscribe applies to optional class | Membership and preference history retained separately |
| Moderator or administrative notices | Operational/transactional | Required for relevant account or community action, but scope must be justified | Bell, account feed, email | Immediate when necessary; no promotional batching | Group scope may apply; pause/unsubscribe may not block required notice | Actor, reason, scope, and audit evidence required |
| Account/security notices | Transactional/security | Required by requested account or security action | Email, account surface | Immediate | No group override; global pause and promotional unsubscribe do not block necessary security notice | Security audit and delivery outcome required |
| Digests | Operational/promotional depending on content | Explicit opt-in or documented permitted basis | Email, account feed | Daily/weekly/never | Group and category overrides apply; pause/unsubscribe apply | Candidate coalescing, dedupe, and audit required |
| Announcements | Operational/promotional depending on purpose | Explicit category decision; no inference from membership | Bell, account feed, email | Never until selected | Group override may scope; pause/unsubscribe apply unless required notice | Campaign identity and consent basis audited |
| Newsletters | Promotional | Explicit opt-in | Email and account surface | Never until selected; daily/weekly only if offered | Group override does not silently enroll; pause/unsubscribe apply | Permanent opt-out evidence and provider outcomes required |
| Campaigns | Promotional | Explicit campaign/category consent | Email, account surface | Never until selected | No silent group enrollment; pause/unsubscribe apply | Campaign, audience, consent, suppression, and outcome audited |

Transactional or security notices are not a license for unrelated promotional content. Promotional categories never become enabled because a member joined a group, viewed a term, followed a relationship, or received a recommendation.

## 4. Preference hierarchy and precedence

Resolution is deterministic. The first applicable blocking or governing rule wins; later rules cannot silently reverse it.

| Order | Rule | Effect |
|---:|---|---|
| 1 | Legal/security-required notice determination | Permits only the necessary notice; scope and reason are required. |
| 2 | Hard suppression | Blocks the affected scope regardless of user preference. |
| 3 | Global unsubscribe | Blocks the unsubscribed optional communication class permanently until explicit re-subscribe. |
| 4 | Global pause | Blocks optional communications during its active period. |
| 5 | Category preference | Allows or blocks the named category. |
| 6 | Channel preference | Allows or blocks the channel within the category. |
| 7 | Group-level override | Narrows or selects behavior for the named group; cannot override higher rules. |
| 8 | Frequency | Resolves immediate, daily, weekly, or never for eligible content. |
| 9 | Temporary mute | Blocks or defers the defined scope until expiry. |
| 10 | Event eligibility | Confirms that the event, recipient, visibility, and product policy qualify. |
| 11 | Dedupe/throttle/coalescing | Removes duplicate or excessive candidates without creating consent. |
| 12 | Final delivery decision | Records allow, block, defer, or no eligible channel and the reason. |

An explicit security notice may bypass optional preferences only to the extent required. No group override can defeat suppression, unsubscribe, pause, or a prohibited channel. A bell-only result is valid when email is not eligible.

## 5. Global pause

Global pause applies to optional communications across the member's account and product scopes covered by the pause. It does not erase preferences, membership, explicit interests, history, or required security/legal notices. A pause must state its scope, effective time, current state, and the classes excluded from it.

The initial contract supports an indefinite pause and, if later approved, clearly labeled temporary durations. A temporary pause expires at its recorded time; an indefinite pause remains until the member resumes optional communications. Restoration returns to the preserved pre-pause category/channel/group preferences rather than silently opting the member into anything new.

Pause changes require actor, source, effective time, scope, prior state, new state, and context in preference history. The UI must explain that pausing is not deletion and that required notices may still arrive. Category and group preferences remain stored but inactive while pause applies.

## 6. Unsubscribe

Optional promotional and digest classes must provide a clear one-click unsubscribe path where the channel and provider support it. The action must identify the communication class and persist the decision without silently changing unrelated categories.

Unsubscribe persists until the member explicitly re-subscribes through a clearly scoped confirmation. Re-subscribe must not be inferred from login, group membership, content viewing, a new relationship, or a campaign click. Confirmation should show the affected class, channel, frequency, and effective state.

Unsubscribe does not suppress necessary account/security or legally required notices, and it does not erase account-closure or deletion obligations. It must create auditable history with actor, source, scope, effective time, prior state, new state, and any provider evidence. Re-enrollment requires a new explicit action; no sender may silently reverse it.

## 7. Suppression

| Suppression type | Authority | Precedence/reversibility | Evidence and audit |
|---|---|---|---|
| User-requested suppression | Member | High; reversible only through explicit correction/resume | Request, scope, source, time, prior/new state |
| Hard bounce | Mail operations/provider evidence | High for affected address/channel; reversible after verified remediation | Provider event, address scope, timestamps, remediation |
| Complaint | Provider/trust operations | Highest for affected promotional scope; requires reviewed re-enable | Complaint evidence, reviewer, decision, time |
| Administrative suppression | Authorized operator | High within stated scope; reversible by authorized correction | Actor, authority, reason, scope, review |
| Abuse or safety suppression | Trust/moderation authority | High and potentially immediate; expiry/review required | Incident, action, reviewer, appeal/review state |
| Legal suppression | Authorized legal/privacy authority | Highest; only authorized review may change | Basis, scope, authority, review history |
| Temporary operational suppression | Communications operator | Blocks during incident/window; expires or is explicitly renewed | Incident, start/end, scope, operator, resolution |
| Global kill switch | Authorized operations/trust authority | Blocks the selected delivery path or class for all affected recipients | Activation, reason, scope, actor, recovery verification |

Suppression is not a preference and must not be overwritten by preference restoration. Every suppression has a scope, evidence, authority, effective time, and either expiry or an explicit review condition. The contract does not choose a numeric retention period; retention remains an Engineering Director/privacy decision.

## 8. Frequency and group overrides

The supported conceptual frequencies are **immediate**, **daily**, **weekly**, and **never**. Frequency is resolved per category and channel. A product may offer a group-level setting for group activity, but group membership alone cannot create an email subscription.

The inherited default is the category/channel default. A member's category preference narrows or enables that category. A group override may narrow it further or select a permitted cadence for that group. It cannot override hard suppression, unsubscribe, global pause, security requirements, or a category set to never. Changes become effective at the recorded policy boundary; previously generated but unsent candidates must be re-evaluated.

High-volume activity is coalesced into the selected digest where eligible. If no permitted email frequency remains, the member may still receive bell or account-feed state where that channel is eligible. “Never” means no communication in that category/channel, not deletion of history or removal from the group.

## 9. Preference history and audit

Every preference, pause, unsubscribe, restoration, correction, suppression, and administrative intervention must be represented in durable history before implementation can be accepted. At minimum it records:

- previous value and new value;
- actor and actor type;
- source or interface;
- effective time and recorded time;
- reason or context;
- channel, category, and group scope;
- restoration or reversal relationship;
- administrative intervention and authority;
- suppression evidence where applicable.

History is append-oriented: a current value may be derived, but the event that changed it must remain inspectable. Retention must be long enough to support member correction, operational investigation, abuse review, and required disclosure, without inventing a numeric period in this contract. Access must be scoped and privacy-conscious.

## 10. Correction and restoration

Members require a visible correction path for current preferences and a way to report an incorrect inferred or legacy state. Administrators may correct records only within their authority, with reason and audit. Corrections must not silently reconstruct consent from old behavior.

Restoration after pause returns to the preserved pre-pause state. Restoration after unsubscribe requires explicit re-subscribe for the affected class. Restoration after suppression requires the responsible authority to review the evidence and record a new decision; user preference alone cannot defeat a complaint, legal, safety, or unresolved hard-bounce suppression.

Conflicting legacy values remain classified as evidence requiring reconciliation. When current fields disagree across Community, WordPress, or BuddyPress, the system must not choose a consent state by convenience. It must preserve source, expose the conflict, apply the safe non-delivery interpretation for optional communications, and require an explicit resolution path.

## 11. Legacy field reconciliation

The following is a read-only repository census. It is not a data migration or claim that production behavior is fully known.

| Observed source/behavior | Classification | Contract treatment |
|---|---|---|
| `tnet_memberships.email_posts` | Partial/legacy | Historical preference evidence only; not current consent without explicit reconciliation. |
| Existing group frequency controls and mail-frequency lookup | Partial/legacy | Group-scoped evidence; must be mapped to category/channel/frequency without assuming `path_id == group_id`. |
| BuddyPress notification preferences, usermeta, options, and notification table | Legacy/unknown authority | Separate framework; not silently combined with Community policy. |
| Chatboard sender, queue, digest worker, throttle, and last-send state | Absent/unknown in inspected repository evidence | No delivery capability may be inferred. |
| Dormant/legacy mail code and `wp_mail()` paths | Legacy/platform behavior | Requires source, category, recipient, and suppression audit before any use. |
| Newsletter/bulk-mail remnants | Unknown/legacy | Requires a separate consent, audience, provider, and unsubscribe census. |
| Custom preference tables/options outside inspected Community evidence | Unknown | Production read-only evidence required before implementation. |

The existing evidence confirms that membership, group frequency, WordPress/BuddyPress notification state, and mail infrastructure are not one authority. No data was changed in this ticket.

## 12. Product subscriber matrix

| Product | Product-owned facts/workflow | Policy application |
|---|---|---|
| Community | Chatboards, groups, posts, moderation, membership, community events | May define Community categories and group overrides; cannot infer consent from membership. |
| Job Center | Jobs, employers, provenance, lifecycle, locations, publication, applications | May consume shared policy contracts; retains product authority; no Job Center implementation here. |
| Lesson Bank | Lessons, resources, authoring, review, licensing | May subscribe to semantic or communications policy only through explicit product scope. |
| CE | Courses, providers, enrollment, completion, credentials | Requires separate category, channel, consent, and correction scope. |
| Marketplace | Listings, sellers, buyers, transactions, fulfillment, safety | Cannot inherit Community promotional consent or group membership. |
| Future products | Their own facts and workflows | Must establish a subscriber contract before consuming shared policy. |

Cross-product identity may support recognition, but it does not grant cross-product consent. A Portable View or relationship may be shared as governed semantic context without enrolling a member in communications.

## 13. Decision examples

1. A group member selects weekly group updates. The selection applies only to the named group/activity category and permitted channel. It does not create a global newsletter subscription.
2. A user globally pauses optional mail. Optional category/channel decisions remain preserved but inactive; a required security notice may still be sent.
3. A user unsubscribes from promotional mail. Newsletters and campaigns are blocked until explicit re-subscribe; replies or security notices are resolved separately.
4. A reply event is eligible for the bell but not email. The final decision records bell allow and email not eligible; no email is sent.
5. A hard bounce is recorded. The affected email channel is suppressed above the user's preference until remediation evidence supports review.
6. A complaint creates promotional suppression. A prior opt-in does not defeat it; re-enable requires authorized review and evidence.
7. A moderator notice is necessary for a community action. It may bypass optional pause or category preferences only to the justified scope; it cannot bypass legal or hard suppression constraints.
8. Membership ends while historical preference remains. Membership-dependent eligibility ends; the preference history is retained and is not converted into an explicit interest or silently restored later.
9. An inferred interest suggests content. It may shape a Portable View or recommendation where allowed, but it cannot trigger mail or create consent.
10. Multiple groups generate digest candidates. Eligible candidates are evaluated against each group/category preference, coalesced and deduplicated, then blocked by pause, unsubscribe, or suppression before any final delivery decision.

## 14. Open decisions and evidence gaps

| Item | Classification |
|---|---|
| Approval of transactional versus promotional classification for edge cases | Engineering Director decision / external research required |
| One-click unsubscribe, list-unsubscribe, confirmation, and re-subscribe expectations | External research required |
| Complaint/bounce provider semantics and operational re-enable evidence | Production evidence required / external research required |
| Preference-center disclosure, correction, and account-deletion behavior | Engineering Director decision / external research required |
| Numeric retention periods and access roles for preference/suppression history | Engineering Director decision |
| Authoritative reconciliation of production `email_posts`, group controls, BuddyPress state, bulk-mail remnants, and custom options | Production evidence required |
| Exact event schema, queue/digest mechanics, and transport implementation | Implementation detail deferred |
| Whether bell-only notices need a separate policy category or remain a channel resolution | Engineering Director decision |
| First bounded implementation surface and test fixture strategy | Engineering Director decision |

No broad web research was performed. These items are recorded for the targeted research and decision pass required before delivery implementation.

## 15. Acceptance criteria for later implementation

Any later implementation must demonstrate, in a safe isolated environment before production consideration:

1. Separate representations and resolution for membership, interest, consent, eligibility, suppression, and delivery.
2. Correct preservation of `path_id` versus `group_id`.
3. Deterministic precedence with explainable final decisions.
4. Category, channel, frequency, group override, global pause, unsubscribe, and suppression behavior matching this contract.
5. No silent consent reconstruction from legacy fields, membership, inference, relevance, or recommendations.
6. Append-oriented preference and suppression history with the required audit fields.
7. Correction, restoration, expiry, and administrative-review behavior with no silent re-enrollment.
8. Bell-only and no-mail outcomes supported without treating them as delivery failures.
9. Dedupe, throttle, coalescing, kill-switch, and provider-outcome behavior proven without sending unsolicited mail.
10. Cross-product isolation demonstrated: one product's consent cannot authorize another product's communication.
11. Read-only legacy/production evidence reconciled or explicitly classified before migration decisions.
12. Verification evidence and rollback/stop conditions recorded before any production or provider change.

Follow-up recommendation: C3-TRUST002 may proceed only as a separately authorized suppression and abuse-control contract review after Engineering Director review of this document. It is not authorized by this ticket.
