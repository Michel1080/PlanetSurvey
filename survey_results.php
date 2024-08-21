<?php
include 'config.php'; // Ensure this file sets up $conn correctly

if (isset($_GET['survey_id']) && is_numeric($_GET['survey_id'])) {
    $survey_id = $_GET['survey_id'];

    // Calculate total responses
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total_responses
        FROM survey_responses
        WHERE survey_id = ?
    ");
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();
    $total_responses_result = $stmt->get_result();
    $total_responses_row = $total_responses_result->fetch_assoc();
    $total_responses = $total_responses_row['total_responses'];

    // Calculate results based on response text
    $stmt = $conn->prepare("
        SELECT sq.question, sr.response AS option_text, COUNT(sr.response) AS response_count
        FROM survey_responses sr
        JOIN survey_questions sq ON sr.question_id = sq.id
        WHERE sr.survey_id = ?
        GROUP BY sq.question, sr.response
    ");
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();
    $results = $stmt->get_result();

    // Debug results
    $results_data = [];
    while ($row = $results->fetch_assoc()) {
        $results_data[] = [
            'question' => htmlspecialchars($row['question']),
            'option_text' => htmlspecialchars($row['option_text']),
            'response_count' => htmlspecialchars($row['response_count'])
        ];
    }

    // Store results
    $stmt = $conn->prepare("
        DELETE FROM survey_results WHERE survey_id = ?
    ");
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();

    // Reset result pointer
    $results->data_seek(0); 

    foreach ($results_data as $row) {
        $percentage = $total_responses > 0 ? ($row['response_count'] / $total_responses) * 100 : 0; // Calculate percentage

        $stmt = $conn->prepare("
            INSERT INTO survey_results (survey_id, result_date, question, option_text, percentage)
            VALUES (?, NOW(), ?, ?, ?)
        ");
        $stmt->bind_param("issd", $survey_id, $row['question'], $row['option_text'], $percentage);
        $stmt->execute();
    }

    // Fetch and display stored results
    $stmt = $conn->prepare("
        SELECT result_date, question, option_text, percentage
        FROM survey_results
        WHERE survey_id = ?
    ");
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();
    $results = $stmt->get_result();

    // Store results data for display
    $stored_results_data = [];
    while ($row = $results->fetch_assoc()) {
        $stored_results_data[] = [
            'result_date' => htmlspecialchars($row['result_date']),
            'question' => htmlspecialchars($row['question']),
            'option_text' => htmlspecialchars($row['option_text']),
            'percentage' => number_format($row['percentage'], 2) . '%'
        ];
    }

} else {
    echo "Invalid or missing survey ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 50px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .back-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Survey Results</h1>
        <?php if (!empty($stored_results_data)): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Question</th>
                <th>Option</th>
                <th>Percentage</th>
            </tr>
            <?php foreach ($stored_results_data as $row): ?>
                <tr>
                    <td><?php echo $row['result_date']; ?></td>
                    <td><?php echo $row['question']; ?></td>
                    <td><?php echo $row['option_text']; ?></td>
                    <td><?php echo $row['percentage']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>No results found for this survey.</p>
        <?php endif; ?>
        <a href="create_survey.php" class="back-button">Create Another Survey</a>
    </div>
</body>
</html>
