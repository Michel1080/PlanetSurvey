<?php
include('config.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;  // Store the user role in the session

            // Redirect based on user role
            if ($role === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "Email not found. Please register.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        h1 span {
            color: #ff0000;
        }

        h1 span.yellow {
            color: #ffff00;
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
        <h1><span>PULL</span> THE RUG OUT FROM UNDER <span class="yellow">YOUR COMPETITORS</span>!</h1>
        <p>Our face-to-face demo is available for a fee. If you are interested, please fill out the fields below.</p>

        <?php if ($message): ?>
            <p style="color: red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required />
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" required />
                <span class="toggle-password" onclick="togglePassword()"></span>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="social-login">
            <h3>Or login with</h3>
            <a href="google_login.php" class="social-button google-button">Login with Google</a>
            <a href="facebook_login.php" class="social-button facebook-button">Login with Facebook</a>
        </div>

        <div class="switch-page">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
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