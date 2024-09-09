<?php
// Include database connection file
include 'config.php';

if (isset($_GET['id'])) {
    $plan_id = $_GET['id'];

    // Fetch the plan details
    $query = "SELECT * FROM plans WHERE id = $plan_id";
    $result = mysqli_query($conn, $query);
    $plan = mysqli_fetch_assoc($result);

    // Fetch the activities associated with this plan
    $query = "SELECT * FROM activities WHERE plan_id = $plan_id";
    $activities = mysqli_query($conn, $query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update plan details
    $name = $_POST['plan_name'];
    $vision = $_POST['vision'];
    $mission = $_POST['mission'];
    $strategies = $_POST['strategies'];
    $target_number = $_POST['target_number'];
    $target_percentage = $_POST['target_percentage'];
    $province = $_POST['province'];
    $region = $_POST['region'];

    $query = "UPDATE plans SET name = '$name', vision = '$vision', mission = '$mission', strategies = '$strategies', target_number = $target_number, target_percentage = $target_percentage, province = '$province', region = '$region', updated_at = NOW() WHERE id = $plan_id";
    mysqli_query($conn, $query);

    // Update activities
    foreach ($_POST['activities'] as $activity_id => $activity) {
        if ($activity_id === 'new') {
            // Insert new activities
            $name = $activity['name'];
            $location = $activity['location'];
            $assigned_person = $activity['assigned_person'];
            $date = $activity['date'];
            $budget = $activity['budget'];

            $query = "INSERT INTO activities (name, location, assigned_person, date, budget, plan_id, created_at, updated_at) VALUES ('$name', '$location', '$assigned_person', '$date', $budget, $plan_id, NOW(), NOW())";
            mysqli_query($conn, $query);
        } else {
            // Update existing activities
            $name = $activity['name'];
            $location = $activity['location'];
            $assigned_person = $activity['assigned_person'];
            $date = $activity['date'];
            $budget = $activity['budget'];

            $query = "UPDATE activities SET name = '$name', location = '$location', assigned_person = '$assigned_person', date = '$date', budget = $budget, updated_at = NOW() WHERE id = $activity_id AND plan_id = $plan_id";
            mysqli_query($conn, $query);
        }
    }

    // Redirect to the view plan page
    header("Location: view_plan.php?id=$plan_id");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Plan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            width: 100%;
        }

        h1 {
            font-size: 2.5rem;
            color: #444;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .activity {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .activity label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .activity input {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        .button-container button {
            margin: 0;
        }

        .add-activity-button {
            background-color: #28a745;
        }

        .add-activity-button:hover {
            background-color: #218838;
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
        <h1>Edit Plan</h1>
        <form method="post" action="edit_plan.php?id=<?php echo $plan_id; ?>">
            <div class="form-group">
                <label for="plan_name">Plan Name:</label>
                <input type="text" id="plan_name" name="plan_name"
                    value="<?php echo htmlspecialchars($plan['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="vision">Vision:</label>
                <textarea id="vision" name="vision" required><?php echo htmlspecialchars($plan['vision']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="mission">Mission:</label>
                <textarea id="mission" name="mission"
                    required><?php echo htmlspecialchars($plan['mission']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="strategies">Strategies:</label>
                <textarea id="strategies" name="strategies"
                    required><?php echo htmlspecialchars($plan['strategies']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="target_number">Target Number:</label>
                <input type="number" id="target_number" name="target_number"
                    value="<?php echo htmlspecialchars($plan['target_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="target_percentage">Target Percentage:</label>
                <input type="number" step="0.01" id="target_percentage" name="target_percentage"
                    value="<?php echo htmlspecialchars($plan['target_percentage']); ?>" required>
            </div>
            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" id="province" name="province"
                    value="<?php echo htmlspecialchars($plan['province']); ?>" required>
            </div>
            <div class="form-group">
                <label for="region">Region:</label>
                <input type="text" id="region" name="region" value="<?php echo htmlspecialchars($plan['region']); ?>"
                    required>
            </div>

            <h2>Activities</h2>
            <div id="activities">
                <?php
                $i = 0;
                while ($activity = mysqli_fetch_assoc($activities)) {
                    ?>
                    <div class="activity">
                        <div>
                            <label for="activity_name_<?php echo $i; ?>">Activity Name:</label>
                            <input type="text" id="activity_name_<?php echo $i; ?>"
                                name="activities[<?php echo $activity['id']; ?>][name]"
                                value="<?php echo htmlspecialchars($activity['name']); ?>" required>
                        </div>
                        <div>
                            <label for="location_<?php echo $i; ?>">Location:</label>
                            <input type="text" id="location_<?php echo $i; ?>"
                                name="activities[<?php echo $activity['id']; ?>][location]"
                                value="<?php echo htmlspecialchars($activity['location']); ?>" required>
                        </div>
                        <div>
                            <label for="assigned_person_<?php echo $i; ?>">Assigned Person:</label>
                            <input type="text" id="assigned_person_<?php echo $i; ?>"
                                name="activities[<?php echo $activity['id']; ?>][assigned_person]"
                                value="<?php echo htmlspecialchars($activity['assigned_person']); ?>" required>
                        </div>
                        <div>
                            <label for="date_<?php echo $i; ?>">Date:</label>
                            <input type="date" id="date_<?php echo $i; ?>"
                                name="activities[<?php echo $activity['id']; ?>][date]"
                                value="<?php echo htmlspecialchars($activity['date']); ?>" required>
                        </div>
                        <div>
                            <label for="budget_<?php echo $i; ?>">Budget:</label>
                            <input type="number" step="0.01" id="budget_<?php echo $i; ?>"
                                name="activities[<?php echo $activity['id']; ?>][budget]"
                                value="<?php echo htmlspecialchars($activity['budget']); ?>" required>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
            <div class="button-container">
                <button type="button" id="add_activity" class="add-activity-button">Add Another Activity</button>
                <button type="submit">Update Plan</button>
            </div>
        </form>
    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
    <script>
        document.getElementById('add_activity').addEventListener('click', function () {
            const activitiesDiv = document.getElementById('activities');
            const activityCount = activitiesDiv.querySelectorAll('.activity').length;
            const newActivityIndex = activityCount;

            const activityHtml = `
              <div class="activity">
    <div class="form-group">
        <label for="activity_name_${newActivityIndex}">Activity Name:</label>
        <input type="text" id="activity_name_${newActivityIndex}" name="activities[new][name]" required>
    </div>
    <div class="form-group">
        <label for="location_${newActivityIndex}">Location:</label>
        <input type="text" id="location_${newActivityIndex}" name="activities[new][location]" required>
    </div>
    <div class="form-group">
        <label for="assigned_person_${newActivityIndex}">Assigned Person:</label>
        <input type="text" id="assigned_person_${newActivityIndex}" name="activities[new][assigned_person]" required>
    </div>
    <div class="form-group">
        <label for="date_${newActivityIndex}">Date:</label>
        <input type="date" id="date_${newActivityIndex}" name="activities[new][date]" required>
    </div>
    <div class="form-group">
        <label for="budget_${newActivityIndex}">Budget:</label>
        <input type="number" step="0.01" id="budget_${newActivityIndex}" name="activities[new][budget]" required>
    </div>
</div>

            `;

            activitiesDiv.insertAdjacentHTML('beforeend', activityHtml);
        });
    </script>
</body>

</html>