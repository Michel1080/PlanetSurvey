<?php
// Include database connection file
include 'config.php';

// Fetch plans
$plans_query = "SELECT * FROM plans";
$plans = mysqli_query($conn, $plans_query);

// Fetch teams
$teams_query = "SELECT * FROM teams";
$teams = mysqli_query($conn, $teams_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plan_id = $_POST['plan_id'];
    $task_name = $_POST['task_name'];
    $task_date = $_POST['task_date'];
    $task_location = $_POST['task_location'];
    $assigned_team = $_POST['assigned_team'];
    $task_budget = $_POST['task_budget'];

    // Insert task into the database
    $insert_query = "INSERT INTO tasks (plan_id, name, date, location, assigned_team, budget) 
                     VALUES ('$plan_id', '$task_name', '$task_date', '$task_location', '$assigned_team', '$task_budget')";
    
    if (mysqli_query($conn, $insert_query)) {
        $message = "Task assigned successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Activity Task</title>
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
        .task-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
        }
        h1 {
            font-size: 2.5rem;
            color: #444;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="date"] {
            padding: 9px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 20px;
            color: #28a745;
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
    <div class="task-container">
        <h1>Assign Activity Task</h1>
        <?php if (isset($message)) { ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php } ?>
        <form action="assign_task.php" method="post">
            <div class="form-group">
                <label for="plan_id">Select Plan:</label>
                <select id="plan_id" name="plan_id" required>
                    <option value="">Select Plan</option>
                    <?php while ($plan = mysqli_fetch_assoc($plans)) { ?>
                        <option value="<?php echo htmlspecialchars($plan['id']); ?>"><?php echo htmlspecialchars($plan['name']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="task_name">Task Name:</label>
                <input type="text" id="task_name" name="task_name" required>
            </div>

            <div class="form-group">
                <label for="task_date">Date:</label>
                <input type="date" id="task_date" name="task_date" required>
            </div>

            <div class="form-group">
                <label for="task_location">Location:</label>
                <input type="text" id="task_location" name="task_location" required>
            </div>

            <div class="form-group">
                <label for="assigned_team">Assign to Team:</label>
                <select id="assigned_team" name="assigned_team" required>
                    <option value="">Select Team</option>
                    <?php while ($team = mysqli_fetch_assoc($teams)) { ?>
                        <option value="<?php echo htmlspecialchars($team['id']); ?>"><?php echo htmlspecialchars($team['name']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="task_budget">Budget:</label>
                <input type="number" id="task_budget" name="task_budget" required>
            </div>

            <button type="submit">Assign Task</button>
        </form>
    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
</body>
</html>
