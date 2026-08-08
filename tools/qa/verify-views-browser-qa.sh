#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
ps_path='\\wsl$\Ubuntu-24.04\home\bobreap\projects\teachers-net-site\tools\qa\bootstrap-views-browser-qa.ps1'

if ! powershell.exe -NoProfile -ExecutionPolicy Bypass -File "$ps_path" -ConfigureBridge; then
  echo 'ENGINEERING INPUT REQUIRED: Windows browser bootstrap or local-only bridge failed.' >&2
  exit 2
fi

host_ip="$(ip route | awk '/^default via/{print $3; exit}')"
if [[ -z "$host_ip" ]]; then
  echo 'ENGINEERING INPUT REQUIRED: WSL default gateway could not be determined.' >&2
  exit 3
fi
bridge="http://${host_ip}:9223/json/version"
payload="$(curl -fsS --max-time 5 "$bridge")" || {
  echo "ENGINEERING INPUT REQUIRED: WSL could not reach the verified bridge at $bridge." >&2
  exit 3
}

grep -q 'Chrome/' <<<"$payload" || {
  echo 'ENGINEERING INPUT REQUIRED: bridged endpoint did not identify Chrome.' >&2
  exit 4
}
printf 'READY WSL_BRIDGE=%s\n' "$bridge"
