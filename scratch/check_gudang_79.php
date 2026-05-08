<?php
require_once('config.php');
$conn_string = "host=" . DB_HOSTNAME . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
$dbconn = pg_connect($conn_string);
$result = pg_query($dbconn, "SELECT COUNT(*) FROM harga_terendah_new WHERE gudang = '79'");
$row = pg_fetch_row($result);
echo "Gudang 79 count: " . $row[0] . "\n";
?>
