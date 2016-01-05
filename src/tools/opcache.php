<?php
declare (strict_types = 1);
namespace WebSharks\Ubuntu\Bootstrap\Tools;

error_reporting(-1);
ini_set('display_errors', 'yes');

header('content-type: text/plain; charset=utf-8');
print_r(opcache_get_status());
