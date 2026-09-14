# C3-V1-MEDIA-APPLICATION-INTEGRATION001

## Cycle

- Cycle: `260914140800`
- Status: `COMPLETE`
- Mode: `STANDARD`
- Objective owner: C3 media application integration
- Community source: `/home/bobreap/projects/teachers-net-community3`
- Implementation commit: `1a790ff`
- Push: `origin/COMMUNITY3-ui-working` succeeded

## Outcome

The approved local QA operator-to-signer path was established and the native
multi-image acceptance objective passed. The existing Sandy production path,
signer permissions/boundary, AWS processor, CloudFront, DNS, and OpenTofu
runtime/delivery seams were carried forward without retest or reopening.

## IAM and local provider evidence

- The existing MFA-backed `TNetC3MediaIaCOperator` identity was verified by
  the Director-provided caller identity. Its exact runtime and attached
  boundary allowance to `TNetC3MediaApplicationSigner` was carried forward.
- OpenTofu targeted plan/apply changed only the existing signer trust policy:
  local principal `TNetC3MediaIaCOperator`, exact `aws:PrincipalArn`, and
  `tnet-c3-media-local-*` session names. The existing Sandy trust remained.
- Local DDEV uses the explicit local Unix-socket provider. The host relay
  invokes only the approved temporary helper, bounds response/timeout, checks
  peer UID, and keeps credentials in memory. The relay was stopped after QA
  and its socket is absent.
- Production remains IMDSv2-only. The signer remains quarantine-PutObject-only
  with the required SSE-S3 condition. No static credential was introduced or
  persisted.

## Native browser acceptance

Canonical Windows Chrome/CDP control and the authenticated local QA identity
were used on registered route `ai-in-education`.

### Positive two-image journey — PROVEN_NATIVE

- Media IDs `media-61e0b7f55feb44e6b26a4e2512bce8a6` and
  `media-d3acae146b6a48ebb01835b5d7e8ff95` were distinct.
- Both assets used presign 201, direct S3 quarantine POST 201, and status 200
  polling. Uploads did not traverse the WordPress upload-body path.
- Post was disabled while either attachment was processing and enabled only
  after both assets reached READY.
- The post was published with both attachments in order and rendered through
  `media.teachers.net`; reload preserved the ordered attachment set and alt
  text.
- Native URL:
  `/community/ai-in-education/c3-native-two-image-media-acceptance-proof/`.

### Negative sibling journey — PROVEN_NATIVE

- Valid sibling `media-7fc607fa5e054d3da6d9135377a3ac6c` remained available.
- Malformed sibling `media-7014633eee7a47c190d89ad3e965a681` entered governed
  failed state with `processing_timeout`; Post remained disabled while it was
  retained and offered retry/remove.
- Removing the failed sibling preserved the valid sibling, enabled
  publication, and the valid-only post persisted after reload.
- Native URL:
  `/community/ai-in-education/c3-invalid-sibling-removed-valid-sibling-retained/`.
- The invalid registry/quarantine state remained recoverable; no direct AWS
  cleanup or unrelated fixture was performed.

## Bounded environment note

The default `/community/new/` route references `community:local-demo`, which
is absent from the registered local Community store. This was recorded as a
fixture/data-boundary issue; no registry row or product fixture was created.
Acceptance used the governed seeded `ai-in-education` route instead.

## Git and durability

The objective changes and continuity records were committed as `1a790ff` and
pushed successfully. Only these nine source files were staged; unrelated
pre-existing worktree changes were preserved and not staged. DDEV PHP lint and
targeted `git diff --check` passed. The durable local-provider/native-
acceptance authority is `docs/community-3.0/community-v1-media-local-
credential-bridge-v1.md`; Cursor, Engineering Handoff, roadmap, and this
Workflow V2 record are updated.

## Terminal state

`C3-V1-MEDIA-APPLICATION-INTEGRATION001` is `COMPLETE`. No further work is
authorized by this cycle. Any subsequent registry, upload, composer, or
production work requires a separately authorized objective.
