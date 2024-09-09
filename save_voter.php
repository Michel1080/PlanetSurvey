<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Collect form data
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
 ];

$keys = array_keys($_POST);
$values = array_values($_POST);
for($i=11; $i<count($keys); $i++){
   $voterData[] = $values[$i];
}

// Load existing spreadsheet or create a new one
$filename = 'data.xlsx';

if (file_exists($filename)) {
    $spreadsheet = IOFactory::load($filename);
    $worksheet = $spreadsheet->getActiveSheet();
    $row = $worksheet->getHighestRow() + 1; // Get the next empty row
} else {
    // $spreadsheet = new Spreadsheet();
    // $worksheet = $spreadsheet->getActiveSheet();
    // $row = 1;

    // // Add headers
    // $worksheet->fromArray([
    //     'VOTER\'S NAME',
    //     'VOTER\'S ADDRESS',
    //     'SEX',
    //     'BIRTHDATE',
    //     'PROVINCE',
    //     'CITY/ MUNICIPALITY',
    //     'BARANGAY',
    //     'PRECINCT',
    //     'EMAIL',
    //     'FB',
    //     'CP NO.',
    //     'GOVERNOR PREFERENCE',
    //     'SECOND DISTRICT CONGRESSMAN PREFERENCE',
    //     'MAYOR PREFERENCE'
    // ], NULL, 'A' . $row);
    // $row++;
    return;
}

// Write data to the worksheet
$worksheet->fromArray($voterData, NULL, 'A' . $row);

// Save the updated spreadsheet
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($filename);

header('Location: list_voters.php');
?>