# Community Publisher Runtime Language Boundary v1

C3-CORE007 is Python and remains the executable contract/characterization
authority. The local WordPress workbench is PHP and cannot directly import
that Python module inside WordPress. This ticket therefore uses the smallest
PHP adapter required to construct the already-approved C3-CORE007 publication
shape and delegates durable state to the C3-CORE008 repository.

This is a local bridge, not a second production publisher domain: it supports
topics only, uses the same canonical fields/reason codes and persistence
contract, and is covered by static boundary tests plus DDEV integration smoke
tests. It does not reimplement replies, moderation workflows, notifications,
identity resolution, or legacy behavior. A future implementation ticket must
replace or formally share this boundary before production persistence is
authorized.

Rollback/removal: deactivate/remove the local prototype plugin and uninstall
only its three prototype tables. No production deployment or data migration is
part of this work.
