# Community V1 Media Operations Authority v1

Status: `PARTIAL / IAC_IMPORT_RECONCILIATION_PENDING`, recorded 2026-09-12.

This is the durable Community authority for the manually bootstrapped C3 media
infrastructure, import-first IaC reconciliation, cost-safety controls, the
media-registry contract, and the future cost review. It supersedes the
resource-not-created assumptions in the earlier diagnostic
`community-v1-media-architecture-and-aws-cost-diagnostic-v1.md`. It does not
authorize application upload integration, production cutover, or mutation of
the proven runtime.

## Authority and proven runtime

The native authority is `C3-V1-MEDIA-RUNTIME-PROOF001`, cycle `260912013811`.
Carry its accepted S3, SQS, Lambda, ECR, IAM, lifecycle, retry/DLQ, output,
metadata, deletion-order, and bounded-log evidence forward as
`PROVEN_NATIVE`. The approved deployment image is the immutable digest
`sha256:390a38fa43578de7c0ec6ca24450299dc9c30907b39ac876fc5fd9639d66ec76`.

The AWS account is `553830187994`, region `us-west-2`, and the governed media
bucket is `tnet-c3-media-553830187994-us-west-2`. No proven processing seam is
retested or changed by this foundation record.

## Canonical IaC home and import-first rule

The canonical future IaC home is the Community source repository directory
`infrastructure/aws/community-media/`, using the repository's approved
OpenTofu/Terraform workflow. The existing `processor/` directory remains the
container source and test authority. No executable resource definitions have
been applied. The manually bootstrapped runtime anchors and the subsequently
created CloudFront delivery anchors remain import-first; native proof is
recorded separately from future IaC state ownership.

Future IaC must declare the existing resources, import them into state, run a
plan, and reconcile only after review. It must not create a parallel bucket,
queue, function, role, log group, ECR repository, or notification.

| Logical IaC object | Existing anchor / import identity | State |
|---|---|---|
| media bucket and S3 subresources | `tnet-c3-media-553830187994-us-west-2`; bucket subresources use that bucket name | proven; import/reconcile pending |
| IaC state bucket | private, versioned manually bootstrapped state bucket; exact name is not present in the current approved runtime record | exact-name inventory pending human infrastructure read |
| processing queue | `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing` | proven; import/reconcile pending |
| processing DLQ | `arn:aws:sqs:us-west-2:553830187994:tnet-c3-media-processing-dlq` | proven; import/reconcile pending |
| Lambda function | `arn:aws:lambda:us-west-2:553830187994:function:tnet-c3-media-processor` | proven; import/reconcile pending |
| event-source mapping | UUID `6219b5b3-b709-485b-880e-a186fc5c9618` | enabled and proven; import/reconcile pending |
| ECR repository | `tnet-c3-media-processor` | immutable image proven; import/reconcile pending |
| processor runtime role/policy/boundary | `TNetC3MediaProcessor`, `TNetC3MediaProcessorRuntime`, `TNetC3MediaProcessorBoundary` | bounded and proven; import/reconcile pending |
| processor log group | `/aws/lambda/tnet-c3-media-processor` | seven-day retention proven; import/reconcile pending |
| CloudFront distribution | `EXVHOH58DVJUJ`; ARN `arn:aws:cloudfront::553830187994:distribution/EXVHOH58DVJUJ`; domain `dqmsvj26oip2t.cloudfront.net`; alternate `media.teachers.net` | native delivery proven; IaC import pending |
| CloudFront OAC | `E3GATUZLP66CJT` (`tnet-c3-media-ready-oac`); S3 SigV4, signing always | native origin authority proven; IaC import pending |
| CloudFront ready-origin bucket policy | `s3:GetObject` on `ready/*`, conditioned to distribution ARN `EXVHOH58DVJUJ` | human-applied and native delivery proven; IaC import/reconcile pending |
| Route 53 `media.teachers.net` record | human-managed record targets `dqmsvj26oip2t.cloudfront.net`; hosted-zone/record import identity not supplied | native canonical-host proof; DNS IaC ownership pending separate review |

The existing Lambda contract remains: arm64 image, 2048 MB memory, 60-second
timeout, 512 MB ephemeral storage, reserved concurrency 2, SQS batch size 1,
zero batching window, partial batch failures, queue visibility 90 seconds,
maximum receives 3, and `C3_MEDIA_BUCKET` bound to the governed bucket.

## Operations and cost safety

The approved cost contract is fail-closed: no recurring- or usage-priced AWS
service or feature is enabled without its pricing basis, low/expected/high
exposure, and Engineering Director approval being recorded first.

Already bounded controls are S3 versioning with approximately 30-day
noncurrent/deletion recovery, transient quarantine handling, SSE-S3, Lambda
reserved concurrency 2, and seven-day retention for the required Lambda log
group. The runtime proof measured 1,483.68 ms duration, 2,431 ms billed
duration, and 164 MB maximum memory on the 1,536x1,024 fixture; this does not
authorize reducing the 2,048 MB allocation.

This foundation enables no optional telemetry or paid add-on: no custom or
high-resolution CloudWatch metrics/alarms, anomaly alarms, Container
Insights, Application Signals, X-Ray, Lambda Insights, verbose or indefinite
logs, NAT, replication, customer-managed KMS, WAF, paid analytics, or
CloudFront edge compute. Basic Lambda logging remains the minimum structured
application logs already proven, with seven-day retention.

AWS Budgets, Cost Anomaly Detection, and Free Tier alerts are account/billing
administration controls, not CloudWatch monitoring and not runtime proof. They
were not mutated here because the approved operator lacks billing authority.
The Engineering Director remains the owner of their configuration and must
verify their current account state before any usage-bearing expansion.

## CloudFront gate

The Director selected `media.teachers.net` as the canonical public media
hostname. The approved two-phase CloudFront bootstrap created exactly one C3
distribution and one OAC. Human completion then proved the canonical-host
delivery path and the exact private-bucket policy boundary; no legacy
distribution was repurposed.

The least-privilege design is recorded in
`community-v1-media-cloudfront-iam-and-delivery-design-v1.md`. It uses a
two-phase CloudFront operator: account-level list/create permissions only for
the reviewed one-time bootstrap, followed by exact distribution/OAC ARNs after
IDs are recorded. The design explicitly excludes Route 53, ACM, S3, IAM,
Lambda, SQS, ECR, billing, WAF, edge compute, real-time logs, Origin Shield,
and invalidations.

CloudFront custom aliases require the trusted `ISSUED` certificate covering
`media.teachers.net` in `us-east-1`; that prerequisite was human-confirmed.
Route 53 now targets `dqmsvj26oip2t.cloudfront.net`, and the human-reviewed
bucket statement is applied only to `ready/*` for the exact distribution ARN.
The canonical-host proof returned HTTP 200, `image/jpeg`, 262142 bytes, and
`X-Cache: Hit from cloudfront` for
`runtime-proof-260912-runtime4/master.jpg`.

## Media-registry contract before application integration

The future C3 registry owns one immutable asset identity and processing
provenance; S3 object keys and URLs are storage/delivery references, not the
sole identity. Each asset record must retain:

- asset/media ID, association owner, lifecycle state, and immutable source
  object version/checksum;
- source encoded byte count, width, height, format, MIME type, and accepted
  timestamp;
- per retained variant (`master.jpg`, `1440.webp`, `960.webp`, `480.webp`):
  object version/checksum, byte count, width, height, format/MIME type, and
  publication timestamp;
- processor identity and immutable image digest/source commit, validation
  policy version, processing start/completion timestamps, and bounded duration;
- quarantine, retry/failure, release, expiry, deletion, and reconciliation
  evidence sufficient to prove source deletion occurred only after the full
  output set verified.

The contract records retained-byte totals for economic accounting but does not
add behavioral tracking, paid telemetry, per-view counters, or presigned URLs
to the registry. V1 ordinary imagery is public only after application-owned
authorization and registry association; raw quarantine remains private and
transient.

## Future media cost/refinement review

Register the next review after real operation accumulates representative
storage and delivery history. It must use actual retained bytes, quarantine
expiry behavior, request/delivery volume, and variant utilization to assess
whether derivative retention can be refined; evaluate Intelligent-Tiering and
lifecycle changes only with a new pricing review; and benchmark Lambda memory
tiers using representative large inputs including the governed upper image
boundary. Compare total GB-seconds per image, error/retry rate, and output
latency—not memory allocation alone. The current 2,048 MB setting remains
unchanged until that evidence and Director approval exist.

## Current acceptance ledger

| Seam | State | Evidence / next boundary |
|---|---|---|
| Storage, quarantine, versioning/lifecycle | `PROVEN_NATIVE` | Runtime-proof cycle; import/reconcile only |
| S3 notification → SQS → Lambda → ready | `PROVEN_NATIVE` | Runtime-proof cycle; no retest here |
| Four outputs, metadata stripping, no-upscale, deletion ordering | `PROVEN_NATIVE` | Runtime-proof cycle |
| Invalid-input retry/source retention/DLQ | `PROVEN_NATIVE` | Runtime-proof cycle |
| Bounded logging and current Lambda sizing | `PROVEN_NATIVE` | Runtime-proof cycle; 2048 MB unchanged |
| IaC import/reconciliation authority | `BOUNDED / PENDING_CONTROLLED_STEP` | Canonical home and exact runtime/CloudFront import map recorded; import, plan, drift review, and any apply remain a later controlled step |
| Account cost controls | `BOUNDED / HUMAN_OWNER` | No optional paid telemetry; Director must verify billing alerts |
| CloudFront delivery foundation | `PROVEN_NATIVE` | Distribution `EXVHOH58DVJUJ`, OAC `E3GATUZLP66CJT`, human DNS target, ready-only bucket policy, and canonical-host HTTP 200 proof |
| Registry metadata contract | `DEFINED / NOT_IMPLEMENTED` | Contract recorded; application integration is a later ticket |

This foundation does not authorize composer/upload UI, signing/upload
integration, historical migration, Community hero administration, or
production cutover.
