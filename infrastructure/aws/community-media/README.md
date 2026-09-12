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
| C3 media S3 bucket and subresources | `tnet-c3-media-553830187994-us-west-2` | proven; import pending |
| IaC state bucket | exact bucket name remains a separate inventory item | human inventory pending |
| SQS processing queue | `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing` | proven; import pending |
| SQS DLQ | `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing-dlq` | proven; import pending |
| Lambda function | `arn:aws:lambda:us-west-2:553830187994:function:tnet-c3-media-processor` | proven; import pending |
| Lambda event source mapping | `6219b5b3-b709-485b-880e-a186fc5c9618` | enabled/proven; import pending |
| ECR repository | `tnet-c3-media-processor` | immutable image proven; import pending |
| Processor role/policy/boundary | `TNetC3MediaProcessor`, `TNetC3MediaProcessorRuntime`, `TNetC3MediaProcessorBoundary` | bounded/proven; import pending |
| Processor log group | `/aws/lambda/tnet-c3-media-processor` | seven-day retention proven; import pending |
| CloudFront distribution | `EXVHOH58DVJUJ` / `arn:aws:cloudfront::553830187994:distribution/EXVHOH58DVJUJ` | canonical-host delivery proven; import pending |
| CloudFront OAC | `E3GATUZLP66CJT` | SigV4-ready origin proven; import pending |
| CloudFront ready-only bucket policy | distribution `EXVHOH58DVJUJ`, resource `ready/*` | human-applied/proven; reconcile pending |
| Route 53 media hostname | `media.teachers.net` → `dqmsvj26oip2t.cloudfront.net` | human-proven; hosted-zone import identity pending |

The CloudFront cache/delivery contract is GET/HEAD, HTTPS redirect,
compression, managed `CachingOptimized`, `PriceClass_100`, IPv6 enabled, no
WAF, edge compute, Origin Shield, real-time logs, or invalidation authority.
The exact anchor configuration and bucket statement are recorded in
`docs/community-3.0/community-v1-media-cloudfront-iam-and-delivery-design-v1.md`.
