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
