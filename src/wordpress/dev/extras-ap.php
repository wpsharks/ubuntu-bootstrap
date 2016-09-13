<?php
namespace WebSharks\Ubuntu\Bootstrap;

// @codingStandardsIgnoreFile
// This is loaded via INI `auto_prepend_file`.
// No strict types. This must be compatible w/ PHP v5.4+.
// i.e., This is used by the WordPress dev containers.

foreach ($_SERVER as $_key => &$_value) {
    if ($_value === ' ' && strpos($_key, 'CFG_') === 0) {
        // See: `src/setups/env-vars` for reasoning.
        $_value = ''; // Empty this string.
    }
} unset($_key, $_value);

if (!empty($_SERVER['CFG_HOST'])) {
    $_SERVER['SERVER_NAME'] = $_SERVER['CFG_HOST'];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on'; // e.g., For WordPress dev containers.
}
