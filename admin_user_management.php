<?php
// Include the configuration file and start the session
include('config.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

// Handle user updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $role = $_POST['role'];

        // Update user role in the database
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $user_id);

        if ($stmt->execute()) {
            $message = "User role updated successfully.";
        } else {
            $message = "Failed to update user role.";
        }

        $stmt->close();
    }
}

// Fetch all users
$stmt = $conn->prepare("SELECT id, name, email, role FROM users");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0b1a58;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid white;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #0066cc;
        }

        .edit-form {
            margin: 20px 0;
        }

        .edit-form input[type="text"],
        .edit-form select {
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            outline: none;
        }

        .edit-form button {
            background-color: #0066cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background-color: #004c99;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: white;
        }

        .footer img {
            width: 40px;
            margin-top: 20px;
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
    <h1>Admin - User Management</h1>
    <?php if ($message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <form class="edit-form" method="post" action="">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>" />
                            <select name="role">
                                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                            </select>
                            <button type="submit" name="update_user">Update Role</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
</body>
</html>
