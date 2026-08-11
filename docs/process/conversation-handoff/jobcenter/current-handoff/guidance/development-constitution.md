# Teachers.Net Jobs Development Constitution

Version: 1.0
Scope: Teachers.Net Jobs plugin development

## Purpose

Build the Teachers.Net Jobs plugin through small, controlled, verifiable increments.

Prefer stable architecture, clear lifecycle rules, and low-risk implementation over cleverness or broad feature jumps.

## Core Rules

* Implement only the requested ticket.
* Do not add adjacent features unless explicitly requested.
* Preserve existing behavior unless the ticket says to change it.
* Prefer many small releases over large mixed changes.
* Keep V1 focused on usable job posting, discovery, employer management, and safe application instructions.

## Architecture

* Business rules belong in service classes.
* Repositories handle query and persistence work.
* Public and admin classes handle routing, rendering, forms, and action wiring.
* Do not duplicate business rules across public/admin layers.
* Reuse existing service/repository methods where practical.
* Avoid parallel workflows that bypass established lifecycle logic.

## Database

* Avoid schema changes unless clearly justified by the ticket.
* Schema changes must be narrow, named, versioned, and verified.
* Never modify Core Terms unless explicitly requested.
* Soft-delete/archive rather than hard-delete whenever practical.
* Do not store applicant resumes, cover letters, or private correspondence in Jobs V1.

## Core Terms

* Core Terms are read-only for the Jobs plugin unless explicitly stated otherwise.
* Jobs must tolerate normal Core Terms maintenance, including edited, moved, archived, newly created, and recompiled terms.
* Do not assume a fixed Core Terms count.
* Classification synchronization should preserve administrator-defined hierarchy and sibling order.
* Every ticket that touches classifications should confirm active Jobs selectors still populate from Core Terms.

## Job Lifecycle

Preserve the established lifecycle unless explicitly changed:

* draft
* submitted
* published
* closed
* expired
* archived

Default assumptions:

* Draft jobs are not public.
* Submitted jobs are not public.
* Published live jobs are public.
* Closed jobs are not listed publicly but may have detail access where already supported.
* Expired jobs are not listed publicly but may have detail access where already supported.
* Archived jobs are unavailable and excluded from normal user-facing flows.

## Moderation and Trust

* Preserve the existing moderation queue unless a ticket explicitly changes it.
* Preserve the existing employer trust rule unless a ticket explicitly changes it.
* Trusted employer behavior must not be expanded accidentally.
* Untrusted employer changes that return jobs to submitted should appear in moderation through the normal path.

## Security

Every user action must enforce:

* authentication where required
* ownership where required
* capability checks where required
* nonce validation for mutating actions
* server-side rejection of unauthorized actions

Never rely only on hidden buttons or UI conditions.

## Application Flow

Jobs V1 does not provide applicant messaging.

Jobs V1 does not:

* forward email
* upload resumes
* upload cover letters
* store applications
* provide an inbox
* provide an ATS

Jobs V1 may show protected Application Instructions to logged-in users for live published jobs.

Application Instructions may contain:

* email address
* external URL
* free-text directions

## Engagement Metrics

Engagement metrics are user signals, not applications.

Established meanings:

* Views = logged-in users who viewed the job.
* Saved = logged-in users who saved the job.
* Interested = logged-in users who saved the job or revealed Application Instructions.

Each user/job pair should count once for each relevant metric.

## Verification

Each ticket should normally verify:

* requested behavior works
* forbidden behavior is rejected server-side
* public listing eligibility remains correct
* moderation behavior remains correct if relevant
* trust behavior remains correct if relevant
* Core Terms integration remains healthy when relevant
* PHP lint clean
* git diff --check clean
* temporary rows/users cleaned back to baseline
* Jobs repo clean
* parent repo clean

## Release Procedure

Each completed ticket should:

* commit with a clear message
* tag with the requested version
* push main
* push tag
* report changed files
* report verification results
* report commit hash
* report tag
* report final repo status

## Prompt Discipline

Future Codex prompts should be short.

Assume this constitution applies unless the prompt explicitly overrides it.

A normal ticket should include only:

* ticket number
* ticket title
* requested behavior
* special constraints
* verification focus
* tag name

## Existing Capability

Before implementing a ticket:

* Determine whether the requested capability already exists.
* If it exists and satisfies the ticket, do not reimplement it.
* Verify the existing implementation.
* Report that the ticket is already satisfied.
* Do not generate duplicate code solely to satisfy a ticket number.
