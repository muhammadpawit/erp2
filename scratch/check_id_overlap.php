<?php
require_once('config.php');
$conn_string = "host=" . DB_HOSTNAME . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
$dbconn = pg_connect($conn_string);
$result = pg_query($dbconn, "SELECT COUNT(*) FROM product p JOIN product_baru pb ON p.product_id = CAST(pb.id AS INTEGER)");
$row = pg_fetch_row($result);
echo "Match count: " . $row[0];
?>
