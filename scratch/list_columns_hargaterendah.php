<?php
require_once('config.php');
$conn = pg_connect("host=".DB_HOSTNAME." dbname=".DB_DATABASE." user=".DB_USERNAME." password=".DB_PASSWORD);
if (!$conn) { echo "Connection failed"; exit; }
$result = pg_query($conn, "SELECT column_name FROM information_schema.columns WHERE table_name = 'harga_terendah_new'");
while ($row = pg_fetch_assoc($result)) {
    echo $row['column_name'] . "\n";
}
?>
