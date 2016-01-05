<?php
declare (strict_types = 1);
namespace WebSharks\Ubuntu\Bootstrap;

// This is loaded via INI `auto_prepend_file`.

(function () {
    foreach ($_SERVER as $_key => &$_value) {
        if ($_value === ' ' && strpos($_key, 'CFG_') === 0) {
            // See: `src/setups/env-vars` for reasoning.
            $_value = ''; // Empty this string.
        }
    } // unset($_key, $_value);
})();
