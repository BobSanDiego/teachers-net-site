#!/usr/bin/env bash
set -euo pipefail

usage() {
  echo "Usage: $0 [--timeout-seconds N] <node-script.mjs> [node arguments...]" >&2
}

timeout_seconds=30
if [[ "${1:-}" == "--timeout-seconds" ]]; then
  timeout_seconds="${2:-}"
  shift 2
fi
if [[ "${1:-}" == --timeout-seconds=* ]]; then
  timeout_seconds="${1#*=}"
  shift
fi
if [[ -z "${1:-}" ]]; then
  usage
  exit 64
fi

script_path="$(realpath "$1")"
shift
repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
launcher_path="$repo_root/tools/qa/run-windows-node-hidden.ps1"
node_bin="${CODEX_WINDOWS_NODE_EXE:-$(command -v node.exe || true)}"

if [[ -z "$node_bin" ]]; then
  echo 'Windows node.exe is unavailable.' >&2
  exit 2
fi

to_unc() {
  local path="$1"
  wslpath -w "$path"
}

node_win="$(wslpath -w "$node_bin")"
launcher_win="$(to_unc "$launcher_path")"
script_win="$(to_unc "$script_path")"
stdout_wsl="$(mktemp "$repo_root/tmp/qa/windows-node-stdout.XXXXXX")"
trap 'rm -f "$stdout_wsl"' EXIT
node_arguments_base64="$(python3 -c 'import base64, json, sys; print(base64.b64encode(json.dumps(sys.argv[1:]).encode()).decode())' "$@")"

command=(powershell.exe -NoLogo -NoProfile -NonInteractive -ExecutionPolicy Bypass -WindowStyle Hidden -File "$launcher_win" -NodePath "$node_win" -ScriptPath "$script_win" -TimeoutSeconds "$timeout_seconds" -NodeArgumentsBase64 "$node_arguments_base64")
set +e
"${command[@]}" >"$stdout_wsl"
powershell_status=$?
set -e
if [[ "$powershell_status" -ne 0 ]]; then
  sed '$d' "$stdout_wsl"
  exit "$powershell_status"
fi
node_status="$(sed -n 's/^__TNET_WINDOWS_NODE_EXIT_CODE=\([0-9][0-9]*\)$/\1/p' "$stdout_wsl" | tail -n 1)"
if [[ ! "$node_status" =~ ^[0-9]+$ ]]; then
  sed '$d' "$stdout_wsl"
  echo 'Windows Node helper did not provide a valid child exit code.' >&2
  exit 70
fi
sed '/^__TNET_WINDOWS_NODE_EXIT_CODE=[0-9][0-9]*$/d' "$stdout_wsl"
exit "$node_status"
