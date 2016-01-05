<?php
declare (strict_types = 1);
namespace WebSharks\Ubuntu\Bootstrap\Tools;

ini_set('session.save_path', '/tmp');
ini_set('session.gc_maxlifetime', '86400');
$cfg['LoginCookieValidity'] = 86400;

$cfg['Servers'][1]['host'] = $_SERVER['CFG_MYSQL_DB_HOST'];
$cfg['Servers'][1]['port'] = $_SERVER['CFG_MYSQL_DB_PORT'];

#$cfg['Servers'][1]['ssl']         = true;
#$cfg['Servers'][1]['ssl_cert']    = '/etc/bootstrap/ssl/client-crt.pem';
#$cfg['Servers'][1]['ssl_key']     = '/etc/bootstrap/ssl/client-private-key.pem';
#$cfg['Servers'][1]['ssl_ca']      = '/etc/bootstrap/ssl/client-ca-crt.pem';

$cfg['blowfish_secret']       = $_SERVER['CFG_TOOLS_PMA_BLOWFISH_KEY'];
$cfg['Servers'][1]['hide_db'] = '(?:(?:performance|information)_schema|phpmyadmin|mysql|innodb)';

$cfg['PmaAbsoluteUri'] = 'https://'.$_SERVER['HTTP_HOST'].'/tools/pma/';
$cfg['ForceSSL']       = true;
