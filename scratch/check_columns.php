<?php
require_once('config.php');
$conn_string = "host=" . DB_HOSTNAME . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
$dbconn = pg_connect($conn_string);
$result = pg_query($dbconn, "SELECT column_name FROM information_schema.columns WHERE table_name = 'product_baru'");
$rows = pg_fetch_all($result);
echo json_encode($rows, JSON_PRETTY_PRINT);
?>
