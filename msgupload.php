<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file has been uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploadDir = 'uploads/'; // Directory where you want to save the file
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        // Check if the uploads directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo "File is successfully uploaded.";
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "No file uploaded or there was an upload error.";
    }
} else {
    echo "Invalid request.";
}
?>
