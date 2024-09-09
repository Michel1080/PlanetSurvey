<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'config.php'; // Include your database configuration file

if (isset($_GET['survey_id']) && is_numeric($_GET['survey_id'])) {
    $survey_id = $_GET['survey_id'];

    // Prepare the statement to fetch the survey name
    $stmt = $conn->prepare("
        SELECT *
        FROM surveys
        WHERE id = ?
    ");
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();
    $survey_result = $stmt->get_result();
    $survey = $survey_result->fetch_assoc();
    $survey_name = $survey['name'] ?? 'Unknown Survey';
    // Prepare the statement to fetch questions and their options
    $stmt = $conn->prepare("
        SELECT q.id AS question_id, q.question, o.id AS option_id, o.option_text
        FROM survey_questions q
        LEFT JOIN question_options o ON q.id = o.question_id
        WHERE q.survey_id = ?
    ");
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();
    $questions = $stmt->get_result();
} else {
    echo "Invalid or missing survey ID.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect voter information
    $voter_name = $_POST['voter_name'];
    $voter_address = $_POST['voter_address'];
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $precinct = $_POST['precinct'];
    $email = $_POST['email'];
    $fb = $_POST['fb'];
    $cp_no = $_POST['cp_no'];

    // Load the existing Excel file or create a new one
    $filePath = 'data.xlsx';
    if (file_exists($filePath)) {
        $spreadsheet = IOFactory::load($filePath);
    } else {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Sheet1');
    }

    $sheet = $spreadsheet->getActiveSheet();

    // Check if the survey name already exists in the header
    $highestColumn = $sheet->getHighestColumn();
    $highestRow = $sheet->getHighestRow();
    $headerRow = 1;

    $surveyColumn = null;
    $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

    for ($col = 1; $col <= $lastColumnIndex; $col++) {
        $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $headerRow;
        $cellValue = $sheet->getCell($cellCoordinate)->getValue();
        if ($cellValue == $survey_name) {
            $surveyColumn = $col;
            break;
        }
    }

    if (!$surveyColumn) {
        // If the survey name doesn't exist, add it to the next column
        $surveyColumn = $lastColumnIndex + 1;
        $surveyCellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($surveyColumn) . $headerRow;
        $sheet->setCellValue($surveyCellCoordinate, $survey_name);
    }

    // Find the next empty row to insert the data
    $nextRow = $highestRow + 1;

    // Insert voter details in the corresponding columns
    $sheet->setCellValue('A' . $nextRow, $voter_name);
    $sheet->setCellValue('B' . $nextRow, $voter_address);
    $sheet->setCellValue('C' . $nextRow, $sex);
    $sheet->setCellValue('D' . $nextRow, $birthdate);
    $sheet->setCellValue('E' . $nextRow, $province);
    $sheet->setCellValue('F' . $nextRow, $city);
    $sheet->setCellValue('G' . $nextRow, $barangay);
    $sheet->setCellValue('H' . $nextRow, $precinct);
    $sheet->setCellValue('I' . $nextRow, $email);
    $sheet->setCellValue('J' . $nextRow, $fb);
    $sheet->setCellValue('K' . $nextRow, $cp_no);

    // Prepare array to store responses for each question
    $responses = [];
    foreach ($_POST['responses'] as $question_id => $response_id) {
        // Get the option text for the response
        $stmt = $conn->prepare("
            SELECT o.option_text
            FROM question_options o
            WHERE o.id = ?
        ");
        $stmt->bind_param("i", $response_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $option = $result->fetch_assoc();
        $response_text = $option['option_text'];
        
        $responses[$question_id] = $response_text;

        // Insert survey responses in the database
        $stmt = $conn->prepare("
            INSERT INTO survey_responses (survey_id, respondent_id, question_id, response)
            VALUES (?, ?, ?, ?)
        ");
        $respondent_id = 1;
        $stmt->bind_param("iiss", $survey_id, $respondent_id, $question_id, $response_text);

        $stmt->execute();
    }

    // Insert responses into the survey column
    foreach ($responses as $question_id => $response_text) {
        // Find the correct column for the question
        $questionColumn = $surveyColumn; // Use the column where survey_name is located
        $questionCellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($questionColumn) . $nextRow;
        $sheet->setCellValue($questionCellCoordinate, $response_text);
    }

    // Save the updated Excel file
    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    header("Location: survey_list.php");

    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .options {
            margin-top: 0.5rem;
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }
        a:hover {
            text-decoration: underline;
        }
        .back-button {
            display: inline-block;
            padding: 0.6rem 1.2rem;
            background-color: #4CAF50;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #45a049;
        }
        .question-section {
            margin-bottom: 1.5rem;
        }
        .question-section label {
            font-weight: 600;
            color: #333;
        }
        .question-section .options {
            margin-top: 0.5rem;
            padding-left: 1rem;
        }
        .question-section .options label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: normal;
        }
        .planlist-back {
            position: fixed;
            float: left;
            left: 20px;
            bottom: 30px;
            width: 50px;
            height: 50px;
        }
        .planlist-back img{
            width: 100%;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Survey Form</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="voter_name">Voter's Name</label>
                <input type="text" id="voter_name" name="voter_name" required>
            </div>
            <div class="form-group">
                <label for="voter_address">Voter's Address</label>
                <input type="text" id="voter_address" name="voter_address" required>
            </div>
            <div class="form-group">
                <label for="sex">Sex</label>
                <input type="text" id="sex" name="sex" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" required>
            </div>
            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" required>
            </div>
            <div class="form-group">
                <label for="city">City/Municipality</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay</label>
                <input type="text" id="barangay" name="barangay" required>
            </div>
            <div class="form-group">
                <label for="precinct">Precinct</label>
                <input type="text" id="precinct" name="precinct" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="fb">Facebook</label>
                <input type="text" id="fb" name="fb" required>
            </div>
            <div class="form-group">
                <label for="cp_no">Contact Number</label>
                <input type="text" id="cp_no" name="cp_no" required>
            </div>
            
            <?php
            $current_question_id = null;
            while ($row = $questions->fetch_assoc()) {
                if ($current_question_id !== $row['question_id']) {
                    if ($current_question_id !== null) {
                        echo '</div>'; 
                        echo '</div>'; 
                    }
                    $current_question_id = $row['question_id'];
                    echo '<div class="question-section">';
                    echo '<label>' . htmlspecialchars($row['question']) . '</label>';
                    echo '<div class="options">';
                }
                if ($row['option_id'] !== null) {
                    echo '<label>';
                    echo '<input type="radio" name="responses[' . htmlspecialchars($row['question_id']) . ']" value="' . htmlspecialchars($row['option_id']) . '" required>';
                    echo htmlspecialchars($row['option_text']);
                    echo '</label>';
                }
            }
            if ($current_question_id !== null) {
                echo '</div></div>';
            }
            ?>

            <input type="submit" value="Submit Responses">
        </form>
        <a href="survey_list.php" class="back-button">Back to Survey List</a>
    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
</body>
</html>
