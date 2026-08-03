#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/../.."
record=.ddev/runtime-authority.json
commit=$(git rev-parse HEAD)
branch=$(git rev-parse --abbrev-ref HEAD)
ddev start >/dev/null
for attempt in $(seq 1 30); do
    hash=$(ddev exec -q wp --path=/var/www/html/wordpress eval 'echo TNet_Community_Runtime_Authority::facts()["plugin_tree_hash"];' 2>/dev/null | rg -o '[a-f0-9]{64}' | tail -n 1) && [ -n "$hash" ] && break
    sleep 1
done
[ -n "${hash:-}" ]
python3 - "$record" "$branch" "$commit" "$hash" <<'PY'
import json, sys
from pathlib import Path
Path(sys.argv[1]).write_text(json.dumps({'branch':sys.argv[2],'commit':sys.argv[3],'plugin_tree_hash':sys.argv[4]}, indent=2)+'\n')
PY
for attempt in $(seq 1 30); do
    curl -k -fsS "https://teachers-net-community3.ddev.site/community/" -o /tmp/community-runtime-preflight.html && break
    sleep 1
done
grep -q 'data-runtime-status="ok"' /tmp/community-runtime-preflight.html
grep -q "data-runtime-git_commit=\"$commit\"" /tmp/community-runtime-preflight.html
echo "runtime authority OK: $branch $commit $hash"
