<?php
require "config.php";

$surveyId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch survey details
$sql = "SELECT * FROM surveys WHERE id = $surveyId";
$surveyResult = $conn->query($sql);

if ($surveyResult === FALSE) {
    die("Error executing query: " . $conn->error);
}

$survey = $surveyResult->fetch_assoc();

if ($survey === NULL) {
    die("Survey not found.");
}

$surveyName = $survey['name'];
$surveyDescription = $survey['description'];

// Fetch survey questions and options
$sql = "SELECT sq.id AS question_id, sq.question, qd.option_text
        FROM survey_questions sq
        LEFT JOIN question_options qd ON sq.id = qd.question_id
        WHERE sq.survey_id = $surveyId
        ORDER BY sq.id, qd.id";
$questionsResult = $conn->query($sql);

if ($questionsResult === FALSE) {
    die("Error executing query: " . $conn->error);
}

$questions = [];
while ($row = $questionsResult->fetch_assoc()) {
    $questions[$row['question_id']]['question'] = $row['question'];
    $questions[$row['question_id']]['options'][] = $row['option_text'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $surveyName = isset($_POST['surveyName']) ? $conn->real_escape_string($_POST['surveyName']) : '';
    $surveyDescription = isset($_POST['surveyDescription']) ? $conn->real_escape_string($_POST['surveyDescription']) : '';
    $questions = $_POST['questions'] ?? [];
    $options = $_POST['options'] ?? [];

    // Update survey details
    $sql = "UPDATE surveys SET name = '$surveyName', description = '$surveyDescription' WHERE id = $surveyId";
    if ($conn->query($sql) === TRUE) {
        // Process each question
        foreach ($questions as $index => $question) {
            $questionId = isset($_POST['question_ids'][$index]) ? intval($_POST['question_ids'][$index]) : null;
            $questionText = $conn->real_escape_string($question);

            if ($questionId) {
                // Update existing question
                $sql = "UPDATE survey_questions SET question = '$questionText' WHERE id = $questionId";
                if ($conn->query($sql) === FALSE) {
                    die("Error updating question: " . $conn->error);
                }

                // Delete old options
                if ($conn->query("DELETE FROM question_options WHERE question_id = $questionId") === FALSE) {
                    die("Error deleting old options: " . $conn->error);
                }
            } else {
                // Insert new question
                $sql = "INSERT INTO survey_questions (survey_id, question) VALUES ('$surveyId', '$questionText')";
                if ($conn->query($sql)) {
                    $questionId = $conn->insert_id;
                } else {
                    die("Error inserting question: " . $conn->error);
                }
            }

            // Insert new options
            if (isset($options[$questionId])) {
                foreach ($options[$questionId] as $option) {
                    $option = $conn->real_escape_string($option);
                    if (!empty($option)) {
                        $sql = "INSERT INTO question_options (question_id, option_text) VALUES ('$questionId', '$option')";
                        if ($conn->query($sql) === FALSE) {
                            die("Error inserting option: " . $conn->error);
                        }
                    }
                }
            }
        }

        header("Location: survey_detail.php?id=$surveyId");
        exit;
    } else {
        die("Error updating survey: " . $conn->error);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Survey</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .section h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }

        .questions-list {
            margin-top: 20px;
        }

        .question-item {
            display: flex;
            align-items: start;
            margin-bottom: 10px;
        }

        .question-item input {
            flex-grow: 1;
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .question-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .question-item button:hover {
            background-color: #c82333;
        }

        .form-group button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #218838;
        }

        .form-group button.add-question-btn {
            background-color: #FFC107 !important;
            color: black !important;
            /* margin-top: 10px; */
        }

        .form-group button.add-question-btn:hover {
            background-color: #0056b3;
        }

        .options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }

        #addQuestionBtn,
        div#questionsList .question-item button {
            background-color: #FFC107;
            color: black;
        }

        .question-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .question-item .options input {
            padding: 5px;
        }

        .deleteQuestion {
            background-color: #de3f4f !important;
            color: white !important;
        }

        .questions-list .question-item {
            gap: 50px;
            align-items: start;
        }

        .questions-list .question-item .options {
            align-items: start;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 22px;
            }

            .form-group button {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($surveyResult->num_rows > 0): ?>
            <h1>Edit Survey</h1>
            <form id="surveyForm" method="POST" action="">
                <div class="form-group">
                    <label for="surveyName">Survey Name</label>
                    <input type="text" id="surveyName" name="surveyName"
                        value="<?php echo htmlspecialchars($surveyName); ?>" required>
                </div>
                <div class="form-group">
                    <label for="surveyDescription">Survey Description</label>
                    <textarea id="surveyDescription"
                        name="surveyDescription"><?php echo htmlspecialchars($surveyDescription); ?></textarea>
                </div>

                <div class="section">
                    <h2>Survey Questions</h2>
                    <div class="form-group">
                        <div class="question-item">
                            <input type="text" id="newQuestion" placeholder="Enter question">
                            <button type="button" class="add-question-btn" onclick="addQuestion()">Add</button>
                        </div>
                    </div>
                    <div class="questions-list" id="questionsList">
                        <?php foreach ($questions as $questionId => $questionData): ?>
                            <div class="question-item" data-question-id="<?php echo $questionId; ?>">
                                <input type="hidden" name="question_ids[]" value="<?php echo $questionId; ?>">
                                <input type="text" name="questions[]"
                                    value="<?php echo htmlspecialchars($questionData['question']); ?>" readonly>
                                <div class="options">
                                    <?php foreach ($questionData['options'] as $optionIndex => $option): ?>
                                        <input type="text" name="options[<?php echo $questionId; ?>][]"
                                            value="<?php echo htmlspecialchars($option); ?>">
                                    <?php endforeach; ?>
                                    <button type="button" onclick="addOption(this)">Add More Options</button>
                                </div>
                                <button type="button" class="deleteQuestion" onclick="removeQuestion(this)">Delete</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit">Save Survey</button>
                </div>
            </form>
        <?php else: ?>
            <p>Survey not found.</p>
        <?php endif; ?>
    </div>

    <script>
        let questionCount = <?php echo count($questions); ?>;

        function addQuestion() {
            const questionText = document.getElementById('newQuestion').value;
            if (questionText.trim() === '') {
                alert('Please enter a question.');
                return;
            }

            const questionsList = document.getElementById('questionsList');
            const questionItem = document.createElement('div');
            questionItem.className = 'question-item';
            questionItem.setAttribute('data-question-id', questionCount);

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'questions[]';
            input.value = questionText;
            input.readOnly = true;

            const optionContainer = document.createElement('div');
            optionContainer.className = 'options';
            optionContainer.innerHTML = `
                <input type="text" name="options[${questionCount}][0]" placeholder="Option 1">
                <input type="text" name="options[${questionCount}][1]" placeholder="Option 2">
                <button type="button" onclick="addOption(this)">Add More Options</button>
            `;

            const deleteButton = document.createElement('button');
            deleteButton.innerText = 'Delete';
            deleteButton.classList = 'deleteQuestion';
            deleteButton.onclick = () => questionsList.removeChild(questionItem);

            questionItem.appendChild(input);
            questionItem.appendChild(optionContainer);
            questionItem.appendChild(deleteButton);
            questionsList.appendChild(questionItem);

            document.getElementById('newQuestion').value = '';
            questionCount++;
        }

        function addOption(button) {
            const optionContainer = button.parentElement;
            const questionIndex = optionContainer.parentElement.getAttribute('data-question-id');
            const optionCount = optionContainer.querySelectorAll('input[type="text"]').length;
            const newOptionIndex = optionCount;

            const newOption = document.createElement('input');
            newOption.type = 'text';
            newOption.name = `options[${questionIndex}][${newOptionIndex}]`;
            newOption.placeholder = `Option ${newOptionIndex + 1}`;
            optionContainer.insertBefore(newOption, button);
        }

        function removeQuestion(button) {
            button.parentElement.remove();
        }
    </script>
</body>

</html>