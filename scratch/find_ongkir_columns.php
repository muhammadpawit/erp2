<?php
require_once('config.php');
$conn = pg_connect("host=".DB_HOSTNAME." dbname=".DB_DATABASE." user=".DB_USERNAME." password=".DB_PASSWORD);
if (!$conn) { echo "Connection failed"; exit; }
$result = pg_query($conn, "SELECT table_name, column_name FROM information_schema.columns WHERE column_name LIKE '%ongkir%'");
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
?>
