# Community Core Terms integration — `COMMUNITY3-CORE-TERMS-INTEGRATION001`

The local canonical Core Terms tree for this integration was read from the active
runtime version, `wp_cfm_framework_versions.tree_json` for framework `1`, active
version `1`. The checked-in CTJ006 export was stale and was refreshed from that
runtime tree; it must not be used to overwrite newer runtime taxonomy state.

Runtime authority at integration time:

- branch: `COMMUNITY3-ui-working`
- Community source commit: `846be8702fc2ed70f4bfd0c26591d9c95b78c3df`
- runtime authority file: `/home/bobreap/projects/teachers-net-live/.ddev/runtime-authority.json`
- resulting normalized tree SHA-256: `60b0e1b331fc505a6909bf0ac3c4805ff33d80ecbea8e42996306d68f68bedfe`

The existing top-level `Hot Topics` term was preserved with UUID
`58cd8714-feb6-45b3-9f37-814840fb801c`. Four missing top-level semantic parents
were added: `Teaching Practice & Theory`, `Professional Communities & Roles`,
`Classroom Projects & Themes`, and `Education Careers & Credentials`. The 53
approved `NEW_TERM_CANDIDATE` child terms were added under their accepted
semantic parents, including `AI in Education` under the existing `Technology`
term. No Rail Parent Lists, C3 bindings, Views, Jobs UI, or production routes
were changed.

The source export is:

`wordpress/wp-content/plugins/profilaxes/docs/teachers-net-core-terms-ctj006-taxonomy-export.json`

Existing Terms retain their UUIDs and hierarchy. RENAMED_EQUIVALENT and
BROADER_TERM_CANDIDATE chatboard rows continue to resolve to existing canonical
Terms; no legacy-name duplicates were created. Feedback, FAQ, and retired Job
Talk identity remain excluded.
