# Local Development

Project root:
cd /home/bobreap/projects/teachers-net-site

Start:
ddev start

Stop:
ddev stop

View project:
https://teachers-net.ddev.site

Activate Core Terms:
ddev wp plugin activate profilaxes --path=wordpress

List plugins:
ddev wp plugin list --path=wordpress

Check Core Terms tables:
ddev mysql -e "SHOW TABLES LIKE 'wp_cfm%';"

PHP lint example:
ddev exec php -l wordpress/wp-content/plugins/profilaxes/profilaxes.php

Warning:
Do not run `wp plugin uninstall profilaxes` unless intentionally deleting the plugin files from wp-content/plugins. Prior testing showed uninstall can remove the plugin files while leaving database tables.
