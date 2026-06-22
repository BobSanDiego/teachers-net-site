# Plugin Architecture

Core Terms / Profilaxes:
- Folder: wordpress/wp-content/plugins/profilaxes
- Owns reusable classification infrastructure.
- Owns term tree, assignment, compiled relationships, stable IDs, APIs, hooks, and Labs diagnostics.
- Does not know Jobs exists.

Teachers.Net Jobs:
- Future folder: wordpress/wp-content/plugins/tnet-jobs
- Owns jobs, employers, job lifecycle, apply behavior, metrics, visibility, recruiter workflows, and future application objects.
- Depends on Core Terms for classification.
- Must not write directly into Core Terms internals.

Future Profile/Onboarding:
- Owns candidate identity, resume, preferences, availability, visibility, and profile completion.
- May consume Core Terms.

Future Commerce:
- Owns billing, plans, receipts, recruiter subscriptions, paid promotions, and resume-search entitlements.

Boundary rule:
Terms classify.
Jobs authorizes.
WordPress authenticates.

Security rule:
Do not use Core Terms as a permission system. Jobs permissions must use WordPress capabilities and Jobs-owned membership/entitlement state.
