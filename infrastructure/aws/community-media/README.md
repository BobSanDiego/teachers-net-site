# C3 media infrastructure authority

This directory is the canonical future OpenTofu/Terraform home for Community
media infrastructure. The current AWS anchors were manually bootstrapped and
are represented by the import/reconciliation authority in
`docs/community-3.0/community-v1-media-operations-authority-v1.md`.

Before any apply, declare the existing resources, import them into the
approved IaC state bucket, compare the plan with the accepted native runtime
record, and obtain review for every drift. Do not recreate resources merely
because state is not yet imported. Do not add CloudFront, DNS, billing
controls, optional telemetry, or other usage-priced features without their
separate approved pricing record.

The Lambda processor implementation and deterministic tests remain under
`processor/`; this directory does not authorize changes to that runtime.

## Current import map

The following resources are existing anchors, not create targets:

| IaC object | Import identity | Reconciliation state |
|---|---|---|
| C3 media S3 bucket and subresources | `tnet-c3-media-553830187994-us-west-2` | imported; semantically equivalent |
| IaC state bucket | `tnet-c3-media-state-553830187994-us-west-2` | separate versioned backend anchor; not managed by this stack |
| SQS processing queue | `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing` | imported |
| SQS DLQ | `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing-dlq` | imported |
| Lambda function | `arn:aws:lambda:us-west-2:553830187994:function:tnet-c3-media-processor` | imported by canonical function name |
| Lambda event source mapping | `6219b5b3-b709-485b-880e-a186fc5c9618` | imported; empty metrics block provider-only residual |
| ECR repository | `tnet-c3-media-processor` | imported |
| Processor role/policy/boundary | `TNetC3MediaProcessor`, `TNetC3MediaProcessorRuntime`, `TNetC3MediaProcessorBoundary` | imported |
| Processor log group | `/aws/lambda/tnet-c3-media-processor` | imported |
| CloudFront distribution | `EXVHOH58DVJUJ` / `arn:aws:cloudfront::553830187994:distribution/EXVHOH58DVJUJ` | imported; no-change |
| CloudFront OAC | `E3GATUZLP66CJT` | imported; no-change |
| CloudFront ready-only bucket policy | distribution `EXVHOH58DVJUJ`, resource `ready/*` | imported; no-change |
| Route 53 media hostname | `media.teachers.net` → `dqmsvj26oip2t.cloudfront.net` | imported; no-change |

The CloudFront cache/delivery contract is GET/HEAD, HTTPS redirect,
compression, managed `CachingOptimized`, `PriceClass_100`, IPv6 enabled, no
WAF, edge compute, Origin Shield, real-time logs, or invalidation authority.
The exact anchor configuration and bucket statement are recorded in
`docs/community-3.0/community-v1-media-cloudfront-iam-and-delivery-design-v1.md`.

## Reconciliation result — 2026-09-12

Cycle `260912194154` imported every declared C3 anchor into the existing
versioned backend `tnet-c3-media-state-553830187994-us-west-2` without apply.
`tofu validate` passed. The final no-apply plan is `0 to add, 1 to change,
0 to destroy`; the sole change removes an imported empty event-source
`metrics_config` block. AWS provider schema requires at least one metric, so
the empty default cannot be represented in configuration. This is recorded as
provider-only representation drift; it is not applied and no runtime behavior
is changed. All other resources are no-change.
