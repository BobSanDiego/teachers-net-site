# Community V1 Native Media Runtime Proof

Status: `PROVEN_NATIVE`, recorded 2026-09-12

This document records the accepted native AWS runtime seam for
`C3-V1-MEDIA-RUNTIME-PROOF001` / cycle `260912013811`. It is infrastructure
evidence and reconciliation guidance, not production cutover authority and
not a substitute for the later C3 media-operations release gate.

## Accepted AWS state

- Account: `553830187994`; region: `us-west-2`
- Media bucket: `tnet-c3-media-553830187994-us-west-2`
- Lambda: `tnet-c3-media-processor`, arm64 container image, Active
- Lambda ARN:
  `arn:aws:lambda:us-west-2:553830187994:function:tnet-c3-media-processor`
- Memory: `2048 MB`; timeout: `60 seconds`; ephemeral storage: `512 MB`
- Deployment image:
  `553830187994.dkr.ecr.us-west-2.amazonaws.com/tnet-c3-media-processor@sha256:390a38fa43578de7c0ec6ca24450299dc9c30907b39ac876fc5fd9639d66ec76`
- Processing queue:
  `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing`
- DLQ:
  `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing-dlq`
- Event-source mapping UUID:
  `6219b5b3-b709-485b-880e-a186fc5c9618`
- Mapping: enabled; batch size `1`; batching window `0`;
  `ReportBatchItemFailures` enabled
- Queue visibility timeout: `90 seconds`; maximum receives before DLQ: `3`
- Log group: `/aws/lambda/tnet-c3-media-processor`; seven-day retention
- S3 notification: `c3-media-quarantine-processing`, ObjectCreated events
  under `quarantine/` targeting `tnet-c3-media-processing`
- SQS policy permits `s3.amazonaws.com` `SendMessage` only from the governed
  bucket in account `553830187994`

Both `TNetC3MediaProcessorRuntime` and `TNetC3MediaProcessorBoundary` include:

```json
{
  "Sid": "ReadReadyObjectsForPostWriteVerification",
  "Effect": "Allow",
  "Action": "s3:GetObject",
  "Resource": "arn:aws:s3:::tnet-c3-media-553830187994-us-west-2/ready/*"
}
```

## Positive native evidence

Fresh controlled key: `quarantine/runtime-proof-260912-runtime4/original.png`.

- Input version `B0HlaK9imCZHKIsyht6VrdtZiEoysC_f`; PNG; `1,636,841` bytes;
  SSE-S3.
- Exact source `HeadObject` returned 404 after processing; scoped listing was
  empty.
- Exactly four ready objects persisted:
  - `master.jpg`: JPEG, `262,142` bytes, `1536x1024`
  - `1440.webp`: WebP, `126,420` bytes, `1440x960`
  - `960.webp`: WebP, `70,430` bytes, `960x640`
  - `480.webp`: WebP, `23,060` bytes, `480x320`
- All outputs were SSE-S3 encrypted with empty user metadata.
- Independent byte inspection found no JPEG EXIF/ICC/IPTC/comment markers;
  each WebP contained only a VP8 chunk and no EXIF, XMP or ICC chunk.
- Lambda emitted bounded `image_released` evidence with `output_count:4`.
- Execution report: duration `1483.68 ms`, billed duration `2431 ms`, maximum
  memory `164 MB`, init duration `947.31 ms`.
- Processing queue returned to zero visible, not-visible and delayed messages;
  DLQ remained at its pre-run count of `2`.

## Governed invalid-input evidence

Controlled key: `quarantine/runtime-proof-260912-invalid/original.png`.

- Input version `YXx83GCKA55nWUesC0P3IBy2SVb6ByvY`; zero bytes declared
  `image/png`; SSE-S3.
- No ready output appeared; the source remained present.
- Lambda emitted bounded `image_rejected` / `encoded_bytes_exceeded` evidence
  on three receives. Durations were `74.37 ms`, `46.50 ms`, and `48.56 ms`;
  maximum memory used was `164 MB`.
- Processing queue returned to zero messages.
- DLQ increased from `2` to `3` after the configured maximum receive count of
  `3`. The invalid source was not deleted or released.

## Reconciliation boundary

The runtime path is proven through S3 quarantine, SQS delivery, Lambda
processing, ready persistence, source deletion ordering, bounded logging,
retry and DLQ handling. Browser upload authorization, media registry
integration, CloudFront delivery, application rendering, historical migration
and production cutover remain open under
`COMMUNITY3-V1-MEDIA-OPERATIONS-FOUNDATION001` and later release gates.

The manually created AWS anchors must be imported or reconciled by future IaC
work; they must not be recreated merely because this proof record is
documentation-only.
