// collect_responses.php

<?php
include 'config.php';

$survey_id = $_GET['survey_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $respondent_name = $_POST['name'];
    $respondent_city = $_POST['city'];

    $conn->query("INSERT INTO respondents (name, city) VALUES ('$respondent_name', '$respondent_city')");
    $respondent_id = $conn->insert_id;

    foreach ($_POST['responses'] as $question_id => $response) {
        $conn->query("INSERT INTO survey_responses (survey_id, respondent_id, question_id, response) VALUES ($survey_id, $respondent_id, $question_id, '$response')");
    }

    echo "Thank you for your response!";
    exit;
}

$questions = $conn->query("SELECT * FROM survey_questions WHERE survey_id = $survey_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collect Responses</title>
</head>
<body>
    <h1>Survey</h1>
    <form method="post" action="">
        <label for="name">Your Name:</label>
        <input type="text" name="name" required><br><br>

        <label for="city">City:</label>
        <input type="text" name="city"><br><br>

        <?php while ($row = $questions->fetch_assoc()) { ?>
            <label><?php echo $row['question']; ?></label><br>
            <textarea name="responses[<?php echo $row['id']; ?>]" required></textarea><br><br>
        <?php } ?>

        <input type="submit" value="Submit Responses">
    </form>
</body>
</html>
