<?php
define('WP_ALLOW_MULTISITE', true);
define('WP_CACHE', is_file(__DIR__.'/wp-content/advanced-cache.php'));

define('DB_NAME', $_SERVER['CFG_MYSQL_DB_NAME']);
define('DB_USER', $_SERVER['CFG_MYSQL_DB_USERNAME']);
define('DB_PASSWORD', $_SERVER['CFG_MYSQL_DB_PASSWORD']);
define('DB_HOST', $_SERVER['CFG_MYSQL_DB_HOST'].':'.$_SERVER['CFG_MYSQL_DB_PORT']);
define('DB_CHARSET', $_SERVER['CFG_MYSQL_DB_CHARSET']);
define('DB_COLLATE', $_SERVER['CFG_MYSQL_DB_COLLATE']);

define('AUTH_KEY', '%%AUTH_KEY%%');
define('SECURE_AUTH_KEY', '%%SECURE_AUTH_KEY%%');
define('LOGGED_IN_KEY', '%%LOGGED_IN_KEY%%');
define('NONCE_KEY', '%%NONCE_KEY%%');
define('AUTH_SALT', '%%AUTH_SALT%%');
define('SECURE_AUTH_SALT', '%%SECURE_AUTH_SALT%%');
define('LOGGED_IN_SALT', '%%LOGGED_IN_SALT%%');
define('NONCE_SALT', '%%NONCE_SALT%%');

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

@ini_set('zend.assertions', '1');
ini_set('assert.exception', 'yes');

$table_prefix = 'wp_';

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__.'/');
}
require_once ABSPATH.'wp-settings.php';
