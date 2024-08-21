<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Collect form data
$name = $_POST['name'];
$address = $_POST['address'];
$sex = $_POST['sex'];
$birthdate = $_POST['birthdate'];
$province = $_POST['province'];
$city = $_POST['city'];
$barangay = $_POST['barangay'];
$precinct = $_POST['precinct'];
$email = $_POST['email'];
$fb = $_POST['fb'];
$cp = $_POST['cp'];
$governor = $_POST['governor'];
$congressman = $_POST['congressman'];
$mayor = $_POST['mayor'];

// Load existing spreadsheet or create a new one
$filename = 'skad_data_dummy_iv_080124.xlsx';

if (file_exists($filename)) {
    $spreadsheet = IOFactory::load($filename);
    $worksheet = $spreadsheet->getActiveSheet();
    $row = $worksheet->getHighestRow() + 1; // Get the next empty row
} else {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $row = 1;

    // Add headers
    $worksheet->fromArray([
        'VOTER\'S NAME',
        'VOTER\'S ADDRESS',
        'SEX',
        'BIRTHDATE',
        'PROVINCE',
        'CITY/ MUNICIPALITY',
        'BARANGAY',
        'PRECINCT',
        'EMAIL',
        'FB',
        'CP NO.',
        'GOVERNOR PREFERENCE',
        'SECOND DISTRICT CONGRESSMAN PREFERENCE',
        'MAYOR PREFERENCE'
    ], NULL, 'A' . $row);
    $row++;
}

// Write data to the worksheet
$worksheet->fromArray([
    $name,
    $address,
    $sex,
    $birthdate,
    $province,
    $city,
    $barangay,
    $precinct,
    $email,
    $fb,
    $cp,
    $governor,
    $congressman,
    $mayor
], NULL, 'A' . $row);

// Save the updated spreadsheet
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($filename);

header('Location: list_voters.php');
?>