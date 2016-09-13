<?php
namespace WebSharks\Ubuntu\Bootstrap;

// @codingStandardsIgnoreFile
// No strict types. This must be compatible w/ PHP v5.4+.
// i.e., This is used by the WordPress dev containers.

error_reporting(-1);
ini_set('display_errors', 'yes');

header('content-type: text/plain; charset=utf-8');
print_r(opcache_get_status());
