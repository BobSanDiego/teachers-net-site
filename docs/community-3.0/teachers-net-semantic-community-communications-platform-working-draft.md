# Teachers.Net Semantic, Community, and Communications Platform

## Architecture and Product Definition — Working Draft

**Status:** Working architecture and product-definition document
**Workstream:** Teachers.Net Community 3.0
**Date:** 2026-07-29
**Authority:** Developed from the attached Community 3.0 transcript and
grounded in the current Teachers.Net Core Terms, Job Center, Community 3.0,
and legacy notification records.

> This document memorializes verified findings and developed architectural
> direction. It does not claim that the proposed semantic, community, or
> communications platform has been implemented. Future tickets must cite the
> relevant section and preserve the boundaries recorded here. Changes to major
> principles require an explicit documentation revision or ADR treatment.

## Executive definition

Teachers.Net has several valuable products and a large inherited community
surface, but they do not yet share a dependable semantic language or a safe,
modern communication substrate. The immediate symptom was a chatboard
notification control whose values are stored but whose delivery system is
missing. Investigation showed that repairing a dropdown would not restore a
trustworthy communications product: there is no active sender, queue, digest
worker, moderation-aware delay, suppression system, or auditable delivery
decision.

The broader opportunity is a shared platform in which three things remain
distinct but cooperate:

1. **Core Terms** provides canonical semantic authority. It classifies things
   without becoming the owner of Jobs, chatboards, profiles, or mail.
2. **Community and product subscribers** use those terms to describe entities,
   organize discovery, and express user interests under subscriber-specific
   policies.
3. **Communications** turns eligible, consented events into controlled
   notifications through a replaceable transport. Email is one transport, not
   the platform itself.

This direction preserves the legacy community rather than flattening it. A
chatboard remains a publication and conversation surface. A group remains a
membership and governance object. A Core Term remains a canonical concept.
Membership may be evidence of interest, but membership is not interest by
definition. A view may present terms in a product-friendly order, but a view
does not replace the canonical term graph. A relationship may connect two
concepts without making one the parent of the other.

The proposed platform therefore solves a sequence of connected problems:

- make legacy content and groups understandable without pretending old IDs or
  old labels are already a coherent taxonomy;
- let products reuse semantic authority without allowing any one product to
  own it;
- let people express, revise, and understand their interests and communication
  choices;
- promote only content that has passed moderation, consent, eligibility, and
  burst controls;
- support related resources and recommendations without hiding opaque,
  ungoverned scoring behind a personalized interface.

## Authority and status model

The following distinctions prevent conceptual drift.

| Category | Meaning in this document |
|---|---|
| Verified current state | Observed in the repository, local lab, or completed audit. |
| Existing implemented system | Code or data that currently exists, even if incomplete or legacy. |
| Converged direction | A strong architectural direction developed in the transcript; not an implementation claim. |
| Working concept | Requires further product, governance, or technical design. |
| Open question | Deliberately unresolved; future work must not silently decide it. |
| Deferred possibility | Interesting later option outside the first governed slice. |

The current Community 3.0 cursor remains in Maintenance after a focused legacy
teacher-group identity correction. That correction established the permanent
invariant that a chatboard `path_id` is not necessarily a teacher `group_id`.
This working draft does not reopen or rewrite that milestone. It uses the
invariant as a prerequisite for future mapping work.

Core Terms is a stable shared classification platform. Its repository and
internal names remain governed by existing Core Terms documentation. Jobs is a
subscriber and authorizes job facts; it does not own canonical classification.
WordPress authenticates. Membership Taxonomy remains a distinct curation and
human-review workstream for historical taxonomy; it is not a Core Terms rename.

## Verified legacy state

### Chatboards, groups, and identity

The legacy system contains chatboard paths, teacher groups, posts, topics, and
membership records. The relevant authorities are not interchangeable:

- `tnet_local_data.path_id` identifies a chatboard/path/feed context.
- `tnet_groups.group_id` identifies a teacher group.
- `tnet_memberships.group_id` identifies the group to which a membership
  belongs.
- `tnet_chatposts.local_id` can carry chatboard identity.

The AI in Education example demonstrated `path_id = 241` and `group_id = 227`.
Any future mapping must resolve `local_path` to the canonical group rather than
assuming numeric equality.

Membership is the legacy authority for group membership. Moderation remains a
separate concern: a person can be a member without every post being suitable
for publication or promotion. Publication, membership, and communications
eligibility must not be collapsed into one boolean.

### Legacy notification findings

The visible group dialog offers four choices: **no updates**, **occasionally**,
**just new topics**, and **any activity**. `js/groupset.js` submits `a0` through
`a3` to the `btr_groupset` WordPress AJAX action. The PHP handler strips the
`a` and stores the result in `tnet_memberships.email_posts`.

The active JavaScript and PHP renderer disagree about the meanings of values 1
and 3. The PHP read helper also prepends `a` before the renderer applies
`intval()`, which causes the displayed value to fall through to “no updates.”
These are confirmed UI/state defects. They do not prove that a working sender
exists.

`email_responses` exists in the table and in an old generated response form,
but no active notification read/write/send path was found. No active chatboard
sender, queue, digest worker, throttle, last-send timestamp, or chatboard cron
process was found. Local DDEV mail is Mailpit for capture; it is not production
delivery infrastructure. The distinction is essential: preference storage is
not communications capability.

The legacy investigation also found a separate BuddyPress notification
framework, usermeta keys, options, and a notifications table. No verified
stable mapping makes that framework the authority for legacy chatboard
notification delivery. It must not be silently combined with `tnet_*` data.

### Why a repair is not the platform

Changing the four labels or correcting readback could make the control appear
consistent while leaving the harder questions unanswered. A safe system needs
to know whether a post was approved, whether an author is generating a burst,
whether a recipient has paused communication, whether a prior event was
already sent, and whether a complaint or bounce requires suppression. It also
needs to cancel queued content that is later hidden or classified as spam.

The first architectural conclusion is therefore negative but productive: do
not restore automatic group-wide email by repairing the old control. Preserve
legacy values for audit, define new consent explicitly, and build a governed
communications boundary separately.

## Community architecture

### Chatboard

A chatboard is a publication and conversation surface organized around a path,
topic stream, post history, and presentation context. It may have a public URL,
local feed identity, a description, and a relationship to one or more groups.
The chatboard is not automatically a canonical concept. “AI in Education” may
be a useful editorial surface while the concepts used to classify it are
Artificial Intelligence, Technology, Assessment, Academic Integrity, and
Professional Development.

### Group

A group is a social and governance object. It has membership, joining/leaving
behavior, possible moderators or administrators, and potentially a chatboard
surface. A group may exist before its publication surface is fully mapped. A
chatboard may also need an explicit association rather than an assumed identity.

### Membership

Membership is an authority relationship: a user belongs to a group under a
status and possibly a role. It may contribute evidence that the user is
interested in the group’s subject matter, but it is not equivalent to an
explicit interest selection. People join for access, habit, moderation, or
temporary work and may not want recommendations or mail.

### Moderation, publication, and promotion

Moderation determines whether content is acceptable and what state it occupies.
Publication determines whether content is visible on a product surface.
Promotion is an additional action: it distributes or elevates content through
a feed, recommendation, digest, or notification.

These actions must remain separate. A published post is not necessarily
notification-eligible. A moderated post may remain visible without being
promoted. A promoted item should carry evidence that it passed the relevant
eligibility gate. This separation protects users from having questionable
content reach their inbox merely because it was briefly visible.

### Direct and future social surfaces

Member directories, profiles, private notes, friendships, and conversations
may share identity and permission infrastructure, but they are not the same as
group membership or public chat. Private notes should not be treated as
published content; friendships should not automatically create notification
subscriptions; and a directory listing should not disclose more than the
person has chosen to expose.

## Teachers.Net Communications Platform

The Communications Platform is a policy and delivery boundary around events,
consent, moderation, and transport. It is not a mail template collection and
not a replacement for product ownership.

### Core capabilities

- centralized consent and preference history;
- global pause, active, paused, and permanent suppression states;
- one-click unsubscribe and a preference center;
- sitewide defaults and category preferences;
- group overrides resolved against global policy;
- immutable event ledger with source and moderation provenance;
- delayed eligibility and cancellation;
- moderation and spam gates;
- per-recipient, per-group, per-thread, per-author, and sitewide limits;
- deduplication and coalescing;
- daily or weekly digests with safe excerpts;
- replaceable transports, beginning with Mailpit locally;
- delivery, bounce, complaint, and suppression audit;
- global, group, recipient, and transport kill switches.

### Effective preference resolution

An effective preference is a decision, not a copied field. A future resolver
should consider, in order, permanent suppression, complaint/bounce suppression,
global pause, explicit global opt-out, category preference, group override,
event type, recipient eligibility, and current moderation state. The resolver
must record which rule won and when.

```text
effective = resolve(
  recipient,
  event.category,
  event.group,
  explicit_preference_history,
  suppression_state,
  global_pause,
  moderation_state,
  current_limits
)
```

Legacy `email_posts` 0–3 values are migration evidence, not current consent.
The platform should preserve their original value and source but require
explicit re-consent for any pilot.

### Event ledger and lifecycle

Each candidate event needs an immutable ID, source post/reply ID, group ID,
author ID, event type, moderation state, eligibility state, queued time,
release time, and cancellation reason. A safe lifecycle is:

```text
candidate event
  -> hold
  -> moderation recheck
  -> consent and suppression decision
  -> limit/deduplication decision
  -> coalesce or queue
  -> final moderation recheck
  -> transport
  -> delivery result and audit
```

Pending, quarantined, hidden, deleted, spam, blocked, and unknown content must
fail closed. Suspicious-but-not-confirmed content should remain held for
review, not become an inbox test. A suggested first holding period is 30
minutes for a direct reply to a user’s own topic and 24 hours for broad group
or digest candidates. These are design proposals, not current settings.

### Communication products

The same platform could eventually support direct reply notices, discussion
updates, job alerts, lesson recommendations, professional-development
announcements, product announcements, and weekly digests. Each product remains
the authority for its event facts and eligibility context. The shared platform
supplies consent, policy resolution, queueing, safety, transport, and audit.

Email is a transport. Other transports—on-site notifications, account feeds,
or future push mechanisms—may use the same event and policy decisions without
forcing every communication through email.

## Core Terms subscriber architecture

Core Terms is canonical semantic authority, not a product-owned taxonomy. An
application may classify an entity with a term, offer a selection interface,
or define a local policy, but it must not silently redefine the term for its
own workflow.

The intended subscriber sequence is:

1. **Job Center** is the first major subscriber. Jobs owns job facts,
   provenance, employer authority, lifecycle, location, and publication rules;
   Core Terms supplies reusable classification.
2. **Chatboards and Groups** are the next major subscriber. Their surfaces and
   memberships remain community-owned while their subjects can be classified
   by shared terms.
3. **Profiles and onboarding** use terms to express explicit interests and
   professional dimensions.
4. **Communications** uses terms for categories and preference scope; it does
   not own the category vocabulary.
5. **Lesson Bank, publications, marketplace, webinars, and future products**
   can subscribe when their contracts are defined.

The distinction is between a product/entity and the Core Terms that classify
it. A job is not a term. A chatboard is not necessarily a term. “Special
Education” may be a canonical concept used by jobs, lessons, chatboards, and
profiles. Product names, labels, and workflow states should not be promoted to
canonical concepts merely because one interface needs them.

## Chatboard and Group mapping

Mapping begins with identity and provenance, not with a bulk rename. For every
legacy surface, record chatboard path, group association, name, description,
historical labels, active state, moderator review state, and candidate terms.
Resolve the `local_path -> group_id` relationship explicitly. Preserve old IDs
and URLs as source facts.

For an AI in Education surface, a candidate semantic profile might include:

| Role | Candidate term |
|---|---|
| Primary subject | Artificial Intelligence |
| Supporting subject | Technology |
| Supporting subject | Academic Integrity |
| Supporting subject | Assessment |
| Context/audience | Professional Development |

“Detecting AI Cheating” may be a more specific content or discussion surface,
but it should not automatically become a child of Artificial Intelligence if
the canonical model says that detection, academic integrity, and assessment
are cross-cutting concepts. Multiple chatboards may overlap the same terms
without being duplicates. A term map should therefore support many-to-many
classification and human rationale.

Membership can be one evidence source for an inferred interest. It must be
stored with source, confidence, and time, and it must not silently become an
explicit preference or a communication opt-in.

## User interests, profiles, and onboarding

The platform should distinguish:

- **explicit interest:** selected or affirmed by the user;
- **inferred interest:** a system hypothesis derived from evidence;
- **behavioral evidence:** membership, reading, posting, search, selection,
  saved jobs, or engagement;
- **source:** where the evidence came from;
- **confidence:** how strongly the evidence supports the hypothesis;
- **professional profile:** role, career stage, geography, grade, subject, and
  population dimensions;
- **communication subscription:** permission to receive a category or event.

These are related but not interchangeable. A person may join a group for a
single project and should not be enrolled in a newsletter. A person may select
Special Education as an interest without joining a corresponding group. A
person may read a topic repeatedly while still declining email.

Likely onboarding axes include grade level, subject, geography, role, career
stage, student populations, Special Education, gifted education, languages,
and selected professional interests. Popular or timely topics may be offered
progressively, but should not displace durable professional dimensions.

Progressive discovery is preferable to presenting the entire taxonomy at
once. The first screen can establish a few high-value axes; later interactions
can invite refinement when the product has a relevant context. A living tag
cloud or semantic constellation could make relationships discoverable in the
future, but it is a working interface concept, not an approved implementation.

## Portable Views

A **View** is a named, reusable projection over canonical Core Terms. It may
define included and excluded terms, order, grouping, local display nesting,
featured or hidden items, labels, presentation hints, version, status, and
subscriber bindings. It references terms; it does not copy or fork their
canonical identity.

One View could be used by an employer picker, a lesson selector, onboarding,
a chatboard topic selector, search facets, or communication categories. A View
can be cloned as a template and then adapted without making its terms product
owned. Multiple Views can present the same dimension for different audiences.

Each View needs draft, preview, publish, rollback, versioning, and impact
analysis. Publishing should expose which subscribers use it and which fields or
selection policies might change. A view is not a shortcut around governance.

### View examples

- Job Center employer picker: a constrained subject or role view.
- Lesson Bank picker: grade and subject views with educator-facing labels.
- Onboarding interests: progressive, small sets of high-value concepts.
- Chatboard topic selector: terms that help describe a new discussion.
- Search facets: a discoverability projection, not a new taxonomy.
- Communications categories: terms grouped into understandable subscription
  choices, with explicit consent language.

## Subscriber selection policies

Views define what is presented; subscriber policies define how a user may select
it. A Job Center form might require exactly one primary subject, allow up to
three supporting subjects, require one or more grade levels, and impose
different rules for employer configuration versus a job listing. Onboarding
may allow more interests but fewer required fields. Communications may allow a
category preference without allowing a user to select arbitrary delivery
events.

Policies may define required versus optional fields, single-select versus
multi-select, maximum counts, validation, display labels, and workflow timing.
Subscribers do not own the canonical options. They own selection and
presentation policy within their contract.

## Job Center application

The proposed administrator workflow begins with an expandable Core Terms tree,
not a private Jobs taxonomy. An administrator can select terms with checkboxes,
bulk-import them into a View, and then arrange the selected set in a second
stage. Drag-and-drop can reorder terms, create display groups, and establish
view-local parent/child presentation. A preview shows employer-facing fields
before publication.

Separate Views can serve subjects, grades, roles, student populations,
certifications, and other dimensions. Existing Views can be reused or cloned as
templates. Canonical hierarchy remains the semantic truth; local display
nesting is a presentation decision and must be labeled as such when it could
mislead users.

This preserves the current Jobs boundary: Terms classify; Jobs authorizes;
WordPress authenticates. Jobs continues to own job facts, source provenance,
employer authority, coordinates, lifecycle, and application integrity. A View
does not grant an employer permission and a term does not establish an
employer claim.

## Meta terms reassessment

Portable Views may eliminate the need for many previously proposed meta terms,
but that does not justify deleting them. Each existing meta term should be
classified as one of:

- durable canonical concept;
- structural dimension;
- presentation grouping;
- subscriber availability control;
- ordering or prominence metadata;
- administrative-only label.

The correct sequence is audit, map, compare references, identify consumers,
design migration or conversion, verify impact, and only then consider
retirement. Historical rationale must remain recoverable. A View can replace a
presentation grouping without proving that the old term was never meaningful.

## Relationship graph

The platform has at least four different structures:

| Structure | Answers |
|---|---|
| Canonical hierarchy | What is this concept and what is its parent/child structure? |
| Portable View | How does a subscriber present a governed subset? |
| Semantic relationship | What approved concept connects to this one, and why? |
| Behavioral affinity | What appears related from observed behavior, with what confidence and expiry? |

Artificial Intelligence and Academic Integrity may be related without one
being the parent of the other. Special Education and Bullying Prevention may
be related in practice without a taxonomic hierarchy. The first governed model
should remain small: hierarchy, aliases, approved related concepts, and
derived affinities. Avoid dozens of relationship types before the governance
and use cases justify them.

Semantic relationships should include type, direction where relevant,
rationale, provenance, confidence, status, approver, and revision history.
Behavioral affinities should have source, confidence, time window, decay or
expiry, and a clear statement that they do not alter canonical meaning.

## Automated relationship discovery

Codex or another analysis process may periodically propose candidate
relationships by examining chatboard descriptions, discussion classifications,
Job Center classifications, Lesson Bank metadata, terms used together,
profile co-selection, search and engagement behavior, existing Views, and
content overlap.

Machines may discover, cluster, and score candidates. Administrators approve,
reject, defer, or change the relationship type. Every candidate should retain
evidence, provenance, confidence, and the model or process that proposed it.
An approved relationship becomes governed; a rejected candidate should not
reappear without new evidence.

Run broad sweeps on a schedule or after meaningful data changes, not after
every minor edit. Use targeted analysis for one term, subscriber, or suspected
gap. Immediate integrity checks should verify local consequences after an
administrator edits a relationship, while expensive full-system analysis can
remain scheduled.

## Recommendations and related resources

Shared terms, approved relationships, Views, subscriber assets, and explicit
or inferred interests can support related chatboards, discussions, lessons,
jobs, newsletters, webinars, onboarding suggestions, personalized feeds,
cross-product search, and “you may also be interested in” features.

The platform should explain why an item is related: shared term, approved
relationship, selected interest, group membership evidence, or recent behavior.
It should let policy decide whether a source is appropriate for a context. A
job search may prioritize job classification and location; a lesson search may
prioritize grade and subject; an onboarding experience may show broad concepts;
a communication category may require explicit opt-in.

No opaque scoring algorithm is settled here. Recommendations must be
reviewable, bounded, privacy-aware, and reversible. Behavioral signals should
not silently become a public label or an email subscription.

## Communications safety design

The first communications design should include an immutable event ledger with
source post/reply, group, author, event type, moderation state, eligibility,
queue and release times, and cancellation reason. Content that is pending,
quarantined, hidden, deleted, spam, or unknown must not send. A queued event
must be canceled if moderation later removes it.

Initial proposed controls are deliberately conservative: three messages per
recipient per hour, ten per recipient per day, twenty per group per hour, one
hundred per group per day, three per thread per hour, ten per thread per day,
five eligible events per author per hour, and a sitewide emergency ceiling.
Exceeding a limit should coalesce or defer into a digest, not create a retry
storm. Repeated edits and reposts need deterministic deduplication.

Digest candidates should have a minimum item threshold, a maximum item count,
no empty sends, no duplicates across digests, safe excerpts, and a final
moderation recheck. Weekly is safer than daily for broad re-engagement. Direct
replies to a user’s own topic are a higher-priority, narrower pilot candidate,
but still require consent, delay, moderation gates, caps, unsubscribe, audit,
and a kill switch.

Deliverability requires sender identity, SPF/DKIM/DMARC alignment, invalid
address handling, bounce and complaint suppression, provider message IDs,
metrics, rate ramp, and transport rollback. Mailpit must be first in local
verification. A provider is replaceable infrastructure, not the authority for
consent or event eligibility.

## Governance principles

1. Hierarchy defines what a concept is.
2. Relationships define what a concept connects to.
3. Views define how a subscriber presents a governed set.
4. Policies define how users may select it.
5. Products remain independent while sharing semantic meaning.
6. Membership, interest, behavior, consent, and publication remain distinct.
7. Machines propose; humans govern canonical relationships.
8. Canonical terms remain free of product-specific presentation concerns.
9. Communications decisions are auditable and fail closed on uncertainty.
10. Email is a transport, not the Communications Platform.
11. Verified history, architectural intent, and future proposals remain
    explicitly separated.
12. Legacy migration preserves source facts and rationale before normalization.

## Proposed project approach

The following is a restrained program, not a list of implementation tickets.
Each phase should produce one coherent artifact and a validation gate.

| Phase | Objective and deliverable | Exclusions | Validation gate and dependency |
|---|---|---|---|
| 1. Authority alignment | Register this document, reconcile Core Terms and Community 3.0 terminology. | No schema or code changes. | Owners agree on status and references. |
| 2. Meta-term audit | Classify existing terms and document durable concepts versus presentation artifacts. | No deletion or migration. | Impact and consumer inventory complete; depends on Phase 1. |
| 3. Subscriber contracts | Define Core Terms contracts for Jobs, Groups, profiles, and communications. | No subscriber UI implementation. | Contracts preserve Jobs authority and Core Terms ownership. |
| 4. View governance | Specify View data, lifecycle, versioning, preview, rollback, and impact analysis. | No production View editor. | One reviewed data and governance model. |
| 5. Job Center View pilot | Use a bounded, non-production configuration example for one dimension. | No broad taxonomy import. | Preview, policy, and rollback evidence; depends on Phase 4. |
| 6. Chatboard mapping audit | Map paths, groups, descriptions, and candidate terms with provenance. | No automatic classification or URL migration. | Human-reviewed sample includes divergent IDs. |
| 7. Interest model | Define explicit, inferred, behavioral, confidence, and consent boundaries. | No personalization rollout. | Privacy and product review; depends on mapping vocabulary. |
| 8. Communications architecture | Convert CHAT004 direction into a ledger, consent, moderation, and transport contract. | No send activation. | Mailpit-only design review; depends on Phase 7. |
| 9. Event and moderation design | Specify eligible events, hold, cancellation, limits, and safe excerpt policy. | No production mail. | Tabletop abuse scenarios pass. |
| 10. Relationship candidate model | Define small relationship types and approval states. | No autonomous canonical writes. | Human governance workflow accepted. |
| 11. Relationship pilot | Run a bounded, read-only candidate analysis for selected terms. | No automatic publish. | Evidence and rejection/defer paths reviewed. |
| 12. Recommendation proof | Demonstrate explainable related-resource retrieval using approved relationships. | No opaque personalization. | Reproducible reasons and privacy review. |
| 13. Subscriber expansion | Extend contracts to Lesson Bank and other products. | No cross-product rollout by assumption. | Each subscriber has an accepted contract and owner. |

The sequence matters. Terms and ownership must be stable enough to define
Views. Views and policies must be clear before onboarding or Jobs forms use
them. Interests and consent must precede communications. Moderation and event
eligibility must precede any sender. Relationship proposals must be governed
before recommendations make them visible.

## Open questions

- Is a dimension metadata, a first-class entity, or a special term class?
- How much local nesting may a View create without misleading users?
- May one canonical term appear more than once in a View?
- Are subscriber-local labels allowed, and how are they governed?
- How should interest confidence be stored, explained, and revised?
- How should behavioral affinity expire or decay?
- How often should relationship sweeps run?
- Who may approve canonical relationships?
- How should legacy chatboards be mapped and reviewed?
- How should group and chatboard identity be separated technically?
- How do existing Job Center selections migrate into Views?
- Which meta terms remain canonical after the audit?
- What is the first bounded implementation pilot?
- What historical or external service, if any, consumed legacy email fields?
- Which moderation states are authoritative for each content source?
- What legal and consent review is required for re-engagement?
- Which provider and sender identity are authorized for a future pilot?

## Compact terminology glossary

| Term | Definition |
|---|---|
| Core Term | Canonical semantic concept shared across products. |
| Subscriber | Product or platform capability that consumes Core Terms under a contract. |
| Chatboard | Publication and conversation surface with path/feed identity. |
| Group | Membership and governance object associated with people and possibly a chatboard. |
| Membership | Authority relationship between a user and group. |
| View | Reusable projection of canonical terms for a subscriber or context. |
| Selection policy | Subscriber rule for required, optional, single-select, multi-select, and maximum choices. |
| Semantic relationship | Human-governed connection between concepts. |
| Behavioral affinity | Time-bounded, evidence-based hypothesis derived from behavior. |
| Promotion | Deliberate distribution or elevation of content beyond ordinary publication. |
| Event ledger | Immutable record of a candidate communication event and its decisions. |
| Suppression | State preventing delivery because of consent, bounce, complaint, policy, or operator action. |

## Context Bootstrap

This section is intentionally detailed enough to restore working context in a
future ChatGPT or Codex session without reopening the original transcript.

Teachers.Net is a multi-product WordPress project with three important
boundaries. WordPress authenticates users. Jobs authorizes job facts, employer
relationships, lifecycle, provenance, application integrity, and coordinates.
Core Terms is the reusable classification platform, although the repository
and PHP namespace still use the historical `profilaxes` name. Membership
Taxonomy is a separate human curation and classification workstream for the
historic Teachers.Net taxonomy; it is not a Core Terms rename. These boundaries
are durable and should be preserved when discussing a new semantic or
community platform.

The Community 3.0 workstream is currently in Maintenance after a focused
teacher-group identity correction. The important invariant is that a legacy
chatboard `path_id` is not necessarily the same value as a teacher-group
`group_id`. AI in Education demonstrated `path_id = 241` and `group_id = 227`.
The canonical group must be resolved from the explicit local-path mapping, and
membership queries must use the canonical group ID. Do not reintroduce numeric
equality as a shortcut. No new group architecture was authorized by that
correction; a focused design ticket is needed for a broader direction.

The notification investigation began with a visible group dialog that offers
“no updates,” “occasionally,” “just new topics,” and “any activity.” The active
JavaScript in `groupset.js` submits `a0` through `a3` to the WordPress AJAX
action `btr_groupset`. The active PHP handler in
`functions-ajax-groupjoin.php` strips the `a` and stores the resulting number
in `tnet_memberships.email_posts`. The read helper reads that field for the
current user and group. The active PHP renderer and JavaScript disagree about
which labels values 1 and 3 represent. The read helper also prefixes `a`
before the PHP renderer applies `intval`, causing the display to fall through
to “no updates.” `email_responses` is present in the legacy table and in an old
generated response form, but no active consumer or sender was found.

Most importantly, the audit found no active chatboard email sender, queue,
digest worker, throttle, last-send field, moderation-aware delivery process,
or chatboard-specific cron hook in the local runtime. DDEV Mailpit is available
for local capture, but it is not an external delivery service. Generic
WordPress `wp_mail` calls exist for unrelated utilities and do not prove that
chatboard notification delivery works. No production mail infrastructure was
queried in the diagnostic ticket. Therefore a UI repair is not a notification
platform: it could make a preference appear consistent while still sending
nothing, or while leaving a future sender without moderation and abuse gates.

The developed direction is a shared Communications Platform. It should own
consent history, global pause, active/paused/permanent suppression, one-click
unsubscribe, category and group preferences, effective-preference resolution,
an immutable event ledger, delayed eligibility, moderation rechecks,
cancellation, rate limits, deduplication, coalescing, digests, replaceable
transports, delivery outcomes, and emergency kill switches. It should not own
the product facts that produced an event. A chatboard owns its content and
group context; Jobs owns job facts; Lesson Bank owns lesson facts; the
Communications Platform decides whether an eligible event can be delivered.
Email is one transport and must remain replaceable.

Every future notification event should retain an immutable ID, source post or
reply ID, group ID, author ID, event type, moderation state, eligibility state,
queued and release times, and cancellation reason. Content that is pending,
quarantined, hidden, deleted, spam, blocked, or unknown must fail closed. A
holding period allows moderation signals to arrive before distribution. A
queued event must be canceled if later moderation removes the source. Direct
replies to a person’s own topic can be a higher-priority, narrower pilot, but
they still require consent, moderation checks, caps, unsubscribe, audit, and a
kill switch. Broad group activity must never be an immediate automatic send.

The initial safety proposal is intentionally conservative: three messages per
recipient per hour and ten per day; twenty per group per hour and one hundred
per day; three per thread per hour and ten per day; a cap on eligible events
from one author; and a sitewide emergency ceiling. Repeated edits and reposts
must deduplicate. When limits are reached, coalesce or defer into a digest
rather than retrying. A first broad digest should be weekly, manually approved,
limited in item count, non-empty, free of duplicates, and rechecked for
moderation. Daily digests are a later possibility, not a starting assumption.

Consent must be explicit. Legacy `email_posts` values are historical evidence,
not current consent. A future preference model should retain original values
and source for audit but require re-consent for a pilot. It should distinguish
global pause, category preferences, per-group overrides, event types, consent
timestamps, one-click unsubscribe, hard-bounce suppression, complaint
suppression, and operator suppression. A `0/0` legacy state must not be called
an opt-out without sender evidence. Unknown values must not authorize a send.

Core Terms provides the semantic foundation for the broader platform. Job
Center is the first major subscriber and Chatboards/Groups are the next major
subscriber. Profiles and onboarding, Communications categories, Lesson Bank,
publications, marketplace, webinars, and future products can subscribe later.
The rule is that applications do not own canonical taxonomy. A product/entity
is classified by Core Terms; it is not itself automatically a Core Term. Jobs
continues to own job facts and authority, while Core Terms supplies reusable
classification.

Legacy chatboards should be mapped with provenance. Preserve path IDs, group
IDs, URLs, historical names, descriptions, and moderation evidence. A surface
such as AI in Education might use Artificial Intelligence as a primary term
and Technology, Academic Integrity, Assessment, or Professional Development as
supporting terms. Detecting AI Cheating may be a discussion surface or a
classification context; it should not be made a child of Artificial Intelligence
without a canonical rationale. Multiple chatboards may share terms. Membership
can provide evidence of inferred interest with source, confidence, and time,
but membership is not explicit interest and neither is communication consent.

User profiles and onboarding should separate explicit interests, inferred
interests, behavioral evidence, source, confidence, professional dimensions,
and communication subscriptions. Candidate axes include grade, subject,
geography, role, career stage, student population, Special Education, gifted
education, language, and selected professional interests. Progressive discovery
is preferred to presenting the entire taxonomy at once. A living tag cloud or
semantic constellation is a possible future interface, not approved
implementation.

A portable View is a reusable projection over canonical terms. It may include
or exclude terms, order and group them, provide local display nesting,
featured/hidden items, labels, presentation hints, version, status, and
subscriber bindings. It references canonical terms rather than copying them.
One View can support an employer picker, Lesson Bank picker, onboarding
interests, chatboard selectors, search facets, or communication categories.
Views need draft, preview, publish, rollback, versioning, and impact analysis.
Selection policies are separate: Job Center might require one primary subject,
allow three supporting subjects, and define grade rules; onboarding might allow
more choices; Communications might require explicit opt-in. Subscribers own
these policies but never own the canonical options.

The Job Center administrator concept is a two-stage workflow: browse an
expandable Core Terms tree, select terms into a View, then arrange them through
ordering, display groups, and local presentation nesting before previewing the
employer-facing result. Separate Views can cover subjects, grades, roles,
student populations, certifications, and other dimensions. Existing Views can
be reused or cloned as templates. Canonical hierarchy is semantic truth; local
nesting is presentation and must not be mistaken for canonical parentage.

Portable Views may make some previously created meta terms unnecessary, but no
meta term should be deleted immediately. Audit each as a durable concept,
structural dimension, presentation grouping, subscriber availability control,
ordering/prominence metadata, or administrative label. Identify consumers and
history before migrating, converting, or retiring it.

Do not collapse hierarchy, Views, semantic relationships, and behavioral
affinities. Hierarchy says what a concept is. A View says how a subscriber
presents it. An approved relationship says what connects to it. A behavioral
affinity says what appears related from time-bounded evidence. The initial
relationship model should remain small: hierarchy, aliases, approved related
concepts, and derived affinities. Machines may propose and score relationships
from descriptions, classifications, co-selection, search, engagement, Views,
and content overlap. Humans approve, reject, defer, or change the relationship
type. Evidence, confidence, provenance, and revision history must be retained.
Broad sweeps should be scheduled; targeted analysis can investigate one term or
gap; integrity checks should run after edits without requiring a wasteful full
system analysis every time.

The restrained project sequence is: authority alignment; meta-term audit;
subscriber contracts; View data and governance; a bounded Job Center View
pilot; chatboard/group mapping; user-interest design; communications
architecture; event-ledger and moderation design; relationship candidate
model; bounded relationship analysis; explainable recommendation proof; and
expansion to Lesson Bank and other subscribers. Do not create dozens of
implementation tickets before these contracts and ownership boundaries are
reviewed. Do not implement migrations, redesign production UI, enable mail, or
make autonomous canonical relationship writes from this document.

Future work should begin with the smallest artifact that resolves one open
question. It should cite this document, the relevant Core Terms contract, and
the applicable subscriber authority. It should preserve the distinction
between verified current state, existing implementation, converged direction,
working concept, open question, and deferred possibility. The next recommended
work is a documentation-only authority review of this draft and the existing
Core Terms/meta-term inventory—not a notification send, taxonomy migration,
or cross-product implementation.
