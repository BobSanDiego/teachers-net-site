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
