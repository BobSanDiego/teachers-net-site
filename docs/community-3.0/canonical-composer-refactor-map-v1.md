# Canonical Community Composer Refactor Map v1

Topic authoring is in class-tnet-community-topic-composer-controller.php with URL extraction, upload validation, staged-image JavaScript, mocked preview selection, nonce, idempotency, PRG, and topic context. Reply authoring is in class-tnet-community-thread-controller.php with a separate inline form, nonce, parent/thread validation, reply publication, and retargeting. Reply currently lacks topic image and preview submission parity.

| Concern | Topic | Reply | Safe decision |
|---|---|---|---|
| Body/Markdown | textarea and shared output renderer | textarea and shared output renderer | extract field/rendering contract |
| HTTPS links | detection, representative, mocked preview | absent from submission | extract service and add reply adapter |
| Images | paste/drop/select, MIME/size/upload/cleanup | absent | extract staged-media service |
| Context | Community, post type, title | inherited community, parent/thread target | remain controller-owned |
| Security/publication | topic nonce, idempotency, PRG, application publish | reply nonce, parent/thread checks, PRG, reply publish | retain separately |

Recommendation: retain thin topic and reply controllers around shared services and a shared view/field component. Do not merge controllers or move parent/thread validation into a generic component.

REF001 extracts the pure HTTPS URL detector and normalized staged-image value
contract. Controller-owned nonce, idempotency, PRG, upload, parent/thread, and
publication boundaries remain unchanged.
