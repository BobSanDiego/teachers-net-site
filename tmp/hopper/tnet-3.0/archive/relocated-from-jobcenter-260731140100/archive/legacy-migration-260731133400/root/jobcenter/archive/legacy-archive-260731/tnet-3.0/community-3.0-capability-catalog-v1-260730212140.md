# Community 3.0 Capability Catalog v1

Status: reconciliation proposal; documentation only. No implementation is authorized.

## Purpose

This catalog reconciles the Community 3.0 product surface with the existing Teachers.Net runtime boundary. It treats identity, privacy, consent, notification, moderation, semantic, relationship, and communication capabilities as governed contracts rather than as one undifferentiated feature backlog.

| ID | Capability | Primary beneficiary | Authority/state | Dependencies | Disposition |
|---|---|---|---|---|---|
| C3-GOV-001 | Product, data, and relationship governance | Members and operators | Proposed | ED decision | Preserve |
| C3-GOV-002 | Audit, retention, suppression, and kill-switch policy | Members and trust operators | Proposed | Privacy and mail evidence | Accelerate |
| C3-IDN-001 | WordPress authentication and canonical member identity | All surfaces | Partially verified | Existing WP identity | Preserve |
| C3-IDN-002 | Path/group identity mapping | Chatboard members | Verified invariant | `path_id != group_id` | Preserve |
| C3-PRV-001 | Visibility and anonymous-post policy | Members | Proposed | Post and group contracts | Accelerate |
| C3-PRV-002 | Membership, unsubscribe, pause, and history controls | Members | Partially verified | Preference evidence | Accelerate |
| C3-CON-001 | Consent, frequency, and subscriber policy | Members and mail operators | Proposed | Communications contract | Accelerate |
| C3-CON-002 | Interests and onboarding preferences | New and returning members | Proposed | Core Terms and profile authority | Preserve |
| C3-GRP-001 | Chatboard/group membership mapping | Members | Partially verified | Legacy tables and mapping census | Accelerate |
| C3-GRP-002 | Group directories and discovery | Members | Proposed | Identity and visibility | Sequence later |
| C3-GRP-003 | Join, leave, and frequency behavior | Members | Partially verified | Subscriber policy | Accelerate |
| C3-GRP-004 | Group updates and administrative oversight | Moderators | Proposed | Audit and event contracts | Sequence later |
| C3-ENG-001 | Rich posts, replies, and anonymous posting | Authors and readers | Legacy/partial | Visibility and moderation | Preserve |
| C3-NOT-001 | Reply notices and in-product bell | Members | Partial/legacy | Event contract | Accelerate |
| C3-NOT-002 | Notification dedupe, throttle, and delivery history | Members/operators | Absent or unknown | Delivery evidence | Accelerate |
| C3-COM-001 | Transactional, digest, and administrative mail | Members/operators | Proposed | Mail policy and provider evidence | Sequence later |
| C3-COM-002 | Global pause, unsubscribe, and suppression | Members | Proposed | Subscriber policy | Accelerate |
| C3-COM-003 | Newsletter, SES, campaigns, and analytics | Subscribers/operators | Proposed | Consent and provider controls | Defer |
| C3-COM-004 | Postfix/SMTP operational boundary | Operators | Unknown | Production audit | Defer pending evidence |
| C3-OVR-001 | Moderation and oversight actions | Trust operators | Proposed | Role authority and audit | Preserve |
| C3-OVR-002 | Abuse controls and rate limits | Members/operators | Proposed | Event and delivery contracts | Accelerate |
| C3-OVR-003 | Retention and explainable audit trail | Operators/members | Proposed | Governance decision | Accelerate |
| C3-SEM-001 | Core Terms semantic authority | Product and content teams | Partially verified locally | `profilaxes` | Preserve |
| C3-SEM-002 | Portable Views and reusable subscriber views | Product teams | Proposed | Terms and policy contracts | Accelerate |
| C3-VIEW-001 | Portable View presentation contract | Members | Proposed | Semantic and visibility authority | Accelerate |
| C3-VIEW-002 | Feed, directory, and profile projections | Members | Proposed | Relationship and privacy policy | Sequence later |
| C3-EVT-001 | Durable domain events | All downstream consumers | Proposed | Event schema and audit | Accelerate |
| C3-REL-001 | Relationship governance and approvals | Members | Proposed | Consent and visibility | Defer |
| C3-DIS-001 | Explainable discovery and recommendations | Members | Proposed | Relationship graph and interests | Defer |
| C3-OUT-001 | Post-view counts and engagement analytics | Authors/operators | Proposed | Privacy and analytics policy | Defer |
| C3-ANA-001 | Aggregate product analytics | Operators | Proposed | Consent and event data | Defer |
| C3-ANA-002 | Campaign and notification outcome reporting | Operators | Proposed | Mail provider evidence | Defer |
| C3-INT-001 | External integrations and provider boundaries | Operators | Proposed | Contracts and secrets | Defer |
| C3-AI-001 | AI-assisted classification or discovery | Members/operators | Proposed | Terms, privacy, review | Defer |

The catalog deliberately does not turn these entries into schema, route, plugin, or production-change instructions. The invariant that `path_id` identifies chatboard/path/feed context while `group_id` identifies teacher-group membership remains authoritative.
