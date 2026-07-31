# Legacy Publisher Characterization Harness v1

This ticket delivers the first executable, local-only compatibility baseline.
`tools/community3/legacy_publisher_characterization.py` is a pure observation
model. It does not port Perl, publish files, write a database, call a network,
send notifications, or implement WordPress.

Fixtures are under `tests/fixtures/community3/legacy-publisher/`. The model
returns stable observations suitable for later comparison:
`legacy_characterization(fixture)` versus a future
`wordpress_publisher_result(fixture)`.

The baseline covers accepted topics, replies, validation, abuse rejection,
legacy URL/timestamp shape, `chat_posts` field names, local-path/group
distinction, missing mapping, duplicate classification, partial-write
classification, and immutable archive references. It deliberately leaves
anonymous posting, edit/delete/retract, moderator authorization, identity
reconciliation, mailring, concurrency, and recovery unknown.

Run:

```text
PYTHONPATH=tools/community3 python3 -m unittest tools.community3.test_legacy_publisher_characterization
python3 -m unittest discover -s tools/community3 -p 'test_*.py'
python3 -m py_compile tools/community3/*.py
```

Rollback/removal is limited to deleting the two harness files, the synthetic
fixture directory, and these documentation files in a later explicitly
authorized ticket. No production rollback is required because the harness has
no external side effects.

Next ticket: obtain authorized source ownership or redacted characterization
evidence, then define the legacy URL/archive compatibility boundary before any
publisher implementation work.
