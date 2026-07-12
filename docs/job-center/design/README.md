# Job Center Approved Mockup Library

## Purpose

This directory is the governed visual-reference system for the Teachers.Net Job Center. It records which artifacts may guide audits, implementation, QA, and future design work. It is not a general image archive, and creating or moving an image into this directory does not grant it authority.

The canonical registry is [manifest.md](manifest.md). Future implementation tickets and conformance audits may treat only manifest entries with `Approved` status as visual authority. Product behavior remains governed by the Canonical V1 Contract and Employer UX V1; visual and interaction rules remain governed by Job Center Design System v1.

## Authority order

When sources conflict, use this order:

1. An explicit, recorded product-owner or Engineering Director approval naming the exact artifact and scope.
2. Job Center Design System v1.
3. Employer UX V1 and the Canonical V1 Contract for product behavior.
4. The manifest's current classification and notes.
5. Draft visual references.
6. Current implementation, which is evidence rather than visual authority.

A filename, sequence number, `final` label, recency, implementation use, or directory location is never approval evidence by itself.

## Status vocabulary

- **Approved** — an exact artifact and its governed scope have durable approval evidence.
- **Draft** — a visual artifact exists but lacks durable approval evidence or remains a design candidate.
- **Placeholder** — a required screen or component has no governed visual artifact, or an asset is intentionally illustrative only.
- **Superseded** — a later identified artifact replaced this artifact as the active design candidate. Superseded does not mean the replacement is Approved.

## Directory roles

- `approved/` — controlled copies of exact Approved artifacts. JC-011 v1.0 is the first governed entry.
- `draft/` — optional controlled copies of active design candidates.
- `superseded/` — optional controlled copies retained for lineage.
- `source/` — optional editable source files and provenance records.

Historical files remain at their registered paths. Approval work copies the exact approved artifact into `approved/` without overwriting its source or lineage.

## Approval and supersession workflow

To promote an artifact to Approved, record all of the following in one governance decision and update the manifest in the same change:

1. Exact artifact path or immutable identifier.
2. Approving authority and decision date or decision record.
3. Screen, component, viewport, and state boundaries covered.
4. Version and source/provenance.
5. Any artifact or version superseded.
6. Known accessibility, responsive, or product-behavior limitations.

Copying the approved artifact into `approved/` is recommended for durability, but the manifest entry and recorded approval decision confer authority. New mockups must receive new versions and explicitly supersede earlier references; they must not silently overwrite them.

## Use rules

- Implementation tickets must cite an Approved `JC-###` entry and exact version. If none exists, the design need remains unresolved.
- Audits compare implementation only with Approved artifacts, plus the controlling written specifications. Drafts may be discussed as candidates, never as conformance targets.
- QA records the Approved ID/version, viewport, and state tested.
- One artifact may cover only part of a screen. Approval does not extend beyond the boundary stated in its entry.
- A Placeholder is a governed gap, not permission to invent a design.
- Metadata streams such as `Zone.Identifier` are not visual artifacts and are not library entries.

## Maintenance

Every artifact addition, approval, supersession, or removal requires a manifest update. IDs are permanent and must not be reused. Version changes preserve lineage in Notes. The versioning and supersession rules in Job Center Design System v1 remain controlling.
