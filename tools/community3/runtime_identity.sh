#!/usr/bin/env bash
set -euo pipefail
printf 'ddev_project=teachers-net-community3\n'
printf 'branch=%s\n' "$(git branch --show-current)"
printf 'commit=%s\n' "$(git rev-parse --short HEAD)"
printf 'worktree_root=%s\n' "$(pwd -P)"
