<?php
// Database connection
require "config.php";

// Get filter criteria from URL parameters
$municipal = $_GET['municipal'] ?? '';
$barangay = $_GET['barangay'] ?? '';
$precinct = $_GET['precinct'] ?? '';
$provincial = $_GET['provincial'] ?? '';

// Prepare the SQL query
$query = "SELECT * FROM plans WHERE 1=1";

if ($municipal) {
    $query .= " AND city = ?";
}
if ($barangay) {
    $query .= " AND barangay = ?";
}
if ($precinct) {
    $query .= " AND precinct = ?";
}
if ($provincial) {
    $query .= " AND provincial = ?";
}

// Prepare statement
$stmt = $conn->prepare($query);

// Bind parameters if necessary
$params = [];
$types = '';
if ($municipal) { $params[] = $municipal; $types .= 's'; }
if ($barangay) { $params[] = $barangay; $types .= 's'; }
if ($precinct) { $params[] = $precinct; $types .= 's'; }
if ($provincial) { $params[] = $provincial; $types .= 's'; }

if ($params) {
    $stmt->bind_param($types, ...$params);
}

// Execute statement and fetch data
$stmt->execute();
$result = $stmt->get_result();
$plans = $result->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Plans</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 30px;
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 1.1em;
        }
        li:last-child {
            border-bottom: none;
        }
        p {
            text-align: center;
            color: #e74c3c;
            font-size: 1.2em;
        }
        a{
            font-size: 36px;
    text-transform: capitalize;
    text-decoration: none;
        }
        a:hover{
    text-decoration: underline;

        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Filtered Plans</h1>
        <?php if (!empty($plans)): ?>
            <ul>
                <?php foreach ($plans as $plan): ?>
                    <li><a href="view_plan.php?id=<?php echo $plan['id']; ?>"><?php echo htmlspecialchars($plan['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No plans found matching the criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>
