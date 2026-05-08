<?php
require_once('config.php');
$conn_string = "host=" . DB_HOSTNAME . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    die("Connection failed");
}
$result = pg_query($dbconn, "SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
if (!$result) {
    echo "Query failed: " . pg_last_error($dbconn);
} else {
    $rows = pg_fetch_all($result);
    echo json_encode($rows, JSON_PRETTY_PRINT);
}
?>
