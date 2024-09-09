<?php
require 'vendor/autoload.php';
include('config.php');
$row = isset($_POST['row']) ? (int) $_POST['row'] : 0;
// var_dump($_POST);exit;
if ($row > 0) {
    try {

        $sql = "UPDATE users SET name = '".$_POST['name']."',email = '".$_POST['email']."',province = '".$_POST['province']."',city = '".$_POST['city']."',barangay = '".$_POST['barangay']."',precinct = '".$_POST['precinct']."',mp = '".$_POST['cp']."',facebook = '".$_POST['fb']."',role = '".$_POST['role']."' where id = ".$row."";
        // echo $sql;exit;
        $result = $conn->query($sql);
        if($result)
            header('Location: list_users.php');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    die("Failed to update user data. Ensure the file exists and the row number is valid.");
}
?>