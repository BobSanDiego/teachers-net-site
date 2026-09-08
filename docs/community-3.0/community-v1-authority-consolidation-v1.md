# Community 3.0 V1 Authority Consolidation

Status: active durable product/architecture guidance recorded from Engineering Director decisions on 2026-09-08.

This document consolidates accepted scope and boundaries. It is not an implementation claim and does not authorize schema, runtime, production, or public-cutover changes. Detailed evidence remains in the named diagnostic reports.

## Authority boundaries

- Core Terms own semantic identity and true ancestry.
- Durable Views may own curated consumer membership and order.
- Community 3.0 owns runtime feeds, subscriptions, placements, moderation, and notifications.
- WordPress identity remains the account authority.
- Legacy `path_id` and `group_id` are distinct identities and must be mapped explicitly; numeric coincidence is not authority.

## Term, Community, and feed model

- A normal canonical Community is an activated social resource bound to one primary Core Term.
- There is no requirement for one future Community per legacy Chatboard.
- There is one canonical post/thread; cross-feed placement does not create duplicate post records.
- Specific or child content may flow upward into broader parent or composite feeds. Broad parent content does not automatically flow downward into child feeds.
- Future explicit placement/tagging remains possible without being required for v1. Placement provenance must remain distinct from canonical post identity.

## Hot Topics and future discovery

Hot Topics is a permanent discovery concept. It may contain curated Term-backed Communities through Views and may later surface dynamic trending/breakout threads. Any future trend score must consider velocity, novelty, participant breadth, and stale/churn suppression rather than raw lifetime totals. Per-user exposure and personalization are separate from site-wide trend scoring.

## Navigation and shell

Persistent left-rail priority is: Home, Jobs, Lesson Plans, Chatboards, Hot Topics, Grade Levels, Subject Areas, and personalized `[State] Teachers`. A contextual parent may be added for a nonpersistent parent family, with the current board nested beneath it. My Groups is not a permanent rail item; Chatboards landing exposes subscriptions plus discovery. There is no permanent More item. Help and Settings remain minimal lower-left utility controls.

The right rail owns discovery, related content, My Groups/Hot Topics where useful, resources/jobs/lessons, and monetization. Search is first-class but need not permanently consume the fixed header. Board-local text subnav may become sticky at the fixed header. `+Create Post` remains persistent/global and resolves contextually to the current Community or main C3 posting context.

## Composer and feed surfaces

- Faux and modal composers are the preferred posting UX.
- Only implemented capabilities are active in v1; Poll and Resource may remain near-v1 design seams.
- Feed sort/filter controls may scroll normally and are not sticky.
- Explicit Community navigation outranks algorithmic inference.

## Notifications and personalization

Preserve thread-interest states `OFF`, `INFERRED_FROM_PARTICIPATION`, and `EXPLICIT_THREAD_FOLLOW`. Explicit follow and direct-reply bell notifications are v1-worthy. Inferred participation may come later and is bell-only by default; it must not imply email/push consent. Explicit `OFF` suppresses later inferred reactivation. Controls should allow turning thread notifications off. Aggregation and dedupe must preserve canonical thread/event identity.

Future controls may include more/less-like-this, hide, why-am-I-seeing-this, not-relevant-to-this-feed, mute/snooze/block, and placement feedback. These are not v1 blockers. Explicit feedback is distinct from engagement and abuse reports.

## Moderation minimum

Credible v1 requires report intake and governed reasons, a moderator queue, scoped moderator/admin authority, reversible post actions, warning/restriction/suspension/ban policy, audit and moderator notes, reporter privacy, duplicate aggregation, spam/rate controls, and review/appeal boundaries.

Placement/distribution moderation remains separate from canonical-post moderation. Prefer reversible state and evidence retention over destructive deletion where practical.

## Scope boundary

### Essential v1

Canonical narrow Community feed/thread; text-first topic/reply posting; modal/faux composer UX; moderation foundation; primary Term binding; Community subscriptions; explicit thread and direct-reply notifications; Latest ordering; low-cost Unanswered; canonical route/search hooks; and preserved provenance/event seams.

### High-value near-v1

Curated Hot Topics via Views; composite/ancestor feeds; save/hide/follow; inferred participation; notification aggregation; production media/link-preview readiness; and placement feedback/provenance.

### Deferred

Dynamic trending/personalization/exposure suppression; recommendation controls; polls/specialized post types; broad resource/media types; mentions until governed; inferred email/push; and global search implementation.

## Evidence pointers and guardrails

- `COMMUNITY3-FEED-RELATIONSHIP-ARCH-DIAGNOSTIC001`, finalized and validated as cycle `260907204929`.
- `COMMUNITY3-V1-CAPABILITY-MODERATION-READINESS001`, finalized and validated as cycle `260908134731`.

Future tickets must label each requested seam as essential v1, near-v1, or deferred and must not infer implementation from this summary. No public cutover, Sandy/production change, Shared Shell redesign, or product behavior change is authorized by this document.
