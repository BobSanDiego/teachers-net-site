# Community V1 Media Architecture and AWS Cost Diagnostic v1

Status: DIAGNOSTIC / DIRECTOR DECISION REQUIRED

Ticket: `COMMUNITY3-V1-MEDIA-ARCHITECTURE-AND-AWS-COST-DIAGNOSTIC001`

Research date: 2026-09-11. Cost inputs are USD in US East (N. Virginia), except
where an AWS CloudFront flat-rate plan governs the allowance.

## Recommendation

Approve a C3-owned media registry and private S3 origin behind a CloudFront
media distribution. Ordinary image intake should be browser direct-to-S3, not
browser-to-WordPress. C3 authorizes each upload and keeps media identity
independent of object keys and URLs.

1. An authenticated C3 author requests authorization for an opaque `media:` ID.
2. C3 applies quota, rate, capability, and rights checks, then returns a short-
   lived presigned POST for a server-generated key under private `quarantine/`.
3. S3 event delivery through SQS invokes an isolated processor; WordPress does
   not stream ordinary image payloads.
4. The processor validates actual signature/MIME, decodes within pixel/resource
   limits, applies orientation, strips EXIF/GPS, creates derivatives, scans, and
   records hashes, dimensions, bytes, policy version, and release state.
5. Only passed objects become versioned `ready` objects. CloudFront Origin
   Access Control is the sole reader of the private bucket and serves a custom
   `media.teachers.net` hostname.
6. The C3 renderer resolves a media version, then emits bounded `srcset` URLs.
   Hidden, removed, or restricted posts never receive a public ready URL.

Use S3 Standard for active V1 image families. Do not use Standard-IA or Glacier
for user-visible Community images. Same-region/cross-account recovery
replication is an optional Director risk decision, not the default; it roughly
doubles storage cost. This ticket authorizes no AWS resource, billing,
credential, production, Sandy, schema, or product change.

## Current-media audit

| Seam | Classification | Evidence and production conclusion |
| --- | --- | --- |
| Composer chooser, drag/drop, nonce and auth POST | REUSABLE | Existing composer/controller UX remains useful; replace transport only. |
| Upload/staging path | REPLACE | `wp_handle_upload()` writes under `wp_upload_dir()` and records `wp-content/uploads/...`; it is local filesystem ownership, not production object storage. |
| Persistence | REPLACE | Attachment arrays are inside post `compatibility_json`; schema has no media table/version registry. Retain post association semantics, not this storage owner. |
| WordPress Media Library | MISSING by design | No `wp_insert_attachment` or attachment metadata ownership exists. Do not make it authoritative for C3 lifecycle/moderation/migration. |
| MIME, size, alt | REUSABLE but insufficient | JPEG/PNG/WebP allowlist, 10 MB and alt intent exist. Add magic-byte/decode/pixel validation, quotas and scan. Topic composer defaulting to `Community image` is not production accessibility proof. |
| Rendering | REPLACE | `TNet_Community_Attachment::render()` produces one `<img>` with no `srcset`, fixed dimensions, version, or CDN policy. |
| Failed publish cleanup | REUSABLE semantic only | Process-local `unlink()` has the right transactional intent but must become quarantined-object state cleanup and audit. |
| Deletion, retention, restore, orphan cleanup | MISSING | Required before real uploads. |
| OG/link preview | REPLACE / SECURITY GAP | `TNet_Community_Link_Preview::resolve()` performs synchronous `wp_safe_remote_get()` and may render a remote `og:image`. It conflicts with the fixture-only/no-live-fetch continuity and is not a production boundary. |
| Historical media provenance | REUSABLE | Preserve provenance and truthful fallback. Corpus inventory, rights disposition and controlled import remain missing. |

The synchronous link preview is a decisive audit finding. This ticket does not
repair it; production work must replace it with an asynchronous, SSRF-safe
fetch/cache service or disable it.

## Ordinary-photo policy

Choose **transient raw original -> normalized retained master**.

- Accept JPEG, PNG and WebP first. Do not promise HEIC/HEIF until supported by
  the runtime and real-device QA.
- Keep raw input only for validation/scan/retry, then lifecycle-delete within
  24 hours. Do not retain ordinary user originals in V1.
- Auto-orient, convert to sRGB, strip EXIF/XMP/IPTC (including GPS), and retain
  a no-upscale 2048px-long-edge progressive JPEG master at approximately q82.
- Generate 480, 960 and 1440px-long-edge WebP display derivatives. Keep the
  JPEG master for future regeneration. Do not generate AVIF in V1.
- Require meaningful author alt text for ordinary image content; filename alt
  is prohibited. A contextual fallback needs explicit product policy.

Community artwork/hero assets are different: retain an authorized source/master,
rights/owner record, approved crops, and derivatives. They should not inherit
the ordinary-user raw-deletion rule.

### Measured local encoder evidence

Inputs are local PNG UI/QA fixtures, not phone photos; their byte results are
illustrative only. The largest input,
`wordpress/wp-content/uploads/2026/08/image-1.png`, is 2448x1960 and 6.70 MB.
Its four-byte decoded raster is about
18.3 MiB before codec overhead.

| Output | Dimensions | Bytes | Local DDEV ImageMagick wall time |
| --- | ---: | ---: | ---: |
| JPEG q82 master | 2048x1640 | 578,446 | 0.3-0.5 s |
| WebP q82 | 1440x1153 | 277,762 | 0.4 s |
| WebP q82 | 960x769 | 152,012 | 0.3 s |
| WebP q82 | 480x384 | 45,068 | 0.3 s |
| AVIF q50 comparison | 2048x1640 | 173,934 | 1.0-4.8 s |

The recommended retained family totals about 1.01 MiB for this fixture,
compared with the 6.70 MB input. For five representative 1-5 MB phone photos,
model 5-25 MB ingress and 3.75-13 MB retained (0.75-2.6 MB/photo). A 12 MP
decoded raster is about 46 MiB; 24 MP is about 92 MiB before decoder overhead.
Set a 24 MP ceiling and process one image per worker invocation.

## Required control ownership

| Responsibility | Recommended owner/control |
| --- | --- |
| Authz, quota, rights, association, lifecycle, audit | C3 application and media registry |
| Upload authorization | C3-issued short-lived, single-object POST; opaque ID and server-generated key |
| Quarantine | Private S3, Block Public Access, Bucket owner enforced, SSE-S3 initially, exact CORS origin |
| Validation and derivatives | Resource-constrained worker validates bytes/MIME/pixels, strips metadata, writes immutable versions |
| Scan/release | Quarantine plus scanner/re-encode release gate. Select and price scanner before launch. |
| Delivery | CloudFront OAC and custom media hostname; public cache only for public/published media |
| Moderation/delete | C3 revokes presentation first, tombstones version, retains review window, then lifecycle-deletes and reconciles orphans |
| Recovery | Registry/audit export; optional cross-account same-region copy after restore rehearsal |

Launch controls: author quotas, upload/authorization rate limits, WAF, abuse
reporting, SQS DLQ, bounded retries, object reconciliation, budget and anomaly
alerts. Do not put a Lambda processor in a VPC unless another approved need
requires it; NAT Gateway is a common fixed/per-GB cost cliff.

## AWS cost model

### Inputs

- S3 Standard: $0.023/GB-month; PUT/COPY/POST/LIST $0.005/1,000; GET/HEAD
  $0.0004/1,000. S3-to-CloudFront transfer is waived.
- Lambda planning rate: $0.0000166667/GB-second plus $0.20/million requests.
  Model: 2 GB and 2-8 sec/photo for decode, normalize, and derivatives; an
  unselected malware scanner is excluded.
- CloudFront plans at research date: Free $0 (1M requests, 100 GB, 5 GB S3);
  Pro $15 (10M, 50 TB, 50 GB S3); Business $200 (125M, 50 TB, 1 TB S3).
  Allowances are not infinite supported capacity; AWS can require a higher plan
  based on historical usage.
- Each accepted photo: five S3 write-like requests and 2% CDN cache miss rate.
  New ingress: 2% of stored photos/month. Flat-plan WAF/DNS/TLS/logging
  features are included by plan; paid bot/CAPTCHA, CloudTrail data events, long
  log retention, customer KMS keys and cross-region DR are excluded.

| Scenario | Retained data | Delivery / plan | S3 after plan storage credit | Processing | S3 requests | CDN | Baseline monthly total |
| --- | ---: | --- | ---: | ---: | ---: | ---: | ---: |
| Development/QA, 1k | 0.75-2.6 GB | 0.05M / 5 GB, Free | $0 | $0.00-0.01 | $0.00 | $0 | about $0-5 with minimal monitoring |
| 10k | 7.5-26 GB | 0.5M / 50 GB, Free | $0.06-0.48 | $0.01-0.05 | $0.01 | $0 | $0.08-0.55 |
| 100k | 75-260 GB | 3M / 1 TB, Pro | $0.58-4.83 | $0.13-0.53 | $0.07 | $15 | $15.78-20.43 |
| 1M | 0.75-2.6 TB | 8M / 4 TB, Pro | $16.10-58.65 | $1.33-5.33 | $0.56 | $15 | $33.00-79.54 |
| 5M | 3.75-13 TB | 40M / 20 TB, Business | $63.25-276.00 | $6.67-26.67 | $2.82 | $200 | $272.74-505.49 |
| 10M | 7.5-26 TB | 120M / 42 TB, Business near allowance | $149.50-575.00 | $13.33-53.33 | $5.96 | $200 | $368.79-834.29 |

The largest low/high uncertainty is retained bytes/photo. Delivery is separate:
a popular 10k-photo Community can outgrow Free before storage does; a dormant
1M-photo corpus may remain cheap only while eligible and under Free limits.
At the modeled 10M profile, Business is close to both non-configurable
allowances. Above 125M requests or 50 TB/month, evaluate Premium/custom pricing.

A full same-region/cross-account DR copy adds approximately one extra pre-credit
S3 storage line: $0.02-0.06 at 1k, $0.17-0.60 at 10k, $1.73-5.98 at 100k,
$17.25-59.80 at 1M, $86.25-299 at 5M, and $172.50-598 at 10M, plus small
replication requests. Cross-region replication also adds transfer. Versioning
without noncurrent expiry has the same storage-growth risk.

### Gotcha register

| Gotcha | V1 control |
| --- | --- |
| NAT/cross-AZ data path | Keep SQS/Lambda outside VPC unless required. |
| Raw originals, versions, replication | 24h quarantine expiry, noncurrent expiry, explicit DR choice. |
| Incomplete multipart parts | Client abort plus `AbortIncompleteMultipartUpload` lifecycle after one day. |
| CloudFront allowance/eligibility | Monitor 50/80/100% and upgrade before sustained pressure. |
| Pay-as-you-go WAF, managed rules, CAPTCHA | Prefer eligible flat plan; model paid rules separately. |
| CloudTrail S3 data events and verbose logs | Scope mutation prefixes, retain short, avoid real-time logs by default. |
| KMS customer-managed key requests | Start with S3-managed encryption unless compliance requires KMS. |
| AVIF, image bombs, retry storms | JPEG/WebP first, pixel/resource caps, DLQ, retry and user limits. |
| Public bucket/hotlinking | Block public access, OAC, opaque versions, WAF and quotas. |
| Malware scanner | Required launch decision; no implicit omission. |

## Processing and alternatives

Use Lambda plus SQS for V1: no idle cost, bounded one-image work, and simple
per-photo accounting. Reject the existing app worker because it shares
interactive web capacity and lacks a durable queue. Reserve ECS/Fargate for a
scanner/transcoder that cannot run in Lambda; a continuously running 0.5
vCPU/1 GB Fargate task is about $16/month before logs/network. EC2 adds patching
and availability ownership without a demonstrated V1 benefit.

The first scaling constraint is processing safety and abuse, then CloudFront
request/delivery allowance. It is not ordinary S3 storage price.

AWS S3 + CloudFront remains recommended for AWS-native queue, security and
delivery coherence. Cloudflare R2 is $0.015/GB-month Standard, $4.50/M writes,
$0.36/M reads and no egress charge; it is attractive for bandwidth-heavy loads
but moves the control plane. Backblaze B2 is $6.95/TB-month, generally free
transactions and three-times-average-storage egress before $0.01/GB; it needs a
separate CDN/security/processing operating model. Neither alternative alone is
a reason to pause the recommended AWS V1.

## Director decisions and approved-next sequence

Director decisions required: provider/account/region; ordinary-photo policy;
scanner/release owner; public-only versus private media; recovery replication;
deletion/review retention; CloudFront plan; and budget/alert response owner.

If approved, sequence work as follows:

1. Define media registry/version identity, states, association, audit, quota,
   tombstone/delete and migration provenance contract.
2. Provision non-production AWS by reviewed infrastructure-as-code: bucket,
   OAC distribution, DNS, SQS/DLQ, least privilege, lifecycle, CORS, budgets.
3. Add upload authorization and idempotent validate/scan/normalize worker.
4. Replace filesystem/JSON-only ownership and single-`src` renderer; disable or
   replace synchronous link preview fetching.
5. Test malformed MIME, bombs, EXIF, scan failure, retry, duplicate, moderation,
   delete, orphan cleanup, expiry and restore.
6. Run authenticated author/moderator/operator native acceptance and alert/cost
   rehearsal before a separate production decision.

## Primary sources

- [Amazon S3 pricing](https://aws.amazon.com/s3/pricing/)
- [CloudFront pricing](https://aws.amazon.com/cloudfront/pricing/) and [flat-rate plan rules](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/flat-rate-pricing-plan.html)
- [AWS Lambda pricing](https://aws.amazon.com/lambda/pricing/) and [Fargate cost guidance](https://aws.amazon.com/blogs/aws-cloud-financial-management/a-finops-guide-to-comparing-containers-and-serverless-functions-for-compute/)
- [Multipart lifecycle guidance](https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpu-abort-incomplete-mpu-lifecycle-config.html)
- [AWS WAF pricing](https://aws.amazon.com/waf/pricing/), [CloudTrail pricing](https://aws.amazon.com/cloudtrail/pricing/), and [AWS KMS pricing](https://aws.amazon.com/kms/pricing/)
- [Cloudflare R2 pricing](https://developers.cloudflare.com/r2/pricing/) and [Backblaze B2 pricing](https://www.backblaze.com/cloud-storage/pricing)
