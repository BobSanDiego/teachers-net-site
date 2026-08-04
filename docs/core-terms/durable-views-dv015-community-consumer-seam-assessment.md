# DV-015 — Community Consumer Seam Assessment

Status: Complete — read-only assessment
Date: 2026-08-04

## Result

Community is a candidate Durable Views consumer, but no implementation seam is
ready for binding. The current Community worktree contains contracts, fixtures,
and test-only publisher infrastructure rather than an owned canonical
publisher or a stable term-backed presentation consumer.

The actual legacy writer is the Sandy CGI at
`/var/www/www.teachers.net/cgi-bin/chatboard/chatboard.cgi`. Existing Community
authority records state that this source is not owned or available in the
repository and that its canonical identity/group mapping is unresolved.

## Boundary Decision

No Community binding, View creation, schema change, migration, publisher hook,
or production action is authorized by this assessment. The synthetic in-memory
publisher and test-owned shadow seams must not be treated as consumer authority.
Core Terms and Job Center remain separate systems and must not be promoted to
Community publisher authority.

## Required Prerequisite

The next Community ticket must establish source ownership/access and define a
read-only compatibility boundary around the legacy publisher before any live
hook or migration work. After that boundary exists, a follow-up ticket can
identify whether Community needs a View for a specific audience-facing
classification surface.

## Durable Views Implication

The Durable Views MVP remains certified for Job Center. Community adoption is
deferred pending the source-ownership and compatibility prerequisite; no
Durable Views platform change is required by this assessment.
