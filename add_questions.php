

<?php
include 'config.php';

$survey_id = $_GET['survey_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];

    $sql = "INSERT INTO survey_questions (survey_id, question) VALUES ($survey_id, '$question')";
    if ($conn->query($sql) === TRUE) {
        echo "Question added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$questions = $conn->query("SELECT * FROM survey_questions WHERE survey_id = $survey_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Questions</title>
</head>
<body>
    <h1>Add Questions to Survey</h1>
    <form method="post" action="">
        <label for="question">Question:</label>
        <textarea name="question" required></textarea><br><br>
        <input type="submit" value="Add Question">
    </form>
    
    <h2>Current Questions</h2>
    <ul>
        <?php while ($row = $questions->fetch_assoc()) { ?>
            <li><?php echo $row['question']; ?></li>
        <?php } ?>
    </ul>

    <a href="create_survey.php">Create Another Survey</a>
    <a href="collect_responses.php?survey_id=<?php echo $survey_id; ?>">Collect Responses</a>
</body>
</html>
