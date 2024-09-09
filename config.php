<?php
session_start();

$servername = "localhost";
$username = "root"; // change this
$password = ""; // change this
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//server IP
define('SERVER_IP','172.20.100.30:8080');
// Google API Configuration
define('GOOGLE_CLIENT_ID', '4b29d7b56156ef963c1e924c04ad9187');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URL', 'http://localhost/google/google_callback.php');

// Facebook API Configuration
define('FB_APP_ID', '3756658971282539');
define('FB_APP_SECRET', '4b29d7b56156ef963c1e924c04ad9187');
define('FB_REDIRECT_URL', 'http://localhost/facebook/fb_callback.php');
?>
