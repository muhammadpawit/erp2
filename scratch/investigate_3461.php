<?php
require_once('config.php');
$conn_string = "host=" . DB_HOSTNAME . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
$dbconn = pg_connect($conn_string);
$result = pg_query($dbconn, "SELECT name, barcode FROM product WHERE product_id = 3461");
$row = pg_fetch_row($result);
echo "Product 3461: Name=" . $row[0] . ", Barcode=" . $row[1] . "\n";

$result2 = pg_query($dbconn, "SELECT kodebarang FROM product_baru WHERE nama = '" . pg_escape_string($row[0]) . "'");
$row2 = pg_fetch_row($result2);
echo "Product Baru match by Name: " . ($row2 ? $row2[0] : "None") . "\n";

$result3 = pg_query($dbconn, "SELECT kodebarang FROM harga_terendah_new WHERE nama = '" . pg_escape_string($row[0]) . "' LIMIT 1");
$row3 = pg_fetch_row($result3);
echo "Harga Terendah New match by Name: " . ($row3 ? $row3[0] : "None") . "\n";
?>
