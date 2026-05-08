<?php
require_once('config.php');
$conn = pg_connect("host=".DB_HOSTNAME." dbname=".DB_DATABASE." user=".DB_USERNAME." password=".DB_PASSWORD);
if (!$conn) { echo "Connection failed"; exit; }
$result = pg_query($conn, "SELECT schema_name FROM information_schema.schemata");
while ($row = pg_fetch_assoc($result)) {
    echo $row['schema_name'] . "\n";
}
?>
