<?php
require_once '../phpexcelreader/SpreadsheetReader.php';
$targetPath = 'Penjualan 3. MARET 2026 (Salin).xlsx';
$Reader = new SpreadsheetReader($targetPath);
var_dump($Reader->Sheets());
