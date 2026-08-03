#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/../.."
export NODE_PATH="${NODE_PATH:-/home/bobreap/projects/teachers-net-site/node_modules}"
node assets/runtime-screenshot-capture.mjs "$@"
