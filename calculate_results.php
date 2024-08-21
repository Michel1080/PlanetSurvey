<?php
include 'config.php';

// Assuming you have a survey_id
$survey_id = $_GET['survey_id'];

// Calculate results
$query = "
    SELECT q.id AS question_id, q.question, o.option_text, COUNT(sr.id) AS response_count
    FROM survey_questions q
    LEFT JOIN survey_responses sr ON q.id = sr.question_id
    LEFT JOIN question_options o ON sr.response = o.id
    WHERE q.survey_id = ?
    GROUP BY q.id, o.option_text
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $survey_id);
$stmt->execute();
$result = $stmt->get_result();

$total_responses = 0;
while ($row = $result->fetch_assoc()) {
    $total_responses += $row['response_count'];
}

// Insert results
$result_date = date('Y-m-d');
$candidate_name = 'Candidate Name'; // Change this as needed

foreach ($result as $row) {
    $percentage = ($row['response_count'] / $total_responses) * 100;

    $stmt = $conn->prepare("
        INSERT INTO survey_results (survey_id, result_date, candidate_name, percentage)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("issi", $survey_id, $result_date, $candidate_name, $percentage);
    $stmt->execute();
}

echo "Results calculated and inserted successfully!";
?>
