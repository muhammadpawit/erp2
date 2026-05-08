<?php
require_once('system/phpexcelreader/SpreadsheetReader.php');

$file = 'system/uploads/tes.xlsx';
// Create a dummy xlsx if needed or use existing one if available
// For now let's just see if it even loads

try {
    $Reader = new SpreadsheetReader($file);
    echo "Sheets: " . count($Reader->sheets()) . "\n";
    $count = 0;
    foreach ($Reader as $Row) {
        $count++;
        if ($count < 5) {
            print_r($Row);
        }
    }
    echo "Total rows: " . $count . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
