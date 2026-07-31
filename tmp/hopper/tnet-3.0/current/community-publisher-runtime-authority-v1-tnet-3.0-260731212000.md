# Community Publisher Runtime Authority v1

Decision: PHP is authoritative for WordPress runtime behavior. The temporary
topic-only workbench adapter was replaced by
`TNet_Community_Publisher_Domain` and
`TNet_Community_Publisher_Application`. Topics and replies share the PHP
domain implementation; the repository owns durable state.

Python is retained for characterization, regression comparison, and shared
fixture support only. No WordPress runtime imports, executes, or depends on
Python. Shared fixtures contain semantic drift. Rollback is local plugin
deactivation plus prototype-table uninstall; no production deployment or
migration is authorized.
