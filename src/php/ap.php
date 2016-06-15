<?php
namespace WebSharks\Ubuntu\Bootstrap;

// This is loaded via INI `auto_prepend_file`.
// No strict types. This must be compatible w/ PHP v5.5+.

foreach ($_SERVER as $_key => &$_value) {
    if ($_value === ' ' && strpos($_key, 'CFG_') === 0) {
        // See: `src/setups/env-vars` for reasoning.
        $_value = ''; // Empty this string.
    }
} unset($_key, $_value);

$_SERVER['SERVER_NAME'] = $_SERVER['CFG_HOST'];
