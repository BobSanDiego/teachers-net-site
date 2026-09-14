TICKET READY FOR CODEX

Ticket: C3-V1-MEDIA-APPLICATION-INTEGRATION001
Mode: STANDARD
Reasoning: HIGH
Recommended model: LUNA
Project: Community
OWNER: C3 media application integration

OUTCOME

Resume the existing media-integration objective from the resolved IAM boundary
contradiction, establish the approved local operator → signer trust path, and
continue through native multi-image acceptance.

AUTHORITY

The Director verified that account 553830187994 is using
TNetC3MediaIaCOperator, whose attached permissions boundary is
arn:aws:iam::553830187994:policy/TNetC3MediaIaCOperatorBoundary. The exact
local sts:AssumeRole allowance is present in both operator runtime and
boundary. Do not reattach or modify that boundary. Sandy's IMDSv2 path and
the signer's quarantine-PutObject-only policy/boundary remain proven.

OBJECTIVE

Update only the OpenTofu signer trust declaration: preserve EC2-CloudWatchAgent
trust and add only TNetC3MediaIaCOperator with aws:PrincipalArn equal to that
role and sts:RoleSessionName matching tnet-c3-media-local-*. Produce an
isolated no-apply plan and apply only that trust change, never the accepted
event-source-mapping metrics_config residual.

Then prove the temporary local operator can assume the same signer without
exposing credentials. Implement the explicit LOCAL/DDEV provider using the
approved temporary helper/profile path, keeping production IMDSv2-only,
fail-closed, and free of static credentials. Resume canonical native browser
acceptance for two-image direct quarantine upload, complete readiness,
publication gating, ordered persistence/reload, media.teachers.net rendering,
and invalid-sibling removal/retry with sibling preservation. Commit and push
only after positive and negative journeys pass.

STOP BOUNDARY

Stop if the trust plan includes unrelated functional changes, local provider
needs persistent credentials or broader AWS authority, production semantics
change, or native QA exposes a material product/security decision.

END TICKET — C3-V1-MEDIA-APPLICATION-INTEGRATION001
