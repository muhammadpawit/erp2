<?php
require_once('config.php');
require_once(DIR_SYSTEM . 'library/db.php');
// OpenCart 1.5.x or similar
if (file_exists(DIR_SYSTEM . 'library/db/mysqli.php')) {
    require_once(DIR_SYSTEM . 'library/db/mysqli.php');
}
if (file_exists(DIR_SYSTEM . 'library/db/postgre.php')) {
    require_once(DIR_SYSTEM . 'library/db/postgre.php');
}

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
try {
    $query = $db->query("SELECT * FROM harga_terendah_new LIMIT 5");
    if ($query) {
        echo json_encode($query->rows, JSON_PRETTY_PRINT);
    } else {
        echo "Query returned false";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
