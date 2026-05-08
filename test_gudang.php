<?php
define('DIR_SYSTEM', '/var/www/erp2/system/');
require_once('/var/www/erp2/config.php');
$db = new pg_exec(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$query = $db->query("SELECT * FROM gudang");
$gudang_map = array();
foreach ($query->rows as $g) {
    $gudang_map[strtolower(trim($g['nama']))] = $g['gudang_id'];
}
print_r($gudang_map);
