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

- `approved/` — normalized, controlled copies of exact Approved artifacts. Canonical filenames use the permanent ID, screen/component, viewport where applicable, and approved version. The library contains `jc-010-job-finder-state-1-desktop-v1.1.png`, `jc-010-job-finder-state-1-tablet-v1.0.png`, `jc-010-job-finder-state-1-mobile-v1.0.png`, `jc-011-job-finder-state-2-desktop-v1.0.png`, `jc-011-job-finder-state-2-tablet-v1.0.png`, `jc-011-job-finder-state-2-mobile-v1.0.png`, `jc-014-location-selection-modal-desktop-v1.0.png`, `jc-014-location-selection-modal-tablet-v1.0.png`, `jc-014-location-selection-modal-mobile-v1.0.png`, `jc-015-browse-reveal-desktop-v1.0.png`, `jc-030-job-detail-desktop-v1.0.png`, `jc-003-mobile-navigation-drawer-logged-out-v1.0.png`, and `jc-004-mobile-navigation-drawer-logged-in-v1.0.png`.
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

## Patch Mode

JC-010 Mobile v1.0 is in **Patch Mode**. Future work may address only an
explicitly named mobile defect or delta against its exact Approved raster. It
does not reopen desktop JC-010 v1.1, alter JC-011, JC-014, or JC-030, establish
tablet authority, or authorize responsive implementation. Any broader redesign
or implementation requires a separate approved ticket.

JC-010 Tablet v1.0 is in **Patch Mode**. Future work may address only an
explicitly named portrait-tablet defect or delta against the exact Approved
03d raster. It does not reopen desktop JC-010 v1.1, alter JC-010 Mobile v1.0,
alter JC-011, JC-014, JC-015, or JC-030, establish another responsive variant,
or authorize implementation. Any broader redesign or implementation requires a
separate approved ticket.

JC-011 Tablet v1.0 is in **Patch Mode**. Future work may address only an
explicitly named portrait-tablet defect or delta against the exact Approved
R003 raster. It does not reopen JC-011 desktop v1.0, alter JC-011 Mobile v1.0,
alter JC-010 authorities, establish another responsive variant, or authorize
implementation. Any broader redesign or implementation requires a separate
approved ticket.

JC-011 Mobile v1.0 is in **Patch Mode**. Future work may address only an
explicitly named mobile defect or delta against the exact Approved raster.
RESP-DEC002 governs its JC-011 Mobile-only support-content exception. It does
not reopen JC-011 desktop v1.0, alter JC-011 Tablet v1.0, alter JC-010
authorities, establish another responsive variant, or authorize implementation.
The Approved raster is the original 360 × 975 Engineering Director-edited
source; its native resolution is an accepted provenance limitation and does not
authorize replacement by an upscale, AI reconstruction, or other derivative.
Any broader redesign, derivative replacement, or implementation requires a
separate approved ticket.

JC-014 Tablet v1.0 is in **Patch Mode**. Future work may address only an
explicitly named portrait-tablet defect or delta against the exact Approved R002
raster. It does not reopen JC-014 desktop v1.0, alter JC-014 Mobile v1.0, alter
JC-010 authorities, establish another responsive variant, or authorize
implementation. Any broader redesign or implementation requires a separate
approved ticket.

JC-014 Mobile v1.0 is in **Patch Mode**. Future work may address only an
explicitly named mobile defect or delta against the exact Approved R003 overlay
raster. The authority changes only the backdrop and modal layer over the
approved JC-010 Mobile page. It does not reopen JC-014 desktop v1.0, alter
JC-014 Tablet v1.0, alter JC-010 authorities, establish another responsive
variant, or authorize implementation. Any broader redesign or implementation
requires a separate approved ticket.

JC-003 and JC-004 mobile navigation drawers are also in **Patch Mode**. They
govern only their respective drawer components across JC-010, JC-011, JC-014,
JC-015, and JC-030 unless a future screen documents an Approved exception. They
do not independently approve an underlying page, responsive page layout, tablet
layout, or implementation.

## Use rules

- Implementation tickets must cite an Approved `JC-###` entry and exact version. If none exists, the design need remains unresolved.
- Audits compare implementation only with Approved artifacts, plus the controlling written specifications. Drafts may be discussed as candidates, never as conformance targets.
- QA records the Approved ID/version, viewport, and state tested.
- One artifact may cover only part of a screen. Approval does not extend beyond the boundary stated in its entry.
- A Placeholder is a governed gap, not permission to invent a design.
- Metadata streams such as `Zone.Identifier` are not visual artifacts and are not library entries.

## Maintenance

Every artifact addition, approval, supersession, or removal requires a manifest update. IDs are permanent and must not be reused. Version changes preserve lineage in Notes. The versioning and supersession rules in Job Center Design System v1 remain controlling.
