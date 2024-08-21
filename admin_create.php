<?php
include('config.php');

// The new admin details
$name = 'New Admin';
$email = 'admin@example.com';
$password = 'admin@123';

// Generate the bcrypt hash for the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Prepare the SQL statement
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
