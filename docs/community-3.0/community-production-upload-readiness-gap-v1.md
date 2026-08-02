# Community Production Upload Readiness Gap v1

The local fixture foundation is not production upload readiness. Before real
attachments are enabled, the platform still requires:

- durable attachment storage;
- Media Library or object-storage authority;
- authoritative MIME inspection;
- malware quarantine and release workflow;
- image derivatives and video/audio transcoding;
- CDN and private-access policy;
- moderation and copyright complaint workflow;
- retention and deletion lifecycle;
- quotas, rate limits, and abuse controls.

These requirements also need accessibility, audit, privacy, recovery, and
cost/retention decisions. No production implementation should infer authority
from the fixture model or accept a client-provided MIME type as proof of safety.
