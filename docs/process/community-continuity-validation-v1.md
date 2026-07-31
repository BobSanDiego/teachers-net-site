# Community 3.0 Continuity Validation v1

Run `tools/hopper/validate_community_continuity.py` only after final cycle
metadata exists:

```text
python3 tools/hopper/validate_community_continuity.py \
  --cursor docs/community-3.0/project-cursor.md \
  --handoff docs/community-3.0/engineering-handoff.md \
  --cycle tmp/hopper/tnet-3.0/current/cycle-tnet-3.0-<cycle>.json \
  --manifest tmp/hopper/tnet-3.0/current/MANIFEST-tnet-3.0-<cycle>.txt
```

The guard fails if the completed ticket is absent or backward-pointed in the
cursor/handoff, if phase blocks disagree, if manifest and cycle identity or
finalization disagree, if protected `output.txt` is manifested, or if known
unverified tickets are falsely marked complete. Its tests reproduce a stale
cursor failure and a corrected passing cycle.

This validation complements, rather than replaces, the mechanical hopper
`validate` command. The mechanical command checks files and metadata; this
guard checks semantic continuity.
