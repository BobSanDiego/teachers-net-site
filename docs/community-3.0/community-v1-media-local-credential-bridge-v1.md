# C3 local media signer bridge and native acceptance — 2026-09-14

`C3-V1-MEDIA-APPLICATION-INTEGRATION001` completed the approved local QA
signer path and native browser acceptance on the registered
`ai-in-education` Community route. This record supplements the AWS media
operations and runtime-proof authorities; it does not change the production
credential architecture.

## Local-only credential boundary

Development DDEV uses `C3_MEDIA_RUNTIME_MODE=local`,
`C3_MEDIA_CREDENTIAL_PROVIDER=unix_socket`, and `IS_DDEV_PROJECT=true`. The
web container receives temporary credential-process output through the
host-only Unix socket `/run/tnet-c3-media/credentials.sock`, backed by the
approved `/home/bobreap/bin/tnet-c3-media-iac-credentials` helper. The relay
uses an ephemeral socket, mode 0600, peer-UID checking, bounded response and
timeout, and no credential logging or persistence. PHP exchanges the
temporary source identity for the existing
`TNetC3MediaApplicationSigner` role in memory for the request only.

Production remains IMDSv2-only and uses the `tnet-c3-media-app-*` session
prefix. Local sessions use `tnet-c3-media-local-*`. The signer role and its
quarantine-only SSE-S3 `s3:PutObject` policy/boundary are unchanged; the only
AWS authorization change for this objective is the exact local operator trust
addition. No static AWS credentials are in Git, DDEV configuration, the
browser, or application persistence.

## Native browser evidence

Using the canonical Windows Chrome/CDP QA fixture and the authenticated local
QA identity on the seeded `ai-in-education` route:

- Positive two-image journey passed. Distinct media IDs
  `media-61e0b7f55feb44e6b26a4e2512bce8a6` and
  `media-d3acae146b6a48ebb01835b5d7e8ff95` presigned successfully, uploaded
  directly to quarantine, reached READY, published in order, rendered through
  `media.teachers.net`, and retained order after reload.
- During processing, Post was disabled. After both assets were READY, Post
  enabled and the persisted attachment payload contained both IDs in order.
- Negative sibling journey passed. Valid sibling
  `media-7fc607fa5e054d3da6d9135377a3ac6c` remained available while malformed
  `media-7014633eee7a47c190d89ad3e965a681` entered failed state with governed
  `processing_timeout`; Post stayed disabled until the failed card was removed.
  The valid sibling then published and remained after reload.
- Canonical native URLs were
  `/community/ai-in-education/c3-native-two-image-media-acceptance-proof/` and
  `/community/ai-in-education/c3-invalid-sibling-removed-valid-sibling-retained/`.
  The direct upload sequence returned presign 201, S3 POST 201, and status
  200 for successful assets.

The default `/community/new/` route remains a bounded local fixture issue:
its `community:local-demo` identifier is absent from the registered local
community store. No product fixture or registry row was created; the
supported seeded route above was used for acceptance.

## Acceptance state and boundary

Local operator → signer, direct quarantine upload, multi-image readiness,
READY-only publication, ordering persistence, `media.teachers.net` rendering,
and invalid-sibling removal/recovery are `PROVEN_NATIVE`. AWS processor,
CloudFront, IaC, and Sandy production seams remain carried-forward
`PROVEN_NATIVE` and were not retested or reopened. The next work may address
application follow-ons only under a separately authorized objective.
