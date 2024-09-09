<?php
require 'vendor/autoload.php';
include('config.php');
// Check if a file is uploaded
if (isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile']['tmp_name'];

    // Open the CSV file
    if (($handle = fopen($file, 'r')) !== FALSE) {
        // Skip the first line if it contains headers
        fgetcsv($handle);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, province, city, barangay, precinct) VALUES (?, ?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("ssssss", $name, $email, $province, $ctiy, $barangay, $precinct);
        $i = 0;
        // Read and insert data
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($i === 0) {
                $i++;
                continue;
            }
            $name = $data[0]; // Adjust according to your CSV structure
            $email = $data[1];
            $province = $data[2];
            $ctiy = $data[3];
            $barangay = $data[4];
            $precinct = $data[5];

            $stmt->execute();
        }

        fclose($handle);
        $stmt->close();
        echo "Data successfully uploaded.";
        header("Location: list_users.php");
    } else {
        echo "Error opening the file.";
    }
} else {
    echo "No file uploaded.";
}

?>
