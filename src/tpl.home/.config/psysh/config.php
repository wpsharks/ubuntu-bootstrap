<?php
$___psysh_config = [];

if (is_file(getcwd().'/src/includes/psysh-stub.php')) {
    $___psysh_config['defaultIncludes'][] = getcwd().'/src/includes/psysh-stub.php';
} elseif (is_file(getcwd().'/src/wp-load.php')) {
    $___psysh_config['defaultIncludes'][] = getcwd().'/src/wp-load.php';
}
return $___psysh_config;
