<?php
// Database connection settings
require "config.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form input
    $surveyName = $conn->real_escape_string($_POST['surveyName']);
    $surveyDescription = $conn->real_escape_string($_POST['surveyDescription']);
    $questions = isset($_POST['questions']) ? $_POST['questions'] : [];
    $options = isset($_POST['options']) ? $_POST['options'] : [];

    // Insert survey details into the database
    $sql = "INSERT INTO surveys (name, description) VALUES ('$surveyName', '$surveyDescription')";
    if ($conn->query($sql) === TRUE) {
        $surveyId = $conn->insert_id; // Get the ID of the inserted survey

        // Check if there are questions
        if (is_array($questions) && !empty($questions)) {
            // Insert survey questions into the database
            foreach ($questions as $index => $question) {
                $question = $conn->real_escape_string($question);
                $sql = "INSERT INTO survey_questions (survey_id, question) VALUES ('$surveyId', '$question')";
                if ($conn->query($sql)) {
                    $questionId = $conn->insert_id; // Get the ID of the inserted question

                    // Insert question options into the database
                    if (isset($options[$index])) {
                        foreach ($options[$index] as $option) {
                            $option = $conn->real_escape_string($option);
                            if (!empty($option)) {
                                $sql = "INSERT INTO question_options (question_id, option_text) VALUES ('$questionId', '$option')";
                                $conn->query($sql);
                            }
                        }
                    }
                } else {
                    echo "Error inserting question: " . $conn->error;
                }
            }
            header("Location: survey_list.php");

            echo "Survey and questions saved successfully!";
        } else {
            echo "No questions were provided.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Survey</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #030b32;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section h2 {
            background-color: #2980B9;
            padding: 10px;
            color: white;
            border-radius: 5px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .questions-list {
            margin-top: 20px;
        }

        .question-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            /* align-items: center; */
        }

        div#questionsList .question-item {
            align-items: start;
        }

        input#newQuestion {
            padding: 10px;
        }

        .question-item input {
            flex-grow: 1;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }

        #addQuestionBtn,
        div#questionsList .question-item button {
            background-color: #2980B9;
            color: white;
        }

        .question-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .question-item button:hover {
            background-color: #c82333;
        }

        div#questionsList .question-item .deleteQuestion {
            background-color: #de3f4f !important;
            color: white !important;
        }

        .questions-list .question-item {
            gap: 50px;
            align-items: start;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>Create Survey</h1>
    </div>

    <div class="container">
        <div class="section">
            <h2>Survey Details</h2>
            <form id="surveyForm" method="POST" action="">
                <div class="form-group">
                    <label for="surveyName">Survey Name</label>
                    <input type="text" id="surveyName" name="surveyName" placeholder="Enter survey name" required>
                </div>
                <div class="form-group">
                    <label for="surveyDescription">Survey Description</label>
                    <textarea id="surveyDescription" name="surveyDescription"
                        placeholder="Enter survey description"></textarea>
                </div>

                <div class="section">
                    <h2>Survey Questions</h2>
                    <div class="form-group">
                        <label for="newQuestion">Add New Question</label>
                        <div class="question-item">
                            <input type="text" id="newQuestion" placeholder="Enter question">
                            <button type="button" onclick="addQuestion()" id="addQuestionBtn">Add</button>
                        </div>
                    </div>
                    <div class="questions-list" id="questionsList">
                        <!-- Questions will be added here dynamically -->
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit">Save Survey</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const questionsList = document.getElementById('questionsList');

        function addQuestion() {
            const questionText = document.getElementById('newQuestion').value;
            if (questionText.trim() === '') {
                alert('Please enter a question.');
                return;
            }

            const questionItem = document.createElement('div');
            questionItem.className = 'question-item';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'questions[]';
            input.value = questionText;
            input.readOnly = true;

            const optionContainer = document.createElement('div');
            optionContainer.className = 'options';
            optionContainer.innerHTML = `
        <input type="text" name="options[${questionsList.children.length}][]" placeholder="Option 1">
        <input type="text" name="options[${questionsList.children.length}][]" placeholder="Option 2">
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
        }

        function addOption(button) {
            const optionContainer = button.parentElement;
            const optionCount = optionContainer.querySelectorAll('input[type="text"]').length;
            const newOptionIndex = optionCount;
            const newOption = document.createElement('input');
            newOption.type = 'text';
            newOption.name = `options[${Array.from(optionContainer.parentElement.parentElement.children).indexOf(optionContainer.parentElement)}][${newOptionIndex}]`;
            newOption.placeholder = `Option ${newOptionIndex + 1}`;
            optionContainer.insertBefore(newOption, button);
        }

    </script>

</body>

</html>