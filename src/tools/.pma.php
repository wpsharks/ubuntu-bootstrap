<?php
namespace WebSharks\Ubuntu\Bootstrap;

// @codingStandardsIgnoreFile
// This is loaded via INI `auto_prepend_file`.
// No strict types. This must be compatible w/ PHP v5.4+.

$cfg['SessionSavePath']     = '/tmp';
$cfg['LoginCookieValidity'] = 86400;
ini_set('session.gc_maxlifetime', '86400');

$cfg['Servers'][1]['host'] = $_SERVER['CFG_MYSQL_DB_HOST'];
$cfg['Servers'][1]['port'] = $_SERVER['CFG_MYSQL_DB_PORT'];

#$cfg['Servers'][1]['ssl']         = true;
#$cfg['Servers'][1]['ssl_cert']    = '/etc/bootstrap/ssl/client-crt.pem';
#$cfg['Servers'][1]['ssl_key']     = '/etc/bootstrap/ssl/client-private-key.pem';
#$cfg['Servers'][1]['ssl_ca']      = '/etc/bootstrap/ssl/client-ca-crt.pem';

$cfg['blowfish_secret']       = $_SERVER['CFG_PMA_BLOWFISH_KEY'];
$cfg['Servers'][1]['hide_db'] = '(?:(?:performance|information)_schema|phpmyadmin|mysql|innodb)';
