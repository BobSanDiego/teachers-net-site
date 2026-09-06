# Community Reply Composer v1

The local Thread View now provides authenticated direct and nested reply forms.
Each form carries a safe parent post identifier, a nonce, and a generated
submission identifier. The controller performs only request/authentication and
rendering responsibilities; reply validation and persistence remain in the
PHP publisher application, domain, and repository.

Successful replies use Post/Redirect/Get back to the same canonical Thread View
with a `reply-{post_id}` anchor. Parent lookup enforces the current thread and
Community, while the existing domain rejects restricted, missing, mismatched,
or otherwise ineligible parents. Anonymous readers receive a login redirect
when they submit and a login link beside the forms.

## Ratified convergence amendment

Reply entry surfaces continue to use the canonical writer and exact lineage
rules. Historical legacy replies import as flat topic-root children; richer
reply targeting applies only where Community source evidence supplies it. Home,
board, and modal presentation may preserve context without creating a second
reply persistence authority.
