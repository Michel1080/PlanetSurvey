<?php
include 'config.php';

// Check if survey_id is set and is a valid number
if (isset($_GET['survey_id']) && is_numeric($_GET['survey_id'])) {
    $survey_id = $_GET['survey_id'];

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("
        SELECT sq.question, sr.response, sr.created_at, r.name AS respondent
        FROM survey_responses sr
        JOIN respondents r ON sr.respondent_id = r.id
        JOIN survey_questions sq ON sr.question_id = sq.id
        WHERE sr.survey_id = ?
    ");
    $stmt->bind_param("i", $survey_id); // "i" denotes the integer type
    $stmt->execute();
    $responses = $stmt->get_result();
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
    <title>Survey Responses</title>
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
        <h1>Survey Responses</h1>
        <?php if ($responses->num_rows > 0): ?>
        <table>
            <tr>
                <th>Question</th>
                <th>Respondent</th>
                <th>Response</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $responses->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['question']); ?></td>
                    <td><?php echo htmlspecialchars($row['respondent']); ?></td>
                    <td><?php echo htmlspecialchars($row['response']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php else: ?>
            <p>No responses found for this survey.</p>
        <?php endif; ?>
        <a href="survey_list.php" class="back-button">Back to Survey List</a>
    </div>
</body>
</html>

