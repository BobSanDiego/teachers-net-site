# Community Publisher Workbench QA v1

Automated checks:

```text
PYTHONPATH=tools/community3 python3 -m unittest tools.community3.test_workbench_contract
ddev exec php -l wordpress/wp-content/plugins/tnet-community/admin/class-tnet-community-workbench.php
ddev wp eval-file tools/community3/local_publisher_persistence_smoke.php
ddev wp eval-file tools/community3/local_publisher_persistence_tests.php
```

Manual local QA: start DDEV, activate `tnet-community`, install prototype
tables, log in as a local administrator, open `http://teachers-net.ddev.site/wp-admin/tools.php?page=tnet-community-workbench`, publish a synthetic topic,
verify result/audit/event display, repeat the same key, and remove prototype
tables. Check desktop widths for aligned labels, readable notices, wrapped IDs,
usable result table, and no public-theme styling leakage. Browser visual QA was
attempted but the browser bridge could not initialize in this session; this is
recorded as an environment limitation, not a rendered-success claim.
