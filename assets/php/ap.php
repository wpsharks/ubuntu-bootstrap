<?php
declare (strict_types = 1);
namespace Bootstrap\Assets\Php;

// This is loaded via INI `auto_prepend_file`.

(function () {
    foreach ($_SERVER as $_key => &$_value) {
        if ($_value === ' ' && strpos($_key, 'CFG_') === 0) {
            // See: `assets/setups/env-vars` for reasoning.
            $_value = ''; // Empty this string.
        }
    } // unset($_key, $_value);
})();
