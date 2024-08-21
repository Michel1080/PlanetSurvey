<?php
require "config.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search query
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch all surveys with search filter
$sql = "SELECT id, name FROM surveys WHERE name LIKE '%$searchTerm%'";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar input[type="text"] {
            width: 30%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        form{
            text-align: left;
        }
        .search-bar input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #2980b9;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
            margin-left: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        ul li {
            background: #ecf0f1;
            margin: 20px 0;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease;
        }
        ul li:hover {
            background-color: #d9dde0;
        }
        ul li a {
            text-decoration: none;
            color: #2980b9;
            font-size: 1.2em;
            display: block;
        }
        ul li a:hover {
            /* color: #1abc9c; */
        }
        .view-button {
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .view-button:hover {
            background-color: #2980b9;
        }
        .top-head{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-head">
        <h1>All Surveys</h1>
        <a href="create_survey.php" class="view-button">Create</a>
        </div>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search surveys..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <input type="submit" value="Search">
            </form>
        </div>
        <ul>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <a href="survey_detail.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                        <a href="survey_detail.php?id=<?php echo $row['id']; ?>" class="view-button">View</a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No surveys found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
