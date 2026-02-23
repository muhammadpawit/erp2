<?php
define('HTTP_SERVER', 'http://'.$_SERVER["HTTP_HOST"].'/');
define('HTTP_IMAGE', 'http://'. $_SERVER["HTTP_HOST"].'image/');
define('HTTPS_SERVER', 'http://'.$_SERVER["HTTP_HOST"].'/');

// DIR
define('DIR_APPLICATION', '/usr/local/nginx/html/erp2.nissonindonesia.com/');
define('DIR_SYSTEM', '/usr/local/nginx/html/erp2.nissonindonesia.com/system/');
define('DIR_DATABASE', '/usr/local/nginx/html/erp2.nissonindonesia.com/system/database/');
define('DIR_LANGUAGE', '/usr/local/nginx/html/erp2.nissonindonesia.com/language/');
define('DIR_TEMPLATE', '/usr/local/nginx/html/erp2.nissonindonesia.com/view/template/');
define('DIR_CONFIG', '/usr/local/nginx/html/erp2.nissonindonesia.com/system/config/');
define('DIR_IMAGE', '/usr/local/nginx/html/erp2.nissonindonesia.com/image/');
define('DIR_CACHE', '/usr/local/nginx/html/erp2.nissonindonesia.com/system/cache/');
define('DIR_DOWNLOAD', '/usr/local/nginx/html/erp2.nissonindonesia.com/download/');
define('DIR_LOGS', '/usr/local/nginx/html/erp2.nissonindonesia.com/system/logs/');
define('PRINTER_JKT','http://localhost/nissonprint/');
define('PRINTER_SBY','http://localhost/nissonprint/');
// DB
define('DB_DRIVER', 'postgre');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'nisson');
define('DB_PASSWORD', 'niPT#9543^');
define('DB_DATABASE', 'erp2nisson');
define('DB_PREFIX', '');

		$url = 'http://' . $_SERVER['HTTP_HOST'] . "/phpexcel_gd-master/";
		$path = $_SERVER['DOCUMENT_ROOT'] . '/phpexcel_gd-master/';
		define('SITEURL', $url);
		define('SITEPATH', str_replace('\\', '/', $path));
		$paths ='/usr/local/nginx/html/coid.momnbab.id/img/cache/';
		define('SITESPATH', str_replace('\\', '/', $paths));
?>
