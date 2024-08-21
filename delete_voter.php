<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$filename = 'skad_data_dummy_iv_080124.xlsx';

if (isset($_GET['row'])) {
    $rowToDelete = intval($_GET['row']);

    if (file_exists($filename)) {
        $spreadsheet = IOFactory::load($filename);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        // Check if the row exists
        if ($rowToDelete > 0 && $rowToDelete < count($data) +1) {
            // Delete the row (index starts from 1)
            $worksheet->removeRow($rowToDelete);
            
            // Save changes
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($filename);
            
            // Redirect to the voters list page
            header("Location: list_voters.php");
            exit();
        } else {
            die("Invalid voter ID.");
        }
    } else {
        die("File not found.");
    }
} else {
    die("No voter ID specified.");
}
?>
