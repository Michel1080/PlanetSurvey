<?php
require 'vendor/autoload.php';

include('config.php');

$row = isset($_GET['row']) ? (int) $_GET['row'] : 0;

if ( $row > 0) {
    $sql = "SELECT name,email,province,city,barangay,precinct,facebook,mp,role From users WHERE id = $row";
    // Execute the query
    $userData = $conn->query($sql)->fetch_assoc();
    $role = $userData['role'];
    // var_dump($userData);exit;
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            margin-top: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        form {
            display: grid;
            gap: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: calc(100% - 22px);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .planlist-back {
            position: fixed;
            float: left;
            left: 20px;
            bottom: 30px;
            width: 50px;
            height: 50px;
        }
        .planlist-back img{
            width: 100%;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1>Edit User</h1>
        <form action="update_user.php" method="post">
            <input type="hidden" name="row" value="<?php echo htmlspecialchars($row); ?>">

            <div class="form-group">
                <label for="name">User's Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
            </div>


            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" id="province" name="province" value="<?php echo htmlspecialchars($userData['province']); ?>">
            </div>

            <div class="form-group">
                <label for="city">City/Municipality:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($userData['city']); ?>">
            </div>

            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" id="barangay" name="barangay" value="<?php echo htmlspecialchars($userData['barangay']); ?>">
            </div>

            <div class="form-group">
                <label for="precinct">Precinct:</label>
                <input type="text" id="precinct" name="precinct" value="<?php echo htmlspecialchars($userData['precinct']); ?>">
            </div>


            <div class="form-group">
                <label for="fb">Facebook:</label>
                <input type="text" id="fb" name="fb" value="<?php echo htmlspecialchars($userData['facebook']); ?>">
            </div>

            <div class="form-group">
                <label for="cp">Cellphone Number:</label>
                <input type="text" id="cp" name="cp" value="<?php echo htmlspecialchars($userData['mp']); ?>">
            </div>
            <?php if($role !== 'admin'){?>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name = "role" style = "width:100px; height:30px;">
                    <option value="user">User</option>   
                    <?php if($_SESSION['user_role'] === 'manager' || $_SESSION['user_role'] === 'chief') {

                    }else{?>
                        <option value="manager">Manager</option>   
                        <option value="chief">Chief</option>   
                    <?php }?>
                    <option value="cordinator">Cordinator</option>   
                    
                </select>
            </div>
            <?php }?>                            
            <button type="submit">Update User</button>
        </form>
    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
</body>

<script>
     document.getElementById("role").value = "<?php echo $role?>";   
</script>
</html>