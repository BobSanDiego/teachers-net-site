# Membership Taxonomy Project Cursor

## Project State

Planning

## Purpose

Curate historic Teachers.Net chatboard taxonomy into future Core Terms
candidates.

Membership Taxonomy is a curation, classification, and human-review workstream.
It is not the Core Terms implementation platform, a Core Terms rename, or a Core
Terms implementation ticket stream.

Core Terms is the plugin/platform/runtime/API/editor/compiler/archive system.
Membership Taxonomy is the human review layer that decides which legacy taxonomy
ideas are meaningful candidates for future Core Terms.

## Current Phase

Legacy taxonomy discovery and classification.

## Current Reference Page/Flow

Historic Teachers.Net chatboard taxonomy lists and any source material gathered
for CT068.

## Current Primitive/Task

CT068 Legacy Chatboard Taxonomy Classification Audit.

## Next Decision

Classify legacy chatboard taxonomy candidates and decide which items require
human review before they can become future Core Terms candidates.

## Ticket Convention

Use CT### unless explicitly changed.

## Core Decision

Human review is required before any taxonomy enters Core Terms.

## Guiding Test

Does this represent something teachers meaningfully belong to?

## Classification Categories

- Core Membership Term
- Chatboard-only topic
- Activity/Event
- Interest/Hobby
- Legacy-only
- Needs Human Review

## Relationship To Jobs

Membership Taxonomy is a parallel workstream. It does not block Jobs. It may
improve future Jobs seed dataset generation by producing better audience and
classification vocabulary later.

## Open Risks

- Do not import Job Center or Core Terms implementation state as a substitute for
  Membership Taxonomy curation decisions.
- Do not treat curation output as approved Core Terms input without human
  review.
- Do not rename existing Core Terms internals as part of this workstream.

## Stop After

Stop after classification and audit reporting. Do not write code, import data,
mutate Core Terms, change Jobs, or rename plugins.
