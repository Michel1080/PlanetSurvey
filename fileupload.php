<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$target_dir = "";
$static_file_name = "data.xlsx";

$target_file = $target_dir . $static_file_name;
$uploadOk = 1;
$fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "File already exists. Overwriting...";
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

  $existingSpreadsheet = IOFactory::load($target_file);

  // Load the uploaded spreadsheet
  $uploadedSpreadsheet = IOFactory::load($_FILES["fileToUpload"]["tmp_name"]);

  // Get the active sheets
  $existingSheet = $existingSpreadsheet->getActiveSheet();
  $uploadedSheet = $uploadedSpreadsheet->getActiveSheet();

  // Iterate through the uploaded sheet and update the existing sheet
  foreach ($uploadedSheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false); // This will include empty cells

      foreach ($cellIterator as $cell) {
          $existingCell = $existingSheet->getCell($cell->getCoordinate());
          // Check if the value has changed
          if ($existingCell->getValue() !== $cell->getValue()) {
              // Update the existing cell with the new value
              $existingCell->setValue($cell->getValue());
          }
      }
  }
 
  // Save the updated existing spreadsheet
  $writer = IOFactory::createWriter($existingSpreadsheet, $fileType == "xlsx" ? 'Xlsx' : 'Xls');
  $writer->save($target_file);
  // echo "dddddd!!!";exit;
  echo "The file " . htmlspecialchars($static_file_name) . " has been updated with new values.";
  header("Location: dashboard.php"); // Redirect to a dashboard or another page
  exit(); // Ensure no further code is executed
}
?>