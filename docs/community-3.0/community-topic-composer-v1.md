# Community Topic Composer v1

The local-only `/community/new/` route provides an authenticated form for a
new topic. It is registered only when the DDEV marker is present, redirects
anonymous users to WordPress login, and uses the current WordPress user as the
author authority.

The controller owns route, nonce, request sanitization, form errors, and
Post/Redirect/Get. It passes a bounded local Community map and a generated
submission key to `TNet_Community_Publisher_Application`; it does not duplicate
domain validation or persistence. Successful publication redirects to the
existing canonical local Thread View. No production, CGI, notification,
migration, or Core Terms behavior is involved.

## Ratified convergence amendment

The topic composer remains a single canonical writer. Home or board presentation
may choose its post-success return context, but cannot duplicate persistence or
replace the canonical discussion-page destination. Historical import is governed
by the source-profile and migration-ledger contracts, not by the new-topic UX.
