<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skadoosh Demo Request</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0b1a58;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .navbar {
            background-color: #030b32; /* Dark blue for the navbar */
            padding: 10px 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .navbar .logo img {
            width: 150px;
            max-width: 100%;
            height: auto;
        }

        .navbar .login {
            font-size: 16px;
            text-decoration: none;
            color: white;
        }

        .container {
            width: 100%;
            height: calc(100vh - 60px); /* Adjusted for the navbar height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 0 20px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 48px; /* Large bold heading */
            line-height: 1.2;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        h1 span {
            color: #ff0000;
        }

        h1 span.yellow {
            color: #ffff00;
        }

        .button-container {
            margin-top: 30px;
        }

        .button-container a {
            display: inline-block;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            padding: 15px 30px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .button-container a:hover {
            background-color: white;
            color: #0b1a58;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 12px;
        }

        .footer img {
            width: 40px;
            margin-top: 10px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 36px; /* Smaller heading for tablets */
            }

            .navbar .logo img {
                width: 120px; /* Smaller logo for tablets */
            }

            .navbar .login {
                font-size: 14px; /* Smaller login text for tablets */
            }

            .button-container a {
                padding: 12px 24px; /* Adjusted padding for smaller screens */
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 28px; /* Smaller heading for mobile */
            }

            .navbar {
                flex-direction: column;
                padding: 10px;
            }

            .navbar .logo img {
                width: 100px; /* Smaller logo for mobile */
                margin-bottom: 10px;
            }

            .navbar .login {
                font-size: 14px; /* Keep smaller login text for mobile */
                margin-right: 0;
            }

            .button-container a {
                padding: 10px 20px; /* Adjusted padding for mobile */
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="logo.png" alt="Skadoosh Logo"> <!-- Update the path to your logo -->
    </div>
    <a href="login.php" class="login">Log in</a>
</div>

<div class="container">
    <h1>
        Manage <br>
        Your <span>Campaign</span><br>
        From Your <span class="yellow">Fingertips</span>.
    </h1>
    <div class="button-container">
        <a href="index.php">Request for a demo</a>
    </div>

    <div class="footer">
        <img src="./logo.png" alt="Footer Logo"> <!-- Update the path to your footer logo -->
        <p>Copyright © 2024 Edgepoint Solutions, Inc. All rights reserved.</p>
    </div>
</div>

</body>
</html>
