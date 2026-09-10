#!/usr/bin/env bash
set -euo pipefail

# Generate the local integrated-host authority record from the governed source
# identity and the mounted runtime tree. This is deliberately separate from
# the dedicated Community3 DDEV preflight.
source_root="${C3_SOURCE_ROOT:-/home/bobreap/projects/teachers-net-community3}"
runtime_root="${C3_RUNTIME_ROOT:-/home/bobreap/projects/teachers-net-live}"
record="${C3_RUNTIME_AUTHORITY_RECORD:-$runtime_root/.ddev/runtime-authority.json}"

branch="$(git -C "$source_root" rev-parse --abbrev-ref HEAD)"
commit="$(git -C "$source_root" rev-parse HEAD)"
test "$branch" = "COMMUNITY3-ui-working"

hash=""
for attempt in $(seq 1 30); do
    hash="$(cd "$runtime_root" && ddev exec -q wp --path=/var/www/html/wordpress eval 'echo TNet_Community_Runtime_Authority::facts()["plugin_tree_hash"];' 2>/dev/null | rg -o '[a-f0-9]{64}' | tail -n 1 || true)"
    test -n "$hash" && break
    sleep 1
done
test -n "$hash"

record_dir="$(dirname "$record")"
mkdir -p "$record_dir"
tmp="$(mktemp "$record.tmp.XXXXXX")"
trap 'rm -f "$tmp"' EXIT
python3 - "$tmp" "$branch" "$commit" "$hash" <<'PY'
import json
import sys
from pathlib import Path

target, branch, commit, tree_hash = sys.argv[1:]
Path(target).write_text(json.dumps({
    'branch': branch,
    'commit': commit,
    'plugin_tree_hash': tree_hash,
}, indent=2) + '\n')
PY
mv "$tmp" "$record"
trap - EXIT

status="$(curl -k -sSI --max-time 10 https://teachers-net-live.ddev.site/community/ai-in-education/ | awk 'BEGIN{IGNORECASE=1} /^x-tnet-community-runtime-status:/{gsub(/\r/,"",$2); print $2; exit}')"
test "$status" = "ok"
printf 'runtime_authority_record=%s\nbranch=%s\ncommit=%s\nplugin_tree_hash=%s\nstatus=%s\n' "$record" "$branch" "$commit" "$hash" "$status"
