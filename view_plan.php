<?php
// Include database connection file
include 'config.php';

$plan_id = $_GET['id'];

// Fetch plan details
$query = "SELECT * FROM plans WHERE id = $plan_id";
$result = mysqli_query($conn, $query);
$plan = mysqli_fetch_assoc($result);

// Fetch activities
$query = "SELECT * FROM activities WHERE plan_id = $plan_id";
$activities = mysqli_query($conn, $query);

// Fetch performance ratings
$query = "SELECT * FROM performance_ratings WHERE plan_id = $plan_id";
$ratings = mysqli_query($conn, $query);

// Fetch tasks
$query = "SELECT * FROM tasks WHERE plan_id = $plan_id";
$tasks = mysqli_query($conn, $query);

// Fetch teams for assignment
$teams_query = "SELECT * FROM teams";
$teams = mysqli_query($conn, $teams_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Plan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .plan-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 900px;
            width: 100%;
            margin: auto;
        }

        h1 {
            font-size: 2.5rem;
            color: #343a40;
            margin-bottom: 20px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }

        p,
        table {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        h2 {
            font-size: 1.8rem;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background-color: #e9ecef;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        ul li strong {
            /* color: #007bff; */
            text-transform: capitalize;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tr:hover {
            background-color: #e9ecef;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .buttons a,
        .buttons button {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: 600;
        }

        .buttons a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .buttons a.back {
            background-color: #6c757d;
        }

        .buttons a.back:hover {
            background-color: #5a6268;
        }

        .task-form {
            margin-top: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: 1px solid #ddd;
        }

        .task-form h2 {
            font-size: 1.6rem;
            margin-bottom: 20px;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }

        .task-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .task-form select,
        .task-form input[type="text"],
        .task-form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .task-form button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .task-form button:hover {
            background-color: #0056b3;
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
    <div class="plan-container">
        <h1><?php echo htmlspecialchars($plan['name']); ?></h1>
        <p><strong>Vision:</strong> <?php echo nl2br(htmlspecialchars($plan['vision'])); ?></p>
        <p><strong>Mission:</strong> <?php echo nl2br(htmlspecialchars($plan['mission'])); ?></p>
        <p><strong>Strategies:</strong> <?php echo nl2br(htmlspecialchars($plan['strategies'])); ?></p>
        <p><strong>Target Number:</strong> <?php echo $plan['target_number']; ?></p>
        <p><strong>Target Percentage:</strong> <?php echo $plan['target_percentage']; ?>%</p>
        <p><strong>Province:</strong> <?php echo htmlspecialchars($plan['province']); ?></p>
        <p><strong>Region:</strong> <?php echo htmlspecialchars($plan['region']); ?></p>

        <h2>Activities</h2>
        <ul>
            <?php while ($activity = mysqli_fetch_assoc($activities)) { ?>
                <li>
                    <strong><?php echo htmlspecialchars($activity['name']); ?></strong>
                    at <?php echo htmlspecialchars($activity['location']); ?> on
                    <?php echo htmlspecialchars($activity['date']); ?>
                    (Assigned to: <?php echo htmlspecialchars($activity['assigned_person']); ?>, Budget:
                    $<?php echo $activity['budget']; ?>)
                </li>
            <?php } ?>
        </ul>

        <h2>Assigned Tasks</h2>
        <table>
            <tr>
                <th>Task Name</th>
                <th>Assigned Team</th>
                <th>Date</th>
                <th>Location</th>
                <th>Budget</th>
            </tr>
            <?php
            while ($task = mysqli_fetch_assoc($tasks)) {
                $team_query = "SELECT name FROM teams WHERE id = " . intval($task['assigned_team']);
                $team_result = mysqli_query($conn, $team_query);
                $team = mysqli_fetch_assoc($team_result);
                $team_name = $team ? htmlspecialchars($team['name']) : 'Unknown Team';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['name']); ?></td>
                    <td><?php echo $team_name; ?></td>
                    <td><?php echo htmlspecialchars($task['date']); ?></td>
                    <td><?php echo htmlspecialchars($task['location']); ?></td>
                    <td>$<?php echo htmlspecialchars($task['budget']); ?></td>
                </tr>
            <?php } ?>
        </table>


        <!-- <h2>Performance Ratings</h2>
        <table>
            <tr>
                <th>Team Name</th>
                <th>Assigned Tasks</th>
                <th>Performed Tasks</th>
                <th>Rating</th>
            </tr>
            <?php while ($rating = mysqli_fetch_assoc($ratings)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($rating['team_name']); ?></td>
                    <td><?php echo $rating['assigned_tasks']; ?></td>
                    <td><?php echo $rating['performed_tasks']; ?></td>
                    <td><?php echo $rating['rating']; ?></td>
                </tr>
            <?php } ?>
        </table> -->

        <div class="buttons">
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') { ?>
        <form action="delete_plan.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($plan_id); ?>">
            <button type="submit" style="background-color: #dc3545;" class="delete">Delete Plan </button>
        </form>
        <a href='edit_plan.php?id=<?php echo htmlspecialchars($plan_id); ?>'>Edit Plan</a>
    <?php } ?>
    <a href="plan_index.php" class="back">Back to Plans List</a>
</div>

    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
</body>

</html>