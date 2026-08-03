# Canonical Community Composer Parity Matrix v1

| Capability | Topic | Reply | Required evidence |
|---|---|---|---|
| Body-first authoring | existing | existing | same field contract |
| Markdown profile | shared output | shared output | fixture corpus |
| Paste/drop/select image | existing | required | MIME, object URL, upload |
| Mocked link preview | existing | required | URL preserved, no live HTTP |
| Staged media/error state | existing | required | component tests |
| Community/post type/title | visible | absent | context snapshot |
| Replying-to context | absent | required | safe author/target |
| Nonce/idempotency/PRG | topic-specific | reply-specific | controller tests |
| Parent/thread validation | absent | required | reply boundary tests |

Parity means the same body, media, link, formatting, and error experience; publication and targeting remain context-specific.
