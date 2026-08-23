# Teachers.Net Profile Project Cursor

Project state: Planning

## Identity

- Project: Teachers.Net Profile
- Project record: `docs/process/conversation-handoff/projects/profile.json`
- Repository: `/home/bobreap/projects/teachers-net-site`

## Current boundary

Bounded project onboarding is complete. Broader Profile implementation, schema,
API, migration, and UI remain outside this boundary. The bounded first-party
avatar capability has an accepted architecture contract at
`docs/profile/avatar-architecture.md`.

## Evidence and authority

The supplied 2026-08-11 ChatGPT transcript is preserved as conversation
evidence under `docs/process/conversation-handoff/profile/chatgpt-sources/`.
It is not automatic product or architecture authority. The avatar architecture
contract is now established; runtime and QA are to be implemented only within
`PROFILE-AVATAR001`.

## Known preliminary direction

The conversation discusses a durable member Profile, onboarding as a workflow,
privacy and communication intent, and future relationship policy. These remain
candidate direction pending explicit product and architecture decisions.

## Immediate next boundary

Execute the bounded `PROFILE-AVATAR001` implementation against the avatar
architecture contract. Keep the broader Profile project state as Planning and
do not modify Job Center in that ticket.
