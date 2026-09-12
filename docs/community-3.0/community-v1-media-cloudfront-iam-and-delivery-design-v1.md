# Community V1 CloudFront IAM and delivery design v1

Status: `DESIGN_READY / OPERATOR_AUTHORIZATION_REQUIRED`, recorded 2026-09-12.

This design continues `C3-V1-MEDIA-OPERATIONS-FOUNDATION001` after the
Director selected `media.teachers.net`. It does not create IAM, CloudFront,
ACM, DNS, S3 policy, or any other AWS state. The current native processor and
runtime remain `PROVEN_NATIVE` under cycle `260912013811`.

## Fixed delivery contract

- Account: `553830187994`; CloudFront is global; origin region: `us-west-2`.
- Private S3 origin: `tnet-c3-media-553830187994-us-west-2.s3.us-west-2.amazonaws.com`.
- Origin path: `/ready`, so the distribution exposes ready media only.
- Alternate domain: `media.teachers.net`.
- OAC: SigV4, signing behavior `always`, signing protocol `sigv4`.
- Viewer protocol: redirect HTTP to HTTPS; allowed and cached methods:
  `GET`, `HEAD` only.
- AWS managed `CachingOptimized` policy ID:
  `658327ea-f89d-4fab-a63d-7e88639e58f6`; no cookies or query strings are
  forwarded. Compression is enabled.
- Price class: `PriceClass_100`; no WAF, edge compute, Origin Shield,
  real-time logs, invalidations, custom KMS, or advanced analytics.

## Trust policy: TNetC3MediaCloudFrontOperator

`MaxSessionDuration` must be set separately on the role to `3600` seconds.
The trust policy intentionally has no `sts:DurationSeconds` condition.

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "TrustExactC3AutomationWithFreshMfa",
      "Effect": "Allow",
      "Principal": {
        "AWS": "arn:aws:iam::553830187994:user/TNetC3MediaAutomation"
      },
      "Action": "sts:AssumeRole",
      "Condition": {
        "Bool": {
          "aws:MultiFactorAuthPresent": "true"
        },
        "NumericLessThanEquals": {
          "aws:MultiFactorAuthAge": "3600"
        }
      }
    }
  ]
}
```

## Additional automation-user statement

Add this statement to the existing `TNetC3MediaAutomation` policy. It grants
no direct CloudFront or other resource access.

```json
{
  "Sid": "AssumeC3MediaCloudFrontOperator",
  "Effect": "Allow",
  "Action": "sts:AssumeRole",
  "Resource": "arn:aws:iam::553830187994:role/TNetC3MediaCloudFrontOperator"
}
```

## Permissions policy: TNetC3MediaCloudFrontOperator

This is a two-phase policy. Replace `DISTRIBUTION_ID_FROM_DISCOVERY` and
`OAC_ID_FROM_DISCOVERY` only after the first read-only discovery. The create
statements are intentionally present only for the one-time bootstrap. Remove
them after the distribution and OAC IDs are recorded and retain only the exact
resource statements.

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "AccountWideCloudFrontDiscoveryRequiredByApi",
      "Effect": "Allow",
      "Action": [
        "cloudfront:ListDistributions",
        "cloudfront:ListOriginAccessControls"
      ],
      "Resource": "*"
    },
    {
      "Sid": "ReadC3DistributionAfterTagOrExactId",
      "Effect": "Allow",
      "Action": [
        "cloudfront:GetDistribution",
        "cloudfront:GetDistributionConfig",
        "cloudfront:ListTagsForResource"
      ],
      "Resource": "arn:aws:cloudfront::553830187994:distribution/DISTRIBUTION_ID_FROM_DISCOVERY",
      "Condition": {
        "StringEquals": {
          "aws:ResourceTag/Project": "TeachersNet",
          "aws:ResourceTag/Component": "C3Media"
        }
      }
    },
    {
      "Sid": "ReadC3OacAfterExactId",
      "Effect": "Allow",
      "Action": [
        "cloudfront:GetOriginAccessControl",
        "cloudfront:GetOriginAccessControlConfig"
      ],
      "Resource": "arn:aws:cloudfront::553830187994:origin-access-control/OAC_ID_FROM_DISCOVERY"
    },
    {
      "Sid": "ReadAdditionalMetricsStateOnly",
      "Effect": "Allow",
      "Action": "cloudfront:GetMonitoringSubscription",
      "Resource": "*"
    },
    {
      "Sid": "CreateExactlyTaggedC3DistributionOneTime",
      "Effect": "Allow",
      "Action": "cloudfront:CreateDistribution",
      "Resource": "*",
      "Condition": {
        "StringEquals": {
          "aws:RequestTag/Project": "TeachersNet",
          "aws:RequestTag/Component": "C3Media",
          "aws:RequestTag/Environment": "CommunityV1"
        },
        "ForAllValues:StringEquals": {
          "aws:TagKeys": [
            "Project",
            "Component",
            "Environment"
          ]
        }
      }
    },
    {
      "Sid": "TagExactlyCreatedC3DistributionOneTime",
      "Effect": "Allow",
      "Action": "cloudfront:TagResource",
      "Resource": "arn:aws:cloudfront::553830187994:distribution/*",
      "Condition": {
        "StringEquals": {
          "aws:RequestTag/Project": "TeachersNet",
          "aws:RequestTag/Component": "C3Media",
          "aws:RequestTag/Environment": "CommunityV1"
        },
        "ForAllValues:StringEquals": {
          "aws:TagKeys": [
            "Project",
            "Component",
            "Environment"
          ]
        }
      }
    },
    {
      "Sid": "CreateC3OacOneTimeApiUnscopable",
      "Effect": "Allow",
      "Action": "cloudfront:CreateOriginAccessControl",
      "Resource": "*"
    },
    {
      "Sid": "UpdateExactC3Distribution",
      "Effect": "Allow",
      "Action": "cloudfront:UpdateDistribution",
      "Resource": "arn:aws:cloudfront::553830187994:distribution/DISTRIBUTION_ID_FROM_DISCOVERY",
      "Condition": {
        "StringEquals": {
          "aws:ResourceTag/Project": "TeachersNet",
          "aws:ResourceTag/Component": "C3Media"
        }
      }
    },
    {
      "Sid": "UpdateExactC3Oac",
      "Effect": "Allow",
      "Action": "cloudfront:UpdateOriginAccessControl",
      "Resource": "arn:aws:cloudfront::553830187994:origin-access-control/OAC_ID_FROM_DISCOVERY"
    }
  ]
}
```

The policy grants no Route 53, ACM, IAM, S3, Lambda, SQS, ECR, EC2, billing,
WAF, invalidation, delete, CloudFront Function, Lambda@Edge, Origin Shield,
real-time-log, or advanced-metric permissions.

## Permissions boundary

The boundary must be attached to the role before it is used. Its allows mirror
the operator policy, and its explicit denies prevent unrelated administration
even if an attachment is accidentally broadened later.

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PermitOnlyC3CloudFrontOperatorSurface",
      "Effect": "Allow",
      "Action": [
        "cloudfront:ListDistributions",
        "cloudfront:ListOriginAccessControls",
        "cloudfront:GetDistribution",
        "cloudfront:GetDistributionConfig",
        "cloudfront:ListTagsForResource",
        "cloudfront:GetOriginAccessControl",
        "cloudfront:GetOriginAccessControlConfig",
        "cloudfront:GetMonitoringSubscription",
        "cloudfront:CreateDistribution",
        "cloudfront:TagResource",
        "cloudfront:CreateOriginAccessControl",
        "cloudfront:UpdateDistribution",
        "cloudfront:UpdateOriginAccessControl"
      ],
      "Resource": "*",
      "Condition": {
        "StringEqualsIfExists": {
          "aws:ResourceTag/Project": "TeachersNet",
          "aws:ResourceTag/Component": "C3Media"
        }
      }
    },
    {
      "Sid": "DenyAllNonCloudFrontAdministration",
      "Effect": "Deny",
      "Action": [
        "acm:*",
        "aws-portal:*",
        "budgets:*",
        "ce:*",
        "cloudwatch:*",
        "ec2:*",
        "ecr:*",
        "iam:*",
        "lambda:*",
        "logs:*",
        "organizations:*",
        "rds:*",
        "route53:*",
        "s3:*",
        "sqs:*",
        "waf:*",
        "wafv2:*",
        "xray:*"
      ],
      "Resource": "*"
    },
    {
      "Sid": "DenyOptionalCloudFrontFeatures",
      "Effect": "Deny",
      "Action": [
        "cloudfront:AssociateDistributionWebACL",
        "cloudfront:CreateFunction",
        "cloudfront:CreateInvalidation",
        "cloudfront:CreateMonitoringSubscription",
        "cloudfront:CreateRealtimeLogConfig",
        "cloudfront:CreateVpcOrigin",
        "cloudfront:DeleteDistribution",
        "cloudfront:DeleteOriginAccessControl",
        "cloudfront:DeleteRealtimeLogConfig",
        "cloudfront:DeleteMonitoringSubscription",
        "cloudfront:PublishFunction",
        "cloudfront:UpdateFunction",
        "cloudfront:UpdateRealtimeLogConfig"
      ],
      "Resource": "*"
    }
  ]
}
```

The `StringEqualsIfExists` boundary condition does not pretend to constrain
the unscopable create APIs. The explicit one-time bootstrap and immediate
narrowing procedure below is the compensating control.

## AWS resource-level limitations and safe bootstrap

CloudFront discovery list actions have no resource ARN. `CreateDistribution`
supports request-tag conditions but has no resource ARN because the ID does
not exist yet. `CreateOriginAccessControl` has neither a resource ARN nor a
tag condition. `GetOriginAccessControl` is resource-addressable but has no
tag condition. Consequently, no single persistent role can both discover
arbitrary pre-existing untagged resources and be perfectly C3-scoped before
IDs exist.

Safest pattern:

1. Human creates the role with this temporary bootstrap policy and boundary,
   sets `MaxSessionDuration=3600`, and adds the exact automation-user assume
   statement.
2. Codex performs only `ListDistributions`, `ListOriginAccessControls`, and
   read-only candidate inspection. If a candidate is untagged, the human
   inspects it with the Director authority; Codex does not broaden the role.
3. If no relevant distribution/OAC exists, Codex may create only the tagged
   C3 distribution and OAC after the certificate prerequisite and final JSON
   review are complete.
4. Human records the returned distribution/OAC IDs, reviews the exact S3
   bucket statement below, and removes the two create statements. The
   steady-state role then uses exact distribution/OAC ARNs.

`UpdateDistribution` can change a distribution's configuration as a whole;
IAM cannot inspect its request body to guarantee that an allowed C3 update did
not add an edge function or WAF association. The reviewed IaC/configuration
payload and the explicit deny list are therefore required compensating
controls. No delete or invalidation capability is needed for this objective.

## Human-reviewed S3 bucket policy statement

After a distribution ID is known, the human owner may add exactly this
statement to the existing private bucket policy. It does not make the bucket
public and it permits only CloudFront service access to `ready/*` from this
distribution. It is returned for review, not applied by this ticket.

```json
{
  "Sid": "AllowCloudFrontC3MediaReadyOnly",
  "Effect": "Allow",
  "Principal": {
    "Service": "cloudfront.amazonaws.com"
  },
  "Action": "s3:GetObject",
  "Resource": "arn:aws:s3:::tnet-c3-media-553830187994-us-west-2/ready/*",
  "Condition": {
    "StringEquals": {
      "AWS:SourceArn": "arn:aws:cloudfront::553830187994:distribution/DISTRIBUTION_ID"
    }
  }
}
```

Keep S3 Block Public Access enabled and do not add a public bucket policy.

## Minimum delivery configuration

```json
{
  "tags": {
    "Project": "TeachersNet",
    "Component": "C3Media",
    "Environment": "CommunityV1"
  },
  "origin": {
    "id": "C3MediaS3Origin",
    "domain_name": "tnet-c3-media-553830187994-us-west-2.s3.us-west-2.amazonaws.com",
    "origin_path": "/ready",
    "origin_access_control_id": "OAC_ID"
  },
  "default_cache_behavior": {
    "target_origin_id": "C3MediaS3Origin",
    "viewer_protocol_policy": "redirect-to-https",
    "allowed_methods": ["GET", "HEAD"],
    "cached_methods": ["GET", "HEAD"],
    "cache_policy_id": "658327ea-f89d-4fab-a63d-7e88639e58f6",
    "compress": true
  },
  "aliases": ["media.teachers.net"],
  "viewer_certificate": {
    "acm_region": "us-east-1",
    "acm_certificate_arn": "ACM_CERTIFICATE_ARN_IN_US_EAST_1",
    "ssl_support_method": "sni-only",
    "minimum_protocol_version": "TLSv1.2_2021"
  },
  "price_class": "PriceClass_100",
  "enabled": true,
  "ipv6_enabled": true,
  "logging_enabled": false,
  "web_acl_id": null,
  "edge_compute": false,
  "origin_shield": false
}
```

The ACM certificate must be a trusted, currently `ISSUED` certificate in
`us-east-1` whose SAN or wildcard covers `media.teachers.net`. CloudFront
custom aliases require that certificate. If it does not exist, the human
certificate owner must request or import it in `us-east-1` and complete DNS
validation. Route 53 or the external DNS owner must publish the validation
record; this ticket does not create or modify it. After distribution creation,
the separate DNS authorization is a CNAME/ALIAS from `media.teachers.net` to
the returned `*.cloudfront.net` domain.

## Cost safety before provisioning

Use the AWS always-free CloudFront allowance unless the Director explicitly
chooses the separate flat-rate pricing-plan product. Current AWS documentation
states that CloudFront includes 1 TB of data transfer out and 10 million
HTTP/HTTPS requests per month; usage beyond those allowances is billed at
regional pay-as-you-go rates. The distribution and OAC have no stated fixed
per-resource charge. S3 storage and request charges remain separate; S3-to-
CloudFront origin transfer is free.

| Initial month | Traffic assumption | Incremental CloudFront exposure |
|---|---:|---:|
| Low | 0 requests / 0 GB | `$0` |
| Expected development | ≤100,000 requests / ≤10 GB | `$0` CloudFront line; S3 storage/requests remain |
| High bounded test envelope | ≤10,000,000 requests / ≤1 TB | `$0` CloudFront line under current allowance; stop and re-review before sustained excess |

Do not subscribe to a paid flat-rate plan, enable WAF, logs, invalidations,
edge compute, Origin Shield, or advanced metrics in this objective. The
separate CloudFront Free flat-rate plan is `$0/month` with different published
allowances and eligibility constraints; it is not assumed here because the
Director has not selected that billing product.

## Human prerequisites and reactivation point

Before Codex can inspect or provision:

1. Engineering Director creates `TNetC3MediaCloudFrontOperator`, attaches the
   trust policy and boundary, sets 3,600-second maximum sessions, and adds the
   automation-user statement.
2. Human confirms an `ISSUED` ACM certificate covering `media.teachers.net` in
   `us-east-1`, or separately completes certificate request/import and DNS
   validation. Codex does not handle certificate material.
3. Human confirms the reviewed policy JSON and the one-time-create/removal
   sequence. No Director access key is reused.
4. Codex assumes the role through MFA-backed `TNetC3MediaAutomation`, performs
   read-only CloudFront discovery, and reports exact existing state.
5. Only if no relevant distribution exists and the reviewed role is active,
   Codex creates exactly one tagged distribution and one OAC, then reports
   IDs, ARN, generated domain, origin ID, cache behavior, and the bucket
   statement for human review. DNS and bucket-policy mutation remain separate
   human-authorized actions.
6. After IDs are recorded, human removes the wildcard create permissions and
   the role becomes exact-resource steady state.

The operations foundation is ready to reactivate for native CloudFront work
only after steps 1–3. Application upload integration remains prohibited until
the distribution, OAC, reviewed bucket policy, and separate DNS authorization
are complete.

## Provisioned C3 delivery anchor and native completion — 2026-09-12

The approved two-phase bootstrap created exactly one C3 media OAC and one C3
media distribution. The four disabled legacy distributions were not changed.

```json
{
  "distribution_id": "EXVHOH58DVJUJ",
  "distribution_arn": "arn:aws:cloudfront::553830187994:distribution/EXVHOH58DVJUJ",
  "distribution_domain": "dqmsvj26oip2t.cloudfront.net",
  "alternate_domain": "media.teachers.net",
  "oac_id": "E3GATUZLP66CJT",
  "oac_name": "tnet-c3-media-ready-oac",
  "origin_id": "C3MediaS3Origin",
  "origin_domain": "tnet-c3-media-553830187994-us-west-2.s3.us-west-2.amazonaws.com",
  "origin_path": "/ready",
  "signing_protocol": "sigv4",
  "signing_behavior": "always",
  "viewer_protocol_policy": "redirect-to-https",
  "allowed_methods": ["GET", "HEAD"],
  "cached_methods": ["GET", "HEAD"],
  "cache_policy_id": "658327ea-f89d-4fab-a63d-7e88639e58f6",
  "compress": true,
  "price_class": "PriceClass_100",
  "ipv6_enabled": true,
  "certificate_arn": "arn:aws:acm:us-east-1:553830187994:certificate/f976179d-4f1c-4efd-970e-05cb73b8c47b",
  "tls": "TLSv1.2_2021",
  "sni_only": true,
  "edge_compute": false,
  "origin_shield": false,
  "web_acl": null,
  "logging_enabled": false
}
```

Human completion proved that Route 53 `media.teachers.net` targets
`dqmsvj26oip2t.cloudfront.net`, and that the private bucket policy permits the
CloudFront service to read only `ready/*` from the exact distribution ARN.
Canonical-host proof for
`https://media.teachers.net/runtime-proof-260912-runtime4/master.jpg` returned
HTTP 200, `image/jpeg`, `Content-Length: 262142`,
`X-Cache: Hit from cloudfront`, and exactly 262142 downloaded bytes.

The exact bucket-policy statement applied by the human owner is:

```json
{
  "Sid": "AllowCloudFrontC3MediaReadyOnly",
  "Effect": "Allow",
  "Principal": {"Service": "cloudfront.amazonaws.com"},
  "Action": "s3:GetObject",
  "Resource": "arn:aws:s3:::tnet-c3-media-553830187994-us-west-2/ready/*",
  "Condition": {
    "StringEquals": {
      "AWS:SourceArn": "arn:aws:cloudfront::553830187994:distribution/EXVHOH58DVJUJ"
    }
  }
}
```

CloudFront delivery is now `PROVEN_NATIVE`. The remaining operations-foundation
step is controlled IaC import/reconciliation: declare these anchors in the
canonical `infrastructure/aws/community-media/` stack, import existing IDs
into the approved state bucket, run a no-change comparison plan, and review
any drift before apply. Do not recreate or mutate the proven runtime or
delivery anchors during import.
