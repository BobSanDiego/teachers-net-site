#!/usr/bin/env bash
set -euo pipefail

# Project-specific clean-cycle hopper helper.
# Usage:
#   scripts/jobcenter-hopper.sh init OPS-HOPPER001
#   scripts/jobcenter-hopper.sh collect OPS-HOPPER001 260731061500 file status source
#   scripts/jobcenter-hopper.sh validate 260731061500

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
hopper_root="$repo_root/tmp/hopper/jobcenter"
current_dir="$hopper_root/current"
archive_root="$hopper_root/archive"

die() { printf 'error: %s\n' "$*" >&2; exit 1; }

case "${1:-}" in
  init)
    ticket="${2:?ticket id required}"
    cycle="$(date -u +%y%m%d%H%M%S)"
    mkdir -p "$current_dir" "$archive_root"
    if find "$current_dir" -mindepth 1 -print -quit | grep -q .; then
      prior="$(date -u +%y%m%d%H%M%S)-prior"
      mkdir "$archive_root/$prior"
      find "$current_dir" -mindepth 1 -maxdepth 1 -exec mv {} "$archive_root/$prior/" \;
    fi
    printf '%s\n' "$cycle"
    printf 'project=jobcenter\nticket=%s\ncycle=%s\n' "$ticket" "$cycle" > "$current_dir/cycle.env"
    ;;
  collect)
    ticket="${2:?ticket id required}"
    cycle="${3:?cycle id required}"
    source_file="${4:?source file required}"
    status="${5:?status required}"
    source_path="${6:?original repository path required}"
    [ -f "$repo_root/$source_file" ] || die "source does not exist: $source_file"
    base="$(basename "$source_file")"
    stem="${base%.*}"
    ext="${base##*.}"
    [ "$stem" != "$base" ] || ext="txt"
    target="$current_dir/${stem}-jobcenter-${cycle}.${ext}"
    [ ! -e "$target" ] || die "collision: $target"
    cp -- "$repo_root/$source_file" "$target"
    printf '%s\t%s\t%s\t%s\n' "$(basename "$target")" "$source_path" "$status" "$ticket" >> "$current_dir/artifacts.tsv"
    ;;
  validate)
    cycle="${2:?cycle id required}"
    [ -d "$current_dir" ] || die "current directory missing"
    [ -f "$current_dir/output-jobcenter-$cycle.txt" ] || die "report missing"
    [ -f "$current_dir/MANIFEST-jobcenter-$cycle.txt" ] || die "manifest missing"
    [ -f "$current_dir/cycle-jobcenter-$cycle.json" ] || die "cycle record missing"
    while IFS=$'\t' read -r file _; do
      [ -z "$file" ] || [ -s "$current_dir/$file" ] || die "missing or empty artifact: $file"
    done < "$current_dir/artifacts.tsv"
    printf 'validated %s\n' "$current_dir"
    ;;
  *)
    die "usage: $0 init <ticket> | collect <ticket> <cycle> <source> <status> <original-path> | validate <cycle>"
    ;;
esac
