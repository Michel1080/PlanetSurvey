<?php
include('config.php');
session_start();

if (!isset($_GET['link'])) {
    die('Survey link not provided.');
}

$survey_link = $_GET['link'];

// Fetch the survey details using the survey link
$stmt = $conn->prepare("SELECT id, survey_name FROM surveys WHERE survey_link = ?");
stmt->bind_param("s", $survey_link);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($survey_id, $survey_name);
$stmt->fetch();

if ($stmt->num_rows == 0) {
    die('Invalid survey link.');
}

// Fetch the questions for this survey
$stmt = $conn->prepare("SELECT id, question_text FROM questions WHERE survey_id = ?");
$stmt->bind_param("i", $survey_id);
$stmt->execute();
$questions_result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $responses = $_POST['responses'];
    $user_id = $_SESSION['user_id'];

    // Insert each response into the survey_responses table
    foreach ($responses as $question_id => $answer) {
        $stmt = $conn->prepare("INSERT INTO survey_responses (survey_id, user_id, question_id, answer) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $survey_id, $user_id, $question_id, $answer);
        $stmt->execute();
    }

    // Redirect to a thank-you page or display a success message
    header("Location: thank_you.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($survey_name); ?></title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($survey_name); ?></h1>
    <form method="post">
        <?php while ($question = $questions_result->fetch_assoc()): ?>
            <div class="form-group">
                <label for="question_<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['question_text']); ?></label>
                <input type="text" id="question_<?php echo $question['id']; ?>" name="responses[<?php echo $question['id']; ?>]" required>
            </div>
        <?php endwhile; ?>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
