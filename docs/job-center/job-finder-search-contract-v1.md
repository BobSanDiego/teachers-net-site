# Job Finder Search Contract v1

**Status:** Approved product/search contract

**Scope:** Public Job Finder search, filtering, sorting, remote inclusion, and
distance-sort behavior. This document is documentation authority only; it does
not authorize implementation or schema changes.

## Terminology

- **Employment Type:** Full-time, Part-time, Contract, Temporary, Substitute,
  and other values only where already canonical elsewhere.
- **Work Location:** On-site, Hybrid, or Remote.

Use **Work Location** when the field describes physical/remote work location.
Do not use “Work Arrangement” for that search concept.

## Contract

### Basic Search

Basic Job Finder search remains intentionally minimal and does not require a
remote-work control before the user searches. Its purpose is to reduce friction
and show relevant jobs immediately. Expanded search provides the explicit Work
Location control.

### Relevance and Date Sorts

Eligible remote jobs may appear with location-based jobs for both Relevance and
Date sorting. Remote jobs participate in the normal ordering and use the same
result paginator. No artificial distance is assigned to remote jobs.

A contextual **Include remote jobs** refinement may appear when eligible remote
jobs exist in the candidate set. If none exist, the refinement need not appear.

### Distance Sort

Distance sorting applies only to jobs with a physical searchable location.
Remote jobs are excluded from the result set and must not affect result order,
result count, page count, pagination, or distance calculations. The interface
must disclose this near the result controls:

> Remote jobs are excluded when sorting by distance.

The interface must provide a direct alternative:

> View remote jobs only

That action switches to **Work Location: Remote**, uses a valid non-distance
sort (normally Relevance), and removes or disables Distance while remote-only
results are active.

When changing from Relevance or Date to Distance, retain the user's prior
Include/Exclude Remote preference where practical. Returning to Relevance or
Date restores that prior preference; distance-sort exclusion is not a new
permanent preference.

At the end of a distance-sorted result set, the interface may offer Expand
search radius, View remote opportunities, or Broaden search terms or filters.
These suggestions do not automatically change the query.

### Remote-Only Search

Remote-only search belongs in expanded Job Finder through **Work Location:
Remote**. Relevance and Date remain valid; Distance is unavailable. Geographic
eligibility may still matter for licensing, credentials, employment law,
residency, or employer restrictions, but it is not physical distance.

Remote does not necessarily mean available everywhere. A later contract may
support nationwide, selected-state, or other explicit geographic eligibility.
That schema is not resolved here.

## Rationale

Relevance and Date can rank remote and location-based jobs without inventing
distance. Distance cannot meaningfully rank a locationless remote job, so
excluding remote jobs preserves truthful ordering, counts, and pagination. The
exclusion must be visible rather than silent, and a one-click remote-only
alternative preserves discoverability while Basic Search remains low-friction.

## Future Audit Requirements

The Job Finder and Job Posting Wizard audits must address, without assuming an
answer here:

- exact Employment Type and Work Location values;
- recruiter-side remote eligibility and state-restricted remote jobs;
- hybrid-location requirements;
- interaction among location, radius, Work Location, and sorting;
- persistence scope for Include Remote;
- contextual visibility of the Include remote jobs refinement;
- remote-only sort availability;
- search counts and pagination;
- compatibility with approved mockups; and
- schema authority and migration implications.

## Governance Notes

This contract supersedes provisional search language that treats Work
Arrangement as the public remote/location search field or assigns remote jobs a
synthetic distance. Historical audits and prior exploration remain preserved as
history and are not current search authority.
