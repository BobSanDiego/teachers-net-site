# Teachers.Net Community 3.0 Master Plan v1

Status: Product authority for planning. Documentation only; no implementation is authorized by this document.

## 1. Executive Vision

Teachers.Net Community 3.0 is a governed professional community for teachers: a place where people can participate in focused conversations, form durable group relationships, express professional interests, discover relevant resources, and receive communications they have knowingly enabled. The product is becoming a coherent community experience without erasing the distinct authorities of WordPress identity, chatboards, teacher groups, Core Terms, Portable Views, subscriber policies, relationships, and communications.

Community 3.0 is not a taxonomy browser, a generic social network, or an automated mailing engine. Its value is the combination of human participation and carefully governed semantic context. A member may read or contribute to a chatboard, join a teacher group, choose explicit interests during onboarding, receive a relevant view of terms, or encounter a recommendation. Each of those experiences must explain what it is, who owns it, what evidence supports it, and what control the member has.

The product authority therefore uses a layered model:

1. WordPress authenticates the person.
2. Community owns participation, group membership, moderation, visibility, and community interaction.
3. Core Terms supplies canonical semantic vocabulary and identity.
4. Portable Views provide reusable, versioned presentations of semantic material.
5. Subscriber Policies determine how a product selects and presents shared concepts.
6. Relationship Graphs represent governed connections between people, resources, and concepts.
7. The Communications Platform governs consent, eligibility, delivery, suppression, and audit; email is one transport, not the product itself.

The permanent identity rule is foundational: a chatboard `path_id` is not a teacher `group_id`. A record may connect those identities through an explicit mapping, but neither may silently substitute for the other.

## 2. Product Principles

### Participation before automation

The community begins with useful human participation: readable conversations, safe posting, understandable membership, and credible moderation. Automation may assist discovery, classification, notification, and operations, but it may not silently create canonical relationships, infer consent, or replace human governance.

### Product authority remains local

Shared platform capabilities reduce duplication without absorbing product facts. Community owns its member experience and group workflows. Job Center owns job facts, employer authority, provenance, lifecycle, locations, publication, and applications. Core Terms classifies; Jobs authorizes; WordPress authenticates.

### Explicit interest is different from inference

A member-selected interest, a group membership, a post, a click, and a model-generated suggestion are different evidence types. They require different source, confidence, retention, correction, and expiry treatment. Membership may inform an inferred interest; it is not an explicit interest and is not communication consent.

### Consent is specific and revocable

Reading or joining a community surface does not grant permission for every communication. Subscriber policies must make category, frequency, group override, global pause, unsubscribe, suppression, and precedence understandable. A member must be able to correct or withdraw a preference.

### Safety and privacy are product features

Visibility, anonymous posting, moderation, abuse controls, retention, audit, and suppression are part of the experience. They are not an operational afterthought to be added after growth.

### Explainability and correction

Recommendations, inferred interests, and relationship candidates must expose a useful reason and provide a correction path. Relevance is not delivery consent, and machine confidence is not approval.

### Portable presentation, stable meaning

Portable Views may vary grouping, labels, ordering, and presentation for a subscriber while preserving Core Terms identity. A local display label or nesting must not silently redefine the canonical term.

### Evidence before release

Every release gate distinguishes verified implementation, approved direction, proposed design, exploratory concept, and deferred work. Unknown production behavior is not treated as evidence that a capability exists.

## 3. Community Experience

### The returning teacher

A returning member authenticates through WordPress and sees a community context that respects prior participation without overclaiming. Chatboards, posts, group membership, and notifications use their correct identities. The member can see where they belong, what is new, and how to pause or change communications. A previously joined group is not presented as an explicit interest unless the member has separately chosen it.

### The first-time participant

A new teacher can discover relevant conversations without being confronted by the entire semantic catalog. Onboarding progressively asks for high-value professional dimensions such as subjects, grade levels, or interests. The member may skip, revise, or later correct those choices. The experience explains whether a choice is an explicit interest, a view preference, or a communication preference.

### The contributor

A contributor can create a rich post, reply, react, or participate anonymously where policy permits. The contribution has an understandable visibility boundary. The contributor can see appropriate reply notices and engagement state without being enrolled in unrelated mail. Abuse controls, moderation, and audit protect the conversation while preserving a fair correction path.

### The group member

A group member can join or leave a teacher group, understand the difference between a group and its chatboard path, and control participation or notification frequency. Directories and discovery respect public/private and anonymous boundaries. Administrative actions are attributable, reviewable, and retained according to policy.

### The resource seeker

A member may receive a Portable View or a related-resource recommendation based on an explicit selection, a governed relationship, or a clearly labeled inferred signal. The experience explains the reason and does not convert discovery into communication consent. The member can correct the signal or adjust the relevant policy.

### The moderator and operator

Moderators and operators have bounded oversight actions, not an invisible superpower. They can review reports, apply moderation decisions, inspect relevant audit history, use abuse controls, and stop a risky notification or delivery path. The system distinguishes community moderation from mail operations and records who changed what and why.

## 4. Capability Model

The authoritative capability IDs are defined in the [Capability Catalog](community-3.0-capability-catalog-v1.md). The following model connects those IDs to product authority.

### Governance, identity, privacy, and consent

- **C3-GOV-001/002** govern product boundaries, audit, retention, suppression, and kill switches.
- **C3-IDN-001/002** establish WordPress identity and preserve the `path_id`/`group_id` distinction.
- **C3-PRV-001/002** define visibility, anonymous participation, membership controls, pause, unsubscribe, and history.
- **C3-CON-001/002** distinguish subscriber policy from interest and onboarding preference.

### Groups, posts, and engagement

- **C3-GRP-001–004** cover mapping, membership behavior, directories, discovery, group updates, and oversight.
- **C3-ENG-001** covers rich posts, replies, reactions, and anonymous posting.
- **C3-NOT-001/002** cover reply notices, the in-product bell, event eligibility, dedupe, throttle, and delivery history.

### Communications and oversight

- **C3-COM-001–004** cover transactional and digest mail, global pause, suppression, campaigns, provider boundaries, and the production Postfix/SMTP question.
- **C3-OVR-001–003** cover moderation, abuse controls, retention, and explainable audit.
- **C3-EVT-001** provides the durable event concept from which notification and communication eligibility can be derived.

### Semantic platform and views

- **C3-SEM-001/002** establish Core Terms and Portable Views as shared semantic authorities.
- **C3-VIEW-001/002** define reusable presentations for feeds, directories, profiles, and other subscriber surfaces.

### Relationships, discovery, analytics, and integrations

- **C3-REL-001** governs human relationships and approvals.
- **C3-DIS-001** governs explainable discovery and recommendations.
- **C3-OUT-001, C3-ANA-001/002** cover post-view counts and aggregate/campaign analytics subject to privacy and consent.
- **C3-INT-001** governs external integrations and provider boundaries.
- **C3-AI-001** permits only bounded, reviewable AI assistance after semantic, privacy, and relationship controls exist.

No capability ID is removed by this master plan. The catalog remains the detailed cross-reference and records whether each capability is verified, partial, legacy, proposed, deferred, or gated.

## 5. Release Strategy

Release sequencing is expressed as user-visible outcomes, not engineering task ownership.

### Release 0 — trustworthy foundation

Members experience stable identity and understandable visibility. The product can explain the difference between chatboard context and group membership. Product and operations have accepted privacy, consent, suppression, audit, and stop conditions.

### Release 1 — safe participation

Members can participate in rich conversations, use supported anonymous posting, manage group participation and frequency, see appropriate reply/bell state, and rely on bounded moderation and abuse controls.

### Release 2 — meaningful semantic context

Members can choose a small set of explicit interests and receive useful Portable Views without navigating an entire taxonomy. Job Center is the reference subscriber proof; its product facts remain its own authority.

### Release 3 — connected community

Members can discover groups, resources, and people through governed relationships and explainable reasons. Recommendations remain separate from communications consent, and relationship candidates require human approval where policy requires it.

### Release 4 — deliberate communications

Members receive only eligible transactional, digest, or administrative communications, with clear controls and suppression behavior. Provider, campaign, analytics, and SES expansion occurs only after the communications lifecycle is proven and operationally bounded.

### Release 5 — measured expansion

Additional subscribers, integrations, analytics, and AI assistance may adopt the shared contracts only when ownership, value, privacy, approval, correction, and rollback are clear. This is expansion of proven capability, not automatic promotion of every concept.

## 6. Platform Strategy

### Core Terms

Core Terms is the canonical semantic vocabulary and identity layer. It classifies; it does not become the owner of community membership, jobs, communications, or relationship approvals. Meta-term audits and historical migration decisions require explicit authority.

### Portable Views

Portable Views are reusable, versioned presentations over Core Terms references. A View can select or exclude terms, order and group them, provide subscriber-local labels or display nesting, support preview and publication, and record impact, rollback, deprecation, and retirement. It does not rewrite canonical meaning.

### Subscriber Policies

Each subscriber owns selection and presentation rules, provenance, freshness, correction, compatibility, and failure behavior. The subscriber policy is the boundary between shared semantic capability and local product workflow.

### Relationship Graphs

Relationship Graphs represent governed connections between members, resources, and concepts. They are not a replacement for hierarchy or membership. Evidence, confidence, status, approver, revision, expiry, and correction are first-class requirements.

### Communications Platform

The Communications Platform governs consent, eligibility, event interpretation, suppression, deduplication, coalescing, frequency limits, transports, delivery history, audit, and kill switches. Bell state, reply notices, transactional mail, digests, newsletters, and campaigns are distinct experiences that may share policy infrastructure. Email provider migration is downstream of the lifecycle, not its definition.

### Semantic Studio

Semantic Studio remains a planning concept for a future governance surface across terms, views, relationships, subscriber bindings, policies, impact analysis, and publication. This plan does not authorize Semantic Studio implementation.

## 7. Engineering Alignment: Experience → Capability → Engineering

| Experience outcome | Capability authority | Engineering alignment |
|---|---|---|
| Member understands where they participate | C3-IDN-001/002, C3-GRP-001 | Preserve WordPress identity and explicit path/group mapping. |
| Member controls visibility and communication | C3-PRV-001/002, C3-CON-001 | Define policy and precedence before delivery or schema work. |
| Member contributes safely | C3-ENG-001, C3-OVR-001/002 | Bound posting, moderation, abuse, and audit contracts. |
| Member receives relevant notices | C3-NOT-001/002, C3-EVT-001 | Define event eligibility, bell state, dedupe, and throttle before senders. |
| Member sees useful semantic choices | C3-SEM-001/002, C3-VIEW-001/002 | Use Core Terms and Portable Views without subscriber authority leakage. |
| Member discovers relevant resources | C3-REL-001, C3-DIS-001 | Require governed relationships, reason codes, and correction. |
| Operator can stop harm | C3-GOV-002, C3-OVR-002/003, C3-COM-002 | Provide suppression, audit, retention, and kill-switch decisions. |
| Product can expand safely | C3-COM-001/003/004, C3-ANA-001/002, C3-INT-001, C3-AI-001 | Sequence provider, analytics, integration, and AI work after contracts. |

The current engineering posture is documentation-first. The next engineering decision is not which code path to change; it is whether the Engineering Director accepts the product authority and authorizes a bounded contract ticket. The current local environment does not contain a cloned Community `tnet_*` data set, and production mail/group evidence remains incomplete.

## 8. ADR Index

These are permanent design decisions or required decision records. The index identifies the decision; it does not authorize implementation.

| ADR | Decision |
|---|---|
| ADR-C3-001 | WordPress authenticates; product domains retain their own facts and workflows. |
| ADR-C3-002 | `path_id` and `group_id` are distinct identities; explicit mapping is required. |
| ADR-C3-003 | Core Terms classifies; it does not own Jobs, Community membership, or communication consent. |
| ADR-C3-004 | Portable Views may localize presentation but must preserve canonical semantic identity. |
| ADR-C3-005 | Subscriber policies distinguish explicit interest, inference, behavior, membership, and consent. |
| ADR-C3-006 | Relationship candidates require evidence, explainability, governance, and correction. |
| ADR-C3-007 | Relevance and recommendation are not communication consent. |
| ADR-C3-008 | Communications are governed by lifecycle policy; email is one transport. |
| ADR-C3-009 | Suppression, pause, unsubscribe, dedupe, throttling, audit, and kill switches are product requirements. |
| ADR-C3-010 | Community moderation and communications operations remain separate authorities with explicit integration. |
| ADR-C3-011 | Semantic Studio is planning-only until separately authorized. |
| ADR-C3-012 | No migration, schema change, delivery rollout, or automated relationship activation occurs without an approved bounded ticket and stop conditions. |

## Authority and stop boundary

This master plan is the product authority above engineering roadmaps and tickets. The reconciliation package remains the evidence and readiness companion. The roadmap remains sequencing guidance. The Project Cursor and Engineering Handoff remain continuity records. If these documents conflict, the conflict must be surfaced for Engineering Director decision; it must not be resolved by silently implementing the most recent wording.

Current decision: **NO-GO for implementation.** The next decision is to approve, revise, or reject this master plan and authorize or decline a bounded M1 contract ticket. Until that decision, no code, schema, migration, production UI, taxonomy import, relationship activation, or communications delivery may begin.
