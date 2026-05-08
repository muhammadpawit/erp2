<?php
require_once '../phpexcelreader/SpreadsheetReader.php';

$targetPath = 'Penjualan 3. MARET 2026 (Salin).xlsx';
$Reader = new SpreadsheetReader($targetPath);

$sheetCount = count($Reader->sheets());
echo "Sheets count: $sheetCount\n";

for($i=0;$i<$sheetCount;$i++) {
    echo "Sheet $i:\n";
    $Reader->ChangeSheet($i);
    $a = 1;
    foreach ($Reader as $Row) {
        if ($a > 1 && $a < 5) {
            echo "Row $a:\n";
            print_r($Row);
        }
        $a++;
    }
}
