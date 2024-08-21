<?php
require "config.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the survey ID from the URL
$surveyId = $_GET['id'] ?? 0;

// Handle delete request
if (isset($_POST['delete'])) {
    // Delete question options related to this survey
    $deleteOptionsSql = "DELETE qo FROM question_options qo
                         JOIN survey_questions sq ON qo.question_id = sq.id
                         WHERE sq.survey_id = $surveyId";
    $conn->query($deleteOptionsSql);
    
    // Delete questions related to this survey
    $deleteQuestionsSql = "DELETE FROM survey_questions WHERE survey_id = $surveyId";
    $conn->query($deleteQuestionsSql);

    // Now delete the survey
    $deleteSurveySql = "DELETE FROM surveys WHERE id = $surveyId";
    if ($conn->query($deleteSurveySql) === TRUE) {
        echo "<script>alert('Survey deleted successfully.'); window.location.href='survey_list.php';</script>";
    } else {
        echo "<script>alert('Error deleting survey: " . $conn->error . "');</script>";
    }
}


// Fetch survey details
$sql = "SELECT * FROM surveys WHERE id = $surveyId";
$surveyResult = $conn->query($sql);

// Fetch survey questions and options
$sql = "
    SELECT sq.id AS question_id, sq.question, qo.option_text
    FROM survey_questions sq
    LEFT JOIN question_options qo ON sq.id = qo.question_id
    WHERE sq.survey_id = $surveyId
    ORDER BY sq.id, qo.id
";
$questionsResult = $conn->query($sql);

$questions = [];
while ($row = $questionsResult->fetch_assoc()) {
    $questionId = $row['question_id'];
    if (!isset($questions[$questionId])) {
        $questions[$questionId] = [
            'question' => $row['question'],
            'options' => []
        ];
    }
    if (!empty($row['option_text'])) {
        $questions[$questionId]['options'][] = $row['option_text'];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-transform: capitalize;
        }

        p {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 40px;
            color: #7f8c8d;
        }

        h2 {
            color: #34495e;
            font-size: 2em;
            margin-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            text-transform: capitalize;

            background: #ecf0f1;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.1em;
            color: #2c3e50;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        a,
        button {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            padding: 15px 25px;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        a:hover,
        button:hover {
            background-color: #2980b9;
        }

        button {
            background-color: #e74c3c;
            border: none;
        }

        button:hover {
            background-color: #c0392b;
        }

        .optionsList li {
            background: white;
            text-transform: capitalize
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (!empty($surveyResult) && $surveyResult->num_rows > 0): ?>
            <?php $survey = $surveyResult->fetch_assoc(); ?>
            <h1><?php echo htmlspecialchars($survey['name']); ?></h1>
            <p><?php echo htmlspecialchars($survey['description']); ?></p>

            <h2>Questions</h2>
            <ul>
                <?php foreach ($questions as $questionId => $questionData): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($questionData['question']); ?></strong>
                        <?php if (!empty($questionData['options'])): ?>
                            <ul class="optionsList">
                                <?php foreach ($questionData['options'] as $option): ?>
                                    <li><?php echo htmlspecialchars($option); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Add links to view results and responses -->
            <a href="survey_results.php?survey_id=<?php echo $surveyId; ?>" class="view-button">View Results</a>
            <a href="survey_responses.php?survey_id=<?php echo $surveyId; ?>" class="view-button">View Responses</a>
            
            <a href="survey_edit.php?id=<?php echo $surveyId; ?>">Edit Survey</a>
            <a href="survey_form.php?survey_id=<?php echo $surveyId; ?>">Fill Survey</a>
            <form method="POST" style="display: inline;">
                <button type="submit" name="delete">Delete Survey</button>
            </form>
        <?php else: ?>
            <p>Survey not found.</p>
        <?php endif; ?>
    </div>
</body>

</html>