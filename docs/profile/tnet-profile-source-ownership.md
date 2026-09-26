# tnet-profile source ownership

## Canonical owner

`teachers-net-site` is the canonical Git owner of the complete first-party
`wordpress/wp-content/plugins/tnet-profile/` runtime boundary. The initial
boundary was recovered under `PROFILE-SOURCE-OWNER-RECOVERY002`; cycle
`260926095358` (`PROFILE-V1-SOURCE-RECOVERY029`) supersedes that partial
inventory with the complete accepted Profile V1/enrichment source. Recovery
used the surviving Profile branch, accepted 023-027 Report/Hopper hashes, and
the active DDEV copy as evidence. DDEV remains the runtime, not a second source
owner.

The canonical isolated worktree is
`/home/bobreap/projects/teachers-net-profile-recovery029` on branch
`codex/profile-v1-basics-facts`. The terminal Workflow V2 report for cycle
`260926095358` records the immutable consolidation commit and complete hash
matrix.

## Tracked runtime boundary

The narrow, complete tracked boundary contains 284 files:

- `tnet-profile.php` — plugin bootstrap, resolver integration, and accepted
  Profile route/service registration;
- `includes/class-tnet-profile-member-context.php` — the accepted Profile V1
  member-fact write/query service;
- `includes/class-tnet-profile-basics.php` and
  `includes/class-tnet-profile-enrichment.php` — the accepted Basics, Roles,
  Complete, shared-card, persistence, and projection owners;
- `includes/class-tnet-profile-public.php` — the current temporary
  authenticated `/profile/` and `/profile/edit/` route owner retained as the
  pre-permanent baseline;
- `includes/class-tnet-profile-avatar-component-set.php` — retained only so
  the accepted bootstrap and diagnostic routes remain loadable. It is a
  rejected, review-only component control and is not an avatar-resolver input;
- `public/css/tnet-profile-basics.css`,
  `public/css/tnet-profile-enrichment.css`,
  `public/js/tnet-profile-basics.js`,
  `public/js/tnet-profile-enrichment.js`, and
  `public/js/tnet-profile-avatar-editor.js` — the accepted Profile presentation
  and interaction owners;
- `public/assets/enrichment-launch/` — the three accepted destination images
  and final Director-approved celebration asset; and
- `assets/portrait-bank-v1/manifest.json` plus
  `assets/portrait-bank-v1/assets/**` — the frozen first-party portrait delivery
  boundary.

The frozen manifest has 268 retained entries and exactly 268 PNG masters. The
269 active files in the portrait-bank directory are those 268 masters plus the
manifest; there is no extra portrait, support image, or runtime residue.
Manifest SHA-256 values match every retained master.

## Explicit exclusions

The following are not Profile production source and remain excluded:

- WordPress uploads and any member-uploaded photos;
- caches, logs, reports, Hopper payloads, temporary browser/QA state, and
  generated review labs;
- `tests/avatar-component-set-runtime.php`, a local QA fixture not needed by
  plugin runtime; and
- removed/superseded portrait-review populations and historical artwork that
  is not in the frozen manifest.

No user-private material, credentials, environment-specific paths, or secrets
belongs in this source boundary. The portrait masters are first-party frozen
assets whose hashes/provenance are recorded by the manifest; their opaque
retrieval identifiers are artwork metadata, not member demographics.

## Reproducibility and next boundary

The bootstrap, accepted Profile V1/enrichment routes, resolver delivery, frozen
bank, and member-fact service are versioned together so the Director-PASS
baseline can be reproduced without adopting the mutable DDEV tree. The current
source is byte-identical to the intended DDEV Profile plugin across all 284
governed files; the excluded diagnostic test is the sole DDEV-only file.

Permanent Profile implementation begins only under a later ticket. The settled
route/visibility contract is `/profile/` for authenticated self entry and
`/profile/<username>/` for public/other-member viewing by permanent
`user_login`; owner-only editing and existing `location_public` and aggregate
`profile_details` visibility remain authoritative. Email, authored activity,
Community relationships, lessons, and Jobs relationships are not Profile V1
content. This recovery implements none of those future surface decisions.
