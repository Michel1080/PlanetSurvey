<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filename = 'data.xlsx';
$row = isset($_POST['row']) ? (int) $_POST['row'] : 0;

if (file_exists($filename) && $row > 0) {
    try {
        $spreadsheet = IOFactory::load($filename);
        $worksheet = $spreadsheet->getActiveSheet();

        // Collect updated data
        $voterData = [
            $_POST['name'],
            $_POST['address'],
            $_POST['sex'],
            $_POST['birthdate'],
            $_POST['province'],
            $_POST['city'],
            $_POST['barangay'],
            $_POST['precinct'],
            $_POST['email'],
            $_POST['fb'],
            $_POST['cp'],
            $_POST['governor'],
            $_POST['congressman'],
            $_POST['mayor']
        ];

        // Update the row with the new data
        $worksheet->fromArray($voterData, NULL, "A$row");

        // Save the updated spreadsheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Check if file is writable
        if (!is_writable($filename)) {
            throw new Exception("File is not writable: $filename");
        }

        $writer->save($filename);

        header('Location: list_voters.php');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    die("Failed to update voter data. Ensure the file exists and the row number is valid.");
}
?>