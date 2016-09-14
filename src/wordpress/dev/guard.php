<?php
namespace WebSharks\Ubuntu\Bootstrap;

// @codingStandardsIgnoreFile
// No strict types. This must be compatible w/ PHP v5.4+.
// i.e., This is used by the WordPress dev containers.

if (!is_file(ABSPATH.'.allow-wp')) {
    if (!empty($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] === '%%CFG_MAINTENANCE_BYPASS_KEY%%') {
        file_put_contents(ABSPATH.'.allow-wp', '');
    } else {
        header('WWW-Authenticate: Basic realm="WordPress Installation Guard"');
        header('HTTP/1.0 401 Unauthorized');
        exit();
    }
}
