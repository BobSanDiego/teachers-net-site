# tnet-profile source ownership

## Canonical owner

`teachers-net-site` is the canonical Git owner of the complete first-party
`wordpress/wp-content/plugins/tnet-profile/` runtime boundary. This boundary
was recovered under `PROFILE-SOURCE-OWNER-RECOVERY002` from accepted Profile
contracts and evidence, then byte-checked against the active local DDEV
runtime. It is not a transfer of ownership to the WordPress runtime directory.

The immutable recovery publication commit is recorded in this document's
terminal provenance update and in the corresponding Workflow V2 Report.

## Tracked runtime boundary

The narrow, complete tracked boundary is:

- `tnet-profile.php` — plugin bootstrap, resolver integration, and accepted
  Profile route/service registration;
- `includes/class-tnet-profile-member-context.php` — the accepted Profile V1
  member-fact write/query service;
- `includes/class-tnet-profile-avatar-component-set.php` — retained only so
  the accepted bootstrap and diagnostic routes remain loadable. It is a
  rejected, review-only component control and is not an avatar-resolver input;
- `assets/portrait-bank-v1/manifest.json` plus
  `assets/portrait-bank-v1/assets/**` — the frozen first-party portrait delivery
  boundary; and
- `docs/profile/member-fact-contract-v1.md` and
  `docs/profile/member-context-screen6-contract.md` — the accepted contracts
  that constrain this source.

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

The bootstrap, resolver delivery, frozen bank, and member-fact service are
versioned together so accepted Screens 3–6 can be reproduced without adopting
the mutable DDEV tree. `PROFILE-V1-BASICS-FACTS001` remains the next Profile
implementation objective and may resume unchanged after this recovery is
published. This recovery makes no product, schema, resolver, portrait, or
onboarding behavior change.
