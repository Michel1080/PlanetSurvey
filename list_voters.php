<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filename = 'skad_data_dummy_iv_080124.xlsx';

if (file_exists($filename)) {
    $spreadsheet = IOFactory::load($filename);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = $worksheet->toArray();
} else {
    die("No voters data found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voters List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #007bff;
            color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .scrollable-table {
            max-height: 600px;
            overflow-y: auto;
            display: block;
        }

        .scrollable-table table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .scrollable-table th,
        .scrollable-table td {
            white-space: nowrap;
        }

        .scrollable-table td {
            border: 1px solid #ddd;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }

        .filter-container {
            text-align: center;
            margin: 20px 0px;
            display: grid;
            gap: 5px;
            grid-template-columns: repeat(7, 1fr);
        }

        .filter-container input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 1px 0px 1px 0px rgba(0, 0, 0, 0.59);
            -webkit-box-shadow: 1px 0px 1px 0px rgba(0, 0, 0, 0.59);
            -moz-box-shadow: 1px 0px 1px 0px rgba(0, 0, 0, 0.59);
        }

        .create-button {
            display: block;
            width: 200px;
            padding: 10px;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
        }

        .create-button:hover {
            background-color: #0056b3;
        }

        .top-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .delete_btn {
            color: #de4242;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="top-head">
            <h1>Voters List</h1>
            <a href="create_voter.php" class="create-button">Create Voter</a>
        </div>
        <div class="filter-container">
            <?php
            for ($i = 0; $i < count($data[0]); $i++) {
                echo "<input type='text' id='filter-$i' placeholder='Filter by " . htmlspecialchars($data[0][$i]) . "' oninput='filterTable()'>";
            }
            ?>
        </div>
        <div class="scrollable-table">
            <table id="votersTable">
                <thead>
                    <tr>
                        <?php
                        $numHeaders = count($data[0]);
                        for ($i = 0; $i < $numHeaders; $i++) {
                            echo "<th>" . htmlspecialchars($data[0][$i]) . "</th>";
                        }
                        ?>
                        <th>Edit</th>
                        <th>Delete</th> <!-- Add Delete Header -->
                    </tr>
                </thead>

                <tbody>
                    <?php
                    for ($i = 1; $i < count($data); $i++) {
                        echo "<tr>";
                        for ($j = 0; $j < count($data[$i]); $j++) {
                            // echo count($data[$i]);
                            // die();
                            echo "<td>" . htmlspecialchars($data[$i][$j]) . "</td>";
                        }
                        $id = $i + 1;
                        echo "<td><a href='edit_voter.php?row=$id'>Edit</a></td>";
                        echo "<td><a class='delete_btn' href='delete_voter.php?row=$id' onclick='return confirm(\"Are you sure you want to delete this voter?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>

    <script>
function filterTable() {
    // Get the table and its rows
    const table = document.getElementById('votersTable');
    const rows = table.querySelectorAll('tbody tr');

    // Get filter inputs excluding the last one (for "Edit" and "Delete" columns)
    const filters = [];
    const numColumns = table.querySelectorAll('thead th').length;
    
    // Check if filter inputs exist
    for (let i = 0; i < numColumns - 2; i++) { // Adjusted for "Edit" and "Delete" columns
        const filterInput = document.getElementById(`filter-${i}`);
        if (filterInput) {
            filters[i] = filterInput.value.toLowerCase();
        } else {
            console.error(`Filter input with ID filter-${i} not found.`);
            filters[i] = ''; // Ensure filters array is properly populated
        }
    }

    // Iterate through rows and apply filters
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let visible = true;

        cells.forEach((cell, index) => {
            if (index < numColumns - 2 && filters[index] && !cell.textContent.toLowerCase().includes(filters[index])) {
                visible = false;
            }
        });

        row.style.display = visible ? '' : 'none';
    });
}

// Call the function after the page has fully loaded
window.onload = function () {
    filterTable(); // Initial filter application
};

    </script>
</body>

</html>