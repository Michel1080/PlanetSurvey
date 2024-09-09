<?php
require 'vendor/autoload.php';

include('config.php');

 $sql = "INSERT INTO users (name,email,province,city,barangay,precinct,mp,facebook,role) VALUES ('". $_POST['name']."','". $_POST['email']."','".$_POST['province']."','".$_POST['city']."','".$_POST['barangay']."','".$_POST['precinct']."','".$_POST['cp']."','".$_POST['fb']."','".$_POST['role']."')";

 $result = $conn->query($sql);
if($result)
    header('Location: list_users.php');
else
    die("Failed to create user data.");
?>