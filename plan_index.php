<?php
// Include database connection file
include 'config.php';

// Fetch all plans
$query = "SELECT * FROM plans ORDER BY created_at DESC";
$plans = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            margin: 0px;
            margin-bottom: 20px;
            font-size: 2.5rem;
            color: #444;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a:hover {
            color: #0056b3;
        }

        .plans-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }

        .plans-container ul {
            list-style: none;
            padding: 0;
        }

        .plans-container li {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 1.2rem;
        }

        .plans-container li:last-child {
            border-bottom: none;
        }

        .create-plan {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .create-plan:hover {
            background-color: #0056b3;
            color: white;
        }
        .top-head{
            display: flex;
            justify-content: space-between;
            align-items: center;
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
    <div class="plans-container">
<div class="top-head">
<h1>Plans</h1>
        <?php

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
}else{
    echo "<a class='create-plan' href='create_plan.php'>Create New Plan</a>";
}
?>
</div>


        
        <ul>
            <?php while ($plan = mysqli_fetch_assoc($plans)) { ?>
                <li><a href="view_plan.php?id=<?php echo $plan['id']; ?>"><?php echo htmlspecialchars($plan['name']); ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
</body>
</html>
