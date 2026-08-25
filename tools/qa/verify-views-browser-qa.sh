#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
views_url='https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17'
endpoint='http://127.0.0.1:9222'
launcher_win="$(wslpath -w "$repo_root/tools/qa/launch-chrome-cdp-9222.ps1")"
screenshot_wsl="$repo_root/tmp/qa/windows-local-probe/canonical-ready.png"
screenshot_win="$(wslpath -w "$screenshot_wsl")"
node_runner="$repo_root/tools/qa/run-windows-node-hidden.sh"

if [[ ! -x "$node_runner" ]]; then
  echo 'ENGINEERING INPUT REQUIRED: canonical hidden Windows Node runner is unavailable.' >&2
  exit 2
fi

if ! powershell.exe -NoLogo -NoProfile -NonInteractive -ExecutionPolicy Bypass -WindowStyle Hidden -File "$launcher_win" -Url "$views_url"; then
  echo 'ENGINEERING INPUT REQUIRED: the isolated Windows Chrome launcher failed.' >&2
  exit 3
fi

mkdir -p "$(dirname "$screenshot_wsl")"
run_probe() {
  "$node_runner" --timeout-seconds 20 "$repo_root/tools/qa/probe-windows-chrome-cdp.mjs" \
    --endpoint="$endpoint" \
    --mode=views \
    --url="$views_url" \
    --replace-views-targets=true \
    --keep-target=true \
    --timeout=12000 \
    --screenshot="$screenshot_win"
}

if probe_output="$(run_probe)"; then
  echo 'BROWSER SELF-HEALING: NOT NEEDED'
else
  echo 'Windows-local command probe failed; restarting only the dedicated QA Chrome profile.' >&2
  if ! powershell.exe -NoLogo -NoProfile -NonInteractive -ExecutionPolicy Bypass -WindowStyle Hidden -File "$launcher_win" -Url "$views_url" -Restart; then
    echo 'ENGINEERING INPUT REQUIRED: dedicated QA Chrome restart failed.' >&2
    exit 4
  fi
  if ! probe_output="$(run_probe)"; then
    echo 'ENGINEERING INPUT REQUIRED: Windows-local Chrome failed after one dedicated-profile restart.' >&2
    exit 5
  fi
  echo 'BROWSER SELF-HEALING: SUCCESS (dedicated QA Chrome restarted)'
fi

if ! grep -q '"status":"READY"' <<<"$probe_output"; then
  printf '%s\n' "$probe_output" >&2
  echo 'ENGINEERING INPUT REQUIRED: the CDP probe did not return READY.' >&2
  exit 6
fi

if [[ ! -s "$screenshot_wsl" ]]; then
  echo "ENGINEERING INPUT REQUIRED: the browser screenshot was not written to $screenshot_wsl." >&2
  exit 7
fi

printf '%s\n' "$probe_output"
