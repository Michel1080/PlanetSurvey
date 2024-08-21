<?php
include('config.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Email already exists. Please choose a different email.";
    } else {
        // Check password strength
        if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
            $message = "Password must be at least 8 characters long, contain at least one uppercase letter, and one number.";
        } else {
            // Password is strong, hash it using bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            max-width: 600px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .logo img {
            width: 150px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 36px;
            color: white;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 40px;
            font-size: 18px;
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-size: 18px;
            background-color: #d9d9d9;
            color: black;
        }

        button[type="submit"] {
            background-color: #0066cc;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            max-width: 400px;
        }

        button[type="submit"]:hover {
            background-color: #004c99;
        }

        .social-login {
            text-align: center;
            margin-top: 20px;
            width: 100%;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .social-button {
            margin: 5px 0;
            padding: 15px;
            font-size: 18px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            color: white;
        }

        .google-button {
            background-color: #db4437;
        }

        .facebook-button {
            background-color: #3b5998;
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

        .password-container {
            position: relative;
            width: 100%;
        }
        
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #000;
        }

        .switch-page {
            margin-top: 20px;
            font-size: 16px;
            color: white;
        }

        .switch-page a {
            color: #00aaff;
            text-decoration: none;
        }

        .switch-page a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>
    <h1>Register</h1>
    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="name" placeholder="Full Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Password" required />
            <span class="toggle-password" onclick="togglePassword()"></span>
        </div>
        <button type="submit">Register</button>
    </form>

    <div class="social-login">
        <h3>Or register with</h3>
        <a href="google_login.php" class="social-button google-button">Register with Google</a>
        <a href="facebook_login.php" class="social-button facebook-button">Register with Facebook</a>
    </div>

    <div class="switch-page">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <div class="footer">
        <p>&copy; 2024 Edgepoint Solutions, Inc. All rights reserved.</p>
    </div>
</div>

<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>

</body>
</html>
