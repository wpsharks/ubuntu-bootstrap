<?php
// @codingStandardsIgnoreFile
// No strict types. This must be compatible w/ PHP v5.4+.
// i.e., This is used by the WordPress dev containers.

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_EDGE', true);
define('WP_DEBUG_DISPLAY', true);
define('JETPACK_DEV_DEBUG', true);

if (version_compare(PHP_VERSION, '7.0.4', '>=')) {
    @ini_set('zend.assertions', '1');
    ini_set('assert.exception', 'yes');
}
define('WP_POST_REVISIONS', 50);
define('WP_ALLOW_MULTISITE', true);
define('ALLOW_UNFILTERED_UPLOADS', true);

define('DB_NAME', '%%CFG_MYSQL_DB_NAME%%');
define('DB_USER', '%%CFG_MYSQL_DB_USERNAME%%');
define('DB_PASSWORD', '%%CFG_MYSQL_DB_PASSWORD%%');
define('DB_HOST', '%%CFG_MYSQL_DB_HOST%%:%%CFG_MYSQL_DB_PORT%%');
define('DB_CHARSET', '%%CFG_MYSQL_DB_CHARSET%%');
define('DB_COLLATE', '%%CFG_MYSQL_DB_COLLATE%%');

define('AUTH_KEY', '%%AUTH_KEY%%');
define('SECURE_AUTH_KEY', '%%SECURE_AUTH_KEY%%');
define('LOGGED_IN_KEY', '%%LOGGED_IN_KEY%%');
define('NONCE_KEY', '%%NONCE_KEY%%');
define('AUTH_SALT', '%%AUTH_SALT%%');
define('SECURE_AUTH_SALT', '%%SECURE_AUTH_SALT%%');
define('LOGGED_IN_SALT', '%%LOGGED_IN_SALT%%');
define('NONCE_SALT', '%%NONCE_SALT%%');

$table_prefix = 'wp_';

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__.'/');
}
require_once '/bootstrap/src/wordpress/dev/guard.php';
require_once ABSPATH.'wp-settings.php';
