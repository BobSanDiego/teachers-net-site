#!/usr/bin/env bash

set -euo pipefail

wp_config="/var/www/html/wordpress/wp-config.php"
flag_line="define( 'TNET_JOBS_JC053_MIGRATION_ENABLED', true );"

if [[ ! -f "$wp_config" ]]; then
  exit 0
fi

if grep -Fqx "$flag_line" "$wp_config"; then
  exit 0
fi

if grep -Fq "That's all, stop editing!" "$wp_config"; then
  sed -i "/That's all, stop editing!/i\\$flag_line" "$wp_config"
else
  printf '\n%s\n' "$flag_line" >> "$wp_config"
fi
