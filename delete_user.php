<?php
require 'vendor/autoload.php';

include('config.php');

if (isset($_GET['row'])) {
    $row = intval($_GET['row']);

    $sql = "UPDATE users SET delete_date = NOW()  WHERE id = $row";
    $result = $conn->query($sql);
    if($result){
        header("Location: list_users.php");
        exit();
    }else{
        die("Invalid user ID.");
    }
   
} else {
    die("No user ID specified.");
}
?>
