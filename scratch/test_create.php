<?php
require_once('config.php');
$conn = pg_connect("host=".DB_HOSTNAME." dbname=".DB_DATABASE." user=".DB_USERNAME." password=".DB_PASSWORD);
if (!$conn) { echo "Connection failed"; exit; }
$result = pg_query($conn, "CREATE TABLE test_table (id SERIAL PRIMARY KEY)");
if (!$result) {
    echo pg_last_error($conn);
} else {
    echo "Success";
}
?>
