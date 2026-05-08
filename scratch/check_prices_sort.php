<?php
require_once('config.php');
$conn_string = "host=" . DB_HOSTNAME . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
$dbconn = pg_connect($conn_string);
$result = pg_query($dbconn, "SELECT harga_terendah, nama FROM harga_terendah_new WHERE hapus = 0 ORDER BY harga_terendah ASC LIMIT 10");
$rows = pg_fetch_all($result);
echo json_encode($rows, JSON_PRETTY_PRINT);
?>
