# C3 V1 media application integration bootstrap gate v1

Status: `BLOCKED / HUMAN_IAM_BOOTSTRAP_REQUIRED`, recorded 2026-09-12 in
cycle `260912223125` for `C3-V1-MEDIA-APPLICATION-INTEGRATION001`.

This is the implementation prerequisite for the Director-approved Community
media application journey. It records the minimum application-side AWS
authority and the human bootstrap boundary. It does not create AWS resources,
credentials, application code, schema, or production state.

## Director decisions carried forward

- Community owns the media registry and product processing state.
- `TNetC3MediaApplicationSigner` is a dedicated application-side role; the
  browser receives no AWS credentials.
- The existing Lambda remains storage-only. Community uses bounded polling of
  the public ready delivery path to determine the four expected variants.
- Posts publish only when every retained attachment is `READY`.
- Existing runtime, CloudFront, DNS, S3, SQS, Lambda, ECR, IAM, and OpenTofu
  seams remain closed and are not retested or changed here.

## Current blocker

The current Community repository contains no AWS SDK/signer dependency, no
application role-assumption configuration, and no `TNetC3MediaApplicationSigner`
or application runtime role in the canonical IaC. The current composer remains
an SSH-hosted WordPress, single-file `wp_handle_upload()` path. No approved
mechanism exists yet for the application process to obtain temporary AWS
authority without placing credentials in the browser or a repository.

Implementation stops until the Engineering Director identifies the dedicated
application host/runtime principal and a human creates the reviewed anchors
below. The existing Sandy host is not modified by this record.

## Exact least-privilege role design for human review

The recommended machine path is a dedicated application runtime role attached
to the approved application host through an instance profile. The runtime role
has no S3 access; it may assume only the signer role. The signer role may only
create objects under `quarantine/*`. Readiness polling uses the existing
`https://media.teachers.net/` delivery path, so the signer role does not need
read access to `ready/*`.

Account: `553830187994`; region: `us-west-2`; bucket:
`tnet-c3-media-553830187994-us-west-2`.

### TNetC3MediaApplicationSigner trust policy

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "AllowOnlyC3ApplicationRuntime",
      "Effect": "Allow",
      "Principal": {
        "AWS": "arn:aws:iam::553830187994:role/TNetC3MediaApplicationRuntime"
      },
      "Action": "sts:AssumeRole",
      "Condition": {
        "ArnEquals": {
          "aws:PrincipalArn": "arn:aws:iam::553830187994:role/TNetC3MediaApplicationRuntime"
        },
        "StringLike": {
          "sts:RoleSessionName": "tnet-c3-media-app-*"
        }
      }
    }
  ]
}
```

Set `MaxSessionDuration` to `3600` seconds. MFA is not required on this
machine-to-machine trust because an unattended application host cannot supply
an interactive MFA code; human administrative access remains separately
MFA-gated. Do not add any human, automation, IaC, processor, CloudFront, or
runtime-proof principal.

### TNetC3MediaApplicationSigner permissions policy

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PutQuarantineObjectsOnly",
      "Effect": "Allow",
      "Action": "s3:PutObject",
      "Resource": "arn:aws:s3:::tnet-c3-media-553830187994-us-west-2/quarantine/*"
    }
  ]
}
```

The application must enforce the exact generated key, one asset per
authorization, allowed image type, maximum size, and short expiry in the
presigned POST conditions. IAM cannot reduce `quarantine/*` to an unanticipated
runtime-generated media ID; the signed policy conditions and the registry
authorization are the exact per-asset boundary. No multipart, ACL, tagging,
delete, read, list, queue, Lambda, IAM, or CloudFront permission is included.

### TNetC3MediaApplicationSigner permissions boundary

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "BoundaryPutQuarantineObjectsOnly",
      "Effect": "Allow",
      "Action": "s3:PutObject",
      "Resource": "arn:aws:s3:::tnet-c3-media-553830187994-us-west-2/quarantine/*"
    }
  ]
}
```

The attached identity policy and this boundary must both be present. The
boundary intentionally contains no unrelated account administration authority.

### TNetC3MediaApplicationRuntime trust policy

Use only if the approved application host is an EC2 instance and is separately
identified by the Engineering Director. The role must not be attached to
Sandy without a separate explicit production authorization.

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "AllowEc2ServiceForApprovedApplicationHost",
      "Effect": "Allow",
      "Principal": {
        "Service": "ec2.amazonaws.com"
      },
      "Action": "sts:AssumeRole",
      "Condition": {
        "StringEquals": {
          "aws:SourceAccount": "553830187994"
        }
      }
    }
  ]
}
```

### TNetC3MediaApplicationRuntime permissions policy and boundary

Use the same JSON for the runtime role's attached policy and its permissions
boundary. The runtime role has no direct S3 or application-data authority.

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "AssumeC3ApplicationSignerOnly",
      "Effect": "Allow",
      "Action": "sts:AssumeRole",
      "Resource": "arn:aws:iam::553830187994:role/TNetC3MediaApplicationSigner"
    }
  ]
}
```

If the approved application host is not EC2, stop and obtain the equivalent
native workload trust mechanism before implementation; do not substitute an
IAM user key or reuse an existing operational role.

## Human bootstrap procedure

1. Engineering Director identifies the dedicated application host and confirms
   whether it can use a native instance profile. Do not modify Sandy or
   production as part of this record.
2. In IAM, create the signer boundary policy, signer permissions policy, and
   runtime policy from the exact JSON above. Create
   `TNetC3MediaApplicationSigner` with the exact trust policy, attach the
   signer permissions policy and boundary, and set maximum session duration to
   3600 seconds.
3. Create `TNetC3MediaApplicationRuntime` with the exact EC2 trust policy,
   attach the runtime policy and the same runtime boundary, and create its
   instance profile. Attach that profile only to the approved application
   host, after the Director has reviewed the host identity.
4. Verify the governed media bucket's CORS configuration for the actual
   Community browser origin. If it does not permit the approved direct POST,
   return the exact origin/method/header change for review before applying it;
   do not guess an origin or broaden CORS to `*`.
5. Keep all role names, policy ARNs, host identity, and any temporary session
   material out of Git, tickets, reports, and logs. The application obtains
   instance-profile credentials from the host metadata path, assumes the
   signer role for a one-hour maximum session, and gives the browser only a
   short-lived presigned POST for one exact quarantine key.
6. Before any application implementation, prove the role chain harmlessly:
   from the approved host/runtime identity, run `sts get-caller-identity` for
   the runtime role, then assume the signer role and run only a denied-action
   matrix plus a harmless allowed-action preflight. Do not upload a test object
   until the application integration ticket explicitly reaches that step.

No AWS resource or credential was created by this cycle. Product implementation
and native acceptance resume only after the human bootstrap is complete and
the role-chain evidence is supplied without exposing temporary credentials.
